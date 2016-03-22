<!DOCTYPE html>
<html>
	<?php include 'environment-init.php';?>
	<title>Password Change</title>
	<?php include "header.php";
	   include 'oj7-functions.php';
	   if (!isset($_SESSION['user']) or !isset($_GET['user']) or $_SESSION['user']!=$_GET['user'])
		{
			echo "Permission Denied<br/>";
			die();
		}
		if (isset($_POST['new_passwd']))
		{
		    security($_POST['new_passwd'],1);
			$result=mysql_query("SELECT * from users where id=\"".$_SESSION["user"]."\"");
			$result=mysql_fetch_array($result);
			if ($result['password']!=$_POST['old_passwd'])
			{
				echo "Wrong Password!<br/>";
				die();
			}
			$res=mysql_query("UPDATE users SET password='".$_POST["new_passwd"]."' WHERE id=".$_SESSION['user']);
			echo "OK! Password Changed.";
			die();
		}
	?>
	<script>
		function vsubmit(thisform)
		{
				with(thisform)
				{
						if (new_passwd.value!=password2.value)
						{
								window.location.href="error.php?error=Please Enter the same password!";
								return false;
						}
						var passwd=hex_md5("oj7"+new_passwd.value);
						new_passwd.value=passwd;
						password2.value=passwd;
						passwd=hex_md5("oj7"+old_passwd.value);
						old_passwd.value=passwd;
					}
					return true;
				}
			</script>
			<div style="margin:auto;width:90%">
				<h1>Change password</h1>
				<form id="ps" onsubmit="return vsubmit(this)" method="post">
				   <input type='hidden' name='user' value='<?php echo $_GET['user'];?>' />
					<table>
						<tr><td>old password:</td><td><input type="password" name="old_passwd" id="old_passwd"/></td></tr>
						<tr><td>password:</td><td><input type="password" name="new_passwd" id="new_passwd"/></td></tr>
						<tr><td>repeat password:</td><td><input type="password" name="password2" id="password2"/></td></tr>
					</table>
					<button value="submit">Submit</button>
				</form>
			</div>
		</html>
