<style type='text/css'>
:root{
--color-of-table-text:#333;
--color-of-table-header-border-color:#a9c6c9;
--color-of-table-border-color:#a9c6c9;
--color-of-table-header-background:#e15afc;
--color-of-table-odd-row:#f1b9fe;
--color-of-table-even-row:#fff;
}
</style>
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
	color : var(--color-of-table-text);/*链接颜色*/
}
table.altrowstable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:var(--color-of-table-text);/*字体颜色*/
	border-width: 1px;
	border-color: var(--color-of-table-border-color);;
	border-collapse: collapse;
}
table.altrowstable th {/*表头*/
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: var(--color-of-table-header-border-color);
	background-color:var(--color-of-table-header-background);
}
table.altrowstable td {/*表格*/
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: var(--color-of-table-border-color);
}
/*奇偶行颜色*/
.oddrowcolor{
	background-color:var(--color-of-table-odd-row);
}
.evenrowcolor{
	background-color:var(--color-of-table-even-row);
}
</style>
