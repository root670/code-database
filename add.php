<!DOCTYPE html>
<html>
<head>
	<title>Add</title>
	<link rel=stylesheet type='text/css' href='style.css' />
</head>
<body>
<a href='./'>&larr;Game List</a>
<?php
include('config.php');

if($adminMode)
{
	if (isset($_GET['title'])) // Add a game to the list
	{
		$name = $_GET['title'];
		
		$valid = true;
		
		if (strlen($name) <= 0)
		{
			echo('<br />ERROR: No title was entered!');
			$valid = false;
		}
			
		switch($_GET['system'])
		{
			case 'PlayStation 2':
				$system = "PS2";
				break;
			case 'Dreamcast':
				$system = "DC";
				break;
			default:
				echo("<br />ERROR: Invalid system selected!");
				$valid = false;
				break;
		}
		
		if($valid == true) // Name was entered and system is valid
		{
			echo "<h1>Code Database</h1>";
			mysql_connect('localhost', $user, $password);
			mysql_select_db($database) or die("Error selecting database!");
			mysql_query("INSERT INTO games (title, system) VALUES (\"" . htmlspecialchars($name) . "\", '$system');");
			echo "Added the game <em>$name</em> to the database";
		}
	}
	// TODO: put the following in a seperate file
	else if (isset($_POST['gameid'])) // Add a code to a game
	{
		echo "<h1>Adding a code...</h1>";
		$gameId = mysql_real_escape_string($_POST['gameid']);
		$codeName = mysql_real_escape_string($_POST['codeName']);
		$code = mysql_real_escape_string($_POST['code']);
		$note = mysql_real_escape_string($_POST['note']);
		$credit = mysql_real_escape_string($_POST['credit']);
		
		if ((strlen($gameId) <= 0) || (strlen($codeName) <= 0) || (strlen($code) <= 0))
		{
			die("ERROR: You didn't enter all the required information.");
		}
		
		mysql_connect($host, $user, $password);
		mysql_select_db($database) or die("Error selecting database!");
		mysql_query("INSERT INTO codes (game_id, name, code, note, credit) VALUES ('$gameId', '$codeName', '$code', '$note', '$credit');");
		die("<script language='javascript'>window.history.back(-1); </script>Added the code <em>$codeName</em> to the database.");
	}
echo "<h1>Add a Game</h1>
	<form action=add.php method=get>
		Title: <input type=text name=title /><br />
		System: 
		<select name=system>
			<option>PlayStation 2</option>
			<option>DC</option>
		</select><br />
		<input type=submit />
	</form>";
}
else
{
	echo "<br />ERROR: adminMode is off. You shouldn't be here :(";
}
?>
</body>
</html>