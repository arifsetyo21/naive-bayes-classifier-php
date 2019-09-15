@extends('layouts.app')

@section('title')
    Training Page | NBC
@endsection
@section('content')
   @if (session('status'))
       {{session('status')}}
   @endif
         <table class="table table-hover">
            <thead>
               <tr>
                  <th scope="col">#</th>
                  <th scope="col"></th>
                  <th scope="col">Url</th>
                  <th scope="col">Kategori</th>
                  <th scope="col">Aksi</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($urls as $index => $url)
               <tr>
                  <th scope="row">{{ $index + 1 }}</th>
                  <td><input type="checkbox" value=""></td>
                  <td>{{ $url->url }}</td>
                  <td></td>
                  <td>
                     <form class="float-left mr-1" action="{{route('training.scrapContentKumparan')}}" method="post">
                        @csrf
                        <input type="hidden" name="url" value="{{$url->url}}">
                        <input type="hidden" name="id" value="{{$url->id}}">
                        <button type="submit" class="btn btn-primary btn-sm">Scrap</button>
                     </form>
                     <a name="" id="" class="btn btn-danger btn-sm" href="#" role="button"><i class="fa fa-trash" aria-hidden="true"></i></a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
@endsection