<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{private $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    public function getDashboard(){
        try {
            $result = $this->category->getCategory();
            if (isset($result) && !empty($result)){
                return view('dashboard')->with(['data'=>$result]);
            }
            return view('dashboard')->with(['data'=>false]);
        }catch (\Exception $ex){
            Log::info('categoryController', ['getcategory'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }
    }
    public function getCategory(){
        try {
            $result = $this->category->getCategory();
            if (isset($result) && !empty($result)){
                return view('category')->with(['data'=>$result]);
            }
            return view('category')->with(['data'=>false]);
        }catch (\Exception $ex){
            Log::info('categoryController', ['getcategory'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }
    }
    public function deleteCategory($id){

        try {
            if (!$id){
                return redirect()->back()->withErrors(['error'=>"Id Is Required"]);
            }
            $result = $this->category->deleteCategory($id);
            if ($result){
                return redirect()->back()->with(['status'=>true,'message'=>'Delete Success']);
            }
            return redirect()->back()->with(['status'=>false,'message'=>'Delete failed']);
        }catch (\Exception $ex){
            Log::info('categoryController', ['deletecategory'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }
    }
    public function addCategory(){
        try {
            return view('category_add');
        }catch (\Exception $ex){
            Log::info('categoryController', ['addcategory'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }
    }
    public function editCategory(Request $request, $category){
        
        try {
            $id = $request->id;
            $categorys = category::whereNull('id')->get();
            $category = category::find($id);
            return view('category_edit', compact('categorys','category'));
        }catch (\Exception $ex){
            Log::info('CategoryController', ['editcategory'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }
    }

    public function updatecategory(Request $request, $category){
        try {
            

            $validator = Validator::make($request->all(),[
                'name' => 'required'
            ]);
            if ($validator->fails()){
                $error = $validator->errors();
                return redirect()->back()->withErrors($error)->withInput();
            }
            $id = $category;
            
            $result = $this->category->updatecategory($id,$request->name);
            if ($result){
                return redirect('/category')->with(['status'=>true,'massage'=>'category Edited Successfully !']);
            }
            return redirect()->back()->withErrors(['message'=>'category Not Changed !'])->withInput();
        }catch (\Exception $ex){
            Log::info('categoryController', ['addcategory'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }
    }
    public function addNewcategory(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'name' => 'required'
            ]);
            if ($validator->fails()){
                $error = $validator->errors()->first();
                return redirect()->back()->withErrors(['error'=>$error]);
            }
            $result = $this->category->addcategory($request->name);
            if ($result){
                return redirect('/category');
            }
            return redirect()->back()->with(['status'=>false,'message'=>'Add Failed']);
        }catch (\Exception $ex){
            Log::info('categoryController', ['addcategory'=>$ex->getMessage(),'line'=>$ex->getLine()]);
            return view('error.500');
        }
    }
}
