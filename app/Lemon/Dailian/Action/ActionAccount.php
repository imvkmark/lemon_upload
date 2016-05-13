<?php namespace App\Lemon\Dailian\Action;


use App\Lemon\Repositories\Sour\LmEnv;
use App\Models\AccountFront;
use App\Models\PamAccount;
use App\Models\PamBind;
use App\Models\PamOnline;
use Carbon\Carbon;

class ActionAccount extends ActionBasic {

	
	/**
	 * 注册普通账号
	 * @param       $account_name
	 * @param       $password
	 * @param array $profile
	 * @return bool
	 */
	public function registerFront($account_name, $password, $profile = []) {
		// check
		if (!$this->isAccountName($account_name)) {
			return $this->setError(trans('action.account.account_name_error'));
		}

		// 普通账号不允许存在 :
		if (strpos($account_name, ':') !== false) {
			return $this->setError(trans('action.account.front_account_name_has_colon'));
		}

		if (PamAccount::accountNameExists($account_name)) {
			return $this->setError(trans('action.account.account_name_exists'));
		}

		$role_id = config('lemon.front_role_id');
		// register
		$account_id = PamAccount::register($account_name, $password, PamAccount::ACCOUNT_TYPE_FRONT, $role_id);
		$front      = ['account_id' => $account_id];
		AccountFront::create(array_merge($front, $profile));
		return $account_id;
	}

	/**
	 * 注册子账号
	 * @param       $subuser_name
	 * @param       $owner_id
	 * @param       $password
	 * @param array $profile
	 * @return bool|mixed
	 */
	public function registerSubuser($subuser_name, $owner_id, $password, $profile = []) {
		if (!$this->isAccountName($subuser_name)) {
			return $this->setError(trans('action.account.account_name_error'));
		}

		// 子账号必须存在 :
		if (strpos($subuser_name, ':') === false) {
			return $this->setError(trans('action.account.subuser_hasnot_colon'));
		}

		// 检测父账号是否存在
		if (!PamAccount::getAccountNameByAccountId($owner_id)) {
			return $this->setError('父账号不存在, 无法添加子账号');
		}

		// 检测账号是否存在
		if (PamAccount::accountNameExists($subuser_name)) {
			return $this->setError('账号存在,不能重复添加');
		}
		$role_id = config('lemon.sub_role_id');
		// register
		$account_id = PamAccount::register($subuser_name, $password, PamAccount::ACCOUNT_TYPE_FRONT, $role_id);
		$front      = [
			'account_id' => $account_id,
			'parent_id'  => $owner_id,
		];
		AccountFront::create(array_merge($front, $profile));
		return $account_id;
	}


	/**
	 * 删除子账户
	 * @param $subuser_id
	 * @param $owner_id
	 * @return bool
	 */
	public function deleteSubuser($subuser_id, $owner_id) {
		$ownerId = AccountFront::where('account_id', $subuser_id)->value('parent_id');
		if ($ownerId != $owner_id) {
			return $this->setError('子用户隶属不同, 不得删除');
		}
		\DB::transaction(function () use ($subuser_id, $owner_id) {
			AccountFront::where('account_id', $subuser_id)->where('parent_id', $owner_id)->delete();
			PamAccount::destroy($subuser_id);
		});
		return true;
	}

	/**
	 * 删除离线
	 * @return bool
	 * @throws \Exception
	 */
	public function clearOffine() {
		// delete 所有的的超期
		$timeInterval = Carbon::now()
			->subMinute(config('session.lifetime'))
			->toDateTimeString();
		PamOnline::where('logined_at', '<', $timeInterval)
			->delete();
		return true;
	}


	/**
	 * 更新用户 ip 和 最后登录时间
	 */
	public function online() {
		PamOnline::updateOrCreate([
			'account_id' => $this->pam->account_id
		], [
			'login_ip'   => LmEnv::ip(),
			'logined_at' => Carbon::now(),
		]);
	}
	/**
	 * 绑定社会化组件
	 * @param      $account_id
	 * @param      $field
	 * @param      $key
	 * @param null $head_pic
	 * @return bool
	 */
	public static function bindSocialite($account_id, $field, $key, $head_pic = null) {
		if ($head_pic) {
			/* 拖慢性能. 暂时不处理
			$img         = \Image::make($head_pic);
			$destination = 'uploads/avatar/' . $account_id . '.png';
			$img->save(public_path($destination));
			$head_pic = $destination;
			 */
			AccountFront::where('account_id', $account_id)->update([
				'head_pic' => $head_pic
			]);
		}
		if (PamBind::where('account_id', $account_id)->first()) {
			PamBind::where('account_id', $account_id)->update([$field => $key]);
		} else {
			PamBind::create([
				'account_id' => $account_id,
				$field       => $key,
			]);
		}
		return true;
	}

	/**
	 * 账户名是否合法, 包含:, 允许是中文, 英文, 数字, 下划线, 和英文的 :
	 * @param $account_name
	 * @return int
	 */
	public function isAccountName($account_name) {
		$re = "/^[\\x{4e00}-\\x{9fa5}A-Za-z0-9_:]+$/u";
		return preg_match($re, $account_name);
	}
}