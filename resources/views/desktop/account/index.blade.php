@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.account.header')
		{!! Form::model(isset($search) ? $search : null,['route' => 'dsk_pam_account.index', 'id' => 'form_search', 'method' => 'get']) !!}
		{!!Form::hidden('type', $account_type)!!}
		<table class="table table-search">
			<tr>
				<td>
					{!! Form::label('account_name', '用户名') !!} <span class="sep">&nbsp;</span>
					{!! Form::text('search[account_name]', null, ['placeholder' => '请输入用户名', 'class' => 'small']) !!}<span class="sep">&nbsp;</span>
					{!! Form::label('role_id', '用户角色') !!} <span class="sep">&nbsp;</span>
					{!! Form::select('search[role_id]', $roles) !!} <span class="sep">&nbsp;</span>
					<button class="btn-small" type="reset" onclick="window.location.href='{{route('dsk_pam_account.index', ['type' => $account_type])}}'"><span>重置搜索</span></button>
					<button class="btn-search"><span>Search</span></button>
				</td>
			</tr>
		</table>
		{!! Form::close() !!}
		<div data-rel="account">
			<table class="table">
				<tr class="thead-space">
					<th>用户ID</th>
					<th>用户名</th>
					<th>登录次数</th>
					<th>角色名称</th>
					<th>注册时间</th>
					@if ($account_type == 'desktop')
						<th>QQ</th>
					@endif
					@if ($account_type == 'front')
						<th>QQ</th>
						<th>联系方式</th>
						<th>资金</th>
					@endif
					<th>操作</th>
				</tr>
				@foreach($accounts as $account)
					<tr>
						<td>{{$account->account_id}}</td>
						<td>{{$account->account_name}}</td>
						<td>{{$account->login_times}}</td>
						<td>{{\App\Models\PamRole::info($account->role_id, 'role_name')}}</td>
						<td>{{$account->created_at}}</td>
						@if ($account_type == 'desktop')
							<td>{{$account->qq}}</td>
						@endif
						@if ($account_type == 'front')
							<th>{{$account->qq}}</th>
							<th>{{$account->mobile}}</th>
							<th>{{$account->money}}</th>
						@endif
						<td>
							@if (check_auth($_role_id, 'dsk_pam_account.auth'))
								<a data-tip="授权进入用户中心" target="_blank" href="{{route('dsk_pam_account.auth', [$account->account_id])}}">
									<i class="fa fa-user-md fa-lg"></i>
								</a>
							@endif
							@if ($account->is_enable == 'Y')
								<a class="J_request" data-tip="当前启用, 点击禁用" title="禁用" href="{{route_url('dsk_pam_account.status',null, ['id' => $account->account_id, 'field' => 'is_enable', 'status' => 'N', 'type' => $account_type])}}">
									<i class="fa fa-unlock fa-lg green"></i>
								</a>
							@else
								<a class="J_request" data-tip="当前禁用, 点击启用" href="{{route_url('dsk_pam_account.status',null, ['id' => $account->account_id, 'field' => 'is_enable', 'status' => 'Y', 'type' => $account_type])}}">
									<i class="fa fa-lock fa-lg red"></i>
								</a>
							@endif
							<a data-tip="编辑[{{$account->account_name}}]" href="{{route('dsk_pam_account.edit', [$account->account_id])}}">
								<i class="fa fa-edit fa-lg"></i>
							</a>
							<a class="J_request" data-tip="删除[{{$account->account_name}}]" data-confirm="确认删除？" href="{{route_url('dsk_pam_account.destroy',null, ['id' =>$account->account_id])}}">
								<i class="fa fa-close fa-lg red"></i>
							</a>
						</td>
					</tr>
				@endforeach
			</table>
			<!-- 分页 -->
			<div class="mt10">
				{!! $accounts->render() !!}
			</div>
		</div>
	</div>
@endsection