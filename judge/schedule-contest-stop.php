
<?php
include '../mysql-inital.php';
$result=mysql_query("SELECT * FROM schedule WHERE activate_time<=now() AND done=FALSE AND action='contest-stop'");
while ($row=mysql_fetch_array($result))
{
		$cres=mysql_query("SELECT * FROM contests WHERE id=".$row['value']);
		$cres=mysql_fetch_array($cres);
		$cid=$row['value'];
		$cname=$cres['name'];
		$cnt=$cres['totp'];
		for ($i=1;$i<=$cnt;$i++)
		{
				mysql_query("UPDATE problems SET submitoption='Pending' WHERE id=".$cres['p'.$i]);
				mysql_query("UPDATE problems SET contest=0 WHERE id=".$cres['p'.$i]);
		}
		mysql_query("UPDATE contests SET status='Finish' WHERE id=".$cid);
		mysql_query("UPDATE schedule SET done=true WHERE id=".$row['id']);
		mysql_query("INSERT INTO schedule (action,value) VALUES ('contest-pending',".$cid.")");
}
?>
