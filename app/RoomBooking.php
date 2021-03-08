<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    //

    protected $fillable = [
        'room_id', 'start', 'end', 'price'
    ];

    protected $hidden = [
        //
    ];

    public function table(){
        return $this->belongsTo(Table::Class);
    }
}
