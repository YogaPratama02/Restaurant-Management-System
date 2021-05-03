<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'name', 'discount', 'status'
    ];

    protected $hidden = [];

    public function sale()
    {
        return $this->hasMany('App\Sale');
    }
}
