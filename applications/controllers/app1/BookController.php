<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Psy\Command\WhereamiCommand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function all(){


        $books= Book::select()->orderby("id","asc")->paginate(6);
        return view("books.all",["books"=>$books]);
    }

    public function show($id){
        $book= book::findorFail($id);
        return view("books.show",["book"=>$book]);
    }

    public function create(){
        $categories = Category::all();
        $users = User::all();
        return view("books.create")->with("categories",$categories)->with("users",$users);

    }

    public function store(Request $request){
        $request->validate([
            "name"=>"required|string|max:150",
            "desc"=>"required|string",
            "price"=>"required|numeric",
            "category_id"=>"required|exists:categories,id",
            "user_id"=>"required|exists:users,id",
            "image"=>"required|image|mimes:png,jpg,jpeg"
        ]);
        $imageName = Storage::putFile("books",$request->image);
        Book::create([
            "name"=>$request->name,
            "desc"=>$request->desc,
            "category_id"=>$request->category_id,
            "user_id"=>$request->user_id,
            "price"=>$request->price,

            "image"=>$imageName
        ]);
        // session()->put("success","data inserted successfuly");
        session()->flash("success","data inserted successfuly");
        return redirect(route("allbook")) ;
    }

    public function edit($id){
        $book= Book::findorFail($id);
        $categories = Category::all();
        $users = User::all();
        return view("books.edit",["book"=>$book,"categories"=>$categories,"users"=>$users]);
    }

    public function update($id, Request $request){

        $request->validate([
            "name"=>"required|string|max:150",
            "desc"=>"required|string",
            "price"=>"required|numeric",
            "category_id"=>"required|exists:categories,id",
            "user_id"=>"required|exists:users,id",
            "image"=>"image|mimes:png,jpg,jpeg"
        ]);

            $book = Book::findOrFail($id);
            $oldImage = $book->image;
               if($request->has("image")){
                   Storage::delete($oldImage);
                   $image= Storage::putFile("books",$request->image);

               }else{$image=$oldImage;}
               $book->update([
               "name"=>$request->name,
               "desc"=>$request->desc,
               "category_id"=>$request->category_id,
               "user_id"=>$request->user_id,
               "price"=>$request->price,
               "image"=>$image
            ]);
               session()->flash("success","data updated successfuly");
               return redirect(route("showbook",$book->id)) ;
    }
    public function delete($id){
        $book = Book::findOrFail($id);
        Storage::delete("$book->image");
        $book->delete();
        session()->flash("error","category [$book->name] deleted ");
        return redirect(route("allbook")) ;
    }

}
