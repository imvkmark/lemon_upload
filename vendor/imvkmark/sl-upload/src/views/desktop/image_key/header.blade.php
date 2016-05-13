<div class="bar-fixed">
	<div class="title-bar">
		<h3>酸柠檬 图片平台KEY管理</h3>
		<ul class="tab-base">
			<li><a class="{{ ($_route == 'dsk_image_key.index') ? 'current' : '' }}" href="{{route('dsk_image_key.index')}}"><span>Key列表</span></a></li>
			<li><a class="{{ ($_route == 'dsk_image_key.create') ? 'current' : '' }}" href="{{route('dsk_image_key.create')}}"><span>Key添加</span></a></li>
			@if (isset($item))
				<li><a class="current" href="javascript:void(0)"><span>编辑 [{{$item->id}}]</span></a></li>
			@endif
		</ul>
	</div>
</div>