<?php namespace App\Models;


/**
 * App\Models\AccountFront
 *
 * @property integer                         $account_id          账户id
 * @property integer                         $parent_id           父账号ID
 * @property string                          $qq                  qq 号码
 * @property string                          $mobile              手机号
 * @property string                          $truename            联系人姓名
 * @property string                          $nickname            昵称
 * @property string                          $address             地址
 * @property string                          $head_pic            头像
 * @property string                          $invite_account_id   邀请人账号
 * @property string                          $invite_code         我的邀请码
 * @property float                           $money               资金
 * @property float                           $lock                锁定资金
 * @property string                          $email               邮箱
 * @property string                          $chid                身份证
 * @property string                          $chid_pic            身份证扫描件
 * @property string                          $area_name           所在地区
 * @property string                          $ali                 旺旺
 * @property string                          $order_prefix        订单默认前缀
 * @property string                          $v_mobile            验证手机真实性
 * @property string                          $v_question          是否设置密保问题
 * @property string                          $v_truename          验证身份证真实性
 * @property string                          $v_email             验证邮箱真实性
 * @property string                          $v_code              生成的验证码 6位
 * @property string                          $v_type              验证类型
 * @property string                          $v_valid_time        验证到期有效期
 * @property string                          $question_title_1    密保问题
 * @property string                          $question_title_2
 * @property string                          $question_title_3
 * @property string                          $question_answer_1   密保答案
 * @property string                          $question_answer_2
 * @property string                          $question_answer_3
 * @property string                          $signature           个性签名
 * @property string                          $permission          用户权限控制
 * @property string                          $payword             支付密码
 * @property string                          $payword_key         支付密码 key
 * @property integer                         $pub_publish_all_num 所有创建订单的数量
 * @property integer                         $pub_create_num      订单创建但是尚未付款数量
 * @property integer                         $pub_publish_num     订单发布完成, 等待接收数量
 * @property integer                         $pub_ing_num         订单进行中数量
 * @property integer                         $pub_lock_num        锁定数量
 * @property integer                         $pub_examine_num     等待验收
 * @property integer                         $pub_exception_num   异常
 * @property integer                         $pub_over_num        订单完成数量
 * @property integer                         $pub_cancel_all_num  撤单数量
 * @property integer                         $pub_cancel_ing_num  进行中的撤单数量
 * @property integer                         $pub_cancel_kf_num   客服介入撤单数量
 * @property integer                         $pub_cancel_deal_num 发布和解撤单总数量
 * @property integer                         $pub_star_good       发单评价 好评
 * @property integer                         $pub_star_normal     发单评价中评
 * @property integer                         $pub_star_bad        发单评价差评
 * @property integer                         $sd_assign_all_num   接单总数量(打手)
 * @property integer                         $sd_ing_num          代练进行中数量(打手)
 * @property integer                         $sd_examine_num      等待验收数量(打手)
 * @property integer                         $sd_exception_num    异常数量(打手)
 * @property integer                         $sd_lock_num         锁定数量(打手)
 * @property integer                         $sd_over_num         完成数量(打手)
 * @property integer                         $sd_cancel_all_num   退单数量(打手)
 * @property integer                         $sd_cancel_ing_num   进行中的退单数量(打手)
 * @property integer                         $sd_cancel_kf_num    客服介入退单数量(打手)
 * @property integer                         $sd_cancel_deal_num  所有退单完成数量
 * @property integer                         $sub_lock_num        锁定数量
 * @property integer                         $sub_unlock_num      解锁数量
 * @property integer                         $sub_over_num        完成数量
 * @property integer                         $sub_cancel_num      取消数量
 * @property integer                         $sd_star_good        代练评价(好评)
 * @property integer                         $sd_star_bad         代练评价(差评)
 * @property integer                         $sd_star_normal      代练评价(中评)
 * @property-read \App\Models\PamAccount     $pam
 * @property-read \App\Models\PamRoleAccount $role
 * @property string $truename_status 真实姓名认证状态
 * @property string $truename_note 真实姓名验证原因
 */
