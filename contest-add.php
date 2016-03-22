<!DOCTYPE html>
<?php 
   include 'environment-init.php';
   include 'mysql-inital.php';
   include 'header.php';
?>
<?php
   if (!$_SESSION['admin'])
   {
	  echo '<script> window.location.href="error.php?error=Access denied!"; </script>';
	  die();
   }
?>
<html>
   <div class="container">
	  <?php 
		 echo "Checking Problem List...<br/>";
		 $cname=$_POST['contest_name'];
		 $cnt=$_POST['total_problems'];
		 $result=mysql_query("SELECT * FROM contests WHERE name='".$cname."'");
		 $result=mysql_fetch_array($result);
		 if ($result)
		 {
			echo "Contest Already Set!";
			die();
		 }
		 for ($i=1;$i<=$cnt;$i++)
		 {
			if (!$_POST['problem'.$i])
			{
			   echo "Problem No.".$i." is empty";
			   die();
			}
			$result=mysql_query("SELECT * FROM problems WHERE id=".$_POST['problem'.$i]);
			$result=mysql_fetch_array($result);
			if (!$result)
			{
			   echo "Can't Find Problem No.".$_POST['problem'.$i];
			   die();
			}
		 }
		 $fname='NULL';
		 mysql_query("INSERT contests (name,totp,taskfile,type,level) VALUES ('".$cname."',".$cnt.",'".$fname."','".$_POST['contest_type']."',".$_POST['clevel'].")");
		 $cid=mysql_insert_id();
		 mkdir("./contests/".$cname."/");
		 echo "Checking Problem File...<br/>";
		 if (empty($_FILES['pfile']['name'])){
			echo "<span style='color:yellow'>No File Upload</span><br/>";
		 }else
		 {
			if ($_FILES['pfile']['error']){
			   echo "ERROR:".$_FILES['pfile']['error']."<br/>";die();
			}
			$fname=$_FILES['pfile']['name'];
			echo "Upload: " . $_FILES['pfile']['name'] . "<br />";
			echo "Type:  ". $_FILES['pfile']['type'] . "<br />";
			echo "Size: " . ($_FILES['pfile']['size'] / 1024) . " Kb<br />";
			echo "Temporary stored in: " . $_FILES['pfile']['tmp_name'] ."<br/>";
			move_uploaded_file($_FILES["pfile"]["tmp_name"],
			"contests/".$cname."/".$fname);
			echo "Stored in: " . "contests/".$cname."/".$fname."<br/>";
			mysql_query("UPDATE contests SET taskfile='".$fname."' WHERE id=".$cid);;
			chmod("contests/".$cname."/".$fname,0640);
		 }
		 for ($i=1;$i<=$cnt;$i++)
		 {
			mysql_query("UPDATE contests SET p".$i."=".$_POST['problem'.$i]." WHERE id=".$cid);
			mysql_query("UPDATE problems SET contest=".$cid." WHERE id=".$_POST['problem'.$i]);
		 }
		 $GLOBALS['currentcontest']=$cid;
		 if ($_POST['contest_type']=="RP++")
		 {
			mysql_query("CREATE TABLE Contest_RPPP_".$cid."_Participants(user int,current int)");
			mysql_query("CREATE TABLE Contest_RPPP_".$cid."_Opened(user int,problem int)");
		 }
		 if (isset($_POST['estimate']))
		 {
			mysql_query("update contests set estimate=1 where id=".$cid);
		 }
	  ?>
   </div>
</html>
