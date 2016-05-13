<?php
/**
 * 统计用户的各种数量
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2015 lemon team
 */
namespace App\Jobs;

use App\Models\AccountFront;
use App\Models\DailianOrder;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FrontStatisticCalcNum extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	protected $account_id;

	/** @var AccountFront */
	protected $front;
	protected $isSub   = false;
	protected $ownerId = 0;

	/**
	 * 统计用户计算数量
	 * @param $account_id
	 */
	public function __construct($account_id) {
		$this->account_id = $account_id;
		$this->front      = AccountFront::findOrFail($account_id);
		if ($this->front->parent_id) {
			$this->isSub   = true;
			$this->ownerId = $this->front->parent_id;
		} else {
			$this->ownerId = $this->front->account_id;
			$this->isSub   = false;
		}
	}

	/**
	 * Execute the job.
	 * @return void
	 */
	public function handle() {
		$ownerId = $this->ownerId;

		$update = [
			// 总创建订单的数量, 删除的和未付款的除外
			'pub_publish_all_num' => DailianOrder::calcPubAllNum($ownerId),

			// 发单者创建, 但是尚未付款的订单
			'pub_create_num'      => DailianOrder::calcPubCreateNum($ownerId),

			// 发单者订单创建, 等待接手的数量
			'pub_publish_num'     => DailianOrder::calcPubPublishNum($ownerId),

			// 发单者完成订单的数量
			'pub_over_num'        => DailianOrder::calcPubOverNum($ownerId),

			// 订单发布人进行中的数量
			'pub_ing_num'         => DailianOrder::calcPubIngNum($ownerId),

			// 发单者异常数量
			'pub_exception_num'   => DailianOrder::calcPubExceptionNum($ownerId),

			// 发单者锁定数量
			'pub_lock_num'        => DailianOrder::calcPubLockNum($ownerId),

			// 等待审核的订单的数量
			'pub_examine_num'     => DailianOrder::calcPubExamineNum($ownerId),

			// 发单人撤单总数量
			'pub_cancel_all_num'  => DailianOrder::calcPubCancelAllNum($ownerId),

			// 进行中的撤单总数量
			'pub_cancel_ing_num'  => DailianOrder::calcPubCancelIngNum($ownerId),

			// 客服介入的撤单数量 / 已完成
			'pub_cancel_kf_num'   => DailianOrder::calcPubCancelKfNum($ownerId),

			// 已完成, 是和解状态的数量
			'pub_cancel_deal_num' => DailianOrder::calcPubCancelDealNum($ownerId),

			// 接单者进行中数量
			'sd_ing_num'          => DailianOrder::calcSdIngNum($ownerId),

			// 总接单数
			'sd_assign_all_num'   => DailianOrder::calcSdAssignNum($ownerId),

			// 接单者异常订单数量
			'sd_exception_num'    => DailianOrder::calcSdExceptionNum($ownerId),

			// 计算接单者锁定订单数量
			'sd_lock_num'         => DailianOrder::calcSdLockNum($ownerId),

			// 接单者等待验收的数量
			'sd_examine_num'      => DailianOrder::calcSdExamineNum($ownerId),

			// 接单者完成订单数
			'sd_over_num'         => DailianOrder::calcSdOverNum($ownerId),

			// 接单者撤单完成的所有数量
			'sd_cancel_all_num'   => DailianOrder::calcSdCancelAllNum($ownerId),

			// 进行中的客服介入数量
			'sd_cancel_kf_num'    => DailianOrder::calcSdCancelKfNum($ownerId),

			// 撤销进行中的订单的数量
			'sd_cancel_ing_num'   => DailianOrder::calcSdCancelIngNum($ownerId),

			// 友好协商的完成的撤单数量
			'sd_cancel_deal_num'  => DailianOrder::calcSdCancelDealNum($ownerId),
		];
		AccountFront::where('account_id', $ownerId)->update($update);
	}
}
