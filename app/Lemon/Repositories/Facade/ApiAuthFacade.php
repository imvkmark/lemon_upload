<?php namespace App\Lemon\Repositories\Facade;

use Illuminate\Support\Facades\Facade;

class ApiAuthFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'lemon.auth.api';
	}
}