<?php namespace App\Http\Controllers\Support;


use App\Lemon\Dailian\Action\ActionValidate;
use App\Lemon\Repositories\Sour\LmUtil;
use Illuminate\Http\Request;

class UtilController extends InitController {

	/** @type  ActionValidate */
	protected $Validate;

	public function __construct(Request $request) {
		parent::__construct($request);
		$this->Validate = new ActionValidate();
	}

	/**
	 * 发送邮箱验证码
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postSendEmailCode(Request $request) {
		$email      = $request->input('email');
		$account_id = $request->input('account_id');

		if (!$this->Validate->checkEmailValid($account_id, $email)) {
			return site_end('error', $this->Validate->getError(), 'forget|1');
		}

		if (!$this->Validate->emailGen($account_id, $email)) {
			return site_end('error', $this->Validate->getError(), 'forget|1');
		}

		return site_end('success', '邮件发送成功, 请查收邮件!', ['json' => true]);
	}

	/**
	 * 发送手机验证码
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postSendMobileCode(Request $request) {
		$mobile     = LmUtil::getMobile($request->input('mobile'));
		$account_id = intval($request->input('account_id'));
		if (!$this->Validate->checkMobileValid($account_id, $mobile)) {
			return site_end('error', $this->Validate->getError(), 'forget|1');
		}

		if (!$this->Validate->smsGen($account_id, $mobile)) {
			return site_end('error', $this->Validate->getError(), 'forget|1');
		}

		return site_end('success', '手机验证码发送成功', ['json' => true, 'forget' => true]);
	}


	public function postCheckMobileCodeValidate(Request $request) {
		$mobile_code = $request->input('mobile_captcha');
		$account_id  = $request->input('account_id');
		$subject     = $request->input('mobile');
		if ($this->Validate->checkMobileCodeValid($subject, $mobile_code, $account_id)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}

	public function postCheckEmailCodeValidate(Request $request) {
		$email_code = $request->input('email_captcha');
		$account_id = $request->input('account_id');
		$subject    = $request->input('email');
		if ($this->Validate->checkEmailCodeValid($subject, $email_code, $account_id)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}

}
