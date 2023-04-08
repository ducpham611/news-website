<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    //
    public function getDashboard()
    {
        $userCount = DB::select("SELECT Count(UserID) AS Quantity FROM user");
        $userCount = $userCount[0]->Quantity;
        $readerCount = DB::select("SELECT Count(ReaderID) AS Quantity FROM reader");
        $readerCount = $readerCount[0]->Quantity;
        if(session()->get('currentUser')['role'] == 'nhà báo' || session()->get('currentUser')['role'] == 'phóng viên')
        {
            $newsCount = DB::select("SELECT Count(NewsID) AS Quantity FROM news WHERE news.AuthorID = ?",[session()->get('currentUser')['id']]);
            $newsCount = $newsCount[0]->Quantity;
            $newsWaitCount = DB::select("SELECT Count(NewsID) AS Quantity FROM news WHERE Status = 'Chờ phê duyệt' AND news.AuthorID = ?",[session()->get('currentUser')['id']]);
            $newsWaitCount = $newsWaitCount[0]->Quantity;
            $newsAcceptedCount = DB::select("SELECT Count(NewsID) AS Quantity FROM news WHERE Status = 'Chấp thuận' AND news.AuthorID = ?",[session()->get('currentUser')['id']]);
            $newsAcceptedCount = $newsAcceptedCount[0]->Quantity;
            $newsNotAcceptedCount = DB::select("SELECT Count(NewsID) AS Quantity FROM news WHERE Status = 'Không chấp thuận' AND news.AuthorID = ?",[session()->get('currentUser')['id']]);
            $newsNotAcceptedCount = $newsNotAcceptedCount[0]->Quantity;
        }
        else
        {
            $newsCount = DB::select("SELECT Count(NewsID) AS Quantity FROM news");
            $newsCount = $newsCount[0]->Quantity;
            $newsWaitCount = DB::select("SELECT Count(NewsID) AS Quantity FROM news WHERE Status = 'Chờ phê duyệt'");
            $newsWaitCount = $newsWaitCount[0]->Quantity;
            $newsAcceptedCount = DB::select("SELECT Count(NewsID) AS Quantity FROM news WHERE Status = 'Chấp thuận'");
            $newsAcceptedCount = $newsAcceptedCount[0]->Quantity;
            $newsNotAcceptedCount = DB::select("SELECT Count(NewsID) AS Quantity FROM news WHERE Status = 'Không chấp thuận'");
            $newsNotAcceptedCount = $newsNotAcceptedCount[0]->Quantity;
        }
        $categoriesCount = DB::select("SELECT Count(CateID) AS Quantity FROM categories");
        $categoriesCount = $categoriesCount[0]->Quantity;
        $commentsCount = DB::select("SELECT Count(CommentID) AS Quantity FROM comments");
        $commentsCount = $commentsCount[0]->Quantity;
        $feedbacksCount = DB::select("SELECT Count(FeedBackID) AS Quantity FROM feedback");
        $feedbacksCount = $feedbacksCount[0]->Quantity;

        //Chart data
        $topCategories = DB::select("SELECT categories.CateName, COUNT(*) AS Quantity FROM news INNER JOIN categories ON news.CateID = categories.CateID 
        WHERE news.Status = 'Chấp thuận' GROUP BY categories.CateName LIMIT 3");
        $array[] = ['CateName','Số lượng'];
        foreach($topCategories as $key => $item)
        {
            $array[++$key] = [$item->CateName, $item->Quantity ];
        }

        $topViewNews = DB::select("SELECT news.Title, news.ViewCount FROM news WHERE news.Status = 'Chấp thuận' ORDER BY ViewCount DESC LIMIT 3");
        $array1[] = ['Tên bài viết','Số lần xem'];
        foreach($topViewNews as $key => $item)
        {
            $array1[++$key] = [$item->Title, $item->ViewCount ];
        }

        $topCommentsNews = DB::select("SELECT news.NewsID, news.Title, Count(CommentID) AS Quantity FROM comments INNER JOIN news ON comments.NewsID = news.NewsID GROUP BY news.NewsID, news.Title ORDER BY Quantity DESC LIMIT 3");
        $array2[] =['Tên bài viết','Số bình luận'];
        foreach($topCommentsNews as $key => $item)
        {
            $array2[++$key] = [$item->Title, $item->Quantity ];
        }
        $feedbackCounts = DB::select("SELECT DATE(CreatedDate) AS Date, Count(*) AS Quantity FROM feedback  WHERE DATE(CreatedDate) >= DATE(NOW() - INTERVAL 7 DAY)
        GROUP BY DATE(CreatedDate) ORDER BY DATE(CreatedDate) ASC ");
        $array3[] = ['Ngày','Số lượng phản hồi'];
        foreach($feedbackCounts as $key => $item)
        {
            $array3[++$key] = [date("d-m-Y",strtotime($item->Date)), $item->Quantity ];
        }

        // $newsUpload_OneWeek = DB::select("SELECT Date(ApprovedDate) AS Date, Count(*) AS Quantity FROM news WHERE DATE(ApprovedDate) >= DATE(NOW() - INTERVAL 7 DAY) AND news.Status = 'Chấp thuận'
        // GROUP BY DATE(ApprovedDate) ORDER BY Date(ApprovedDate) ASC");
        $period = CarbonPeriod::create(Carbon::now()->subDays(7), Carbon::now());
        $period->toArray();
        // foreach($period as $key=>$value)
        // {
        //     echo($value->toDateString()." ");
        // }
        $dbData = [];
        $dbDataReaderCount = [];
        foreach($period as $date){
            $range[$date->toDateString()] = 0;
            $range1[$date->toDateString()] = 0;
        }

        // dd($range);

        $newsUpload_OneWeek = DB::select("SELECT Date(ApprovedDate) AS Date, Count(*) AS Quantity FROM news WHERE news.Status = 'Chấp thuận' AND DATE(ApprovedDate) BETWEEN DATE(NOW() - INTERVAL 7 DAY) AND DATE(NOW())
        GROUP BY DATE(ApprovedDate) ORDER BY Date(ApprovedDate) ASC");

        foreach($newsUpload_OneWeek as $key => $val){
            $dbData[$val->Date] = $val->Quantity;
        }
        $newsUpload_OneWeek = array_replace($range,$dbData);

        // dd($newsUpload_OneWeek);

        // $array4[] = ['Ngày','Số lượng bài viết'];
        // foreach($newsUpload_OneWeek as $key => $item)
        // {
        //     $array4[++$key] = [date("d-m-Y",strtotime($item->Date)), $item->Quantity ];
        // }

        $array4[] = ['Ngày','Số lượng bài viết'];
        $array4count = 0;
        foreach($newsUpload_OneWeek as $key => $item)
        {
            $array4[++$array4count] = [date("d-m-Y",strtotime($key)), $item];
        }
        // dd($array4);

        $readerAccountCreate_OneWeek = DB::select("SELECT Date(CreatedDate) AS Date, Count(*) AS Quantity FROM reader WHERE Date(CreatedDate) >= DATE(NOW() - INTERVAL 7 DAY)
        GROUP BY Date ORDER BY Date ASC");
        foreach($readerAccountCreate_OneWeek as $key => $val){
            $dbDataReaderCount[$val->Date] = $val->Quantity;
        }
        $readerAccountCreate_OneWeek = array_replace($range1,$dbDataReaderCount);
        $array5[] = ['Ngày','Số lượng tài khoản'];
        $array5count = 0;
        foreach($readerAccountCreate_OneWeek as $key => $item)
        {
            $array5[++$array5count] = [date("d-m-Y",strtotime($key)), $item ];
        }

        return view('admin.dashboard',compact('userCount','readerCount','newsCount','newsWaitCount','newsAcceptedCount','newsNotAcceptedCount',
    'categoriesCount','commentsCount','feedbacksCount'))->with('top_categories',json_encode($array))
                                                        ->with('top_view_news',json_encode($array1))
                                                        ->with('top_comments_news',json_encode($array2))
                                                        ->with('feedback_count',json_encode($array3))
                                                        ->with('news_upload_oneweek',json_encode($array4))
                                                        ->with('reader_account_oneweek',json_encode($array5));
    }
}
