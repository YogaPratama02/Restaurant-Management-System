<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $fillable = [
        'name', 'hpp', 'price', 'image', 'discount', 'category_id', 'description'
    ];

    protected $hidden = [
        //
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventorymenus()
    {
        return $this->belongsToMany(InventoryMenu::class);
    }
    // public function inventories(){
    //     return $this->belongsToMany(Inventory::class);
    // }
    public function saleDetail()
    {
        return $this->hasMany('App\SaleDetail');
    }
}
