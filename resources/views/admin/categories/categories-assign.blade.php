<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Phân quyền chuyên mục tin tức</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">

  <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-multiselect.css')}}" type="text/css"/>


</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route('admin.dashboard')}}" class="nav-link">Dashboard</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
      {{-- <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      @include('admin.blocks.sidebar-menu')
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Phân quyền chuyên mục tin tức</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Phân quyền chuyên mục tin tức</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST">
                @if (session('msg'))
                    <div class="alert alert-success">
                      {{session('msg')}}
                    </div>
                @endif
                @if ($errors->any())
                  <div class="alert alert-danger">Dữ liệu nhập vào không hợp lệ. Vui lòng kiểm tra lại</div>
                @endif
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Tên chuyên mục</label>
                    <select name="selection[]" class="form-control multiple-select" style="width: 75%" id="" multiple>
                      @if (!empty($categories))
                        @foreach ($categories as  $key => $item)
                        @if (in_array($item->CateID,$result))
                          <option value="{{$item->CateID}}" selected>{{$item->CateName}}</option>      
                        @elseif(in_array($item->CateID,$result) == false)
                          <option value="{{$item->CateID}}">{{$item->CateName}}</option>      
                        @endif
                        @endforeach
                      @endif
                    </select>
                    @error('category')
                      <span style="color:red;">{{$message}}</span>
                    @enderror 
                  </div>
                </div>
                <!-- /.card-body -->
                @csrf
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Phân quyền</button>
                  <a href="{{route('admin.categories-assign-manage')}}" class="btn btn-warning">Quay lại</a>
                </div>
              </form>
            </div>
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
          <!-- right column -->
          
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
{{-- <script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script> --}}
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- bs-custom-file-input -->
<script src="{{asset('assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>

{{-- <script type="text/javascript" src="{{asset('assets/admin/js/adminlte.min.js')}}"></script> --}}
 
<script type="text/javascript" src="{{asset('assets/admin/js/bootstrap-multiselect.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function() {
      $('.multiple-select').multiselect({
          buttonText: function(options, select) {
              if (options.length === 0) {
                  return 'Chọn chuyên mục ...';
              }
               else {
                   var labels = [];
                   options.each(function() {
                       if ($(this).attr('label') !== undefined) {
                           labels.push($(this).attr('label'));
                       }
                       else {
                           labels.push($(this).html());
                       }
                   });
                   return labels.join(', ') + '';
               }
          }
      });
  });
</script>
</body>
</html>
