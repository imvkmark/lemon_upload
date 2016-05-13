<?php namespace Imvkmark\SlDeploy\Http;

use App\Http\Controllers\Controller;
use Imvkmark\SlDeploy\Jobs\WebDeploy;

class SlDeployController extends Controller {

	public function postCoding() {
		if (\Input::get('token') == config('app.key')) {
			dispatch(new WebDeploy());
			echo 'Send Deploy Request!';
		} else {
			\Log::error('Deploy! But Token Error!');
		}
	}
}
