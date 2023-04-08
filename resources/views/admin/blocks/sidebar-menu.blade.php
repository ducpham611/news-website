<!-- Sidebar user (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
  <div class="info">
    @if (session('currentUser'))
      <a href="#" class="d-block">{{Session::get('currentUser')['name']}}</a>   
    @endif
    {{-- <a href="#" class="d-block">Alexander Pierce</a> --}}
    <a href="/admin/logout" class="d-block" style="margin-top: 20px;">
        Đăng xuất
    </a>
  </div>
</div>
<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
      <li class="nav-item">
        <a href="/admin/dashboard" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Dashboard
          </p>
        </a>
      </li>
      @if (session()->get('currentUser')['role'] == 'quản trị viên')
      <li class="nav-item">
        <a href="" class="nav-link">
          <i class="nav-icon fas fa-user"></i>
          <p>
            Quản lý tài khoản
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{route('admin.reader-management')}}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Tài khoản người đọc</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin.user-management')}}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Tài khoản nhân viên</p>
            </a>
          </li>
        </ul>
      </li>
      @endif
      @if (session()->get('currentUser')['role'] != 'quản trị viên')
      <li class="nav-item">
        <a href="" class="nav-link">
          <i class="nav-icon fas fa-newspaper"></i>
          <p>
            Quản lý bài viết
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if (session()->get('currentUser')['role'] == 'nhà báo' || session()->get('currentUser')['role'] == 'phóng viên')
          <li class="nav-item">
            <a href="{{route('admin.news-create')}}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Tạo bài viết mới</p>
            </a>
          </li>
          @endif 
          <li class="nav-item">
            <a href="{{route('admin.news-management')}}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Danh sách bài viết</p>
            </a>
          </li>
        </ul>
      </li>
      @endif
      @if (session()->get('currentUser')['role'] == 'tổng biên tập')
      <li class="nav-item">
        <a href="" class="nav-link">
          <i class="nav-icon fas fa-list-ul"></i>
          <p>
            Quản lý chuyên mục
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{route('admin.categories-create')}}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Tạo chuyên mục mới</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin.categories-management')}}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Danh sách chuyên mục</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin.categories-assign-manage')}}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Phân quyền chuyên mục</p>
            </a>
          </li>
        </ul>
      </li>
      @endif
      @if (session()->get('currentUser')['role'] == 'quản trị viên')
      <li class="nav-item">
        <a href="{{route('admin.comments-management')}}" class="nav-link">
          <i class="nav-icon fas fa-comments"></i>
          <p>
            Quản lý bình luận
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{route('admin.feedbacks-management')}}" class="nav-link">
          <i class="nav-icon fas fa-inbox"></i>
          <p>
            Quản lý phản hồi
          </p>
        </a>
      </li>
      @endif
      <li class="nav-item">
        <a href="{{route('admin.logs-management')}}" class="nav-link">
          <i class="nav-icon fas fa-clock"></i>
          <p>
            Lịch sử hoạt động
          </p>
        </a>
      </li>