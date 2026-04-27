<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use App\Models\Teknisi;
use App\Models\ReceiveDetail;

class DocumentController extends Controller
{
    public function invoicePrint(Invoice $invoice){
        $invoice->load(['details', 'details.keluhan']);
                
        $pdf = Pdf::loadView('document.invoice', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'dpi' => 150,
            ]);
        
        $filename = 'invoice-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $invoice->code) . '.pdf';
        // return $pdf->stream('invoice-' . $invoice->code . '.pdf');
        return $pdf->stream($filename);
    }

    public function kwitansiPrint(Invoice $invoice){
        $invoice->load(['details',]);
                
        $pdf = Pdf::loadView('document.kwitansi', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'dpi' => 150,
            ]);
        $filename = 'kwitansi-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $invoice->code) . '.pdf';
        // return $pdf->stream('kwitansi-' . $invoice->code . '.pdf');
        return $pdf->stream($filename);
    }

    public function penawaraPrint(ReceiveDetail $receivedetail){
        $receivedetail->load(['keluhans', 'customer', 'Receive']);
        // return response()->json($receivedetail);
        $pdf = Pdf::loadView('document.sph', compact('receivedetail'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'dpi' => 150,
            ]);
        $filename = 'penawaran-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $receivedetail->Receive->code) . '.pdf';
        return $pdf->stream($filename);
    }

    public function TeknisiPrint(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil semua teknisi
        $teknisis = Teknisi::all();

        // Siapkan data detail per teknisi (mirip seperti di show)
        $reportData = [];

        foreach ($teknisis as $teknisi) {
            $query = ReceiveDetail::where('teknisi_id', $teknisi->teknisi_id)
                ->with('product');

            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }

            $details = $query->get()->groupBy('product_id');

            if ($details->isNotEmpty()) {
                $reportData[$teknisi->teknisi_id] = [
                    'teknisi' => $teknisi,
                    'details' => $details
                ];
            }
        }

        $pdf = PDF::loadView('document.teknisi', compact('reportData', 'startDate', 'endDate'))
                  ->setPaper('a4', 'landscape'); // Landscape agar muat tabel lebar

        $fileName = 'Laporan_Kinerja_Teknisi_' . 
                    ($startDate ?? 'awal') . '_sd_' . 
                    ($endDate ?? 'sekarang') . '.pdf';

        return $pdf->stream($fileName);
    }
}
