<!DOCTYPE html>
<html>
   <?php 
	  include 'environment-init.php';
	  include 'header.php';
	  include 'oj7-functions.php';
   ?>
   <?php
	  $result=mysql_query("SELECT * FROM problems WHERE id='".$_GET['id']."'");
	  $result=mysql_fetch_array($result);
	  $pinfo=$result;
	  $pid=$_GET['id'];
	  $cinfo=get_contest_info_by_problem_id($pid);
	  if ($cinfo['type']=='RP++')
	  {
		 $result=mysql_query("SELECT * FROM Contest_RPPP_".$cinfo['id']."_Participants WHERE user=".$_SESSION['user']);
		 $result=mysql_fetch_array($result);
		 if ($result==NULL or $result['current']!=$pid or !isset($_SESSION['user']))
		 {
			echo "You Cannot Submit This Problem<br/>";
			die();
		 }
	  }
	  if (isset($cinfo) and $cinfo['estimate']==1)
	  {
		 if (!file_exists('./contests/'.$cinfo['name'].'/'.$_SESSION['user'].'.txt'))
		 {
			echo '<center><h2>Please go to problem page!</h2></center>';
			die();
		 }
	  }

   ?>
   <title>Submit Code</title>
   <div class=container>
	  <form action="submit.php" enctype='multipart/form-data' method="post">
		 Problem id:<?php echo $_GET['id'] ?>
		 <input name="problem" type="hidden" value=<?php echo '"'.$_GET['id'].'"';?>/></br>
		 <?php
			if ($pinfo['issubmit'])
			{
			?>
			Submit Answer:
			<input type='file' name='answer'></input>
			<?php
			}
			else
			{
			?>
			Source Code:
			<select name='language'>
			   <option value='C++'>C++</option>
			   <option value='C'>C</option>
			   <option value='Pascal'>Pascal</option>
			</select><br/>
			<input type='file' name='code'></input>
			<textarea name="code"  spellcheck="false" style="width:100%;height:300pt" id="myCode"></textarea>
			<?php 
			}
		 ?>
		 <div class="ccontainer">
			<button style="width:70px;height:30px">Submit</button>
		 </div>
	  </form>
	  <script type="text/javascript">
		 var onTextErea=function(e)
		 {
			   if (e.keyCode==9)
			   {
					 e.preventDefault();
					 var start=this.selectionStart, end=this.selectionEnd;
					 var text=this.value;
					 var tab='    ';
					 text=text.substr(0,start)+tab+text.substr(start);
					 this.value=text;
					 this.selectionStart=start+tab.length;
					 this.selectionEnd=end+tab.length;
			   }
		 }
		 document.getElementById('myCode').onkeydown=onTextErea;
	  </script>

	  <?php include 'footer.php';?>
   </div>
</html>
