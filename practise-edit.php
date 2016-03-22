<!DOCTYPE html>
<?php include 'environment-init.php'?>
<?php include 'header.php'?>
<?php include 'mysql-inital.php'?>
<html>
<head>
<?php
if (!isset($_SESSION['admin']) or !$_SESSION['admin'])
{
		echo "Permission denied.";
		die();
}
if (isset($_POST['newgroup']))
{
		$result=mysql_query("SELECT * FROM practise WHERE name='".$_POST['newgroup']."'");
		if (mysql_num_rows($result)>0)
		{
				echo "Same name existed";
				die();
		}
		mysql_query("INSERT INTO practise (name) VALUE ('".$_POST['newgroup']."')");
		$gid=mysql_insert_id();
		mysql_query("DROP TABLE practise_".$gid." IF EXIST");
		mysql_query("CREATE TABLE practise_".$gid." (id int primary key auto_increment,problem int)");
		echo "CREATE TABLE practise_".$gid." (id int primary key auto_increment,problem int)";
}
if (isset($_POST['group']))
{
		$lst=explode(',',$_POST['problemlist']);
		for ($i=0;$i<count($lst);$i++)
		{
				if ($lst[$i]=="")
				{
						array_remove($lst,$i);
						$i--;
				}
		}
		if (count($lst)==0)
		{
				echo "No Problem Found.<br/>";
				die();
		}
		$result=mysql_query("SELECT * FROM practise WHERE id=".$_POST['group']);
		if (mysql_num_rows($result)==0)
		{
				echo "Cannot Find Group:".$_POST['group'];
				die();
		}
		for ($i=0;$i<count($lst);$i++)
		{
				$result=mysql_query("SELECT * FROM problems WHERE id=".$lst[$i]);
				if (mysql_num_rows($result)==0)
						echo "Cannot Find Problem ".$lst[$i]."<br/>";
				mysql_query("INSERT INTO practise_".$_POST['group']." (problem) VALUES (".$lst[$i].")");
				echo "INSERT INTO practise_".$_POST['group']." (problem) VALUES (".$lst[$i].")";
		}
}
?>
<form method='post'>
Add Practise:<input name='newgroup' type='text'/><br/>
<input type='submit'/>
</form>
<form method='post'>
Add Problem:<input name='problemlist' type='text'/> Into Group No.<input name='group' type='text'/><br/>
<input type='submit'/>
</form>
</head>
</html>
