<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $transactions;

    protected $transactiondetail;
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

    try{
        $params = $request->all();
        $transaction= $this->transactions->addtransaction($params);
     
		if ($transaction) {
			return redirect()->route('transactions.show', $transaction->id)->with([
				'message' => 'Success order',
				'alert-type' => 'success'
			]);
		}
    return redirect()->back()->with(['status'=>false,'message'=>'Add Failed']);
        }catch (\Exception $ex){
            Log::info('TransactionController', ['Store Transaction'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }

    }
    public function show(Transaction $transaction){
        return view('transactions.show', compact('transaction'));
    }



    public function destroy(Transaction $transaction){

        try {
            if($transaction){
                $this->transactiondetail->deletetrans($transaction->id);
                $this->transactions->deletetransaction($transaction->id);

                return redirect()->back()->with(['message' => 'success delete','alert-type' => 'danger']);
            }

        }catch (\Exception $ex){
            Log::info('TransactionController', ['Delete Transaction'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }        
        
        
    
    }

    public function printpage(Transaction $transaction){        
        
        return view('transactions.printpage', compact('transaction'));
    }


}
