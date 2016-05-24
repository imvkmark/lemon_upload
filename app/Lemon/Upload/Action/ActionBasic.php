<?php namespace App\Lemon\Upload\Action;

use App\Models\PamAccount;

class ActionBasic {

	protected $error   = '';
	protected $success = '';
	
	/** @type  PamAccount */
	protected $pam;

	public function setError($error) {
		$this->error = $error;
		return false;
	}

	public function getError() {
		return $this->error;
	}

	public function setPam($pam) {
		$this->pam = $pam;
	}
}