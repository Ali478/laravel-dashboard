<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

use function PHPUnit\Framework\isNull;

class CartController extends Controller
{
    protected $cart;
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }
   
    public function index()
    {
        $carts = Cart::all();
        

        return response()->json([
            'status' => 200,
            'carts' => $carts,
            'message' => 'success',
        ]);
    }

    public function create(): View
    {
        return view('carts.create');
    }

    public function store(Request $request)
    {
        $request->productId ? $product = Product::findOrFail($request->productId) :
        $product = Product::where('code', $request->productCode)->first();
        if($product === null){
            return response()->json([
                'status' => 500,
                'message' => 'product not found !'
            ]);
        }

        $isExist = $this->cart->isExits($product);

        $carts =  $this->cart->carts();
        
		$itemQuantity = 0;
		if ($carts) {
			foreach ($carts as $cart) {
				if ($cart->name == $product->name) {
					$itemQuantity = $cart->quantity;
					break;
				}
			}
        }

        if ($product->qnt < $itemQuantity || $product->qnt < 1) {
            return response()->json([
                'status' => 400,
                'message' => 'product is empty'
            ]);
        }

        if($isExist) {
            return response()->json([
                'status' => 400,
                'message' => 'product is already added'
            ]);
        }else {
            $carts = Cart::updateOrCreate([
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => 1,
                'stock' => $product->qnt,
                'name' => $product->name,
                'user_id' => auth()->id(),
            ]);
    
            return response()->json([
                'status' => 200,
                'carts' => $carts,
                'message' => 'success'
            ]);
        }
    }

    public function show(Cart $cart): View
    {
        return view('carts.show', compact('cart'));
    }

    public function edit(Cart $cart): View
    {
        return view('carts.edit', compact('cart'));
    }

    public function update(Request $request, Cart $cart)
    {
        $product = Product::findOrFail($cart->product_id);

        $carts = Cart::where('user_id', auth()->id())->get();

		$itemQuantity = 0;
		if ($carts) {
			foreach ($carts as $cart) {
				if ($cart->name == $product->name) {
					$itemQuantity = $cart->quantity;
					break;
				}
			}
        }

        if ($product->qnt <= $itemQuantity && $request->qty > $product->qnt ) {
            return response()->json([
                'status' => 400,
                'message' => 'product is limit'
            ]);
        }

        $cart->update(['quantity' => $request->qty]);
    
        return response()->json([
            'status' => 200,
            'message' => 'success'
        ]);
        
       
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return response()->json([
            'status' => 200,
            'message' => 'success'
        ]);
    }

   

    public function scan(Request $request){
        $product = Product::where('code', $request->productCode)->first();
        dd($product);

        $isExist = Cart::where('product_id', $product->id)->first();

        $carts = Cart::where('user_id', auth()->id())->get();

		$itemQuantity = 0;
		if ($carts) {
			foreach ($carts as $cart) {
				if ($cart->name == $product->name) {
					$itemQuantity = $cart->quantity;
					break;
				}
			}
        }

        if ($product->quantity < $itemQuantity || $product->quantity < 1) {
            return response()->json([
                'status' => 400,
                'message' => 'product is empty'
            ]);
        }

        if($isExist) {
            return response()->json([
                'status' => 400,
                'message' => 'product is already added'
            ]);
        }else {
            $carts = Cart::updateOrCreate([
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => 1,
                'stock' => $product->quantity,
                'name' => $product->name,
                'user_id' => auth()->id(),
            ]);
    
            return response()->json([
                'status' => 200,
                'carts' => $carts,
                'message' => 'success'
            ]);
        }
    }
}
