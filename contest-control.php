<!DOCTYPE html>
<html>
	<?php include 'environment-init.php';?>
	<?php
		if (!isset($_SESSION['admin']))
		{
			header("location:error.php");
			die();
		}
		function get_score($ccc)
		{
			list($a,$b)=split("/",$ccc);
			if ($a==0 and $b==0)return 0;
			return 100/$b*$a;
		}
		function get_extension($file){
			return pathinfo($file, PATHINFO_EXTENSION);
		} 
		if (!$_SESSION['admin'])
		{
			header("Location:error.php?error=Access denied!");
			die();
		}
	?>
	<?php include "header.php";?>
	<?php
		include 'mysql-inital.php';
		$cres=mysql_query("SELECT * FROM contests WHERE id=".$_GET['id']);
		$cres=mysql_fetch_array($cres);
		$cid=$_GET['id'];
		$cname=$cres['name'];
		$cnt=$cres['totp'];
		if ($cid<=38)
		{
			echo "Locked";
			die();
		}
	?>
	<?php
		if (isset($_POST['button_start']))
		{
			mysql_query("INSERT INTO schedule (action,value) VALUES ('contest-start',".$cid.")");
		}
		if (isset($_POST['button_stop']))
		{
			mysql_query("INSERT INTO schedule (action,value) VALUES ('contest-stop',".$cid.")");
		}
		if (isset($_POST['button_stop2']))
		{
			for ($i=1;$i<=$cnt;$i++){
				mysql_query("UPDATE problems SET submitoption='Pending' WHERE id=".$cres['p'.$i]);
				mysql_query("UPDATE problems SET contest=0 WHERE id=".$cres['p'.$i]);
			}
			mysql_query("UPDATE contests SET status='Finish' WHERE id=".$cid);
		}
		if (isset($_POST['button_rejudge']))
		{
			mysql_query("INSERT INTO schedule (action,value) VALUES ('contest-pending',".$cid.")");
		}
		if (isset($_POST['button_waiting']))
		{
			mysql_query("INSERT INTO schedule (action,value) VALUES ('contest-waiting',".$cid.")");
		}
		if (isset($_POST['button_make']))
		{
			mysql_query("INSERT INTO schedule (action,value) VALUES ('contest-make',".$cid.")");
		}
		if (isset($_POST['button_jstd']))
		{
			mysql_query("UPDATE statuses SET result='Pending' WHERE user=13 AND contest=".$cid);
		}
		if (isset($_POST['button_rating']))
		{
			mysql_query("INSERT INTO schedule (action) VALUES ('rating-update')");
		}
		if (isset($_POST['button_copy_problem_files']))
		{
			for ($i=1;$i<=$cnt;$i++)
			{
				$extname=get_extension($cres['taskfile']);
				if ($extname=='pdf')
				exec("cp ./contests/".$cname."/".$cres['taskfile']." ./problems/".$cres['p'.$i]."/problem.pdf");
				else if ($extname=='doc')
				exec("cp ./contests/".$cname."/".$cres['taskfile']." ./problems/".$cres['p'.$i]."/problem.doc");
				else if ($extname=='docx')
				exec("cp ./contests/".$cname."/".$cres['taskfile']." ./problems/".$cres['p'.$i]."/problem.docx");
				else if ($extname=='zip')
				exec("cp ./contests/".$cname."/".$cres['taskfile']." ./problems/".$cres['p'.$i]."/problem.zip");
				//echo "cp ./contest/".$cid."/".$cres['taskfile']." ./problems/".$cres['p'.$i]."/problem.pdf";
			}
		}
		if (isset($_POST['button_export_score']))
		{
			mysql_query("INSERT INTO schedule (action,value) VALUES ('contest-export-score',".$cid.")");
		}
		if (isset($_POST['button_schedule_stop']))
		{
			mysql_query("INSERT INTO schedule (action,value,activate_time) VALUES ('contest-stop',".$cid.",'".date("y-m-d ").$_POST['finish_time']."')");
			echo "INSERT INTO schedule (action,value,activate_time) VALUES ('contest-stop',".$cid.",'".date("y-m-d ").$_POST['finish_time']."')";
		}
	?>
	<div class="container">
		<h1>Contest Control</h1>
		<div class="content">
			<form method='post'>
				<input formmethod='get' type='hidden' name='id' value='<?php echo$_GET['id'];?>'></input>
				<button name='button_start'>Start Contest</button>
				<button name='button_stop'>Stop Contest</button>
				<button name='button_stop2'>Stop Contest Only</button>
				<button name='button_rejudge'>All Pending</button>
				<button name='button_waiting'>All Waiting</button>
				<button name='button_make'>Make Result</button>
				<button name='button_jstd'>Judge Std</button>
				<button name='button_rating'>Make Rating</button>
				<button name='button_copy_problem_files'>Copy Problem Files</button>
				<button name='button_export_score'>Export Score</button>
			</form>
			<form method='post'>
				<input type='text' name='finish_time'/>(hh:ii:ss)
				<button name='button_schedule_stop'>Set Stop Time</button>
			</form>
		</div>
		<a class='linker' href='contest.php?name=<?php echo $cname;?>'>Back To Contest</a>
	</div>
</html>
