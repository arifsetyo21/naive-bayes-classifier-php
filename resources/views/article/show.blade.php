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
            <p class="category">{{$article->url->url}}</p>
         </div>
         <div class="card-body">
            <h3>{{$article->title}}</h3>
            @foreach (json_decode($article->content) as $paragraf)
               <p>{{html_entity_decode($paragraf)}}</p>
            @endforeach
            <h3>Daftar Kata</h3>
            @foreach ($article->words as $key => $term)
               <a href="#" class="btn btn-danger btn-sm disabled" role="button" aria-disabled="true">{{$term->word_term}} <span class="badge badge-pill badge-light">{{$term->id}}</span></a>
            @endforeach
         </div>
      </div>
   </div>
   {{-- <ul>
      <li>{{$article->title}}</li>
      <li>{{$article->category->name}}</li>
      <li>{{$article->url->url}}</li>
      <li>{{$article->content}}</li>
      <li>{{$article->content_cleaned }}</li>
      <li>{{}}</li>
   <li><a name="" id="" class="btn btn-primary btn-sm" href="{{route('training.saveCleaned', ['id' => $article->id])}}" role="button">Save Word</a></li>
   </ul> --}}
@endsection