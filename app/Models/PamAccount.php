<?php namespace App\Models;

use App\Lemon\Repositories\Application\Rbac\Traits\RbacUserTrait;
use App\Lemon\Repositories\Sour\LmEnv;
use Illuminate\Auth\Authenticatable as TraitAuthenticatable;
use Illuminate\Contracts\Auth\Authenticatable;


/**
 * App\Models\PamAccount
 * @property integer                         $account_id
 * @property string                          $account_name 账号名称， 支持中文
 * @property string                          $account_pwd  账号密码
 * @property string                          $account_key  账号注册时候随机生成的6位key
 * @property string                          $account_type 账户类型
 * @property integer                         $login_times  登陆次数
 * @property string                          $reg_ip       注册IP
 * @property string                          $is_enable
 * @property \Carbon\Carbon                  $created_at
 * @property string                          $deleted_at
 * @property string                          $logined_at   上次登录时间
 * @property \Carbon\Carbon                  $updated_at
 * @property string                          $remember_token
 * @property-read \App\Models\PamRoleAccount $role
 * @property-read \App\Models\AccountDesktop $desktop
 * @property-read \App\Models\AccountFront   $front
 */
class PamAccount extends \Eloquent implements Authenticatable {

	use TraitAuthenticatable, RbacUserTrait;


	const ACCOUNT_TYPE_DESKTOP = 'desktop';
	const ACCOUNT_TYPE_FRONT   = 'front';
	const ACCOUNT_TYPE_DEVELOP = 'develop';
	const DESKTOP_SYSTEM       = 'system';  // 后台类型
	const FRONT_SUBUSER        = 'subuser'; // 子账号

	protected $table = 'pam_account';

	protected $primaryKey = 'account_id';

	protected $fillable = [
		'account_name',
		'account_pwd',
		'account_key',
		'account_type',
		'reg_ip',
	];

	protected static $userTypeDesc = [
		self::ACCOUNT_TYPE_DESKTOP => [
			'name' => '管理员',
			'type' => 'desktop',
			'desc' => '管理员',
		],
		self::ACCOUNT_TYPE_DEVELOP => [
			'name' => '开发者账号',
			'type' => 'develop',
			'desc' => '开发者账号',
		],
		self::ACCOUNT_TYPE_FRONT   => [
			'name' => '用户',
			'type' => 'front',
			'desc' => '前台用户',
		],
		self::DESKTOP_SYSTEM       => [
			'name' => '系统',
			'type' => 'desktop',
			'desc' => '系统后台主动操控',
		],
		self::FRONT_SUBUSER        => [
			'name' => '子账号',
			'type' => 'front',
			'desc' => '子账号操作',
		],
	];

	public function role() {
		return $this->hasOne('App\Models\PamRoleAccount', 'account_id');
	}

	public function desktop() {
		return $this->hasOne('App\Models\AccountDesktop', 'account_id');
	}

	public function front() {
		return $this->hasOne('App\Models\AccountFront', 'account_id', 'account_id');
	}

	/**
	 * Get the password for the user.
	 * @return string
	 */
	public function getAuthPassword() {
		return $this->account_pwd;
	}


	/**
	 * 根据账户名称/类型获取账户ID
	 * @param $accountName
	 * @param $accountType
	 * @return mixed
	 */
	public static function getAccountIdByAccountName($account_name) {
		return self::where('account_name', $account_name)->value('account_id');
	}


	public static function userType($account_id) {
		if (!$account_id) {
			return self::DESKTOP_SYSTEM;
		}
		$accountType = self::getAccountTypeByAccountId($account_id);
		if ($accountType == self::ACCOUNT_TYPE_FRONT) {
			$parentId = AccountFront::where('account_id', $account_id)->value('parent_id');
			if ($parentId) { // 子账号
				$accountType = self::FRONT_SUBUSER;
			}
		}
		return $accountType;
	}


	/**
	 * 用户所有类型
	 * @return array
	 */
	public static function userTypeLinear() {
		$linear = [];
		foreach (self::$userTypeDesc as $key => $val) {
			$linear[$key] = $val['name'];
		}
		return $linear;
	}

	/**
	 * 用户账户类型描述
	 * @param $key
	 * @return string
	 */
	public static function userTypeDesc($key) {
		static $cache;
		if (!$cache) {
			$cache = self::userTypeLinear();
		}
		return isset($cache[$key]) ? $cache[$key] : '';
	}

	/**
	 * 获取所有账户类型
	 * @return array
	 */
	public static function accountTypeAll() {
		$accountTypeDesc = [];
		$keys            = [
			self::ACCOUNT_TYPE_FRONT,
			self::ACCOUNT_TYPE_DESKTOP,
		];

		// 启用系统账号管理
		if (config('lemon.enable_develop')) {
			$keys[] = self::ACCOUNT_TYPE_DEVELOP;
		}
		foreach (self::$userTypeDesc as $key => $val) {
			if (in_array($key, $keys)) {
				$accountTypeDesc[$key] = $val;
			}
		}
		return $accountTypeDesc;
	}


