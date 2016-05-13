<div class="bar-fixed">
	<div class="title-bar">
		<h3>角色管理</h3>
		<ul class="tab-base">
			<li><a class="{{ ($_route == 'dsk_pam_role.index') ? 'current' : '' }}" href="{{route_url('dsk_pam_role.index',null, ['type'=> \Request::input('type')])}}"><span>角色列表</span></a></li>
			@permission('dsk_pam_role.create')
			<li><a class="{{ ($_route == 'dsk_pam_role.create') ? 'current' : '' }}" href="{{route_url('dsk_pam_role.create',null, ['type'=> \Request::input('type')])}}"><span>添加角色</span></a></li>
			@endpermission
			@if (isset($item))
				<li><a class="current" href="javascript:void(0)"><span>编辑 [{{$item['role_name']}}]</span></a></li>
			@endif
		</ul>
		<ul class="tab-group">
			@foreach($_pam_types as $pt)
				<li><a href="{{route('dsk_pam_role.index')}}?type={{$pt['type']}}" class="{{$pt['type']==\Request::input('type') ? 'current' : ''}}"><span>{{$pt['name']}}</span></a></li>
			@endforeach
		</ul>
	</div>
</div>