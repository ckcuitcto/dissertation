<?php

namespace App\Http\Controllers\Notification;

use App\Model\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(){

        $userLogin = $this->getUserLogin();

        $notificationList = DB::table('notifications')
            ->leftJoin('notification_users','notification_users.notification_id','=','notifications.id')
            ->leftJoin('users','users.users_id','=','notification_users.users_id')
            ->select('notifications.*','users.name')
            ->where('users.users_id',$userLogin->users_id)
            ->orderBy('id','DESC')
            ->get();

        return view('notification.index',compact('notificationList'));
    }

    public function show($id)
    {
        $userLogin = $this->getUserLogin();
        $noti = Notification::find($id);
        if(!empty($noti)) {

            DB::table('notification_users')
                ->where('users_id', $userLogin->users_id)
                ->where('notification_id', $id)
                ->update(['status' => 'Đã xem']);

            return response()->json([
                'notification' => $noti,
                'status' => true
            ], 200);
        }else{
            return response()->json([
                'status' => false
            ], 200);
        }
    }
}
