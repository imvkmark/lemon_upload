<?php namespace App\Lemon\Dailian\Action;

use App\Lemon\Repositories\Sour\LmUtil;
use App\Models\AccountFront;
use App\Models\BaseAttachment;
use App\Models\FinanceLock;
use App\Models\FinanceMoney;
use App\Models\DailianLog;
use App\Models\DailianOrder;
use App\Models\DailianOrderStar;
use App\Models\DailianPicture;
use App\Models\PamAccount;
use Carbon\Carbon;

class ActionDailianOrder extends ActionBasic {

	/**
	 * 创建并发布订单
	 * @param $input
	 * @param $publisher_id
	 * @return bool
	 */
	public function create($input, $publisher_id) {
		$ownerId               = AccountFront::ownerId($publisher_id);
		$owner                 = PamAccount::info($ownerId, true);
		$input['order_no']     = DailianOrder::genNumber();
		$input['account_id']   = $owner['account_id'];
		$input['publisher_id'] = $publisher_id;
		$input['account_name'] = $owner['account_name'];

		if (bccomp($input['order_price'], $owner['front']['money']) > 0) {
			return $this->setError('您的账户余额不足, 请先进行充值');
		}

		\DB::transaction(function () use ($input, $owner, $publisher_id) {
			// for pay
			$input['order_status'] = DailianOrder::ORDER_STATUS_PUBLISH;

			// create
			$order = DailianOrder::create($input);

			// progress log
			$log = '[订单创建] 订单号: ' . $order->order_no;
			DailianLog::record($order->order_id, $publisher_id, DailianLog::LOG_TYPE_PUBLISH, $log);

			// 冻结资金
			$Money = new ActionMoney();
			$Money->lock($owner['account_id'], $input['order_price'], FinanceMoney::MONEY_TYPE_LOCK, $order->order_no, $log);

			/* not save file [(Mark Zhao) 2015/10/11]
			if ($input['secret_key1']) {
				$key1 = 'user_' . $pam['account_id'] . '_order_new_secret1';
				SysAttach::saveFile($key1, $input['secret_key1'], 'order_' . $order->order_id . '_secret1');
			}
			----------- */

		});
		return true;
	}

	/**
	 * 编辑订单
	 * @param     $order_id
	 * @param     $input
	 * @param int $editor_id
	 * @return bool
	 */
	public function edit($order_id, $input, $editor_id = 0) {
		$order = DailianOrder::info($order_id);

		$account_id = $order->account_id;
		if (!$editor_id) {
			$editor_id = $account_id;
		}
		$pam = PamAccount::info($account_id, true);

		$diffPrice = 0;
		if ($input['order_price'] != $order->order_price) { // price 改动
			$diffPrice = $input['order_price'] - $order->order_price;
			if ($diffPrice > 0) {  // 需要计算差价金额是否够
				if (bccomp($diffPrice, $pam['front']['money']) > 0) {
					return $this->setError('您的账户余额不足, 请先进行充值');
				}
			}
		}


		\DB::transaction(function () use ($input, $pam, $order, $diffPrice, $editor_id) {

			// price
			if ($diffPrice != 0) {
				$log   = '[修改订单] 订单号: ' . $order->order_no . ', 原金额 : ' . $order->order_price . ', 现金额: ' . LmUtil::formatDecimal($input['order_price']);
				$Money = new ActionMoney();
				if ($diffPrice > 0) {
					$log .= ' 补款: ' . LmUtil::formatDecimal($diffPrice);
					// 操作主张好
					$Money->lock($order->account_id, $diffPrice, FinanceMoney::MONEY_TYPE_LOCK, $order->order_no, $log);
				} else {
					$log .= ' 退款: ' . LmUtil::formatDecimal(abs($diffPrice));
					$Money->unlock($order->account_id, abs($diffPrice), FinanceMoney::MONEY_TYPE_UNLOCK, $order->order_no, $log);
				}
			}

			DailianOrder::where('order_id', $order->order_id)->update($input);

			// progress log
			$log = '[订单编辑] 订单号: ' . $order->order_no;
			DailianLog::record($order->order_id, $editor_id, DailianLog::LOG_TYPE_PUBLISH, $log);

			//save picture
			if (isset($input['secret_key1']) && $input['secret_key1'] != $order->secret_key1) {
				BaseAttachment::saveFile('order_' . $order->order_id . '_secret1', $input['secret_key1']);
			}

		});
		return true;
	}


