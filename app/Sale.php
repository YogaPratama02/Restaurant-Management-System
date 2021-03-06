<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'table_id', 'voucher_id', 'user_id', 'user_name', 'customer_name', 'customer_phone', 'total_hpp', 'total_price', 'total_vat', 'total_vatprice', 'total_received', 'change', 'payment_type', 'sale_status'
    ];
    public function saleDetails()
    {
        return $this->hasMany('App\SaleDetail');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function voucher()
    {
        return $this->belongsTo('App\Voucher');
    }
}
