<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
      {{-- <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
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
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
         <!-- Small Box (Stat card) -->
         <div class="row">
            @if (!empty($userCount) && session()->get('currentUser')['role'] == 'quản trị viên')
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$userCount}}</h3>
  
                  <p>Tài khoản nhân viên</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user"></i>
                </div>
              </div>
            </div>
            @endif

            @if (!empty($readerCount) && session()->get('currentUser')['role'] == 'quản trị viên')
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$readerCount}}</h3>
  
                  <p>Tài khoản người đọc</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user"></i>
                </div>
              </div>
            </div>
            @endif

            @if (!empty($newsCount))
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$newsCount}}</h3>
  
                  <p>Bài viết hiện tại</p>
                </div>
                <div class="icon">
                  <i class="fas fa-newspaper"></i>
                </div>
              </div>
            </div>
            @else
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>0</h3>
  
                  <p>Bài viết hiện tại</p>
                </div>
                <div class="icon">
                  <i class="fas fa-newspaper"></i>
                </div>
              </div>
            </div>
            @endif

            @if (!empty($newsWaitCount))
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-secondary">
                <div class="inner">
                  <h3>{{$newsWaitCount}}</h3>
  
                  <p>Bài viết chờ phê duyệt</p>
                </div>
                <div class="icon">
                  <i class="fas fa-newspaper"></i>
                </div>
              </div>
            </div>
            @else
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-secondary">
                <div class="inner">
                  <h3>0</h3>
  
                  <p>Bài viết chờ phê duyệt</p>
                </div>
                <div class="icon">
                  <i class="fas fa-newspaper"></i>
                </div>
              </div>
            </div>
            @endif

            @if (!empty($newsAcceptedCount))
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{$newsAcceptedCount}}</h3>
  
                  <p>Bài viết được chấp thuận</p>
                </div>
                <div class="icon">
                  <i class="fas fa-newspaper"></i>
                </div>
              </div>
            </div>
            @else
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>0</h3>
  
                  <p>Bài viết được chấp thuận</p>
                </div>
                <div class="icon">
                  <i class="fas fa-newspaper"></i>
                </div>
              </div>
            </div>
            @endif

            @if (!empty($newsNotAcceptedCount))
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>{{$newsNotAcceptedCount}}</h3>
  
                  <p style="max-width: 219px;word-break: break-word;white-space: normal;">Bài viết không chấp thuận</p>
                </div>
                <div class="icon">
                  <i class="fas fa-newspaper"></i>
                </div>
              </div>
            </div>
            @else
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>0</h3>
  
                  <p style="max-width: 219px;word-break: break-word;white-space: normal;">Bài viết không chấp thuận</p>
                </div>
                <div class="icon">
                  <i class="fas fa-newspaper"></i>
                </div>
              </div>
            </div>
            @endif

            @if (!empty($categoriesCount))
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$categoriesCount}}</h3>
  
                  <p>Chuyên mục tin tức</p>
                </div>
                <div class="icon">
                  <i class="fas fa-list-ul"></i>
                </div>
              </div>
            </div>
            @endif

            @if (!empty($commentsCount))
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$commentsCount}}</h3>
  
                  <p>Bình luận</p>
                </div>
                <div class="icon">
                  <i class="fas fa-comments"></i>
                </div>
              </div>
            </div>
            @endif

            @if (!empty($feedbacksCount) && session()->get('currentUser')['role'] == 'quản trị viên')
            <div class="col-lg-3 col-6">
              <!-- small card -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$feedbacksCount}}</h3>
  
                  <p>Phản hồi</p>
                </div>
                <div class="icon">
                  <i class="fas fa-inbox"></i>
                </div>
              </div>
            </div>
            @endif

            {{-- Charts --}}
            {{-- <div class="col-lg-3 col-6" style="margin-bottom: 20px;">
              <div id="column_chart"></div>
              <br>
              <div id="column_chart1"></div>
            </div> --}}
            @if (session()->get('currentUser')['role'] == 'quản trị viên')
            <table class="columns">
              <tr>
                <td class="col-lg-3 col-6">
                  <h3 style="text-align: left;" class="piechartheader">Số tài khoản người đọc tạo mới trong 1 tuần qua</h3>
                  <div id="column_chart5" style="border: 1px solid #ccc"></div>
                </td>
                <br>
                <td class="col-lg-3 col-6">
                  <h3 style="text-align: left;" class="piechartheader">Số phản hồi trong 1 tuần qua</h3>
                  <div id="column_chart3" style="border: 1px solid #ccc"></div>
                </td>
              </tr>
            </table>
            @endif

            @if (session()->get('currentUser')['role'] == 'tổng biên tập')
            <table class="columns">
              <tr>
                <td class="col-lg-3 col-6">
                  <h3 style="text-align: left;" class="piechartheader">Chuyên mục nhiều bài viết</h3>
                  <div id="column_chart" style="border: 1px solid #ccc"></div>
                </td>
                <br>
                <td class="col-lg-3 col-6">
                  <h3 style="text-align: left;" class="piechartheader">Bài viết nhiều lượt xem</h3>
                  <div id="column_chart1" style="border: 1px solid #ccc; "></div>
                </td>
              </tr>
              <tr>
                {{-- <td class="col-lg-3 col-6">
                  <h3 style="text-align: left;" class="piechartheader">Bài viết có nhiều bình luận </h3>
                  <div id="column_chart2" style="border: 1px solid #ccc"></div>
                </td>
                <br> --}}
                {{-- <td class="col-lg-3 col-6">
                  <h3 style="text-align: left;" class="piechartheader">Số bài viết được đăng trong 1 tuần qua</h3>
                  <div id="column_chart4" style="border: 1px solid #ccc"></div>
                </td> --}}
                {{-- <td class="col-lg-3 col-6">
                  <h3 style="text-align: left;" class="piechartheader">Số phản hồi trong 1 tuần qua</h3>
                  <div id="column_chart3" style="border: 1px solid #ccc"></div>
                </td> --}}
              </tr>
            </table>
            <table class="columns">
              <tr>
                <td class="col-lg-6 col-12">
                  <h3 style="text-align: left;" class="piechartheader">Bài viết có nhiều bình luận </h3>
                  <div id="column_chart2" style="border: 1px solid #ccc"></div>
                </td>
              </tr>
            </table>
            <table class="columns">
              <tr>
                <td class="col-lg-6 col-12">
                  <h3 style="text-align: left;" class="piechartheader">Số bài viết được đăng trong 1 tuần qua</h3>
                  <div id="column_chart4" style="border: 1px solid #ccc"></div>
                </td>
              </tr>
            </table>
            @endif
            
           <!-- ./col -->
         </div>
         <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    
    <!-- /.content -->
    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
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

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    var analytics = <?php echo $top_categories; ?>;
    var analytics1 = <?php echo $top_view_news; ?>;
    var analytics2 = <?php echo $top_comments_news; ?>;
    var analytics3 = <?php echo $feedback_count; ?>;
    var analytics4 = <?php echo $news_upload_oneweek; ?>;
    var analytics5 = <?php echo $reader_account_oneweek; ?>;






    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChartTopViewNews);
    google.charts.setOnLoadCallback(drawChartTopCommentsNews);
    google.charts.setOnLoadCallback(drawChartFeedBacksCount);
    google.charts.setOnLoadCallback(drawChartNewsUploadOneWeek);
    google.charts.setOnLoadCallback(drawChartReadersCreateOneWeek);





    function drawChart()
    {
      var data = google.visualization.arrayToDataTable(analytics);
      var options = {
        // title : 'Top 3 chuyên mục có nhiều bài viết nhất',
        'width': 616,
        'height': 400,
        'is3D':true,
      };
      var chart = new google.visualization.ColumnChart(
        document.getElementById('column_chart')
      );
      chart.draw(data, options);
    }

    function drawChartTopViewNews()
    {
      var data = google.visualization.arrayToDataTable(analytics1);
      var options = {
        // title : 'Top 3 bài viết có nhiều lượt xem nhất',
        'width': 600,
        'height': 400,
        'is3D':true,
      };
      var chart = new google.visualization.ColumnChart(
        document.getElementById('column_chart1')
      );
      chart.draw(data, options);
    }

    function drawChartTopCommentsNews()
    {
      var data = google.visualization.arrayToDataTable(analytics2);
      var options = {
        // title : 'Top 3 bài viết có nhiều lượt xem nhất',
        'width': 1240,
        'height': 500,
        'is3D':true,
      };
      var chart = new google.visualization.ColumnChart(
        document.getElementById('column_chart2')
      );
      chart.draw(data, options);
    }

    function drawChartFeedBacksCount()
    {
      var data = google.visualization.arrayToDataTable(analytics3);
      var options = {
        'width': 600,
        'height': 400,
      };
      var chart = new google.visualization.PieChart(
        document.getElementById('column_chart3')
      );
      chart.draw(data, options);
    }

    function drawChartNewsUploadOneWeek()
    {
      var data = google.visualization.arrayToDataTable(analytics4);
      var options = {
        'width': 1240,
        'height': 400,
      };
      var chart = new google.visualization.LineChart(
        document.getElementById('column_chart4')
      );
      chart.draw(data, options);
    }

    function drawChartReadersCreateOneWeek()
    {
      var data = google.visualization.arrayToDataTable(analytics5);
      var options = {
        'width': 600,
        'height': 400,
      };
      var chart = new google.visualization.LineChart(
        document.getElementById('column_chart5')
      );
      chart.draw(data, options);
    }
</script>
</body>
</html>
