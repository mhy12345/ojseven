<!DOCTYPE html>
<html>
<?php include 'environment-init.php'?>
<?if (!isset $_SESSION['admin'];)die();?>
<?php
if (empty($_FILES['file']['tmp_name']))
{
		echo "No File Upload...<br/>";
		die();
}
include 'mysql-inital.php';
move_uploaded_file($_FILES["file"]["tmp_name"],
		"uploads/".$_POST['file-type']."/".$_FILES['file']['name']);
echo "uploads/".$_POST['file-type']."/".$_FILES['file']['name'];
mysql_query("INSERT uploads (name,type) VALUES ('".$_FILES['file']['name']."','".$_POST['file-type']."')");
header("location:home.php");
?>
</html>
