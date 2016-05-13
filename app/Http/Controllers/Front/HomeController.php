<?php namespace App\Http\Controllers\Front;


use App\Models\DailianOrder;
use App\Models\PluginHelp;
use App\Models\PluginKf;
use Illuminate\Http\Request;

class HomeController extends InitController {

	public function __construct(Request $request) {
		parent::__construct($request);
		$this->middleware('lm_front.auth', [
			'except' => [
				'getHomepage',
				'getTest',
				'postTest',
			]
		]);
	}

	public function getHomepage() {
		return view('front.home.homepage');
	}

	/**
	 * 主控制面板
	 * @return \Illuminate\View\View
	 */
	public function getCp() {
		$kfs       = PluginKf::where('is_enable', 'Y')->orderBy('list_order', 'asc')->get();
		$announces = PluginHelp::cat(1)->orderBy('list_order', 'asc')->take(5)->get();
		$nums      = [
			'pub_publish'   => DailianOrder::calcPubAllNum($this->ownerId),
			'pub_wait'      => DailianOrder::calcPubPublishNum($this->ownerId),
			'pub_ing'       => DailianOrder::calcPubIngNum($this->ownerId),
			'pub_exception' => DailianOrder::calcPubExceptionNum($this->ownerId),
			'pub_lock'      => DailianOrder::calcPubLockNum($this->ownerId),
			'pub_examine'   => DailianOrder::calcPubExamineNum($this->ownerId),
			'pub_cancel'    => DailianOrder::calcPubCancelIngNum($this->ownerId),
			'pub_over'      => DailianOrder::calcPubOverNum($this->ownerId),
			'sd_publish'    => DailianOrder::calcSdAssignNum($this->ownerId),
			'sd_wait'       => DailianOrder::sdWaitNum($this->ownerId),
			'sd_ing'        => DailianOrder::calcSdIngNum($this->ownerId),
			'sd_exception'  => DailianOrder::calcSdExceptionNum($this->ownerId),
			'sd_lock'       => DailianOrder::calcSdLockNum($this->ownerId),
			'sd_examine'    => DailianOrder::calcSdExamineNum($this->ownerId),
			'sd_cancel'     => DailianOrder::calcSdCancelIngNum($this->ownerId),
		];
		$orderUrl  = [
			'pub_publish'   => route_url('order.my_create', null, ['status[]' => DailianOrder::ORDER_STATUS_PUBLISH]),
			'pub_ing'       => route_url('order.my_create', null, ['status[]' => DailianOrder::ORDER_STATUS_ING]),
			'pub_exception' => route_url('order.my_create', null, ['status[]' => DailianOrder::ORDER_STATUS_EXCEPTION]),
			'pub_lock'      => route_url('order.my_create', null, ['order_lock' => 'Y']),
			'pub_examine'   => route_url('order.my_create', null, ['status[]' => DailianOrder::ORDER_STATUS_EXAMINE]),
			'pub_cancel'    => route_url('order.my_create', null, ['status[]' => DailianOrder::ORDER_STATUS_CANCEL]),
			'sd_publish'    => route('order.index'),
			'sd_ing'        => route_url('order.my', null, ['status[]' => DailianOrder::ORDER_STATUS_ING]),
			'sd_exception'  => route_url('order.my', null, ['status[]' => DailianOrder::ORDER_STATUS_EXCEPTION]),
			'sd_lock'       => route_url('order.my', null, ['order_lock' => 'Y']),
			'sd_examine'    => route_url('order.my', null, ['status[]' => DailianOrder::ORDER_STATUS_EXAMINE]),
			'sd_cancel'     => route_url('order.my', null, ['status[]' => DailianOrder::ORDER_STATUS_CANCEL]),
		];
		return view('front.home.cp', [
			'kfs'       => $kfs,
			'announces' => $announces,
			'num'       => $nums,
			'url'       => $orderUrl,
		]);
	}


	public function getTest() {
		app('l5.sms')->test('15254109156');
	}

	public function postTest() {
		\Log::info(\Input::all());
	}

}
