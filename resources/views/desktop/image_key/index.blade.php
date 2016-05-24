@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.image_key.header')
		<div>
		@if ($items->total())
			<!-- 数据表格 -->
				<table class="table">
					<tr class="thead-space">
						<th class="w72">开发者账户ID</th>
						<th>类型</th>
						<th>Key</th>
						<th>密钥</th>
						<th class="w108">操作</th>
					</tr>
					@foreach($items as $item)
						<tr>
							<td>{{$item['account_id']}}</td>
							<td>{{\App\Models\PluginImageKey::typeDesc($item['key_type'])}}</td>
							<td>{{$item['key_public']}}</td>
							<td>{{$item['key_secret']}}</td>
							<td>
								<a class="fa fa-edit fa-lg" href="{{route('dsk_image_key.edit', [$item->id])}}"></a>
								<a class="fa fa-remove fa-lg red J_request" data-confirm="您是否要删除 {{$item['pk_key']}} ?" href="{{route('dsk_image_key.destroy', [$item->id])}}"></a>
							</td>
						</tr>
					@endforeach
				</table>
				<!-- 分页 -->
				@if ($items->hasPages())
					<div class="mt10">{!!$items->render()!!}</div>
				@endif
			@else
				@include('desktop.inc.empty')
			@endif
		</div>
	</div>
@endsection