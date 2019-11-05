@extends('layouts.app')

@section('title')
    Add Data Testing
@endsection

@section('header', 'Tambah Data Testing')

@section('css')

@endsection

@section('content')
   @if (session('status'))
       {{session('status')}}
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
        <a href="{{route('classification.list')}}" class="float-left mr-1">
            <button class="btn btn-fill btn-primary">
              <i class="material-icons">
                  list
              </i> Daftar Data Testing
            </button>
        </a>
      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-danger">
            <h4 class="card-title">Tambah Data Testing</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <form action="{{route('classification.storeDataTesting')}}" method="post">
                  @csrf
                  <div class="form-group">
                    <label for="url">Alamat Berita Kumparan</label>
                    <textarea name="url" class="form-control" id="url" rows="12" placeholder="https://kumparan.com/..., https://kumparan.com/...">{{old('url')}}</textarea>
                    <small style="color:red">{{$errors->first('url')}}</small>
                  </div>
                  <div class="form-group">
                    <label for="category">Kategori</label>
                    <select class="form-control" data-style="btn btn-link" name="real_category_id" id="category">
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