class AccountFront extends \Eloquent {

	const V_TYPE_MOBILE   = 'mobile';
	const V_TYPE_TRUENAME = 'truename';
	const V_TYPE_EMAIL    = 'email';

	const TRUENAME_STATUS_NONE   = 'none';
	const TRUENAME_STATUS_WAIT   = 'wait';
	const TRUENAME_STATUS_PASSED = 'passed';
	const TRUENAME_STATUS_FAILED = 'failed';

	protected $table = 'account_front';

	protected $primaryKey = 'account_id';

	public $timestamps = false;

	protected $fillable = [
		'account_id',
		'parent_id',
		'permission',
		'v_mobile',
		'truename',
		'qq',
		'address',
		'mobile',
		'invite_account_id',
	];

	protected static $validateStatusDesc = [
		'Y' => '是',
		'N' => '否',
	];

	protected static $truenameStatusDesc = [
		self::TRUENAME_STATUS_NONE   => '未提交认证资料',
		self::TRUENAME_STATUS_WAIT   => '已提交, 等待审核',
		self::TRUENAME_STATUS_PASSED => '审核通过',
		self::TRUENAME_STATUS_FAILED => '审核失败',
	];

	protected static $subuserPermissionDesc = [
		// 进程操作权限
		'order.create'              => '发布订单',
		'order.quash'               => '撤销订单',
		'order.add_money'           => '补款',
		'order.add_time'            => '补时',
		'order.lock'                => '锁定账号',
		'order.unlock'              => '解除锁定',
		'order.pub_cancel'          => '申请协商撤销',
		'order.cancel_pub_cancel'   => '取消协商撤销',
		'order.handle_sd_cancel'    => '同意协商撤销',
		'order.kf'                  => '申请客服介入',
		'order.over'                => '确认完单',
		'order.start'               => '启动游戏',
		'order.edit'                => '编辑订单',
		'order.talk'                => '订单留言',
		'order.update_progress'     => '上传进度图',
		'order.change_game_account' => '修改游戏密码',

		// 非进程权限
		'order._show_pwd'           => '显示游戏密码',
		'order._text'               => '订单文本',
	];


	public function pam() {
		return $this->belongsTo('App\Models\PamAccount', 'account_id', 'account_id');
	}

	public function role() {
		return $this->hasOne('App\Models\PamRoleAccount', 'account_id', 'account_id');
	}


	/**
	 * 资金余额
	 * @param $account_id
	 * @return float
	 */
	public static function money($account_id) {
		return (float) self::where('account_id', $account_id)->value('money');
	}


	/**
	 * 检测子账号的所有者
	 * @param $subuser_id
	 * @param $owner_id
	 * @return bool
	 */
	public static function checkSubuserOwner($subuser_id, $owner_id) {
		$owner = AccountFront::where('account_id', $subuser_id)->value('parent_id');
		return $owner == $owner_id;
	}

	/**
	 * 锁定资金的数量
	 * @param $account_id
	 * @return float
	 */
	public static function locking($account_id) {
		return (float) AccountFront::where('account_id', $account_id)->value('lock');
	}


	/**
	 * 根据用户 id 检测支付密码是否正确
	 * @param $account_id
	 * @param $payword
	 * @return bool
	 */
	public static function checkPayword($account_id, $payword) {
		$account = PamAccount::info($account_id, true);
		$regUnix = strtotime($account['created_at']);
		return $account['front']['payword'] == self::genPayword($payword, $regUnix, $account['front']['payword_key']);
	}

