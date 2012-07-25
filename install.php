<!DOCTYPE html>
<html>
<head>
	<title>Install</title>
	<link rel=stylesheet type='text/css' href='style.css' />
</head>
<body>
<div class='main'>
<h2>Install</h2>
<?php

if( isset($_GET['install']))
{
	echo "<p>Installing...</p>";
	
	include('config.php');
	
	$init = 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";';
	$gamesTable = 'CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `system` text NOT NULL,
  `numOfCodes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1487 ;';
	$codesTable = 'CREATE TABLE IF NOT EXISTS `codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `code` text NOT NULL,
  `note` text NOT NULL,
  `credit` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=168001 ;';

	$connection = mysql_connect($host, $user, $password);
	mysql_select_db($database) or die("Error selecting database! Does it exist?");
	
	mysql_query($init) or die("An error occured.");
	mysql_query($gamesTable) or die("An error occured.");
	mysql_query($codesTable) or die("An error occured.");
	
	echo("<p>Database has been installed. Click <a href=index.php>here</a> to go to the front page.</p>");
}
else
{
?>
<p>Please edit config.php with your database server's information. The name that you choose must already exist. Only MySQL servers are usable for now.<p>
<p>When you're sure the settings are correct, click the button below to set up a blank database</p>
<form action=install.php method=get>
	<input type=submit value="Begin Installation" name=install />
</div>
<?php } ?>
</body>
</html>