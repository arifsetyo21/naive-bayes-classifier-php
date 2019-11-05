@extends('layouts.app')

@section('title')
    Tool Konversi
@endsection

@section('header', 'Konversi Artikel ke Array JSON')

@section('css')

@endsection

@section('content')
   @if (session('status'))
       {{session('status')}}
   @endif
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            <h4 class="card-title">Tool Konversi Artikel ke JSON Array</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <form action="{{route('tool.convertjson')}}" method="post">
                  @csrf
                  <div class="form-group">
                    <label for="url">Artikel Berita</label>
                    <textarea name="article" class="form-control" id="url" rows="12" placeholder="Seekor buaya di Florida, AS, ditemukan tewas termutilasi dengan kepala dan ekor terpisah pada Kamis, 31 Oktober 2019. Kasus ini menjadi kedua kalinya yang terjadi di Florida dalam kurun waktu kurang dari satu bulan. &#10; &#10; Bangkai buaya termutilasi itu ditemukan oleh Kino Belez, seorang yang sedang mengayuh kayak di Hosford Park Boat Ramp, di Sungai St. Lucie, kota Stuart, Florida, Amerika Serikat. &#10; &#10;Ini perlu diambil sebelum membusuk, ujar Kino Velez, dalam sebuah posting di Twitter.Terlihat dalam foto-foto yang beredar, sebagian besar tubuh buaya dipotong-potong, dengan kepala dan ekor menghilang.&#10; &#10;Fakta bahwa ekornya hilang, itu berarti mereka mati akibat dari perburuan, kata Velez kepada Orlando Sentinel.">{{old('url')}}</textarea>
                    <small style="color:red">{{$errors->first('url')}}</small>
                  </div>
                  <div class="form-group">
                    <label for="category">Kategori</label>
                    <select class="form-control" data-style="btn btn-link" name="category_id" id="category">
                      <option disabled selected value> -- pilih kategori -- </option>
                      @foreach ($categories as $category)
                      <option value="{{$category->id}}">{{$category->name}}</option>
                      @endforeach
                    </select>
                    <small style="color:red">{{$errors->first('category_id')}}</small>
                  </div>
                  <button type="submit" class="btn btn-primary">Konversi</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection