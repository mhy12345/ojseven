<?php
function get_score($ccc)
{
		list($a,$b)=split("/",$ccc);
		if ($a==0 and $b==0)return 0;
		return 100/$b*$a;
}
include '../mysql-inital.php';
$result0=mysql_query("SELECT * FROM schedule WHERE activate_time<=now() AND done=FALSE AND action='acm-updatescore'");
while ($row0=mysql_fetch_array($result0))
{
		$cres=mysql_query("SELECT * FROM contests WHERE id=".$row0['value']);
		$cres=mysql_fetch_array($cres);
		$cid=$row0['value'];
		$cname=$cres['name'];
		$cnt=$cres['totp'];
		$tname="Contest_Ranklist_".$cid;
		$result=mysql_query("SHOW TABLES LIKE '".$tname."'");
		$result=mysql_fetch_array($result);
		if ($result)
				mysql_query("DROP TABLE ".$tname);
		if ($cnt==3)
				mysql_query("CREATE TABLE ".$tname."(uid int primary key,rank int,score int,s1 int,s2 int,s3 int)");
		else if ($cnt==4)
				mysql_query("CREATE TABLE ".$tname."(uid int primary key,rank int,score int,s1 int,s2 int,s3 int,s4 int)");
		$result=mysql_query("SELECT * FROM statuses WHERE contest=".$cid);
		while ($row=mysql_fetch_array($result))
		{
				$result2=mysql_query("SELECT * FROM ".$tname." WHERE uid=".$row['user']);
				$result2=mysql_fetch_array($result2);
				if (!$result2)
						mysql_query("INSERT ".$tname." (uid) VALUES (".$row['user'].")");
				for ($i=1;$i<=$cnt;$i++)
						if ($cres['p'.$i]==$row['problem'])
								mysql_query("UPDATE ".$tname." set s".$i."=".get_score($row['score'])." WHERE uid=".$row['user']);
		}
		$result=mysql_query("SELECT * FROM ".$tname);
		$lastscr=-1;
		$cntc=0;$lastcnt=0;
		while ($row=mysql_fetch_array($result))
		{
				$sum=0;
				for ($i=1;$i<=$cnt;$i++)
						$sum+=$row['s'.$i];
				mysql_query("UPDATE ".$tname." set score=".$sum." WHERE uid=".$row['uid']);
		}
		$result=mysql_query("SELECT * FROM ".$tname." ORDER BY score DESC");
		while ($row=mysql_fetch_array($result))
		{
				$cntc++;
				$sum=$row['score'];
				if ($lastscr==$sum)
				{
						mysql_query("UPDATE ".$tname." set rank=".$lastcnt." WHERE uid=".$row['uid']);
				}
				else
				{
						mysql_query("UPDATE ".$tname." set rank=".$cntc." WHERE uid=".$row['uid']);
						$lastcnt=$cntc;
				}
				$lastscr=$sum;
		}
		mysql_query("UPDATE contests SET participants = ".$cntc." WHERE id=".$cid);
		mysql_query("UPDATE schedule SET done=true WHERE id=".$row0['id']);
		mysql_query("INSERT INTO schedule (action,activate_time,value) VALUES ('acm-updatescore',DATE_ADD(now(),INTERVAL 1 MINUTE),".$cid.")");
}
?>
