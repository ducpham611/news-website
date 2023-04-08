<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Danh sách các bài viết</title>

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
      <!-- SidebarSearch Form -->
      {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}

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
            <h1>Danh sách các bài viết</h1>
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

                <h3 class="card-title">Danh sách các bài viết</h3>              
                  <form action="{{route('admin.news-search')}}" method="GET">
                    <div class="row">
                        <div class="col offset-4">
                          <label class="input-group-text" for="inputGroupSelect01" style="padding-left: 15%;">Trạng thái</label>
                        </div>
                        <select class="form-select col-2" id="statusSelect" name="status">
                          <option value="all" selected>Tất cả</option>
                          <option value="Chờ phê duyệt">Chờ phê duyệt</option>
                          <option value="Không chấp thuận">Không chấp thuận</option>
                          <option value="Chấp thuận">Chấp thuận</option>
                        </select>
                      <div class="col-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm">
                      </div>
                      <div class="col">
                        <button type="submit" class="btn btn-default">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                </form>
                {{-- </div> --}}
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered  table-hover table-head-fixed text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th style="min-width: 280px;">Tiêu đề</th>
                      <th>Chuyên mục</th>
                      <th>Tác giả</th>
                      {{-- <th>Thời điểm viết</th> --}}
                      <th>Trạng thái</th>
                      {{-- <th>Người phê duyệt</th> --}}
                      {{-- <th>Thời điểm phê duyệt</th> --}}
                      {{-- <th>Lý do</th> --}}
                      {{-- <th>Thời điểm cập nhật</th> --}}
                      <th>Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (!empty($news))
                        @foreach($news as $key => $item)
                        <tr>
                          <td>{{$item->NewsID}}</td>
                          <td style="min-width: 280px;word-break: break-word;white-space: normal;">
                            {{-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                              {{$item->Title}}
                            </button> --}}
                            <a style="
                            background-color: transparent;
                            /* background-repeat: no-repeat; */
                            border: none;
                            cursor: pointer;
                            overflow: hidden;
                            outline: none;
                            color: black;
                            margin-left:0;
                            padding-left: 0;"
                                class="btn btn-primary" data-toggle="modal"
                                data-target="#modal-default-{{$item->NewsID}}">
                                {{ $item->Title }}</a>

                            {{-- {{$item->Title}} --}}
                          </td>
                          <td>{{$item->CateName}}</td>
                          <td>{{$item->AuthorFirstName ." ".$item->AuthorLastName}}</td>
                          {{-- <td>{{date("d-m-Y H:i:s",strtotime($item->CreatedDate))}}</td> --}}
                          @if ($item->Status == 'Chờ phê duyệt')
                              <td><span class="btn btn-secondary">Chờ phê duyệt</span></td>
                          @elseif($item->Status == 'Chấp thuận')
                              <td><span class="btn btn-success">{{$item->Status}}</span></td>
                          @else
                              <td><span class="btn btn-danger">{{$item->Status}}</span></td>
                          @endif
                          {{-- <td>{{$item->ApproverFirstName. " ".$item->ApproverLastName}}</td> --}}
                          {{-- @if (!empty($item->ApprovedDate))
                              <td>{{date("d-m-Y H:i:s",strtotime($item->ApprovedDate))}}</td>   
                          @else
                              <td></td>   
                          @endif --}}
                          {{-- <td>{{$item->Reason}}</td> --}}
                          {{-- @if (!empty($item->UpdateDate))
                              <td>{{date("d-m-Y H:i:s",strtotime($item->UpdateDate))}}</td>   
                          @else
                              <td></td>   
                          @endif --}}
                          <td>
                            @if (session()->get('currentUser')['role'] == 'biên tập viên' || session()->get('currentUser')['role'] == 'tổng biên tập')
                              <a href="{{route('admin.news-detail',['id' => $item->NewsID])}}" class="btn btn-primary">Phê duyệt</a>
                            @else
                              <a href="{{route('admin.news-detail',['id' => $item->NewsID])}}" class="btn btn-primary">Xem chi tiết</a>
                            @endif
                            <a href="{{route('admin.news-edit',['id' => $item->NewsID])}}" class="btn btn-primary">Sửa</a>
                            <a href="{{route('admin.news-delete',['id' => $item->NewsID])}}" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');" class="btn btn-warning">Xóa</a>
                          </td>
                        </tr>


                        <div class="modal fade" id="modal-default-{{$item->NewsID}}">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Thông tin bài viết</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <p>Thời điểm viết: {{date("d-m-Y H:i:s",strtotime($item->CreatedDate))}}</p>
                                <p>Người phê duyệt: {{$item->ApproverFirstName. " ".$item->ApproverLastName}}</p>
                                @if (!empty($item->ApprovedDate))
                                  <p>Thời điểm phê duyệt: {{date("d-m-Y H:i:s",strtotime($item->ApprovedDate))}}</p>   
                                @else
                                  <p>Thời điểm phê duyệt: </p>   
                                @endif
                                <p>Lý do: {{$item->Reason}}</p>
                                @if (!empty($item->UpdateDate))
                                  <p>Thời điểm cập nhật: {{date("d-m-Y H:i:s",strtotime($item->UpdateDate))}}</p>   
                                @else
                                  <p>Thời điểm cập nhật: </p>   
                                @endif
                                {{-- <p>One fine body&hellip;</p> --}}
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                <a href="{{route('admin.news-edit',['id' => $item->NewsID])}}" class="btn btn-primary">Sửa</a>
                              </div>
                            </div>
                            <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
                        </div>

                        @endforeach
                        @else
                        <tr>
                          <td colspan="7">Chưa có bài viết nào</td>
                        </tr>
                    @endif
                  </tbody>
                </table>
                <div class="pagination">
                  {{ $news->links() }}
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
{{-- <script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script> --}}
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>


<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>

{{-- <script type="text/javascript">
  $(document).ready(function(){
    $('#statusSelect').change(function(){
      // alert($(this).val());
      var statusSearch = $(this).val();
      $.ajax({
        url: '/admin/news-status-search',
        type: 'GET',
        data: {
          'status' : statusSearch,
        },
        success: function(response)
        {
          $( "html" ).html( response );
        }
      })
    });
  })
</script> --}}
</body>
</html>
