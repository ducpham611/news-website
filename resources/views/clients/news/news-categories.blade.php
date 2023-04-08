<!DOCTYPE html>
<html>
<head>
<title>Tin tức {{$categoryName}}</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/font.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/li-scroller.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/slick.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/jquery.fancybox.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/theme.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/clients/css/style.css')}}">
<!--[if lt IE 9]>
<script src="../assets/js/html5shiv.min.js"></script>
<script src="../assets/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
{{-- <div id="preloader">
  <div id="status">&nbsp;</div>
</div> --}}
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
@include('clients.blocks.header')
  <section id="contentSection">

    @if (!empty($news))
      @foreach ($news as $key => $item)
      <div class="row" style="margin-top: 20px;margin-bottom: 20px;">
        <div class="container">
           <div class="col-lg-2 col-md-2 col-sm-2">
            <img src="{{asset('storage/images/'.$item->Image)}}" alt="" width="177" height="140" style="margin-left:-15px;">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6">
            <a href="{{route('news-detail',['id' => $item->NewsID])}}" style="font-family: Arial, Helvetica, sans-serif; font-weight: 600; font-size: 20px;line-height: 28px;">{{$item->Title}}</a>
            <div class="article-excerpt">
              <a href="{{route('news-detail',['id' => $item->NewsID])}}" style="line-height: 22px;">
                {{$item->Excerpt}}
              </a>
            </div>
          </div>
        </div>
      </div>
      @endforeach        
    @endif
    <div class="pagination">
      {{ $news->links() }}
    </div>
    <style>
        .pagination {
          display: flex;
          /* justify-content: center; */
        }

        .pagination li {
          display: block;

        }
        .pagination li a:hover{
          background-color: #70ad47;
          border-color: #70ad47;
        }
    </style>
    {{-- <div class="row" style="margin-top: 20px;margin-bottom: 20px;">
      <div class="container">
         <div class="col-lg-2 col-md-2 col-sm-2">
          <img src="https://icdn.dantri.com.vn/zoom/210_140/2022/06/23/tuthieu-1655970826398.jpeg" alt="" width="177" height="140" style="margin-left:-15px;">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
          <h3 style="margin-top: -4px;font-family: Arial, Helvetica, sans-serif; font-weight: 600;">Bộ Công an nêu bất cập khi công dân có quá nhiều loại giấy tờ tùy thân </h3>
          <div class="article-excerpt">
            <a href="">
              Trich doan
            </a>
          </div>
          <div class="article-category">
            <a href="" class="article-category">The thao</a>
          </div>
        </div>
      </div>
    </div> --}}
    
    {{-- <div class="row" style="margin-top: 20px;margin-bottom: 20px;">
      <div class="container">
         <div class="col-lg-2 col-md-2 col-sm-2">
          <img src="https://icdn.dantri.com.vn/zoom/210_140/2022/06/23/tuthieu-1655970826398.jpeg" alt="" width="177" height="140" style="margin-left:-15px;">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
          <h3 style="margin-top: -4px;font-family: Arial, Helvetica, sans-serif; font-weight: 600;">Bộ Công an nêu bất cập khi công dân có quá nhiều loại giấy tờ tùy thân </h3>
          <div class="article-excerpt">
            <a href="">
              Trich doan
            </a>
          </div>
          <div class="article-category">
            <a href="" class="article-category">The thao</a>
          </div>
        </div>
      </div>
    </div> --}}
  </section>

  <footer id="footer">
    <div class="footer_top">
      <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5">
          <div class="footer_widget wow fadeInDown">
            <h2>Thông tin</h2>
            <div class="footer_widget wow fadeInRightBig" style="min-height: 0px;">
              <p>Cơ quan của Bộ Lao động - Thương binh và Xã hội</p>
              <p>Tổng biên tập: Chi Lan</p>
              <p>Địa chỉ tòa soạn: Nhà 1248, ngõ 2122, Đống Đa, Hà Nội</p>
              <p>Điện thoại: 024-3736-6491. Fax: 024-3736-6491</p>
              <p>Hotline HN: 0973-567-567. Hotline TP HCM: 0974-567-567</p> 
              <p>Email: info@new-website.com.vn</p>   
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer_bottom">
      <p class="copyright">&copy; 2022 Bản quyền thuộc về Báo điện tử NewsWebsite.</p>
    </div>
  </footer>
</div>
<script src="{{asset('assets/clients/js/jquery.min.js')}}"></script> 
<script src="{{asset('assets/clients/js/wow.min.js')}}"></script> 
<script src="{{asset('assets/clients/js/bootstrap.min.js')}}"></script> 
<script src="{{asset('assets/clients/js/slick.min.js')}}"></script> 
<script src="{{asset('assets/clients/js/jquery.li-scroller.1.0.js')}}"></script> 
<script src="{{asset('assets/clients/js/jquery.newsTicker.min.js')}}"></script> 
<script src="{{asset('assets/clients/js/jquery.fancybox.pack.js')}}"></script> 
<script src="{{asset('assets/clients/js/custom.js')}}"></script> 
<script type="text/javascript">
  window.onscroll = function() {myFunction()};

  var header = document.getElementById("navArea");
  var sticky = header.offsetTop;

  function myFunction() {
    if (window.pageYOffset > sticky) {
      header.classList.add("sticky");
    } else {
      header.classList.remove("sticky");
    }
  }
</script>
</body>
</html>