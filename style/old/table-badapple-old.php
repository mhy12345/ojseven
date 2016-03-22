<script type="text/javascript">
function altRows(id){
		if(document.getElementsByTagName){
				var table = document.getElementById(id);
		var rows = table.getElementsByTagName("tr");
		for(i = 1; i < rows.length; i++){
			if(i % 2 == 0){
				rows[i].className = "evenrowcolor";
			}else{
				rows[i].className = "oddrowcolor";
			}
		}
	}
}
</script>
<style type="text/css">
table{
width:100%;
margin:auto;
}
table a{
 color : #fff;
}
table.altrowstable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#fff;
	border-width: 1px;
	border-color: #000;
	border-collapse: collapse;
}
table.altrowstable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #000;
	background-color:#000;
}
table.altrowstable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #fff;
}
.oddrowcolor{
	background-color:#616161;
}
.evenrowcolor{
	background-color:#a3a3a3;
}
</style>
