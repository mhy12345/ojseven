<html>
<body>
<?php
include "environment-init.php";
include "header.php";
include 'oj7-functions.php';
?>
<?php check_for_login();?>
<?php
	if(isset($_POST['code']) and !empty($_POST['code']))
	{
		$file=fopen("./graph/".$_SESSION['user'].".dot","w");
		fwrite($file,$_POST['code']);
		echo "<p>".system("dot -Tpng -o ./graph/".$_SESSION['user'].".png ./graph/".$_SESSION['user'].".dot")."</p>";
	}
?>
<title>Graphviz</title>
<div class=container>
<h4>You can draw graph by using the <a href="https://zh.wikipedia.org/wiki/DOT语言">dot-laguage</a>.</h4>
<center><img src="<?php echo "graph/".$_SESSION['user'].".png"?>"/></center>
<form action="graph.php" enctype='multipart/form-data' method="post">
<textarea name="code"  spellcheck="false" style="width:100%;height:300pt" id="myCode"></textarea>
<button style="width:70px;height:30px">Submit</button>
</form>
</div>
<script>
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
</body>
</html>
