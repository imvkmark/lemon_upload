@extends('dailian.template.cp')
@section('dailian_cp-main')
<div class="row">
	<div class="col-md-12">
		<div class="ibox">
			<div class="ibox-title">
				<h5>账户信息</h5>
				<div class="ibox-tools">
					<a href="{!! route('finance.money_list') !!}">查询明细</a>
				</div>
			</div>
			<div class="ibox-content">
				@include('front.inc.finance')
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>订单管理</h5>
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="订单发布, 等待接手的数量" href="{!! $url['pub_publish'] !!}">
									未接手: <span class="badge badge-warning">{!! $num['pub_wait'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="订单正在被代练的数量" href="{!! $url['pub_ing'] !!}">
									进行中: <span class="badge badge-warning">{!! $num['pub_ing'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="异常订单数量" href="{!! $url['pub_exception'] !!}">
									异常: <span class="badge badge-warning">{!! $num['pub_exception'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="锁定订单数量" href="{!! $url['pub_lock'] !!}">
									锁定: <span class="badge badge-warning">{!! $num['pub_lock'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="待验收订单数量" href="{!! $url['pub_examine'] !!}">
									待验收: <span class="badge badge-warning">{!! $num['pub_examine'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="撤单中订单数量" href="{!! $url['pub_cancel'] !!}">
									退单中: <span class="badge badge-warning">{!! $num['pub_cancel'] !!}</span>
								</a>
							</div>
						</div>
						<br/>
						<div class="row">
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="等待接单的订单" href="{!! $url['sd_publish'] !!}" >
									待接单: <span class="badge badge-primary">{!! $num['sd_wait'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="我正在代练中的订单数量" href="{!! $url['sd_ing'] !!}" >
									进行中: <span class="badge badge-primary">{!! $num['sd_ing'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="订单异常数量" href="{!! $url['sd_exception'] !!}" >
									异常: <span class="badge badge-primary">{!! $num['sd_exception'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="锁定订单数量" href="{!! $url['sd_lock'] !!}" >
									锁定: <span class="badge badge-primary">{!! $num['sd_lock'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="待验收订单数量" href="{!! $url['sd_examine'] !!}" >
									待验收: <span class="badge badge-primary">{!! $num['sd_examine'] !!}</span>
								</a>
							</div>
							<div class="col-md-2">
								<a data-toggle="tooltip" data-placement="top" title="退单订单数量" href="{!! $url['sd_cancel'] !!}" >
									退单中: <span class="badge badge-primary">{!! $num['sd_cancel'] !!}</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>发单管理</h5>
					</div>
					<div class="ibox-content">
						<ul class="folder-list m-b-md" style="padding: 0">
							<li>
								<i class="fa fa-inbox "></i>
								总发单数<span class="label label-warning pull-right">{!! $num['pub_publish'] !!}</span>
							</li>
							<li>
								<i class="fa fa-envelope-o"></i>
								订单完成数<span class="label label-warning pull-right">{!! $num['pub_over'] !!}</span>
							</li>
							<li>
								<i class="fa fa-certificate"></i>
								好评数<span class="label label-warning pull-right">{!! $_front->pub_star_good !!}</span>
							</li>
							<li>
								<i class="fa fa-file-text-o"></i>
								中评数 <span class="label label-danger pull-right">{!! $_front->pub_star_normal !!}</span>
							</li>
							<li>
								<i class="fa fa-trash-o"></i>
								差评数<span class="label label-warning pull-right">{!! $_front->pub_star_bad !!}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>接单管理</h5>
					</div>
					<div class="ibox-content">
						<ul class="folder-list m-b-md" style="padding: 0">
							<li>
								<i class="fa fa-inbox "></i>
								总接单数<span class="label label-warning pull-right">{!! $_front->sd_assign_all_num !!}</span>
							</li>
							<li>
								<i class="fa fa-envelope-o"></i>
								总完成数<span class="label label-warning pull-right">{!! $_front->sd_over_num !!}</span>
							</li>
							<li>
								<i class="fa fa-certificate"></i>
								好评数<span class="label label-warning pull-right">{!! $_front->sd_star_good !!}</span>
							</li>
							<li>
								<i class="fa fa-file-text-o"></i>
								中评数<span class="label label-warning pull-right">{!! $_front->sd_star_normal !!}</span>
							</li>
							<li>
								<i class="fa fa-trash-o"></i> 差评数<span class="label label-warning pull-right">{!! $_front->sd_star_bad !!}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>最新公告</h5>
				<div class="ibox-tools">
					<a href="{{route('help.index', ['cat_id'=> 1])}}">更多</a>
				</div>
			</div>
			<div class="ibox-content">
				<div>
					<div class="feed-activity-list">
						@foreach($announces as $announce)
						<div class="feed-element">
							<div class="media-body ">
								<a href="{{route('help.show', ['id'=> $announce['help_id']])}}">{{$announce['help_title']}}</a>
								<small class="text-muted J_timeago pull-right">{{$announce['created_at']}}</small>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>联系我们</h5>
			</div>
			<div class="ibox-content">
				<div>
					<div class="feed-activity-list">
						@foreach($kfs as $kf)
						<div class="feed-element">
							<div class="media-body ">
								<strong>{{$kf['kf_title']}}</strong> &nbsp;&nbsp;
								<small class="text-muted"> &nbsp;&nbsp;
									<i class="fa fa-qq fa-lg">{{$kf['qq']}}</i> &nbsp;&nbsp;
									<i class="fa fa-phone fa-lg">{{$kf['mobile']}}</i>
								</small>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection