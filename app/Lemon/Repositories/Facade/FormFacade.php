<?php namespace App\Lemon\Repositories\Facade;

use Illuminate\Support\Facades\Facade;

class FormFacade extends Facade {


	protected static function getFacadeAccessor() {
		return 'lemon.extensions.form';
	}

}