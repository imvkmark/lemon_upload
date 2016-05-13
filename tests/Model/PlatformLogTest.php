<?php namespace Model;

use App\Models\PlatformLog;

class PlatformLogTest extends \TestCase {

	/**
	 * A basic functional test example.
	 * @return void
	 */
	public function testLog() {
		PlatformLog::record(5, 8, PlatformLog::LOG_TYPE_PUBLISH, 'log');
	}


}
