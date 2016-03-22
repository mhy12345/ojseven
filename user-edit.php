<!DOCTYPE html>
<html>
   <?php
	  include 'environment-init.php';
	  include 'header.php';
	  include 'oj7-functions.php';
	  if ((!isset($_SESSION['user'])) or (!isset($_GET['id'])) or ($_GET['id']!=$_SESSION['user']))
	  {
		 echo '<script> window.location.href="error.php?error=Permission Denied!"; </script>';
		 die();
	  }
	  security($_GET['id'],0);
	  if (isset($_POST['realname']))
	  {
		 security($_POST['realname'],2);
		 mysql_query("UPDATE users SET realname='".$_POST['realname']."' WHERE id=".$_GET['id']);
		 unset($_POST['realname']);
	  }
	  if (isset($_POST['colortheme']))
	  {
		 security($_POST['colortheme'],1);
		 mysql_query("UPDATE users SET colortheme='".$_POST['colortheme']."' WHERE id=".$_GET['id']);
		 $_SESSION['colortheme']=$_POST['colortheme'];
		 unset($_POST['colortheme']);
		 if (isset($_POST['danmuku']))
		 $_SESSION['danmuku']=1;
		 else
		 $_SESSION['danmuku']=0;
		 mysql_query("UPDATE users SET danmuku=".$_SESSION['danmuku']." WHERE id=".$_GET['id']);
		 unset($_POST['danmuku']);
		 echo '<script> window.location.href="user-edit.php?id='.$_GET['id'].'; </script>';
	  }
	  $result=mysql_query("SELECT * FROM users WHERE id=".$_GET['id']);
	  $result=mysql_fetch_array($result);
   ?>
   <div class="container">
	  <h1> User Edit</h1>
	  <form method='post'>
		 Color Theme:<input type='text' name='colortheme' value='<?php echo $result['colortheme']?>'/><br/>
		 Real Name:<input type='text' name='realname' value='<?php echo $result['realname']?>'/>（格式：英文拼音，姓+“ ”+名，姓名首字母大写）<br/>
		 Enable Danmaku:<input type='checkbox' name='danmuku'<?php if ($result['danmuku']) echo " checked='checked'"; ?>></br>
		 <input type='submit'/>
	  </form>
	  <div class='content'>
		 <?php include 'style/theme-list.html';?>
	  </div>

   </div>
</html>
