<?php

use App\Models\PluginAllowip;

class PluginAllowIpTest extends TestCase {

	protected $ip = '111.111.111.111';

	/**
	 * A basic functional test example.
	 * @return void
	 */
	public function testIpInsert() {
		$AllowIp = PluginAllowIp::create([
			'ip_addr' => $this->ip,
			'note'    => '单元测试创建IP',
		]);
		$this->assertNotNull($AllowIp->ip_id);
	}

	public function testIpClear() {
		$this->assertNotFalse(PluginAllowip::whereIpAddr($this->ip)->delete());
	}


}
