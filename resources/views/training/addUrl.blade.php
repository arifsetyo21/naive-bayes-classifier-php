@extends('layouts.app')

@section('title')
    Training Page | NBC
@endsection
@section('content')
   @if (session('status'))
       {{session('status')}}
   @endif
      <form action="{{route('training.storeUrl')}}" method="post">
         @csrf
         <div class="form-group">
           <label for="url">Alamat Berita Kumparan</label>
           <input type="text"
             class="form-control" name="url" id="url" aria-describedby="helpId" placeholder="https://kumparan.com/...">
           <small id="helpId" class="form-text text-muted"></small>
         </div>
         <div class="form-group">
           <label for="category">Kategori</label>
           <select class="form-control" name="category_id" id="category">
             @foreach ($categories as $category)
             <option value="{{$category->id}}">{{$category->name}}</option>
             @endforeach
           </select>
         </div>
         <button type="submit" class="btn btn-primary">Ambil Konten</button>
      </form>
@endsection