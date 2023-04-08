<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    //
    public function getAdminLogin(){
        return view('admin.login-admin');
    }
    public function postAdminLogin(Request $request){
        $request->validate([
            'account' => ['required','exists:user,Account'],
            'password' => ['required']
        ],[
            'account.required' => 'Tên tài khoản cần được nhập',
            'account.exists' => 'Tên tài khoản không tồn tại',
            'password.required' => 'Mật khẩu cần được nhập'
        ]);
        $accountPassword = DB::select('SELECT Password FROM user WHERE Account = ?',[$request->account]);
        $loginPassword = $request->password;
        if($loginPassword === $accountPassword[0]->Password)
        {
            $currentUser = DB::select('SELECT * FROM user WHERE Account = ?',[$request->account]);
            $currentUserName = $currentUser[0]->FirstName. " " .$currentUser[0]->LastName;
            $request->session()->put('currentUser',['name' => $currentUserName, 'role' => $currentUser[0]->Role, 'id' => $currentUser[0]->UserID]);
            return redirect()->route('admin.dashboard');
        }
        else{
            return redirect()->back()->with('error','Sai mật khẩu')->withInput();
        }
    }
    public function getAdminLogOut(){
        if(session()->has('currentUser')){
            session()->pull('currentUser');
        }
        return redirect()->route('admin.login');
    }
    public function getUserManagement(){
        // $users = DB::select("SELECT * FROM user");
        $users = DB::table('user')
                    ->select('user.*')
                    ->paginate(8);
        if(!empty($users))
        {
            return view('admin.users-manage',compact('users'));
        }
        else
        {
            return view('admin.users-manage')->with('msg','Không có tài khoản nhân viên nào');
        }   
    }
    public function getUserRegister(){
        return view('admin.user-register');
    }
    public function postUserRegister(Request $request){
        $request->validate([
            'account' => ['required','min:6','unique:user,Account'],
            'password' => ['required','min:6'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'role' => ['required'],
            'email' => ['required','email','unique:user,Email'],
            'phone' => ['required']
        ],[
            'account.required' => 'Tên tài khoản cần được nhập',
            'account.min' => 'Tên tài khoản cần lớn hơn :min ký tự',
            'account.unique' => 'Tên tài khoản đã tồn tại',
            'password.required' => 'Mật khẩu cần được nhập',
            'password.min' => 'Mật khẩu cần tối thiểu :min ký tự',
            'first_name.required' => 'Họ tên cần được nhập',
            'last_name.required' => 'Họ tên cần được nhập',
            'role.required' => 'Chức vụ cần được nhập',
            'email.required' => 'Email cần được nhập',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được dùng để đăng ký tài khoản',
            'phone.required' => 'Số điện thoại cần được nhập'
        ]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $createdDate = date('Y-m-d H:i:s');
        $dataInsert = [
            $request->account,
            $request->password,
            $request->first_name,
            $request->last_name,
            $request->role,
            $request->email,
            $request->phone,
            $createdDate
        ];  
        DB::insert("INSERT INTO user (Account, Password, FirstName, LastName, Role, Email, Phone, CreatedDate) VALUES (?,?,?,?,?,?,?,?)",$dataInsert);
        return redirect()->back()->with('msg','Tạo tài khoản mới thành công');
    }
    public function getUserEdit(Request $request, $id=0)
    {
        if(!empty($id)){
            $userDetail = DB::select("SELECT * FROM user WHERE UserID = ?",[$id]);
            if(!empty($userDetail[0]))
            {
                $userDetail = $userDetail[0];
            }
            else
            {
                return redirect()->route('admin.user-management')->with('msg','Tài khoản không tồn tại');
            }
        }
        else{
            return redirect()->route('admin.user-management')->with('msg','Liên kết không tồn tại');
        }
        return view('admin.user-edit',compact('userDetail'));
    }
    public function postUserEdit(Request $request, $id=0)
    {
        $request->validate([
            'account' => ['required','min:5'],
            'password' => ['required','min:6'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'role' => ['required'],
            'email' => ['required','email'],
            'phone' => ['required']
        ],[
            'account.required' => 'Tên tài khoản cần được nhập',
            'account.min' => 'Tên tài khoản cần lớn hơn :min ký tự',
            'password.required' => 'Mật khẩu cần được nhập',
            'password.min' => 'Mật khẩu cần tối thiểu :min ký tự',
            'first_name.required' => 'Họ tên cần được nhập',
            'last_name.required' => 'Họ tên cần được nhập',
            'role.required' => 'Chức vụ cần được nhập',
            'email.required' => 'Email cần được nhập',
            'email.email' => 'Email không đúng định dạng',
            'phone.required' => 'Số điện thoại cần được nhập'
        ]);
        $accountUpdate = $request->account;
        $emailUpdate = $request->email;
        $oldAccount = DB::select('SELECT Account FROM user WHERE UserID = ?',[$id]);
        $oldEmail = DB::select('SELECT Email FROM user WHERE UserID = ?',[$id]);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        if($accountUpdate == $oldAccount[0]->Account && $emailUpdate == $oldEmail[0]->Email)
        {
            $dataUpdate = [
                $request->password,
                $request->first_name,
                $request->last_name,
                $request->role,
                $request->phone,
                date('Y-m-d H:i:s'),
                $id
            ];
            DB::update('UPDATE user SET Password = ?, FirstName = ?, LastName = ?, Role = ?, Phone = ?, UpdateDate = ? WHERE UserID = ?',$dataUpdate);
            return redirect()->route('admin.user-management')->with('success-msg','Cập nhật thông tin tài khoản thành công');
        }
        else if($accountUpdate == $oldAccount[0]->Account && $emailUpdate != $oldEmail[0]->Email)
        {
            $dataUpdate = [
                $request->password,
                $request->first_name,
                $request->last_name,
                $request->role,
                $request->email,
                $request->phone,
                date('Y-m-d H:i:s'),
                $id
            ];
            $emailCheck = DB::select("SELECT Email FROM user WHERE Email = ?",[$emailUpdate]);
            if(empty($emailCheck))
            {
                DB::update('UPDATE user SET Password = ?, FirstName = ?, LastName = ?, Role = ?, Email = ?, Phone = ?, UpdateDate = ? WHERE UserID = ?',$dataUpdate);
                return redirect()->route('admin.user-management')->with('success-msg','Cập nhật thông tin tài khoản thành công');
            }
            else
            {
                return redirect()->back()->with('errEmail','Email đã được dùng để đăng ký tài khoản');
            }
        }
        else if($accountUpdate != $oldAccount[0]->Account && $emailUpdate == $oldEmail[0]->Email)
        {
            $accountCheck = DB::select("SELECT Account FROM user WHERE Account = ?",[$accountUpdate]);
            if(empty($accountCheck))
            {
                $dataUpdate = [
                    $accountUpdate,
                    $request->password,
                    $request->first_name,
                    $request->last_name,
                    $request->role,
                    $request->phone,
                    date('Y-m-d H:i:s'),
                    $id
                ];
                DB::update('UPDATE user SET Account = ?, Password = ?, FirstName = ?, LastName = ?, Role = ?, Phone = ?, UpdateDate = ? WHERE UserID = ?',$dataUpdate);
                return redirect()->route('admin.user-management')->with('success-msg','Cập nhật thông tin tài khoản thành công');
            }
            else
            {
                return redirect()->back()->with('errAccount','Tên tài khoản đã tồn tại');
            }
        }
        else
        {
            $accountCheck = DB::select("SELECT Account FROM user WHERE Account = ?",[$accountUpdate]);
            $emailCheck = DB::select("SELECT Email FROM user WHERE Email = ?",[$emailUpdate]);
            if(empty($accountCheck) && empty($emailCheck))
            {
                $dataUpdate = [
                    $accountUpdate,
                    $request->password,
                    $request->first_name,
                    $request->last_name,
                    $request->role,
                    $emailUpdate,
                    $request->phone,
                    date('Y-m-d H:i:s'),
                    $id
                ];
                // $dataDetailUpdate = [
                //     $request->first_name,
                //     $request->last_name,
                //     $request->role,
                //     $emailUpdate,
                //     $request->phone,
                //     $id
                // ];
                DB::update('UPDATE user SET Account = ?, Password = ?, FirstName = ?, LastName = ?, Role = ?, Email = ?, Phone = ?, UpdateDate = ?
                WHERE UserID = ?',$dataUpdate);
                return redirect()->route('admin.user-management')->with('success-msg','Cập nhật thông tin tài khoản thành công');
            }
            else if (!empty($accountCheck) && empty($emailCheck))
            {
                return redirect()->back()->with('errAccount','Tên tài khoản đã tồn tại');
            }
            else if (empty($accountCheck) && !empty($emailCheck))
            {
                return redirect()->back()->with('errEmail','Email đã được sử dụng để đăng ký tài khoản');
            }
            else
            {
                return redirect()->back()->with('errAccount','Tên tài khoản đã tồn tại')->with('errEmail','Email đã được sử dụng để đăng ký tài khoản');
            }
        }
    }
    public function deleteUser($id=0){
        if(session()->get('currentUser')['role'] == 'quản trị viên')
        {
            DB::delete("DELETE FROM user WHERE user.UserID = ?",[$id]);
            return redirect()->route('admin.user-management')->with('success-msg','Xóa tài khoản thành công');
        }
        else
        {
            return redirect()->route('admin.dashboard');
        }
    }
    public function searchUser(Request $request){
        $search = $request->query('search');
        if(!empty($search))
        {
            // $users = DB::select("SELECT * FROM user WHERE user.Account LIKE '%$search%' 
            // OR FirstName LIKE '%$search%' OR LastName LIKE '%$search%' OR Role LIKE '%$search%'
            // OR Phone LIKE '%$search%' OR Email LIKE '%$search%'");
            $users = DB::table('user')
                        ->where('Account','like','%'.$search.'%')
                        ->orWhere('FirstName','like','%'.$search.'%')
                        ->orWhere('LastName','like','%'.$search.'%')
                        ->orWhere('Role','like','%'.$search.'%')
                        ->orWhere('Phone','like','%'.$search.'%')
                        ->orWhere('Email','like','%'.$search.'%')
                        ->select('user.*')
                        ->paginate(5);
            $users->appends('search', $search);// Gui kem gia tri search de chuyen trang
            if(!empty($users))
            {
                return view('admin.users-manage',compact('users'));
            }
            else
            {
                return redirect()->route('admin.user-management')->with('msg','Không tìm thấy tài khoản nhân viên nào');
            }
        }
        else
        {
            return redirect()->route('admin.user-management');
        }
    }
    //Reader
    public function getReaderManagement(){
        // $readers = DB::select("SELECT * FROM reader");
        $readers = DB::table('reader')
                    ->select('reader.*')
                    ->paginate(8);
        if(!empty($readers))
        {
            return view('admin.readers-manage',compact('readers'));
        }
        else
        {
            return view('admin.readers-manage')->with('msg','Không có tài khoản người đọc nào');
        }   
    }
    public function getReaderDelete($id=0){
        if(session()->get('currentUser')['role'] == 'quản trị viên')
        {
            $emailSendTo = DB::select("SELECT FirstName, LastName, Email FROM reader WHERE ReaderID = ?",[$id]);
            DB::delete("DELETE FROM reader WHERE ReaderID = ?",[$id]);
            $emailDetail = [
                'receiver' => $emailSendTo[0]->FirstName. " ".$emailSendTo[0]->LastName,
                'title' => 'Thông báo về việc xóa tài khoản',
                'body' => 'Tài khoản của bạn đã bị xóa bởi quản trị viên vì lý do vi phạm nội quy website',
            ];
            Mail::to($emailSendTo[0]->Email)->send(new SendMail($emailDetail));
            return redirect()->route('admin.reader-management')->with('success-msg','Xóa tài khoản thành công');
        }
        else
        {
            return redirect()->route('admin.dashboard');
        }
    }
    public function searchReader(Request $request){
        $search = $request->query('search');
        if(!empty($search))
        {
            // $readers = DB::select("SELECT * FROM reader WHERE Account LIKE '%$search%' OR FirstName LIKE '%$search%'
            // OR LastName LIKE '%$search%' OR Email LIKE '%$search%' OR Phone LIKE '%$search%'");
            $readers = DB::table('reader')
                        ->where('Account','like','%'.$search.'%')
                        ->orWhere('FirstName','like','%'.$search.'%')
                        ->orWhere('LastName','like','%'.$search.'%')
                        ->orWhere('Phone','like','%'.$search.'%')
                        ->orWhere('Email','like','%'.$search.'%')
                        ->select('reader.*')
                        ->paginate(5);
            $readers->appends('search', $search);// Gui kem gia tri search de chuyen trang
            if(!empty($readers))
            {

                return view('admin.readers-manage',compact('readers'));
            }
            else
            {
                return redirect()->route('admin.reader-management')->with('msg','Không tìm thấy tài khoản người đọc nào');
            }
        }
        else
        {
            return redirect()->route('admin.reader-management');
        }        
    }
}
