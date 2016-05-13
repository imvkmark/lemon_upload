<?php namespace App\Http\Requests\Desktop;

use App\Http\Requests\Request;
use App\Models\PamRole;

class L5RoleRequest extends Request {

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
		$table = (new PamRole())->getTable();
		switch ($this->method()) {
			case 'POST': {
				return [
					'role_name'    => 'required|unique:' . $table,
					'role_title'   => 'required',
					'account_type' => 'required',
				];
			}
			default:
				return [];
				break;
		}
	}


}
