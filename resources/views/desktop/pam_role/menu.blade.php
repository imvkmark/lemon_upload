@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-dialog" style="height:550px;overflow-y: auto">
		{!! Form::open(['route' => ['dsk_pam_role.menu', $role->id]]) !!}
		<table id="J_tree" width="100%" border="0" cellpadding="4" cellspacing="1" class="table">
			<tr>
				<td>
					@foreach($permission as $title => $links)
						<fieldset class="d">
							<legend>{!! $title !!} </legend>
							<div class="form-element">
								<ul class="check-list">
									@foreach($links as $link)
										<li class="w120">
											<label for="per_{!! $link->id !!}">
												<input id="per_{!! $link->id !!}" type="checkbox" name="key[{!! $link->id !!}]" @if ($role->hasPermission($link->permission_name)) checked="checked" @endif value="1">
												@if ($link->is_menu)
													{!! Form::tip("菜单项目", "fa-bars") !!}
												@endif
												{!! $link->permission_title !!}
											</label>
										</li>
									@endforeach
								</ul>
							</div>
						</fieldset>
					@endforeach
				</td>
			</tr>
			<tr>
				<td>{!! Form::button('<span>保存</span>', ['class'=>'btn-small', 'type'=>'submit']) !!}</td>
			</tr>
		</table>
		{!!Form::close()!!}
	</div>
@endsection