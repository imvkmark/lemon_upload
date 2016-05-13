<?php namespace App\Http\Controllers;

use App\Lemon\Repositories\Sour\LmEnv;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController {

	use DispatchesJobs, ValidatesRequests;

	protected $pageNum = 15;
	protected $ip;
	protected $time;
	protected $route;
	protected $datetime;

	public function __construct() {
		$this->route = \Route::currentRouteName();
		\View::share([
			'_site'  => site(),
			'_route' => $this->route,
		]);

		// pagesize
		$this->pageNum = config('lemon.page_num', 15);
		if (\Input::get('pagesize')) {
			$pagesize = abs(intval(\Input::get('pagesize')));
			$pagesize = $pagesize < 501 ? $pagesize : 500;
			if ($pagesize > 0) {
				$this->pageNum = $pagesize;
			}
		}

		$this->ip       = LmEnv::ip();
		$this->time     = LmEnv::time();
		$this->datetime = Carbon::now();

		\View::share([
			'_ip'       => $this->ip,
			'_time'     => $this->time,
			'_datetime' => Carbon::now(),
			'_pagesize' => $this->pageNum,
		]);
	}

}
