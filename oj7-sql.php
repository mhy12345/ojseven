<!DOCTYPE html>
<html>
<?php
include 'environment-init.php';
include 'mysql-inital.php';
include 'oj7-functions.php';
include 'header.php';
if (!isset($_SESSION['user']) or $_SESSION['user']!=2)
{
		die();
}
?>
<form method='post'>
<input type='text' name='cmd'/>
<input type='submit'/>
</form>
<?php
if (isset($_POST['cmd']))
{
		$result=mysql_query($_POST['cmd']);
		echo mysql_error();
		while ($row=mysql_fetch_array($result))
		{
				while (list($a,$b) = each($row))
						echo " ".$a."->".$b." ";
				echo "<br/>";
		}
}
?>
</html>
