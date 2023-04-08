<div class="container">
    <header id="header">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="header_top">
            <div class="header_top_left">
              <ul class="top_nav">
                <li><a href="/">Trang chủ</a></li>
                <li><a href="/contact">Phản hồi</a></li>
                <li><a href="/search-news">Tìm kiếm</a></li>
                @if (session('currentReader'))
                <li>
                  <div class="dropdown show">
                      <a class="dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{Session::get('currentReader')['name']}}
                      </a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="/logout" style="width: 172px; padding-top:10px; padding-bottom: 10px;">Đăng xuất</a>
                        <a href="{{route('edit-account',['id' => Session::get('currentReader')['id']])}}" class="dropdown-item" style="width: 172px; padding-top:10px; padding-bottom: 10px;">
                          Chỉnh sửa thông tin
                        </a>
                      </div>
                  </div>
                </li>
                @else
                    <li><a href="/register">Đăng ký</a></li>
                    <li><a href="/login">Đăng nhập</a></li>
                @endif
              </ul>
            </div>
            <div class="header_top_right">
              <p>{{$current_time}}</p>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="header_bottom">
            <div class="logo_area"><a href="/" class="logo"><img src="{{asset('assets/clients/images/Logo1.PNG')}}" alt=""></a></div>
            <!-- <div class="add_banner"><a href="#"><img src="images/addbanner_728x90_V1.jpg" alt=""></a></div> -->
          </div>
        </div>
      </div>
    </header>
    <section id="navArea">
      <nav class="navbar navbar-inverse" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav main_nav">
            <li class="active"><a href="/"><span class="fa fa-home desktop-home"></span><span class="mobile-show">Home</span></a></li>
            @if (!empty($categories))
              @foreach ($categories as $key => $item)
                <li>
                <a href="{{route('news-by-categories',['id' => $item->CateID])}}" style="font-family:Arial, Helvetica, sans-serif">
                  {{$item->CateName}}
                </a>
              </li>    
              @endforeach
            @endif
          </ul>
        </div>
      </nav>
    </section>
    <style>
      .sticky {
      position: sticky;
      top: 0;
      width: 100%;
      z-index: 1;
    }
    </style>
    
    
