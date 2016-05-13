@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-dialog">
		@if ($total > 0)
			<div class="progress-info">本次需要更新 <strong>{{$total}}</strong> 条信息, 每批次更新 <strong>{{$section}}</strong> 条, 还剩余 <strong>{{$left}}</strong>条</div>
			<div class="progress">
				<div class="progress-bar" style="width: {{$percentage}}%">
					<span class="sr-only">{{$percentage}}%</span>
				</div>
			</div>
			@if ($left == 0) <!--over-->
			<script>
			// over progress close it
			require(['lemon/util'], function (util) {
				var opener = top.util.opener('workspace');
				opener.util.dialog.close();
				opener.util.splash({status:'success',msg: '更新成功!'});
			})
			</script>
			@else

				<script>
				setTimeout("window.location.href = '{!!$continue_url!!}'", {{$continue_time}});
				</script>
			@endif
		@else
			<div class="progress-info">没有需要更新的内容</div>
		@endif
	</div>
@endsection