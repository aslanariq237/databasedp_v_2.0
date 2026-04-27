<?php

namespace App\Http\Controllers;

use App\Models\Receive;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\ReceiveDetail;

class TransactionController extends Controller
{
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

        return view('container.finance.admin.index', compact('receives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('container.finance.admin.form');
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
    public function show(string $id)
    {
        $receive = Receive::with([
            'details.keluhans',                       
            'details.customer',                       
        ])        
        ->findOrFail($id);         

        // return response()->json($receive);
        return view('container.finance.admin.show', compact('receive'));
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
}
