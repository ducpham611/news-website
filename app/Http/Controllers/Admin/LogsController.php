<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    //
    public function getLogsManagement()
    {
        if(session()->get('currentUser')['role'] == 'nhà báo' || session()->get('currentUser')['role'] == 'phóng viên')
        {
            $logs = DB::table('log')
                        ->join('user','log.UserID','=','user.UserID')
                        ->select('log.*','user.FirstName','user.LastName','user.Role')
                        ->where('log.UserID','=',session()->get('currentUser')['id'])
                        ->orderBy('log.Date','DESC')
                        ->paginate(8);
            return view('admin.logs.logs-manage',compact('logs'));
        }
        else if(session()->get('currentUser')['role'] == 'biên tập viên')
        {
            $logs = DB::table('log')
                        ->join('user','log.UserID','=','user.UserID')
                        ->select('log.*','user.FirstName','user.LastName','user.Role')
                        ->where('user.Role','!=', 'quản trị viên')
                        ->where('user.Role','!=', 'tổng biên tập')
                        ->orderBy('log.Date','DESC')
                        ->paginate(8);
            return view('admin.logs.logs-manage',compact('logs'));
        }
        else
        {
            $logs = DB::table('log')
                        ->join('user','log.UserID','=','user.UserID')
                        ->select('log.*','user.FirstName','user.LastName','user.Role')
                        ->orderBy('log.Date','DESC')
                        ->paginate(8);
            return view('admin.logs.logs-manage',compact('logs'));
        }
    }
    public function getLogsSearch(Request $request)
    {
        $search = $request->query('search');
        if(!empty($search))
        {
            if(session()->get('currentUser')['role'] == 'nhà báo' || session()->get('currentUser')['role'] == 'phóng viên')
            {
                $logs = DB::table('log')
                            ->join('user','log.UserID','=','user.UserID')
                            ->select('log.*','user.FirstName','user.LastName','user.Role')
                            ->where('log.UserID','=',session()->get('currentUser')['id'])
                            ->where(function($query) use($search){
                                $query->orWhere(DB::raw('concat(user.FirstName, " ", user.LastName)'),'like','%'.$search.'%')
                                      ->orWhere('log.Activity','like','%'.$search.'%')
                                      ->orWhere('user.Role','like','%'.$search.'%');
                            })
                            ->orderBy('log.Date','DESC')
                            ->paginate(8);
                $logs->appends('search', $search);
                return view('admin.logs.logs-manage',compact('logs'));
            }
            else if(session()->get('currentUser')['role'] == 'biên tập viên')
            {
                $logs = DB::table('log')
                            ->join('user','log.UserID','=','user.UserID')
                            ->select('log.*','user.FirstName','user.LastName','user.Role')
                            ->where('user.Role','!=', 'quản trị viên')
                            ->where('user.Role','!=', 'tổng biên tập')
                            ->where(function($query) use($search){
                                $query->orWhere(DB::raw('concat(user.FirstName, " ", user.LastName)'),'like','%'.$search.'%')
                                      ->orWhere('log.Activity','like','%'.$search.'%')
                                      ->orWhere('user.Role','like','%'.$search.'%');
                            })
                            ->orderBy('log.Date','DESC')
                            ->paginate(8);
                $logs->appends('search', $search);
                return view('admin.logs.logs-manage',compact('logs'));
            }
            else
            {
                $logs = DB::table('log')
                            ->join('user','log.UserID','=','user.UserID')
                            ->select('log.*','user.FirstName','user.LastName','user.Role')
                            ->where(function($query) use($search){
                                $query->orWhere(DB::raw('concat(user.FirstName, " ", user.LastName)'),'like','%'.$search.'%')
                                      ->orWhere('log.Activity','like','%'.$search.'%')
                                      ->orWhere('user.Role','like','%'.$search.'%');
                            })
                            ->orderBy('log.Date','DESC')
                            ->paginate(8);
                $logs->appends('search', $search);
                return view('admin.logs.logs-manage',compact('logs'));
            }
        }
        else
        {
            if(session()->get('currentUser')['role'] == 'nhà báo' || session()->get('currentUser')['role'] == 'phóng viên')
            {
                $logs = DB::table('log')
                            ->join('user','log.UserID','=','user.UserID')
                            ->select('log.*','user.FirstName','user.LastName','user.Role')
                            ->where('log.UserID','=',session()->get('currentUser')['id'])
                            ->orderBy('log.Date','DESC')
                            ->paginate(8);
                return view('admin.logs.logs-manage',compact('logs'));
            }
            else if(session()->get('currentUser')['role'] == 'biên tập viên')
            {
                $logs = DB::table('log')
                            ->join('user','log.UserID','=','user.UserID')
                            ->select('log.*','user.FirstName','user.LastName','user.Role')
                            ->where('user.Role','!=', 'quản trị viên')
                            ->where('user.Role','!=', 'tổng biên tập')
                            ->orderBy('log.Date','DESC')
                            ->paginate(8);
                return view('admin.logs.logs-manage',compact('logs'));
            }
            else
            {
                $logs = DB::table('log')
                            ->join('user','log.UserID','=','user.UserID')
                            ->select('log.*','user.FirstName','user.LastName','user.Role')
                            ->orderBy('log.Date','DESC')
                            ->paginate(8);
                return view('admin.logs.logs-manage',compact('logs'));
            }
        }

    }
}
