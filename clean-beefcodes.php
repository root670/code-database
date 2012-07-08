<?php
include_once('config.php');

$connection = mysql_connect($host, $user, $password);
mysql_select_db($database) or die("Error selecting database!");

$result = mysql_query("SELECT * FROM codes WHERE name = 'Enable Code (Must Be On)'");

//echo mysql_num_rows($result) . "rows were found.\n<br />";

$numBeefCodes = 0;

echo "Games with Long Enable Codes:<br /><br />";

while ($row = mysql_fetch_array($result))
{
	$enableCode = preg_split("/[\s,]+/", $row['code']);
	$firstAddress = $enableCode[0];
	if($firstAddress == "BEEFC0DE")
	{
		//echo $row['game_id'] . "-----";
		//$numBeefCodes++;
		//$realEnableCode = $enableCode[6] . " " . $enableCode[7] . "\n\r" . $enableCode[8] . " " . $enableCode[9] . "\n\r";
		if(isset($enableCode[10]))
		{
			echo $row['game_id'];
			echo '<br />';
		}
		//echo "<br />";
	}
	//echo $enableCode[1];
}
echo "beefcodes: " . $numBeefCodes;
echo "<br /><br />End of list";