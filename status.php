<!DOCTYPE html>
<html>
   <?php include 'environment-init.php';?>
   <title>Status</title>
   <?php include 'header.php';?>
   <div id="container">
	  <div class=problemset>
		 <?php include 'oj7-functions.php'?>
		 <div style="height:20px;padding:10px">
			<?php
			   if (!isset($_GET['from']))
			   $from=0;
			   else
			   $from=$_GET['from'];
			   if ($from<10)
			   $pfrom=0;
			   else
			   $pfrom=$from-15;
			?>
		 </div>
		 <div>
			<center>
			   <form method='get'>
				  <font size='2'>
					 Problem name:<input type='text' name='pid' style='width:10%' value='<?php if (isset($_GET['pid']))echo $_GET['pid'];?>'/> 
					 User name:<input type='text' name='uname' style='width:10%' value='<?php if (isset($_GET['pid']))echo $_GET['uname'];?>'/>
					 Result:<select name='result'>
						<option value=''<?php if (!isset($_GET['result']) or empty($_GET['result'])) echo " selected='selected'" ?>>All</option>
						<option value='Accept'<?php if (isset($_GET['result']) and $_GET['result']=='Accept') echo " selected='selected'" ?>>Accept</option>
						<option value='Wrong Answer'<?php if (isset($_GET['result']) and $_GET['result']=='Wrong Answer') echo " selected='selected'" ?>>Wrong Answer</option>
						<option value='Time Limit Exceed'<?php if (isset($_GET['result']) and $_GET['result']=='Time Limit Exceed') echo " selected='selected'" ?>>Time Limit Exceed</option>
						<option value='Memory Limit Exceed'<?php if (isset($_GET['result']) and $_GET['result']=='Memory Limit Exceed') echo " selected='selected'" ?>>Memory Limit Exceed</option>
						<option value='Runtime Error'<?php if (isset($_GET['result']) and $_GET['result']=='Runtime Error') echo " selected='selected'" ?>>Runtime Error</option>
						<option value='Compile Error'<?php if (isset($_GET['result']) and $_GET['result']=='Compile Error') echo " selected='selected'" ?>>Compile Error</option>
						<option value='File Error'<?php if (isset($_GET['result']) and $_GET['result']=='File Error') echo " selected='selected'" ?>>File Error</option>
						<option value='Pending'<?php if (isset($_GET['result']) and $_GET['result']=='Pending') echo " selected='selected'" ?>>Pending</option>
						<option value='Waiting'<?php if (isset($_GET['result']) and $_GET['result']=='Waiting') echo " selected='selected'" ?>>Waiting</option>
					 </select>
					 <input type='submit' value='search'/>
				  </font>
			   </form>
			</center>
			<br/>
			<table class="altrowstable" id="alternatecolor">
			   <tr><th width=8%>Run id</th><th width=10%>User</th><th width=12%>Problem Name</th><th width=15%>Result</th><th width=8%>Memory</th><th width=8%>Time</th><th width=9%>Score</th><th width=7%>Language</th><th width=8%>Code length</th><th width=15%>Submit time</th></tr>
			   <?php
				  function get_user_id_by_name($uname)
				  {
					 $result=mysql_query("SELECT * FROM users WHERE name='".$uname."'");
					 $result=mysql_fetch_array($result);
					 return $result['id'];
				  }
				  if (isset($_GET['pid']) and !empty($_GET['pid']))
				  {
					 security($_GET['pid'],2);
					 if (!is_numeric($_GET['pid']))
					 {
						$result=mysql_query("SELECT * FROM problems WHERE name='".$_GET['pid']."'");
						$sp='(';
						while ($row=mysql_fetch_array($result))
						{
						   $sp=$sp."problem = ".$row['id']." OR ";
						}
						$sp=$sp." FALSE)";
						$vp=$_GET['pid'];
					 }else
					 {
						$sp='problem ='.$_GET['pid'];$vp=$_GET['pid'];
					 }
				  }
				  else
				  {
					 $sp='problem IS NOT NULL ';$vp='';
				  }
				  if (isset($_GET['uname']) and !empty($_GET['uname']))
				  {
					 security($_GET['uname'],1);
					 $su='user ='.get_user_id_by_name($_GET['uname']);
					 $vu=$_GET['uname'];
				  }
				  else
				  {
					 $su='user IS NOT NULL ';
					 $vu='';
				  }
				  if (isset($_GET['result']) and !empty($_GET['result']))
				  {
					 security($_GET['result'],2);
					 $sr="result='".$_GET['result']."'";
					 $vr=$_GET['result'];
				  }
				  else
				  {
					 $sr="result is not null";
					 $vr='';
				  }
				  $result=mysql_query("SELECT * FROM statuses WHERE ".$sp." AND ".$su." AND ".$sr." order by id desc limit ".$from.",15");
				  if ($result)
				  {
					 while ($row = mysql_fetch_array($result))
					 {
						echo "<tr>";
						   echo "<td>".$row['id']."</td>";
						   echo "<td><a href='user.php?id=".$row['user']."'>".get_user_name_by_id($row['user'])."</a></td>";
						   echo "<td><a href=\"problem.php?id=".$row['problem']."\">".get_problem_name_by_id($row['problem'])."</a></td>";
						   echo "<td><a href='status-view.php?id=".$row['id']."' style='color:".get_color_by_result($row['result'])."'>".$row['result']."</a></td>";
						   if ($row['result']=='Pending' or $row['result']=='Waiting')
						   $row['memory']=$row['time']=$row['score']='[N/A]';
						   echo "<td>".$row['memory']."KB</td>";
						   echo "<td>".$row['time']."ms</td>";
						   echo "<td>".$row['score']."</td>";
						   echo "<td>".$row['language']."</td>";
						   echo "<td>".$row['codelength']."B</td>";
						   echo "<td>".$row['submittime']."</td>";
						   echo "</tr>";
					 }
				  }
				  echo "</table>";
			?>
		 </div>
	  </div>
	  <center>
		 <button onclick="window.location.href='status.php?uname=<?php echo $vu?>&pid=<?php echo $vp;?>&from=<?php echo $pfrom;?>&result=<?php echo $vr?>'">Prev</button>
		 <button onclick="window.location.href='status.php?uname=<?php echo $vu?>&pid=<?php echo $vp;?>&from=<?php echo ($from+15);?>&result=<?php echo $vr?>'">Next</a>
	  </center>
	  <a class='linker' href='status-realtime.php'>Judging Detail</a>
	  <?php
		 for($x=0;$x<=3;$x++)
		 echo "<br/>";
	  ?>
	  <?php include 'footer.php';?>
   </div><!--container-->
   <script>window.onload=altRows("alternatecolor");</script>
</html>
