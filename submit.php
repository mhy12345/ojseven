<?php 
   include 'environment-init.php';
   include 'oj7-functions.php';
   include 'header.php';
?>
<?php
   function get_extension($file){
	  return pathinfo($file, PATHINFO_EXTENSION);
   } 
?>
<?php
   if (!$_POST['problem'])
   {
	  echo '<script> window.location.href="error.php?error=how did you find this page?"; </script>';
	  die();
   }else if (!$_SESSION['user'])
   {
	  echo '<script> window.location.href="error.php?error=Please login!"; </script>';
	  die();
   }else
   {
	  security($_POST['problem'],0);
	  $pid=htmlspecialchars($_POST['problem']);
	  if (isset($_POST['code']))
	  $code=htmlspecialchars($_POST['code']);
	  if(!empty($_FILES['answer']['tmp_name']))
	  $_POST['language']="Text";
	  $result=mysql_query("SELECT * FROM problems WHERE id=".$_POST['problem']);
	  $result=mysql_fetch_array($result);
	  if (!$result)
	  {
		 echo "Problem Not Found!\n";
		 die();
	  }
	  if ($result['level']>$_SESSION['ulevel'])
	  {
		 echo "Permission Denied<br/>";
		 die();
	  }
	  $def_result=$result['submitoption'];
	  if (!empty($_FILES['code']['tmp_name']))
	  $scrlen=filesize($_FILES['code']['tmp_name']);
	  else
	  $scrlen=strlen($code);
	  if (!$result['contest'])
	  mysql_query("INSERT INTO statuses (user,problem,codelength,result,language) VALUES (".$_SESSION['user'].",".$_POST['problem'].",".$scrlen.",'Waiting','".$_POST['language']."')");
	  else
	  mysql_query("INSERT INTO statuses (user,problem,codelength,result,contest,language) VALUES (".$_SESSION['user'].",".$_POST['problem'].",".$scrlen.",'Waiting',".$result['contest'].",'".$_POST['language']."')");
	  $sid=mysql_insert_id();
	  $floder_name=get_file_name($sid);
	  mkdir("./status/".$floder_name,0777,true);
	  exec("chmod 750 ./status/".$floder_name);
	  //echo "chmod 750 ./status/".$floder_name;
	  if ($_POST['language']=='C++')
	  $filename="pro.cpp";
	  else if ($_POST['language']=='C')
	  $filename="pro.c";
	  else if ($_POST['language']=='Pascal')
	  $filename="pro.pas";
	  else 
	  $filename="answer.zip";
	  if (!empty($_FILES['code']['tmp_name']))
	  {
		 move_uploaded_file($_FILES["code"]["tmp_name"],
		 "./status/".$floder_name."/".$filename);
	  }elseif(!empty($_FILES['answer']['tmp_name']))
	  {
		 if (get_extension($_FILES['answer']['name'])!="zip")
		 {
			echo "Zip Only";
			die();
		 }
		 move_uploaded_file($_FILES["answer"]["tmp_name"],
		 "./status/".$floder_name."/answer.zip");
	  }else
	  {
		 $myfile=fopen("./status/".$floder_name."/".$filename,"w");
		 fwrite($myfile,$_POST['code']);
		 fclose($myfile);
	  }
	  exec("chmod 644 ./status/".$floder_name."/".$filename);
	  exec("touch ./status/".$floder_name."/detail.txt");
	  exec("chmod 664 ./status/".$floder_name."/detail.txt");
	  exec("touch ./status/".$floder_name."/result.txt");
	  exec("chmod 664 ./status/".$floder_name."/result.txt");
	  mysql_query("UPDATE statuses SET result='".$def_result."' WHERE id=".$sid);
	  echo '<script> window.location.href="status.php"; </script>';
   }
?>
