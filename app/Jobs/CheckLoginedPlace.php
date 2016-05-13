<?php
/**
 * 统计用户的各种数量
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */
namespace App\Jobs;

use App\Models\PamLog;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckLoginedPlace extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	protected $account_id;

	/**
	 * 统计用户计算数量
	 * @return void
	 */
	public function __construct($account_id) {
		$this->account_id = $account_id;
	}

	/**
	 * Execute the job.
	 * @return void
	 */
	public function handle() {
		$logined = PamLog::where('account_id', $this->account_id)
			->take(2)
			->orderBy('created_at', 'desc')
			->get()->toArray();
		if (count($logined) == 2) {
			$now  = $logined[0];
			$last = $logined[1];
			if ($now['log_area_id'] != $last['log_area_id']) {
				// 视为异地登陆
				$content = trans('sms.strange_land', [
					'time'      => '15:23',
					'last_area' => $last['log_area_text'],
					'this_area' => $now['log_area_text'],
				]);
				// todo 记录异地登录日志绑定
				// 短信发送. 必须绑定
				/*
				\App::make('lemon.sys.sms')->send('15254109156', $content);
				*/
			}
		}
	}
}