	/**
	 * 检测子账号
	 * @param $account_name
	 * @param $main_account
	 * @return bool
	 */
	public static function checkSubuser($account_name, $main_account) {
		if (strpos($account_name, ':') != false) {
			return false;
		}

		$fullSubAccount = $main_account . ':' . $account_name;
		// 检测账号是否存在
		if (PamAccount::accountNameExists($fullSubAccount)) {
			return false;
		}
		return true;
	}

	/**
	 * 生成账户密码
	 * @param String $ori_payword 原始密码
	 * @param String $reg_unix    注册时间 unix 时间戳
	 * @param String $random_key  六位随机值
	 * @return string
	 */
	public static function genPayword($ori_payword, $reg_unix, $random_key) {
		return md5(sha1($ori_payword . $reg_unix) . $random_key);
	}

	/**
	 * 更改支付密码
	 * @param        $account_id
	 * @param string $newPayword 新支付密码
	 * @return bool
	 */
	public static function changePayword($account_id, $newPayword) {
		$pam          = PamAccount::info($account_id);
		$key          = str_random(6);
		$regTime      = strtotime($pam['created_at']);
		$cryptPayword = self::genPayword($newPayword, $regTime, $key);
		AccountFront::where('account_id', $account_id)->update([
			'payword'     => $cryptPayword,
			'payword_key' => $key,
		]);
		return true;
	}

	/**
	 * 子用户权限
	 * @return array
	 */
	public static function subuserPermissionLinear() {
		return self::$subuserPermissionDesc;
	}

	/**
	 * 子账号操作权限说明
	 * @param        $permission
	 * @param string $clue
	 * @return string
	 */
	public static function subuserPermissionDesc($permission, $clue = ',') {
		$arrPerKey     = explode(',', $permission);
		$arrPermission = [];
		foreach ($arrPerKey as $key) {
			if (isset(self::$subuserPermissionDesc[$key])) {
				$arrPermission[] = self::$subuserPermissionDesc[$key];
			}
		}
		return $arrPermission ? implode($clue, $arrPermission) : '';
	}

	/**
	 * 返回账号所有者ID
	 * @param $account_id
	 * @return mixed
	 */
	public static function ownerId($account_id) {
		$front = AccountFront::findOrFail($account_id);
		if ($front->parent_id) {
			$ownerId = $front->parent_id;
		} else {
			$ownerId = $front->account_id;
		}
		return $ownerId;
	}

	/**
	 * 子用户权限列表
	 * @param $subuser_id
	 * @return array
	 */
	public static function subuserPermissions($subuser_id) {
		$permission = AccountFront::where('account_id', $subuser_id)->value('permission');
		if ($permission) {
			return explode(',', $permission);
		} else {
			return [];
		}
	}

	public static function truenameStatusLinear() {
		return self::$truenameStatusDesc;
	}

	public static function truenameStatusDesc($key) {
		return isset(self::$truenameStatusDesc[$key]) ? self::$truenameStatusDesc[$key] : '';
	}


	/**
	 * 检测邮件code 是否可用
	 * @param $account_id
	 * @param $code
	 * @return bool|null
	 */
	public static function checkEmailCodeValid($account_id, $code) {
		return AccountFront::where('account_id', $account_id)
			->where('v_code', $code)
			->where('v_valid_time', '>', \Carbon\Carbon::now())
			->where('v_type', self::V_TYPE_EMAIL)
			->exists();
	}

	/**
	 * 检查手机代码是否可用
	 * @param $account_id
	 * @param $code
	 * @return bool|null
	 */
	public static function checkMobileCodeValid($account_id, $code) {
		return AccountFront::where('account_id', $account_id)
			->where('v_code', $code)
			->where('v_valid_time', '>', \Carbon\Carbon::now())
			->where('v_type', self::V_TYPE_MOBILE)
			->exists();
	}

	public static function validateStatusDesc($key) {
		return isset(self::$validateStatusDesc[$key]) ? self::$validateStatusDesc[$key] : '';
	}

	public static function validateStatusLinear() {
		return self::$validateStatusDesc;
	}

}
