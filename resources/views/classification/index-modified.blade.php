@extends('layouts.app')

@section('title')
   Classification
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
            <div class="card-header card-header-rose">
               <ul class="nav nav-tabs">
                  <li class="nav-item">
                     <a class="nav-link active" href="#0">Active</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#0">Link</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link disabled" href="#0">Disabled</a>
                  </li>
               </ul>
            </div>
            <form class="card-body" method="post" action="{{route('classification.nbcModified')}}">
               @if (session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
               @endif
               @csrf
               <h4 class="card-title">Input Data Testing</h4>
               <div class="card-text">
                  <div class="form-group">
                     <label for="articleTitle">Judul Artikel</label>
                     <input class="form-control" id="articleTitle" name="articleTitle" placeholder="Put Title article here"/>
                  </div>
                  <div class="form-group">
                     <label for="articleText">Testing Article</label>
                     <textarea class="form-control is-invalid" id="articleText" rows="10" name="articleText" placeholder="Put article content here.."></textarea>
                     @error('articleTitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     @error('articleText')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     {{-- <div class="invalid-feedback">
                        {{$errors->first('articleText')}}
                     </div>
                     <div class="invalid-feedback">
                        {{$errors->first('articleTitle')}}
                     </div> --}}
                  </div>
               </div>
               <button type="submit" class="btn btn-primary">Test</a>
            </form>
         </div>
      </div>
   </div>
@endsection