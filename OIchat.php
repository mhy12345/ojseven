<!DOCTYPE html>
<html>
<!--
************************************************
*          This page by ZCY & LYT	       *
************************************************
-->
<title>OIchat!</title>
<?php 
include 'environment-init.php';
include 'header.php';
include 'oj7-functions.php';
?>
<!--<center><h2><a href="bbs.php">此页面可能出现CE，RE，卡炸浏览器等事件，讨论请暂时移步BBS！</a></h2></center>-->
<style type="text/css">
.mailbox {
position:relative;
background:#000079;
width:240px;
      border-radius:5px;
padding:10px;
	text-align:center;
color:#fff;
      font-size:20px;
}
.mailbox:hover {
background:blue;
}

</style>
<script>
function jump(id)
{
	window.open("OIchat-room.php?id="+id);
}
$(document).ready(function(){
		$(".mailbox").each(function(){
			var el=this;
			var limit=2;
			var deltax=Math.random()*limit;
			var deltay=Math.random()*limit;
			setInterval(function(){
				var l=$(el).offset().left;
				var h=$(el).offset().top;
				var myOffset=new Object();
				myOffset.left=l+deltax;
				myOffset.top=h+deltay;
				if (myOffset.left<240||myOffset.left>920)
				deltax=-deltax;
				if (myOffset.top<200||myOffset.top>600)
				deltay=-deltay;
				//guichu
/*				deltax=Math.random()*limit;
				deltay=Math.random()*limit;
				if ((Math.random()>0.5||myOffset.left>920)&&myOffset.left>240)
					deltax=-deltax;
				if ((Math.random()>0.5||myOffset.top>600)&&myOffset.top>200)
					deltay=-deltay;*/
				//EOG
				$(el).offset(myOffset);
		  },30);
/*				$(this).mouseover(function(){	
					  setTimeout(function(){
							var l=Math.random()*680+240;
							var h=Math.random()*400+200;
							var myOffset=new Object();
							myOffset.left=l;
							myOffset.top=h;
							$(el).offset(myOffset);
					  },200);
			});*/
			/*$(this).mouseleave(function(){
				  //	limit=3;
			});*/
		});	
	});
</script>
<div class="container">
	<?php
function newmail($cont,$name)
{
	$res=get_user_name_by_id($name);
	$randx=rand()%680+240;
	$randy=rand()%400+200;
	echo '<div class="mailbox" onclick="javascript:jump('.$name.')" style="left:'.$randx.'px; top:'.$randy.'px; position:fixed" id="aa"> '.$res;
	if ($cont>0) echo ' ('.$cont.')';
			echo '</div>';
			}
function oldmail($cont,$name)
			{
			$res=get_user_name_by_id($name);
			$randx=rand()%680+240;
			$randy=rand()%400+200;
			echo '<div class="mailbox" onclick="javascript:jump('.$name.')" style="left:'.$randx.'px; top:'.$randy.'px; position:fixed" id="aa"> '.$res.'</div>';
			}
			if (!isset($_SESSION['user']))
			{
			echo 'Please contact mhy12345 and % both mhy12345 and myh12346';
			die();
			}
			echo '<center><h1>OIchat!<br/></h1></center>';
			?>
			<center>
			<form method='get' action='OIchat-room.php'>
			Name or ID : <input type='text' name='id'/>
			<button type='submit'>Go!</button>
			</form>
			</center>
	<?php
	   $result=mysql_query("select * from oichat where receive=".$_SESSION['user']." and hint=1 and type!='imp'");
	$exist=Array();
while ($row=mysql_fetch_array($result))
{
	if (isset($exist[$row['user']]))
	{
		$exist[$row['user']]++;
		continue;
	}
	$exist[$row['user']]=1;
}
array_walk($exist,"newmail");
$result=mysql_query("select * from oichat where receive=".$_SESSION['user']." or user=".$_SESSION['user']." and hint=0 order by id desc limit 100");
$exist2=Array();
while ($row=mysql_fetch_array($result))
{
	if (isset($exist[$row['user']]))
		continue;
	$exist2[$row['user']]=0;
}
array_walk($exist2,"oldmail");
unset($exist2);
unset($exist);
//for ($i=1; $i<=20; $i++)
//	echo '<br/>';
?>
</div><!--container-->
</html>

