@extends('layouts.app')

@section('title')
   Setting
@endsection

@section('header', 'Pengaturan')

@section('content')

@if (session('status'))
    {{ session('status') }}
@endif
   <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-12">
         <div class="card">
            <div class="card-header card-header-warning">
               <h4 class="card-title align-middle">
                  <i class="material-icons">
                     settings_applications
                  </i>Pengaturan
               </h4>               
            </div>
            <div class="card-body table-responsive">
               <h4>Bersihkan Data Training</h4>
               <form action="{{route('setting.destroy')}}" method="post" onsubmit="return confirm('Hapus Semua Data ?')">
                  @csrf
                  <input type="hidden" name="_method" value="delete">
                  <button type="submit" class="btn btn-danger btn-sm" role="button">Bersihkan</button>
               </form>
            </div>
         </div>
      </div>
      <div class="col-lg-8 col-md-6 col-sm-12">
         <div class="card">
               <div class="card-header card-header-icon card-header-rose">
               <div class="card-icon">
                  <i class="material-icons">language</i>
               </div>
               </div>
               <div class="card-body">
                  <h4 class="card-title">NBC Classification Application</h4>
                  <div class="list-group">
                     <a href="#" class="list-group-item list-group-item-action active">
                        <h4>0.1.0</h4>
                        <li>Menyelesaikan tampilan hasil klasifikasi (Oke)</li>
                        <li>Menyelesaikan fitur hapus semua data/bersihkan data</li>
                        <li>Menyelesaikan fitur update category (oke)</li>
                        <li>Menyelesaikan fitur preproses semua </li>
                        <li>Menyelesaikan tampilan detail data training (oke)</li>
                        <li>Menyelesaikan fitur klasifikasi langsung banyak</li>
                        <li>Tampilan Ringkasan Uji Coba</li>
                     </a>
                  </div>
               </div>
         </div>
      </div>
   </div>
@endsection
