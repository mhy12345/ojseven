<script>
   function showTime()
   {
		 var xmlhttp;
		 if (window.XMLHttpRequest)
		 {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
	  }
	  else
	  {// code for IE6, IE5
		 xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function()
   {
		 if (xmlhttp.readyState==4 && xmlhttp.status==200)
		 {
			   var x=document.getElementsByName("servertime");
			   for (var i=0;i<x.length;i++)
			   x[i].innerHTML=xmlhttp.responseText;
		 }
   }
   xmlhttp.open("GET","/ajax/server-time.php",true);
   xmlhttp.send();
   setTimeout("showTime()",1000);
}
showTime();
</script>
<div style='margin-top:40px'>
   <hr/>
   <div style='float:left'>
	  Server Time:<div name='servertime'></div>
   </div>
   <div style='float:right'>
	  <a class='linker' style='float:right' href='help.php'>help</a><br/>
	  by mhy12345
   </div>
   <center>
	  OjSeven
   </center>
</div>
