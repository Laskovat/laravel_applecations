@extends('layout')
@section('title')
show one
@endsection
@section('body')

@if (session()->has("success"))
<div class="alert alert-success">
{{session()->get("success")}}
</div>
@endif
{{-- relationsip between two tables --}}
<h1>category title : <a href="{{route("showcat",$book->category_id)}}">{{$book->category->title}}</a></h1>

title:{{$book->name}}<br>
desc:{{$book->desc}}<br>
<img src="{{asset("storage/$book->image")}}" width="300px" alt="">
<a href="{{route("editbook",$book->id)}}"><h2>edit</h2></a>



<form action="{{route("deletebook",$book->id)}}" method="post">
    @csrf
    @method('DELETE')
    <button type="submit">delete</button>
</form>
<a href="{{route("allbook")}}">home</a>
@endsection