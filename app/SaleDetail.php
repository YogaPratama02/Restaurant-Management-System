<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $fillable = [
        'sale_id', 'menu_id', 'menu_name', 'menu_price', 'quantity', 'menu_discount', 'status'
    ];

    public function sale()
    {
        return $this->belongsTo('App\Sale');
    }
    // public function salez()
    // {
    //     return $this->hasMany('App\Sale');
    // }

    public function menu()
    {
        return $this->belongsTo('App\Menu');
    }
}
