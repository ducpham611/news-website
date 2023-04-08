<!DOCTYPE html>
<html>
<head>
<title>NewsWebsite</title>
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">


<!--[if lt IE 9]>
<script src="assets/js/html5shiv.min.js"></script>
<script src="assets/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
{{-- <div id="preloader">
  <div id="status">&nbsp;</div>
</div> --}}
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>

@include('clients.blocks.header')

  <section id="sliderSection">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="slick_slider">
          @if (!empty($randomNews))
              @foreach ($randomNews as $key => $item)
              <div class="single_iteam"> <a href="{{route('news-detail',['id' => $item->NewsID])}}"> <img src="{{asset('storage/images/'.$item->Image)}}" alt=""></a>
                <div class="slider_article">
                  <h2 style="font-family: 'Inter', sans-serif;"><a class="slider_tittle" href="{{route('news-detail',['id' => $item->NewsID])}}">{{$item->Title}}</a></h2>
                  <p style="font-family: 'Inter', sans-serif;">{{$item->Excerpt}}</p>
                </div>
              </div>
              @endforeach
          @endif
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="latest_post">
          <h2 style="font-family: 'Inter', sans-serif;"><span>Mới nhất</span></h2>
          <div class="latest_post_container">
            <div id="prev-button"><i class="fa fa-chevron-up"></i></div>
            <ul class="latest_postnav">
              @if (!empty($featureNews))
              @foreach ($featureNews as $key => $item)
              <li>
                <div class="media"> <a href="{{route('news-detail',['id' => $item->NewsID])}}" class="media-left"> <img alt="" src="{{asset('storage/images/'.$item->Image)}}"> </a>
                  <div class="media-body" style="font-family: 'Inter', sans-serif;"> <a href="{{route('news-detail',['id' => $item->NewsID])}}" class="catg_title">{{$item->Title}}</a> </div>
                </div>
              </li>
              @endforeach
              @endif
            </ul>
            <div id="next-button"><i class="fa  fa-chevron-down"></i></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="contentSection">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="left_content">
          @if (!empty($newsSend))
              @foreach ($cateHasNews as $key => $item)
                  {{-- <p>{{$item->CateName}}</p> --}}
                  <div class="single_post_content">
                    <h2 style="font-family: 'Inter', sans-serif;"><span>{{$item->CateName}}</span></h2>
                    @foreach ($newsSend[$item->CateID] as $key1 => $item1)
                      @if ($key1 == 0)
                      <div class="single_post_content_left">
                        <ul class="business_catgnav  wow fadeInDown">
                          <li>
                            <figure class="bsbig_fig"> <a href="{{route('news-detail',['id' => $item1[3]])}}" class="featured_img"> <img alt="" src="{{asset('storage/images/'.$item1[2])}}"> <span class="overlay"></span> </a>
                              <figcaption> <a href="{{route('news-detail',['id' => $item1[3]])}}" style="font-family: 'Inter', sans-serif;">{{$item1[0]}}</a> </figcaption>
                              <p style="font-family: 'Inter', sans-serif;">{{$item1[1]}}</p>
                            </figure>
                          </li>
                        </ul>
                      </div>
                      @else
                      <div class="single_post_content_right">
                        <ul class="spost_nav">
                        <li>
                          <div class="media wow fadeInDown"> <a href="{{route('news-detail',['id' => $item1[3]])}}" class="media-left"> <img alt="" src="{{asset('storage/images/'.$item1[2])}}"> </a>
                            <div class="media-body"> <a href="{{route('news-detail',['id' => $item1[3]])}}" style="font-family: 'Inter', sans-serif;" class="catg_title">{{$item1[0]}}</a> </div>
                          </div>
                        </li>
                        </ul>
                      </div>
                      @endif
                    @endforeach
                  </div>
              @endforeach
          @endif
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <aside class="right_content">
          <div class="single_sidebar">
            <h2><span style="font-family: 'Inter', sans-serif;">Đọc nhiều</span></h2>
            <ul class="spost_nav">
              @if (!empty($mostView))
                  @foreach ($mostView as $key => $item)
                  <li>
                    <div class="media wow fadeInDown"> <a href="{{route('news-detail',['id' => $item->NewsID])}}" class="media-left"> <img alt="" src="{{asset('storage/images/'.$item->Image)}}"> </a>
                      <div class="media-body" > <a href="{{route('news-detail',['id' => $item->NewsID])}}" style="font-family: 'Inter', sans-serif;" class="catg_title">{{$item->Title}}</a> </div>
                    </div>
                  </li>
                  @endforeach
              @endif
            </ul>
          </div>
          {{-- <div class="single_sidebar wow fadeInDown">
            <h2 style="font-family: 'Inter', sans-serif;"><span>Quảng cáo</span></h2>
            <a class="sideAdd" href="#"><img src="images/add_img.jpg" alt=""></a> 
          </div> --}}
        </aside>
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
  var msg = '{{Session::get('alert')}}';
  var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
</script>
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