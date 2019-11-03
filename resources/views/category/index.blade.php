@extends('layouts.app')

@section('title')
    Category List
@endsection

@section('header', 'Daftar Data Kategori')

@section('content')

@if (session('status'))
    {{ session('status') }}
@endif
   <form action="{{ route('category.store') }}" method="post">
      <div class="form-row">
         <div class="col-lg-4 col-md-10">
            @csrf
            <label for="name">Nama Kategori</label>
            <input type="text" name="name" class="form-control" placeholder="@kumparankategori" id="name">
         </div>
         <div class="col-lg-2 col-md-2">
            <button type="submit" class="btn btn-primary btn-sm  align-bottom">Tambah</button>
         </div>
      </div>      
   </form>
   <div class="row">
      <div class="col-lg-6 col-md-12">
         <div class="card">
            <div class="card-header card-header-warning">
               <h4 class="card-title">Daftar Data Kategori</h4>
               <p class="card-category">Current Data Training Detail per Category</p>
            </div>
            <div class="card-body table-responsive">
               <table class="table table-hover">
               <thead class="text-warning">
                  <th>No</th>
                  <th>Nama Kategori</th>
                  <th></th>
               </thead>
               <tbody>
                  @foreach ($categories as $key => $category)
                  <tr>
                     <td>{{ $key + 1 }}</td>
                     <td>{{ $category->name }}</td>
                     <td>
                        <a class="btn btn-primary btn-sm float-left" href="{{route('category.edit', ['category' => $category->id])}}">Ubah</a>
                        @if ($category->deleted_at)
                        <form action="{{ route('category.destroy-permenent', ['id' => $category->id]) }}" method="post">
                           @csrf
                           <input type="hidden" name="_method" value="delete">
                           <input type="submit" value="delete permanent" class="btn btn-danger btn-sm float-right">    
                        </form>
                        @else
                        <form action="{{ route('category.destroy', ['id' => $category->id]) }}" method="post">
                           @csrf
                           <input type="hidden" name="_method" value="delete">
                           <input type="submit" value="delete" class="btn btn-danger btn-sm float-right">
                        </form>
                        @endif
                     </td>
                  </tr>
                  @endforeach
               </tbody>
               <tfoot>
                  <tr>
                     <td colspan="3" class="text-center">
                        {{$categories->links()}}
                     </td>
                  </tr>
               </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
@endsection
