<?php

namespace App\Http\Controllers\Notification;

use App\Model\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function index(Request $request){
        if($request->id){
            $notification = Notification::find($request->id);
            return view('notification.index',compact('notification'));
        }

        return view('notification.index');
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

    public function ajaxGetNotifications(){

        $userLogin = $this->getUserLogin();

        $notificationList = DB::table('notifications')
            ->leftJoin('notification_users','notification_users.notification_id','=','notifications.id')
            ->leftJoin('users','users.users_id','=','notification_users.users_id')
            ->select('notifications.*','users.name as createdByName','notification_users.status')
            ->where('notification_users.users_id',$userLogin->users_id)
            ->orderBy('notifications.id','DESC')
            ->get();

        return DataTables::of($notificationList)
            ->editColumn('created_at', function ($noti){
                $date = Carbon::parse($noti->created_at)->format('H:i d/m/y');
                return $date;
            })
            ->addColumn('action', function ($noti) {
                $linkView = route('notifications-show',$noti->id);
                $buttonView = "<button class='view-notification btn btn-info' data-id='$noti->id' link-view='$linkView'><i class='fa fa-eye' aria-hidden='true'></i></button>";
                return "<p class='bs-component'>$buttonView </p>";
            })
            ->make(true);
    }
}
