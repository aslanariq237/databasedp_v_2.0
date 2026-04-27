<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';
    protected $fillable = [
        'receive_id',
        'customer_id',
        'status_payment',
        'code',
        'customer_name',
        'customer_company',
        'kode_toko',
        'sub_total',
        'ppn',
        'grand_total',
        'jasa_service',
        'has_jasa_service',
        'deposit',
        'has_sj',
        'issue_at',
        'due_at'        
    ];

    public function details(){
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }
    public function receive(){
        return $this->belongsTo(Receive::class, 'receive_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }    
}
