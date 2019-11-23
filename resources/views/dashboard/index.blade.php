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
         <form action="{{route('dashboard.destroyAll')}}" method="post" onsubmit="return confirm('Hapus Semua Data ?')">
            @csrf
            <button type="submit" class="btn btn-fill btn-primary">
                  <i class="material-icons">
                     delete
                  </i> Hapus Semua
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
               <h4 class="card-title">Confussion Matrix NBC</h4>
            </div>
            </div>
            <div class="card-body">
                  <center><span style="font-weight:bold">Kategori Asli</span></center>
               <table class="table">
                  <thead>
                     <tr>
                        <td class="rotate text-center font-weight-bold">#</td>
                        @foreach ($categories as $category)
                           <td class="rotate font-weight-bold text-center">{{$category->name}}</td>
                        @endforeach
                        <td class="font-weight-bold text-center">Precision</td>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($categories as $category)
                        <tr>
                           <td class="text-center font-weight-bold">{{$category->name}}</td>
                         @foreach ($categories2 as $category2)
                              {{-- <td class="text-center">{{$category2->name}} {{$category->name}} {{$dashboard->where('real_category', $category2->id)->where('prediction_nbc', $category->id)->count()}}</td> --}}
                              <td class="text-center">{{$dashboard->where('real_category', $category2->id)->where('prediction_nbc', $category->id)->count()}}</td>
                         @endforeach
                           @if ($dashboard->where('prediction_nbc', $category->id)->count())
                              <td class="text-center">{{round(($dashboard->where('real_category', $category->id)->where('prediction_nbc', $category->id)->count()/$dashboard->where('prediction_nbc', $category->id)->count())*100)}}%</td>
                           @else
                              <td class="text-center">{{$dashboard->where('prediction_nbc', $category->id)->count()}}</td>
                           @endif
                        </tr>
                     @endforeach
                  </tbody>
                  <tfoot>
                     <tr>
                        <td class="text-center font-weight-bold">Recall</td>
                        @foreach ($categories as $category)
                           @if ($dashboard->where('real_category', $category->id)->count())
                              <td class="text-center">{{round(($dashboard->where('real_category', $category->id)->where('prediction_nbc', $category->id)->count()/$dashboard->where('real_category', $category->id)->count())*100)}}%</td>
                           @else
                              <td class="text-center">0</td>
                           @endif
                        @endforeach
                     </tr>
                  </tfoot>
               </table>
               @if ($dashboard->count())
                  <p class="text-center font-weight-bold">Akurasi : {{number_format($total['nbc']->sum()/$dashboard->count()*100, 2)}}%</p>
               @else
                   <p class="text-center font-weight-bold">Akurasi : 0</p>
               @endif
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header card-header-text card-header-primary">
            <div class="card-text">
               <h4 class="card-title">Confussion Matrix NBC Modified</h4>
            </div>
            </div>
            <div class="card-body">
                  <center><span style="font-weight:bold">Kategori Asli</span></center>
               <table class="table">
                  <thead>
                     <tr>
                        <td class="rotate text-center font-weight-bold">#</td>
                        @foreach ($categories as $category)
                           <td class="rotate font-weight-bold text-center">{{$category->name}}</td>
                        @endforeach
                        <td class="text-center font-weight-bold">Precision</td>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($categories as $category)
                        <tr>
                           <td class="text-center font-weight-bold">{{$category->name}}</td>
                           @foreach ($categories2 as $category2)
                              {{-- <td>{{$category2->name}} {{$category->name}} {{$dashboard->where('real_category', $category2->id)->where('prediction_nbc', $category->id)->count()}}</td> --}}
                              <td class="text-center">{{$dashboard->where('real_category', $category2->id)->where('prediction_nbc', $category->id)->count()}}</td>
                           @endforeach
                           @if ($dashboard->where('prediction_modified', $category->id)->count())
                              <td class="text-center">{{round(($dashboard->where('real_category', $category->id)->where('prediction_modified', $category->id)->count()/$dashboard->where('prediction_modified', $category->id)->count())*100)}}%</td>
                           @else
                              <td class="text-center">0</td>
                           @endif
                        </tr>
                     @endforeach
                  </tbody>
                  <tfoot>
                     <tr>
                        <td class="font-weight-bold text-center">Recall</td>
                        @foreach ($categories as $category)
                           @if ($dashboard->where('real_category', $category->id)->count())
                              <td class="text-center">{{round(($dashboard->where('real_category', $category->id)->where('prediction_modified', $category->id)->count()/$dashboard->where('real_category', $category->id)->count())*100)}}%</td>
                           @else
                              <td class="text-center">0</td>
                           @endif
                        @endforeach
                     </tr>
                  </tfoot>
               </table>
               @if ($dashboard->count())
                  <p class="text-center font-weight-bold">Akurasi : {{number_format($total['modified']->sum()/$dashboard->count()*100, 2)}}%</p>
               @else
                   <p class="text-center font-weight-bold">Akurasi : 0</p>
               @endif
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
            <div class="card">
               <div class="card-header card-header-text card-header-primary">
                  <div class="card-text">
                     <h4 class="card-title">Perbandingan Performa</h4>
                  </div>
                  </div>
                  <div class="card-body">
                     <canvas id="myChart"></canvas>
                     <p class="text-center font-weight-bold">Rata Rata Selisih Performa : {{$avg_performance}} detik</p>
                  </div>
            </div>
      </div>
   </div>
@endsection

@section('css')
   table.table > tbody > tr > td > form  {
      display: inline-block;
    }

    th.rotate {
      /* Something you can count on */
      height: 140px;
      white-space: nowrap;
    }
    
    th.rotate > div {
      transform: 
        /* Magic Numbers */
        translate(25px, 51px)
        /* 45 is really 360 - 45 */
        rotate(315deg);
      width: 30px;
    }
    th.rotate > div > span {
      border-bottom: 1px solid #ccc;
      padding: 5px 10px;
    }

    .csstransforms & th.rotate {
      height: 140px;
      white-space: nowrap;
    }
@endsection

@section('js')
   $('div.alert').delay(3000).slideUp(300);

   $(document).ready(function() {
      
      var labels = '';

      $.ajax({
         type: "GET",
         url: '/dashboard/article-list-chart',
         success: function (data) {
            labels = data;
         }, 
         // for assign data to global variable
         async: false
      });

      var ctx = document.getElementById('myChart').getContext('2d');

      var chart = new Chart(ctx, {
         // The type of chart we want to create
         type: 'horizontalBar',

         // The data for our dataset
         {{-- // [{{implode($labels, ',')}}] --}}
         data: {
            labels: labels,
            datasets: [
               {  
                  label: 'NBC',
                  backgroundColor: 'rgb(255, 99, 132)',
                  borderColor: 'rgb(255, 99, 132)',
                  data: [{{$nbc}}]
               },
               {
                  label: 'NBC Modified',
                  backgroundColor: 'blue',
                  borderColor: 'blue',
                  data: [{{$modified}}]
               }
            ]
         },

         // Configuration options go here
         options: {}
      });
   });

@endsection