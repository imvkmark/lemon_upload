<?php namespace App\Console\Commands;

use App\Lemon\Dailian\Action\ActionDailianOrder;
use App\Lemon\Repositories\Sour\LmEnv;
use App\Models\DailianOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OverGameOrder extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'lemon:over-game-order';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Over order when the order is in examine status';

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle() {
		$orderOverAt = Carbon::createFromTimestamp(LmEnv::time() - 3600 * site('order_over_hour'))->toDateTimeString();
		$order       = DailianOrder::where('order_status', DailianOrder::ORDER_STATUS_EXAMINE)
			->where('overed_at', '<', $orderOverAt)
			->get();
		$Order = new ActionDailianOrder();
		if ($order && is_array($order)) {
			foreach ($order as $od) {
				$Order->over($od['order_id'], 0);
			}
		}
	}
}
