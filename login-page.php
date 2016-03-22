<!DOCTYPE html>
<html>
	<title> Login - Oj7 </title>
	<?php 
		include 'environment-init.php';
		include "header.php";
	?>
	<div class="container">
		<h1>Login</h1>
		<?php
			if (isset($_SESSION['name']))
			{
				echo '<script> window.location.href="error.php?error=You have already login!"; </script>';
				die();
			}
		?>
		<script>
			function vsubmit(thisform)
			{
					with (thisform)
					{
							var ulen=user_name.value.length;
							if (ulen<=3 || ulen>15)
							{
									window.location.href="error.php?error=Invalid user name!";
									return false;
							}
							for (var i=0;i<ulen;i++)
							{
									var c=user_name.value.charAt(i);
									if (!((c<='9' && c>='0') || (c<='Z' && c>='A') || (c<='z' && c>='a') || (c=='_')))
									{
											window.location.href="error.php?error=Invalid user name!";
											return false;
									}
							}
							//password.value=hex_md5("oj7"+password.value);
							return true;
					}
			}
		</script>
		<form action="login-action.php" id="ps" method="post">
			<table>
				<tr><td>user name:</td><td><input type="text" name="user_name" id="user_name"/></td></tr>
				<tr><td>password:</td><td><input type="password" name="password" id="password"/></td></tr>
			</table>
			<button value="submit">Submit</button>
		</form>
	</div>
</html>
