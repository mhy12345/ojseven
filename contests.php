<!DOCTYPE html>
<html>
<title>Oj7 Contest</title>
<?php include 'environment-init.php';?>
<?php include 'header.php';?>
<?php include 'oj7-functions.php';?>
<div class="container">
<button onclick='window.location.href="scoreboard-page.php"'>ScoreBoard</button>
<?php
include 'mysql-inital.php';
$result=mysql_query("SELECT * FROM contests ORDER BY id DESC");
echo "<table class=\"altrowstable\" id=\"alternatecolor\">";
echo "<tr><th width=30%>Contest id</th><th width=70%>Contest Name</th></tr>";
while ($row = mysql_fetch_array($result))
{
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td><a href='contest.php?name=".$row['name']."'>".$row['name']."</a></td>";
		echo "</tr>";
}
?>
</div><!--container-->
<script>
   window.onload=altRows("alternatecolor");
</script>
</html>

