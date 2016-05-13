<?php namespace App\Lemon\Dailian\Action;

use App\Lemon\Repositories\Sour\LmEnv;
use App\Lemon\Repositories\Sour\LmUtil;
use App\Lemon\Repositories\System\SysCrypt;
use App\Models\ApiInit;
use Carbon\Carbon;

class ActionApi extends ActionBasic {


	/**
	 * Api 初始化
	 * @param $device_id
	 * @param $device_type
	 * @param $api_version
	 * @param $time
	 * @param $sign
	 * @return bool|string 返回 Access token
	 */
	public function init($device_id, $device_type, $api_version, $time, $sign) {

		if (intval($time) == 0) {
			return $this->setError(trans('api_front.init_time_invalid'));
		}

		if (abs($time - LmEnv::time()) > config('api.time_offset')) {
			return $this->setError(trans('api_front.init_time_offset_max'));
		}

		if (!ApiInit::deviceTypeDesc($device_type)) {
			return $this->setError(trans('api_front.init_device_invalid'));
		}


		if (!LmUtil::isVersion($api_version)) {
			return $this->setError(trans('api_front.init_version_invalid'));
		}
		$params = [
			'api_version' => $api_version,
			'device_type' => $device_type,
			'device_id'   => $device_id,
			'time'        => $time,
		];

		$calcSign = SysCrypt::crypt($params);
		if ($calcSign != $sign) {
			return $this->setError(trans('api_front.init_sign_error'));
		}

		$series      = [
			'device_id'   => $device_id,
			'device_type' => $device_type,
			'api_version' => $api_version,
		];
		$accessToken = LmUtil::md5($series);

		$series['access_token'] = $accessToken;

		// 是否存在, 如果存在, 续期增加 config('api.time_expire') 小时
		$series['updated_at'] = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->addHour(config('api.time_expire'))->toDateTimeString();

		ApiInit::updateOrCreate([
			'access_token' => $accessToken,
		], $series);

		return $accessToken;
	}


}