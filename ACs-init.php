<?php
//	********************************************
//	*          This page by LYT                *
//	********************************************
	include "mysql-inital.php";
	$time_last=file_get_contents("./ACs/last_day.txt");
	if($time_last!=date("ymd"))
	{
		exec("mysqldump -u root -pcdqzoj7 oj7database > /sql-bf/20".date("ymd").".sql");
		echo "<center><p>You Are the First Person who Come to OJ7 Today!</p></center>";
		$file_now=fopen("./ACs/last_day.txt","w");
		fwrite($file_now,date("ymd"));
		fclose($file_now);
		$result=mysql_query("SELECT * FROM users");
		while($row=mysql_fetch_array($result))
		{
			mysql_query("update users set Dacs = ".$row['acs']." where id = ".$row['id']);
			/*
			if(!is_dir("./ACs/".$row['id']."/"))
			{
				mkdir("./ACs/".$row['id']."/");
			}
			$file_now=fopen("./ACs/".$row['id']."/day.txt","w");
			fwrite($file_now,$row['acs']);
			fclose($file_now);
			*/
		}
	}
?>

