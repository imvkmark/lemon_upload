<?php

use App\Models\DailianOrder;

class ServerTest extends TestCase {


	public function testServerIdParent()
	{
		$account_id = 1831;
		$update = [

			// 总创建订单的数量, 删除的和未付款的除外
			'pub_publish_all_num' => DailianOrder::calcPubAllNum($account_id),

			// 发单者创建, 但是尚未付款的订单
			'pub_create_num'      => DailianOrder::calcPubCreateNum($account_id),

			// 发单者订单创建, 等待接手的数量
			'pub_publish_num'     => DailianOrder::calcPubPublishNum($account_id),

			// 发单者完成订单的数量
			'pub_over_num'        => DailianOrder::calcPubOverNum($account_id),

			// 订单发布人进行中的数量
			'pub_ing_num'         => DailianOrder::calcPubIngNum($account_id),

			// 发单者异常数量
			'pub_exception_num'   => DailianOrder::calcPubExceptionNum($account_id),

			// 发单者锁定数量
			'pub_lock_num'        => DailianOrder::calcPubLockNum($account_id),

			// 等待审核的订单的数量
			'pub_examine_num'     => DailianOrder::calcPubExamineNum($account_id),

			// 发单人撤单总数量
			'pub_cancel_all_num'  => DailianOrder::calcPubCancelAllNum($account_id),

			// 进行中的撤单总数量
			'pub_cancel_ing_num'  => DailianOrder::calcPubCancelIngNum($account_id),

			// 客服介入的撤单数量 / 已完成
			'pub_cancel_kf_num'   => DailianOrder::calcPubCancelKfNum($account_id),

			// 已完成, 是和解状态的数量
			'pub_cancel_deal_num' => DailianOrder::calcPubCancelDealNum($account_id),

			// 接单者进行中数量
			'sd_ing_num'          => DailianOrder::calcSdIngNum($account_id),

			// 总接单数
			'sd_assign_all_num'   => DailianOrder::calcSdAssignNum($account_id),

			// 接单者异常订单数量
			'sd_exception_num'    => DailianOrder::calcSdExceptionNum($account_id),

			// 计算接单者锁定订单数量
			'sd_lock_num'         => DailianOrder::calcSdLockNum($account_id),

			// 接单者等待验收的数量
			'sd_examine_num'      => DailianOrder::calcSdExamineNum($account_id),

			// 接单者完成订单数
			'sd_over_num'         => DailianOrder::calcSdOverNum($account_id),

			// 接单者撤单完成的所有数量
			'sd_cancel_all_num'   => DailianOrder::calcSdCancelAllNum($account_id),

			// 进行中的客服介入数量
			'sd_cancel_kf_num'    => DailianOrder::calcSdCancelKfNum($account_id),

			// 撤销进行中的订单的数量
			'sd_cancel_ing_num'   => DailianOrder::calcSdCancelIngNum($account_id),

			// 友好协商的完成的撤单数量
			'sd_cancel_deal_num'  => DailianOrder::calcSdCancelDealNum($account_id),
		];
		\Log::info($update);
	}


	public function testServerIdChild()
	{
		$parentId = \App\Models\GameServer::find(12)->parent_id;
		$this->assertEquals($parentId, 1);
	}

}
