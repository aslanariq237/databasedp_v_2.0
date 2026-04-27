<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiveDetail extends Model
{
    protected $table = 'receivedetail';
    protected $primaryKey = 'id_receive_details';
    protected $fillable = [
        'receive_id',
        'product_id',
        'customer_id',
        'teknisi_id',
        'product_name',
        'serial_number',
        'customer_name',
        'customer_company',
        'price',
        'status',
        'has_customer',
        'has_garansi',
        'has_sj',
    ];

    public function Receive(){
        return $this->belongsTo(Receive::class, 'receive_id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function keluhans(){
        return $this->hasMany(KeluhanDetail::class, 'id_receive_details');
    }
    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function teknisi(){
        return $this->belongsTo(Teknisi::class, 'teknisi_id');
    }
}
