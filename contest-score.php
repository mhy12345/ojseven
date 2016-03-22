<!DOCTYPE html>
<html>
<?php include 'environment-init.php'?>
<title>Oj7 Contest</title>
<?php include 'header.php';
include 'mysql-inital.php';
include 'oj7-functions.php';
function get_user_name($uid)
{
	$result=mysql_query("SELECT * FROM users WHERE id='".$uid."'");
	$result=mysql_fetch_array($result);
	return $result['name']."(".$result['realname'].")";
}
function get_problem_name($pid)
{
	$result=mysql_query("SELECT * FROM problems WHERE id='".$pid."'");
	$result=mysql_fetch_array($result);
	return $result['name'];
}
if (!isset($_GET['id']))
{
	echo '<script> window.location.href="error.php"; </script>';
	die();
}
$cid=$_GET['id'];
$result=mysql_query("SELECT * FROM contests WHERE id='".$cid."'");
$conres=mysql_fetch_array($result);
$cname=$conres['name'];
$cnt=$conres['totp'];
$tname="Contest_Ranklist_".$cid;
$result=mysql_query("SELECT * FROM ".$tname." ORDER BY score DESC");
if (!$result)
{
	echo "ScoreBoard Not Exists<br/>";
	die();
}
?>
<div class='container'>
<h2>Contest <?php echo $cname;?> Scoreboard</h2>
<table class='altrowstable' id='alternatecolor'>
<tr>
   <th width=5%> Rank </th>
<th width=20%> User </th>
<th width=10%> Score </th>
<?php
   if ($conres['estimate']==1)
   echo '<th width=10%> Estimate </th>';
for ($i=1;$i<=$cnt;$i++)
echo "<th width=".(55/$cnt)."%>".get_problem_name($conres['p'.$i])."</th>";
while ($row=mysql_fetch_array($result))
{
	echo "<tr>";
	echo "<td>".$row['rank']."</td>";
	echo "<td>".get_user_name($row['uid'])."</td>";
	echo "<td>".$row['score']."</td>";
	if ($conres['estimate']==1)
	{
	   if (!file_exists("./contests/".$cname."/".$row['uid'].".txt"))
	   $content=0;
	   else
	   $content=file_get_contents("./contests/".$cname."/".$row['uid'].".txt");
	   $cinfo=$row['score']-$content;
	   if ($cinfo>=0)
	   $cinfo='+'.$cinfo;
	   echo '<td>'.$content.'('.$cinfo.')</td>';
	}
	for ($i=1;$i<=$cnt;$i++)
	{
		if ($row['s'.$i]!=NULL)
			$res1=$row['s'.$i];
		else
			$res1='-';
		$result2=mysql_query("SELECT * FROM statuses WHERE user=".$row['uid']." AND problem=".$conres['p'.$i]." ORDER BY id DESC LIMIT 0,1");
		//echo "SELECT * FROM statuses WHERE user=".$row['uid']." AND problem=".$conres['p'.$i]." ORDER BY id DESC LIMIT 0,1";
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
<script>window.onload=altRows("alternatecolor");</script>
<?php
include 'footer.php';
?>
</div>
