<?php namespace Imvkmark\L5Thumber\Http;

use App\Http\Controllers\Controller;
use Imvkmark\L5Thumber\Eva\Thumber;

/**
 * http://www.fake_domain.com/thumber/config/201604/15/18/0104sbyURn9i.jpg
 * Class ImageController
 * @package Imvkmark\L5Thumber\Http
 */
class ImageController extends Controller {

	public function getIndex() {
		$thumber = new Thumber(app('l5.thumber.config'));
		try {
			return $thumber->show();
		} catch (\Exception $e) {
			$config = $thumber->getConfig();
			$url    = $config->get('error_url') . '?msg=' . urlencode($e->getMessage());
			return response()->redirectTo($url);
		}
	}
}

