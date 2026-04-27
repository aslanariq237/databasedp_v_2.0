<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoicedetail';
    protected $primaryKey  = 'id_invoice_detail';
    protected $fillable = [
        'invoice_id',
        'id_receive_details',
        'product_id',
        'product_name',
        'serial_number',
        'status',
        'price',
    ];

    public function invoice(){
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id','id');
    }
    public function Keluhan(){
        return $this->hasMany(KeluhanDetail::class, 'id_receive_details');
    }
    public function receiveDetail(){
        return $this->hasMany(ReceiveDetail::class, 'id_receive_details', 'id_receive_details');
    }
}
