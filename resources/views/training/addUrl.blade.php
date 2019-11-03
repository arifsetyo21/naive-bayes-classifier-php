@extends('layouts.app')

@section('title')
    Training Page
@endsection

@section('header', 'Tambah Data Training')

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
            <h4 class="card-title">Tambah Data Training</h4>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <form action="{{route('article.import')}}" method="post" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group float-left mr-2">
                    <label for="import_article">Choose File</label>
                    <input type="file" class="form-control-file" name="import_article" id="import_article" placeholder="" aria-describedby="fileHelpId">
                    {{-- <small id="fileHelpId" class="form-text text-muted">Help text</small> --}}
                  </div>
                  <button type="submit" class="btn btn-primary float-left">
                      <i class="material-icons">
                        cloud_upload
                      </i>
                      Upload Data Article 
                  </button>
                  <a class="btn btn-info" href="{{asset('contoh_data.xlsx')}}" download>Download Sample</a>
                  {{-- <div class="input-group mb-3">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="inputGroupFile02" name="import_article">
                      <label class="custom-file-label form-control " for="inputGroupFile02" aria-describedby="inputGroupFileAddon02"></label>
                    </div>
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-primary">
                          Upload
                      </button>
                    </div>
                  </div> --}}
                </form>
              </div>
            </div>
            <a href="http://"></a>
            <div class="row">
              <div class="col-md-12">
                <form action="{{route('training.storeUrl')}}" method="post">
                  @csrf
                  <div class="form-group">
                    <label for="url">Alamat Berita Kumparan</label>
                    <textarea name="url" class="form-control" id="url" rows="12" placeholder="https://kumparan.com/..., https://kumparan.com/...">{{old('url')}}</textarea>
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
                  {{-- <div class="form-group">
                    <label for="category">Kategori</label>
                    <select class="form-control" name="category_id" id="category">
                      @foreach ($categories as $category)
                      <option class="dropdown-menu" value="{{$category->id}}">{{$category->name}}</option>
                      @endforeach
                    </select>
                  </div> --}}
                  <button type="submit" class="btn btn-primary">Ambil Konten</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection