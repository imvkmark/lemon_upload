<?php

class AccountTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testAccountCreate() {
		$user       = 'test_'.str_random(10);
		$pwd        =  '123456';
		$account_id = \Account::registerFront($user, $pwd);
		$this->assertNotFalse(\App\Models\AccountFront::where('account_id', $account_id)->get());
	}


	public function testMbStringLength() {
		$string = '纯中文';
		var_dump(strlen($string));
	}
	
	
	public function testSms() {
//		$app = $this->createApplication();
//		var_dump(config('app.providers'));
//		var_dump(app('l5.ip'));
		app('l5.sms')->test('15254109156');
//		var_dump($app);
	}

}
