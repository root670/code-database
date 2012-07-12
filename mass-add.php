<!DOCTYPE html>
<html>
<head>
	<title>Mass Add</title>
	<link rel=stylesheet type='text/css' href='style.css' />
</head>
<body>
<div class='main'>
<h2>Mass Add</h2>
<p>This will allow you to quickly add multiple codes to a game. Simply paste your codes into the textbox below, formatted like this:<br /><br />
Cheat 1 Name<br />
12345678 12345678<br />
<br />
Cheat 2 Name<br />
12345678 12345678<br />
12345678 12345678<br />
<br />
etc. Make sure that each cheat is seperated by a blank line.<br />
<strong>NOTE: There is currently a bug and the list must end with a blank line!</strong><br /></p>
<form action=mass-add.php method=post>
	<table id=addGame>
		<tr>
			<td>Game ID</td>
		</tr>
		<tr>
		<?php
		if( isset($_GET['gameid']))
			echo "<td><input type=text name=gameid value=" . mysql_real_escape_string($_GET['gameid']) ." /></td>";
		else
			echo "<td><input type=text name=gameid /></td>";
		?>
		</tr>
		<tr>
			<td>Cheats</td>
		</tr>
		<tr>
			<td><textarea rows=25 cols=25 name=cheats></textarea></td>
		</tr>
		<tr>
			<td><input type=submit value=Add style='width:100%;' /></td>
		</tr>
	</table>
</form>
<?php
include('config.php');

class CheatCode {
	public $name;
	public $codes;
	
	public function SetName($newName)
	{
		$this->name = $newName;
	}
	
	public function GetName()
	{
		return $this->name;
	}
	
	public function AddCode($newCode)
	{
		$this->codes = $this->codes . $newCode . "\n";
	}
	
	public function GetCodes()
	{
		return $this->codes;
	}
}

if($adminMode)
{
	if (isset($_POST['gameid']) & isset($_POST['cheats']))
	{
		$connection = mysql_connect($host, $user, $password);
		
		$gameId = mysql_real_escape_string($_POST['gameid']);
		$cheats = mysql_real_escape_string($_POST['cheats']);
		$numberOfCheats = 0;
		$numberOfCodes = 0;
		
		if ((strlen($gameId) <= 0) || (strlen($cheats) <= 0))
		{
			die("ERROR: You didn't enter all the required information.");
		}
		
		mysql_select_db($database) or die("Error selecting database!");
		$result = mysql_query("SELECT * from games where id = '" . $gameId . "';");
		if( mysql_num_rows($result) <= 0 )
			die("GameID '" . $gameId . "' could not be found in the database.");
			
		$gameName = mysql_fetch_row($result);
		$gameName = $gameName[1];
		
		$cheatsArray = explode("\\n", $cheats); // Seperate each line of text that was entered
		
		$cheatCode = new CheatCode();
		$allCheatCodes = array();
		
		for($x = 0; $x < (sizeof($cheatsArray)); $x++)
		{
			if($x != (sizeof($cheatsArray)) - 1)
				$cheatsArray[$x] = substr_replace($cheatsArray[$x] ,"",-2); // Remove the \r's

			if($cheatsArray[$x] != '') // Not a blank line
			{
				if(strlen($cheatsArray[$x]) == 17) // code line
				{
					$cheatCode->AddCode($cheatsArray[$x]);
					$numberOfCodes++;
				}
				else // cheat name
				{
					$cheatCode->SetName($cheatsArray[$x]);
					$numberOfCheats++;
				}
			}
			else
			{
				if(strlen($cheatCode->GetCodes()) > 0) //ignore blank lines
					$allCheatCodes[] = $cheatCode; // add the new cheat code to an array
				
				unset($cheatCode); //We don't need it anymore
				$cheatCode = new CheatCode();
				//echo "<br />new cheat yoo<br />";
			}
		}
		
		foreach($allCheatCodes as $cheatCode)
		{
			mysql_query("INSERT INTO codes (game_id, name, code) VALUES ('" . $gameId . "', '" . $cheatCode->GetName() . "', '" . $cheatCode->GetCodes() . "');");
		}
		
		// Update numOfCodes to reflect additions
		mysql_query("UPDATE games SET numOfCodes=numOfCodes+$numberOfCheats WHERE id=$gameId;");
		
		echo("<br />Added " . $numberOfCheats . " cheats for " . $gameName . " to the database.<br />");
		echo("Number of Cheats Added: " . $numberOfCheats);
		echo("<br />Total code lines: " . $numberOfCodes);		
		
		mysql_close($connection);
	}
}
?>
</div>
</body>
</html>