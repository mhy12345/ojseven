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
table.altrowstable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #a9c6c9;
	border-collapse: collapse;
}
table.altrowstable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #a9c6c9;
	background-color:#50a3f7;
}
table.altrowstable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #a9c6c9;
}
.oddrowcolor{
	background-color:#aef4ef;
}
.evenrowcolor{
	background-color:#fff;
}
</style>
