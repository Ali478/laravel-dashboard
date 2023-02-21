<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;


class PosController extends Controller
{
    public function index(){

        $products = Product::all();

        return view('cart.index', compact('products'));
    }   
}
