<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    //
    public function getHome(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_time = date('D, d M Y H:i');
        $categories = DB::select("SELECT * FROM categories");
        $featureNews = DB::select("SELECT * FROM news WHERE Status = 'Chấp thuận' ORDER BY CreatedDate DESC LIMIT 5");
        $randomNews = DB::select("SELECT * FROM news WHERE Status = 'Chấp thuận' ORDER BY RAND() LIMIT 5");
        // $newsByCategory = DB::select("SELECT * FROM news WHERE Status = 'Chấp thuận' ORDER BY CateID ASC, CreatedDate DESC"); //Tin moi nhat  trong chuyen muc hien thi to nhat
        $newsSend = [];
        $cateHasNews = DB::select("SELECT DISTINCT news.CateID, categories.CateName FROM news INNER JOIN categories ON news.CateID = categories.CateID WHERE news.Status = 'Chấp thuận' ORDER BY news.CateID");
        foreach($cateHasNews as $key => $item)
        {
            $newsByCategory = DB::select("SELECT * FROM news WHERE CateID = $item->CateID AND Status = 'Chấp thuận' ORDER BY CateID ASC, CreatedDate DESC LIMIT 6"); //Tin moi nhat  trong chuyen muc hien thi to nhat
            foreach($newsByCategory as $key1 => $item1)
            {
                // if($item1->CateID == $item->CateID)
                // {
                    $newsSend[$item->CateID][] = [$item1->Title, $item1->Excerpt, $item1->Image, $item1->NewsID];
                // }
            }
        }
        $mostView = DB::select("SELECT * FROM news WHERE Status = 'Chấp thuận' ORDER BY ViewCount DESC LIMIT 5");
        return view('clients.home',compact('current_time','categories','featureNews','randomNews','newsSend','cateHasNews','mostView'));
    }
    public function getLogin(){
        return view('clients.login');
    }
    public function getRegister(){
        return view('clients.register');
    }
    public function postRegister(Request $request){
        // dd($request);   
        $request->validate([
            'account' => ['required','min:6','unique:reader,Account'],
            'password' => ['required','min:6'],
            'email' => ['required','email','unique:reader,Email'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'g-recaptcha-response' => 'required|captcha'
        ],[
            'account.required' => 'Tên tài khoản bắt buộc phải nhập',
            'account.min' => 'Tên tài khoản phải từ :min ký tự trở lên',
            'account.unique' => 'Tên tài khoản đã tồn tại',
            'password.required' => 'Mật khẩu bắt buộc phải nhập',
            'password.min' => 'Mật khẩu phải từ :min ký tự trở lên',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email cần đúng định dạng dành cho email',
            'email.unique' => 'Email đã được sử dụng để đăng ký tài khoản',
            'first_name.required' => "Họ tên cần được nhập",
            'last_name.required' => 'Họ tên cần được nhập',
            'g-recaptcha-response.required' => 'Vui lòng điền captcha',
            'g-recaptcha-response.captcha' => 'Lỗi captcha, vui lòng thử lại sau'
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $createdDate = date('Y-m-d H:i:s');
        $dataInsert = [
            $request->account,
            $request->password,
            $request->first_name,
            $request->last_name,
            $request->email,
            $request->phone,
            $createdDate
        ];
        DB::insert('INSERT INTO reader (Account, Password, FirstName, LastName, Email, Phone, CreatedDate) VALUES (?,?,?,?,?,?,?)',$dataInsert);
        return redirect()->back()->with('msg','Đăng ký tài khoản thành công');
    }
    public function postLogin(Request $request){
        $accountLogin = $request->account;
        $request->validate([
            'account' => ['required','exists:reader,Account'],
            'password' => ['required',Rule::exists('reader','Password')->where('Account',$accountLogin)]
        ],[
            'account.required' => 'Yêu cầu nhập tài khoản',
            'account.exists' => 'Tên tài khoản không tồn tại',
            'password.required' => 'Yêu cầu nhập mật khẩu',
            'password.exists' => 'Sai mật khẩu'
        ]);
        $readerAccount = $request->account;
        $currentReader = DB::select('SELECT * FROM reader WHERE Account = ?',[$readerAccount]);
        $currentName = $currentReader[0]->FirstName." ".$currentReader[0]->LastName;
        $request->session()->put('currentReader', ['name' => $currentName, 'id' => $currentReader[0]->ReaderID]);
        return redirect()->route('home');
    }
    public function getLogout(){
        if(session()->has('currentReader')){
            session()->pull('currentReader');
        }
        return redirect()->route('home');
    }
    public function getEditAccount($id=0){
        // dd($id == session()->get('currentReader')['id']);
        if(session()->get('currentReader')['id'] === (int)$id )
        {
            $dataReader = DB::select("SELECT * FROM reader WHERE ReaderID = ?",[$id]);
            if(!empty($dataReader))
            {
                $dataReader = $dataReader[0];
                return view('clients.edit-account',compact('dataReader'));
            }
            else
            {
                return redirect()->route('edit-account')->with('msg','Tài khoản không tồn tại');
            }
        }
        else
        {
            return redirect()->route('home'); 
        }  
    }
    public function postEditAccount(Request $request, $id=0)
    {
        $request->validate([
            'account' => ['required','unique:reader,Account,'.$id.',ReaderID'],
            // 'password' => ['required','min:6'],
            'email' => ['required','email','unique:reader,Email,'.$id.',ReaderID'],
            'first_name' => ['required'],
            'last_name' => ['required']
        ],[
            'account.required' => 'Tên tài khoản cần được nhập',
            'account.unique' => 'Tên tài khoản đã tồn tại',
            // 'password.required' => 'Mật khẩu bắt buộc phải nhập',
            // 'password.min' => 'Mật khẩu phải từ :min ký tự trở lên',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email cần đúng định dạng dành cho email',
            'email.unique' => 'Email đã được sử dụng để đăng ký tài khoản',
            'first_name.required' => "Họ tên cần được nhập",
            'last_name.required' => 'Họ tên cần được nhập'
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dataInsert = [
            $request->account,
            // $request->password,
            $request->email,
            $request->first_name,
            $request->last_name,
            $request->phone,
            date('Y-m-d H:i:s'),
            $id
        ];
        if(session()->get('currentReader')['id'] === (int)$id )
        {
            DB::update("UPDATE reader SET Account = ?, Email = ?, FirstName = ?, LastName = ?
            , Phone = ?, UpdateDate = ? WHERE ReaderID = ?",$dataInsert);
            return redirect()->back()->with('msg','Thay đổi thông tin tài khoản thành công');
        }
        else
        {
            return redirect()->route('home');
        }
    }
    public function getEditAccountPassword()
    {
        return view('clients.edit-account-password');
    }
    public function postEditAccountPassword(Request $request, $id = 0)
    {
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required','min:6'],
            'again_password' => ['required','same:new_password'],
        ],[
            'old_password.required' => 'Mật khẩu cũ bắt buộc phải nhập',
            'new_password.required' => 'Mật khẩu mới bắt buộc phải nhập',
            'new_password.min' => 'Mật khẩu mới phải từ :min ký tự trở lên',
            'again_password.required' => 'Vui lòng nhập lại mật khẩu mới',
            'again_password.same' => "Mật khẩu nhập lại không đúng"
        ]);
        $oldPassword = DB::select("SELECT Password FROM reader WHERE ReaderID = ?",[$id]);
        $oldPassword = $oldPassword[0]->Password;
        if($request->old_password === $oldPassword)
        {
            if(session()->get('currentReader')['id'] === (int)$id)
            {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $dataInsert = [
                    $request->new_password,
                    date('Y-m-d H:i:s'),
                    $id,
                ];
                DB::update("UPDATE reader SET Password = ?, UpdateDate = ? WHERE ReaderID = ?",$dataInsert);
                return redirect()->route('edit-account',['id' => $id])->with('msg','Thay đổi mật khẩu thành công');
            }
            else{
                return redirect()->route('home');
            }  
        }
        else
        {
            throw ValidationException::withMessages(['old_password' => 'Mật khẩu cũ không chính xác, vui lòng nhập lại']);
        }
    }
    public function getForgetPassword()
    {
        return view('clients.forget-password');
    }
    public function postForgetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required','email','exists:reader']
        ],[
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Vui lòng nhập địa chỉ email hợp lệ',
            'email.exists' => 'Email này không tồn tại trong hệ thống'
        ]);
        $token = strtoupper(Str::random(10));
        DB::update("UPDATE reader SET Token = ? WHERE reader.Email = ?",[$token,$request->email]);
        $emailSendTo = DB::select("SELECT FirstName, LastName, Email FROM reader WHERE Email = ?",[$request->email]);
        $emailDetail = [
            'receiver' => $emailSendTo[0]->FirstName. " ".$emailSendTo[0]->LastName,
            'title' => 'NewsWebsite - Lấy lại mật khẩu tài khoản',
            'body' => 'Vui lòng nhấn vào đường link dưới đây để thay đổi mật khẩu',
            'token' => $token,
        ];
        Mail::to($emailSendTo[0]->Email)->send(new ResetPasswordMail($emailDetail));
        return redirect()->back()->with('success-msg','Vui lòng kiểm tra email của bạn để thực hiện thay đổi mật khẩu');
    }
    public function getResetPassword($token)
    {
        $tokenCheck = DB::select("SELECT Token FROM reader WHERE Token = ?",[$token]);
        if(!empty($tokenCheck))
        {
            $accountEmail = DB::select("SELECT Email FROM reader WHERE Token = ?",[$token]);
            $accountEmail = $accountEmail[0];
            return view('clients.reset-password',compact('accountEmail'));
        }
        else
        {
            return redirect()->back();
        }
    }
    public function postResetPassword(Request $request, $token)
    {
        $request->validate([
            'password' => ['required','min:6'],
            'confirm_password' => ['required','same:password'],
        ],[
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu cần trên :min ký tự',
            'confirm_password.required' => 'Vui lòng nhập lại mật khẩu mới',
            'confirm_password.same' => 'Mật khẩu không trùng với mật khẩu đã nhập bên trên',
        ]);
        DB::update("UPDATE reader SET Password = ?, Token = NULL WHERE Email = ? AND Token = ?",[$request->password,$request->email,$token]);
        return redirect()->route('login')->with('success-msg','Thay đổi mật khẩu thành công !');
    }
}
