<!DOCTYPE html>
<html>
   <?php 
	  include 'environment-init.php';
	  include 'mysql-inital.php';
	  include 'oj7-functions.php';
   ?>
   <?php
	  if ((!isset($_SESSION['user'])) or !isset($_POST['id']))
	  {
		 echo '<script> window.location.href="error.php?error=You are forbidden."; </script>';
		 die();
	  }
	  security($_POST['id'],0);
	  if (isset($_POST['content']) and !empty($_POST['content']))
	  {
		 $bid=0;
		 if (isset($_POST['type']) and !empty($_POST['type']))
		 {
			echo $_POST['type']." ".$_POST['id'];
			if ($_POST['type']=='imp')
			{
			   if (strlen($_POST['type'])>128)
			   {
				  echo '<script> window.location.href="error.php?error=Your danmaku is too long."; </script>';
				  die();
			   }
			   $result=mysql_query("select * from oichat where user=".$_SESSION['user']." and receive=".$_POST['id']." and type='imp'");
			   if ($result=mysql_fetch_array($result))
			   $bid=$result['id'];
			   else
			   {
				  mysql_query("INSERT oichat (user,receive,type) VALUES (".$_SESSION['user'].",".$_POST['id'].",'imp')");
				  $bid=mysql_insert_id();
			   }
			}
		 }
		 else
		 {
			mysql_query("INSERT oichat (user,receive) VALUES (".$_SESSION['user'].",".$_POST['id'].")");
			$bid=mysql_insert_id();
		 }
		 $bid=get_file_name($bid);
		 if (!is_dir("./oichat/".$bid."/"))
		 mkdir("./oichat/".$bid."/");
		 $file=fopen("./oichat/".$bid."/content.txt","w");
		 fwrite($file,$_POST['content']);
		 fclose($file);
	  }
	  $result=mysql_query("select * from oichat where (user=".$_SESSION['user']." and receive=".$_POST['id'].") or (user=".$_POST['id']." and receive=".$_SESSION['user'].") order by id desc limit 50,1;");
	  if ($row=mysql_fetch_array($result))
	  mysql_query("delete from oichat where ((user=".$_SESSION['user']." and receive=".$_POST['id'].") or (user=".$_POST['id']." and receive=".$_SESSION['user'].")) and id<".$row['id']);
   ?>
   <script>
	  history.back();
   </script>
</html>
