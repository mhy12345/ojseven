<?php
	if (!$_SESSION['admin'])
	{
		echo '<script> window.location.href="error.php?error=Access Denied!"; </script>';
		die();
	}
?>
<script>
	function use_problem_name()
	{
			var name=document.getElementById('problem_name').value;
			document.getElementById('input_file').value=name+".in";
			document.getElementById('output_file').value=name+".out";
			document.getElementById('data_name').value=name;
	}

	function use_data_name()
	{
			var name=document.getElementById('data_name').value;
			document.getElementById('input_file').value=name+".in";
			document.getElementById('output_file').value=name+".out";
	}
</script>
<div class='content'>
	<h3>Add Problem</h3>
	<form action='problem-add.php' enctype='multipart/form-data' method='post'>
		Problem Name:<input type='text' id='problem_name' name='problem_name'></input><br/>
		Problem Time Limit:<input type='text' name='time_limit' value='1000'></input>ms<br/> 
		Problem Memory Limit:<input type='text' name='memory_limit' value='256'></input>mb<br/>
		Source:<input type='text' name='source' value="<?php echo $_SESSION['name'];?>"></input><br/> 
		Input File:<input type='text' name='input_file' id='input_file' value='stdin'></input><br/>
		Output File:<input type='text' name='output_file' id='output_file' value='stdout'></input><br/>
		Data name:<input type='text' name='data_name' id='data_name' ></input><br/> 
		Data range:<input type='text' name='data_range_1' value='1'></input>to<input type='text' name='data_range_2' value='10'></input><br/>
		Submit default status:
		<input  type='radio' name='submitoption' value='Pending'>Pending</input>
		<input  type='radio' name='submitoption' value='Waiting' checked>Waiting</input><br/>
		<input type='checkbox' name='iso2'>O2</input><br/>
		<input type='checkbox' name='isspj'>SPJ</input><br/>
		<input type='checkbox' name='issubmit'>Submit Answer</input><br/>
		Data:<input type='file' name='data'></input><br/>
		<input type='submit' value='submit'></input>
	</form>
	<button onclick='use_problem_name()'>Use Problem Name</button><br/>
	<button onclick='use_data_name()'>Use Data Name</button><br/>
</div>
<div class='content'>
	<h3>Add Contest</h3>
	<?php
		$result=mysql_query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name='problems' AND table_schema='".$cur_database."'");
		$result=mysql_fetch_array($result);
		$pmx=$result['AUTO_INCREMENT'];
	?>
	<form action='contest-add.php' enctype='multipart/form-data' method='post'>
		Contest Name:<input type='text' name='contest_name' value='<?php echo date("Ymd");?>'></input><br/>
		Contest Type:
		<input  type='radio' name='contest_type' value='OI' checked>OI</input>
		<input  type='radio' name='contest_type' value='ACM'>ACM</input>
		<input  type='radio' name='contest_type' value='RP++'>RP++</input>
		<br/>
		Total problems:	<input type='text' name='total_problems' value='3'></input><br/>

		<?php
			for ($i=1;$i<=3;$i++)
			{
			?>
			Problem No.<?php echo $i;?>:<input type='text' name='problem<?php echo $i?>' value=<?php echo $pmx+$i-4;?>></input><br/>
			<?php
			}
			for ($i=4;$i<=5;$i++)
			{
			?>
			Problem No.<?php echo $i;?>:<input type='text' name='problem<?php echo $i?>'></input><br/>
			<?php
			}
		?>
		Problem:<input type='file' name='pfile'></input><br/>
		Contest Level:<input type='text' name='clevel' value='2'></input><br/>
		<input type='checkbox' name='estimate'>Estimate Score</input><br/>
		<input type='submit'/>
	</form>
</div>
<div class='content'>
	<h3>Upload files</h3>
	<form action='uploads-submit.php' method='post' enctype='multipart/form-data'>
		File type:<input type='radio' name='file-type' value='resource' checked>Resource</input>
		<input type='radio' name='file-type' value='software'>Software</input><br/>
		<input type='file' name='file'/>
		<input type='submit'/>
	</form>
</div>
