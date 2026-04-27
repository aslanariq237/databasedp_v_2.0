<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluhanDetail extends Model
{
    protected $table = 'keluhandetail';
    protected $primaryKey = 'id_keluhan_detail';
    protected $fillable = [
        'id_receive_details',
        'receive_id',
        'keluhan_id',
        'keluhan',
        'has_done',
        'issue_at',
        'price'
    ];

    public function details(){
        return $this->belongsTo(ReceiveDetail::class, 'id_receive_details');
    }

    public function receive(){
        return $this->belongsTo(Receive::class, 'receive_id');
    }

    public function keluhan(){
        return $this->belongsTo(Keluhan::class, 'keluhan_id');
    }
}
