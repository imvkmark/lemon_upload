<?php namespace Imvkmark\L5Thumber\Http;

use App\Http\Controllers\Controller;
use Imvkmark\L5Thumber\Eva\Config\Config;
use Imvkmark\L5Thumber\Eva\Thumber;

/**
 * http://www.larxd.com/thumber/thumber/201604/15/18/0104sbyURn9i.jpg
 * Class ImageController
 * @package Imvkmark\L5Thumber\Http
 */
class ImageController extends Controller {

	public function getIndex() {
		$thumber = new Thumber(app('l5.thumber.config'));

		try {
			$thumber->show();
		} catch (\Exception $e) {
//			throw $e;
			$config  = $thumber->getConfig();
			header('location:' . $config->get('error_url') . '?msg=' . urlencode($e->getMessage()));

		}
	}
}

