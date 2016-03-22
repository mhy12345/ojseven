<?php
   //	include 'environment-init.php';
   function get_today_cleaner()
   {
	  $lst=array("yjq","zcy","tyh","lpx","lyt","lez","dxq","zxr","lk","yyr","zms","zqq","lxl");
	  return $lst[date("ymd")%count($lst)];
   }
   function days_to($time)
   {
	  return date("z",$time)-date("z");
   }
?>
<div class="content">
   <p><b>
	  2016/03/16 15:47 维护终于提前完成！撒花！由于对文件结构和数据库进行了大量修改，请及时报告bug！
   </b></p>
   <?php
	  if (!(!isset ($_SESSION['ulevel']) or $_SESSION['ulevel']<2))
	  {
	  ?>
	  <p>
	  今日清洁：<?php echo get_today_cleaner();?><br/>
	  </p>
	  <?php
	  }	
   ?>
   <p>
   距离SCTSC：<?php echo days_to(mktime(0,0,0,4,9,2016));?>天<br/><!--mktime(hour,minute,second,month,day,year,is_dst)-->
   <p>
   文件共享功能<a class='linker' href='share.php'>Here</a>
   </p>
   <p>
   征集rating计算公式:当前公式:<br/>
   f[0]=1500;<br/>
   f[i]=1500+(f[i-1]-1500)*0.95+((2000-f[i-1])/1000-(rank-1)/tot_participants)*200;<br/>
   </p>
   <p>
   请不要恶意hack。
   </p>
   <?php
	  if (!(!isset ($_SESSION['ulevel']) or $_SESSION['ulevel']<2))
	  {
	  ?>
	  <b>
		 本OJ的评测环境与详细信息：<a class='linker' href="help.php">help</a>。
	  </b>
	  <?php
	  }
   ?>
</div>
<br/>
