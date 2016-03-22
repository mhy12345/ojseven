<!DOCTYPE html>
<html>
   <?php 
	  include 'environment-init.php';
	  include 'header.php';
	  include 'oj7-functions.php';
   ?>
   <script type="text/javascript" src="javascript/MathJax-master/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
   <script type="text/x-mathjax-config">
	  MathJax.Hub.Config({
			tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
	  });
   </script>
   <!--<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>-->
   <?php
	  $pid=$_GET["id"];
	  if (!$pid){
		 echo "No Such Problem";
		 die();
	  }
	  security($_GET['id'],0);
	  $result=mysql_query("select * from problems where id=".$pid);
	  $result=mysql_fetch_array($result);
	  $pname=$result['name'];
	  $pinfo=$result;
	  if (!isset ($_SESSION['ulevel']) or $_SESSION['ulevel']<$result['level'])
	  {
		 echo "Please contact maohanyang789@163.com!<br/>";
		 die();
	  }
	  $cinfo=get_contest_info_by_problem_id($pid);
	  if (isset($cinfo) and $cinfo['type']=='RP++' and $cinfo['status']!='Finish' and !$_SESSION['admin'])
	  {
		 $result=mysql_query("SELECT * FROM Contest_RPPP_".$cinfo['id']."_Participants WHERE user='".$_SESSION['user']."'");
		 $result=mysql_fetch_array($result);
		 if (!$result)die();
		 if ($result['current']!=NULL and $result['current']!=$pid)
		 {
			echo "You Don't Have Permission To View This Page";
			echo "<a href='problem.php?id=".$result['current']."'>Here</a>";
			die();
		 }else if ($result['current']==NULL)
		 {
			$result=mysql_fetch_array(mysql_query("SELECT * FROM Contest_RPPP_".$cinfo['id']."_Opened WHERE problem=".$pid." AND user='".$_SESSION['user']."'"));
			if ($result)
			{
			   echo "You've already open this task";
			   die();
			}
			mysql_query("UPDATE Contest_RPPP_".$cinfo['id']."_Participants SET current=".$pid." WHERE user='".$_SESSION['user']."'");
			mysql_query("INSERT INTO Contest_RPPP_".$cinfo['id']."_Opened (problem,user) VALUES (".$pid.",".$_SESSION['user'].")");
		 }
	  }
	  if (isset($cinfo) and isset($_POST['score']))
	  {
		 security($_POST['score'],0);
		 if ($_POST['score']<0||$_POST['score']>$cinfo['totp']*100)
		 {
			echo '<center><h2>Do not be so frustrated!</h2></center>';
			die();
		 }
		 $file=fopen('./contests/'.$cinfo['name'].'/'.$_SESSION['user'].'.txt',"w");
		 fwrite($file,$_POST['score']);
		 fclose($file);
	  }
	  if (isset($cinfo) and $cinfo['estimate']==1)
	  {
		 if (!file_exists('./contests/'.$cinfo['name'].'/'.$_SESSION['user'].'.txt'))
		 {
		 ?>
		 <div class='container'>
			<div class='content'>
			   <form method='post'>
				  <h3><center>
						Before entering this contest, <br/>you have to input your estimated SUM SCORE FOR ALL PROBLEMS IN THIS CONTEST :<br/><br/>
						<input type='text' name='score' style='width:100px; height:50px; font-size:50px'/><br/><br/>
						<input type='Submit' value='我就是这么强！' style='width:160px; height:50px; font-size:20px; font-weight:800'/>
					 </h3>
				  </center>
			   </form>
			</div>
		 </div>
		 <?php
			include 'footer.php';
			die();
		 }
	  }
   ?>
   <title><?php echo $pname;?></title>
   <div class="container">
	  <h1><?php echo $pname;?></h1>
	  <div class='problem-info'>
		 <?php
			$file=fopen("./problems/".$pid."/data.cfg","r");
			$content=fgets($file);
			$content=split(" ",$content);
			$vv=$content[1]-$content[0]+1;
			if ($pinfo['issubmit'] and $pinfo['isspj'])
			{
			   echo "Answer Special Judge<br/>";
			   $content=fgets($file);
			   $content=fgets($file);
			   echo "Submit Format:".$content."<br/>";
			   echo "Total Cases:".$vv."<br/>";
			   echo "<b>答案提交提请提交zip文件，不内建任何子文件夹</b><br/>";
			}
			else
			{
			   $content=fgets($file);$content=fgets($file);
			   echo "Time Limit:".($pinfo['timelimit'])."ms<br/>";
			   echo "Memory Limit:".($pinfo['memorylimit']*1024)."kb<br/>";
			   $content=fgets($file);
			   if ($content=='stdin')$content='Standard Input';
			   echo "Input:".$content."<br/>";
			   $content=fgets($file);
			   if ($content=='stdout')$content='Standard Output';
			   echo "Output:".$content."<br/>";
			   echo "Total Cases:".$vv."<br/>";
			}
		 ?>
		 <?php 
			if (isset($_SESSION['admin']))
			echo "<a class='linker' href='problem-edit.php?id=".$_GET['id']."'>edit</a><br/>";
		 ?>
		 <script>
			function tags_display()
			{
			   var tmp=document.getElementById('tags');
			   if (tmp.style.display=='none')
			   tmp.style.display='';
			   else
			   tmp.style.display='none';
			}
		 </script>
		 <a class='linker' onclick='return tags_display()'>tags</a> 
		 <span id='tags' style='display: none;'>
			<?php 
			   if (!empty($result['tags']))
			   echo $result['tags'];
			   else
			   echo "No Tags Here";
			?>
		 </span>
	  </div>
	  <?php
		 if (file_exists("./problems/".$_GET['id']."/user.zip"))
		 {
			$destf=file_transfer("./problems/".$_GET['id'],"user.zip");
			echo "<a class='linker' href='".$destf."'>user.zip</a><br/>";
		 }
		 if (file_exists("./problems/".$_GET['id']."/problem.pdf"))
		 {
			$destf=file_transfer("./problems/".$_GET['id'],"problem.pdf");
			echo "<embed width=90% height=120% src='".$destf."'></embed><br/>";
		 }
		 if (file_exists("./problems/".$_GET['id']."/problem.doc"))
		 {
			$destf=file_transfer("./problems/".$_GET['id'],"problem.doc");
			echo "<a class='linker' href='".$destf."'>problems</a><br/>";
		 }
		 if (file_exists("./problems/".$_GET['id']."/problem.docx"))
		 {
			$destf=file_transfer("./problems/".$_GET['id'],"problem.docx");
			echo "<a class='linker' href='".$destf."'>problems</a><br/>";
		 }
		 if (file_exists("./problems/".$_GET['id']."/problem.zip"))
		 {
			$destf=file_transfer("./problems/".$_GET['id'],"problem.zip");
			echo "<a class='linker' href='".$destf."'>problems</a><br/>";
		 }
		 $items=array("background","description","input format","output format","sample input","sample output","constraint","hint");
		 for ($i=0;$i<count($items);$i++)
		 {
			if (file_exists("./problems/".$pinfo["id"]."/".str_replace(" ","",$items[$i]).".txt")){
			   $content=file_get_contents("./problems/".$pinfo["id"]."/".str_replace(" ","",$items[$i]).".txt");
			   $content=str_replace("\n","<br/>",$content);
			   echo "<h2>".ucwords($items[$i])."</h2>";
			   echo "<div class=content>";
				  echo "<table style='word-break:break-all'><tr><td>";
						   echo $content;
						   echo "</td></tr></table>";
				  echo "</div>";
			}
		 }
	  ?>
	  <br/>
	  <center>
		 [<?php echo '<a href="submit-page.php?id='.$_GET['id'].'">Submit</a>';?>]
		 [<?php echo '<a href="bbs.php?id='.$_GET['id'].'">Discuss</a>';?>]
		 <br/>
	  </center>
   </div>
   <?php include'footer.php'?>
</html>
