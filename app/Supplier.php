<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'date', 'name', 'total'
    ];

    protected $hidden = [
        //
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
