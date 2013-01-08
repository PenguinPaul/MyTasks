<?php

if(!defined('WHATAMI'))
{
	die('This MyTasks install file cannot be accessed directly.');
}

// MyTasks only needs one tiny table... woohoo!
//Let's make this baby!

echo "Preparing to create table {$db->table_prefix}tasks... <br />";
if(!$db->table_exists('tasks'))
{
	$db->query("CREATE TABLE IF NOT EXISTS `{$db->table_prefix}tasks` (
	  `tid` int(11) NOT NULL AUTO_INCREMENT,
	  `type` int(11) NOT NULL,
	  `priority` int(11) NOT NULL,
	  `name` text CHARACTER SET latin1 NOT NULL,
	  `comments` text CHARACTER SET latin1 NOT NULL,
	  `created` int(11) NOT NULL,
	  `at` int(11) NOT NULL,
	  PRIMARY KEY (`tid`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

	echo 'Table created successfully!<br />';
} else {
	echo 'MyTasks seems to already be installed.  Aborting install. <br />';
}

?>