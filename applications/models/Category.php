<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;



    protected $fillable = [
        "title","desc","image"
    ];
    public function books(){
        return $this->hasMany(Book::class);
    }
}
