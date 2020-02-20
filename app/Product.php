<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'section', 'category', 'manufacturer','name','model','description','price','warranty','stock'
    ];
}
