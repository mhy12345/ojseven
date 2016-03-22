<html>
   <?php include "environment-init.php";?>
   <?php include 'header.php';?>
   <title>Problemset</title>
   <?php 
	  include 'mysql-inital.php';
	  include 'oj7-functions.php';
   ?>
   <div id="container">
	  <div class=problemset>
		 <?php
			if (isset($_GET['page']))
			{
			   $page=$_GET['page'];
			}else
			{
			   $page=0;
			}
			if (!isset($_GET['search']))$_GET['search']="";
			$result=mysql_query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name='problems' and table_schema='".$cur_database."'");
			$result=mysql_fetch_array($result);
			$tot=$result["AUTO_INCREMENT"]-1000;
			echo "<center>";
			   for ($i=0;$i*100<=$tot;$i++)
			   echo "<a href='problemset.php?page=".$i."&search=".$_GET['search']."'>&nbsp;<image style='max-height:26px' src='share/image/t".($i+1).".png'/>&nbsp;"."</a>&nbsp;";
			   echo "</center>";
		 ?>
		 <center><form method='get'>
			   <input type='text' name='search' value='<?php echo $_GET['search'];?>'></input>
			   <input type='submit' value='Search'/></center>
		 </form>
		 <?php
			if (empty($_GET['search']))
			$sid=-1;
			else
			$sid=$_GET['search'];
			if (isset($_GET['search']))
			security($_GET['search'],2);
			$result=mysql_query("SELECT * FROM problems WHERE id='".$sid."' OR name LIKE '%".$_GET['search']."%' OR source LIKE '%".$_GET['search']."%' OR tags LIKE '%".$_GET['search']."%' LIMIT ".($page*100).",100");
			echo "<table class=\"altrowstable\" id=\"alternatecolor\">";
			   echo "<tr><th width=5%>Status</th><th width=10%>Problem id</th><th width=30%>Name</th><th width=35%>Source</th><th width=10%>AC</th><th width=10%>Submit</th></tr>";
			   $lst=Array();
			   if (isset($_SESSION['user']))
			   {
				  $result2=mysql_query("SELECT * FROM statuses WHERE user='".$_SESSION['user']."'");
				  while ($row=mysql_fetch_array($result2))
				  $lst[$row['problem']]=1;
				  $result2=mysql_query("SELECT * FROM statuses WHERE user='".$_SESSION['user']."' and result='Accept'");
				  while ($row=mysql_fetch_array($result2))
				  $lst[$row['problem']]=2;
			   }
			   while ($row = mysql_fetch_array($result))
			   {
				  if (!isset($_SESSION['user']) or (isset($_SESSION['ulevel']) and $row['level']>$_SESSION['ulevel'])) continue;
				  $result2=mysql_query("SELECT count(*) FROM statuses WHERE result='Accept' and problem='".$row['id']."'");
				  $result2=mysql_fetch_array($result2);
				  $cntAC=$result2['count(*)'];
				  $result2=mysql_query("SELECT count(*) FROM statuses WHERE problem='".$row['id']."'");
				  $result2=mysql_fetch_array($result2);
				  $cntSub=$result2['count(*)'];
				  if (isset($_SESSION['user']))
				  {
					 if (!isset($lst[$row['id']])) $status="";
					 else if ($lst[$row['id']]==2) $status="<span style='color:blue'>Accept</span>";
					 else if ($lst[$row['id']]==1) $status="<span style='color:red'>Naive</span>";
				  }else
				  $status="";
				  echo "<tr>";
					 echo "<td>".$status."</td>";
					 echo "<td>".$row['id']."</td>";
					 echo "<td><a href=\"problem.php?id=".$row['id']."\">".$row['name']."</a></td>";
					 echo "<td>".$row['source']."</td>";
					 echo "<td>".$cntAC."</td>";
					 echo "<td>".$cntSub."</td>";
					 echo "</tr>";
			   }
			   echo "</table>";
			unset($lst);
		 ?>
	  </div>
	  <?php include 'footer.php';?>
   </div><!--container-->
   <script>window.onload=altRows("alternatecolor");</script>
</html>
