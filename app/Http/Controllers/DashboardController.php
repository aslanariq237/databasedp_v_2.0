<?php

namespace App\Http\Controllers;

use App\Models\Receive;
use App\Models\Invoice;
use App\Models\SuratJalan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Receive
        $totalReceive = Receive::count();
        $receiveSudahSJ = Receive::whereHas('details', function ($q) {
            $q->where('has_sj', 1);
        })->count();
        $receiveBelumSJ = $totalReceive - $receiveSudahSJ;

        // Total Invoice
        $totalInvoice = Invoice::count();
        $invoiceSudahSJ = Invoice::where('has_sj', 1)->count();
        $invoiceBelumSJ = $totalInvoice - $invoiceSudahSJ;

        // Total Surat Jalan
        $totalSuratJalan = SuratJalan::count();
        $sjSent = SuratJalan::where('status', 'sent')->count();
        $sjDraft = SuratJalan::where('status', 'draft')->count();

        return view('container.dashboards.index', compact(
            'totalReceive', 'receiveSudahSJ', 'receiveBelumSJ',
            'totalInvoice', 'invoiceSudahSJ', 'invoiceBelumSJ',
            'totalSuratJalan', 'sjSent', 'sjDraft'
        ));
    }
}