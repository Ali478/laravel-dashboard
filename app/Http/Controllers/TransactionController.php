<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactions;

    private $transactiondetail;
    public function __construct(Transaction $transaction, TransactionDetail $transactionDetail)
    {
        $this->transactions = $transaction;
        $this->transactiondetail = $transactionDetail;
    }


    public function index(){
        $transactions = Transaction::latest()->get();

        return view('transactions.index', compact('transactions'));
    }
    public function store(Request $request){
        $params = $request->all();

        $transaction = \DB::transaction(function() use ($params) {
            
            $transactionParams = [
                'transaction_code' => 'P100' . mt_rand(1,1000),
                'name' => auth()->user()->name,
                'total_price' => $params['total'],
                'accept' => $params['accept'],
                'return' => $params['return'],
			];

			$transaction = Transaction::create($transactionParams);

			$carts = Cart::all();

			if ($transaction && $carts) {
				foreach ($carts as $cart) {

                    $itemBaseTotal = $cart->quantity * $cart->price;

					$orderItemParams = [
						'transaction_id' => $transaction->id,
						'product_id' => $cart->product_id,
						'qty' => $cart->quantity,
                        'name' => $cart->name,
						'base_price' => $cart->price,
						'base_total' => $itemBaseTotal,
					];

					$orderItem = TransactionDetail::create($orderItemParams);
					
					if ($orderItem) {
						$product = Product::findOrFail($cart->product_id);
						$product->qnt -= $cart->quantity;
						$product->save();
                    }
                    
                    $cart->delete();
				}
            }
            
            return $transaction;
        });


        
		if ($transaction) {
			return redirect()->route('pos.index', $transaction->id)->with([
				'message' => 'Success order',
				'alert-type' => 'success'
			]);
		}
    }  
    
    
    public function show(Transaction $transaction){
        return view('transactions.show', compact('transaction'));
    }



    public function destroy(Transaction $transaction){

        
        
         $this->transactiondetail->deletetrans($transaction->id);

        $this->transactions->deletetransaction($transaction->id);

        return redirect()->back()->with([
            'message' => 'success delete',
            'alert-type' => 'danger'
        ]);
    
    }

    public function printpage(Transaction $transaction){        
        
        return view('transactions.printpage', compact('transaction'));
    }
}
