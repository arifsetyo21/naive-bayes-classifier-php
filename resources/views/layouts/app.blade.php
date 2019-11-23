  <!--
  =========================================================
  Material Dashboard - v2.1.1
  =========================================================

  Product Page: https://www.creative-tim.com/product/material-dashboard
  Copyright 2019 Creative Tim (https://www.creative-tim.com)
  Licensed under MIT (https://github.com/creativetimofficial/material-dashboard/blob/master/LICENSE.md)

  Coded by Creative Tim

  =========================================================

  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->

<!doctype html>
<html lang="en">
  
  <head>
    <title>@yield('title') | Klasifikasi NBC</title>
    <!-- Title Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="{{asset('css/material-dashboard.css')}}" rel="stylesheet" />
    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="{{asset('css/sweetalert2/sweetalert2.min.css')}}">
    <script src="{{asset('js/core/jquery.min.js')}}"></script>
    <style>
      @yield('css');
    </style>
  </head>
 
  <body>
    <div class="wrapper ">
      @include('layouts.sidebar')
      <div class="main-panel">
        @include('layouts.navbar')
        <div class="content">
          <div class="container-fluid">
            <!-- your content here -->
            @yield('content')
          </div>
        </div>
        @include('layouts.footer')
      </div>
    </div>
    <!-- Chart.js -->
    <script src="{{asset('js/chart.js')}}"></script>
    <script>
      @yield('js')
    </script>
    @include('sweetalert::alert')
    <script src="{{asset('js/core/jquery.min.js')}}"></script>
    <script src="{{asset('js/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('js/core/popper.min.js')}}"></script>
    <script src="{{asset('js/core/bootstrap-material-design.min.js')}}"></script>
    <script src="{{asset('js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>
    <!-- Plugin for the momentJs  -->
    <script src="{{asset('js/plugins/moment.min.js')}}"></script>
    <!--  Plugin for Sweet Alert -->
    <script src="{{asset('js/plugins/sweetalert2.js')}}"></script>
    <!-- Forms Validations Plugin -->
    <script src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
    <script src="{{asset('js/plugins/jquery.bootstrap-wizard.js')}}"></script>
    <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="{{asset('js/plugins/bootstrap-selectpicker.js')}}"></script>
    <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <script src="{{asset('js/plugins/bootstrap-datetimepicker.min.js')}}"></script>
    <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="{{asset('js/plugins/jquery.dataTables.min.js')}}"></script>
    <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
    <script src="{{asset('js/plugins/bootstrap-tagsinput.js')}}"></script>
    <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="{{asset('js/plugins/jasny-bootstrap.min.js')}}"></script>
    <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
    <script src="{{asset('js/plugins/fullcalendar.min.js')}}"></script>
    <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
    <script src="{{asset('js/plugins/jquery-jvectormap.js')}}"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="{{asset('js/plugins/nouislider.min.js')}}"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <!-- Library for adding dinamically elements -->
    <script src="{{asset('js/plugins/arrive.min.js')}}"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkHLeQsyxikJxATBG3Y5Kq8z7KaJ7MAG0&callback"></script>
    <!-- Chartist JS -->
    <script src="{{asset('js/plugins/chartist.min.js')}}"></script>
    <!--  Notifications Plugin    -->
    <script src="{{asset('js/plugins/bootstrap-notify.js')}}"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{asset('js/material-dashboard.js?v=2.1.1" type="text/javascript')}}"></script>
  </body>
  
</html>