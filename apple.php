<!DOCTYPE html>
<html>
<script>
var list;
var answer;
var cid=0;
function _search()
{
	list[cid].innerHTML=answer[cid];
	cid++;
}
function inital()
{
	list=document.getElementsByName('ques');
	answer=new Array();
	for (var i=0;i<list.length;i++)
	{
		answer[i]=list[i].innerHTML;
		//document.write(list[i].innerHTML[0]='_');
		list[i].innerHTML="";
		for (var j=0;j<answer[i].length;j++)
			list[i].innerHTML+='__';
	}
}
</script>
<body onkeypress="if (event.keyCode == 13) _search();">
<?php
$str=file_get_contents("share/apple/".$_GET['name']);
$str=str_replace("\n","<br/>",$str);
$str=str_replace("<<","<span name='ques'>",$str);
$str=str_replace(">>","</span>",$str);
echo $str;
?>
</body>
<script>
window.onload=inital();
</script>
</html>
