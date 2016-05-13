<?php namespace App\Lemon\Dailian\Action;

/**
 * 提现操作
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */


use App\Models\FinanceBank;
use App\Models\FinanceCash;
use App\Models\FinanceLock;
use App\Models\FinanceMoney;
use App\Models\PamAccount;
use Carbon\Carbon;

class ActionCash extends ActionBasic {


	/**
	 * 提交提现申请
	 * @param $account_id
	 * @param $bank_id
	 * @param $amount
	 * @return bool
	 */
	public function create($account_id, $bank_id, $amount) {
		$account = PamAccount::info($account_id, true);
		if ($amount > $account['front']['money']) {
			return $this->setError('没有充足的金额');
		}

		$banks = FinanceCash::banks($account_id);
		if (!$bank_id || !array_key_exists($bank_id, $banks)) {
			return $this->setError('您的提现账户不存在, 请刷新页面后重试!');
		}

		return \DB::transaction(function () use ($account_id, $amount, $bank_id) {
			$Money = new ActionMoney();
			$Money->lock($account_id, $amount, FinanceMoney::MONEY_TYPE_CASH, 0, '申请提现', 0);
			$cashNo     = FinanceCash::genNumber();
			$cashFee    = round($amount * (intval(site('cash_rate')) / 100), 2);
			$overAmount = $amount - $cashFee;
			$bank       = FinanceBank::findOrFail($bank_id);
			FinanceCash::create([
				'cash_no'       => $cashNo,
				'amount'        => $amount,
				'cash_status'   => FinanceCash::CASH_STATUS_CREATE,
				'cash_fee'      => $cashFee,
				'over_amount'   => $overAmount,
				'bank_type'     => $bank['bank_type'],
				'bank_truename' => $bank['bank_truename'],
				'bank_account'  => $bank['bank_account'],
				'account_id'    => $account_id,
			]);
			return true;
		});
	}

	/**
	 * 审查通过
	 * @param $cash_id
	 * @param $editor_id
	 * @return bool
	 */
	public function check($cash_id, $editor_id) {
		$data = [
			'cash_status'      => FinanceCash::CASH_STATUS_ING,
			'passed_at'        => Carbon::now(),
			'passed_editor_id' => $editor_id,
		];
		FinanceCash::where('cash_id', $cash_id)
			->update($data);
		return true;
	}

	/**
	 * 拒绝提现申请
	 * @param      $cash_id
	 * @param      $editor_id
	 * @param null $reject_note
	 * @return bool
	 */
	public function reject($cash_id, $editor_id, $reject_note = null) {
		$cash  = FinanceCash::findOrFail($cash_id);
		$Money = new ActionMoney();
		$Money->unlock($cash['account_id'], $cash['amount'], FinanceMoney::MONEY_TYPE_UNLOCK, null, $reject_note, $editor_id);
		$data = [
			'cash_status'        => FinanceCash::CASH_STATUS_REJECT,
			'reject_note'        => $reject_note,
			'rejected_at'        => Carbon::now(),
			'rejected_editor_id' => $editor_id,
		];
		FinanceCash::where('cash_id', $cash_id)
			->update($data);
		return true;
	}


	/**
	 * 提现完成, 扣减冻结资金
	 * @param      $cash_id
	 * @param      $editor_id
	 * @param null $over_note
	 * @return bool
	 */
	public function over($cash_id, $editor_id, $over_note = null) {
		$cash = FinanceCash::findOrFail($cash_id);
		$Lock = new ActionLock();
		$Lock->reduce($cash['account_id'], $cash['amount'], FinanceLock::MONEY_TYPE_UNLOCK, 0, $over_note, $editor_id);
		$data = [
			'cash_status'      => FinanceCash::CASH_STATUS_OVER,
			'over_note'        => $over_note,
			'overed_at'        => Carbon::now(),
			'overed_editor_id' => $editor_id,
		];
		FinanceCash::where('cash_id', $cash_id)
			->update($data);
		return true;
	}

	/**
	 * 提现完成, 扣减冻结资金
	 * @param      $cash_id
	 * @param      $editor_id
	 * @param null $fail_note
	 * @return bool
	 */
	public function fail($cash_id, $editor_id, $fail_note = null) {
		$cash  = FinanceCash::findOrFail($cash_id);
		$Money = new ActionMoney();
		$Money->unlock($cash['account_id'], $cash['amount'], FinanceMoney::MONEY_TYPE_UNLOCK, 0, $fail_note, $editor_id);
		$data = [
			'cash_status'      => FinanceCash::CASH_STATUS_FAIL,
			'fail_note'        => $fail_note,
			'failed_at'        => Carbon::now(),
			'failed_editor_id' => $editor_id,
		];
		FinanceCash::where('cash_id', $cash_id)
			->update($data);
		return true;
	}


}