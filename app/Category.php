<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    protected $hidden = [];

    public function menu()
    {
        return $this->hasMany(Menu::class);
    }
}
