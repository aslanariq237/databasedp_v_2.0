<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    protected $table = 'suratjalan';
    protected $primaryKey = 'suratjalan_id';
    protected $fillable = [
        'customer_id',
        'code',
        'name',
        'status',
        'issue_at',
        'due_at'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function details()
    {
        return $this->hasMany(DetailSuratJalan::class, 'suratjalan_id', 'suratjalan_id');
    }
}
