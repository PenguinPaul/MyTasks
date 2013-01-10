$(document).ready(function() {

	// New task slidedown menu
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


	// Edit stuff!
	$('.edit_name').editable('index.php?action=edit', {
			//cancel    : 'Cancel',
			//submit    : 'OK',
			indicator : "<img src='images/indicator.gif'>",
			event     : "dblclick",
	});

	$('.edit_comments').editable('index.php?action=edit', {
			cancel    : 'Cancel',
			submit    : 'OK',
	});

});