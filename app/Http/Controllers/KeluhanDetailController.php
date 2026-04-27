<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Receive;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Teknisi;
use App\Models\Keluhan;
use App\Models\ReceiveDetail;
use App\Models\KeluhanDetail;

class KeluhanDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Receive::query()
            ->with(['details', 'keluhan'])
            ->withCount('keluhanDetails')
            ->latest();

        // Pencarian teks + tipe
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

        // Filter Status Keluhan
        if ($request->filled('status_keluhan')) {
            $query->whereHas('keluhan', function ($qk) use ($request) {
                $qk->where('status', $request->status_keluhan);
            });
        }

        // Filter Punya Keluhan atau Tidak
        if ($request->filled('punya_keluhan')) {
            if ($request->punya_keluhan === 'yes') {
                $query->whereHas('keluhan');
            } elseif ($request->punya_keluhan === 'no') {
                $query->has('keluhan', '=', 0);
            }
        }

        $receives = $query
            ->orderByDesc('issue_at')
            ->paginate(10)
            ->appends($request->query());

        return view('container.operation.keluhan.index', compact('receives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Receive $receive)
    {
        //        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Receive $receive)
    {
        $request->validate([
            'id_receive_details' => 'required|exists:receivedetail,id_receive_details',
            'teknisi_id' => 'required|exists:teknisi,teknisi_id',
            'keluhans' => 'required|array|min:1',
            'keluhans.*.keluhan' => 'required|string',
            'keluhans.*.price' => 'numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $detail = ReceiveDetail::findOrFail($request->id_receive_details);
            $detail->update(['teknisi_id' => $request->teknisi_id]);
            
            $incomingKeluhans = collect($request->keluhans)->keyBy('id_keluhan_detail');

            $detail->keluhans()
                ->whereNotIn('id_keluhan_detail', $incomingKeluhans->keys()->filter())
                ->delete();

            foreach ($request->keluhans as $kel) {
                KeluhanDetail::updateOrCreate(
                    ['id_keluhan_detail' => $kel['id_keluhan_detail'] ?? null],
                    [
                        'receive_id' => $request->receive_id,
                        'id_receive_details' => $detail->id_receive_details,
                        'keluhan_id' => $kel['keluhan_id'] ?? null,
                        'keluhan' => $kel['keluhan'],
                        'price' => 5000,
                        'has_done' => $kel['has_done'] ?? false,
                        'issue_at' => now(),
                    ]
                );
            }

            $detailSubTotal = $detail->keluhans()->sum('price');

            $detail->update([
                'price' => $detailSubTotal
            ]);
            
            $receive = Receive::findOrFail($request->receive_id);

            $subTotal = KeluhanDetail::where('receive_id', $receive->receive_id)->sum('price');
            if($subTotal != 0){
                $subTotal = $subTotal;
            }else{
                $subTotal = 5000;
            }            
            $ppnRate = $receive->ppn_rate ?? 11;
            $ppn = ($subTotal * $ppnRate) / 100;
            $grandTotal = $subTotal + $ppn;

            $receive->update([
                'sub_total'   => $subTotal,
                'ppn'         => $ppn,
                'grand_total' => $grandTotal,
            ]);
            
            $totalKeluhan = $detail->keluhans()->count();
            $doneKeluhan  = $detail->keluhans()->where('has_done', true)->count();

            if ($totalKeluhan > 0 && $totalKeluhan === $doneKeluhan) {
                $detail->update(['status' => 'selesai']);
            } else {
                $detail->update(['status' => 'penawaran']);
            }

            DB::commit();
            
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receive $receive)
    {
        $receive->load('details.customer');
        $teknisi = Teknisi::all();
        $keluhan = Keluhan::all();        
        // return response()->json($receive);
        return view('container.operation.keluhan.form', compact('receive', 'teknisi', 'keluhan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    //method lainnya
    public function getKeluhanData(Request $request)
    {
        $detailId = $request->detail_id;
        $keluhans = KeluhanDetail::where('id_receive_details', $detailId)
                                ->get(['id_keluhan_detail', 'keluhan_id', 'keluhan', 'price', 'has_done']);

        return response()->json($keluhans);
    }
}
