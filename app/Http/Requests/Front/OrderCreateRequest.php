<?php namespace App\Http\Requests\Front;

use App\Http\Requests\Request;

/**
 * Class OrderCreateRequest
 * @package App\Http\Requests\Site
 */
class OrderCreateRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 * @return array
	 */
	public function rules() {

		switch ($this->method()) {
			case 'GET':
			case 'DELETE': {
				return [];
			}
			case 'POST': {
				return [
					'payword' => [
						'required'
					],
				];
			}
			default:
				break;
		}
	}


}
