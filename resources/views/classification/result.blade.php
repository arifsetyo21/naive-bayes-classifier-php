@extends('layouts.app')

@section('title', 'Classification Result')

@section('header', 'Hasil Klasifikasi')

@section('css')
    .card-text {
       margin-top: 20px;
    }

    div.form-group {
       margin-bottom: 20px !important;
    }
@endsection

@section('content')
   <div class="row">
      <div class="col-lg-6 col-md-12 col-sm-12">
         <div class="card">
            <div class="card-header card-header-danger">
               <h4 class="card-title">Hasil Klasifikasi</h4>
               <p class="category mb-0">Naive Bayes Classifier</p>
            </div>
            <div class="card-body"> 
               @if (session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
               @endif
               @if (empty($result))
                  @if (session('error'))
                     {{$exception->getMessage()}}
                  @endif
                     <div class="alert alert-danger">{{ session('error') }}</div>
               @else
               <label for="result_modified" class="inline">Prediksi Kategori</label><br>
               <a href="#" class="btn btn-default disabled font-weight-bold" id="result_modified" role="button" aria-disabled="true">@kumparanoto</a>

               <table class="table">
                  <thead>
                        <tr>
                           <th>Kategori</th>
                           <th>Nilai</th>
                           <th>Presisi</th>
                        </tr>
                  </thead>
                  <tbody>
                     @foreach ($result['result']['category'] as $key => $value)
                        <tr>
                           <td>{{$key}}</td>
                           <td>{{$value['nbc_value_per_class']}}</td>
                           <td>2013</td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
               
               <p>Total Kata : {{ $result['result']['total_words']}}</p>
                  @foreach ($result['result']['category'] as $key => $value)
                  <h4 class="card-title">{{$key}} <span class="badge badge-info">{{$value['nbc_value_per_class']}}</span></h4><span style="font-size:13px"> Count(C) : {{$value['words_count_in_category']}}</span>
                  <div class="card-text"> 
                     @foreach ($value['words'] as $word)
                        <div class="btn btn-rose btn-sm">
                           {{ $word['word']}} <span class="badge badge-pill badge-light">{{ "  " . $word['word_count']}}</span>
                        </div>
                     @endforeach
                  </div>
                  <br>
                  <br>
                  @endforeach
               @endif
            </div>
         </div>
      </div>
      <div class="col-lg-6 col-md-12 col-sm-12">
         <div class="card">
               <div class="card-header card-header-danger">
                  <h4 class="card-title">Hasil Klasifikasi</h4>
                  <p class="category mb-0">Naive Bayes Classifier yang Dimodifikasi</p>
               </div>
               <div class="card-body"> 
                  @if (session('error'))
                     <div class="alert alert-danger">{{ session('error') }}</div>
                  @endif
                  @if (empty($result_modified))
                     @if (session('error'))
                        {{$exception->getMessage()}}
                     @endif
                        <div class="alert alert-danger">{{ session('error') }}</div>
                  @else
                     <label for="result_modified" class="inline">Prediksi Kategori</label><br>
                     <a href="#" class="btn btn-default disabled font-weight-bold" id="result_modified" role="button" aria-disabled="true">@kumparanoto</a>

                     <table class="table">
                        <thead>
                              <tr>
                                 <th>Kategori</th>
                                 <th>Nilai</th>
                                 <th>Presisi</th>
                              </tr>
                        </thead>
                        <tbody>
                           @foreach ($result_modified['result']['category'] as $key => $value)
                              <tr>
                                 <td>{{$key}}</td>
                                 <td>{{$value['nbc_value_per_class']}}</td>
                                 <td>2013</td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                     
                     <p>Total Kata : {{ $result_modified['result']['total_words']}}</p>
                     @foreach ($result_modified['result']['category'] as $key => $value)
                     <h4 class="card-title">{{$key}} <span class="badge badge-info">{{$value['nbc_value_per_class']}}</span></h4><span style="font-size:13px"> Count(C) : {{$value['words_count_in_category']}}</span>
                     <div class="card-text"> 
                        @foreach ($value['words'] as $word)
                           <div class="btn btn-rose btn-sm">
                                 {{ $word['word']}} <span class="badge badge-pill badge-light">{{ "  " . $word['word_count']}}</span>
                           </div>
                        @endforeach
                     </div>
                     <br>
                     <br>
                     @endforeach
                  @endif
               </div>
         </div>
      </div>
   </div>
@endsection