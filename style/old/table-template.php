<style type='text/css'>
:root{
--color-of-table-text:#fff;
--color-of-table-header-border-color:#000;
--color-of-table-border-color:#fff;
--color-of-table-header-background:#1e1e1e;
--color-of-table-odd-row:#616161;
--color-of-table-even-row:#a3a3a3;
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
