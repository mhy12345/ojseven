<script type="text/javascript" src="javascript/jquery.js"></script>
<style type="text/css">
   .notice {
		 position:fixed;
		 color:#fff;
		 width:auto;
		 font-size:20px;
		 font-width:bold;
		 font-family:"黑体";
		 text-shadow: 2px 0px 0px #000,
		 -2px 0px 0px #000,
		 0px 2px 0px #000,
		 0px -2px 0px #000;
		 visibility:hidden;
		 white-space:nowrap;
   }
</style>

<script type="text/javascript">
   var pre, timeSavior, ele;
   function jsddm_open()
   {
		 $(this).siblings().children("span").css("visibility", "hidden");
		 $(this).children("span").css("visibility", "visible");
		 if (timeSavior)
		 {
			   window.clearTimeout(timeSavior);
			   timeSavior=null;
		 }
   }

   function jsddm_close()
   {
		 var ele=this;
		 timeSavior=setTimeout(function(){
			   $(ele).children("span").css("visibility", "hidden");
		 },100);
   }

   function word_open()
   {
		 $(this).children("a").css("color", $(this).css("color"));
   }

   function word_close()
   {
		 $(this).children("a").css("color", pre);
   }

   $(document).ready(function()
   {
		 pre=$("#nav > div a").css("color");
		 $('#nav > div').bind('mouseover', jsddm_open);
		 $('#nav > div').bind('mouseout',  jsddm_close);
		 $('#nav > div span').bind('mouseover', word_open);
		 $('#nav > div span').bind('mouseout', word_close);
		 $(".notice").each(function(){
			   var el=this;
			   var deltax=-2;
			   var myOffset=new Object();
			   var width=window.outerWidth;
			   myOffset.top=60;
			   myOffset.left=width;
			   $(el).offset(myOffset);
			   setTimeout(function(){
					 $(el).css("visibility","visible");
					 setInterval(function(){
						   l=$(el).offset().left;
						   if (l<-$(el).width())
						   myOffset.left=width+500;
						   else
						   myOffset.left=l+deltax;
						   $(el).offset(myOffset);
					 },20);
			   },1000);
		 });

   });

   function a_post(url,args){
		 var form = $("<form method='post'></form>"),
		 input;
		 form.attr({"action":url});
		 $.each(args,function(key,value){
			   input = $("<input type='hidden'>");
			   input.attr({"name":key});
			   input.val(value);
			   form.append(input);
		 });
		 form.submit();
   }
</script>
<div id="nav_cont">
   <div id="nav">
	  <div style="float:left"><a href="index.php">OJSeven</a>
		 <span><a href="help.php">Help</a></span>
	  </div>
	  <div style="float:left"><a href="problemset.php">Problemset</a>
		 <?php
			include 'mysql-inital.php';
			$result=mysql_query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name='problems' and table_schema='".$cur_database."'");
			$result=mysql_fetch_array($result);
			$tot=$result["AUTO_INCREMENT"]-1000;
			for ($i=0;$i*100<=$tot;$i++)
			echo "<span><a href='problemset.php?page=".$i."'>Page ".($i+1)."</a></span>";
		 ?>
	  </div>
	  <div style="float:left"><a href="status.php">&nbsp;Status&nbsp;</a>
		 <?php
			if (isset($_SESSION['name']))
			echo "<span><a href='status.php?uname=".$_SESSION['name']."'>My Code</a></span>"
		 ?>
		 <span><a href='status.php?uname=call_me_std'>Std</a></span>
		 <span><a href="status-realtime.php" target="_blank">Realtime</a></span>
	  </div>
	  <div style="float:left"><a href="contests.php">Contests</a>
		 <?php
			$result=mysql_query("SELECT * FROM contests ORDER BY id DESC");
			$result=mysql_fetch_array($result);
			echo "<span><a href='contest.php?name=".$result['name']."'>Latest</a></span>"
		 ?>
	  </div>
	  <div style="float:left"><a href="ranklist.php">Ranklist</a>
		 <?php
			$result=mysql_query("SELECT * FROM users WHERE realname IS NOT NULL AND length(realname)>1 AND NOT forbid ORDER BY rating DESC limit 0,1");
			$result=mysql_fetch_array($result);
			echo "<span><a href='user.php?id=".$result['id']."'>See Top</a></span>"
		 ?>
	  </div>
	  <div style="float:left"><a href="practise.php">Practise</a>
	  </div>
	  <div style="float:left"><a href="bbs.php">BBS</a></div>
	  <?php
		 if (!isset($_SESSION['name']))
		 {
			echo '<div style="float:right"><a href="register.php">register</a></div>';
			echo '<div style="float:right"><a href="login-page.php">login</a></div>';
		 }else
		 {
			$result=mysql_query("select count(*) from oichat where receive=".$_SESSION['user']." and hint=1 and type!='imp'");
			$row=mysql_fetch_array($result);
		 ?>
		 <div style="float:right; margin:auto 10px auto auto"><a href="user.php?id=<?php echo $_SESSION['user'] ?>">
			   <?php 
				  echo $_SESSION['name'];
				  if ($row['count(*)']>0)
				  echo ' ('.$row['count(*)'].')';
			   ?></a>
			<span><a href="check-in.php">Check in</a></span>
			<span><a href="OIchat.php">OIchat!
				  <?php if ($row['count(*)']>0)
					 echo ' ('.$row['count(*)'].')';
				  ?></a></span>
			<span><a href="user-edit.php?id=<?php echo $_SESSION['user'] ?>">Modify</a></span>
			<span><a href="user-passwd.php?user=<?php echo $_SESSION['user'] ?>">Password</a></span>
			<span><a href="logout-action.php">Logout</a></span>
		 </div>
		 <?php
		 }
	  ?>
   </div><!--nat-->
</div><!--nav_cont-->
<p>
<?php 
   if ((!isset($_SESSION['admin']) or !$_SESSION['admin']))
   {
	  //include 'maintain.php';
   }
   include 'header-notice.html';
   include "ACs-init.php";
?>
