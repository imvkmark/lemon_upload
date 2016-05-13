<?php namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest {

	protected function formatErrors(Validator $validator) {
		$error    = [];
		$messages = $validator->getMessageBag();
		foreach ($messages->all('<li>:message</li>') as $message) {
			$error[] = $message;
		}
		return $error;
	}

	public function response(array $errors) {
		$error = implode(',', $errors);
		return site_end('error', $error, null, $this->request->all());
	}

}
