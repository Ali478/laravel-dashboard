<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionDetail;
use App\Models\Cart;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory;
    
    protected $table = 'transactions';
    protected $guarded = ['id','created_at', 'updated_at'];

    public function transaction_details(){
        return $this->hasMany(TransactionDetail::class);
    }
    public function deletetransaction($id)
	{

		return $this->where('id',$id)->delete();;
	}

    public function addtransaction($params){
        
        try{
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
            
            return $transaction;
    
            }
            catch (QueryException $ex){
                Log::info('TransactionModel Error',['AddNew Transation'=>$ex->getMessage(),'line'=>$ex->getLine()]);
                return false;
            }

}
        
    
    

}
