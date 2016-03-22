<!DOCTYPE html>
<html>
	<?php 
		include 'environment-init.php';
		include 'mysql-inital.php';
		include 'oj7-functions.php';
		if (isset($_GET['bbsid']) and !empty($_GET['bbsid']))
		{
		    security($_GET['bbsid'],0);
			if (!isset($_SESSION['user']))
			{
				header("Location:error.php?error=You cannot delete this message.");
				die();
			}
			$result=mysql_query("SELECT * FROM bbs WHERE id=".$_GET['bbsid']);
			$result=mysql_fetch_array($result);
			if (((!isset($_SESSION['admin'])) or ($_SESSION['admin']!=1)) and $result['user'] != $_SESSION['user'])
			{
				header("Location:error.php?error=You cannot delete this message.");
				die();
			}
			mysql_query("UPDATE bbs set type='del' WHERE id=".$_GET['bbsid']);
		}
		else
		{
			header("Location:error.php?error=How did you find this page?");
			die();
		}
	?>
	<script>
		history.back();
	</script>
</html>
