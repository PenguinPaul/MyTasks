<?php

if(!defined('WHATAMI'))
{
	die('This MyTasks functions file cannot be accessed directly.');
}


// This function checks to see if the user is logged in.
function check_login($password)
{
	// Check for the delicios cookie!
	if(isset($_COOKIE['mytasks_pw']))
	{
		if($_COOKIE['mytasks_pw'] == md5($password))
		{
			// Hey, this is the right cookie!  Let's eat it, nomnomnom!
			return true;
		} else {
			// Password is set, but it's wrong!  It's fake!  Poisonous!  Kill it with fire!
			logout();
			return false;
		}
	} else {
		// No password set :(
		return false;
	}
}

// Logout the user
function logout()
{
	// Get rid of the cookie
	setcookie('mytasks_pw',NULL,time()-86400,NULL);
}

function output_head()
{
?>
<!DOCTYPE html>
<html>
<head>
<title>MyTasks</title>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="js/mytasks.js"></script>
<style type="text/css">
#high {background: red;}
#medium {background: yellow;}
#low {background: green;}
</style>
</head>
<body>
<header>
<h1>MyTasks</h1>
<a href="#" id="addtask">New</a>
<div id="newtaskform" style="display:none">
	<form action="index.php" method="post">
	<label for="type">Type:</label>
	<select name="type" id="type">
		<option value="1">Task</option>
		<option value="2">Appointment</option>
	</select><br />
	<label for="name">Name:</label>
	<input type="text" name="name" id="name" /><br />
	<label for="priority">Comments:</label><br />
	<textarea name="comments"></textarea><br />
	<label for="priority">Priority:</label>
	<select name="priority">
		<option id="low">Low</option>
		<option id="medium" selected="selected">Medium</option>
		<option id="high">High</option>
	</select><br />
	<label for="priority">Time:</label>
	<input type="time" name="time" value=""><br />
	<label for="priority">Date:</label>
	<input type="date" name="date" value="" /><br />
	<input type="submit" id="submitnew" value="Add Task"/>
	<input type="hidden" name="action" value="addnew" />
	</form>
</div>
</header>
<br />
<?php
}

function output_foot()
{
?>
<br /><br />
<footer>
<a href="index.php?action=logout&logoutkey=<?php echo md5($_COOKIE['mytasks_pw']); ?>">Logout</a> &bull; Powered by <a href="https://github.com/PenguinPaul/MyTasks">MyTasks</a> &copy; <?php echo date('Y'); ?> Paul Hedman &bull; <a href="https://github.com/PenguinPaul/MyTasks/wiki/Help">Help</a><br />
</footer>
</body>
</html>
<?php
 	exit; 
}

// Bye bye!
?>