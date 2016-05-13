<div class="bar-fixed">
	<div class="title-bar">
		<h3>{!! $title !!}</h3>
		<ul class="tab-base J_tab" data-relation="{!! $type !!}">
			@foreach($groups as $group_key => $group)
				<li><a href="javaScript:void(0);"><span>{!! isset($group['title']) ? $group['title'] : '_其他'  !!}</span></a></li>
			@endforeach
		</ul>
	</div>
</div>