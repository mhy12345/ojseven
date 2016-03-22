<!DOCTYPE html>
<html>
	<?php 
		include 'environment-init.php';
		include 'mysql-inital.php';
		include 'oj7-functions.php';
	?>
	<?php
		if ((!isset($_SESSION['user'])) or (!isset($_GET['id'])) or !is_numeric($_GET['id']))
		{
			echo '<script> window.location.href="error.php?error=You are forbidden."; </script>';
			die();
		 }
		security($_GET['id'],0);
		$result=mysql_query("select * from oichat where id=".$_GET['id']);
		$row=mysql_fetch_array($result);
		if ((!$row) or ($row['user']!=$_SESSION['user']))
		{
			echo '<script> window.location.href="error.php?error=You are forbidden."; </script>';
			die();
		}
		mysql_query("update oichat set type='back' where id=".$_GET['id']);
	?>
	<script>
		history.back();
	</script>
</html>
