<!DOCTYPE html>
<html>
<!--
************************************************
*          This page by ZCY      	       *
************************************************
-->
		<script type="text/javascript" src="javascript/MathJax-master/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
		<script type="text/x-mathjax-config">
			MathJax.Hub.Config({
					tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
			});
		</script>
		<script type="text/javascript" src="javascript/jquery.js"></script>

<style type="text/css">

.right {
position:relative;
background:#000079;
border-radius:5px; /* 圆角 */
margin:auto 0 auto 50%;
word-break:break-all;
color:#fff;
padding:5px;
}

.right .arrow {
position:absolute;
top:5px;
right:-16px; /* 圆角的位置需要细心调试哦 */
width:0;
height:0;
font-size:0;
border:solid 8px;
border-color:#fff #fff #fff #000079;
}

.left {
position:relative;
background:#000079;
border-radius:5px; /* 圆角 */
margin:auto 50% auto 0;
word-break:break-all;
color:#fff;
padding:5px;
}

.left .arrow {
position:absolute;
top:5px;
left:-16px; /* 圆角的位置需要细心调试哦 */
width:0;
height:0;
font-size:0;
border:solid 8px;
border-color:#fff #000079 #fff #fff;
}


</style>
<script>
function recallmess(id)
			{
					var back=confirm("Your message will be recalled and you cannot undo this.\nContinue to withdraw it?");
					if (back==false) return;
					location.href='OIchat-recall.php?id=' + id;
			}
</script>
<?php
include 'environment-init.php';
include 'header.php';
include 'oj7-functions.php';
?>
<div class="container">
<?php
if (!isset($_SESSION['user']))
{
	echo 'Please Login!';
	die();
}
if ((!isset($_GET['id']))/* or $_GET['id']==$_SESSION['user']*/)
{
	echo '<script> window.location.href="OIchat.php"; </script>';
	die();
}
if (!is_numeric($_GET['id']))
{
	security($_GET['id'],1);
	$result=mysql_query("select * from users where name='".$_GET['id']."'");
	$result=mysql_fetch_array($result);
	if (!$result)
	{
		echo '<script> window.location.href="error.php?error=No such user!"; </script>';
		die();
	}
	echo '<script> window.location.href="OIchat-room.php?id='.$result['id'].'"; </script>';
	die();
}
$result=mysql_query("select * from users where id=".$_GET['id']);
$otherone=mysql_fetch_array($result);
if (!$otherone)
{
	echo '<script> window.location.href="OIchat.php"; </script>';
	die();
}
mysql_query("update oichat set hint=0 where user=".$_GET['id']." and receive=".$_SESSION['user']);
$result=mysql_query("select * from oichat where (user=".$_GET['id']." and receive=".$_SESSION['user'].") or (user=".$_SESSION['user']." and receive=".$_GET['id'].")");
$suc=0;
$from=get_user_name_by_id($_SESSION['user']);
$to=get_user_name_by_id($_GET['id']);
echo '<title>OIchat! - '.$to.'</title>';
echo '<center><h4>Talking with '.$to.'...</h4></center>';
while ($row=mysql_fetch_array($result))
{
   if ($row['type']=='imp') continue;
	$suc=1;
	if ($row['type']=='at' and $row['receive']!=$_SESSION['user']) continue;
	if ($row['user']==$_SESSION['user'])
	{
		echo "<div style='text-align:right'><b>".$from." at ".$row['time']."</b>";
		if ($row['type']!='back')
			echo "<a href='javascript:recallmess(".$row['id'].")'><i> Recall</i></a>";
		echo "</div>";
		if ($row['type']!='back') echo "<div class='right'>";
		else echo "<div style='text-align:right'>";
	}
	else
	{
		echo "<div style='text-align:left'><b>".$to." at ".$row['time']."</b></div>";
		if ($row['type']!='back') echo "<div class='left'>";
		else echo "<div style='text-align:left'>";
	}
	if ($row['type']=='back')
	{
		echo "<i>Withdrew a message.</i></div><br/>";
		continue;
	}
	$content=file_get_contents("./oichat/".get_file_name($row['id'])."/content.txt");
	if ($row['type']!='at')
	{
		$content=htmlspecialchars($content);
		$content=str_replace("\n","<br/>",$content);
	}
	else
	{
		$bid=intval($content);
		$res=mysql_query("select count(*) from bbs where type!='del' and id>".$content);
		$res=mysql_fetch_array($res);
		if ($res)
			$content="<i><a href='bbs.php?from=".$res['count(*)']."' style='color:white'>@ you at BBS, Floor #".$bid."</a></i>";
	}
	echo $content."<div class='arrow'></div></div><br/>";
}
if (!$suc)
{
	echo '<center> You have not talked with HE / SHE yet. </center><br/>';
}?>
<form method='post' action='OIchat-submit.php'>
   <input type='hidden' name='id' value='<?php echo $_GET['id']; ?>'/>
	<textarea name='content' style='width:80%;height:40pt;resize:none' id='content'></textarea>
	<button type='submit' style='width:9%;height:40pt;float:right;margin:auto 5px auto auto'>Send</button>
	<button type='button' style='width:9%;height:40pt;float:right;margin:auto 5px auto auto' onclick="window.location.reload()">Refresh</button>
</form>
<?php
include 'footer.php';?>
</div><!--container-->
</html>

