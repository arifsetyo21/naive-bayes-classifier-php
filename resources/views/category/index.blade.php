@extends('layouts.app')

@section('title')
    Category List
@endsection

@section('content')

@if (session('status'))
    {{ session('status') }}
@endif
   <form action="{{ route('category.store') }}" method="post">
      @csrf
      <label for="name">Category Name</label>
      <input type="text" name="name" id="name">
   
      <button type="submit">SUBMIT</button>
   </form>
    <ul>
       @foreach ($categories as $category)
         <li>
            {{ $category->name }} 
               {{$category->delete_at}}
               @if ($category->deleted_at)
               <form action="{{ route('category.destroy-permenent', ['id' => $category->id]) }}" method="post">
                  @csrf
                  <input type="hidden" name="_method" value="delete">
                  <input type="submit" value="delete-permanent">    
               </form>
               @else
               <form action="{{ route('category.destroy', ['id' => $category->id]) }}" method="post">
                  @csrf
                  <input type="hidden" name="_method" value="delete">
                  <input type="submit" value="delete">
               </form>
               @endif
         </li>
       @endforeach
    </ul>
@endsection
