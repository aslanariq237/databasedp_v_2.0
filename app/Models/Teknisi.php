<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    protected $table = 'teknisi';
    protected $primaryKey = 'teknisi_id';
    protected $fillable = [
        'name',
        'status'
    ];

    public function receiveDetails(){
        return $this->hasMany(ReceiveDetail::class, 'teknisi_id');
    }
}
