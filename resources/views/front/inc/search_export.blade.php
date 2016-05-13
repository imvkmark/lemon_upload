<input type="text" name="pagesize" value="{!! $_pagesize !!}" placeholder="分页" class="form-control w60">
<input type="hidden" name="export" id="export" value="">
{!!Form::button('搜索', ['class' => 'btn btn-info btn-sm J_search', 'type' => 'submit'])!!}
{!!Form::button('导出', ['class' => 'btn btn-success btn-sm J_export', 'type' => 'submit'])!!}
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