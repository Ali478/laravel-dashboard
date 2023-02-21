<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function isExits($product){   
        $exits = Cart::where('product_id', $product->id)->first();
        return $exits;
    }

    public function carts(){
        
        $carts = Cart::where('user_id', auth()->id())->get();
        return $carts;   
    }
    

}
