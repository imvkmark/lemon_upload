@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.validate.header')
		{!! Form::model(isset($search) ? $search : null,['route' => 'dsk_pam_account.index', 'id' => 'form_search', 'method' => 'get']) !!}
		<table class="table table-search">
			<tr>
				<td>
					{!! Form::label('account_name', '用户名') !!} <span class="sep">&nbsp;</span>
					{!! Form::text('search[account_name]', null, ['placeholder' => '请输入用户名', 'class' => 'small']) !!}<span class="sep">&nbsp;</span>
					<button class="btn-small" type="reset" onclick="window.location.href='{{route('dsk_validate.truename')}}'"><span>重置搜索</span></button>
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
					<th>真实姓名</th>
					<th>昵称</th>
					<th>身份证号</th>
					<th>身份证信息</th>
					<th>操作</th>
				</tr>
				@foreach($accounts as $account)
					<tr>
						<td>{{$account->account_id}}</td>
						<td>{{$account->account_name}}</td>
						<td>{{$account->truename}}</td>
						<td>{{$account->nickname}}</td>
						<th>{{$account->chid}}</th>
						<th>{!!  Form::showThumb($account->chid_pic, ['style'=> 'width:80px;height:60px;'])!!} </th>
						<td>
							@if ($account->v_truename == 'Y')
							<a data-tip="当前认证通过, 点击取消认证" title="禁用" href="{{route('dsk_validate.validate', ['id' => $account->account_id,'field' => 'v_truename', 'status' => 'N'])}}">
								<i class="fa fa-unlock fa-lg green"></i>
							</a>
							@endif
							@if ($account->v_truename == 'N')
							<a data-tip="当前未认证, 点击认证通过" href="{{route('dsk_validate.validate', ['id' => $account->account_id,'field' => 'v_truename', 'status' => 'Y'])}}">
								<i class="fa fa-lock fa-lg red"></i>
							</a>
							@endif
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