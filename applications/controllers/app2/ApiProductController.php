<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiProductController extends Controller
{
    public function all(){
        $products = Product::all();
        if($products != null){

            return ProductResource::collection($products) ;
        }else{
            return response()->json([
                "msg"=>"data not found"
            ],404);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            "name"=>"required|string|max:200",
            "desc"=>"required|string",
            "price"=>"required|numeric",
            "quantity"=>"required|numeric",
            "category_id"=>"required|exists:categories,id",
            "image"=>"required|image|mimes:png,jpg,jpeg"
        ]);
        if($validator->fails()){
            return response()->json([
                "error"=>$validator->errors()
            ],301);
        }



        $image = Storage::putFile("products",$request->image);
        Product::create([

            "name"=>$request->name,
            "desc"=>$request->desc,
            "price"=>$request->price,
            "quantity"=>$request->quantity,
            "category_id"=>$request->category_id,
            "image"=>$image
        ]);
        return response()->json([
            "msg"=>"product created succefuly"
        ],201);
    }
    public function update($id, Request $request){

        $validator = Validator::make($request->all(),[
            "name"=>"required|string|max:200",
            "desc"=>"required|string",
            "price"=>"required|numeric",
            "quantity"=>"required|numeric",
            "category_id"=>"required|exists:categories,id",
            "image"=>"image|mimes:png,jpg,jpeg"
        ]);
        if($validator->fails()){
            return response()->json([
                "error"=>$validator->errors()
            ],301);
        }
        $product = Product::find($id);
        if($product == null){
            return response()->json([
                "msg"=>"product not found"
            ],404);
        }
        $image = $product->image;
        if($request->has("image")){
            Storage::delete($product->image);
            $image = Storage::putFile("products",$request->image);
        }
        $product->update([
            "name"=>$request->name,
            "desc"=>$request->desc,
            "price"=>$request->price,
            "quantity"=>$request->quantity,
            "category_id"=>$request->category_id,
            "image"=>$image
        ]);
        return response()->json([
            "msg"=>"product updated successfully",
            "product"=>new ProductResource($product),

        ],201);
   }
   public function delete($id){
    $product = Product::find($id);
    if($product == null){
        return response()->json([
            "msg"=>"product not found"
        ],404);
    }
    Storage::delete("$product->image");
    $product->delete();
    return response()->json([
        "msg"=>"product deleted successfully",
    ],200);



   }


}
