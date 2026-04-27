<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receive extends Model
{
    protected $table = 'receive';
    protected $primaryKey = 'receive_id';
    protected $fillable = [
        'name',
        'code',
        'status_payment',        
        'sub_total',
        'ppn',
        'grand_total',
        'deposit',
        'jasa_service',
        'has_pajak_service',
        'has_invoice',
        'has_faktur',
        'has_sj',
        'issue_at',
        'due_at',
    ];
    protected $casts = [
        'issue_at' => 'datetime',
        'due_at'   => 'datetime',
    ];
    public function details(){
        return $this->hasMany(ReceiveDetail::class, 'receive_id');
    }

    public function keluhan(){
        return $this->hasMany(KeluhanDetail::class, 'receive_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function penawaran(){
        return $this->belongsTo(Penawaran::class, 'receive_id');
    }

    public function keluhanDetails()
    {
        return $this->hasManyThrough(KeluhanDetail::class, ReceiveDetail::class, 'receive_id', 'id_receive_details');
    }
}
