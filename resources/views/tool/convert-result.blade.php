@extends('layouts.app')

@section('title')
    Tool Konversi
@endsection

@section('header', 'Konversi Artikel ke Array JSON')

@section('css')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary float-right" onclick="copyClipboard()">
            <i class="material-icons">
                file_copy
            </i>
            Copy to Clipboard
        </button>
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                  <label for="">Nama[ID Kategori]</label>
                  <input type="text" class="form-control" aria-describedby="helpId" value="{{$category->name}}[{{$category->id}}]" disabled>
                </div>
                <div class="form-group">
                    <label for="article_result">Hasil Konversi</label>
                    <textarea class="form-control" id="article_result" rows="20">{{$article}}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
function copyClipboard(){
   var copyText = document.getElementById("article_result");
   copyText.select();
   copyText.setSelectionRange(0, 99999)
   document.execCommand("copy");
   alert("Copied the text: " + copyText.value);
}
@endsection