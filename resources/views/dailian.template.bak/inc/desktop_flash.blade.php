@if ($errors)
	<?php
	// 删除掉请求之后这里再行删除
	$messages = '';
	foreach ($errors->all('<li>:message</li>') as $message) {
		$messages .= $message;
	}
	?>
	@if ($messages)
		<div class="J_flash flash-danger">
			<button type="button" class="J_flash_close">&times;</button>
			<ul>{!! $messages !!}</ul>
		</div>
		<script>
		require(['jquery'], function($){
			$('.J_flash_close').on('click', function(e){
				$('.J_flash').remove();
				e.preventDefault();
			})
		})
		</script>
	@endif
@endif
@if (Session::has('end.message'))
	<div class="J_vendorFlash flash-{{ Session::get('end.level') }}">
		<button type="button" class="J_vendorFlash_close">&times;</button>
		{!! Session::get('end.message') !!}
	</div>
	<script>
	require(['jquery'], function($){
		$('.J_vendorFlash_close').on('click', function(e){
			$('.J_vendorFlash').remove();
			e.preventDefault();
		})
	})
	</script>
@endif