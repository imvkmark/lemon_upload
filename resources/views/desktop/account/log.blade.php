@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		<div class="bar-fixed">
			<div class="title-bar">
				<h3>其他管理</h3>
				<ul class="tab-base">
					<li><a class="{{ ($_route == 'dsk_account.log') ? 'current' : '' }}" href="{{route('dsk_account.log')}}"><span>账户日志</span></a></li>
				</ul>
			</div>
		</div>
		<div>
			<!-- 数据表格 -->
			<table class="table">
				<tr class="thead-space">
					<th class="w72">ID</th>
					<th class="w108">用户名</th>
					<th class="w120">操作时间</th>
					<th class="w108">成功/失败</th>
					<th>说明</th>
				</tr>
				@foreach($items as $item)
					<tr>
						<td>{{$item['log_id']}}</td>
						<td>{{$item['account_name']}}</td>
						<td>{{$item['created_at']}}</td>
						<td>{{$item['log_type']}}</td>
						<td>{{$item['log_content']}}</td>
					</tr>
				@endforeach
			</table>
			<!-- 分页 -->
			<div class="clearfix mt10">
				{!! $items->render() !!}
			</div>
		</div>
	</div>
@endsection