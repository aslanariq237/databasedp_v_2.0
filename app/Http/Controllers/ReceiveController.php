<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receive;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ReceiveDetail;
use App\Models\KeluhanDetail;
use Illuminate\Support\Facades\DB;


class ReceiveController extends Controller
{
    private string $table = 'receive';
    protected float $ppn_service = 0.02;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Receive::query()
            ->with(['details'])
            ->latest();

        // Search teks + tipe (tetap seperti sebelumnya)
        if ($request->filled('search')) {
            $search = $request->search;
            $searchType = $request->input('search_type');

            $query->where(function ($q) use ($search, $searchType) {
                if (!$searchType || $searchType === 'all') {
                    $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhereHas('details', function ($qd) use ($search) {
                        $qd->where('product_name', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%");
                    });
                } elseif ($searchType === 'code') {
                    $q->where('code', 'like', "%{$search}%");
                } elseif ($searchType === 'name') {
                    $q->where('name', 'like', "%{$search}%");
                } elseif ($searchType === 'product_name') {
                    $q->whereHas('details', function ($qd) use ($search) {
                        $qd->where('product_name', 'like', "%{$search}%");
                    });
                } elseif ($searchType === 'serial_number') {
                    $q->whereHas('details', function ($qd) use ($search) {
                        $qd->where('serial_number', 'like', "%{$search}%");
                    });
                }
            });
        }

        // Filter Tanggal
        if ($request->filled('from_date')) {
            $query->whereDate('issue_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('issue_at', '<=', $request->to_date);
        }

        // Filter Status Garansi (BARU - GANTI DARI STATUS PEMBAYARAN)
        if ($request->filled('garansi')) {
            if ($request->garansi === 'yes') {
                $query->whereHas('details', function ($qd) {
                    $qd->where('has_garansi', 1);
                });
            } elseif ($request->garansi === 'no') {
                $query->whereHas('details', function ($qd) {
                    $qd->where('has_garansi', 0);
                });
            }
        }

        $receives = $query
            ->orderByDesc('issue_at')
            ->paginate(10)
            ->appends($request->query());

        return view('container.operation.receives.index', compact('receives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {                
        return view('container.operation.receives.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:receive,code',
            'issue_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:issue_at',
            'details' => 'required|array|min:1',           
        ]);

        $ppnRate = $request->input('ppn_rate', 11);

        try {
            DB::beginTransaction();
            $receive = Receive::create(array_merge($request->except('details', 'ppn_rate'), [
                'sub_total' => 0,
                'ppn' => 0,
                'jasa_service' => 0,
                'grand_total' => 0,
                'unpaid'=> 'unpaid',
            ]));

            foreach ($request->details as $detail) {
                $customerId = isset($detail['customer_id']) && $detail['customer_id'] !== '' ? $detail['customer_id'] : null;
                $hasCustomer = $customerId ? 1 : 0;
                    
                if ($customerId && empty($customerName)) {  
                    // Optional: fetch customer name from customers table if model exists
                    // $cust = Customer::find($customerId);
                    // $customerName = $cust ? $cust->name : null;
                }

                ReceiveDetail::create([
                    'receive_id'     => $receive->receive_id,
                    'product_id'     => $detail['product_id'] ?? null,
                    'product_name'   => $detail['product_name'],
                    'serial_number'  => $detail['serial_number'],
                    'customer_id'    => $customerId,
                    'customer_name'  => $detail['customer_name'],
                    'price'          => $detail['price'] ?? 0,
                    'status'         => 'pending',
                    'has_customer'   => $hasCustomer,
                    'has_garansi'    => $detail['has_garansi'] ?? 0,
                    'teknisi_id'     => $detail['teknisi_id'] ?? null,
                ]);
            }   

            $this->calculateTotals($receive, (float)$ppnRate);
            DB::commit();
            return redirect()->route('receives.index')->with('success', 'Penerimaan barang berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message'   => 'Failed to Created Customer',
                'error'     => $e->getMessage()
            ], 403);
        }

        return redirect()->route('receives.index')->with('success', 'Penerimaan barang berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $receive = Receive::with(['details.customer'])->findOrFail($id);
        // return response()->json($receive);
        return view('container.operation.receives.show', compact('receive'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receive $receive)
    {           
        return view('container.operation.receives.form', compact('receive'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receive $receive)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:receive,code,' . $receive->receive_id . ',receive_id',
            'issue_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:issue_at',
            'details' => 'required|array|min:1',
            'details.*.product_name' => 'required_without:details.*.product_id',
            'details.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Update header receive
            $receive->update($request->except('details'));

            foreach ($request->details as $detail) {

                $customerId = !empty($detail['customer_id']) ? $detail['customer_id'] : null;

                $payload = [
                    'receive_id'       => $receive->receive_id,
                    'product_id'       => $detail['product_id'] ?? null,
                    'product_name'     => $detail['product_name'],
                    'customer_id'      => $customerId,
                    'customer_name'    => $detail['customer_name'] ?? null,
                    'customer_company' => $detail['customer_company'] ?? null,
                    'price'            => $detail['price'],
                    'status'           => $detail['status'] ?? 'pending',
                    'has_customer'     => $customerId ? 1 : 0,
                    'has_garansi'      => $detail['has_garansi'] ?? 0,
                    'teknisi_id'       => $detail['teknisi_id'] ?? null,
                ];
                
                if (!empty($detail['id_receive_details'])) {
                    ReceiveDetail::where('id_receive_details', $detail['id_receive_details'])
                        ->where('receive_id', $receive->receive_id)
                        ->update($payload);
                }                 
                else {
                    ReceiveDetail::create($payload);
                }
            }
            
            $this->calculateTotals($receive, (float) $request->input('ppn_rate', 11));

            DB::commit();

            return redirect()
                ->route('receives.index')
                ->with('success', 'Penerimaan barang berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receive $receive)
    {
        $receive->details()->delete();
        $receive->delete();
        return back()->with('success', 'Data dihapus.');
    }

    // methods lainnya

    public function editService(Receive $receive)
    {
        $receive->load('details');
        return view('container.finance.admin.form', compact('receive'));
    }

    public function updateService(Request $request, Receive $receive)
    {        
        $request->validate([
            'jasa_service' => 'required|numeric|min:0',
            'keluhan.*.price' => 'required|numeric|min:0',
        ]);
        $this->calculateService($receive, $request);
        try {
            DB::beginTransaction();
            $this->calculateService($receive, $request);

            foreach($request->keluhan as $keluhanId => $data) {
                $keluhan = KeluhanDetail::findOrFail($keluhanId);
                $keluhan->update(['price' => $data['price']]);
            }

            $receiveDetails = $receive->details()->with('keluhans')->get();

            foreach ($receiveDetails as $detail) {
                $subTotalDetail = $detail->keluhans->sum('price');

                $detail->update([
                    'price' => $subTotalDetail
                ]);
            }

            $subTotalReceive = $receiveDetails->sum('price');
            $lastCalculate = $subTotalReceive + $receive->jasa_service;
            $ppn = round($lastCalculate * (11.0 / 100.0), 2);
            $receive->update([
                'sub_total' => $subTotalReceive,
                'grand_total' => $subTotalReceive + $receive->jasa_service
            ]);
            DB::commit();
            return back()->with('success', 'Harga keluhan & sub total berhasil diupdate.');            
        } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Update Service failed: ' . $e->getMessage());
                return back()->with('error', 'Gagal Meng update service: ' . $e->getMessage());
        }        
    }

    protected function calculateTotals(Receive $receive, float $ppnRate = 11.0): array
    {        
        $subTotal = (float) $receive->details()->sum('price');

        $ppn = round($subTotal * ($ppnRate / 100.0), 2);

        $grandTotal = round($subTotal + $ppn, 2);

        $receive->update([
            'sub_total'     => $subTotal,
            'ppn'           => $ppn,
            'grand_total'   => $grandTotal,
            'deposit'       => 0,
            'jasa_service'  => 0,
        ]);

        return [
            'sub_total' => $subTotal,
            'ppn' => $ppn,
            'grand_total' => $grandTotal,
        ];
    }

    protected function calculateService(Receive $receive, $request){
        $jasaServiceValue = $request->jasa_service;        

        $receive->update([
            'jasa_service' => $jasaServiceValue
        ]);
    }
}
