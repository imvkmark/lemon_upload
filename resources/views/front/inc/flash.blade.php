@if (Session::has('end.message'))
	<script>
	require(['jquery', 'jquery.toastr'], function ($, toastr) {
		setTimeout(function () {
			toastr.options = {
				closeButton : true,
				progressBar : true,
				showMethod : 'slideDown',
				timeOut : 4000
			};
			@if (Session::get('end.level') == 'success' )
			toastr.success('{!! Session::get('end.message') !!}');
			@endif
			@if (Session::get('end.level') == 'danger' )
			toastr.error('{!! Session::get('end.message') !!}');
			@endif
		}, 1300);
	})
	</script>
@endif