<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryMenu extends Model
{
    protected $fillable = [
       'inventory_id', 'menu_id', 'consumption'
    ];

    protected $hidden = [
        //
    ];

    // public function menu(){
    //     return $this->belongsToMany(Menu::class);
    // }
    public function inventory(){
        return $this->belongsTo(Inventory::class);
    }
    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
