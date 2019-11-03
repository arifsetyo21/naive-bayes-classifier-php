@extends('layouts.app')

@section('title')
    Category Update
@endsection

@section('header', 'Ubah Data Kategori')

@section('content')

@if (session('status'))
    {{ session('status') }}
@endif
   <form action="{{ route('category.update', ['category' => $category->id]) }}" method="post">
      @csrf
      <div class="form-row">
         <div class="col-md-6">
            <div class="card">
               <div class="card-header card-header-danger">
                  <h4 class="card-title">Ubah Kategori</h4>
               </div>
               <div class="card-body">
                  <input type="hidden" name="id" value="{{$category->id}}">
                  <input type="hidden" name="_method" value="put">
                  <label for="name">Nama Kategori</label>
                  <input type="text" name="name" class="form-control" placeholder="@kumparankategori" id="name" value="{{$category->name}}">
                  <br>
                  <button type="submit" class="btn btn-primary align-bottom">Ubah</button>
               </div>
            </div>
         </div>
      </div>      
   </form>
@endsection
