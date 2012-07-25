<!DOCTYPE html>
<html>
<head>
	<title>Code Database</title>
	<link rel=stylesheet type='text/css' href='style.css' />
</head>
<body>
<div class='main'>
<?php
include('config.php');

if (!isset($_GET['gameid'])) // Show list of games
{
	echo "<h1 style='text-align: center'>Code Database</h1>";
	$connection = mysql_connect($host, $user, $password);
	mysql_select_db($database) or die("Error selecting database!");

	$result = mysql_query("SELECT * FROM games ORDER BY title");

	if(!$result) die("No games in database!");
	echo "<p>There are <strong>" . mysql_num_rows($result) . "</strong> games in the database. All codes are in RAW format unless otherwise noted. All of these codes should work with CodeBreaker v7.1+ without any changes.</p>\n";
	echo "<table border=1>";

	while($row = mysql_fetch_array($result))
	{
		$gameTitle = $row['title'];
		echo "<tr><td><a href=\"?gameid=" . $row['id'] . "&amp;title=" . urlencode(htmlspecialchars($gameTitle)) . "\">";
		echo $gameTitle . "</a></td><td>" . $row['system'] . "</td>";
		echo "<td>" . $row['numOfCodes'] . "</td></tr>";
	}
	echo "</table>";
	
	if($adminMode)
		echo "<br /><a href='add.php'>*Add a game*</a>";
		
	mysql_close($connection);
}

else if (isset($_GET['gameid'])) // Show codes for specific game
{
	echo "<a href='./'>&larr;Game List</a>";
	echo "<h2 style='text-align: center'>" . $_GET['title'] . "</h2>";
	$connection = mysql_connect($host, $user, $password);
	mysql_select_db($database) or die("Error selecting database!");
	
	$query = 'SELECT * FROM codes WHERE game_id = "' . mysql_real_escape_string($_GET['gameid']) . '"'; // Only get codes with a specific gameid
	$result = mysql_query($query);
	
	$curRow = 0;
	if($showNumbers)
		echo("\n\n<table border='1px'>\n<th></th><th>Name</th><th>Code</th>");
	else
		echo("\n\n<table border='1px'>\n<th>Name</th><th>Code</th>");
	
	if($showNotes)
		echo("<th>Note</th>");
	if($showCredit)
		echo("<th>Credit</th>");
		
	while($row = mysql_fetch_array($result))
	{
		$curRow++;
		echo "\n<tr>";
		
		if($row['code'] != "[header]")
		{
			if($showCredit)
			{
				if (strlen($row['credit']) <= 0)
					$credit = "Unknown";
				else
					$credit = $row['credit'];
			}
			
			if($showNumbers)
				echo "<td>$curRow</td>";
			echo "<td>" . $row['name'] . "</td><td class=code>" . nl2br($row['code']) . "</td>"; // nl2r converts NULL characters to line breaks
			if($showNotes)
				echo "<td>" . nl2br($row['note']) . "</td>";
			if($showCredit)
				echo "<td>" . $credit . "</td>";
		}
		else
		{
			echo "<td class=codeHeader colspan=5>" . $row['name'] . "</td>";
			$curRow--;
		}
		echo "</tr>";
	}
	echo ("\n</table>");
	if ($adminMode)
	{
		echo ("<h3>Add a code for this game</h3>
		<p>To add multiple codes to this game, <a href='mass-add.php?gameid=" . mysql_real_escape_string($_GET['gameid']) . "'>click here</a>.</p>
		<form action='add.php' method=post>
			<input hidden=true name=gameid value=" . mysql_real_escape_string($_GET['gameid']) . " />
			<table id=addGame>
				<tr>
					<td>Code Name</td>
					<td><input type=text name='codeName' /></td>
				</tr>
				<tr>
					<td>Code</td>
					<td><textarea name='code' rows=3 cols=18></textarea></td>
				</tr>
				<tr>
					<td>Note (optional)</td>
					<td><input type=text name='note' /></td>
				</tr>
				<tr>
					<td>Credit (optional)</td>
					<td><input type=text name='credit' /></td>
				</tr>
				<tr>
					<td colspan=2><input type=submit value='Add' style='width: 100%;' /></td>
				</tr>
			</table>
		</form>\n");
	}
	mysql_close($connection);
}
?>
</div>
</body>
</html>