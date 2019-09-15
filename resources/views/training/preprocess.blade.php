@extends('layouts.app')

@section('title')
    Training Page | NBC
@endsection
@section('content')
   <ol>
      @foreach ($words as $key => $word)
         <li>{{ $word }}</li>
      @endforeach
   </ol>
<a name="" id="" class="btn btn-primary" href="{{route('training.save')}}" role="button">Save</a>
@endsection