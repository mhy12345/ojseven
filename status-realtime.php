<!DOCTYPE html>
<html>
   <?php 
	  include 'environment-init.php';
	  include 'mysql-inital.php'
   ?>
   <div class='container'>
	  <center><h1>Status</h1></center>
	  <center><i><h3><a href='status.php' style="color:blue">Back To Status</a></h3></i></center>
	  <title>Realtime Status</title>
	  <div class='content' style='width:480pt' name='status'>
		 <script>
			function showStatus()
			{
				  var xmlhttp;
				  if (window.XMLHttpRequest)
				  {
						xmlhttp=new XMLHttpRequest();
				  }
				  else
				  {
						xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				  xmlhttp.onreadystatechange=function()
				  {
						if (xmlhttp.readyState==4 && xmlhttp.status==200)
						{
							  var x=document.getElementsByName("status");
							  for (var i=0;i<x.length;i++)
							  x[i].innerHTML=xmlhttp.responseText;
						}
				  }
				  xmlhttp.open("GET","/ajax/status-realtime-show.php",true);
				  xmlhttp.send();
			}
			showStatus();
			setInterval("showStatus()",1000);
		 </script>
	  </div>
   </div>
</html>
