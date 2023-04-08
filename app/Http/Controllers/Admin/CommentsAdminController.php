<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class CommentsAdminController extends Controller
{
    //
    public function getCommentsManagement()
    {
        // $comments = DB::select("SELECT * ,comments.Content AS commentContent, comments.CreatedDate AS DateCreate, reader.FirstName AS readerFN, reader.LastName AS readerLN FROM comments INNER JOIN reader ON comments.ReaderID = reader.ReaderID
        // INNER JOIN news on comments.NewsID = news.NewsID");
        $comments = DB::table('comments')
                        ->join('reader','comments.ReaderID','=','reader.ReaderID')
                        ->join('news','comments.NewsID','=','news.NewsID')
                        ->select('comments.Content AS commentContent','comments.ReaderID','comments.CommentID','news.Title','comments.CreatedDate AS DateCreate',
                        'reader.FirstName AS readerFN','reader.LastName AS readerLN')
                        ->orderByDesc('comments.CreatedDate')
                        ->paginate(5);
        return view('admin.comments.comments-manage',compact('comments'));
    }
    public function getCommentsDelete($id = 0)
    {
        $emailSendTo = DB::select("SELECT reader.FirstName, reader.LastName, reader.Email, comments.CreatedDate, news.Title FROM comments INNER JOIN reader ON
        comments.ReaderID = reader.ReaderID INNER JOIN news ON comments.NewsID = news.NewsID WHERE CommentID = ?",[$id]);
        $emailDetail = [
            'receiver' => $emailSendTo[0]->FirstName. " ".$emailSendTo[0]->LastName,
            'title' => 'Thông báo về việc xóa bình luận',
            'body' => 'Bình luận của bạn tại bài viết: '.$emailSendTo[0]->Title.' tại thời điểm '.date("d-m-Y H:i:s",strtotime($emailSendTo[0]->CreatedDate)). ' đã bị xóa bởi quản trị viên do vi phạm nội quy website',
        ];
        Mail::to($emailSendTo[0]->Email)->send(new SendMail($emailDetail));
        DB::delete('DELETE FROM comments WHERE CommentID = ?',[$id]);
        return redirect()->route('admin.comments-management')->with('success-msg','Xóa bình luận thành công');
    }
    public function getCommentsSearch(Request $request)
    {
        $search = $request->query('search');
        if(!empty($search))
        {
            $comments = DB::table('comments')
                            ->join('reader','comments.ReaderID','=','reader.ReaderID')
                            ->join('news','comments.NewsID','=','news.NewsID')
                            ->select('comments.Content AS commentContent','comments.ReaderID','comments.CommentID','news.Title','comments.CreatedDate AS DateCreate',
                            'reader.FirstName AS readerFN','reader.LastName AS readerLN')
                            ->where('news.Title','like','%'.$search.'%')
                            ->orWhere(DB::raw('concat(reader.FirstName, " ", reader.LastName)'),'like','%'.$search.'%')
                            ->orWhere('comments.Content','like','%'.$search.'%')
                            ->orderByDesc('comments.CreatedDate')
                            ->paginate(5);
            $comments->appends('search', $search);
            if(!empty($comments))
            {
                return view('admin.comments.comments-manage',compact('comments'));
            }
            else
            {
                return view('admin.comments.comments-manage')->with('msg','Chưa có bình luận nào!');
            }
        }
        else
        {
            $comments = DB::table('comments')
                            ->join('reader','comments.ReaderID','=','reader.ReaderID')
                            ->join('news','comments.NewsID','=','news.NewsID')
                            ->select('comments.Content AS commentContent','comments.ReaderID','comments.CommentID','news.Title','comments.CreatedDate AS DateCreate',
                            'reader.FirstName AS readerFN','reader.LastName AS readerLN')
                            ->orderByDesc('comments.CreatedDate')
                            ->paginate(5);
            $comments->appends('search', $search);
            if(!empty($comments))
            {
                return view('admin.comments.comments-manage',compact('comments'));
            }
            else
            {
                return view('admin.comments.comments-manage')->with('msg','Chưa có bình luận nào!');
            }
        }
    }
}
