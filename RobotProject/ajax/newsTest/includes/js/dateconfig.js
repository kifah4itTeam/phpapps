$(function() {
	$('.date').datepicker({
		showTime: false,
		dateFormat: 'yy-mm-dd',
		showButtonPanel: true
	 });
	$('.datetime').datepicker({
		showTime: true,
		dateFormat: 'yy-mm-dd',
		showButtonPanel: true,
		stepMinutes: 5,  
		stepHours: 1, 
		time24h: true
	 });
});
