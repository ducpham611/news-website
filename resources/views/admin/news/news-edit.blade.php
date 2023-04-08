<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chỉnh sửa bài viết</title>

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
    <a href="{{route('admin.dashboard')}}}" class="brand-link">
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
            <h1>Chỉnh sửa bài viết</h1>
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
                <h3 class="card-title">Chỉnh sửa bài viết</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" enctype="multipart/form-data">
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
                    <label for="exampleInputEmail1">Tiêu đề bài viết</label>
                    <input type="text" class="form-control" id="" placeholder="Tiêu đề bài viết" name="title" value="{{old('title') ?? $newsDetail->Title}}">
                    @error('title')
                        <span style="color:red;">{{$message}}</span>  
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Trích đoạn nội dung</label>
                    <textarea name="excerpt" id="" cols="30" rows="5"  class="form-control" placeholder="Trích đoạn">{{old('excerpt') ?? $newsDetail->Excerpt}}</textarea>
                    @error('excerpt')
                        <span style="color:red;">{{$message}}</span>  
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Chuyên mục</label>
                    <select class="form-select form-control" aria-label="Default select example" name="category">
                      <option value="{{$newsDetail->CateID}}" selected hidden>{{$newsDetail->CateName}}</option>
                      @if (!empty($categories))
                          @foreach ($categories as $key => $item)
                              <option value="{{$item->CateID}}">{{$item->CateName}}</option> 
                          @endforeach
                          @else
                              <option value="" disabled>Chưa có chuyên mục</option> 
                      @endif
                    </select>
                    @error('category')
                        <span style="color:red;">{{$message}}</span>  
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nội dung bài viết</label>
                    <textarea id="mytextarea" name="content" placeholder="Nội dung bài viết">{{$newsDetail->Content}}</textarea>
                    @error('content')
                        <span style="color:red;">{{$message}}</span>  
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="formFile" class="form-label">Hình ảnh</label>
                    <br>
                    <input type="file" id="formFile" name="image" accept="image/*"  onchange="showMyImage(this)">
                    <br>
                    <p style="font-weight: 600;">Ảnh mới</p>
                    <img id="thumbnil" style="width:30%; margin-top:10px; display:none;"  src="" alt="image"/>
                    @error('image')
                        <span style="color:red;">{{$message}}</span>
                    @enderror
                    @if (session('img-msg'))
                        <span style="color:red;">{{session('img-msg')}}</span>
                    @endif
                    <br>
                    <p style="font-weight: 600;">Ảnh cũ</p>
                    <img style="width: 800px; height: 500px;" src="{{asset('storage/images/'.$newsDetail->Image)}}" alt="">
                    <br>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Chú thích hình ảnh</label>
                    <input type="text" class="form-control" id="" placeholder="Chú thích hình ảnh" name="imageDescription" 
                    value="{{old('imageDescription') ?? $newsDetail->ImageDescription}}">
                    @error('imageDescription')
                      <span style="color:red;">{{$message}}</span>
                    @enderror 
                  </div>
                <!-- /.card-body -->
                @csrf
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" style="margin-left: -15px;">Cập nhật</button>
                  <a href="{{route('admin.news-management')}}" class="btn btn-warning">Quay lại</a>
                </div>
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
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- bs-custom-file-input -->
<script src="{{asset('assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.min.js')}}"></script>

{{-- <script src="https://cdn.tiny.cloud/1/wstnmzr0mh3r4vbfb6nzkxsyp4pgqagaelgvsvj9ghzdn461/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}
<script src="{{asset('assets/admin/tinymce/tinymce.min.js')}}"></script>
  
  <script>
    tinymce.init({
      selector: '#mytextarea',
      height: 500
    });
</script>
<script type="text/javascript">
function showMyImage(fileInput) {
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                continue;
            }           
            var img=document.getElementById("thumbnil");            
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
            img.style.display = "block";
        }    
    }
</script>
</body>
</html>
