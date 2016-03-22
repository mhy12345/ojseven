<!DOCTYPE html>
<html>
   <?php include 'environment-init.php';?>
   <title>Register to OjSeven</title>
   <?php include "header.php"?>
   <?php 
	  include 'oj7-functions';
	  echo "please contact maohanyang789@163.com";
	  die();
	  if (isset($_POST['user_name']) and strlen($_POST['user_name'])>3 and strlen($_POST['user_name'])<=15 and isset($_POST['password']))
	  {
		 security($_POST['user_name'],1);
		 security($_POST['password'],1);
		 $result=mysql_query("SELECT * from users where name=\"".$_POST["user_name"]."\"");
		 $result=mysql_fetch_array($result);
		 if ($result)
		 {
			echo '<script> window.location.href="error.php?error=user '.$_POST["user_name"].' already exists."; </script>';
			die();
		 }
		 $res=mysql_query("INSERT INTO users (name,password,level) values (\"".$_POST["user_name"]."\",\"".$_POST["password"]."\",2)");
		 //echo "INSERT INTO users (name,password) values (\"".$_POST["user_name"]."\",\"".$_POST["password"]."\")";
		 if ($res)echo "<h1> Success! </h1>";
		 else echo "<h1> Not success! </h1>";
		 die();
	  }
   ?>
   <script>
	  function vsubmit(thisform)
	  {
		 with(thisform)
		 {
			if(user_name.value.length>15)
			{
			   window.location.href="error.php?error=Length of user_name should not exceed 15!";
			   return false;
			}
			if(user_name.value.length<=3)
			{
			   window.location.href="error.php?error=Length of user_name should exceed 3!";
			   return false;
			}
			for (var i=0;i<user_name.value.length;i++)
			{
			   var c=user_name.value.charAt(i);
			   if (!((c<='9' && c>='0') || (c<='Z' && c>='A') || (c<='z' && c>='a') || (c=='_')))
			   {
				  window.location.href="error.php?error=User's name can only contain '0-9' 'a-z' 'A-Z' '_' ";
				  return false;
			   }
			}
			if (password.value!=password2.value)
			{
			   window.location.href="error.php?error=Please Enter the same password!";
			   return false;
			}
			var passwd=hex_md5("oj7"+password.value);
			password.value=passwd;
			password2.value=passwd;
		 }
		 return true;
	  }
   </script>
   <div style="margin:auto;width:90%">
	  <h1>Register Information</h1>
	  <form id="ps" onsubmit="return vsubmit(this)" method="post">
		 <table>
			<tr><td>user name:</td><td><input type="text" name="user_name" id="user_name"/></td></tr>
			<tr><td>password:</td><td><input type="password" name="password" id="password"/></td></tr>
			<tr><td>repeat password:</td><td><input type="password" name="password2" id="password2"/></td></tr>
		 </table>
		 <button value="submit">Submit</button>
	  </form>
   </div>
</html>
