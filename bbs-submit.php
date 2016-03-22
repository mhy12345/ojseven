<!DOCTYPE html>
<html>
   <?php 
	  include 'environment-init.php';
	  include 'mysql-inital.php';
	  include 'oj7-functions.php';
   ?>
   <?php
	  if (!isset($_SESSION['user']))
	  {
		 header("Location:error.php?error=You are forbidden.");
		 die();
	  }
	  if (isset($_POST['content']) and !empty($_POST['content']))
	  {
		 $ctype='text';
		 if (isset($_POST['language'])) $ctype=$_POST['language'];	
		 if ($_SESSION['ulevel'] <= 1 && $_POST['language']=='html')
		 {
			$ctype='text';
		 }
		 if ($ctype!='text' and $ctype!='html' and $ctype!='pascal' and $ctype!='c' and $ctype!='cpp' and $ctype!='latex' and $ctype!='java' and $ctype !='javascript' and $ctype!='python' and $ctype !='css')
		 $ctype='text';
		 if ($ctype == 'text' and strlen($_POST['content'])>1024) {
			header("Location:error.php?error=This message is too long for oj7. (limit:1KB)");
			die();
		 }
		 $result=mysql_query("SELECT * from bbs where user=".$_SESSION['user']." ORDER BY id DESC");
		 $result=mysql_fetch_array($result);
		 $tmp=30-(strtotime(date("Y-m-d H:i:s",time()))-strtotime($result['time']));
		 if ($tmp>0)
		 {
			echo '<script>window.locataion.href="Location:error.php?error=You are too fast! Please drink a cup of tea.('.$tmp.'s)"</script>';
			die();
		 }
		 mysql_query("INSERT bbs (user,type,problem) VALUES (".$_SESSION['user'].",'".$ctype."',".$_POST['problem'].")");
		 $bid=mysql_insert_id();
		 $sid=$bid;
		 $bid=get_file_name($bid);
		 mkdir("./bbs/".$bid."/");
		 $file=fopen("./bbs/".$bid."/content.txt","w");
		 fwrite($file,$_POST['content']);
		 fclose($file);
		 if ($ctype == 'text')
		 {
			preg_match_all('/@(\w)+/',$_POST['content'],$atcont);
			for ($i=0; $i<min(count($atcont[0]),5); $i++)
			{
			   $atname=substr($atcont[0][$i],1);
			   $res=mysql_query("SELECT * FROM users WHERE name=\"".$atname."\"");
			   $res=mysql_fetch_array($res);
			   if (!$res) continue;
			   mysql_query("INSERT oichat (user,receive,type) VALUES (".$_SESSION['user'].",".$res['id'].",'at')");
			   $cid=mysql_insert_id();
			   $cid=get_file_name($cid);
			   mkdir("./oichat/".$cid."/");
			   $file=fopen("./oichat/".$cid."/content.txt","w");
			   fwrite($file,$sid);
			   fclose($file);
			}
		 }

	  }
   ?>
   <script>
	  history.back();
   </script>
</html>
