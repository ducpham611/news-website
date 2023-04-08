<!DOCTYPE html>
<html>
<head>
<title>Phản hồi</title>
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
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="left_content">
          <div class="contact_area">
            <h2 style="font-family: 'Inter', sans-serif;">Phản hồi</h2>
            <p>Nếu các bạn có phản hồi, ý kiến muốn gửi cho chúng tôi, xin hay gửi qua đây. Chúng tôi rất mong nhận được phản hồi, ý kiến từ các bạn để website có thể hoạt động tốt hơn</p>
            <form method="POST" class="contact_form">
              {{-- <input class="form-control" type="text" placeholder="Name*">
              <input class="form-control" type="email" placeholder="Email*"> --}}
              <textarea class="form-control" cols="30" rows="10" placeholder="Ý kiến của bạn" name="content"></textarea>
              @error('content')
                  <span style="color:red;">{{$message}}</span>
              @enderror
              @csrf
              <button type="submit" class="btn btn-info pull-right" style="color:white;background-color:#70ad47;border-color:#70ad47;">Gửi ý kiến</button>
            </form>
          </div>
        </div>
      </div>
      {{-- <div class="col-lg-4 col-md-4 col-sm-4">
        <aside class="right_content">
          <div class="single_sidebar">
            <h2><span>Popular Post</span></h2>
            <ul class="spost_nav">
              <li>
                <div class="media wow fadeInDown"> <a href="single_page.html" class="media-left"> <img alt="" src="../images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 1</a> </div>
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> <a href="single_page.html" class="media-left"> <img alt="" src="../images/post_img2.jpg"> </a>
                  <div class="media-body"> <a href="single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 2</a> </div>
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> <a href="single_page.html" class="media-left"> <img alt="" src="../images/post_img1.jpg"> </a>
                  <div class="media-body"> <a href="single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 3</a> </div>
                </div>
              </li>
              <li>
                <div class="media wow fadeInDown"> <a href="single_page.html" class="media-left"> <img alt="" src="../images/post_img2.jpg"> </a>
                  <div class="media-body"> <a href="single_page.html" class="catg_title"> Aliquam malesuada diam eget turpis varius 4</a> </div>
                </div>
                
              </li>
            </ul>
          </div>
        </aside>
      </div> --}}
    </div>
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