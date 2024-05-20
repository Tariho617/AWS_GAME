<?php

namespace App\Models;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class MasterLoginItem extends Model
{
	protected $table =  'master_login_item';
	protected $primaryKey = 'login_day';

	public static function GetMasterLoginItem()
	{
		$master_data_list = MasterDataService::GetMasterData('master_login_item');
		return $master_data_list;
	}

	public static function GetMasterLoginItemByLoginDay($login_day)
	{
		$master_data_list = self::GetMasterLoginItem();
		foreach($master_data_list as $master_data)
		{
			$master_login_item = new MasterLoginItem;
			$master_login_item->login_day = $master_data['login_day'];
			$master_login_item->item_type = $master_data['item_type'];
			$master_login_item->item_count = $master_data['item_count'];
			if($login_day == $master_login_item->login_day)
			{
				return $master_login_item;
			}
		}
		$log = new Logger('debug');
		$log->pushHandler(new StreamHandler('/var/www/html/SampleProject/storage/logs/debug/loginDebug.log', Logger::DEBUG));
		return null;
	}
}
