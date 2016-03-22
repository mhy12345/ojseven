<!DOCTYPE html>
<html>
	<?php 
		include 'environment-init.php';
		include 'header.php';
		include 'oj7-functions.php';
	?>
   <style type="text/css">
	  .danmu {
			position:fixed;
			color:#fff;
			width:auto;
			font-size:40px;
			font-family:"黑体";
			font-weight:bold;
			text-shadow: 2px 0px 0px #000,
			-2px 0px 0px #000,
			0px 2px 0px #000,
			0px -2px 0px #000;
			visibility:hidden;
			white-space:nowrap;
	  }
   </style>
   <script type="text/javascript" src="javascript/MathJax-master/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
	<script type="text/x-mathjax-config">
		MathJax.Hub.Config({
				tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
		});
	</script>
   <script>
	  $(document).ready(function(){
			var width=window.outerWidth;
			var height=window.outerHeight;
			//var deltax=-4;
			var sum=Math.min(10,$(".danmu").length);
			var cnt=0;
			$(".danmu").each(function(){
				var el=this;
				var deltax=-($(el).width()+width)/500;
				var myOffset=new Object();
				var l=cnt++;
				myOffset.top=(l%10)/sum*height*0.6+height*0.2;
				myOffset.left=width;
				$(el).offset(myOffset);
				setTimeout(function(){
					$(el).css("visibility","visible");
					setInterval(function(){
						  l=$(el).offset().left;
						  if (l<-$(el).width())
						  $(el).remove();
						  myOffset.left=l+deltax;
						  $(el).offset(myOffset);
					},20);
			  },Math.floor(l/10)*5000+Math.random()*3000);
			});
	  });
   </script>
	<script>
	function printUser(rat)
	{
		if (rat>=1800)
			document.write("Legendary Grandmaster");
		else if (rat>=1700)
			document.write("International Master");
		else if (rat>=1550)
			document.write("Candidate Master");
		else if (rat>=1450)
			document.write("Expert");
		else if (rat>=1300)
			document.write("Specialist");
		else if (rat>0)
			document.write("Pupil");
		else
			document.write("Newbie");
	}
 </script>
 <?php
 ?>
 <div class="container">
	<?php
			if (!isset($_GET['id']))
				die();
			security($_GET['id'],0);
			$result=mysql_query("SELECT * from users where id=".$_GET['id']);
			if (!$result){
				echo '<script> window.location.href="error.php?error=No such user!"; </script>';
				die();
			}
			$result=mysql_fetch_array($result);
			if (!$result){
				echo '<script> window.location.href="error.php?error=No such user!"; </script>';
				die();
			 }
			 if (isset($_SESSION['danmuku']) and $_SESSION['danmuku']==1)
			 {
				$result3=mysql_query("SELECT * from oichat where receive=".$_GET['id']." and type='imp'");
				while ($row=mysql_fetch_array($result3))
				{
				    echo '<div class="danmu"';
					if (isset($_SESSION['user']) and ($_SESSION['user']==$row['user']))
					echo ' style="border:2px solid blue"';
					$content=file_get_contents("./oichat/".$row['id']."/content.txt");
					$content=htmlspecialchars($content);
					echo '>'.$content.'</div>';
				}
			}
			echo "<title>".$result['name']."</title>";
			echo "<h1 style='color:".get_user_color($result['id'])."'>".$result['name']."</h1>";
			echo "<h3 style='color:".get_user_color($result['id'])."'>";
			?>
			<script>
			printUser(<?php echo $result['rating'] ?>);
			</script>
			<?php
			echo"</h3>";
			if ($result['admin'])
			echo "<font size='5'>Administrator</font><br/>";
			else
			echo "<font size='5'>User</font><br/>";
			echo "RealName:".$result['realname']."<br/>";
			echo "<a href='OIchat-room.php?id=".$result['id']."' style='color:blue'><i>Chat With Him!</i></a>";
		 ?>
		<br/>
		<h2>Rating</h2>
		<div>
			<canvas width=900 height=360 id="rating_picture" class='rating-graph'></canvas>
			<script src='rating-draw.js'></script>
			<script>
				setTimeout("draw2()",100);
			</script>
			<?php 
				$lst=Array();
				$result=mysql_query("SELECT * FROM contests");
				while ($row=mysql_fetch_array($result))
				$lst[$row['id']]=-1;
				$lst[0]=1500;
				echo "<div id='ranklist' style='display:none'>";
				$file=fopen("./judge/rating/".$_GET['id'].".log","r");
				if ($file)
				{
				   while (!feof($file))
				   {
					  $row=explode(' ',fgets($file));
					  if (!isset($row[1])) break;
					  $lst[$row[0]]=$row[1];
					  $result3=mysql_query("SELECT * FROM Contest_Ranklist_".$row[0]." WHERE uid=".$_GET['id']);
					   $result3=mysql_fetch_array($result3);
					   $content=$result3['rank']-1;
					   $result3=mysql_query("SELECT count(*) FROM Contest_Ranklist_".$row[0]);
					   $result3=mysql_fetch_array($result3);
					   if ($result3['count(*)']>1)
					   $content=$content*1000/($result3['count(*)']-1);
					   else
					   $content=0;
					   echo $content." ";
					}
				}
/*				$result=mysql_query("SELECT * FROM User_Rating_".$_GET['id']);
				if ($result)
				{
  				   while ($row=mysql_fetch_array($result))
				   {
						$lst[$row['contest']]=$row['rating'];
						if ($row['contest']==0) continue;
					   $result3=mysql_query("SELECT * FROM Contest_Ranklist_".$row['contest']." WHERE uid=".$_GET['id']);
					   $result3=mysql_fetch_array($result3);
					   $content=$result3['rank']-1;
					   $result3=mysql_query("SELECT count(*) FROM Contest_Ranklist_".$row['contest']);
					   $result3=mysql_fetch_array($result3);
					   if ($result3['count(*)']>1)
					   $content=$content*1000/($result3['count(*)']-1);
					   else
					   $content=0;
						echo $content." ";
					 }
   				  }*/
				 echo "</div>"
			?>
			<h2>Contest Ranks</h2>
			<div id='ratinglist' style='display:none'>
				<?php
					for ($i=0;$i<count($lst);$i++)
					{
						echo $lst[$i];
						if ($i!=count($lst)-1)
						echo " ";
					}
				?>
			</div>
			<canvas width=900 height=360 id="rank_picture" class='rating-graph'></canvas>
			<script src='rank-draw.js'></script>
			<script>
			   setTimeout("draw3()",100);
			</script>
			<?php if (isset($_SESSION['danmuku']) and $_SESSION['danmuku']==1) { ?>
			<center><form method='post' action='OIchat-submit.php'>
				  Comment: <input type='text' name='content' style='width:60%' maxlength=128 />
				  <input type='hidden' name='type' value='imp'>
				  <button type='submit'>Danmaku Go!</button>
				  <input type='hidden' name='id' value=<?php echo $_GET['id']?>>
			</form><br/>Notice: Older message will be replaced.</center>
			<?php } ?>
		</div>
			<?php
			$result=mysql_query("SELECT * from users where id=".$_GET['id']);
			$result=mysql_fetch_array($result);
			unset($lst);
			if (isset($_SESSION['user']) and $result['admin'] and $result['id']==$_SESSION['user'])
			include 'user-admin.php';	
			include 'footer.php';?>
	</div>
</html>
