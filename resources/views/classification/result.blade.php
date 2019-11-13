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
               <a href="#" class="btn btn-primary disabled font-weight-bold" id="result_modified" role="button" aria-disabled="true">{{$result['classprediction']['category']}}</a>

               <table class="table">
                  <thead>
                        <tr class="font-text-bold">
                           <th>Kategori</th>
                           <th>Probabilitas</th>
                           <th>Nilai Confidence</th>
                        </tr>
                  </thead>
                  <tbody>
                     @foreach ($result['result']['category'] as $key => $value)
                     <tr>
                        @if ($result['classprediction']['category'] == $key)
                           <td class="font-weight-bold">{{$key}}</td>
                           <td class="font-weight-bold">{{$value['nbc_value_per_class']}}</td>
                           <td class="font-weight-bold text-center">{{(($value['nbc_value_per_class'] - $result['lower_value']['nbc_value_per_class']) / ($result['classprediction']['nbc_value_per_class'] - $result['lower_value']['nbc_value_per_class']))*100}} %</td>
                        @else
                           <td>{{$key}}</td>
                           <td>{{$value['nbc_value_per_class']}}</td>
                           @if (($value['nbc_value_per_class'] - $result['lower_value']['nbc_value_per_class']) != 0)
                              <td class="text-center">{{round((($value['nbc_value_per_class'] - $result['lower_value']['nbc_value_per_class'])/($result['classprediction']['nbc_value_per_class'] - $result['lower_value']['nbc_value_per_class']))*100, 2)}} %</td> 
                           @else
                              <td class="text-center">0 %</td>
                           @endif
                        @endif   
                        </tr>
                     @endforeach
                  </tbody>
               </table>
               
               <p>Total Kata : {{ $result['result']['total_words']}}</p>
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
                     <a href="#" class="btn btn-primary disabled font-weight-bold" id="result_modified" role="button" aria-disabled="true">{{$result_modified['classprediction']['category']}}</a>
                     <table class="table">
                        <thead>
                              <tr>
                                 <th>Kategori</th>
                                 <th>Probabilitas</th>
                                 <th>Nilai Confidence</th>
                              </tr>
                        </thead>
                        <tbody>
                           @foreach ($result_modified['result']['category'] as $key => $value)
                              <tr>
                                 @if ($result_modified['classprediction']['category'] == $key)
                                    <td class="font-weight-bold">{{$key}}</td>
                                    <td class="font-weight-bold">{{$value['nbc_value_per_class']}}</td>
                                    <td class="font-weight-bold text-center">{{(($value['nbc_value_per_class'] - $result_modified['lower_value']['nbc_value_per_class']) / ($result_modified['classprediction']['nbc_value_per_class'] - $result_modified['lower_value']['nbc_value_per_class']))*100}} %</td>
                                 @else
                                    <td>{{$key}}</td>
                                    <td>{{$value['nbc_value_per_class']}}</td>
                                    @if (($value['nbc_value_per_class'] - $result_modified['lower_value']['nbc_value_per_class']) != 0)
                                       <td class="text-center">{{round((($value['nbc_value_per_class'] - $result_modified['lower_value']['nbc_value_per_class'])/($result_modified['classprediction']['nbc_value_per_class'] - $result_modified['lower_value']['nbc_value_per_class']))*100, 2)}} %</td> 
                                    @else
                                       <td class="text-center">0 %</td>
                                    @endif
                                 @endif
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                     
                     <p>Total Kata : {{ $result_modified['result']['total_words']}}</p>
                  @endif
               </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="card">
               <div class="card-header card-header-text card-header-primary">
               <div class="card-text">
                  <h4 class="card-title">Daftar Kata</h4>
               </div>
               </div>
               <div class="card-body">
                     @foreach ($result['result']['category'] as $key => $value)
                     <h4 class="card-title">{{$key}}</h4><span style="font-size:13px"> Count(C) : {{$value['words_count_in_category']}}</span>
                     <div class="card-text"> 
                        @foreach ($value['words'] as $word)
                           <div class="btn btn-rose btn-sm">
                              {{ $word['word']}} <span class="badge badge-pill badge-light">{{ "  " . $word['word_count']}}</span>
                           </div>
                        @endforeach
                     </div>
                     <br>
                     @endforeach
               </div>
         </div>
      </div>
   </div>
@endsection