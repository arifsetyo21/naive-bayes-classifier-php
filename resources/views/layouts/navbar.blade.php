<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
   <div class="container-fluid">
      <div class="navbar-wrapper">
         <a class="navbar-brand" href="{{ URL::previous() }}">
            <button class="btn btn-default btn-sm">
            <i class="material-icons">
               arrow_back_ios
            </i>
               Back
            </button>
         </a>
         <a class="navbar-brand" href="#">@yield('header')</a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
         <span class="sr-only">Toggle navigation</span>
         <span class="navbar-toggler-icon icon-bar"></span>
         <span class="navbar-toggler-icon icon-bar"></span>
         <span class="navbar-toggler-icon icon-bar"></span>
      </button>
      {{-- <div class="collapse navbar-collapse justify-content-end">
         <ul class="navbar-nav">
         <li class="nav-item">
            <a class="nav-link" href="#pablo">
               <i class="material-icons">notifications</i> Notifications
            </a>
         </li>
         <form class="navbar-form">
            <div class="input-group no-border">
               <input type="text" value="" class="form-control" placeholder="Search...">
               <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
               </button>
            </div>
         </form>
         <!-- your navbar here -->
         </ul>
      </div> --}}
   </div>
</nav>