<div class="bar-fixed">
	<div class="title-bar">
		<h3>用户管理</h3>
		<ul class="tab-base">
			<li><a class="{{ ($_route == 'dsk_pam_account.index') ? 'current' : '' }}" href="{{route_url('dsk_pam_account.index',null, ['type'=> $account_type])}}"><span>用户管理</span></a></li>
			<li><a class="{{ ($_route == 'dsk_pam_account.create') ? 'current' : '' }}" href="{{route_url('dsk_pam_account.create',null, ['type'=> $account_type])}}"><span>添加用户</span></a></li>
			@if (isset($item))
				<li><a class="current" href="javascript:void(0)"><span>编辑 [{{$item['account_name']}}]</span></a></li>
			@endif
		</ul>
		<ul class="tab-group">
			@foreach($_pam_types as $pt)
				<li><a href="{{route_url('dsk_pam_account.index', null, ['type'=>$pt['type']])}}" class="{{$pt['type']==$account_type ? 'current' : ''}}"><span>{{$pt['name']}}</span></a></li>
			@endforeach
		</ul>
	</div>
</div>