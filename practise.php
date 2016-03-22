<!DOCTYPE html>
<html>
<?php include 'environment-init.php';?>
<?php include 'mysql-inital.php';?>
<title>Oj7 Practise</title>
<!--<link rel="stylesheet" href="style.css" type="text/css"/>-->
<?php include 'header.php';?>
<?php include 'oj7-functions.php';?>
<div class="container">
<script>
function change_status(id)
{
		var target=document.getElementById(id);
		if (target.style.display=='none')
		{
				target.style.display='';
		}else
		{
				target.style.display='none';
		}
}
</script>
<?php
if (!isset($_SESSION['user']))
{
		die();
}
$result=mysql_query("SELECT * FROM practise");
while ($row=mysql_fetch_array($result))
{
?>
	<hr/>
	<h2 onclick='change_status("pset<?php echo $row['id'];?>")'><?php echo $row['name']?></h2>
	<div id='pset<?php echo $row['id'];?>' style='display:none' >
<?php

		$result2=mysql_query("SELECT * FROM practise_".$row['id']);
		if (!$result2){
				echo "nothing";
				continue;
		}
?>
	<table class="altrowstable" id="alternatecolor<?php echo $row['id']?>">
	<tr><th width=10%>Status</th><th width=5%>Id</th><th width=10%>Problem id</th><th width=25%>Name</th><th width=30%>Source</th><th width=10%>AC</th><th width=10%>Submit</th></tr>
<?php
		$cnt=0;
		$cntac=0;
		while ($row2=mysql_fetch_array($result2))
		{
				echo "<tr>";
				$cnt++;
				$result3=mysql_query("SELECT count(*) FROM statuses WHERE result='Accept' and problem=".$row2['problem']." and user=".$_SESSION['user']);
				$result3=mysql_fetch_array($result3);
				if ($result3['count(*)']>0)
				{
						echo "<td style='color:blue'>Accept</td>";
						$cntac++;
				}
				else
						echo "<td> </td>";
				echo "<td>".$cnt."</td>";
				echo "<td>".$row2['problem']."</td>";
				$result3=mysql_query("SELECT name,source FROM problems WHERE id=".$row2['problem']);
				$result3=mysql_fetch_array($result3);
				echo "<td><a href='problem.php?id=".$row2['problem']."'>".$result3['name']."</a></td>";
				echo "<td>".$result3['source']."</td>";
				$result3=mysql_query("SELECT count(*) FROM statuses WHERE result='Accept' and problem='".$row2['problem']."'");
				$result3=mysql_fetch_array($result3);
				$cntAC=$result3['count(*)'];
				$result3=mysql_query("SELECT count(*) FROM statuses WHERE problem='".$row2['problem']."'");
				$result3=mysql_fetch_array($result3);
				$cntSub=$result3['count(*)'];
				echo "<td>".$cntAC."</td>";
				echo "<td>".$cntSub."</td>";
				echo "</tr>";
		}
?>
	</table>
	</div>
<?php
echo "Finish:".$cntac;
}
?>
<hr/>
</div><!--container-->
<?php
$result=mysql_query("SELECT * FROM practise");
while ($row=mysql_fetch_array($result))
echo '<script>window.onload=altRows("alternatecolor'.$row['id'].'");</script>';
?>
</html>

