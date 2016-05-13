<?php namespace App\Providers;

use App\Lemon\Repositories\Application\FormBuilder;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider {

	public function register() {

		$this->registerFormBuilder();

		$this->app->alias('lemon.extensions.form', 'App\Lemon\Extensions\FormBuilder');
	}


	public function registerFormBuilder() {
		$this->app->bindShared('lemon.extensions.form', function ($app) {
			$form = new FormBuilder($app['html'], $app['url'], $app['session.store']->getToken());
			return $form->setSessionStore($app['session.store']);
		});
	}


}