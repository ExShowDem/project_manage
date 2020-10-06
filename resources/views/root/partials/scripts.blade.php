@routes
<script src="{{ asset('assets/root/js/theme.js') }}"></script>
<script src="{{ asset('/js/app.js') }}"></script>
@yield('script')
<script src="{{ asset('/js/vue_init.js') }}"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$( function() {
		$( "#start_date" ).datepicker();
		$( "#end_date" ).datepicker();
	} );
</script>
