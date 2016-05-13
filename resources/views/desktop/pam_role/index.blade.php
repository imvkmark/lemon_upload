@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		@include('desktop.pam_role.header')
		<div>
			<!-- 数据表格 -->
			<table class="table">
				<tr class="thead-space">
					<th>ID</th>
					<th>角色</th>
					<th>角色显示名称</th>
					<th>添加时间</th>
					<th>编辑时间</th>
					<th>操作</th>
				</tr>
				@foreach($roles as $role)
					<tr>
						<td>{{$role->id}}</td>
						<td>{{$role->role_name}}</td>
						<td>{{$role->role_title}}</td>
						<td>{{$role->created_at}}</td>
						<td>{{$role->updated_at}}</td>
						<td>
							@can('menu', $role)
							<a class="fa fa-check-square-o fa-lg J_iframe"
							   data-title="编辑 [{{$role->role_title}}] 权限"
							   data-width="600"
							   href="{{route('dsk_pam_role.menu', [$role->id])}}"></a>
							@endcan
							@can('edit', $role)
							<a class="fa fa-edit fa-lg" href="{{route('dsk_pam_role.edit', [$role->id])}}"></a>
							@endcan
							@can('destroy', $role)
							<a class="fa fa-remove fa-lg red J_delete" href="{{route('dsk_pam_role.destroy', [$role->id])}}"></a>
							@endcan
						</td>
					</tr>
				@endforeach
			</table>
			<!-- 分页 -->
			<div class="clearfix mt10">
				{!! $roles->render() !!}
			</div>
		</div>
	</div>
@endsection