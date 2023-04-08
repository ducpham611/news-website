<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    //
    public function postComments(Request $request, $id = 0)
    {
        $request->validate([
            'content' => ['required']
        ],[
            'content.required' => 'Nội dung chưa được nhập'
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        if(empty(session()->get('currentReader')))
        {
            return redirect()->route('news-detail',['id' => $id])->with('alert','Vui lòng đăng nhập để gửi bình luận!');
        }
        else
        {
            $dataInsert = [
                session()->get('currentReader')['id'],
                $request->content,
                date('Y-m-d H:i:s'),
                $id
            ];
            DB::insert("INSERT INTO comments (ReaderID, Content, CreatedDate, NewsID) VALUES (?,?,?,?)",$dataInsert);
            return redirect()->route('news-detail',['id' => $id])->with('alert','Gửi bình luận thành công!');
        }
    }
}
