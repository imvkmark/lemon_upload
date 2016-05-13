<?php
use Hyancat\Sendcloud\SendCloudMessage;

class LmUtilTest extends TestCase {

	public function testGetMobile() {
		$mobile = '+8615988746651';
		$this->assertEquals(\App\Lemon\Repositories\Sour\LmUtil::getMobile($mobile), '15988746651');
	}


	public function testMailSend() {
		\SendCloud::sendTemplate('_layout.email.front_welcome', ['name' => '小明'], function (SendCloudMessage $message) {
			$message->to(['zhaody901@126.com'])->subject('你好！');
		})->success(function ($response) {
		})->failure(function ($response, $error) {
		});
	}

}
