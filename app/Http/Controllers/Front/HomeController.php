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
			]
		]);
	}

	public function getHomepage() {
		return view('front.home.homepage');
	}
}
