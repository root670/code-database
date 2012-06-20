<?php
include('config.php');

echo "<!DOCTYPE html>
<html>
<head>
<title>Code Database</title>
<style type='text/css'>
body
{
	font-family: Verdana, Arial, Helvetica, sans-serif;
}

td, th {
	vertical-align: top;
}

.code {
	font-family: monospace;
	font-size: larger;
}
</style>
</head>
<body>
";

$user = 'root';
$password = '';
$database = 'codedb';

if (!isset($_GET['gameid']))
{
	echo "<h1>Code Database</h1>";
	mysql_connect('localhost', $user, $password);
	mysql_select_db($database) or die("Error selecting database!");

	$result = mysql_query("SELECT * FROM games ORDER BY title");

	if(!$result) die("No games in database!");
	echo "<p>There are <strong>" . mysql_num_rows($result) . "</strong> games in the database.</p>";

	$curRow = 0;

	while($row = mysql_fetch_array($result))
	{
		$curRow++;
		$gameTitle = $row['title'];
		echo "<a href=\"?gameid=" . $row['id'] . "&title=" . html_entity_decode($gameTitle) . "\">";
		echo "$curRow. " . $gameTitle . " (" . $row['system'] . ")" . "<br />";
		echo "</a>";
	}
	if($adminMode)
		echo "<br /><a href='add.php'>*Add a game*</a>";
}

else if (isset($_GET['gameid'])) // Go here after selecting a game from the list
{
	echo "<a href='./'>&larr;Game List</a>";
	echo "<h1>Codes for " . $_GET['title'] . "</h1>";
	mysql_connect('localhost', $user, $password);
	mysql_select_db($database) or die("Error selecting database!");
	
	$query = 'SELECT * FROM codes WHERE game_id = "' . mysql_real_escape_string($_GET['gameid']) . '"'; // Only get codes with a specific gameid
	$result = mysql_query($query);
	
	$curRow = 0;
	
	echo("\n\n<table border='1px'>\n<th colspan=2>Name</th><th>Code</th><th>Note</th><th>Credit</th>");
	while($row = mysql_fetch_array($result))
	{
		$curRow++;
		echo "\n<tr>";
		
		if (strlen($row['credit']) <= 0)
		{
			$credit = "Unknown";
		} else
		{
			$credit = $row['credit'];
		}
		
		echo "<td>$curRow</td><td>" . $row['name'] . "</td><td class=code>" . nl2br($row['code']) . "</td><td>" . nl2br($row['note']) . "</td><td>" . $credit . "</td>"; //nl2r converts NULL characters to line breaks
		echo "</tr>";
	}
	echo ("\n</table>");
	if ($adminMode)
	{
	echo ("<h3>Add a code for this game</h3>
	<form action='add.php' method=post>
		<input hidden=true name=gameid value=" . mysql_real_escape_string($_GET['gameid']) . " />
		<table>
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
}
?>
</body>
</html>