
<?php

// MyTasks
// Task Management System by Paul H.
// Copyright 2013 Paul H.
// http://www.paulhedman.com
// GPL? LGPL? WTFPL? Dunno yet.

define('WHATAMI','MyTasks');
define('VERSION','0.1');

if(isset($_GET['intcheck']))
{
	die(WHATAMI);
}

if(function_exists("unicode_decode"))
{
    // Unicode extension introduced in 6.0
    error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE ^ E_STRICT);
}
elseif(defined("E_DEPRECATED"))
{
    // E_DEPRECATED introduced in 5.3
    error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
}
else
{
    error_reporting(E_ALL & ~E_NOTICE);
}

include('./config.php');
include('./inc/functions.php');

// We'll check for the password first... if the person can't see the data why bother connecting to the DB?

if(isset($_GET['action']) && $_GET['action'] == 'logout')
{
	if($_GET['logoutkey'] == md5($_COOKIE['mytasks_pw']))
	{
		logout();
		header('Location: index.php');
	} else {
		output_head();
		die('An error occured when logging out.');
	}
}

if(isset($_POST['password']))
{
	if($_POST['password'] == $password)
	{
		$hashdatpass = md5($password);
		setcookie('mytasks_pw',$hashdatpass,0,NULL);
		$_COOKIE['mytasks_pw'] = $hashdatpass;
	} else {
		output_head();
		echo 'Incorrect password.<br />';
	}
}



if(isset($password))
{
	$loginstatus = check_login($password);

	if($loginstatus == false)
	{
		output_head();
		echo 'You\'re not logged in!  Login to see your tasks and stuff. <br />
<form action="index.php" method="post">
	<label for="password">Password:</label>
	<input type="password" name="password" />
	<input type="submit" value="Login"/>
</form>';
		output_foot();
	}
}


if(!isset($config))
{
	output_head();
	die('No configuration information found.  Please edit config.php with your database information.');
}

// Get that DB connection going!
include("./inc/db_mysqli.php");
$db = new DB_MySQLi;
$db->connect($config);
$db->set_table_prefix($config['prefix']);

if(!$db->table_exists('tasks'))
{
	echo 'MyTasks is not installed! <br /> Attempting to run installer...<br />';
	include('./inc/install.php');
	echo 'Installer ran.<br /><a href="index.php">Reload page to complete installation</a>.';
	die;

}

// Add a new task? :o
if($_POST['action'] == 'addnew')
{
	$status = insert_task($_POST);

	if(is_array($status))
	{	
		// O noes, an error!
		var_dump($status);
		exit;
	} else {
		// Hooray!
		header('Location: index.php?add=success');
		exit;
	}
}


// Or maybe edit one?
if($_POST['action'] == 'edit')
{
	die('Is this working?');
}

output_head();

// The main tasks page.
$query = $db->simple_select('tasks','*');

if($db->num_rows($query) == 0)
{
	// Oh noes!  This person isn't doing anything!  They should make some cookies and mail them to me.  They can do so here: http://a1i.org/uIU5H
	echo 'You currently have no tasks.  Why not add one?';
} else {
	echo 'Double click any item to edit it.';
	echo '<table>';

	while($task = $db->fetch_array($query))
	{
		// Show the tasks!
		echo '<tr>';
		echo "<td id=\"name_{$task['tid']}\" class=\"edit_name\">{$task['name']}</td>";
		echo "<span id=\"comments_{$task['tid']}\" class=\"edit_comments\">{$task['comments']}</span>";
		echo '</tr>';
	}

	echo '</table>';
}




// Add a task!

output_foot();
?>