@extends('layouts.app')

@section('title', 'Dashboard')

@section('header', 'Beranda')

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
         <form action="{{route('training.preprocessAll')}}" method="post" onsubmit="return confirm('Preprocess Semua Data ?')">
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
                        Kat. Pred. NBC
                     </th>
                     <th>
                        Kat. Pred. Modif
                     </th>
                     <th>
                        Aksi
                     </th>
                     </thead>
                     <tbody>
                     @foreach ($articles as $index => $item)
                        <tr>
                           <td>
                              {{ $index + $articles->firstItem() }}
                           </td>
                           <td>
                              ({{$item->id}}) {{ $item->title }}
                           </td>
                           <td>
                              {{ $item->category_real_category->name}}
                           </td>
                           <td>
                              {{ $item->category_prediction_nbc->name}}
                           </td>
                           <td>
                              {{ $item->category_prediction_modified->name}}
                           </td>
                           <td>
                              <a class="" href="{{route('dashboard.show', ['id' => $item->id])}}">
                                 <button type="button" class="btn btn-primary btn-sm">Detail</button>
                              </a>
                              <a class="btn btn-danger btn-sm" href="{{route('dashboard.destroy', ['id' => $item->id])}}">
                                 Hapus
                              </a>
                              {{-- <form class="" action="{{route('dashboard.destroy', ['id' => $item->id])}}" method="post">
                                 @csrf
                                 <input type="hidden" name="_method" value="delete">
                                 <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                              </form> --}}
                           </td>
                        </tr>
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
   </div>
   {{-- TODO Tambah Tabel validasi untuk 2 klasifikasi (confusion matrix) --}}
   <div class="row">
      <div class="col-md-12">
         <div class="card">
               <div class="card-header card-header-text card-header-primary">
               <div class="card-text">
                  <h4 class="card-title">Here is the Text</h4>
               </div>
               </div>
               <div class="card-body">
         The place is close to Barceloneta Beach and bus stop just 2 min by walk and near to "Naviglio" where you can enjoy the main night life in Barcelona...
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