	/**
	 * 接单
	 * @param     $order_id
	 * @param     $account_id
	 * @param int $editor_id
	 * @return bool
	 */
	public function handle($order_id, $account_id, $editor_id = 0) {
		$order = DailianOrder::info($order_id);
		$pam   = PamAccount::info($account_id);
		// money
		$frozen_money = bcadd($order->safe_money, $order->speed_money);
		if ($frozen_money > AccountFront::money($account_id)) {
			return $this->setError('您的余额不足, 无法接单!');
		}

		if ($order->order_status != DailianOrder::ORDER_STATUS_PUBLISH) {
			return $this->setError('订单状态已经改变, 不得重复提交!');
		}

		\DB::transaction(function () use ($order, $pam, $editor_id) {

			if ($editor_id) {
				$editorAccountName = PamAccount::getAccountNameByAccountId($editor_id);
				$msg               = '[分配订单] 管理员 ' . $editorAccountName . ' 将订单 (订单号: ' . $order->order_no . ') 分配给用户 ' . $pam['account_name'];
				$doer_id           = $editor_id;
			} else {
				$msg     = '[接单] 订单号: ' . $order->order_no;
				$doer_id = $pam['account_id'];
			}

			if ($order->safe_money) {
				$msg .= ', 冻结安全保证金: ' . $order->safe_money;
			}
			if ($order->speed_money) {
				$msg .= ', 冻结效率保证金: ' . $order->speed_money;
			}
			if ($order->speed_money || $order->safe_money) {
				$frozen_money = LmUtil::formatDecimal(bcadd($order->safe_money, $order->speed_money));
				$msg .= ', 总计冻结: ' . $frozen_money;
			}
			// 冻结资金
			$frozen_money = bcadd($order->safe_money, $order->speed_money);
			$Money        = new ActionMoney();
			$Money->lock($pam['account_id'], $frozen_money, FinanceMoney::MONEY_TYPE_LOCK, $order->order_no, $msg);

			// 游戏日志
			DailianLog::record($order->order_id, $doer_id, DailianLog::LOG_TYPE_ASSIGN, $msg);

			// 订单状态, 接单时间
			$ended_at  = Carbon::now()->addHours($order->order_hours)->toDateTimeString();
			$orderInfo = [
				'order_status'    => DailianOrder::ORDER_STATUS_ING,
				'sd_account_id'   => $pam['account_id'],
				'sd_account_name' => $pam['account_name'],
				'assigned_at'     => Carbon::now(),
				'last_log'        => $msg,
				'ended_at'        => $ended_at,
			];
			DailianOrder::where('order_id', $order->order_id)
				->update($orderInfo);

		});
		return true;
	}

	/**
	 * 上传进度图
	 * @param        $order_id
	 * @param        $log
	 * @param        $pic_key
	 * @param string $type
	 * @param        $account_id
	 * @return bool
	 */
	public function upload($order_id, $log, $pic_key, $type = DailianPicture::PICTURE_TYPE_SOLDIER_PROGRESS, $account_id) {
		if ($pic_key) {
			$old_source = 'order_' . $order_id . '_pic';
			$picNum     = DailianPicture::where('order_id', $order_id)->count();
			$new_source = $old_source . '_' . ($picNum + 1);
			BaseAttachment::saveFile($old_source, $pic_key, $new_source);
		}
		$accountType = PamAccount::userType($account_id);
		DailianPicture::create([
			'pic_desc'     => '[进度图] ' . $log,
			'order_id'     => $order_id,
			'pic_screen'   => $pic_key,
			'pic_type'     => $type,
			'account_id'   => $account_id,
			'account_type' => $accountType,
		]);
		return true;
	}

