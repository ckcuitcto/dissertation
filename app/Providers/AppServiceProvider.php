<?php

namespace App\Providers;

use App\Model\Notification;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['*'], function ($view) {
            $userLogin = Auth::user();

            if(Auth::check()){
                $notifications = DB::table('notifications')
                    ->leftJoin('notification_users','notification_users.notification_id','=','notifications.id')
                    ->leftJoin('users','users.users_id','=','notification_users.users_id')
                    ->select('notifications.*')
                    ->where('users.users_id',$userLogin->users_id)
                    ->where('notification_users.status','like','%Chưa xem%')
                    ->orderBy('id','DESC')
                    ->get();
                $view->with('notifications', $notifications);
            }
            $view->with('userLogin', $userLogin);
        });


        Schema::defaultStringLength(191);

        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
        });

        Validator::replacer('phone', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, ':attribute is invalid phone number');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
