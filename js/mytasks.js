$(document).ready(function() {
	$('#addtask').click(function() {
			$('#newtaskform').slideDown('slow', function() {
			// Animation complete.
		});
	});

	$("#type").bind("change", function() {
		if($("#type").val() == 1)
		{
			$("#submitnew").attr('value','Add Task');
		} else if($("#type").val() == 2) {
			$("#submitnew").attr('value','Add Appointment');
		}
	});
});