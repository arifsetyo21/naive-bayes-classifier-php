@extends('layouts.app')

@section('title', 'Article Detail')

@section('header', 'Detail Data Training')

@section('content')
@if (session('status'))
    {{ session('status')}}
@endif
   <div class="col-md-12 col-lg-12 col-sm-12">
      <div class="card">
         <div class="card-header card-header-danger">
            <h4 class="card-title">{{$article->category->name}}</h4>
            <p class="category">{{$article->url}}</p>
         </div>
         <div class="card-body">
            <h3>{{$article->title}}</h3>
            @foreach (json_decode($article->content) as $paragraf)
               <p>{{html_entity_decode($paragraf)}}</p>
            @endforeach
         </div>
      </div>
   </div>
@endsection