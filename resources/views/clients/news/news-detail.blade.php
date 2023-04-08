<!DOCTYPE html>
<html>
<head>
<title>{{$newsDetail->Title}}</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@600&family=Noto+Serif:ital@0;1&display=swap" rel="stylesheet">

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
          <div class="single_page">
            <ol class="breadcrumb">
              <li><a href="/">Trang chủ</a></li>
              <li><a href="{{route('news-by-categories',['id' => $newsDetail->CateID])}}">{{$newsDetail->CateName}}</a></li>
            </ol>
            <p style="font-size: 33px; font-weight: 600; font-family: 'Inter', sans-serif;">{{$newsDetail->Title}}</p>
            <div class="post_commentbox" style="font-size: 14px; font-family: 'Inter', sans-serif;"> 
              <a href="#"><i class="fa fa-user"></i>{{$newsDetail->FirstName." ".$newsDetail->LastName}}</a> 
              <span><i class="fa fa-calendar"></i>{{date("d-m-Y H:i:s",strtotime($newsDetail->CreatedDate))}}</span> 
              <a href="{{route('news-by-categories',['id' => $newsDetail->CateID])}}"><i class="fa fa-tags"></i>{{$newsDetail->CateName}}</a> 
            </div>
            <div class="single_page_content"> 
              <img style="width: 680px; height: 383px;"class="img-center" src="{{asset('storage/images/'.$newsDetail->Image)}}" alt="">
              <figcaption style="margin: 8px 0 0; padding: 0 30px; font-size: 14px; line-height: 22px; font-style: italic; text-align: center; color: #666;">
                <p style="font-family: 'Noto Serif', serif;">{{$newsDetail->ImageDescription}}</p>
              </figcaption>
              <p style="font-style: italic; font-family: 'Noto Serif', serif; ;font-size: 18px; line-height: 30px; margin-top: 30px;">
                {{$newsDetail->Excerpt}}
              </p>
              <div class="news-content" style="font-family: 'Noto Serif', serif; ;font-size: 18px; line-height: 30px; margin-top: 30px;">
                <p>{!!$newsDetail->Content!!}</p>
              </div>
              <hr>
              <div class="comment-wrapper">
                <div class="panel panel-info" style="border-color: #70ad47;">
                    <div class="panel-heading" style="font-size: 20px; font-weight: 600; font-family: 'Inter', sans-serif; color:white;background-color:#70ad47;border-color:#70ad47">  
                      Bình luận ({{$commentsCount}})
                      @if (empty(session()->get('currentReader')))
                        <p>Vui lòng <a href="/login">Đăng nhập</a> để gửi bình luận</p>   
                      @endif
                    </div>
                    <div class="panel-body">
                      <form method="POST">
                        <textarea class="form-control" name="content" placeholder="Bạn nghĩ gì về tin này?" rows="5"></textarea>
                        @error('content')
                          <span style="color:red;">{{$message}}</span>  
                        @enderror
                        <br>
                        @csrf
                        <button type="submit" class="btn btn-info pull-right" style="color:white;background-color:#70ad47;border-color:#70ad47;">Gửi bình luận</button>
                      </form>  
                        <div class="clearfix"></div>
                        <hr>
                        <ul class="media-list">
                          @if (!empty($comments))
                            @foreach ($comments as $key => $item)
                            <li class="media">
                              <a href="#" class="pull-left">
                                  <img src="https://bootdey.com/img/Content/user_1.jpg" alt="" class="img-circle">
                              </a>
                              <strong class="text-success">{{$item->FirstName." ".$item->LastName}}</strong>

                              <span class="text-muted ">
                                <p class="text-muted" style="font-size: 13px;">{{date("d-m-Y H:i:s",strtotime($item->DateCreate))}}</p>
                              </span>

                              <div class="media-body">
                                  <p>
                                      {{$item->Content}}
                                  </p>
                              </div>
                          </li> 
                            @endforeach
                          @endif
                        </ul>
                    </div>
                </div>
            </div>
            </div>
            <div class="social_link">
              <ul class="sociallink_nav">
                <li><a href="https://vi-vn.facebook.com/"><i class="fa fa-facebook"></i></a></li>
                <li><a href="https://twitter.com/"><i class="fa fa-twitter"></i></a></li>
                <li><a href="https://www.linkedin.com/"><i class="fa fa-linkedin"></i></a></li>
                <li><a href="https://www.pinterest.com/"><i class="fa fa-pinterest"></i></a></li>
              </ul>
            </div>
            <div class="related_post">
              <h2 style="font-family: 'Inter', sans-serif;">Tin tức cùng chuyên mục</h2>
              <ul class="spost_nav wow fadeInDown animated">
                @if (!empty($relatedNews))
                    @foreach ($relatedNews as $key => $item)
                    <li>
                      <div class="media"> <a class="media-left" href="single_page.html"> <img src="{{asset('storage/images/'.$item->Image)}}" alt=""> </a>
                        <div class="media-body"> 
                          <a class="catg_title" style="font-family: 'Inter', sans-serif;"href="{{route('news-detail',['id' => $item->NewsID])}}">{{$item->Title}}</a> 
                        </div>
                      </div>
                    </li>
                    @endforeach
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
        
      <div class="col-lg-4 col-md-4 col-sm-4">
        <aside class="right_content">
          <div class="single_sidebar">
            <h2 style="font-family: 'Inter', sans-serif;"><span>Mới nhất</span></h2>
            <ul class="spost_nav">
              @if (!empty($newestNews))
                  @foreach ($newestNews as $key => $item)
                  <li>
                    <div class="media wow fadeInDown"> 
                      <a href="{{route('news-detail',['id' => $item->NewsID])}}" class="media-left"> 
                        <img alt="" src="{{asset('storage/images/'.$item->Image)}}"> 
                      </a>
                      <div class="media-body"> 
                        <a href="{{route('news-detail',['id' => $item->NewsID])}}" class="catg_title" style="font-family: 'Inter', sans-serif;">{{$item->Title}}</a> 
                      </div>
                    </div>
                  </li>
                  @endforeach
              @endif
            </ul>
          </div>
        </aside>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
        <aside class="right_content">
          <div class="single_sidebar">
            <h2 style="font-family: 'Inter', sans-serif;"><span>Đọc nhiều</span></h2>
            <ul class="spost_nav">
              @if (!empty($mostView))
                  @foreach ($mostView as $key => $item)
                  <li>
                    <div class="media wow fadeInDown"> 
                      <a href="{{route('news-detail',['id' => $item->NewsID])}}" class="media-left"> 
                        <img alt="" src="{{asset('storage/images/'.$item->Image)}}"> 
                      </a>
                      <div class="media-body"> 
                        <a href="{{route('news-detail',['id' => $item->NewsID])}}" class="catg_title" style="font-family: 'Inter', sans-serif;">{{$item->Title}}</a> 
                      </div>
                    </div>
                  </li>
                  @endforeach
              @endif
            </ul>
          </div>
        </aside>
      </div>
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