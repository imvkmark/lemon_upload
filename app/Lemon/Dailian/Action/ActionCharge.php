<?php namespace App\Lemon\Dailian\Action;

/**
 * 充值
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */


use App\Models\FinanceCharge;
use App\Models\FinanceMoney;
use App\Models\PamAccount;

class ActionCharge extends ActionBasic {


	/**
	 * 创建支付订单
	 * @param        $account_id
	 * @param        $amount
	 * @param        $charge_type
	 * @param   int  $editor_id 编辑者ID
	 * @param string $note
	 * @return mixed
	 */
	public function createOrder($account_id, $amount, $charge_type, $editor_id, $note = '') {
		$account_id = is_numeric($account_id) ? $account_id : PamAccount::getAccountIdByAccountName($account_id);
		$Charge     = FinanceCharge::create([
			'account_id'    => $account_id,
			'amount'        => $amount,
			'charge_type'   => $charge_type,
			'charge_status' => FinanceCharge::CHARGE_STATUS_CREATE,
			'note'          => $note,
			'editor_id'     => $editor_id,
			'charge_no'     => FinanceCharge::genNumber(),
		]);
		return $Charge->charge_no;
	}

	/**
	 * 支付成功
	 * @param $charge_no
	 * @param $payment_no
	 * @return bool
	 */
	public function payOk($charge_no, $payment_no) {
		// check
		/** @var FinanceCharge $orderInfo */
		$orderInfo = FinanceCharge::where('charge_no', $charge_no)->firstOrFail();
		if ($orderInfo->charge_status == FinanceCharge::CHARGE_STATUS_OVER) {
			return $this->setError('此订单已经支付完成');
		}
		FinanceCharge::where('charge_no', $charge_no)->update([
			'charge_no'     => $charge_no,
			'order_no'      => $payment_no,
			'editor_id'     => 0,
			'charge_status' => FinanceCharge::CHARGE_STATUS_OVER,
		]);
		$Money = new ActionMoney();
		$Money->alter($orderInfo->account_id, $orderInfo['amount'], FinanceMoney::MONEY_TYPE_CHARGE, $payment_no, '充值成功:' . $orderInfo['charge_no']);
		return true;
	}

}