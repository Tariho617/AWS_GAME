<?php

namespace App\Http\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\Models\UserProfile;
use App\Models\MasterLoginItem;

class LoginController extends Controller
{
	public function Login(Request $request)
	{
		date_default_timezone_set('Asia/Tokyo');

		$log = new Logger('debug');
		$log->pushHandler(new StreamHandler('/var/www/html/SampleProject/storage/logs/debug/loginDebug.log', Logger::DEBUG));
		
		$client_master_version = $request->client_master_version;
		$user_id = $request->user_id;

		//マスターデータチェック
		if(!MasterDataService::CheckMasterDataVersion($client_master_version))
		{
			$log->warning(config(('error.ERROR_MASTER_DATA_UPDATE '.'ClientVer: '.$client_master_version)));
			return config('error.ERROR_MASTER_DATA_UPDATE');
		}
                
        //user_profileとmaster_login_itemのテーブルのレコードを取得
		$user_profile = UserProfile::where('user_id', $user_id)->first();
		$log->debug('loginday: '.$user_profile->login_day);
		$master_login_item = MasterLoginItem::where('login_day', $user_profile->login_day + 1)->first();

        //レコード存在チェック
		if(!$user_profile || !$master_login_item)
		{
			$log->warning(config('error.ERROR_INVALID_DATA').' user_profile: '.$user_profile .'master_login_item: '.$master_login_item);
			return config('error.ERROR_INVALID_DATA');
		}

		//初回ログイン時のみ実行される
		if($user_profile->login_day == 0)
		{
			//初期値の設定
			$last_login_at = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, 2000));
			$user_profile->last_login_at = $last_login_at;
		}

		//日付の比較
		$today = date('Y-m-d');
		$log->debug("today: ".$today);
		$last_login_day = date('Y-m-d', strtotime($user_profile->last_login_at));
		$log->debug("last_login_day: ".$last_login_day);

		if($today !== $last_login_day)
		{
			//ログイン日数を更新し、その日のログインボーナス
			$log->debug("ログイン日数更新");
			$log->debug('BeforeLoginday: '.$user_profile->login_day);
			$user_profile->login_day += 1;
			$log->debug('UpdateLoginday: '.$user_profile->login_day);
			$log->debug('itemType : '.$master_login_item->item_type);
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
			else
			{
				$log->error('アイテムのレコードがないよ');
				return config('error.ERROR_NOTFOUND_ITEM');
			}
		}
			$log->debug(("アイテムデータ確認終わり"));
			//ログイン時刻の更新
			$user_profile->last_login_at = date("Y-m-d H:i:s");

			//ユーザープロファイルとログイン情報を保存し、クライアントにレスポンスを返す
			try
			{
				$log->debug('トライしてる');
				$user_profile->save();
				$master_login_item->save();
			}
			catch(\PDOException $error)
			{
				$log->debug(("エラーしてる"));
				$log->warning('PDOExeption'.$error);
				return config('error.ERROR_DB_UPDATE');
			}

				//クライアントへのレスポンス
				$response = 
				[
					"user_profile" => $user_profile,
					"master_login_item" => $master_login_item
				];

			return response()->json($response);
	}
}
