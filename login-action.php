<!DOCTYPE html>
<?php include 'environment-init.php';
   include 'oj7-functions.php';?>
<html>
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<div class="container">
		<?php
			include 'mysql-inital.php';
			if (!$_POST['user_name'])
			{
			   echo '<script> window.location.href="error.php?error=Please input username!"; </script>';
				die();
			}else
			{
				security($_POST['user_name'],1);
				security($_POST['password'],1);
				//echo "Name:".$_POST['user_name'];
				//echo "Password:".$_POST['password'];
				$name=$_POST['user_name'];
				for ($i=0;$i<strlen($name);$i++)
				{
					if (($name[$i]>='0' and $name[$i]<='9') or ($name[$i]>='a' and $name[$i]<='z' ) or ($name[$i]>='A' && $name[$i]<='Z') or ($name[$i]=='_'))continue;
					echo "Unsafe User Name";
					die();
				}
				$result=mysql_query("SELECT * FROM users WHERE name=\"".$_POST['user_name']."\"");
				$result=mysql_fetch_array($result);
				if (!$result)
				{
				   echo '<script>window.location.href="error.php?error=No such user!"</script>';
					die();
				}
				//echo $_POST['password']." ".$_POST['remember'];
				$_POST['password']=md5("oj7".$_POST['password']);
				if ($result['password']!=$_POST['password'])
				{
				   echo '<script>window.location.href="error.php?error=Wrong Password!"</script>';
					die();
				}
				$_SESSION['name']=$_POST['user_name'];
				$_SESSION['user']=$result['id'];
				$_SESSION['admin']=$result['admin'];
				$_SESSION['colortheme']=$result['colortheme'];
				$_SESSION['ulevel']=$result['level'];
				$_SESSION['danmuku']=$result['danmuku'];
				$res=mysql_query("SELECT * FROM users WHERE id=".$_SESSION['user']);
				$res=mysql_fetch_array($res);
				mysql_query("UPDATE users SET lastlogin='".date('Y-m-d h:i:s')."' WHERE id=".$_SESSION['user']);
				echo '<script> window.location.href="home.php"; </script>';
			}
		?>
	</div>
</html>
