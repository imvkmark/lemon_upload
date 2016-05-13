<?php

class Carbon extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testCarbon()
	{
		$Carbon = new \Carbon\Carbon();
		echo $Carbon->addMinutes(10);
		$this->assertNotFalse(true);
	}

	public function testCode() {
		$encodeString = \App\Lemon\Repositories\System\SysCrypt::encode('11223344556677889900', 'axz');
		echo $encodeString."\n";
		echo \App\Lemon\Repositories\System\SysCrypt::decode($encodeString, 'axz');
	}
}
