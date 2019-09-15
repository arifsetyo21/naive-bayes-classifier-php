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
               <h4 class="card-title ">Simple Table</h4>
               <p class="card-category"> Here is a subtitle for this table</p>
            </div>
            <div class="card-body">
               <h3 class="card-title">Kategori Otomotif</h3>
               <div class="card-text">
                  @foreach ($words as $word)
                  <div class="btn btn-primary btn-sm">
                     {{$word}} <span class="badge badge-light">4</span>
                  </div>
                  @endforeach
               </div>
               <br>
               <h3 class="card-title">Kategori Otomotif</h3>
               <div class="card-text">
                  <div class="btn btn-primary btn-sm">
                     Notifications <span class="badge badge-light">4</span>
                  </div>
               </div><br>
               <h3 class="card-title">Kategori Otomotif</h3>
               <div class="card-text">
                  <div class="btn btn-primary btn-sm">
                     Notifications <span class="badge badge-light">4</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection