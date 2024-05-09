<?php

namespace App\Libs;

use Illuminate\Support\Facades\File;
use App\MasterQuest;
use App\MasterLoginItem;

class MasterDataService
{
    /**
     * マスタデータ作成処理
     * 
     * @param version マスタバージョン
     */
    public static function GenerateMasterData($version)
    {
        // master_dataを追加
        $master_data_list = array();
	$master_data_list['master_quest'] = MasterQuest::all();
	$master_data_list['master_login_item'] = MasterLoginItem::all();

        // JSONファイルを作成
        $json = json_encode($master_data_list);
        $path = storage_path('app/' . $version . '.json');
        File::put($path, $json);
    }

    /**
     * マスタデータ取得処理
     * 
     * @param data_name 取得データ名
     */
    public static function GetMasterData($data_name)
    {
        $version = config('constants.MASTER_DATA_VERSION');
        $path = storage_path('app/' . $version . '.json');

        if (!File::exists($path)) {
            return false;
        }

        $json = File::get($path);
        $data = json_decode($json, true);

        return isset($data[$data_name]) ? $data[$data_name] : false;
    }

    /**
     * マスタバージョンチェック処理
     * 
     * @param client_master_version マスタバージョン(クライアント)
     */
    public static function CheckMasterDataVersion($client_master_version)
    {
        $server_master_version = config('constants.MASTER_DATA_VERSION');
        return intval($server_master_version) <= intval($client_master_version);
    }
}
