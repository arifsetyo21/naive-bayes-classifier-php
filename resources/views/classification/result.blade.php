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
                  {{$result['total_words']}} 
                  @foreach ($result['category'] as $key => $value)
                  <h3 class="card-title">Kategori {{$key}} <span class="badge badge-info">{{$value['nbc_value_per_class']}}</span></h3> Count(C) : {{$value['words_count_in_category']}}
                  <div class="card-text"> 
                     @foreach ($value['words'] as $word)
                        <div class="btn btn-rose btn-sm">
                           {{ $word['word']}} <span class="badge badge-light">{{ "  " . $word['word_count']}}</span>
                        </div>
                     @endforeach
                     <br>
                     <br>
                  @endforeach
               </div>
               <br>
            </div>
         </div>
      </div>
   </div>
@endsection