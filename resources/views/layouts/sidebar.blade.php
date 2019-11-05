<div class="sidebar" data-color="purple" data-background-color="white">
   <!--
   Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

   Tip 2: you can also add an image using data-image tag
-->
   @php
      $active = explode('/', url()->current())[3]
   @endphp
   <div class="logo">
      {{-- <a href="http://www.creative-tim.com" class="simple-text logo-mini">
         CT
      </a> --}}
      <a href="{{ url('/') }}" class="simple-text logo-normal">
         NBC and NBC Modified
      </a>
   </div>
   <div class="sidebar-wrapper">
      <ul class="nav">
         <li class="nav-item{{ $active == 'dashboard' ? ' active' : ''}}">
            <a class="nav-link" href="{{route('dashboard.index')}}">
               <i class="material-icons">
                  dashboard
               </i>
               <p>Beranda</p>
            </a>
         </li>
         <li class="nav-item{{ $active == 'training' ? ' active' : ''}}">
            <a class="nav-link" href="{{route('training.index')}}">
               <i class="material-icons">
                  list
               </i>
               <p>Latih</p>
            </a>
         </li>
         <li class="nav-item{{ $active == 'category' ? ' active' : ''}}">
            <a class="nav-link" href="{{route('category.index')}}">
               <i class="material-icons">
                  category
               </i>
               <p>Kategori</p>
            </a>
         </li>
         <li class="nav-item{{ $active == 'classification' ? ' active' : ''}}">
            <a class="nav-link" href="{{route('classification.index')}}">
                  <i class="material-icons">
                     text_fields
                  </i>                        
               <p>Klasifikasi</p>
            </a>
         </li>
         <li class="nav-item{{ $active == 'tool' ? ' active' : ''}}">
            <a class="nav-link" href="{{route('tool.index')}}">
                  <i class="material-icons">
                     build
                  </i>
               <p>Tools</p>
            </a>
         </li>
         <li class="nav-item{{ $active == 'setting' ? ' active' : ''}}">
            <a class="nav-link" href="{{route('setting.index')}}">
                  <i class="material-icons">
                     settings_applications
                  </i>
               <p>Pengaturan</p>
            </a>
         </li>
         <!-- your sidebar here -->
      </ul>
   </div>
</div>