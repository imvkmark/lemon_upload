<?php namespace App\Http\Requests\Front;

use App\Http\Requests\Request;

class FeedbackRequest extends Request {

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
					'feedback_title' => 'required',
					'content' => 'required',
				];
			}
			default:
				break;
		}
	}


}
