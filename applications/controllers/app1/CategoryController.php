<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function all(){


        $categories= Category::orderby("id","asc")->paginate(5);
        return view("cat.all",["categories"=>$categories]);
    }
   public function show($id){

    $category= Category::findorFail($id);
    return view("cat.show",["category"=>$category]);
   }

    public function create(){

        return view("cat.create");

    }

    public function store(Request $request){
                    $request->validate([
                        "title"=>"required|string|max:150",
                        "desc"=>"required|string",
                        "image"=>"required|image|mimes:png,jpg,jpeg"
                    ]);
                    $imageName = Storage::putFile("categories",$request->image);
                    Category::create([
                        "title"=>$request->title,
                        "desc"=>$request->desc,
                        "image"=>$imageName
                    ]);
                    // session()->put("success","data inserted successfuly");
                    session()->flash("success","data inserted successfuly");
                    return redirect(route("allcat")) ;
    }

    public function edit($id){
        $category= Category::findorFail($id);
        return view("cat.edit",["category"=>$category]);
    }

    public function update($id, Request $request){

    $data = $request->validate([
                "title"=>"required|string|max:150",
                "desc"=>"required|string",
                "image"=>"image|mimes:png,jpg,jpeg"
            ]);

            $category = Category::findOrFail($id);
            $oldImage = $category->image;
            if($request->has("image")){
                Storage::delete($oldImage);
                $data['image']= Storage::putFile("categories",$request->image);

            }else{$data['image']=$oldImage;}


            $category->update($data);
            session()->flash("success","data updated successfuly");


            return redirect(url("categories/show/$category->id")) ;


    }

    public function delete($id){
        $category = Category::findOrFail($id);
        Storage::delete("$category->image");
        $category->delete();
        session()->flash("error","category [$category->title] deleted ");

        return redirect(url("categories")) ;
    }


}
