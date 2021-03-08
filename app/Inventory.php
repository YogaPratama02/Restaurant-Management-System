<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //
    protected $fillable = [
        'ingredients', 'stock_quantity'
    ];

    protected $hidden = [
        //
    ];
    
    public function inventorymenu(){
        return $this->belongsToMany(InventoryMenu::class);
    }
    public function menus(){
        return $this->belongsToMany(Menu::class);
    }
}
