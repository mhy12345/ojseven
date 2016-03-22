<?php include '../mysql-inital.php';?>
<?php include '../oj7-functions.php';?>
<?php ob_start();?>
<!DOCTYPE html>
<style><?php include '../style/style-template.css';?></style>
<?php include '../style/table.php';?>
<?php
$result0=mysql_query("SELECT * FROM schedule WHERE activate_time<=now() AND done=FALSE AND action='contest-export-score'");
while ($row0=mysql_fetch_array($result0))
{
		$cres=mysql_query("SELECT * FROM contests WHERE id=".$row0['value']);
		$cres=mysql_fetch_array($cres);
		$cid=$row0['value'];
		$cname=$cres['name'];
		$cnt=$cres['totp'];
		$tname="Contest_Ranklist_".$cid;
		$result=mysql_query("SELECT * FROM ".$tname." ORDER BY score DESC");
?>
<div class='container'>
<title>Contest <?php echo $cname;?> Scoreboard</title>
<h1>Contest <?php echo $cname;?> Scoreboard</h1>
<table class='altrowstable' id='alternatecolor'>
<tr><th width=10%> Rank </th><th width=20%> User </th><th width=15%> Score </th>
<?php
		for ($i=1;$i<=$cnt;$i++)
				echo "<th width=".(55/$cnt)."%>".get_problem_name_by_id($cres['p'.$i])."</th>";
		while ($row=mysql_fetch_array($result))
		{
				echo "<tr>";
				echo "<td>".$row['rank']."</td>";
				echo "<td>".get_user_full_name_by_id($row['uid'])."</td>";
				echo "<td>".$row['score']."</td>";
				for ($i=1;$i<=$cnt;$i++)
				{
						if ($row['s'.$i]!=NULL)
								$res1=$row['s'.$i];
						else
								$res1='-';
						$result2=mysql_query("SELECT * FROM statuses WHERE user=".$row['uid']." AND problem=".$cres['p'.$i]." ORDER BY id DESC LIMIT 0,1");
						//echo "SELECT * FROM statuses WHERE user=".$row['uid']." AND problem=".$cres['p'.$i]." ORDER BY id DESC LIMIT 0,1";
						$result2=mysql_fetch_array($result2);
						if ($result2['score']!=NULL)
								$res2=intval(get_score($result2['score']));
						else
								$res2='-';
						echo "<td>".$res1."(".$res2.")</td>";
				}
				echo "</tr>";
		}
?>
</table>
</div>
<script>window.onload=altRows("alternatecolor");</script>
<?php
		mysql_query("UPDATE schedule SET done=true WHERE id=".$row0['id']);
		if (file_exists("../contests/".$cname."/score.html"))
				file_release("../contests/".$cname,"score.html");
		$file=fopen("../score/".$cname."-score.html","w");
		fwrite($file,ob_get_contents());
		fclose($file);
		die();
}
?>
