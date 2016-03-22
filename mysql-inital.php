<?php
	$cur_database='oj7database';
	$pdb=mysql_connect("localhost","root","cdqzoj7");
	if (!$pdb){
		echo "Could not connect:".mysql_error();die();
	}
	mysql_select_db("oj7database",$pdb);
?>
