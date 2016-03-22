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
 color : #fff; /* color of name of problem */
}
table.altrowstable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#ff00ff; /* color of word except problems and users */
	border-width: 1px;
	border-color: #000; /* no use */
	border-collapse: collapse;
}
table.altrowstable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #ff0000; /* color of line between the head */
	background-color:#ffff00;/*color of head*/
}
table.altrowstable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #0f0f0f;/* color of line between problems */
}
.oddrowcolor{
	background-color:#00ff00;/* color of 1,3,5...line */
}
.evenrowcolor{
	background-color:#f0f0f0;/*ndd*/
}
</style>
