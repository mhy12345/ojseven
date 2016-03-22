<!DOCTYPE html>
<?php include 'environment-init.php'?>
<?php
	function get_file_extension($file){
		return pathinfo($file, PATHINFO_EXTENSION);
	} 
	if (!$_SESSION['admin'])
	{
		header("Location:error.php?error=Access denied!");
		die();
	}
?>
<html>
	<?php include'header.php'?>
	<div class="container">
		<?php
			include 'mysql-inital.php';
			$result=mysql_query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name='problems' AND table_schema='".$cur_database."'");
			$result=mysql_fetch_array($result);
			$pid=$result['AUTO_INCREMENT'];
			echo "New Problem id=".$result['AUTO_INCREMENT']."<br/>";
			echo "Checking data...<br/>";
			$flag=true;
			if (empty($_FILES['data']['tmp_name']))
			{
				echo "No File Upload...<br/>";
				if ($_POST['submitoption']!="Waiting")
				echo "<div style='background-color:red'>Warning:Problem set as Pending But no data uploaded!<br/></div>";
				mkdir("./problems/".$pid."/");
				mkdir("./problems/".$pid."/data/");
			}else{
				if ($_FILES['data']['error'])
				{
					echo "ERROR:".$_FILES['data']['error']."<br/>";
					die();
				}
				if (get_file_extension($_FILES['data']['name'])!="zip")
				{
					echo $_FILES['data']['type'];
					echo "Error:Zip Only<br/>";
					die();
				}
				echo "Upload: " . $_FILES['data']['name'] . "<br />";
				echo "Type: " . $_FILES['data']['type'] . "<br />";
				echo "Size: " . ($_FILES['data']['size'] / 1024) . " Kb<br />";
				echo "Temporary stored in: " . $_FILES['data']['tmp_name'] ."<br/>";
				mkdir("./problems/".$pid);
				chmod("./problems/".$pid,0770);
				mkdir("./problems/".$pid."/data");
				chmod("./problems/".$pid."/data",0750);
				move_uploaded_file($_FILES["data"]["tmp_name"],
				"problems/".$pid."/". $_FILES["data"]["name"]);
				echo "Stored in: " . "problems/".$pid."/" . $_FILES["data"]["name"]."<br/>";
				echo "Begin to unzip...";
				exec("unzip ./problems/".$pid."/".$_FILES["data"]["name"]." -d ./problems/".$pid."/data/");
				exec("chmod 640 ./problems/".$pid."/data/*");
				echo "Unzip Successfullly!<br/>";
			}
			echo "Edit mysql database...<br/>";
			if (isset($_POST['iso2']) and $_POST['iso2']=='on'){
				echo "<span style='background-color:yellow'>Enable O2 optmize.</span><br/>";
				$iso2='true';
			}else{
				echo "<span style='background-color:green'>Disable O2 optmize.</span><br/>";
				$iso2='false';
			}
			if (isset($_POST['isspj']) and $_POST['isspj']=='on'){
				echo "<span style='background-color:yellow'>Using Special Judge.</span><br/>";
				$isspj='true';
			}else{
				echo "<span style='background-color:green'>No Special Judge.</span><br/>";
				$isspj='false';
			}
			if (isset($_POST['issubmit']) and $_POST['issubmit']=='on'){
				echo "<span style='background-color:yellow'>Enable Submit Answer.</span><br/>";
				$issubmit='true';
			}else{
				echo "<span style='background-color:green'>Disable Submit Answer.</span><br/>";
				$issubmit='false';
			}
			echo "Submit default status:".$_POST['submitoption'];
			echo "INSERT INTO problems (name,source,timelimit,memorylimit,iso2,submitoption,isspj,issubmit) VALUES ('".$_POST['problem_name']."','".$_POST['source']."',".$_POST['time_limit'].",".$_POST['memory_limit'].",".$iso2.",'".$_POST['submitoption']."',".$isspj,",".$issubmit.")<br/>";
			mysql_query("INSERT INTO problems (name,source,timelimit,memorylimit,iso2,submitoption,isspj,issubmit) VALUES ('".$_POST['problem_name']."','".$_POST['source']."',".$_POST['time_limit'].",".$_POST['memory_limit'].",".$iso2.",'".$_POST['submitoption']."',".$isspj.",".$issubmit.")");
			$file=fopen("problems/".$pid."/data.cfg","w");
			fwrite($file,$_POST['data_range_1']." ".$_POST['data_range_2']."\n");
			fwrite($file,$_POST['data_name']."%d.in\n");
			fwrite($file,$_POST['data_name']."%d.out\n");
			fwrite($file,$_POST['input_file']."\n");
			fwrite($file,$_POST['output_file']."\n");
			fclose($file);
			chmod("problems/".$pid."/data.cfg",0640);
			echo "Complete!<br/>";
		?>
	</div>

</html>