	/**
	 * 补时
	 * @param $order_id
	 * @param $hour
	 * @param $editor_id
	 * @return bool
	 */
	public function addTime($order_id, $hour, $editor_id) {
		\DB::transaction(function () use ($hour, $order_id, $editor_id) {
			$order = DailianOrder::info($order_id);
			$log   = '[代练时间变动] 补时' . $hour . '小时!';

			// log
			DailianLog::record($order_id, $editor_id, DailianLog::LOG_TYPE_ADD_TIME, $log);

			// update ended time
			$ended_at = Carbon::createFromFormat('Y-m-d H:i:s', $order->assigned_at)
				->addHours($order->order_hours + $hour)
				->toDateTimeString();
			$order->order_add_hour += $hour;
			$order->order_hours += $hour;
			$order->ended_at = $ended_at;
			$order->save();
		});
		return true;
	}


	/**
	 * 补款
	 * @param int   $order_id  订单id
	 * @param float $amount    补款金额
	 * @param int   $editor_id 操作员ID
	 * @return bool
	 */
	public function addMoney($order_id, $amount, $editor_id) {
		\DB::transaction(function () use ($order_id, $amount, $editor_id) {
			$order    = DailianOrder::info($order_id);
			$nowPrice = bcadd($order->order_price, $amount);
			$log      = ' 原金额:' . $order->order_price
				. ', 增加: ' . LmUtil::formatDecimal($amount)
				. ', 现金额: ' . LmUtil::formatDecimal($nowPrice) . '!';

			// money
			$Money = new ActionMoney();
			$Money->lock($order->account_id, $amount, FinanceMoney::MONEY_TYPE_LOCK, $order->order_no, '补款' . $log);

			// log
			DailianLog::record($order_id, $editor_id, DailianLog::LOG_TYPE_ADD_MONEY, '[补款]' . $log);

			// order price
			$order->order_price += $amount;
			$order->save();
		});
		return true;
	}

