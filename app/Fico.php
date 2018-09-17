<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fico extends Model
{
    protected $fillable = ['id', 'fico_id', 'email', 'created_at', 'updated_at'];
    
    protected $table = 'fico';
}
