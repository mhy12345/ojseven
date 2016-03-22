<!DOCTYPE html>
<html>
	<title>Oj7 Contest</title>
	<?php 
		include 'environment-init.php';
		include 'header.php';
		include 'mysql-inital.php';
		include 'oj7-functions.php';
	?>
	<?php
		if (!isset($_GET['name']))
		{
			header("error.php");
			die();
		}
		if (isset($_GET['abort']))
		{
			mysql_query("UPDATE Contest_RPPP_".$cid."_Participants SET current=NULL WHERE user='".$_SESSION['user']."'");
			$_GET['abort']=NULL;
			header("contest.php?name=".$_GET['name']);
			die();
		}
	?>
	<div class="container">
		<h1>Contest <?php echo $_GET['name']?></h1>
		<?php
			$cname=$_GET['name'];
			$result=mysql_query("SELECT * FROM contests WHERE name='".$_GET['name']."'");
			$result=mysql_fetch_array($result);
			$cinfo=$result;
			$cid=$result['id'];
			$cnt=$result['totp'];
			$cfname=$result['taskfile'];
			$cstatus=$result['status'];
			$clevel=$result['level'];
			$ctype=$result['type'];
			$cres=$result;
			if (!isset($_SESSION['user']))
			{
				echo "Access Denied!";
				die();
			}
			if (!$result)
			{
				echo "Error!";
				die();
			}
		?>
		<h2>Time</h2>
		<div class='content'>
			<div name='servertime'>
			</div>
			<?php
				$result=mysql_query("SELECT * FROM schedule WHERE action='contest-stop' AND value=".$cid." AND done=false");
				$result=mysql_fetch_array($result);
				if ($result)
				echo "Scheduled Stop Time:".$result['activate_time'];
			?>
		</div>
		<h2> Status </h2>
		<div class='content'>
			<?php
				echo $cstatus;
			?>
		</div>
		<h2>Materials</h2>
		<div class="content">
			<?php
				if ($_SESSION['ulevel']>=$clevel)
				{
					if ($cfname and file_exists("./contests/".$cname."/".$cfname))
					{
						$dest=file_transfer("./contests/".$cname,$cfname);
						echo "<a href='".$dest."'>ProblemSet</a><br/>";
					}
					if(file_exists("./contests/".$cname."/user.zip"))
					echo "<a href='".file_transfer("./contests/".$cname,"user.zip")."'>User</a><br/>";
					if(file_exists("./contests/".$cname."/pack.zip") and $cstatus=='Finish')
					echo "<a href='".file_transfer("./contests/".$cname,"pack.zip")."'>Pack</a><br/>";
					if(file_exists("./contests/".$cname."/score.html"))
					echo "<a href='".file_transfer("./contests/".$cname,"score.html")."'>Score</a>";

				}
			?>
		</div>
		<h2>Problems</h2>
		<div class="content">
			<?php
				for ($i=1;$i<=$cnt;$i++)
				echo "<a href='problem.php?id=".$cinfo['p'.$i]."'>".get_problem_name_by_id($cinfo['p'.$i])."</a><br/>";
			?>
		</div>
		<div>
			<a class='linker' href="contest-score.php?id=<?php echo $cid;?>">Show Score</a><br/>
		</div>
		<h2>Participants</h2>
		<div class="content">
			<?php
				$result=mysql_query("SELECT DISTINCT user FROM statuses WHERE contest=".$cid);
				while ($row=mysql_fetch_array($result))
				{
					echo "<span style='color:".get_user_color($row['user'])."'>".get_user_name_by_id($row['user'])."</span>";
					for ($i=1;$i<=$cnt;$i++)
					{ 
						$result2=mysql_query("SELECT * FROM statuses WHERE user=".$row['user']."  AND problem=".$cres['p'.$i]." AND contest=".$cid." ORDER BY id DESC");
						//echo "SELECT * FROM statuses WHERE user=".$row['user']."  AND problem=".$cres['p'.$i]." ORDER BY id DESC<br/>";
						$result2=mysql_fetch_array($result2);
						if ($result2['result']=="File Error" or $result2['result']=="Compile Error")
						echo "<image style='color:black;max-height:30px' src='share/image/picture.png'></image>";
					}
					echo "<br/>";
				}
			?>
		</div>
		<?php
			if ($ctype=="RP++")
			{
			?>
			<h2>RP++</h2>
			<div class='content'>
				<?php
					$result=mysql_query("SELECT * FROM Contest_RPPP_".$cid."_Participants WHERE user='".$_SESSION['user']."'");
					//echo "SELECT * FROM Contest_RPPP_".$cid."_Participants WHERE user='".$_SESSION['user']."'";
					$result=mysql_fetch_array($result);
					if (!$result)
					mysql_query("INSERT INTO Contest_RPPP_".$cid."_Participants (user) VALUES ('".$_SESSION['user']."')");
					$result=mysql_query("SELECT * FROM Contest_RPPP_".$cid."_Participants WHERE user='".$_SESSION['user']."'");
					$result=mysql_fetch_array($result);
					if ($result['current']!=NULL)
					{
						echo "Your Current Problem Is <a href='problem.php?id=".$result['current']."'><b>".get_problem_name_by_id($result['current'])."</b></a>(<a href='contest.php?name=".$cname."&abort=true'>Abort</a>)";
					}else
					{
						echo "Lets Open a Problem!";
					}
				?>
			</div>
			<?php
			}
			if (isset($_SESSION['admin']) and $_SESSION['admin']>0)
			{
			?>
			<h2>Admin</h2>
			<div class='content'>
				<a href='contest-control.php?id=<?php echo $cid?>'>Control Contest</a><br/>
				<a href='contest-edit.php?id=<?php echo $cid;?>'>Edit Contest</a><br/>
			</div>
			<?php 
			}
		?>
		<?php include 'footer.php';?>
	</div><!--container-->
</html>

