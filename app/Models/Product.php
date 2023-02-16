<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\Category;


class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'product';
    public $incrementing = false;
    public $timestamps = false;
    public function getProduct(){
        try {
            $result = $this->get();
            $result = Product::join('categories', 'product.category', '=', 'categories.id')->get(['product.*', 'categories.category_name']);
            if (count($result)){
                return $result;
            }
            return null;
        }catch (QueryException $ex){
            Log::info('ProductModel Error',['getProfileData'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return null;
        }
    }
    public function deleteProduct($id){
        try {
            $result = $this->where('id',$id)->delete();
            if ($result){
                return $result;
            }
            return null;
        }catch (QueryException $ex){
            Log::info('ProductModel Error',['deleteProduct'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return null;
        }
    }
    public function addProduct($name,$category,$price,$quantity,$code){
        try {
            $this->name = $name;
            $this->category = $category;
            $this->price = $price;
            $this->qnt = $quantity;
            $this->created_at = now();
            $this->updated_at = now();
            $this->code = $code;

            if ($this->save()){
                return true;
            }
            return false;
        }catch (QueryException $ex){
            Log::info('ProductModel Error',['AddNewProduct'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return false;
        }
    }
    public function updateProduct($id,$name,$category,$price,$quantity){
        try {
            $data = array(
                    'name' => $name,
                    'category' => $category,
                    'price' => $price,
                    'qnt' => $quantity,
                    'updated_at' => now()
                );
            if ($this->where('id', $id)->update($data)){
                return true;
            }
            return false;
        }catch (QueryException $ex){
            Log::info('UserModel Error',['profileUpdate'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return false;
        }
    }
}
