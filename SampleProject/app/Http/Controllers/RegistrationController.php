<?php

namespace App\Http\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Http\Request;
use App\Models\UserProfile;

class RegistrationController extends Controller
{
    public function Registration(Request $request)
    {
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler('logs/response.log', Logger::WARNING));
        $log->info('Request received', ['params' => $request->all()]);

        //ユーザーIDの決定
        $user_id = uniqid(); //例:4b3403665fea6

        //初期データの設定
        $user_profile = new UserProfile;
        $user_profile->user_id = $user_id;
        $user_profile->user_name = $request->user_name;
        $user_profile->jewel = config('constants.JEWEL_DEFAULT');
        $user_profile->jewel_free = config('constants.JEWEL_FREE_DEFAULT');
        $user_profile->friend_coin = config('constants.FRIEND_COIN_DEFAULT');
        $user_profile->tutorial_progress = config('constants.TUTORIAL_START');

        //データの書き込み
        try {
            $user_profile->save();
        } catch (\PDOException $e) {
            $log->error($e->getMessage());
            return config('error.ERROR_DB_UPDATE');
        }

        //クライアントへのレスポンス
        $user_profile = UserProfile::where('user_id', $user_id)->first();

        $response = array(
            'user_profile' => $user_profile,
        );

        return response()->json($response);
    }
}
