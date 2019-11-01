<div class="sidebar" data-color="purple" data-background-color="white">
   <!--
   Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

   Tip 2: you can also add an image using data-image tag
-->
   @php
      $active = explode('/', url()->current())[3]
   @endphp
   <div class="logo">
      <a href="http://www.creative-tim.com" class="simple-text logo-mini">
         CT
      </a>
      <a href="http://www.creative-tim.com" class="simple-text logo-normal">
         Creative Tim
      </a>
   </div>
   <div class="sidebar-wrapper">
      <ul class="nav">
         <li class="nav-item{{ $active == 'training' ? ' active' : ''}}">
         <a class="nav-link" href="{{route('training.index')}}">
            <i class="material-icons">dashboard</i>
            <p>Training</p>
         </a>
         </li>
         <li class="nav-item{{ $active == 'category' ? ' active' : ''}}">
            <a class="nav-link" href="{{route('category.index')}}">
               <i class="material-icons">person</i>
               <p>Category</p>
            </a>
         </li>
         <li class="nav-item{{ $active == 'classification' ? ' active' : ''}}">
            <a class="nav-link" href="{{route('classification.index')}}">
               <i class="material-icons">content_paste</i>
               <p>Classification</p>
            </a>
         </li>
         <!-- your sidebar here -->
      </ul>
   </div>
</div>