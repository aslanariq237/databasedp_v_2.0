<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Receive;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ReceiveDetail;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    private string $codeInv = 'INV'; 
    private string $companyCode = 'DataPrint';
    private array $romanMonths = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
        5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
        9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::query()
            ->with(['receive', 'customer'])
            ->latest('issue_at');

        // Filter Code
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        // Filter Customer (nama atau company)
        if ($request->filled('customer')) {
            $customerSearch = $request->customer;
            $query->where(function ($q) use ($customerSearch) {
                $q->whereHas('customer', function ($qc) use ($customerSearch) {
                    $qc->where('name', 'like', '%' . $customerSearch . '%')
                    ->orWhere('company', 'like', '%' . $customerSearch . '%');
                })
                ->orWhere('customer_name', 'like', '%' . $customerSearch . '%')
                ->orWhere('customer_company', 'like', '%' . $customerSearch . '%');
            });
        }

        // Filter Issue At (rentang tanggal)
        if ($request->filled('issue_from')) {
            $query->whereDate('issue_at', '>=', $request->issue_from);
        }
        if ($request->filled('issue_to')) {
            $query->whereDate('issue_at', '<=', $request->issue_to);
        }

        // Filter Due At (rentang tanggal)
        if ($request->filled('due_from')) {
            $query->whereDate('due_at', '>=', $request->due_from);
        }
        if ($request->filled('due_to')) {
            $query->whereDate('due_at', '<=', $request->due_to);
        }

        $invoices = $query->paginate(15)->appends($request->query());

        return view('container.finance.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $receives = Receive::where('has_invoice', false)->get(); // hanya yang belum ada invoice
        return view('container.finance.invoices.form', compact('receives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Invoice store payload', $request->all()); // debug

        $request->validate([
            'receive_id' => 'required|exists:receive,receive_id',
            'selected_details' => 'required|array|min:1',
            'selected_details.*' => 'integer',
            'details.*.price' => 'required|numeric|min:0',
            'details.*.status' => 'required|in:Selesai,Ditolak,Penawaran',
        ]);

        $receive = Receive::with('details', 'details.customer')->findOrFail($request->receive_id);

        // Pastikan semua selected_details benar-benar milik receive ini
        $validDetailIds = $receive->details->pluck('id_receive_details')->toArray();

        $invalidIds = array_diff($request->selected_details, $validDetailIds);
        if (!empty($invalidIds)) {
            return back()->with('error', 'Beberapa item tidak valid atau bukan milik receive ini.');
        }

        try {
            DB::beginTransaction();

            // Ambil customer dari detail pertama
            $firstDetail = $receive->details()->find($request->selected_details[0]);

            $invoice = Invoice::create([
                'receive_id' => $receive->receive_id,
                'customer_id' => $firstDetail->customer_id,
                'customer_name' => $firstDetail->customer_id 
                    ? $firstDetail->customer->name 
                    : $firstDetail->customer_name,
                'customer_company' => $firstDetail->customer_id
                    ? $firstDetail->customer->company
                    : '',
                'kode_toko' => $firstDetail->customer_id
                    ?$firstDetail->customer->code
                    :'',
                'code' => $this->generateInvoiceCode($receive),
                'status_payment' => 'unpaid',
                'jasa_service' => $receive->jasa_service ?? 0,
                'deposit' => 0,
                'issue_at' => now(),
                'due_at' => now()->addDays(40),
                'sub_total' => 0,
                'ppn' => 0,
                'grand_total' => 0,
            ]);

            $subTotal = 0;
            foreach ($request->selected_details as $detailId) {
                $input = $request->details[$detailId];
                $receiveDetail = $receive->details()->findOrFail($detailId); // pasti ketemu karena sudah divalidasi

                InvoiceDetail::create([
                    'invoice_id' => $invoice->invoice_id,
                    'id_receive_details' => $detailId,
                    'product_id' => $receiveDetail->product_id,
                    'product_name' => $receiveDetail->product_name,
                    'serial_number' => $input['serial_number'] ?? $receiveDetail->serial_number,
                    'status' => $input['status'],
                    'price' => $input['price'],
                ]);

                $subTotal += $input['price'];
            }
            $sub_barang = $subTotal;
            $sub_service = $invoice->jasa_service;

            $ppn = $sub_barang + $sub_service * 0.11;
            $grandTotal = $sub_barang + $sub_service + $ppnq;

            $invoice->update([
                'sub_total' => $subTotal,
                'ppn' => $ppn,
                'grand_total' => $grandTotal,
            ]);

            // Tandai receive sudah punya invoice
            $receive->update(['has_invoice' => true]);

            DB::commit();

            return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Invoice creation failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }    

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('details', 'receive');
        return view('container.finance.invoices.view', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    private function getCustomerInitial(Receive $receive): string
    {
        // Ambil detail pertama
        $detail = $receive->details->first();

        if (!$detail) {
            return '-';
        }

        // Ambil nama customer
        if (!empty($detail->customer_id) && $detail->customer) {
            $name = $detail->customer->name;
        } else {
            $name = $detail->customer_name;
        }

        if (empty($name)) {
            return '-';
        }

        // Ubah ke uppercase
        $name = strtoupper($name);

        // Hilangkan teks setelah "/"
        $name = explode('/', $name)[0];

        // Kata yang diabaikan
        $ignoredWords = ['PT', 'CV', 'TBK', 'UD'];

        // Bersihkan & pecah kata
        $words = preg_split('/\s+/', preg_replace('/[^A-Z\s]/', '', $name));

        $initials = '';

        foreach ($words as $word) {
            if (!in_array($word, $ignoredWords) && strlen($word) > 0) {
                $initials .= $word[0];
            }
        }

        return $initials ?: '-';
    }



    private function generateInvoiceCode($receive): string
    {
        $now = now();

        $count = Invoice::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count() + 1;

        $index = str_pad($count, 3, '0', STR_PAD_LEFT);

        $customerInitial = $this->getCustomerInitial($receive);
        $romanMonth = $this->romanMonths[$now->month];

        return "{$index}/{$this->companyCode}/{$customerInitial}/{$romanMonth}/{$now->year}";
    }
}
