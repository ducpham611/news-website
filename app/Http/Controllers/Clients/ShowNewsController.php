<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShowNewsController extends Controller
{
    //
    public function getNewsByCategories($id)
    {
        $categoryID = $id;
        $categoryName = DB::select("SELECT CateName FROM categories WHERE CateID = ?",[$id]);
        if(!empty($categoryName))
        {
            $categoryName = $categoryName[0]->CateName;
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $current_time = date('D, d M Y H:i');
            $categories = DB::select("SELECT * FROM categories");
            $news = DB::table('news')
                        ->join('categories','news.CateID','=','categories.CateID')
                        ->join('user','news.AuthorID','=','user.UserID')
                        ->select('news.*','categories.CateName',DB::raw('concat(user.FirstName, " ", user.LastName) AS AuthorName'))
                        ->where('news.CateID','=',$id)
                        ->where('news.Status','=','Chấp thuận')
                        ->paginate(4);
            return view('clients.news.news-categories',compact('categoryName','current_time','categories','news'));
        }
        else
        {
            return redirect()->route('home');
        }
    }
    public function getNewsDetail($id = 0)
    {
        $newsIdCheck = DB::select("SELECT NewsID FROM news WHERE NewsID = ?",[$id]);
        if(!empty($newsIdCheck))
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $current_time = date('D, d M Y H:i');
            $categories = DB::select("SELECT * FROM categories");
            $newsDetail = DB::select("SELECT * FROM news INNER JOIN categories ON news.CateID = categories.CateID 
            INNER JOIN user ON news.AuthorID = user.UserID WHERE news.NewsID = ?",[$id]);
            $newsDetail = $newsDetail[0];
            $getViewCount = $newsDetail->ViewCount;
            DB::update("UPDATE news SET ViewCount = ? WHERE NewsID = ?",[$getViewCount + 1,$id]);
            $comments = DB::select("SELECT * , comments.CreatedDate AS DateCreate FROM comments INNER JOIN reader ON comments.ReaderID = reader.ReaderID WHERE comments.NewsID = ?",[$id]);
            $commentsCount = DB::select("SELECT COUNT(comments.CommentID) AS quantity  FROM comments INNER JOIN reader ON comments.ReaderID = reader.ReaderID WHERE comments.NewsID = ?",[$id]);
            $commentsCount = $commentsCount[0]->quantity;

            $newsCateID = DB::select("SELECT CateID FROM News WHERE NewsID = ?",[$id]);
            $newsCateID = $newsCateID[0]->CateID;
            $relatedNews = DB::select("SELECT * FROM news WHERE NewsID != ? AND CateID = ? AND Status = ? ORDER BY RAND() LIMIT 3",[$id,$newsCateID,'Chấp thuận']);

            $newestNews = DB::select("SELECT * FROM news WHERE CateID = ? AND Status = ? ORDER BY CreatedDate DESC",[$newsCateID,'Chấp thuận']);
            
            $mostView = DB::select("SELECT * FROM news WHERE CateID = ? AND Status = ? ORDER BY ViewCount DESC",[$newsCateID,'Chấp thuận']);
            return view('clients.news.news-detail',compact('newsDetail','categories','current_time','comments','commentsCount','relatedNews','newestNews','mostView'));
        }
        else
        {
            return redirect()->route('home');
        }
    }
}
