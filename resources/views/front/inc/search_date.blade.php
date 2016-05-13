<div class="form-group">
	{!!Form::label('time', '时间: ')!!}
	<div class="input-group form-group" style="margin-left: -3px;">
		<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
		{!! Form::text('start_date', null, ['id' => 'J_daystart', 'class' => 'form-control w120', 'placeholder'=>"开始时间"]) !!}
	</div>
	-
	<div class="input-group form-group" style="margin-left: -3px;">
		<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
		{!! Form::text('end_date', null, ['id' => 'J_dayend', 'class' => 'form-control w120', 'placeholder'=>"结束时间"]) !!}
	</div>
	<script>
	require(['jquery', 'jquery.ui'], function ($) {
		$("#J_daystart").datepicker({
			onClose : function (selectedDate) {
				$("#J_dayend").datepicker("option", "minDate", selectedDate);
			},
			dateFormat : "yy-mm-dd"
		});
		$("#J_dayend").datepicker({
			maxDate : 0,
			onClose : function (selectedDate) {
				$("#J_daystart").datepicker("option", "maxDate", selectedDate);
			},
			dateFormat : "yy-mm-dd"
		});
	});
	</script>
</div>