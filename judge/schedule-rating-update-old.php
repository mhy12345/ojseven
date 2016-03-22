<?php
include '../mysql-inital.php';
$result0=mysql_query("SELECT * FROM schedule WHERE activate_time<=now() AND done=FALSE AND action='rating-update'");
while ($row0=mysql_fetch_array($result0))
{
		$cres=mysql_query("SELECT * FROM contests WHERE id=".$row0['value']);
		$cres=mysql_fetch_array($cres);
		$cid=$row0['value'];
		$cname=$cres['name'];
		$cnt=$cres['totp'];
		$result=mysql_query("SELECT * FROM users WHERE realname IS NOT NULL");
		while ($rowuser=mysql_fetch_array($result))
		{
				$flag=0;
				$val=1500;
				$uid=$rowuser['id'];
				$tname="User_Rating_".$uid;
				$result2=mysql_query("SHOW TABLES LIKE '".$tname."'");
				$result2=mysql_fetch_array($result2);
				if ($result2)
						mysql_query("DROP TABLE ".$tname);
				mysql_query("CREATE TABLE ".$tname." (id int primary key auto_increment,contest int,rating int)");
				mysql_query("INSERT INTO ".$tname." (contest,rating) VALUES (0,1500)");
				$result2=mysql_query("SELECT * FROM contests WHERE israted != 0");
				while ($row2=mysql_fetch_array($result2))
				{
						$cid=$row2['id'];
						$totp=$row2['participants'];
						$result3=mysql_query("SELECT * FROM Contest_Ranklist_".$cid." WHERE uid=".$uid);
						$result3=mysql_fetch_array($result3);
						if (!$result3)continue;
						$flag=1;
						$val=$val*0.95+(150-$result3['rank']/$totp*150);
						mysql_query("INSERT INTO ".$tname." (contest,rating) VALUES (".$cid.",".intval($val).")");
						//echo $val."<br/>";
				}
				mysql_query("UPDATE users SET rating = ".intval($val*$flag)." WHERE id=".$uid);
		}
		mysql_query("UPDATE schedule SET done=true WHERE id=".$row0['id']);
}
?>
