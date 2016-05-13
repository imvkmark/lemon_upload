<?php namespace App\Lemon\Dailian\Action;

/**
 * 金钱资金流向
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */


use App\Models\AccountFront;
use App\Models\FinanceLock;
use App\Models\FinanceMoney;
use App\Models\PamAccount;

class ActionMoney extends ActionBasic {

	/**
	 * 资金的修改
	 * @param        $account_name
	 * @param        $amount
	 * @param        $money_type
	 * @param string $order_no
	 * @param string $note
	 * @param int    $editor_id
	 * @return bool|mixed
	 */
	public function alter($account_name, $amount, $money_type, $order_no = '', $note = '', $editor_id = 0) {
		if (!is_numeric($account_name)) {
			$account_id = PamAccount::getAccountIdByAccountName($account_name);
		} else {
			$account_id = $account_name;
		}
		if (!$account_id) {
			return $this->setError('用户不存在');
		}

		if ($amount < 0) { // 资金减少
			$balance = AccountFront::money($account_id);
			if (abs($amount) > $balance) {
				return $this->setError('余额不足');
			}
		}

		// 写入余额
		$AccountFront = AccountFront::where('account_id', $account_id)->firstOrFail();
		$AccountFront->money += $amount;
		$AccountFront->save();
		// 这个语句无法处理是 null 的状况
		// AccountFront::where('account_id', $account_id)->increment('money', $amount);
		$balance = AccountFront::money($account_id);
		$moneyNo = FinanceMoney::genNumber();

		$handleType = PamAccount::userType($editor_id);

		// 写入日志
		$FinanceMoney = FinanceMoney::create([
			'account_id'  => $account_id,
			'money_no'    => $moneyNo,
			'editor_id'   => $editor_id,
			'amount'      => $amount,
			'money_type'  => $money_type,
			'handle_type' => $handleType,
			'balance'     => $balance,
			'order_no'    => $order_no,
			'note'        => $note,
		]);
		$money_id     = $FinanceMoney->money_id;

		return $money_id;
	}

	/**
	 * 冻结资金
	 * @param        $account_id_or_name
	 * @param        $amount
	 * @param        $money_type
	 * @param string $order_no
	 * @param        $note
	 * @param int    $editor_id 0 是系统
	 * @return bool|mixed
	 */
	public function lock($account_id_or_name, $amount, $money_type, $order_no = '', $note, $editor_id = 0) {
		if (!is_numeric($account_id_or_name)) {
			$account_id = PamAccount::getAccountIdByAccountName($account_id_or_name);
		} else {
			$account_id = $account_id_or_name;
		}

		// 变动资金
		if (!$this->alter($account_id, -$amount, $money_type, $order_no, $note, $editor_id)) {
			return false;
		}
		$Lock = new ActionLock();
		if ($amount > 0) {
			$type = FinanceLock::MONEY_TYPE_LOCK;
		} else {
			$type = FinanceLock::MONEY_TYPE_UNLOCK;
		}
		return $Lock->add($account_id, $amount, $type, $order_no, $note, $editor_id);

	}

	/**
	 * 解锁资金到用户账户
	 * @param        $account_name
	 * @param        $amount
	 * @param        $money_type
	 * @param string $order_no
	 * @param        $note
	 * @param int    $editor_id
	 * @return bool|mixed
	 */
	public function unlock($account_name, $amount, $money_type, $order_no = '', $note, $editor_id = 0) {
		return $this->lock($account_name, -$amount, $money_type, $order_no, $note, $editor_id);
	}

}