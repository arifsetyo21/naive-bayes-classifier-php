@extends('layouts.app')

@section('title', 'Testing Page')

@section('header', 'Daftar Data Testing')

@section('content')
   @if (session('status'))   
   <div class="alert alert-primary" id="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <i class="material-icons">close</i>
      </button>
      <span>
         {{session('status')}}
      </span>
   </div>       
   @endif
   <div class="row">
      <div class="col-md-12 social-button-demo" style="height: 50px;">
         <a href="{{route('classification.index')}}" class="float-left mr-1">
            <button class="btn btn-fill btn-success">
                  <i class="material-icons">
                     note_add
                  </i> Tambah Data Testing 
            </button>
         </a>
         <a onclick="return confirm('Hapus Semua Data ?')" href="{{route('classification.deleteAll')}}" class="float-left mr-1">
            <button class="btn btn-fill btn-danger">
                  <i class="material-icons">
                     delete
                  </i> Hapus Data Testing 
            </button>
         </a>
         <form action="{{route('classification.all')}}" method="post" onsubmit="return confirm('Preprocess Semua Data ?')">
            @csrf
            <button type="submit" class="btn btn-fill btn-primary">
               <i class="material-icons">
                  file_copy
               </i> Klasifikasi Semua
            </button>
         </form>
         <br>
      </div>
      <div class="col-md-12">
         <div class="card">
            <div class="card-header card-header-primary">
            <h4 class="card-title ">Daftar Data Training</h4>
            <p class="card-category"> Semua Kelas Kategori</p>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table">
                     <thead class=" text-primary">
                     <th>
                        No
                     </th>
                     <th>
                        Nama
                     </th>
                     <th>
                        Kategori Asli
                     </th>
                     <th>
                        Aksi
                     </th>
                     </thead>
                     <tbody>
                     @foreach ($articles as $index => $item)
                     @if ($item->words_count == null)
                        <tr>
                           <td>
                              {{ $index + $articles->firstItem() }}
                           </td>
                           <td>
                              ({{$item->id}}) {{ $item->title }}
                           </td>
                           <td>{{$item->category->name}}</td>
                           <td>
                              <form action="{{route('classification.direct')}}" method="post">
                                 @csrf
                                 <input type="hidden" name="id" value="{{$item->id}}">
                                 <button class="btn btn-primary btn-sm" type="submit">Klasifikasi</button>
                              </form>
                              <a class="" href="{{route('classification.show', ['id' => $item->id])}}">
                                 <button type="button" class="btn btn-info btn-sm">Detail</button>
                              </a>
                              <form class="" action="{{route('classification.destroy', ['id' => $item->id])}}" method="post">
                                 @csrf
                                 <input type="hidden" name="_method" value="delete">
                                 <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                              </form>
                           </td>
                        </tr>
                        @else
                        <tr>
                           <td>
                              {{ $index + $articles->firstItem() }}
                           </td>
                           <td>
                              ({{$item->id}}) {{ $item->title }} <span class="badge badge-success">clean</span>
                           </td>
                           <td>
                              {{ $item->category->name}}
                           </td>
                           <td>
                              <a name="" id="" class="btn btn-primary btn-sm" href="{{route('article.show', ['id' => $item->id])}}" role="button">
                                 Detail
                              </a>
                              <form action="{{route('article.delete')}}" method="POST">
                                 @csrf
                                 <input type="hidden" name="id" value="{{$item->id}}">
                                 <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                              </form>
                           </td>
                        </tr>
                        @endif
                     @endforeach
                     </tbody>
                     <tfoot>
                        <tr>
                           <td colspan="12" class="text-center">
                              {{$articles->links()}}
                           </td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-6 col-md-12">
         <div class="card">
            <div class="card-header card-header-warning">
               <h4 class="card-title">Data Training Detail</h4>
               <p class="card-category">Current Data Training Detail per Category</p>
            </div>
            <div class="card-body table-responsive">
               <table class="table table-hover">
               <thead class="text-warning">
                  <th>No</th>
                  <th>Nama Kategori</th>
                  <th>Jumlah Dokumen</th>
               </thead>
               <tbody>
                  @foreach ($data_testing_detail as $index => $detail)
                  <tr>
                     <td>{{$index}}</td>
                     <td>{{$detail->name}}</td>
                     <td class="text-center">{{$detail->test_datas_count}}</td>
                  </tr>
                  @endforeach
               </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
@endsection

@section('css')
   table.table > tbody > tr > td > form  {
      display: inline-block;
    }
@endsection

@section('js')
   $('div.alert').delay(3000).slideUp(300);
@endsection