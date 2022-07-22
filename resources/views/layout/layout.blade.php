<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Web Harindra</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{url('assets/lte/fontawesome-free/css/all.min.css')}}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('assets/lte/adminlte.min.css')}}">

  <!--Old CSS-->

  <link rel="stylesheet" href="{{url('vendors/bootstrap/dist/css/bootstrap.min.css')}}">

  <link rel="stylesheet" href="{{url('assets/css/bootstrap-select.min.css')}}">
  <link rel="stylesheet" href="{{url('assets/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{url('assets/css/tablestyle.css')}}">
  <link rel="stylesheet" href="{{url('assets/css/tablemobile.css')}}">
  <link rel="stylesheet" href="{{url('assets/css/checkbox.css')}}">
  <link rel="stylesheet" href="{{url('assets/css/jquery-ui.css')}}">

</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Select Domain -->
        @if(Auth()->user()->role_id == 1 || str_contains(Auth()->user()->getRoleType->accessmenu,'PD01'))
        <li>
          <select name="headdomain" id="headdomain" class="form-control">
            @php
              $domain = DB::table('domain')->get();
              foreach($domain as $domains){
                if($domains->domain_code == Session::get('domain')){
                  echo '<option value="'.$domains->domain_code.'" Selected >'.$domains->domain_code.'</option>';
                }else{
                  echo '<option value="'.$domains->domain_code.'" >'.$domains->domain_code.'</option>';
                }
              }
            @endphp
          </select>
        </li>
        @endif

        <li>
          <a class="nav-link" role="button" data-toggle="dropdown">
            <i class="fas fa-user mr-2"></i>
            Hello, {{Session::get('name')}}
          </a>
          <div class="dropdown-menu dropdown=menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
              <i class="fas fa-power-off mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{url('/')}}" class="brand-link">
        <img src="{{url('images/imi.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">IMI Modules</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @can('access_dashboard')
            <li class="nav-item">
              <a href="{{url('/home')}}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Home
                </p>
              </a>
            </li>
            @endcan

            <li class="nav-header">TRANSAKSI</li>

            @can('access_so_side')
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-shopping-basket"></i>
                <p>
                  Sales Order
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('access_co')
                <li class="nav-item">
                  <a href="{{route('customerorder.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>CO Maintenance</p>
                  </a>
                </li>
                @endcan
                @can('access_so')
                <li class="nav-item">
                  <a href="{{route('salesorder.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>SO Maintenance</p>
                  </a>
                </li>
                @endcan
                @can('access_sj')
                <li class="nav-item">
                  <a href="{{route('suratjalan.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>SPK Maintenance</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('access_trip_side')
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-truck"></i>
                <p>
                  Trip
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('access_trip')
                <li class="nav-item">
                  <a href="{{route('tripmt.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Trip Browse</p>
                  </a>
                </li>
                @endcan
                @can('access_lapor_trip')
                <li class="nav-item">
                  <a href="{{route('laportrip.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lapor Trip MT</p>
                  </a>
                </li>
                @endcan
                @can('access_lapor_sj')
                <li class="nav-item">
                  <a href="{{route('laporsj.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lapor Surat Jalan</p>
                  </a>
                </li>
                @endcan
                @can('access_lapor_kerusakan')
                <li class="nav-item">
                  <a href="{{route('laporkerusakan.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lapor Kerusakan</p>
                  </a>
                </li>
                @endcan
                @can('access_rb')
                <li class="nav-item">
                  <a href="{{route('rbhist.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lapor Biaya Tambahan</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('access_drive_side')
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-shopping-basket"></i>
                <p>
                  Driver Check In
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('access_check_in_out')
                <li class="nav-item">
                  <a href="{{route('checkinout.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Check In / Out</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('access_report_side')
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>
                  Report  
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('access_report')
                <li class="nav-item">
                  <a href="{{route('report.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Report Maintenance</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcan


            @can('access_masters')
            <li class="nav-header">MASTER</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-wrench"></i>
                <p>
                  Setting
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('usermaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('rolemaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Role Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('accessrolemenu.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Role Menu Maintenance</p>
                  </a>
                </li>
                {{-- <li class="nav-item">
                  <a href="{{route('deptmaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Department Maintenance</p>
                  </a>
                </li> --}}
                <li class="nav-item">
                  <a href="{{route('customermaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Customer Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('custshipto.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ship To Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('shipfrom.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ship From Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('itemmaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Item Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('kerusakanmt.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Kerusakan Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('strukturkerusakanmt.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Struktur Kerusakan Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('truckmaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Truck Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('tipetruck.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tipe Truck Maintenance</p>
                  </a>
                </li>
                {{-- <li class="nav-item">
                  <a href="{{route('truckdrivemaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Truck Driver Maintenance</p>
                  </a>
                </li> --}}
                {{-- <li class="nav-item">
                  <a href="{{route('prefixmaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Prefix Maintenance</p>
                  </a>
                </li> --}}
                <li class="nav-item">
                  <a href="{{route('domainmaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Domain Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('qxwsa')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>WSA Qxtend Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('rute')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Rute Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('approvalmt')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Approval Maintenance</p>
                  </a>
                </li>

              </ul>
            </li>
            @endcan


          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
              @yield('breadcrumbs')
            </div>
          </div>
        </div>
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          @yield('content')
          <div id="loader" class="lds-dual-ring hidden overlay"></div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

  </div>
  <!-- ./wrapper -->


  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
            {{ __('Logout') }} </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="{{url('assets/css/jquery-3.2.1.min.js')}}"></script>
  <script src="{{url('assets/css/jquery-ui.js')}}"></script>
  <!--Date Picker-->
  <script src="{{url('vendors/popper.js/dist/umd/popper.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{url('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

  <script src="{{url('assets/js/bootstrap-select.min.js')}}"></script>
  <!-- AdminLTE -->
  <script src="{{url('assets/lte/adminlte.js')}}"></script>

  <script src="{{url('assets/css/select2.min.js')}}"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <!--Sweet Alert-->
  @include('sweetalert::alert')


  @yield('scripts')

  @if(session('errors'))
    <script type="text/javascript">
      var newerror = [];

      <?php
      foreach ($errors->all() as $err) {
        echo "newerror.push('" . $err . "');";
      }
      ?>
      var countnewerror = newerror.length;
      var newtext = '';
      for (var i = 0; i < countnewerror; i++) {

        newtext += '<li>' + newerror[i] + '</li>';
      }
      Swal.fire({
        icon: 'error',
        title: 'Error',
        html: newtext,
        showCloseButton: true,
      })
    </script>
  @endif

  <script type="text/javascript">
    $(document).ready(function() {
      if (window.innerWidth <= 576) {
        document.querySelector('body').classList.remove('open');
      } else {
        document.querySelector('body').classList.add('open');
      }


      window.addEventListener("resize", myFunction);

      function myFunction() {
        if (window.innerWidth <= 576) {
          document.querySelector('body').classList.remove('open');
        } else {
          document.querySelector('body').classList.add('open');
        }
      }
    });

    /** add active class and stay opened when selected */
    var url = window.location.href;
    let pecah = '';
    if(url.includes("?")){
      pecah = url.split('/');
      if(url.split("/").length > 4){
        url = pecah[0] + '/' + pecah[1] + '/' + pecah[2] + '/' + pecah[3];
      }
      url = url.split('?')[0];
    }else if (url.split("/").length > 4) {
      pecah = url.split('/');
      url = pecah[0] + '/' + pecah[1] + '/' + pecah[2] + '/' + pecah[3];
    }

    // for sidebar menu entirely but not cover treeview
    $('ul.nav-sidebar a').filter(function() {
      return this.href == url;
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
      return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview").prev('a').addClass('active');

    $('#headdomain').on('change',function($e){
      let domain = $(this).val();
      
      $.ajax("{{ route('changeDomain') }}", {
        method: 'Get',
        data: {
          "domain": domain
        },beforeSend: function(data){
						$('#loader').removeClass('hidden');
        },
        success: function(data) {
          location.reload();
        }
      });
    });
  </script>

</body>

</html>