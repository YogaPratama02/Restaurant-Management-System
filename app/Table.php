<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [

    ];

    public function roombookings(){
        return $this->hasMany(RoomBooking::Class);
    }
}