	/**
	 * 申报异常
	 * @param $order_id
	 * @param $message
	 * @param $exception_type
	 * @param $account_id
	 * @return bool
	 */
	public function exception($order_id, $message, $exception_type, $account_id) {
		// 记录异常, 记录日志
		\DB::transaction(function () use ($order_id, $message, $exception_type, $account_id) {
			// log
			$exceptionDesc = DailianOrder::exceptionTypeDesc($exception_type);
			$log           = "[提交异常] 异常类型: {$exceptionDesc}, 说明:" . $message;
			DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_EXCEPTION, $log);

			// update order
			DailianOrder::where('order_id', $order_id)->update([
				'order_status'   => DailianOrder::ORDER_STATUS_EXCEPTION,
				'exception_type' => $exception_type,
				'excepted_at'    => Carbon::now(),
				'is_exception'   => 'Y',
			]);

		});
		return true;
	}

	/**
	 * 取消异常
	 * @param $order_id
	 * @param $editor_id
	 * @return bool
	 */
	public function cancelException($order_id, $editor_id) {
		DailianOrder::where('order_id', $order_id)->update([
			'exception_cancelled_at' => Carbon::now(),
			'is_exception'           => 'N',
			'is_exception_handled'   => 'N',
			'order_status'           => DailianOrder::ORDER_STATUS_ING,
		]);
		DailianLog::record($order_id, $editor_id, DailianLog::LOG_TYPE_CANCEL_EXCEPTION, '[取消异常]');

		return true;
	}


	/**
	 * 留言, 不开放管理员评价
	 * @param $order_id
	 * @param $message
	 * @param $editor_id
	 * @return bool
	 */
	public function talk($order_id, $message, $editor_id) {
		$order = DailianOrder::info($order_id);
		$data  = [];
		if (is_pub($order, $editor_id)) {
			$data['msg_pub_talk'] = 'Y';
		}
		if (is_sd($order, $editor_id)) {
			$data['msg_sd_talk'] = 'Y';
		}
		if ($data) {
			DailianOrder::where('order_id', $order_id)->update($data);
		}

		DailianLog::record($order_id, $editor_id, DailianLog::LOG_TYPE_PROGRESS, $message);
		return true;
	}

	/**
	 * 提交订单至完成状态
	 * @param $order_id
	 * @param $message
	 * @param $pic_key
	 * @param $editor_id
	 */
	public function submitOver($order_id, $message, $pic_key, $editor_id) {
		\DB::transaction(function () use ($pic_key, $order_id, $message, $editor_id) {
			// over picture
			if ($pic_key) {
				self::upload($order_id, $message, $pic_key, DailianPicture::PICTURE_TYPE_SOLDIER_OVER, $editor_id);
			}

			// over log
			DailianLog::record($order_id, $editor_id, DailianLog::LOG_TYPE_SUBMIT_OVER, '[完成待审] 提交订单至待审核状态, 请发单者尽快登陆游戏验收并修改密码! 72 小时后系统将自动结算');

			// 订单状态
			DailianOrder::where('order_id', $order_id)->update([
				'overed_at'    => Carbon::now(),
				'order_status' => DailianOrder::ORDER_STATUS_EXAMINE,
			]);

		});
	}

	/**
	 * 常规完成并付款
	 * @param $order_id
	 * @param $account_id
	 * @return bool
	 */
	public function over($order_id, $account_id) {
		\DB::transaction(function () use ($order_id, $account_id) {

			$order = DailianOrder::info($order_id);

			// 订单状态
			DailianOrder::where('order_id', $order_id)->update([
				'ended_at'     => Carbon::now(),
				'order_status' => DailianOrder::ORDER_STATUS_OVER,
			]);


			// 将冻结订单金发放给用户
			$price = $order->order_price;    // 订单总金额, 发单冻结扣减, 增加用户资金
			$Lock  = new ActionLock();
			$note  = '[订单完成] 支付代练金: ' . $price . ', 订单号: ' . $order['order_no'];
			$Lock->reduce($order->account_id, $price, FinanceLock::MONEY_TYPE_PUB_PAY, $order->order_no, $note);

			$Money    = new ActionMoney();
			$noteOver = '[订单完成] 获得代练金: ' . $price . ', 订单号: ' . $order->order_no;
			$Money->alter($order->sd_account_id, $price, FinanceMoney::MONEY_TYPE_SD_GET, $order->order_no, $noteOver);

			// 解冻保证金
			$guarantee  = bcadd($order->safe_money, $order->speed_money);
			$noteUnlock = '[订单完成] 解冻安全保证金: ' . $order->safe_money . ',解冻效率保证金: ' . $order->speed_money . ', 订单号: ' . $order->order_no;
			$Money->unlock($order->sd_account_id, $guarantee, FinanceMoney::MONEY_TYPE_UNLOCK, $order->order_no, $noteUnlock);

			// log
			DailianLog::record($order->order_id, $account_id, DailianLog::LOG_TYPE_OVER, '[订单完成]审核并支付代练金!');

		});
		return true;
	}

	/**
	 * 锁定订单
	 * @param $order_id
	 * @param $reason
	 * @param $account_id
	 * @return bool
	 */
	public function lock($order_id, $reason, $account_id) {
		DailianOrder::where('order_id', $order_id)->update([
			'order_lock' => DailianOrder::ORDER_LOCK_LOCK,
			'locked_at'  => Carbon::now(),
		]);
		$lockLog = '[锁定订单] ' . $reason;
		DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_LOCK, $lockLog);

		return true;
	}


	/**
	 * 解除锁定
	 * @param $order_id
	 * @param $reason
	 * @param $account_id
	 */
	public function unlock($order_id, $reason, $account_id) {
		DailianOrder::where('order_id', $order_id)->update([
			'order_lock'  => DailianOrder::ORDER_LOCK_UNLOCK,
			'unlocked_at' => Carbon::now(),
		]);
		$unlockLog = '[解除订单锁定] ' . $reason;
		DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_UNLOCK, $unlockLog);

	}

	/**
	 * 修改账号密码
	 * @param $order_id
	 * @param $game_account
	 * @param $game_pwd
	 * @param $account_id
	 */
	public function changeGameAccount($order_id, $game_account, $game_pwd, $account_id) {
		DailianOrder::where('order_id', $order_id)->update([
			'game_account'         => $game_account,
			'game_pwd'             => $game_pwd,
			'exception_handled_at' => Carbon::now(),
			'is_exception_handled' => 'Y',
		]);
		DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_EXCEPTION, '账号密码已经修改!');
	}

	/**
	 * 发布者取消
	 * @param        $order_id
	 * @param        $pub_pay
	 * @param        $sd_pay
	 * @param        $account_id
	 * @param string $reason
	 */
	public function pubCancel($order_id, $pub_pay, $sd_pay, $account_id, $reason = '') {
		$data = [
			'order_status'      => DailianOrder::ORDER_STATUS_CANCEL,
			'cancel_type'       => DailianOrder::CANCEL_TYPE_PUB_DEAL,
			'cancel_status'     => DailianOrder::CANCEL_STATUS_ING,
			'msg_pub_cancel'    => 'Y',
			'cancel_applied_at' => Carbon::now(),
			'pub_pay'           => $pub_pay,
			'sd_pay'            => $sd_pay,
		];
		DailianOrder::where('order_id', $order_id)->update($data);

		$log = '[申请撤单] 发单者申请撤单! ';
		if ($reason) {
			$log .= '[理由]:' . $reason . '! ';
		}
		if ($pub_pay) {
			$log .= '发单者同意支付代练金: ' . LmUtil::formatDecimal($pub_pay) . ', ';
		}
		if ($sd_pay) {
			$log .= '发单者需要接单者支付保证金: ' . LmUtil::formatDecimal($sd_pay) . ', ';
		}
		$log = rtrim($log, ', ');
		DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_PUB_CANCEL, $log);
	}

	/**
	 * 同意发单者撤销
	 * @param $order_id
	 * @return bool
	 */
	public function pubCancelAgree($order_id) {
		$order       = DailianOrder::info($order_id);
		$toSoldier   = $order->pub_pay;  // to soldier
		$toPublisher = $order->sd_pay;   // paid to publisher

		// left money
		$returnToSoldier   = ($order->safe_money + $order->speed_money) - $toPublisher;
		$returnToPublisher = $order->order_price - $toSoldier;

		\DB::transaction(function () use ($order, $toSoldier, $toPublisher, $returnToPublisher, $returnToSoldier) {
			$Money = new ActionMoney();
			$Lock  = new ActionLock();
			// publisher to soldier
			if ($toSoldier) {
				$toSdLog = '[代练金支付] 订单号: ' . $order->order_no;
				$Lock->reduce($order->account_id, $toSoldier, FinanceLock::MONEY_TYPE_PUB_PART_PAY, $order->order_no, $toSdLog);
				$Money->alter($order->sd_account_id, $toSoldier, FinanceMoney::MONEY_TYPE_SD_PART_GET, $order->order_no, $toSdLog);
			}

			// soldier to publisher
			if ($toPublisher) {
				$toPubLog = '[安全金支付] 订单号: ' . $order->order_no;
				$Lock->reduce($order->sd_account_id, $toPublisher, FinanceLock::MONEY_TYPE_SD_COMPENSATE, $order->order_no, $toPubLog);
				$Money->alter($order->account_id, $toPublisher, FinanceMoney::MONEY_TYPE_SD_GET, $order->order_no, $toPubLog);
			}

			// unlock left money
			if ($returnToPublisher) {
				$returnPubLog = '[代练金退还] 订单号:' . $order->order_no;
				$Money->unlock($order->account_id, $returnToPublisher, FinanceLock::MONEY_TYPE_UNLOCK, $order->order_no, $returnPubLog);
			}

			if ($returnToSoldier) {
				$returnSdLog = '[保证金退还] 订单号:' . $order->order_no;
				$Money->unlock($order->sd_account_id, $returnToSoldier, FinanceLock::MONEY_TYPE_UNLOCK, $order->order_no, $returnSdLog);
			}

			// 订单状态的处理
			$data = [
				'order_status'     => DailianOrder::ORDER_STATUS_CANCEL,
				'cancel_status'    => DailianOrder::CANCEL_STATUS_DONE,
				'cancel_passed_at' => Carbon::now(),
			];
			DailianOrder::where('order_id', $order->order_id)->update($data);

		});
		return true;
	}


	/**
	 * 不同意发单者撤销
	 * @param $order_id
	 * @param $account_id
	 */
	public function pubCancelReject($order_id, $account_id) {
		DailianOrder::where('order_id', $order_id)->update([
			'pub_pay'            => 0,
			'sd_pay'             => 0,
			'cancel_status'      => DailianOrder::CANCEL_STATUS_REJECT,
			'cancel_rejected_at' => Carbon::now(),
		]);

		DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_CANCEL, '[拒绝撤销方案] 接单者不同意您的撤销方案!');

	}

	/**
	 * 发布者取消
	 * @param $order_id
	 * @param $pub_pay
	 * @param $sd_pay
	 * @param $account_id
	 */
	public function sdCancel($order_id, $pub_pay, $sd_pay, $account_id) {
		$data = [
			'order_status'      => DailianOrder::ORDER_STATUS_CANCEL,
			'cancel_type'       => DailianOrder::CANCEL_TYPE_SD_DEAL,
			'cancel_status'     => DailianOrder::CANCEL_STATUS_ING,
			'msg_sd_cancel'     => 'Y',
			'cancel_applied_at' => Carbon::now(),
			'pub_pay'           => $pub_pay,
			'sd_pay'            => $sd_pay,
		];
		DailianOrder::where('order_id', $order_id)->update($data);

		$log = '[申请退单] 代练者申请退单! ';
		if ($sd_pay) {
			$log .= '代练者支付保证金: ' . LmUtil::formatDecimal($sd_pay) . ', ';
		}
		if ($pub_pay) {
			$log .= '需要发单者支付代练金: ' . LmUtil::formatDecimal($pub_pay) . ', ';
		}
		$log = rtrim($log, ', ');
		DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_CANCEL, $log);
	}


	/**
	 * 申请客服介入
	 * @param $order_id
	 * @param $editor_id
	 */
	public function kf($order_id, $editor_id) {
		$order = DailianOrder::info($order_id);
		$log   = '[申请客服介入] ';
		if ($order->account_id == $editor_id) {
			$kfApplyBy = DailianOrder::KF_APPLY_BY_PUB;
			$log .= '发单者申请客服介入!';
		} else {
			$kfApplyBy = DailianOrder::KF_APPLY_BY_SD;
			$log .= '接单者申请客服介入!';
		}
		DailianOrder::where('order_id', $order_id)->update([
			'cancel_type'   => DailianOrder::CANCEL_TYPE_KF,
			'kf_status'     => DailianOrder::KF_STATUS_WAIT,
			'kf_apply_by'   => $kfApplyBy,
			'kf_applied_at' => Carbon::now(),
		]);
		DailianLog::record($order_id, $editor_id, DailianLog::LOG_TYPE_CANCEL, $log);
	}

	/**
	 * 客服同意介入, 后台处理
	 * @param $order_id
	 * @param $editor_id
	 */
	public function kfAgree($order_id, $editor_id) {
		$accountName = PamAccount::getAccountNameByAccountId($editor_id);
		$log         = '[客服介入处理] 管理员ID:' . $editor_id . ', 管理员账号: ' . $accountName;
		DailianOrder::where('order_id', $order_id)->update([
			'cancel_type'           => DailianOrder::CANCEL_TYPE_KF,
			'kf_status'             => DailianOrder::KF_STATUS_ING,
			'kf_agree_account_id'   => $editor_id,
			'kf_agree_account_name' => $accountName,
			'kf_agreed_at'          => Carbon::now(),
		]);
		DailianLog::record($order_id, $editor_id, DailianLog::LOG_TYPE_CANCEL, $log);
	}

	/**
	 * 客服处理
	 * @param $order_id
	 * @param $pub_pay
	 * @param $sd_pay
	 * @param $handle_log
	 * @param $editor_id
	 * @return bool
	 */
	public function kfHandle($order_id, $pub_pay, $sd_pay, $handle_log, $editor_id) {
		$order       = DailianOrder::info($order_id);
		$toSoldier   = $pub_pay;  // to soldier
		$toPublisher = $sd_pay;   // paid to publisher

		// left money
		$returnToSoldier   = ($order->safe_money + $order->speed_money) - $toPublisher;
		$returnToPublisher = $order->order_price - $toSoldier;

		\DB::transaction(function () use ($order_id, $returnToPublisher, $toSoldier, $toPublisher, $order, $returnToSoldier, $handle_log, $editor_id) {
			$Money = new ActionMoney();
			$Lock  = new ActionLock();
			// publisher to soldier
			if ($toSoldier) {
				$toSdLog = '[代练金支付] 订单号: ' . $order->order_no;
				$Lock->reduce($order->account_id, $toSoldier, FinanceLock::MONEY_TYPE_PUB_PART_PAY, $order->order_no, $toSdLog, $editor_id);
				$Money->alter($order->sd_account_id, $toSoldier, FinanceMoney::MONEY_TYPE_SD_PART_GET, $order->order_no, $toSdLog, $editor_id);
			}

			// soldier to publisher
			if ($toPublisher) {
				$toPubLog = '[安全金支付] 订单号: ' . $order->order_no;
				$Lock->reduce($order->sd_account_id, $toPublisher, FinanceLock::MONEY_TYPE_SD_COMPENSATE, $order->order_no, $toPubLog, $editor_id);
				$Money->alter($order->account_id, $toPublisher, FinanceMoney::MONEY_TYPE_SD_GET, $order->order_no, $toPubLog, $editor_id);
			}

			// unlock left money
			if ($returnToPublisher) {
				$returnPubLog = '[代练金退还] 订单号:' . $order->order_no;
				$Money->unlock($order->account_id, $returnToPublisher, FinanceLock::MONEY_TYPE_UNLOCK, $order->order_no, $returnPubLog, $editor_id);
			}

			if ($returnToSoldier) {
				$returnSdLog = '[保证金退还] 订单号:' . $order->order_no;
				$Money->unlock($order->sd_account_id, $returnToSoldier, FinanceLock::MONEY_TYPE_UNLOCK, $order->order_no, $returnSdLog, $editor_id);
			}

			// 订单状态的处理
			$data = [
				'order_status'           => DailianOrder::ORDER_STATUS_CANCEL,
				'cancel_status'          => DailianOrder::CANCEL_STATUS_DONE,
				'kf_status'              => DailianOrder::KF_STATUS_DONE,
				'cancel_passed_at'       => Carbon::now(),
				'kf_handled_at'          => Carbon::now(),
				'kf_handle_account_id'   => $editor_id,
				'kf_handle_account_name' => PamAccount::getAccountNameByAccountId($editor_id),
			];

			DailianOrder::where('order_id', $order_id)->update($data);

			DailianLog::record($order_id, $editor_id, DailianLog::LOG_TYPE_CANCEL, '[客服处理完毕] 接单者赔偿保证金:' . $toPublisher . ', 发单者支付代练费' . $toSoldier . ', 备注 : ' . $handle_log);

		});

		return true;
	}

	/**
	 * 取消退单
	 * @param $order_id
	 * @param $account_id
	 */
	public function cancelCancel($order_id, $account_id) {
		$data = [
			'order_status'        => DailianOrder::ORDER_STATUS_ING,
			'cancel_type'         => DailianOrder::CANCEL_TYPE_NONE,
			'cancel_status'       => DailianOrder::CANCEL_STATUS_NONE,
			'msg_sd_cancel'       => 'N',
			'cancel_cancelled_at' => Carbon::now(),
			'pub_pay'             => 0,
			'sd_pay'              => 0,
		];
		DailianOrder::where('order_id', $order_id)->update($data);

		$log = '[取消退单] 取消退单! ';
		DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_CANCEL, $log);
	}


	/**
	 * 撤销订单
	 * @param     $order_id
	 * @param int $editor_id
	 * @return bool
	 */
	public function quash($order_id, $editor_id = 0) {
		$order = DailianOrder::info($order_id);
		if ($order->order_status == DailianOrder::ORDER_STATUS_DELETE) {
			return $this->setError('订单状态已经改变, 不允许重复操作!');
		}

		$price = $order->order_price;

		\DB::transaction(function () use ($order, $price, $editor_id) {
			$Money = new ActionMoney();
			$log   = '[撤单] 订单号: ' . $order['order_no'];
			// money
			$Money->unlock($order->account_id, $price, FinanceMoney::MONEY_TYPE_UNLOCK, $order->order_no, $log);

			// log
			DailianLog::record($order->order_id, $editor_id, DailianLog::LOG_TYPE_DELETE, '[撤单] 用户删除订单');

			DailianOrder::where('order_id', $order->order_id)->update([
				'order_status' => DailianOrder::ORDER_STATUS_DELETE,
				'deleted_at'   => Carbon::now(),
			]);
		});
		return true;
	}


	public function star($order_id, $star_type, $comment, $account_id) {
		$order = DailianOrder::info($order_id);
		$type  = is_pub($order, $account_id) ? DailianOrderStar::FROM_PUBLISHER : DailianOrderStar::FROM_SOLDIER;
		$field = $type == DailianOrderStar::FROM_PUBLISHER ? 'is_pub_star' : 'is_sd_star';
		// 写入 star
		$star = DailianOrderStar::create([
			'order_id'     => $order->order_id,
			'soldier_id'   => $order->sd_account_id,
			'publisher_id' => $order->account_id,
			'star_from'    => $type,
			'is_good'      => $star_type == 'good' ? 'Y' : 'N',
			'is_normal'    => $star_type == 'normal' ? 'Y' : 'N',
			'is_bad'       => $star_type == 'bad' ? 'Y' : 'N',
			'note'         => $comment,
		]);
		// 计算各种数目
		if ($type == DailianOrderStar::FROM_PUBLISHER) {
			// 更新打手的
			switch ($star_type) {
				case 'bad':
					DailianOrderStar::calcSdBad($order->sd_account_id);
					break;
				case 'normal':
					DailianOrderStar::calcSdNormal($order->sd_account_id);
					break;
				case 'good':
				default:
					DailianOrderStar::calcSdGood($order->sd_account_id);
					break;
			}
		} else {
			// 更新发单者的
			switch ($star_type) {
				case 'bad':
					DailianOrderStar::calcPubBad($order->account_id);
					break;
				case 'normal':
					DailianOrderStar::calcPubNormal($order->account_id);
					break;
				case 'good':
				default:
					DailianOrderStar::calcPubGood($order->account_id);
					break;
			}
		}

		// update order
		DailianOrder::where('order_id', $order_id)->update([
			$field    => 'Y',
			'star_id' => $star->star_id,
		]);

		// 写入日志
		DailianLog::record($order_id, $account_id, DailianLog::LOG_TYPE_OVER, '[订单评价] 评价内容:' . $comment);
	}
}