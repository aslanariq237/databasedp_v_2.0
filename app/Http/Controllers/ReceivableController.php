<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class ReceivableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('receive')
            ->whereIn('status_payment', ['unpaid', 'partial'])
            ->latest();            

        if ($request->filled('search')) {
            $search = $request->search;
            $searchType = $request->input('search_type');

            $query->where(function ($q) use ($search, $searchType) {
                if (!$searchType || $searchType === 'all') {
                    $q->where('code', 'like', "%{$search}%")                    
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
        
        $invoices = $query
            ->orderByDesc('issue_at')
            ->paginate(10)
            ->appends($request->query());

        return view('container.finance.accountReceivable.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('details', 'receive');
        return view('container.finance.accountReceivable.view', compact('invoice'));
    }

    public function bayar(Request $request, Invoice $invoice)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0|max:' . $invoice->grand_total,
        ]);

        $jumlahBayar = $request->jumlah_bayar;
        $grandTotal = $invoice->grand_total;

        $newStatus = 'unpaid';
        if ($jumlahBayar >= $grandTotal) {
            $newStatus = 'full'; // Lunas
        } elseif ($jumlahBayar > 0) {
            $newStatus = 'partial'; // Sebagian
        }

        $invoice->update([
            'status_payment' => $newStatus,
            'deposit'        => $jumlahBayar,
        ]);

        $statusText = $newStatus == 'full' ? 'Lunas' : ($newStatus == 'partial' ? 'Sebagian' : 'Belum Bayar');

        return back()->with('success', "Pembayaran Rp " . number_format($jumlahBayar, 0, ',', '.') . " berhasil. Status invoice: {$statusText}");
    }
}
