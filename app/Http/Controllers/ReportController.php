<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teknisi;
use App\Models\ReceiveDetail;
use App\Models\Product;

class ReportController extends Controller
{
    //index
    public function indexTeknisi(){

        $teknisis = Teknisi::withCount(['receiveDetails as total_barang' => function ($query) {
            $query->select(\DB::raw('count(*)'));
        }])->get();

        return view('container.report.report_teknisi', compact('teknisis'));
    }

    // detail
    public function detailTeknisi($teknisi_id){

        $teknisi = Teknisi::findOrFail($teknisi_id);
    
        $details = ReceiveDetail::where('teknisi_id', $teknisi_id)
            ->with('product') 
            ->get()
            ->groupBy('product_id');
        
        return view('container.report.detail_teknisi', compact('teknisi', 'details'));
    }   
    
    public function indexTransaksi(){
        //
    }
}
