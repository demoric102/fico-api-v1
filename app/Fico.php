<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fico extends Model
{
    protected $fillable = ['id', 'fico_id', 'email', 'status', 'created_at', 'updated_at'];
    
    protected $table = 'fico';

    public function user()
    {
        return $this->belongsTo('App\User', 'email');
    }
}


