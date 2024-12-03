<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Faker\Provider\Lorem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function all(){
        $products = Product::paginate(3);
        return view('admin.home',["products"=>$products]);
    }
    public function create(){

        $categories = Category::all();
        return view('admin.create')->with("categories",$categories);
    }
    public function store(Request $request){

        $request->validate([
            "name"=>"required|string|max:200",
            "desc"=>"required|string",
            "price"=>"required|numeric",
            "quantity"=>"required|numeric",
            "category_id"=>"required|exists:categories,id",
            "image"=>"required|image|mimes:png,jpg,jpeg"
        ]);
        $imageName = Storage::putFile("products",$request->image);
        Product::create([
            "name"=>$request->name,
            "desc"=>$request->desc,
            "price"=>$request->price,
            "quantity"=>$request->quantity,
            "category_id"=>$request->category_id,
            "image"=>$imageName
        ]);
        session()->flash("success","data inserted successfuly");
        return redirect(route("createproduct")) ;
    }
    public function edit($id){
        $product = Product::findorFail($id);
        $categories = Category::all();
        // $users = User::all();
        return view("admin.edit",["product"=>$product,"categories"=>$categories]);
    }
    public function update($id, Request $request){

        $request->validate([
            "name"=>"required|string|max:200",
            "desc"=>"required|string",
            "price"=>"required|numeric",
            "quantity"=>"required|numeric",
            "category_id"=>"required|exists:categories,id",
            "image"=>"image|mimes:png,jpg,jpeg"
        ]);

            $product = Product::findOrFail($id);
            $oldImage = $product->image;
               if($request->has("image")){
                   Storage::delete($oldImage);
                   $image= Storage::putFile("products",$request->image);

               }else{$image=$oldImage;}
               $product->update([
               "name"=>$request->name,
               "desc"=>$request->desc,
               "category_id"=>$request->category_id,
               "price"=>$request->price,
               "quantity"=>$request->quantity,
               "image"=>$image
            ]);
               session()->flash("success","data updated successfuly");
               return redirect(route("allproducts")) ;
    }
    public function delete($id){
        $product = Product::findOrFail($id);
        Storage::delete("$product->image");
        $product->delete();
        session()->flash("error","product has been deleted successfuly");
        return redirect(url("products")) ;
    }

}
