<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class category extends Model
{
    protected $table = 'categories';
    public $incrementing = false;
    public $timestamps = false;
    public function getCategory(){
        try {
            $result = $this->get();
            if (count($result)){
                return $result;
            }
            return null;
        }catch (QueryException $ex){
            Log::info('ProductModel Error',['getProfileData'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return null;
        }
    }
    public function deleteCategory($id){
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
    public function addCategory($name){
        try {
            $this->category_name = $name;
            $this->created_at = now();
            $this->updated_at = now();
            if ($this->save()){
                return true;
            }
            return false;
        }catch (QueryException $ex){
            Log::info('UserModel Error',['profileUpdate'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return false;
        }
    }
    public function updateCategory($id,$name){
        try {
            $data = array(
                    'category_name' => $name,
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
