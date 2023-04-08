<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContactAndSearchController extends Controller
{
    //
    public function getContact()
    {
        if(empty(session()->get('currentReader')))
        {
            return redirect()->route('home')->with('alert','Bạn cần đăng nhập để gửi ý kiến');
        }
        else
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $current_time = date('D, d M Y H:i');
            $categories = DB::select("SELECT * FROM categories");
            return view('clients.contact',compact('current_time','categories'));
        }
    }
    public function postContact(Request $request)
    {
        $request->validate([
            'content' => ['required']
        ],[
            'content.required' => 'Vui lòng nhập ý kiến phản hồi!',
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dataInsert = [
            session()->get('currentReader')['id'],
            $request->content,
            date('Y-m-d H:i:s'),
        ];
        DB::insert("INSERT INTO feedback (ReaderID, Content, CreatedDate) VALUES (?,?,?)",$dataInsert);
        return redirect()->route('home')->with('alert','Gửi phản hồi thành công!');
    }
    public function getSearch()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_time = date('D, d M Y H:i');
        $categories = DB::select("SELECT * FROM categories");
        return view('clients.search',compact('current_time','categories'));
    }
    public function getSearchResults(Request $request)
    {
        $search = $request->query('search-value');
        $current_time = date('D, d M Y H:i');
        $categories = DB::select("SELECT * FROM categories");
        if(!empty($search))
        {
            $results = DB::table('news')
                    ->join('categories','news.CateID','=','categories.CateID')
                    ->where('news.Title','like','%'.$search.'%')
                    ->orWhere('news.Excerpt','like','%'.$search.'%')
                    ->select('news.*','categories.CateName')
                    ->paginate(5);
            $results->appends('search-value', $search);
            if(!empty($results))
            {
                return view('clients.search',compact('results','current_time','categories'));
            }
        }
        else
        {
            return view('clients.search',compact('current_time','categories'));

        }
    }
}
