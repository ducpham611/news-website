<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FeedbackAdminController extends Controller
{
    //
    public function getFeedbacksManagement()
    {
        $feedbacks = DB::table('feedback') 
                        ->join('reader','feedback.ReaderID','=','reader.ReaderID')
                        ->select('feedback.*','reader.FirstName AS readerFN','reader.LastName AS readerLN')
                        ->orderBy('feedback.CreatedDate','DESC')
                        ->paginate(8);
        return view('admin.feedbacks.feedbacks-manage',compact('feedbacks'));
    }
    public function getFeedbacksDelete($id = 0)
    {
        DB::delete("DELETE FROM feedback WHERE FeedBackID = ?",[$id]);
        return redirect()->route('admin.feedbacks-management')->with('success-msg','Xóa phản hồi thành công!');
    }
    public function getFeedbacksSearch(Request $request)
    {
        $search = $request->query('search');
        if(!empty($search))
        {
            $feedbacks = DB::table('feedback')
                            ->join('reader','feedback.ReaderID','=','reader.ReaderID')
                            ->where(DB::raw('concat(reader.FirstName, " ", reader.LastName)'),'like','%'.$search.'%')
                            ->orWhere('feedback.content','like','%'.$search.'%')
                            ->select('feedback.*','reader.FirstName AS readerFN','reader.LastName AS readerLN')
                            ->orderBy('feedback.CreatedDate','DESC')
                            ->paginate(5);
            $feedbacks->appends('search', $search);
            if(!empty($feedbacks))
            {
                return view('admin.feedbacks.feedbacks-manage',compact('feedbacks'));
            }
            else
            {
                return view('admin.feedbacks.feedbacks-manage')->with('msg','Chưa có bình luận nào!');
            }
        }
        else
        {
            $feedbacks = DB::table('feedback') 
                        ->join('reader','feedback.ReaderID','=','reader.ReaderID')
                        ->select('feedback.*','reader.FirstName AS readerFN','reader.LastName AS readerLN')
                        ->paginate(8);
            $feedbacks->appends('search', $search);
            if(!empty($feedbacks))
            {
                return view('admin.feedbacks.feedbacks-manage',compact('feedbacks'));
            }
            else
            {
                return view('admin.feedbacks.feedbacks-manage')->with('msg','Chưa có bình luận nào!');
            }
        }
    }
}
