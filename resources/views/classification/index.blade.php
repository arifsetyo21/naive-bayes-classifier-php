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
               </div>
               <button type="submit" class="btn btn-primary">Test</a>
            </form>
         </div>
      </div>
   </div>
@endsection