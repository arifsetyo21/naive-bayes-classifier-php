@extends('layouts.app')

@section('title')
    Training Page | NBC
@endsection
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
      <div class="col-md-12 social-button-demo">
         <a href="{{url('training/addUrl')}}">
            <button class="btn btn-fill btn-success">
                  <i class="material-icons">
                     note_add
                  </i> Tambah Data Training
            </button>
         </a>
         <br>
      </div>
      <div class="col-md-12">
         <div class="card">
            <div class="card-header card-header-primary">
            <h4 class="card-title ">Data Training</h4>
            <p class="card-category"> Here is a subtitle for this table</p>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table">
                     <thead class=" text-primary">
                     <th>
                        ID
                     </th>
                     <th>
                        Nama
                     </th>
                     <th>
                        Kategori
                     </th>
                     <th>
                        Aksi
                     </th>
                     </thead>
                     <tbody>
                     @foreach ($article as $index => $item)
                        @if ($item->content_cleaned == null)
                        <tr>
                           <td>
                              {{ $index + $article->firstItem() }}
                           </td>
                           <td>
                              {{ $item->title }}
                           </td>
                           <td>
                              {{ $item->category->name}}
                           </td>
                           <td>
                              <a href="{{route('training.preprocess', ['id' => $item->id])}}">
                                 <button type="button" class="btn btn-primary btn-sm">Preprocess</button>
                              </a>
                           </td>
                        </tr>
                        @else
                        <tr>
                           <td>
                              {{ $index + $article->firstItem() }}
                           </td>
                           <td>
                              {{ $item->title }}
                           </td>
                           <td>
                              {{ $item->category->name}}
                           </td>
                           <td>
                              <button type="button" class="btn btn-success btn-sm">Cleaned</button>
                              <a name="" id="" class="btn btn-primary btn-sm" href="{{route('article.show', ['id' => $item->id])}}" role="button">
                                 Detail
                              </a>
                           </td>
                        </tr>
                        @endif
                     @endforeach
                     </tbody>
                     <tfoot>
                        <tr>
                           <td colspan="12" class="text-center">
                              {{$article->links()}}
                           </td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection

@section('js')
   $('div.alert').delay(3000).slideUp(300);
@endsection