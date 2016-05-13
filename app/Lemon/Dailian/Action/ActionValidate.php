<?php namespace App\Lemon\Dailian\Action;

use App\Lemon\Repositories\Sour\LmEnv;
use App\Lemon\Repositories\Sour\LmStr;
use App\Lemon\Repositories\Sour\LmUtil;
use App\Models\AccountFront;
use App\Models\AccountValidate;
use \Carbon\Carbon;
use Illuminate\Mail\Message;
use Imvkmark\L5Sms\Contracts\Sms;

class ActionValidate extends ActionBasic {

	/**
	 * 生成短信认证码
	 * @param $account_id
	 * @param $mobile
	 * @return bool
	 */
	public function smsGen($account_id, $mobile) {
		$randCode = LmStr::random(6, '0123456789');
		$Carbon   = new Carbon();
		// 删除过期
		AccountValidate::where('expired_at', '<', $Carbon->now())->delete();
		AccountValidate::create([
			'valid_type'    => AccountValidate::VALID_TYPE_MOBILE,
			'valid_ip'      => LmEnv::ip(),
			'valid_subject' => $mobile,
			'valid_auth'    => $randCode,
			'account_id'    => $account_id,
			'expired_at'    => $Carbon->addMinutes(10),
		]);
		$content = trans('sms.captcha', [
			'captcha' => $randCode,
		]);
		/** @type Sms $sms */
		$sms = app('l5.sms');
		if (!$sms->send($mobile, $content)) {
			return $this->setError('短信发送不成功, 请联系管理员或者客服人员!');
		}
		return true;
	}


	/**
	 * 生成邮箱验证码并发送, 邮箱验证码默认 30 分钟有效
	 * @param $account_id
	 * @param $email
	 * @return bool
	 * @throws \Exception
	 */
	public function emailGen($account_id, $email) {
		$randCode = LmStr::random(6, '0123456789');
		$Carbon   = new Carbon();

		// 删除过期
		AccountValidate::where('expired_at', '<', $Carbon->now())->delete();

		AccountValidate::create([
			'valid_type'    => AccountValidate::VALID_TYPE_EMAIL,
			'valid_ip'      => LmEnv::ip(),
			'valid_subject' => $email,
			'account_id'    => $account_id,
			'valid_auth'    => $randCode,
			'expired_at'    => $Carbon->addMinutes(30),
		]);

		\Mail::send('dailian.email.validate_code', [
			'code'  => $randCode,
			'email' => $email,
			'date'  => Carbon::now(),
		], function (Message $m) use ($email) {
			$prefix = env('MAIL_SIGN') ? '[' . env('MAIL_SIGN') . ']' : '';
			$m->to($email, $email)->subject($prefix . '邮箱绑定验证码!');
		});
		return true;

	}


	/**
	 * 测试手机验证码是否可用
	 * @param     $subject
	 * @param     $code
	 * @param int $account_id
	 * @return bool
	 */
	public function checkMobileCodeValid($subject, $code, $account_id = 0) {
		return $this->checkCodeValid($subject, $code, AccountValidate::VALID_TYPE_MOBILE, $account_id);
	}


	public function checkEmailCodeValid($subject, $code, $account_id = 0) {
		return $this->checkCodeValid($subject, $code, AccountValidate::VALID_TYPE_EMAIL, $account_id);
	}


	/**
	 * 删除验证代码
	 * @param $subject
	 * @param $code
	 * @return bool|null
	 * @throws \Exception
	 */
	public function deleteValidateCode($subject, $code) {
		return AccountValidate::where('valid_subject', $subject)
			->where('valid_auth', $code)->delete();
	}

	/**
	 * 检查手机是否可用, 已经绑定或者格式是否正确
	 * @param $account_id
	 * @param $mobile
	 * @return bool
	 */
	public function checkMobileValid($account_id, $mobile) {
		if (!LmUtil::isMobile($mobile)) {
			return $this->setError('手机号码格式不正确');
		}
		$Front = AccountFront::where('mobile', $mobile);
		if ($account_id) {
			$Front->where('account_id', '!=', $account_id);
		}
		$isBind = $Front->where('v_mobile', 'Y')->exists();
		if ($isBind) {
			return $this->setError('此手机号码已经被绑定, 请更换手机号码重新绑定!');
		}
		return true;
	}


	/**
	 * 验证邮箱是否可用
	 * @param $account_id
	 * @param $email
	 * @return bool
	 */
	public function checkEmailValid($account_id, $email) {
		if (!LmUtil::isEmail($email)) {
			return $this->setError('邮箱格式不正确!');
		}

		$Front = AccountFront::where('email', $email);
		if ($account_id) {
			$Front->where('account_id', '!=', $account_id);
		}
		$isBind = $Front->where('v_email', 'Y')->exists();

		if ($isBind) {
			return $this->setError('此邮箱已经被其他账号绑定, 请更换邮箱后重新绑定!');
		}
		return true;
	}

	/**
	 * 获取验证内容
	 * @param $account_id
	 * @return array
	 */
	public function getValidations($account_id) {
		/** @type AccountFront $front */
		$front       = AccountFront::find($account_id);
		$validations = [];
		if ($front->v_mobile == 'Y') {
			$validations['mobile'] = $front->mobile;
		}
		if ($front->v_email == 'Y') {
			$validations['email'] = $front->email;
		}
		if ($front->v_question == 'Y') {
			$validations['question'] = [
				'qt1' => $front->question_title_1,
				'qt2' => $front->question_title_2,
				'qt3' => $front->question_title_3,
				'qa1' => $front->question_answer_1,
				'qa2' => $front->question_answer_2,
				'qa3' => $front->question_answer_3,
			];
		}
		return $validations;
	}

	public function getValidateType($account_id) {
		$validations = $this->getValidations($account_id);
		/** @type AccountFront $front */
		$front        = AccountFront::find($account_id);
		$validateType = [];
		if (isset($validations['mobile'])) {
			$validateType['mobile'] = '使用安全手机验证(' . hide_contact($front->mobile) . ')';
		}
		if (isset($validations['email'])) {
			$validateType['email'] = '使用安全邮箱验证(' . hide_email($front->email) . ')';
		}
		if (isset($validations['question'])) {
			$validateType['question'] = '您已经设置密保问题, 使用密保问题验证';
		}
		return $validateType;
	}

	/**
	 * @param        $subject
	 * @param        $code
	 * @param string $type AccountValidate 中定义的类型常量
	 * @param int    $account_id
	 * @return bool
	 */
	protected function checkCodeValid($subject, $code, $type, $account_id = 0) {
		$Validate = AccountValidate::where('expired_at', '>', Carbon::now());
		if ($account_id) {
			$Validate->where('account_id', $account_id);
		}
		return $Validate->where('valid_auth', $code)
			->where('valid_subject', $subject)
			->where('valid_type', $type)
			->exists();
	}
}