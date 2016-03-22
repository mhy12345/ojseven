<!DOCTYPE html>
<html>
<?php include "environment-init.php";?>
<?php
function file_release($sourced,$sourcef)
{
	$dest="./files/".md5("lala".$sourced."/".$sourcef);
	if (file_exists($dest."/".$sourcef))
	{
			unlink($dest."/".$sourcef);
			rmdir($dest);
	}
}
include 'mysql-inital.php';
if (!$_SESSION['admin'])
{
		header("location:error.php?error=Permission denied");
		die();
}
function atrim($s)
{
		return str_replace(' ','',$s);
}
function get_problem($pid)
{
		$result=mysql_query("SELECT * FROM problems where id=".$pid);
		$result=mysql_fetch_array($result);
		return $result;
}
if (isset($_POST['problemname']))
		$result=mysql_query("UPDATE problems set name='".$_POST['problemname']."' WHERE id=".$_GET['id']);
if (isset($_POST['timelimit']))
		$result=mysql_query("UPDATE problems set timelimit='".$_POST['timelimit']."' WHERE id=".$_GET['id']);
if (isset($_POST['memorylimit']))
		$result=mysql_query("UPDATE problems set memorylimit='".$_POST['memorylimit']."' WHERE id=".$_GET['id']);
if (isset($_POST['source']))
		$result=mysql_query("UPDATE problems set source='".$_POST['source']."' WHERE id=".$_GET['id']);
if (isset($_POST['tags']))
		$result=mysql_query("UPDATE problems set tags='".$_POST['tags']."' WHERE id=".$_GET['id']);
if (isset($_POST['level']))
		$result=mysql_query("UPDATE problems set level='".$_POST['level']."' WHERE id=".$_GET['id']);
if (isset($_POST['rejudge']))
		$result=mysql_query("UPDATE statuses SET result='Pending' where problem=".$_GET['id']);
if (isset($_POST['rejudge AC']))
		$result=mysql_query("UPDATE statuses SET result='Pending' where result='Accept' AND problem=".$_GET['id']);
if (!empty($_FILES['data']['name']))
{
		if ($_FILES['data']['error'])
		{
				echo "ERROR:".$_FILES['data']['error']."<br/>";
				die();
		}
		$pid=$_GET['id'];
		move_uploaded_file($_FILES["data"]["tmp_name"],
				"problems/".$pid."/". $_FILES["data"]["name"]);
		exec("rm ./problems/".$pid."/data/*");
		exec("unzip ./problems/".$pid."/".$_FILES["data"]["name"]." -d ./problems/".$pid."/data/");
		chmod("./problems/".$pid."/".$_FILES['data']['name'],0640);
		exec("chmod 640 ./problems/".$pid."/data/*");
		//chmod("./problems/".$pid."/data/*",0640);
		echo "Data upload success!<br/>";
}
if (isset($_POST['data-configure']))
{
		$file=fopen("./problems/".$_GET['id']."/data.cfg","w");
		fwrite($file,$_POST['data-configure']);
		fclose($file);
		chmod("./problems/".$_GET['id']."/data.cfg",0640);
}
if (isset($_POST['ftarget']) and !empty($_FILES['cfile']['tmp_name']))
{
		if ($_FILES['cfile']['error'])
		{
				echo "ERROR:".$_FILES['cfile']['error']."<br/>";
				die();
		}
		$pid=$_GET['id'];
		if (file_exists("problems/".$pid."/".$_POST['ftarget']))
				file_release("problems/".$pid,$_POST['ftarget']);
		move_uploaded_file($_FILES["cfile"]["tmp_name"],
				"problems/".$pid."/". $_POST['ftarget']);
		echo "File upload success!<br/>";
}
$items=array("background","description","input format","output format","sample input","sample output","constraint","hint");
$change=false;
for ($i=0;$i<count($items);$i++)
{
		if (!isset($_POST[atrim($items[$i])]))continue;
		if (!is_dir("./problems/".$_GET["id"]))
				mkdir("./problems/".$_GET['id']);
		$ncontent=$_POST[atrim($items[$i])];
		$fname="./problems/".$_GET["id"]."/".str_replace(" ","",$items[$i]).".txt";
		if (($ncontent=="" or $ncontent==NULL) and file_exists($fname))
		{
				unlink($fname);
				continue;
		}
		if ($ncontent=="" or $ncontent==NULL)continue;
		$diff=false;
		if (!file_exists($fname))$diff=true;
		else
		{
				$content=file_get_contents($fname);
				if ($content!=$ncontent)$diff=true;
		}
		if (!$diff)continue;
		$change=true;
		$myfile=fopen($fname,"w");
		fwrite($myfile,$ncontent);
		fclose($myfile);
		chmod($fname,0640);
}
?>
<html>
<title>Edit</title>
<?php include 'header.php';?>
<div class="container">
<form method="post" enctype='multipart/form-data'>
<input formmethod='get' type='hidden' name='id' value='<?php echo $_GET['id'];?>'></input>
<a class='linker' href='problem.php?id=<?php echo $_GET['id'];?>'>Back To The Problem</a><br/>
<a class='linker' href='problem-edit.php?id=<?php echo $_GET['id']-1;?>'>[Prev]</a>
<a class='linker' href='problem-edit.php?id=<?php echo $_GET['id']+1;?>'>[Next]</a><br/>
<?php $res=get_problem($_GET['id']);?>
<button name='rejudge'>Rejudge</button>
<button name='rejudge AC'>Rejdge AC</button><br/>
Name:<input type='text' name='problemname'value='<?php echo $res['name']?>'></input><br/>
<?php
if (!isset($_SESSION['ulevel']))
{
		die();
}
echo "level:";
for ($i=1;$i<=min(4,$_SESSION['ulevel']);$i++)
{
		if ($res['level']==$i)
				$ck="checked";
		else
				$ck="";
		echo "<input type='radio' name='level' value=".$i." ".$ck.">".$i."</input>";
}
echo "<br/>";
?>
Timelimit:<input type='text' name='timelimit'value='<?php echo $res['timelimit']?>'></input><br/>
Memorylimit:<input type='text' name='memorylimit'value='<?php echo $res['memorylimit']?>'></input><br/>
Source:<input type='text' name='source' value='<?php echo $res['source']?>'></input><br/>
Tags:<input type='text' name='tags' value='<?php echo $res['tags']?>'></input><br/>
Data:<input type='file' name='data'></input><br/>
File Upload:<input type='file' name='cfile'></input>As:<input type='text' name='ftarget' value='problem.pdf'/><br/>
Data configure:<br/>
<textarea name='data-configure' style='width:200px;height:100px'>
<?php
$content=file_get_contents("./problems/".$_GET["id"]."/data.cfg");
echo $content;
?>
</textarea>
<input type=submit></input><br/>
<?php
for ($i=0;$i<count($items);$i++)
{
		$fname="./problems/".$_GET["id"]."/".str_replace(" ","",$items[$i]).".txt";
		echo "<h2>".ucwords($items[$i])."</h2>";
		echo "<textarea name=".atrim($items[$i])." style='width:600px;height:150px' id=".$items[$i].">";
		if (file_exists($fname))
		{
				$content=file_get_contents($fname);
				echo $content;
		}
		echo "</textarea>";
		echo "<input type=submit></input>";
}
?>
</form>
</div>
</html>

