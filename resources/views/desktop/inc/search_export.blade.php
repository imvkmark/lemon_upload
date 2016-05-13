<input type="text" name="pagesize" value="{!! $_pagesize !!}" placeholder="分页" class="w60">
<input type="hidden" name="export" id="export" value="">
{!!Form::button('<span>导出</span>', ['class' => 'btn-small J_export', 'type' => 'submit'])!!}
<script>
require(['jquery'], function($){
	$(function(){
		$('.J_export').on('click', function(){
			$('#export').val(1);
		});
		$('.J_search').on('click', function () {
			$('#export').val(0);
		})
	})
})
</script>