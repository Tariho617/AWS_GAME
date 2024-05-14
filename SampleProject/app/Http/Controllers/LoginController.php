<?php

namespace App\Http\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\Models\UserProfile;
use App\Models\UserLogin;
use App\Models\MasterLoginItem;

class LoginController extends Controller
{
	public function Login(Request $request)
	{
		$log = new Logger('debug');
		$log->pushHandler(new StreamHandler('/var/www/html/SampleProject/storage/debug/loginDebug.log', Logger::DEBUG));
		
		$client_master_version = $request->client_master_version;
		$user_id = $request->user_id;

		//マスターデータチェック
		if(!MasterDataService::CheckMasterDataVersion($client_master_version))
		{
			return config('error.ERROR_MASTER_DATA_UPDATE');
		}
                
        //user_profileテーブルのレコードを取得
		$user_profile = UserProfile::where('user_id', $user_id)->first();

        //レコード存在チェック
		if(!$user_profile)
		{
			return config('error.ERROR_INVALID_DATA');		
		}

		//ログインボーナステーブルのレコードを取得
		$user_login = UserLogin::where('user_id', $user_id)->first();
		if(!$user_login)
		{
			//初期値の設定
			$user_login = new UserLogin;
			$user_login->user_id = $user_id;
			$user_login->login_day = 0;
			$last_login_at = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, 2000));
			$user_login->last_login_at = $last_login_at;
		}

		//日付の比較
		$today = date('Y-m-d');
		$last_login_day = date('Y-m-d', strtotime($user_login->last_login_at));
		if(strtotime($today) !== $last_login_day)
		{
			$user_login->login_day += 1;
			$master_login_item = MasterLoginItem::GetMasterLoginItemByLoginDay($user_login->login_day);

			//アイテムデータがあるか確認
			if(!is_null($master_login_item))
			{
				//アイテム付与
				switch($master_login_item->item_type)
				{
			           case config('constants.ITEM_TYPE_JEWELE'):
					   $user_profile->jewele += $master_login_item->item_count;
					   break;
				   case config('constants.ITEM_TYPE_JEWELE_FREE'):
					   $user_profile->jewele_free += $master_login_item->item_count;
					   break;
				   case config('constants.ITEM_TYPE_FRIEND_COIN'):
					   $user_profile->friend_coin += $master_login_item->item_count;
					   break;
				   default:
					   break;
				}
			}

			//ログイン時刻の更新
			$user_login->last_login_at = date("Y-m-d H:i:s");

			//ユーザープロファイルとログイン情報を保存し、クライアントにレスポンスを返す
			try
			{
				$user_profile->save();
				$user_login->save();
			}
			catch(\PDOException $error)
			{
				$log->warning($error);
				return config('error.ERROR_DB_UPDATE');
			}

				//クライアントへのレスポンス
				$response = 
				[
					"user_profile" => $user_profile,
					"user_login" => $user_login
				];

			return response()->json($response);
		}
	}
}