	/**
	 * 检查用户名是否存在
	 * @param $accountName
	 * @return mixed
	 */
	public static function accountNameExists($accountName) {
		return PamAccount::where('account_name', $accountName)->value('account_id');
	}

	/**
	 * 允许缓存, 获取账户类型, 因为账户类型不会变化
	 * @param $account_id
	 * @return mixed
	 */
	public static function getAccountTypeByAccountId($account_id) {
		static $accountType;
		if (!isset($accountType[$account_id])) {
			$accountType[$account_id] = PamAccount::where('account_id', $account_id)->value('account_type');
		}
		return $accountType[$account_id];
	}

	/**
	 * 账户类型
	 * @return array
	 */
	public static function accountTypeLinear() {
		$linear       = [];
		$accountTypes = self::accountTypeAll();
		foreach ($accountTypes as $key => $val) {
			$linear[$key] = $val['name'];
		}
		return $linear;
	}

	/**
	 * 账户类型描述
	 * @param $key
	 * @return string
	 */
	public static function accountTypeDesc($key) {
		$linear = self::accountTypeLinear();
		return isset($linear[$key]) ? $linear[$key] : '';
	}

	/**
	 * 根据账户名称/类型获取账户ID
	 * @param $accountName
	 * @param $accountType
	 * @return mixed
	 */
	public static function getAccountNameByAccountId($account_id) {
		return PamAccount::where('account_id', $account_id)->value('account_name');
	}


	/**
	 * 检测账户密码是否正确
	 * @param PamAccount $pam      用户账户信息
	 * @param String     $password 用户传入的密码
	 * @return bool
	 */
	public static function checkPassword($pam, $password) {
		$accountKey   = $pam->account_key;
		$regTime      = strtotime($pam->created_at);
		$authPassword = $pam->getAuthPassword();
		$gendPassword = self::genPassword($password, $regTime, $accountKey);
		return (bool) ($authPassword === $gendPassword);
	}

	/**
	 * 生成账户密码
	 * @param String $password  原始密码
	 * @param String $regTime   注册时间/ unix 时间戳
	 * @param String $randomKey 六位随机值
	 * @return string
	 */
	public static function genPassword($password, $regTime, $randomKey) {
		return md5(sha1($password . $regTime) . $randomKey);
	}

	/**
	 * 创建用户账户
	 * @param $accountName
	 * @param $password
	 * @param $accountType
	 * @param $roleId
	 * @return bool|mixed
	 */
	public static function register($accountName, $password, $accountType, $roleId) {
		$key           = str_random(6);
		$PamAccount    = PamAccount::create([
			'account_name' => $accountName,
			'account_key'  => $key,
			'account_type' => $accountType,
			'reg_ip'       => LmEnv::ip(),
		]);
		$createdAtUnix = strtotime($PamAccount->created_at);
		$gendPwd       = self::genPassword($password, $createdAtUnix, $key);

		PamAccount::where('account_id', $PamAccount->account_id)->update([
			'account_pwd' => $gendPwd,
		]);
		if (!$PamAccount->account_id) {
			return false;
		} else {
			PamRoleAccount::create([
				'role_id'    => $roleId,
				'account_id' => $PamAccount->account_id,
			]);
			return $PamAccount->account_id;
		}
	}

	/**
	 * 更改密码
	 * @param int    $account_id
	 * @param String $newPassword
	 */
	public static function changePassword($account_id, $newPassword) {
		/** @type PamAccount $user */
		$user              = PamAccount::find($account_id);
		$key               = str_random(6);
		$regTime           = strtotime($user->created_at);
		$gendPassword      = PamAccount::genPassword($newPassword, $regTime, $key);
		$user->account_pwd = $gendPassword;
		$user->account_key = $key;
		$user->save();
	}

	/**
	 * 根据账户ID 来获取账户的信息
	 * @param      $account_id
	 * @param bool $profile
	 * @return mixed
	 */
	public static function info($account_id, $profile = false) {

		$account = PamAccount::find($account_id)->toArray();
		if ($profile) {
			$account_type = $account['account_type'];
			if ($account_type == PamAccount::ACCOUNT_TYPE_DESKTOP) {
				$detail                                    = AccountDesktop::findOrFail($account_id)->toArray();
				$account[PamAccount::ACCOUNT_TYPE_DESKTOP] = $detail;
			}
			if ($account_type == PamAccount::ACCOUNT_TYPE_FRONT) {
				$detail                                  = AccountFront::findOrFail($account_id)->toArray();
				$account[PamAccount::ACCOUNT_TYPE_FRONT] = $detail;
			}
			if ($account_type == PamAccount::ACCOUNT_TYPE_DEVELOP) {
				$detail                                    = AccountDevelop::findOrFail($account_id)->toArray();
				$account[PamAccount::ACCOUNT_TYPE_DEVELOP] = $detail;
			}
			$account['role_id'] = PamRoleAccount::getRoleIdByAccountId($account_id);
			return $account;
		}
		return $account;
	}

	/**
	 * 通过账户名称获取信息, 没有返回 null
	 * @param $account_name
	 * @return PamAccount
	 */
	public static function getByAccountName($account_name) {
		return PamAccount::where('account_name', $account_name)->first();
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
				'head_pic' => $head_pic,
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

}
