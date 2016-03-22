<!DOCTYPE html>
<html>
	<?php 
		include "environment-init.php";
		include 'header.php';
		include 'oj7-functions.php';
	?>
	<div class='container'>
		<?php
			if (!$_SESSION['admin'])
			{
				echo '<script> window.location.href="error.php?error=Permission denied"; </script>';
				die();
			}
			$result=mysql_query("SELECT * FROM contests WHERE id=".$_GET['id']);
			$result=mysql_fetch_array($result);
			$cname=$result['name'];
			echo "<h1>".$result['name']."</h1>";
			if (!empty($_FILES['file']['name']))
			{
				if (isset($_POST['isproblem']) and $_POST['isproblem']=='on')
				mysql_query("UPDATE contests SET taskfile = '".$_POST['target']."'");
				if (file_exists("contests/".$cname."/".$_POST['target']))
				{
					echo "File exists!!<br/>";
					exec("rm contests/".$cname."/".$_POST['target']);
					file_release("./contests/".$cname,$_POST['target']);
				}
				move_uploaded_file($_FILES["file"]["tmp_name"],
				"contests/".$cname."/".$_POST['target']);
				echo "File upload success!<br/>";
			}
		?>
		<form method='post' enctype='multipart/form-data'>
			File Upload:<input type='file' name='file'/>
			Target:<input type='text' name='target' value='pack.zip'/><br/>
			<input type='checkbox' name='isproblem'> taskfile </input>
			<input type='submit'/>
		</form>
	</div>
</html>

