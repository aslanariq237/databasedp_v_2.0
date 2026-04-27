<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSuratJalan extends Model
{
    protected $table = 'detailsuratjalan';
    protected $primaryKey = 'id_surat_jalan';
    protected $fillable = [
        'suratjalan_id',
        'invoice_id',
        'id_invoice_detail',
        'id_receive_details',
        'customer_id',
        'product_name',
        'serial_number'
    ];

    public function suratjalan(){
        return $this->belongsTo(SuratJalan::class, 'suratjalan_id');
    }
    
    public function invoice(){
        return $this->belongsTo(Invoice::class, 'invoice_id');        
    }

    public function detail(){
        return $this->belongsTo(ReceiveDetail::class, 'id_receive_details');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
