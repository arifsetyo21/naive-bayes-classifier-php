@extends('layouts.app')

@section('title')
   Detail | Training Page | NBC
@endsection
@section('content')
<a href="{{route('training.index')}}">< back</a>
@if (session('status'))
    {{ session('status')}}
@endif
   <ul>
      <li>{{$article->title}}</li>
      <li>{{$article->category->name}}</li>
      <li>{{$article->url->url}}</li>
      <li>{{$article->content}}</li>
      <li>{{$article->content_cleaned }}</li>
      <li>{{$article->words}}</li>
   <li><a name="" id="" class="btn btn-primary btn-sm" href="{{route('training.saveCleaned', ['id' => $article->id])}}" role="button">Save Word</a></li>
   </ul>
@endsection