<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý tài khoản nhân viên</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">



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
            <h1>Danh sách tài khoản nhân viên</h1>
          </div>
          <div class="col-sm-6">
            <a href="{{route('admin.user-register')}}" class="btn btn-primary float-right">Tạo tài khoản</a>
          </div>
          {{-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Simple Tables</li>
            </ol>
          </div> --}}
        </div>
      </div><!-- /.container-fluid -->
      @if (session('msg'))
        <div class="alert alert-warning">
            {{session('msg')}}
        </div>
      @endif
      @if (session('success-msg'))
        <div class="alert alert-success">
            {{session('success-msg')}}
        </div>
      @endif
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Danh sách tài khoản nhân viên</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 200px;">
                    <form method="GET" action="{{route('admin.user-search')}}">
                      <input type="text" name="search" class="form-control float-right search-user" placeholder="Tìm kiếm">
                    </form>
                    {{-- <input type="text" name="table_search" class="form-control float-right search-user" placeholder="Search">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-default user-search">
                        <i class="fas fa-search"></i>
                      </button>
                    </div> --}}
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 500px;">
                <table class="table table-bordered  table-hover table-head-fixed text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tài khoản</th>
                      <th>Mật khẩu</th>
                      <th>Họ và tên</th>
                      <th>Chức vụ</th>
                      <th>Email</th>
                      <th>Số điện thoại</th>
                      <th>Thời điểm tạo tài khoản</th>
                      <th>Thời điểm cập nhật</th>
                      <th>Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (!empty($users))
                        @foreach($users as $key => $item)
                        <tr>
                          <td>{{$item->UserID}}</td>
                          <td>{{$item->Account}}</td>
                          <td>{{$item->Password}}</td>
                          <td>{{$item->FirstName." ".$item->LastName}}</td>
                          <td>{{$item->Role}}</td>
                          <td>{{$item->Email}}</td>
                          <td>{{$item->Phone}}</td>
                          <td>{{date("d-m-Y H:i:s",strtotime($item->CreatedDate))}}</td>
                          @if (!empty($item->UpdateDate))
                              <td>{{date("d-m-Y H:i:s",strtotime($item->UpdateDate))}}</td>  
                          @else
                              <td></td>
                          @endif
                          <td>
                            <a href="{{route('admin.user-edit',['id' => $item->UserID])}}" class="btn btn-primary">Sửa</a>
                            <a href="{{route('admin.user-delete',['id' => $item->UserID])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');" class="btn btn-warning">Xóa</a>
                          </td>
                        </tr>
                        @endforeach
                    @endif
                  </tbody>
                </table>
                <div class="pagination">
                  {{ $users->links() }}
                </div>
                <style>
                    .pagination {
                      display: flex;
                      justify-content: center;
                    }

                    .pagination li {
                      display: block;
                    }
                </style>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
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
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>

</body>
</html>
