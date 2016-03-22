<?php
include '../mysql-inital.php';
$result=mysql_query("SHOW TABLES LIKE 'Contest_Scoreboard%'");
while ($row=mysql_fetch_array($result))
{
		$tname=$row['Tables_in_oj7database (Contest_Scoreboard%)'];
		mysql_query("DROP TABLE ".$tname);
}
?>
