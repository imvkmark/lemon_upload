<?php namespace Model;

use App\Lemon\Dailian\System\SdlKernel;
use App\Models\PlatformOrder;

class PlatformOrderTest extends \TestCase {
	/**
	 * A basic functional test example.
	 * @return void
	 */
	public function testCreateEvent() {
		$order = PlatformOrder::find(15);
		\Event::fire(SdlKernel::EVENT_PLATFORM_ORDER_CREATE, [$order]);
	}


}
