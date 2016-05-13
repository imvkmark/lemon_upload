<?php

class FinanceChargeTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testChargeCreate()
	{
		$Charge = new \App\Lemon\Dailian\Action\ActionCharge();
		$charge_no = \Charge::createOrder('fadan001', 5, \App\Models\FinanceCharge::CHARGE_TYPE_SYSTEM, 0);
		echo $charge_no;
		$this->assertNotNull($charge_no);
	}

}
