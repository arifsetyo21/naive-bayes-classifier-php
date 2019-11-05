@extends('layouts.app')

@section('title')
   Classification
@endsection

@section('header')
   Form Klasifikasi
@endsection

@section('css')
    .card-text {
       margin-top: 20px;
    }

    div.form-group {
       margin-bottom: 20px !important;
    }
@endsection

@section('content')
   <div class="row">
      <div class="col-md-12 social-button-demo" style="height: 50px;">
         <a href="{{route('classification.create')}}" class="float-left mr-1">
            <button class="btn btn-fill btn-success">
                  <i class="material-icons">
                     note_add
                  </i> Tambah Data Testing (URL)
            </button>
         </a>
         <a href="{{route('classification.list')}}" class="float-left mr-1">
            <button class="btn btn-fill btn-primary">
               <i class="material-icons">
                  list
               </i> Daftar Data Testing
            </button>
         </a>
      </div>
      <div class="col-md-12">
         <div class="card card-nav-tabs">
            <div class="card-header card-header-danger">
               <h4 class="card-title">Form Klasifikasi</h4>
               <p class="category mb-0">Klasifikasi Menggunakan NBC dan NBC Modified</p>
            </div>
            <form class="card-body" method="post" action="{{route('classification.store')}}">
               @csrf
               <div class="card-text">
                  <div class="form-group">
                     <label for="articleTitle">Judul Artikel</label>
                     <input class="form-control" id="articleTitle" name="articleTitle" placeholder="Judul Artikel ...."/>
                  </div>
                  <div class="form-group">
                     <label for="articleText">Testing Article</label>
                     <textarea class="form-control is-invalid" id="articleText" rows="10" name="articleText" placeholder="Isi Artikel ...."></textarea>
                     @error('articleTitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     @error('articleText')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>
                  <div class="form-group">
                    <label for="asli">Kategori Asli</label>
                    <select class="form-control" name="real_category" id="asli">
                       @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                       @endforeach
                    </select>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary">Test</a>
            </form>
         </div>
      </div>
   </div>
@endsection