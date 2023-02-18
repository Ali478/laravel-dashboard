<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionDetail;

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
    

}
    