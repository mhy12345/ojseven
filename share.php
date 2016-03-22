<!DOCTYPE html>
<html>
   <?php include 'environment-init.php'; 
	  include 'header.php'; 
	  include 'oj7-functions.php';
	  echo '<title> Share </title>';
	//echo "Sorry But Share Is Not Supported Currently!\n";
	//die();
	if (!isset($_SESSION['admin'])){
		die();
	}
	if (isset($_POST['ndir']))
	{
		for ($i=0;$i<strlen($_POST['ndir']);$i++)
		{
			$ch=$_POST['ndir'][$i];
			if ($ch<='z' and $ch>='a')continue;
			if ($ch<='Z' and $ch>='A')continue;
			if ($ch=='/' or $ch=='-' or $ch=='_')continue;
			echo "Unsafe char in directory name";
			die();
		}
		if (is_dir("./share/".$_POST['ndir']))
		{
			echo "Directory Arealdy Exists!";
			die();
		}
		mkdir ("./share/".$_POST['ndir']);
	}
	if (!empty($_FILES['file']['tmp_name']))
	{
		if (empty($_POST['tdir']))
		{
			echo "Choose A Directroy!";
			die();
		}
		for ($i=0;$i<strlen($_POST['tdir']);$i++)
		{
			$ch=$_POST['tdir'][$i];
			if ($ch<='z' and $ch>='a')continue;
			if ($ch<='Z' and $ch>='A')continue;
			if ($ch=='/' or $ch=='-' or $ch==' ' or $ch=='_')continue;
			echo "Unsafe char in directory name";
			die();
		}
		if (file_exists("share/".$_POST['tdir']."/".$_FILES["file"]["name"]))
		file_release("share/".$_POST['tdir'],$_FILES['file']['name']);
		move_uploaded_file($_FILES["file"]["tmp_name"],
		"share/".$_POST['tdir']."/". $_FILES["file"]["name"]);
		chmod("share/".$_POST['tdir']."/". $_FILES["file"]["name"],0600);
		$file=fopen("share/".$_POST['tdir']."/@level.txt","w");
		fwrite($file,"2");
		fclose($file);
	}
?>
<div class='container'>
	<h2>Files</h2>
	<div class='content'>
		<div id='directoryList'>
		</div>
	</div>
	<script>
		function showDirectory(a)
		{
				var xmlhttp;
				if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
						var x=document.getElementById("directoryList");
						x.innerHTML=xmlhttp.responseText;
				}
		}
		//document.write("ajax/fetch_files.php?Directory="+a);
		xmlhttp.open("GET","ajax/fetch_files.php?Directory="+a,true);
		xmlhttp.send();
	}
	function step_into(dir,a)
	{
			if (a=='.')
			return dir;
			if (a=='..')
			{
					if (dir=='share')return dir;
					while (dir.charAt(dir.length-1)!='/')
					dir=dir.substr(0,dir.length-1);
					dir=dir.substr(0,dir.length-1);
					if (dir=='')
					dir='share';
					showDirectory(dir);
			}else
			{
					dir=dir+'/'+a;
					showDirectory(dir);
			}
		}
		showDirectory("share");
	</script>
	<h2> File Upload</h2>
	<div class='content'>
		<form enctype='multipart/form-data' method='post'>
			Upload File:<input type='file' name='file' /><br/>
			Upload Directory:<input type='text' name='tdir'/><br/>
			<input type='submit' value='Upload'></input>
		</form>
	</div>
	<h2> Directory Make</h2>
	<div class='content'>
		<form method='post'>
			New Directory:<input type='text' name='ndir'/>
			<input type='submit' value='Submit'></input>
		</form>
	</div>
</div>
</html>
