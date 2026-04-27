<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use App\Models\DetailSuratJalan;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\ReceiveDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratJalanController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratJalan::query();

        // 🔍 Search (kode, nama, produk, serial)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhereHas('details', function ($qd) use ($search) {
                      $qd->where('product_name', 'like', "%{$search}%")
                         ->orWhere('serial_number', 'like', "%{$search}%");
                  });
            });
        }

        // 📅 Filter dari tanggal
        if ($request->filled('from_date')) {
            $query->whereDate('issue_at', '>=', $request->from_date);
        }

        // 📅 Filter sampai tanggal
        if ($request->filled('to_date')) {
            $query->whereDate('issue_at', '<=', $request->to_date);
        }

        // 📌 Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 📄 Ambil data + pagination
        $suratjalans = $query
            ->orderBy('issue_at', 'desc')
            ->paginate(10);

        return view('container.finance.suratjalan.index', compact('suratjalans'));
    }

    public function show($suratjalan_id)
    {
        $suratjalan = SuratJalan::with(['details.invoice', 'customer'])->findOrFail($suratjalan_id);

        return view('container.finance.suratjalan.show', compact('suratjalan'));
    }

    public function create()
    {
        $invoices = Invoice::where('has_sj', 0)
            ->with(['customer', 'details.receiveDetail'])
            ->latest()
            ->get();

        return view('container.finance.suratjalan.form', [
            'isEdit' => false,
            'invoices' => $invoices,
            'suratjalan' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_ids' => 'required|array',           
            'code' => 'required|string|max:50|unique:suratjalan,code',
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,sented,cancelled',
            'issue_at' => 'required|date',
            'due_at' => 'nullable|date|after_or_equal:issue_at',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Ambil semua invoice yang dipilih
                $selectedInvoices = Invoice::with(['details.receiveDetail', 'customer'])
                    ->whereIn('invoice_id', $validated['invoice_ids'])
                    ->get();                

                if ($selectedInvoices->isEmpty()) {
                    throw new \Exception('Tidak ada invoice yang valid.');
                }

                // Pastikan semua invoice dari customer yang sama (opsional, bisa dihapus kalau boleh beda customer)
                // $customerId = $selectedInvoices->first()->customer_id;
                // if ($selectedInvoices->pluck('customer_id')->unique()->count() > 1) {
                //     throw new \Exception('Semua invoice harus dari customer yang sama.');
                // }

                // Buat header SJ
                $sj = SuratJalan::create([
                    'customer_id' => 1,
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'status' => $validated['status'],
                    'issue_at' => $validated['issue_at'],
                    'due_at' => $validated['due_at'] ?? null,
                ]);

                // Loop semua invoice & semua detailnya
                foreach ($selectedInvoices as $invoice) {
                    foreach ($invoice->details as $detail) {
                        $receive = $detail->receiveDetail->first();
                        if (!$receive) continue;

                        DetailSuratJalan::create([
                            'suratjalan_id' => $sj->suratjalan_id,
                            'invoice_id' => $invoice->invoice_id,
                            'id_invoice_detail' => $detail->id_invoice_detail,
                            'id_receive_details' => $receive->id_receive_details,
                            'customer_id' => $invoice->customer_id,
                            'product_name' => $receive->product_name,
                            'serial_number' => $receive->serial_number,
                        ]);

                        $receive->update(['has_sj' => 1]);
                    }

                    $invoice->update(['has_sj' => 1]);
                }
            });
        DB::commit();
        return redirect()->route('surat-jalan.index')
            ->with('success', 'Surat Jalan berhasil dibuat untuk multiple invoice!');

        } catch ( \Exception $e) {
            DB::rollback();
            return response()->json([
                'message'   => 'Failed Create Surat Jalan',
                'error'     => $e->getMessage()
            ], 403);
        }
    }

    public function edit($suratjalan_id)
    {
        $suratjalan = SuratJalan::with('details')->findOrFail($suratjalan_id);

        // Ambil ID invoice yang sudah dipakai di SJ ini
        $selectedInvoiceIds = $suratjalan->details->pluck('invoice_id')->unique()->toArray();

        // Ambil semua invoice yang bisa ditambah (has_sj = 0) + yang sudah dipakai
        $invoices = Invoice::with(['customer'])
            ->where(function ($q) use ($selectedInvoiceIds) {
                $q->where('has_sj', 0)
                ->orWhereIn('invoice_id', $selectedInvoiceIds);
            })
            ->latest()
            ->get();

        return view('container.finance.suratjalan.form', [
            'isEdit' => true,
            'suratjalan' => $suratjalan,
            'invoices' => $invoices,
            'selectedInvoiceIds' => $selectedInvoiceIds,
        ]);
}

    public function update(Request $request, $suratjalan_id)
    {
        $sj = SuratJalan::findOrFail($suratjalan_id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:suratjalan,code,' . $sj->suratjalan_id . ',suratjalan_id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,sented,cancelled',
            'issue_at' => 'required|date',
            'due_at' => 'nullable|date|after_or_equal:issue_at',
        ]);

        $sj->update($validated);

        return redirect()->route('surat-jalan.index')
            ->with('success', 'Surat Jalan berhasil diupdate!');
    }
}