<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class NewsController extends Controller
{
    //
    public function getNewsCreate()
    {
        $categories = DB::select("SELECT * FROM categories");
        return view('admin.news.news-create',compact('categories'));
    }
    public function postNewsCreate(Request $request)
    {
        $request->validate([
            'title' => ['required','unique:news,Title'],
            'excerpt' => ['required','unique:news,Excerpt'],
            'category' => ['required'],
            'content' => ['required','unique:news,Content'],
            'image' => ['required'],
            'imageDescription' => ['required']
        ],[
            'title.required' => 'Tiêu đề bài viết chưa được nhập',
            'title.unique' => 'Tiêu đề bài viết đã tồn tại',
            'excerpt.required' => 'Trích đoạn nội dung chưa được nhập',
            'excerpt.unique' => 'Trích đoạn nội dung đã tồn tại',
            'category.required' => 'Chuyên mục của bài viết chưa được nhập',
            'content.required' => 'Nội dung bài viết chưa được nhập',
            'content.unique' => 'Nội dung bài viết bị trùng lặp',
            'image.required' => 'Hình ảnh của bài viết chưa có',
            'imageDescription.required' => "Chú thích hình ảnh chưa có"
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $imageFile = $request->image;
        if($imageFile->isValid()){
            $imageName = $imageFile->getClientOriginalName();
            if(Storage::disk('public')->exists($imageName))
            {
                return redirect()->back()->with('img-msg','Tên file ảnh đã tồn tại, vui lòng đổi tên!');
            }
            else
            {
                $path = $imageFile->storeAs('public/images',$imageName);//Duong dan luu anh
                $dataInsert = [
                    $request->title,
                    $request->excerpt,
                    $request->category,
                    $request->content,
                    session()->get('currentUser')['id'],
                    $imageName,
                    $request->imageDescription,
                    date('Y-m-d H:i:s'),
                    0,
                    'Chờ phê duyệt',
                    NULL,
                    '',
                    NULL
                ];  
                DB::insert("INSERT INTO news (Title, Excerpt, CateID, Content, AuthorID, Image, ImageDescription, CreatedDate, ViewCount, Status, ApproverID, Reason, ApprovedDate) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",$dataInsert);
                $dataLog = [
                    session()->get('currentUser')['id'],
                    'Tạo bài viết: '.$request->title,
                    date('Y-m-d H:i:s'),
                ];
                DB::insert("INSERT INTO log (UserID, Activity, Date) VALUES (?,?,?)",$dataLog);
                return redirect()->route('admin.news-management')->with('success-msg','Tạo bài viết mới thành công');
            }
        }
        else
        {
            return redirect()->back()->with('err-msg','File không hợp lệ');
        }
    }
    public function getNewsManagement()
    {
        // $news = DB::select("SELECT * FROM news INNER JOIN categories on news.CateID = categories.CateID INNER JOIN news_status
        // ON news.NewsID = news_status.NewsID");
        if((session()->get('currentUser')['role'] == 'nhà báo') || session()->get('currentUser')['role'] == 'phóng viên')
        {
            $news = DB::table('news')
                        ->join('categories','news.CateID','=','categories.CateID')
                        ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                        ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                        ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                        'u2.LastName as ApproverLastName')
                        ->where('news.AuthorID','=',session()->get('currentUser')['id'])
                        ->orderByDesc('news.CreatedDate')
                        ->paginate(8);
            if(!empty($news))
            {
                return view('admin.news.news-manage',compact('news'));
            }
            else
            {
                return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
            }
        }
        else if (session()->get('currentUser')['role'] == 'biên tập viên')
        {
            $cate_assigned = DB::select("SELECT CateID FROM cate_assign WHERE UserID = ?",[session()->get('currentUser')['id']]);
            $cate_check = [];
            foreach($cate_assigned as $key => $value)
            {
                $cate_check[] = $value->CateID;
            }
            $news = DB::table('news')
                    ->join('categories','news.CateID','=','categories.CateID')
                    ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                    ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                    ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                    'u2.LastName as ApproverLastName')
                    ->whereIn('news.CateID', $cate_check)
                    ->orderByDesc('news.CreatedDate')
                    ->paginate(8);
            if(!empty($news))
            {
                return view('admin.news.news-manage',compact('news'));
            }
            else
            {
                return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
            }
        }
        else
        {
            $news = DB::table('news')
                    ->join('categories','news.CateID','=','categories.CateID')
                    ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                    ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                    ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                    'u2.LastName as ApproverLastName')
                    ->orderByDesc('news.CreatedDate')
                    ->paginate(8);
            if(!empty($news))
            {
                return view('admin.news.news-manage',compact('news'));
            }
            else
            {
                return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
            }
        }
    }
    public function getNewsDetail($id=0)
    {
        if(!empty($id))
        {
            $newsDetail = DB::select("SELECT * FROM news INNER JOIN categories ON news.CateID = categories.CateID WHERE news.NewsID = ?",[$id]);
            $newsDetail = $newsDetail[0];
            return view('admin.news.news-detail',compact('newsDetail'));
        }
        else
        {
            return redirect()->route('admin.news-management')->with('msg','Bài viết không tồn tại');
        }
    }
    public function postNewsDetail(Request $request, $id=0) //Chuc nang Phe duyet bai viet
    {
        $request->validate([
            'status' => ['required'],
        ],[
            'status.required' => 'Trạng thái chưa được nhập',
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dataInsert = [
            $request->status,
            session()->get('currentUser')['id'],
            $request->reason,
            date('Y-m-d H:i:s'),
            $id
        ];
        DB::update("UPDATE news SET Status = ?, ApproverID = ?, Reason = ?, ApprovedDate = ? WHERE NewsID = ?",$dataInsert);

        $logNewsTitle = DB::select("SELECT Title FROM news WHERE NewsID = ?",[$id]);
        $dataLog = [
            session()->get('currentUser')['id'],
            'Phê duyệt bài viết: '.$logNewsTitle[0]->Title." ".'với trạng thái'." ".$request->status,
            date('Y-m-d H:i:s'),
        ];
        DB::insert("INSERT INTO log (UserID, Activity, Date) VALUES (?,?,?)",$dataLog);

        $emailSendTo = DB::select("SELECT user.Email, user.FirstName, user.LastName, news.Title, news.Status, news.Reason, news.CreatedDate, news.ApprovedDate FROM news INNER JOIN user ON news.AuthorID = user.UserID WHERE NewsID = ?",[$id]);
        $approverEmail = DB::select("SELECT Email FROM user WHERE UserID = ?",[session()->get('currentUser')['id']]);
        $approverEmail = $approverEmail[0]->Email;
        $emailDetail = [
            'receiver' => $emailSendTo[0]->FirstName. " ".$emailSendTo[0]->LastName,
            'title' => 'Thông báo về việc phê duyệt bài viết',
            'body' => 'Bài viết: '.$emailSendTo[0]->Title.'  được viết tại thời điểm '.date("d-m-Y H:i:s",strtotime($emailSendTo[0]->CreatedDate)),
            'body1' => 'Đã được phê duyệt bởi '.session()->get('currentUser')['role']. " ".session()->get('currentUser')['name'],
            'body2' => 'Với trạng thái '.$emailSendTo[0]->Status.' vào thời gian '.date("d-m-Y H:i:s",strtotime($emailSendTo[0]->ApprovedDate)),
            'reason' => 'Lý do: '.$emailSendTo[0]->Reason,
            'contact' => $approverEmail,
        ];
        Mail::to($emailSendTo[0]->Email)->send(new SendMail($emailDetail));

        return redirect()->route('admin.news-management')->with('success-msg','Phê duyệt thành công');
    }
    public function getNewsEdit($id = 0)
    {
        if(!empty($id))
        {
            $newsDetail = DB::select("SELECT * FROM news INNER JOIN categories ON news.CateID = categories.CateID WHERE news.NewsID = ?",[$id]);
            $newsDetail = $newsDetail[0];
            $categories = DB::select("SELECT * FROM categories");
            return view('admin.news.news-edit',compact('newsDetail','categories'));
        }
        else
        {
            return redirect()->route('admin.news-management')->with('msg','Bài viết không tồn tại');
        }
    }
    public function postNewsEdit(Request $request,$id = 0)
    {
        $request->validate([
            'title' => ['required','unique:news,Title,'.$id.',NewsID'],
            'excerpt' => ['required','unique:news,Excerpt,'.$id.',NewsID'],
            'category' => ['required'],
            'content' => ['required','unique:news,Content,'.$id.',NewsID'],
            'imageDescription' => ['required']
        ],[
            'title.required' => 'Tiêu đề bài viết chưa được nhập',
            'title.unique' => 'Tiêu đề bài viết đã tồn tại',
            'excerpt.required' => 'Trích đoạn nội dung chưa được nhập',
            'excerpt.unique' => 'Trích đoạn nội dung đã tồn tại',
            'category.required' => 'Chuyên mục của bài viết chưa được nhập',
            'content.required' => 'Nội dung bài viết chưa được nhập',
            'content.unique' => 'Nội dung bài viết bị trùng lặp',
            'imageDescription.required' => 'Chú thích hình ảnh chưa được nhập'
        ]);
        if(!empty($request->image))
        {
            $imageFile = $request->image;
            $imageName = $imageFile->getClientOriginalName();
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            if(Storage::disk('public')->exists($imageName))
            {
                return redirect()->back()->with('img-msg','Tên file ảnh đã tồn tại, vui lòng đổi tên!');
            }
            else
            {
                $path = $imageFile->storeAs('public/images',$imageName);//Duong dan luu anh
                $dataInsert = [
                    $request->title,
                    $request->excerpt,
                    $request->category,
                    $request->content,
                    $imageName,
                    $request->imageDescription,
                    'Chờ phê duyệt',
                    // NULL,
                    date('Y-m-d H:i:s'),
                    $id
                ];  
                $oldFileName = DB::select("SELECT Image FROM news WHERE NewsID = ?",[$id]);
                $oldTitle = DB::select("SELECT Title FROM news WHERE NewsID = ?",[$id]);
                Storage::disk('public')->delete($oldFileName[0]->Image);
                DB::update("UPDATE news SET Title = ?, Excerpt = ?, CateID = ?, Content = ?, Image = ?, ImageDescription = ?, Status = ?, UpdateDate = ? WHERE NewsID = ?",$dataInsert);
                $dataLog = [
                    session()->get('currentUser')['id'],
                    'Chỉnh sửa bài viết: '.$oldTitle[0]->Title,
                    date('Y-m-d H:i:s'),
                ];
                DB::insert("INSERT INTO log (UserID, Activity, Date) VALUES (?,?,?)",$dataLog);
                return redirect()->route('admin.news-management')->with('success-msg','Chỉnh sửa bài viết thành công');
            }
        }
        else
        {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $dataInsert = [
                $request->title,
                $request->excerpt,
                $request->category,
                $request->content,
                $request->imageDescription,
                'Chờ phê duyệt',
                // NULL,
                date('Y-m-d H:i:s'),
                $id
            ];
            $oldTitle = DB::select("SELECT Title FROM news WHERE NewsID = ?",[$id]);
            DB::update("UPDATE news SET Title = ?, Excerpt = ?, CateID = ?, Content = ?, ImageDescription = ?, Status = ?, UpdateDate = ? WHERE NewsID = ?",$dataInsert);
            $dataLog = [
                session()->get('currentUser')['id'],
                'Chỉnh sửa bài viết: '.$oldTitle[0]->Title,
                date('Y-m-d H:i:s'),
            ];
            DB::insert("INSERT INTO log (UserID, Activity, Date) VALUES (?,?,?)",$dataLog);
            return redirect()->route('admin.news-management')->with('success-msg','Chỉnh sửa bài viết thành công');
        }
    }
    public function getNewsDelete($id = 0)
    {   
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $fileName = DB::select("SELECT Image FROM news WHERE NewsID = ?",[$id]);
        $oldTitle = DB::select("SELECT Title FROM news WHERE NewsID = ?",[$id]);
        //Gui mail thong bao xoa neu bien tap vien xoa bai viet cua 1 nha bao
        if(session()->get('currentUser')['role'] = 'Biên tập viên' || session()->get('currentUser')['role'] = 'Tổng biên tập')
        {
            $emailSendTo = DB::select("SELECT user.Email, user.FirstName, user.LastName, news.Title, news.Status, news.Reason, news.CreatedDate, news.ApprovedDate FROM news INNER JOIN user ON news.AuthorID = user.UserID WHERE NewsID = ?",[$id]);
            $approverEmail = DB::select("SELECT Email FROM user WHERE UserID = ?",[session()->get('currentUser')['id']]);
            $approverEmail = $approverEmail[0]->Email;
            $emailDetail = [
                'receiver' => $emailSendTo[0]->FirstName. " ".$emailSendTo[0]->LastName,
                'title' => 'Thông báo về việc xóa bài viết',
                'body' => 'Bài viết: '.$emailSendTo[0]->Title.'  được viết tại thời điểm '.date("d-m-Y H:i:s",strtotime($emailSendTo[0]->CreatedDate)),
                'body1' => 'Đã bị xóa bởi '.session()->get('currentUser')['role']. " ".session()->get('currentUser')['name'],
                'contact' => $approverEmail,
            ];
            Mail::to($emailSendTo[0]->Email)->send(new SendMail($emailDetail));
        }
        DB::delete("DELETE FROM news WHERE NewsID = ?",[$id]);
        Storage::disk('public')->delete($fileName[0]->Image);
        $dataLog = [
            session()->get('currentUser')['id'],
            'Xóa bài viết: '.$oldTitle[0]->Title,
            date('Y-m-d H:i:s'),
        ];
        DB::insert("INSERT INTO log (UserID, Activity, Date) VALUES (?,?,?)",$dataLog);
        return redirect()->route('admin.news-management')->with('success-msg','Xóa bài viết thành công');
    }
    public function getNewsSearch(Request $request)
    {
        if(session()->get('currentUser')['role'] == 'nhà báo')
        {   
            $search = $request->query('search');
            $status = $request->query('status');
            if(!empty($search) && $status == 'all')
            {
                $news = DB::table('news')
                        ->join('categories','news.CateID','=','categories.CateID')
                        ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                        ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                        ->where('news.AuthorID','=',session()->get('currentUser')['id'])
                        ->where(function($query) use ($search){
                            $query->orWhere('news.Title','like','%'.$search.'%')
                                    ->orWhere('CateName','like','%'.$search.'%')
                                    ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
                                    ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%');
                        })
                        // ->orWhere('CateName','like','%'.$search.'%')
                        // ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
                        // ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%')
                        ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                        'u2.LastName as ApproverLastName')
                        ->orderByDesc('news.CreatedDate')
                        ->paginate(8);
                $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                    {
                        return view('admin.news.news-manage',compact('news'));
                    }
                    else
                    {
                        return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                    }
            }
            else if(empty($search) && $status != 'all')
            {
                if($status == 'Chờ phê duyệt')
                {
                    $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->where('news.Status','=', $status)
                            ->where('news.AuthorID','=',session()->get('currentUser')['id'])
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                    // $news->appends('search', $search);
                    $news->appends('status', $status);
                }
                else
                {
                    $news = DB::table('news')
                                ->join('categories','news.CateID','=','categories.CateID')
                                ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                                ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                                ->where('news.Status','=', $status)
                                ->where('news.AuthorID','=',session()->get('currentUser')['id'])
                                ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                                'u2.LastName as ApproverLastName')
                                ->orderByDesc('news.ApprovedDate')
                                ->paginate(8);
                    // $news->appends('search', $search);
                    $news->appends('status', $status);
                }
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }
            }
            else if(empty($search) && $status == 'all')
            {
                $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->where('news.AuthorID','=',session()->get('currentUser')['id'])
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                // $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }
            }
            else
            {
                $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->where('news.Status', '=', $status)
                            ->where('news.AuthorID','=',session()->get('currentUser')['id'])
                            ->where(function($query) use ($search)
                            {
                                $query->orWhere('news.Title','like','%'.$search.'%')
                                      ->orWhere('CateName','like','%'.$search.'%')
                                      ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
                                      ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%');
                            })
                            // ->where('news.Title','like','%'.$search.'%')
                            // ->orWhere('CateName','like','%'.$search.'%')
                            // ->orWhere('news.Author','like','%'.$search.'%')
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }           
            }           
        }  
        else if(session()->get('currentUser')['role'] == 'biên tập viên')
        {
            $search = $request->query('search');
            $status = $request->query('status');
            $cate_assigned = DB::select("SELECT CateID FROM cate_assign WHERE UserID = ?",[session()->get('currentUser')['id']]);
            $cate_check = [];
            foreach($cate_assigned as $key => $value)
            {
                $cate_check[] = $value->CateID;
            }
            if(!empty($search) && $status == 'all')
            {
                $news = DB::table('news')
                        ->join('categories','news.CateID','=','categories.CateID')
                        ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                        ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                        ->whereIn('news.CateID', $cate_check)
                        ->where(function ($query) use ($search){
                            $query->orWhere('news.Title','like','%'.$search.'%')
                            ->orWhere('CateName','like','%'.$search.'%')
                            ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
                            ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%');
                        })
                        // ->where('news.Title','like','%'.$search.'%')
                        // ->orWhere('CateName','like','%'.$search.'%')
                        // ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
                        // ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%')
                        ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                        'u2.LastName as ApproverLastName')
                        ->orderByDesc('news.CreatedDate')
                        ->paginate(8);
                $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                    {
                        return view('admin.news.news-manage',compact('news'));
                    }
                    else
                    {
                        return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                    }
            }
            else if(empty($search) && $status != 'all')
            {
                if($status == 'Chờ phê duyệt')
                {
                    $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->where('news.Status','=', $status)
                            ->whereIn('news.CateID', $cate_check)
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                    // $news->appends('search', $search);
                    $news->appends('status', $status);
                }
                else
                {
                    $news = DB::table('news')
                                ->join('categories','news.CateID','=','categories.CateID')
                                ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                                ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                                ->where('news.Status','=', $status)
                                ->whereIn('news.CateID', $cate_check)
                                ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                                'u2.LastName as ApproverLastName')
                                ->orderByDesc('news.ApprovedDate')
                                ->paginate(8);
                    // $news->appends('search', $search);
                    $news->appends('status', $status);
                }
                
    
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }
            }
            else if(empty($search) && $status == 'all')
            {
                $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->whereIn('news.CateID', $cate_check)
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                // $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }
            }
            else
            {
                $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->where('news.Status', '=', $status)
                            ->whereIn('news.CateID', $cate_check)
                            ->where(function($query) use ($search)
                            {
                                $query->orWhere('news.Title','like','%'.$search.'%')
                                      ->orWhere('CateName','like','%'.$search.'%')
                                      ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
                                      ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%');
                            })
                            // ->where('news.Title','like','%'.$search.'%')
                            // ->orWhere('CateName','like','%'.$search.'%')
                            // ->orWhere('news.Author','like','%'.$search.'%')
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }           
            }            
        }

        else //TH khong phai nha bao va bien tap vien
        {
            $search = $request->query('search');
            $status = $request->query('status');
            if(!empty($search) && $status == 'all')
            {
                $news = DB::table('news')
                        ->join('categories','news.CateID','=','categories.CateID')
                        ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                        ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                        ->where('news.Title','like','%'.$search.'%')
                        ->orWhere('CateName','like','%'.$search.'%')
                        ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
                        ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%')
                        ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                        'u2.LastName as ApproverLastName')
                        ->orderByDesc('news.CreatedDate')
                        ->paginate(8);
                $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                    {
                        return view('admin.news.news-manage',compact('news'));
                    }
                    else
                    {
                        return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                    }
            }
            else if(empty($search) && $status != 'all')
            {
                if($status == 'Chờ phê duyệt')
                {
                    $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->where('news.Status','=', $status)
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                    // $news->appends('search', $search);
                    $news->appends('status', $status);
                }
                else
                {
                    $news = DB::table('news')
                                ->join('categories','news.CateID','=','categories.CateID')
                                ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                                ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                                ->where('news.Status','=', $status)
                                ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                                'u2.LastName as ApproverLastName')
                                ->orderByDesc('news.ApprovedDate')
                                ->paginate(8);
                    // $news->appends('search', $search);
                    $news->appends('status', $status);
                }
                
    
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }
            }
            else if(empty($search) && $status == 'all')
            {
                $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                // $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }
            }
            else
            {
                $news = DB::table('news')
                            ->join('categories','news.CateID','=','categories.CateID')
                            ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
                            ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
                            ->where('news.Status', '=', $status)
                            ->where(function($query) use ($search)
                            {
                                $query->orWhere('news.Title','like','%'.$search.'%')
                                      ->orWhere('CateName','like','%'.$search.'%')
                                      ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
                                      ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%');
                            })
                            // ->where('news.Title','like','%'.$search.'%')
                            // ->orWhere('CateName','like','%'.$search.'%')
                            // ->orWhere('news.Author','like','%'.$search.'%')
                            ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
                            'u2.LastName as ApproverLastName')
                            ->orderByDesc('news.CreatedDate')
                            ->paginate(8);
                $news->appends('search', $search);
                $news->appends('status', $status);
                if(!empty($news))
                {
                    return view('admin.news.news-manage',compact('news'));
                }
                else
                {
                    return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
                }           
            }            
        }
        // $search = $request->query('search');
        // $status = $request->query('status');
        // if(!empty($search) && $status == 'all')
        // {
        //     $news = DB::table('news')
        //             ->join('categories','news.CateID','=','categories.CateID')
        //             ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
        //             ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
        //             ->where('news.Title','like','%'.$search.'%')
        //             ->orWhere('CateName','like','%'.$search.'%')
        //             ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
        //             ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%')
        //             ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
        //             'u2.LastName as ApproverLastName')
        //             ->orderByDesc('news.CreatedDate')
        //             ->paginate(8);
        //     $news->appends('search', $search);
        //     $news->appends('status', $status);
        //     if(!empty($news))
        //         {
        //             return view('admin.news.news-manage',compact('news'));
        //         }
        //         else
        //         {
        //             return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
        //         }
        // }
        // else if(empty($search) && $status != 'all')
        // {
        //     if($status == 'Chờ phê duyệt')
        //     {
        //         $news = DB::table('news')
        //                 ->join('categories','news.CateID','=','categories.CateID')
        //                 ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
        //                 ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
        //                 ->where('news.Status','=', $status)
        //                 ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
        //                 'u2.LastName as ApproverLastName')
        //                 ->orderByDesc('news.CreatedDate')
        //                 ->paginate(8);
        //         // $news->appends('search', $search);
        //         $news->appends('status', $status);
        //     }
        //     else
        //     {
        //         $news = DB::table('news')
        //                     ->join('categories','news.CateID','=','categories.CateID')
        //                     ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
        //                     ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
        //                     ->where('news.Status','=', $status)
        //                     ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
        //                     'u2.LastName as ApproverLastName')
        //                     ->orderByDesc('news.ApprovedDate')
        //                     ->paginate(8);
        //         // $news->appends('search', $search);
        //         $news->appends('status', $status);
        //     }
            

        //     if(!empty($news))
        //     {
        //         return view('admin.news.news-manage',compact('news'));
        //     }
        //     else
        //     {
        //         return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
        //     }
        // }
        // else if(empty($search) && $status == 'all')
        // {
        //     $news = DB::table('news')
        //                 ->join('categories','news.CateID','=','categories.CateID')
        //                 ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
        //                 ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
        //                 ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
        //                 'u2.LastName as ApproverLastName')
        //                 ->orderByDesc('news.CreatedDate')
        //                 ->paginate(8);
        //     // $news->appends('search', $search);
        //     $news->appends('status', $status);
        //     if(!empty($news))
        //     {
        //         return view('admin.news.news-manage',compact('news'));
        //     }
        //     else
        //     {
        //         return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
        //     }
        // }
        // else
        // {
        //     $news = DB::table('news')
        //                 ->join('categories','news.CateID','=','categories.CateID')
        //                 ->join('user as u1','news.AuthorID','=',DB::raw('u1.UserID'))
        //                 ->leftjoin('user as u2','news.ApproverID','=',DB::raw('u2.UserID'))
        //                 ->where('news.Status', '=', $status)
        //                 ->where(function($query) use ($search)
        //                 {
        //                     $query->orWhere('news.Title','like','%'.$search.'%')
        //                           ->orWhere('CateName','like','%'.$search.'%')
        //                           ->orWhere(DB::raw('concat(u1.FirstName, " ", u1.LastName)'),'like','%'.$search.'%')
        //                           ->orWhere(DB::raw('concat(u2.FirstName, " ", u2.LastName)'),'like','%'.$search.'%');
        //                 })
        //                 // ->where('news.Title','like','%'.$search.'%')
        //                 // ->orWhere('CateName','like','%'.$search.'%')
        //                 // ->orWhere('news.Author','like','%'.$search.'%')
        //                 ->select('news.*','categories.CateName','u1.FirstName as AuthorFirstName','u1.LastName as AuthorLastName','u2.FirstName as ApproverFirstName',
        //                 'u2.LastName as ApproverLastName')
        //                 ->orderByDesc('news.CreatedDate')
        //                 ->paginate(8);
        //     $news->appends('search', $search);
        //     $news->appends('status', $status);
        //     if(!empty($news))
        //     {
        //         return view('admin.news.news-manage',compact('news'));
        //     }
        //     else
        //     {
        //         return view('admin.news.news-manage')->with('msg','Chưa có bài viết nào!');
        //     }           
        // }
    }

    //Route for categories
    public function getCategoriesManagement()
    {
        $categories = DB::table('categories')
                            ->join('user','categories.AuthorID','=','user.UserID')
                            ->select('categories.*','user.FirstName','user.LastName','user.Role')
                            ->paginate(8);
        return view('admin.categories.categories-manage',compact('categories'));
    }
    public function getCategoriesCreate()
    {
        return view('admin.categories.categories-create');
    }
    public function postCategoriesCreate(Request $request)
    {
        $request->validate([
            'category' => ['required','unique:categories,CateName']
        ],[
            'category.required' => 'Tên chuyên mục cần được nhập',
            'category.unique' => 'Tên chuyên mục đã tồn tại'
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dataInsert = [
            $request->category,
            session()->get('currentUser')['id'],
            date('Y-m-d H:i:s'),
        ];
        DB::insert("INSERT INTO categories (CateName, AuthorID, CreatedDate) VALUES (?,?,?)",$dataInsert);
        $dataLog = [
            session()->get('currentUser')['id'],
            'Thêm chuyên mục mới: '.$request->category,
            date('Y-m-d H:i:s'),
        ];
        DB::insert("INSERT INTO log (UserID, Activity, Date) VALUES (?,?,?)",$dataLog);
        return redirect()->route('admin.categories-management')->with('success-msg','Tạo chuyên mục thành công');
    }
    public function getCategoriesEdit($id=0)
    {
        if(!empty($id))
        {
            $categoriesDetail = DB::select("SELECT * FROM categories WHERE CateID = ?",[$id]);
            if(!empty($categoriesDetail))
            {
                $categoriesDetail = $categoriesDetail[0];
                return view('admin.categories.categories-edit',compact('categoriesDetail'));
            }
            else
            {
                return redirect()->route('admin.categories-management')->with('msg','Chuyên mục không tồn tại');   
            }
        }
        else
        {
            return redirect()->route('admin.categories-management')->with('msg','Chuyên mục không tồn tại');
        }
    }
    public function postCategoriesEdit(Request $request, $id = 0)
    {
        $request->validate([
            'category' => ['required','unique:categories,CateName, '.$id.',CateID']
        ],[
            'category.required' => 'Tên chuyên mục cần được nhập',
            'category.unique' => 'Tên chuyên mục đã tồn tại'
        ]);
        $oldCate = DB::select("SELECT CateName FROM categories WHERE CateID = ?",[$id]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dataInsert = [
            $request->category,
            date('Y-m-d H:i:s'),
            $id
        ];
        DB::update("UPDATE categories SET CateName = ?, UpdateDate = ? WHERE CateID = ?",$dataInsert);
        $dataLog = [
            session()->get('currentUser')['id'],
            'Sửa tên chuyên mục '.$oldCate[0]->CateName. " ". 'thành ' .$request->category,
            date('Y-m-d H:i:s'),
        ];
        DB::insert("INSERT INTO log (UserID, Activity, Date) VALUES (?,?,?)",$dataLog);
        return redirect()->route('admin.categories-management')->with('success-msg','Cập nhật chuyên mục thành công');
    }
    public function getCategoriesDelete($id = 0)
    {
        if(!empty($id) && !empty(DB::select("SELECT * FROM categories WHERE CateID = ?",[$id])))
        {
            $oldCate = DB::select("SELECT CateName FROM categories WHERE CateID = ?",[$id]);

            $fileName = DB::select("SELECT Image FROM news WHERE CateID = ?",[$id]);

            foreach($fileName as $key => $item)
            {
                Storage::disk('public')->delete($item->Image);
            }

            DB::delete("DELETE FROM categories WHERE CateID = ?",[$id]);
            $dataLog = [
                session()->get('currentUser')['id'],
                'Xóa chuyên mục: '.$oldCate[0]->CateName,
                date('Y-m-d H:i:s'),
            ];
            DB::insert("INSERT INTO log (UserID, Activity, Date) VALUES (?,?,?)",$dataLog);
            return redirect()->route('admin.categories-management')->with('success-msg','Xóa chuyên mục thành công');
        }
        else
        {
            return redirect()->route('admin.categories-management')->with('msg','Chuyên mục không tồn tại');
        }
    }
    public function getCategoriesSearch(Request $request)
    {
        $search = $request->query('search');
        if(!empty($search))
        {
            $categories = DB::table('categories')
                            ->join('user','categories.AuthorID','=','user.UserID')
                            ->where('categories.CateName','like','%'.$search.'%')
                            ->orWhere('user.FirstName','like','%'.$search.'%')
                            ->orWhere('user.LastName','like','%'.$search.'%')
                            ->orWhere(DB::raw('concat(user.FirstName," ",user.LastName)'),'like','%'.$search.'%')
                            ->orWhere('user.Role','like','%'.$search.'%')
                            ->select('categories.*','user.FirstName','user.LastName','user.Role')
                            ->paginate(8);
            $categories->appends('search', $search);
        return view('admin.categories.categories-manage',compact('categories'));
        }
        else
        {
            $categories = DB::table('categories')
                            ->join('user','categories.AuthorID','=','user.UserID')
                            ->select('categories.*','user.FirstName','user.LastName','user.Role')
                            ->paginate(8);
        return view('admin.categories.categories-manage',compact('categories'));
        }
    }
    public function getCategoriesAssignManage()
    {
        // $editors = DB::table('user')
        //                 ->leftjoin('cate_assign','user.UserID','=','cate_assign.UserID')
        //                 ->where('role','=','biên tập viên')
        //                 ->select('user.*','cate_assign.CreatedDate as DateCreate')
        //                 // ->distinct()
        //                 ->orderBy('user.UserID')
        //                 ->paginate(8);
            $date_assigned = [];
            $editors = DB::table('user')
                            ->where('role','=','biên tập viên')
                            ->select('user.*')
                            // ->distinct()
                            ->orderBy('user.UserID')
                            ->paginate(8);
            $assigned_time = DB::table('cate_assign')
                                ->select('cate_assign.UserID',DB::raw('MAX(cate_assign.CreatedDate) AS DATE'))
                                ->groupBy('cate_assign.UserID')
                                ->get();
            foreach($assigned_time as $key => $item)
            {
                $date_assigned[$item->UserID] = $item->DATE;
            }
        return view('admin.categories.categories-assign-manage',compact('editors','date_assigned'));
    }
    public function getCategoriesAssign($id = 0)
    {
        $categories = DB::select("SELECT * FROM categories");
        $selectedCate = DB::select("SELECT * FROM cate_assign WHERE UserID = ?",[$id]);
        $result = [];
        foreach($selectedCate as $key => $value)
        {
            $result[] = $value->CateID;
        }
        return view('admin.categories.categories-assign',compact('categories','result'));
    }
    public function postCategoriesAssign(Request $request,$id = 0)
    {
        $oldvalue = [];
        $old = DB::select("SELECT * FROM cate_assign WHERE UserID = ?",[$id]);
        if(!empty($request->selection))
        {
            foreach($old as $key => $value)
            {
                $oldvalue[] = $value->CateID;
            }
            foreach($request->selection as $key => $value)
            {
                if(!in_array($value,$oldvalue))
                {
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $updateDate = date('Y-m-d H:i:s');
                    $dataInsert = [
                        $value,
                        $id,
                        date('Y-m-d H:i:s'),
                    ];
                    DB::insert("INSERT INTO cate_assign (CateID, UserID, CreatedDate) VALUES (?,?,?)",$dataInsert);
                    DB::update("UPDATE cate_assign SET CreatedDate = ? WHERE UserID = ? AND CateID != ?",[$updateDate,$id,$value]);
                }
            }
            foreach($oldvalue as $key => $value)
            {
                if(!in_array($value,$request->selection))
                {
                    DB::delete("DELETE FROM cate_assign WHERE UserID = ? AND CateID = ?",[$id,$value]);
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $dateInsert = date('Y-m-d H:i:s');
                    DB::update("UPDATE cate_assign SET CreatedDate = ? WHERE UserID = ? AND CateID != ?",[$dateInsert,$id,$value]);
                }
            }
            return redirect()->route('admin.categories-assign-manage')->with('success-msg','Phân quyền chuyên mục thành công');
        }
        else{
            DB::delete("DELETE FROM cate_assign WHERE UserID = ?",[$id]);
            return redirect()->route('admin.categories-assign-manage')->with('success-msg','Xóa phân quyền chuyên mục thành công');
        }
    }
}
