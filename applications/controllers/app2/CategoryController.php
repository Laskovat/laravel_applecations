<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function all(){
        $categories = Category::paginate(2);
        return view('admin.homecat',["categories"=>$categories]);

    }
    public function create(){

        return view('admin.createcat');
    }
    public function store(Request $request){

        $request->validate([
            "title"=>"required|string|max:200",
            "desc"=>"required|string",
        ]);
        Category::create([
            "title"=>$request->title,
            "desc"=>$request->desc,
        ]);
        session()->flash("success","data inserted successfuly");
        return redirect(url("redirect")) ;
    }
    public function edit($id){
        $category = Category::findorFail($id);
        return view("admin.editcat",["category"=>$category]);
    }
    public function update($id, Request $request){
        $request->validate([
            "title"=>"required|string|max:200",
            "desc"=>"required|string",
        ]);
            $category = Category::findOrFail($id);
               $category->update([
               "title"=>$request->title,
               "desc"=>$request->desc,
            ]);
               session()->flash("success","category has updated successfuly");
               return redirect(route("allcat")) ;
    }
    public function delete($id){
        $category = Category::findOrFail($id);
        $category->delete();

        session()->flash("error","category [$category->title] deleted ");

        return redirect(url("categories")) ;



    }

}
