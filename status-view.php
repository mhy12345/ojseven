<!DOCTYPE html>
<?php 
   include 'environment-init.php';
   include 'mysql-inital.php';
   include 'header.php';
   include 'oj7-functions.php';
?>
<html>
   <head>
	  <link href="include/prism.css" rel="stylesheet" />
	  <script src="include/prism.js"></script>
	  <script src="javascript/jquery.js"></script>
	  <script src="javascript/jquery.zclip.js"></script>
	  <style type="text/css">
		 .line{margin-bottom:20px;}
		 /* 复制提示 */
		 .copy-tips{position:fixed;z-index:999;bottom:50%;left:50%;margin:0 0 -20px -80px;background-color:rgba(0, 0, 0, 0.2);filter:progid:DXImageTransform.Microsoft.Gradient(startColorstr=#30000000, endColorstr=#30000000);padding:6px;}
		 .copy-tips-wrap{padding:10px 20px;text-align:center;border:1px solid #F4D9A6;background-color:#FFFDEE;font-size:14px;}
	  </style>
   </head>
   <body>
	  <?php
		 if (!isset($_GET['id']))
		 {
			echo '<script> window.location.href="error.php"; </script>';
			die();	
		 }
		 security($_GET['id'],0);
		 echo '<title>Status View: '.$_GET['id'].'</title>';
		 echo '<div class="container">';
			if (isset($_SESSION['admin']) and isset($_POST['rejudge']))
			{
			   mysql_query("UPDATE statuses SET result='Pending' WHERE id=".$_GET['id']);
			   echo "Rejudged";
			}
			if (isset($_SESSION['admin']) and $_SESSION['admin']==true)
			{
			?>
			<form method='post'>
			   <input type='submit'value='rejudge' name='rejudge' />
			</form>
			<?php
			}
			$result=mysql_query("SELECT * FROM statuses WHERE id=".$_GET['id']);
			$result=mysql_fetch_array($result);
			$slan=$result['language'];
			$result2=mysql_query("SELECT * FROM problems WHERE id=".$result['problem']);
			$result2=mysql_fetch_array($result2);
			if (!isset($_SESSION['user']) or $_SESSION['ulevel'] < $result2['level'] or ($_SESSION['user']!=$result['user'] and $_SESSION['admin']!=1 and ($result2['contest'] or $result2['submitoption']=='Waiting')))
			{
			   echo $_SESSION['admin'].$_SESSION['user'].$result['user'];
			   echo "<script> window.location.href='error.php?error=Permission Denied!".$_SESSION['user'].$result['user']."'; </script>";
			}
			if ($result2['contest']!=0 && $_SESSION['admin']!=1 && $_SESSION['user']!=$result['user'])
			{
			   $result3=mysql_query("SELECT * FROM contests WHERE id=".$result2['contest']);
			   $result3=mysql_fetch_array($result3);
			   if ($result3['status']=='Pending' || $result3['status']='On')
			   {
				  die();
			   }
			}
		 ?>
		 <?php
			if (!$result2['issubmit'])
			{
			   echo '<h2>Code For Problem #'.$result2['id'].': '.$result2['name'];
			   echo '</h2>';
			   //echo get_file_name($_GET['id']);
			   if ($slan=='C++')
			   {
			   ?>
			   <a href="#none" class="copy-input" style="color:blue">Copy</a>
			   <pre><code class='language-cpp line-numbers'><?php $code=file_get_contents("./status/".get_file_name($_GET['id'])."/pro.cpp");$code=htmlspecialchars($code);if (strlen($code)<102400)echo $code;	?></code></pre>
			   <?php
			   }
			   if ($slan=='C')
			   {
			   ?>
			   <a href="#none" class="copy-input" style="color:blue">Copy</a>
			   <pre><code class='language-c line-numbers'><?php $code=file_get_contents("./status/".get_file_name($_GET['id'])."/pro.c");$code=htmlspecialchars($code);if (strlen($code)<102400)echo $code;?></code></pre>
			   <?php
			   }
			   if ($slan=='Pascal')
			   {
			   ?>
			   <a href="#none" class="copy-input" style="color:blue">Copy</a>
			   <pre><code class='language-pascal line-numbers'><?php $code=file_get_contents("./status/".get_file_name($_GET['id'])."/pro.pas");$code=htmlspecialchars($code);if (strlen($code)<102400)echo $code;?></code></pre>
			   <?php
			   }
			?>
			<?php 
			} 
		 ?>
		 <h2>Details</h2>
		 <div class="content">
			<?php
			   if (!file_exists("./status/".get_file_name($_GET['id'])."/detail.txt"))
			   $code="Not Exist!\n";
			   else
			   $code=file_get_contents("./status/".get_file_name($_GET['id'])."/detail.txt");
			   $code=htmlspecialchars($code);
			   $code=str_replace("\n","<br/>",$code);
			   $code=str_replace("\t"," &nbsp &nbsp &nbsp &nbsp ",$code);
			   echo $code;
			   unset($code);
			?>
		 </div>
	  </div>
	  <script type='text/javascript'>
		 $(document).ready(function(){
			   /* 定义所有class为copy-input标签，点击后可复制后继元素文本*/
			   $(".copy-input").zclip({
					 path: "javascript/ZeroClipboard.swf",
					 copy: function(){
						   return $(this).next().text();
					 },
					 afterCopy:function(){/* 复制成功后的操作 */
						var $copysuc = $("<div class='copy-tips'><div class='copy-tips-wrap'>Okay</div></div>");
						$("body").find(".copy-tips").remove().end().append($copysuc);
						$(".copy-tips").fadeOut(3000);
				  }
			});
	  });
   </script>
</body>
 </html>

