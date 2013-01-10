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

// From MyBB, prevents XSS.
function htmlspecialchars_uni($message)
{
	$message = preg_replace("#&(?!\#[0-9]+;)#si", "&amp;", $message); // Fix & but allow unicode
	$message = str_replace("<", "&lt;", $message);
	$message = str_replace(">", "&gt;", $message);
	$message = str_replace("\"", "&quot;", $message);
	return $message;
}

// Insert a task, yay!
function insert_task($task)
{
	global $db;

	$allowedfields = array('type','name','comments','priority','time','date','tid');

	foreach($task as $tk => $t)
	{
		if(!in_array($tk,$allowedfields))
		{
			unset($task[$tk]);
		}
	}

	// Validate type
	$task['type'] = (int)$task['type'];
	if($task['type'] != 1 && $task['type'] != 2)
	{
		// Default to a task
		$task['type'] = 1;
	}

	// Validate name
	$task['name'] = trim($task['name']);
	if(empty($task['name']))
	{
		return array("error" => true, "message" => "The name cannot be blank.");
	} else {
		// Clean it out
		$task['name'] = htmlspecialchars_uni($db->escape_string($task['name']));
	}

	// Comments aren't required, we'll just clean it
	$task['comments'] = htmlspecialchars_uni($db->escape_string($task['comments']));

	// Priority
	$task['priority'] = (int)$task['priority'];
	if($task['priority'] != 1 && $task['priority'] != 2 && $task['priority'] != 3)
	{
		// Default to a medium
		$task['type'] = 2;
	}

	// Time
	$time = explode(':',$task['time']);
	$hour = (int)$time[0];
	$minute = (int)$time[1];

	if($hour > 23 || $hour < 0 || $minute > 59 || $minute < 0)
	{
		return array("error" => true, "message" => "Invalid time!");
	} else {
		$task['time'] = "{$hour}:{$minute}";
	}

	// Date
	$date = explode('-',$task['date']);
	$year = (int)$date[0];
	$month = (int)$date[1];
	$day = (int)$date[2];

	if(checkdate($month,$day,$year))
	{
		$task['date'] = "{$year}-{$month}-{$day}";
	} else {

	}

	// Are we in the past?
	$dv = "{$month}/{$day}/{$year}";
	$time = strtotime("{$dv} {$task['time']}");
	if ($time < time())
	{
		return array("error" => true, "message" => "You can't schedule something in the past!");	
	}

	// Edit or add?
	if(isset($task['tid']))
	{
		$task['tid'] = (int)$task['tid'];
		// Edit it is!
		// Check to make sure the task exists

		$texists = $db->simple_select('tasks','*',"tid='{$task['tid']}'");
		if($db->num_rows($texists) == 0)
		{
			return array("error" => true, "message" => "Invalid task.");			
		}

		$db->update_query('tasks',$task,"tid='{$task['tid']}'");

	} else {
		// Add this new baby!
		$db->insert_query('tasks',$task);
		return true;
	}
}

// Logout the user
function logout()
{
	// Get rid of the cookie
	setcookie('mytasks_pw',NULL,time()-86400,NULL);
}

function output_foot()
{
	echo '<br /><br />
<footer>
<a href="index.php?action=logout&logoutkey='.md5($_COOKIE["mytasks_pw"]).'">Logout</a> &bull; Powered by <a href="https://github.com/PenguinPaul/MyTasks">MyTasks</a> &copy; '.date("Y").' Paul Hedman &bull; <a href="https://github.com/PenguinPaul/MyTasks/wiki/Help">Help</a><br />
</footer>
</body>
</html>';
 	exit; 
}

function output_head()
{
echo '
<!DOCTYPE html>
<html>
<head>
<title>MyTasks</title>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="js/jquery.jeditable.js"></script>
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
		<option id="low" value="1">Low</option>
		<option id="medium" selected="selected" value="2">Medium</option>
		<option id="high" value="3">High</option>
	</select><br />
	<label for="priority">Time:</label>
	<input type="time" name="time" value=""><br />
	<label for="priority">Date:</label>
	<input type="date" name="date" value="" /><br />
	<input type="hidden" name="action" value="addnew" />
	<input type="submit" id="submitnew" value="Add Task"/>
	</form>
</div>
</header>
<br />
';
}

// Bye bye!
?>