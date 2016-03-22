<!DOCTYPE html>
<html>
<!--********************************************
*          This page by ZCY & LYT	       *
********************************************
-->
<?php include 'environment-init.php';?>
<title>Check in</title>
<?php include 'header.php';?>
<div class="container">
<?php
if (!isset($_SESSION['user']))
{
	echo 'Please contact mhy12345 and % both mhy12345 and myh12346';
	die();
}
?>
	<script type="text/javascript">
function getCookie(cookieName)
{
	var strCookie = document.cookie;
	var arrCookie = strCookie.split("; ");
	for (var i=0; i<arrCookie.length; i++)
	{
		var arr=arrCookie[i].split("=");
		if (cookieName == arr[0])
			return arr[1];
	}
	return "";
}

function checkCode()
{
	var nowDate=new Date();
	if(nowDate.getHours()>=20)
		return 1;
	else
		return 0;
}

function getRP()
{
	var mod=1511;
	var base=23;
	var userSess="<?php echo $_SESSION['name'].$_SESSION['user']; ?>";//getCookie("PHPSESSID");
	var nowDate=new Date();
	var today=new Array(nowDate.getYear(),nowDate.getMonth(),nowDate.getDate());
	var pos=0, hash=0;
	for (var i=0; i<userSess.length; i++)
	{
		hash=(hash*base+today[pos]*userSess.charCodeAt(i))%mod;
		pos++;
		if (pos==3) pos=0;
	}
	return hash%1511-500;
}

var info=new Array("Link Cut Tree","Data Structure","Dynamic Programming","Number Theory","Graph Theory","Trees","Cactuses","Math Problems","Interesting Questions","Reach the top of an OJ","Breadth / Depth First Search","Work on easy problems","Participate in a contest","% YJQ","Come up with new problems","Suffix Automaton","KMP / AC Automaton","Optimized Dynamic Programming","Game Theory","Read theses","Computation Geometry");
var maxSit=info.length;
var todayRP=getRP();
var suggest=todayRP%info.length;
if (suggest < 0)
	suggest += maxSit;
	document.write("<h3>");
if (todayRP <= -200)
{
	document.write("Your RP is too low today ("+todayRP+") ! Please % YJQ immediately to increase your RP!<br/>");
	document.write("<font size=\"10\"><a href=\"user.php?id=4\"> Orz YJQ </a></font>");
}
else
{
	document.write("Your RP Today : " + todayRP);
}
var nowDate=new Date();
var yourInfo = info[suggest];
document.write("<br/> Your best choice today : " + yourInfo);
document.write("</h3><br/>");
</script>
<?php
$which=intval(date("ymdw"))%3+1;
//echo $which;
if($which==2)
{
?>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="800" height="600" id="FlashID" title="boxcar2d.v3.2">
    <param name="movie" value="http://www.boxcar2d.com/swf/boxcar2d.v3.2.swf" />
    <param name="quality" value="high" />
    <param name="wmode" value="opaque" />
    <param name="swfversion" value="6.0.65.0" />
    <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
    <param name="expressinstall" value="http://www.boxcar2d.com/Scripts/expressInstall.swf" />
    <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
    <!--[if !IE]>-->
    <object type="application/x-shockwave-flash" data="http://www.boxcar2d.com/swf/boxcar2d.v3.2.swf" width="800" height="600">
      <!--<![endif]-->
      <param name="quality" value="high" />
      <param name="wmode" value="opaque" />
      <param name="swfversion" value="6.0.65.0" />
      <param name="expressinstall" value="http://www.boxcar2d.com/Scripts/expressInstall.swf" />
      <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
      <div>
        <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
        <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
      </div>
      <!--[if !IE]>-->
    </object>
    <!--<![endif]-->
  </object>
<?php
}
?>
<?php include 'footer.php';?>
</div><!--container-->
</html>

