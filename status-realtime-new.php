<!DOCTYPE html>
<html>
   <script type="text/javascript" src="javascript/jquery.js"></script>
   <style type="text/css">
	  .danmu {
			position:fixed;
			color:#fff;
			width:auto;
			font-size:40px;
			font-family:"黑体";
			font-weight:bold;
			text-shadow: 2px 0px 0px #000,
			-2px 0px 0px #000,
			0px 2px 0px #000,
			0px -2px 0px #000;
			visibility:hidden;
			white-space:nowrap;
	  }
   </style>
   <script type="text/javascript" src="javascript/MathJax-master/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
	<script type="text/x-mathjax-config">
		MathJax.Hub.Config({
				tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
		});
	</script>

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
			function(el)
			{
				  var myOffset=new Object();
				  var l=cnt++;
				  myOffset.top=Math.random()*height*0.6+height*0.2;
				  myOffset.left=width;
				  $(el).offset(myOffset);
				  $(el).css("visibility","visible");
				  setInterval(function(){
						l=$(el).offset().left;
						if (l<-$(el).width())
						$(el).remove();
						myOffset.left=l+deltax;
						$(el).offset(myOffset);
				  },20);
			}
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
