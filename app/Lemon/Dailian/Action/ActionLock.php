<?php namespace App\Lemon\Dailian\Action;

/**
 * 金钱资金流向
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */


use App\Models\AccountFront;
use App\Models\FinanceLock;
use App\Models\PamAccount;

class ActionLock extends ActionBasic {


	/**
	 * 冻结资金并记录, 纯冻结, 不涉及任何资金增减操作
	 * @param        $account_name
	 * @param        $amount
	 * @param string $money_type
	 * @param string $order_no
	 * @param        $note
	 * @param int    $editor_id 0 是系统
	 * @return bool|mixed
	 */
	public function add($account_name, $amount, $money_type = FinanceLock::MONEY_TYPE_LOCK, $order_no = '', $note, $editor_id = 0) {
		if (!is_numeric($account_name)) {
			$account_id = PamAccount::getAccountIdByAccountName($account_name);
		} else {
			$account_id = $account_name;
		}

		// 冻结资金变动
		$AccountFront = AccountFront::where('account_id',$account_id)->firstOrFail();
		$AccountFront->lock += $amount;
		$AccountFront->save();

		$lockNo = FinanceLock::genNumber();

		$handleType = PamAccount::userType($editor_id);

		// 写入冻结资金记录
		$balance     = AccountFront::locking($account_id);
		$FinanceLock = FinanceLock::create([
			'account_id'  => $account_id,
			'editor_id'   => $editor_id,
			'lock_no'     => $lockNo,
			'amount'      => $amount,
			'handle_type' => $handleType,
			'money_type'  => $money_type,
			'balance'     => $balance,
			'order_no'    => $order_no,
			'note'        => $note,
		]);
		return $FinanceLock->lock_id;
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
	public function reduce($account_name, $amount, $money_type, $order_no = '', $note, $editor_id = 0) {
		return $this->add($account_name, -$amount, $money_type, $order_no, $note, $editor_id);
	}

}