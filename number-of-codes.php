<?php
include('config.php');

$connection = mysql_connect($host, $user, $password);
mysql_select_db($database) or die("Error selecting database!");
$result = mysql_query("SELECT * FROM games ORDER BY title");
$totalGames = mysql_num_rows($result);

$startingRow = 288;
$currentRow = 1;

while($row = mysql_fetch_array($result))
{
	if($currentRow >= $startingRow)
	{
		//echo $row['id'] . ".       ";
		echo "Processing row " . $currentRow . ".<br/>";
		$query = 'SELECT * FROM codes WHERE game_id = "' . $row['id'] . '";';
		$realQuery = mysql_query($query);
		$totalCodes = mysql_num_rows($realQuery);
		
		while($row2 = mysql_fetch_array($realQuery))
		{
			if($row2['code'] == "[header]")
				$totalCodes--;
		}
		
		mysql_query("UPDATE games SET numOfCodes = '" . $totalCodes . "' WHERE ID = '" . $row['id'] . "';");
		
		//echo $totalCodes . "<br />\n";
		$currentRow++;
	} else {
	$currentRow++;
	}
	set_time_limit(999);
}
?>