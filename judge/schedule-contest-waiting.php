<?php
include '../mysql-inital.php';
$result=mysql_query("SELECT * FROM schedule WHERE activate_time<=now() AND done=FALSE AND action='contest-waiting'");
while ($row=mysql_fetch_array($result))
{
		$cres=mysql_query("SELECT * FROM contests WHERE id=".$row['value']);
		$cres=mysql_fetch_array($cres);
		$cid=$row['value'];
		mysql_query("UPDATE statuses SET result='Waiting' WHERE contest=".$cid);
		mysql_query("UPDATE schedule SET done=true WHERE id=".$row['id']);
}
?>
