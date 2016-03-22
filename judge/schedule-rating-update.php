<?php
   include '../mysql-inital.php';
   function rating_update($rating,$rank_new)
   {
	  $rank_exp=(2000-$rating)/1000;
	  $res=1500+($rating-1500)*0.95+($rank_exp-$rank_new)*200;
	  $res=max($res,$rating-50);
	  $res=min($res,$rating+50);
	  return $res;
   }
   function user_rating($cont,$name)
   {
	  mysql_query("UPDATE users SET rating = ".intval($cont)." WHERE id=".$name);
   }
   function close_file($cont,$name)
   {
	  fclose($cont);
   }

   $result0=mysql_query("SELECT * FROM schedule WHERE activate_time<=now() AND done=FALSE AND action='rating-update'");
   while ($row0=mysql_fetch_array($result0))
   {
	  $cres=mysql_query("SELECT * FROM contests WHERE id=".$row0['value']);
	  $cres=mysql_fetch_array($cres);
	  $cid=$row0['value'];
	  $cname=$cres['name'];
	  $cnt=$cres['totp'];
	  $val=Array();
	  $output=Array();
	  $result=mysql_query("SELECT * FROM users WHERE realname IS NOT NULL");
	  while ($row=mysql_fetch_array($result))
	  {
		 $val[$row['id']]=0;
		 $output[$row['id']]=fopen("./rating/".$row['id'].".log","w");
	  }
	  $result=mysql_query("select * from contests where israted != 0");
	  while ($row=mysql_fetch_array($result))
	  {
		 $cid=$row['id'];
		 echo "Contest #".$cid."... ";
		 $totp=$row['participants'];
		 $result2=mysql_query("SELECT * FROM Contest_Ranklist_".$cid);
		 while ($row2=mysql_fetch_array($result2))
		 {
			$uid=$row2['uid'];
			if (!isset($val[$uid]))
			continue;
			$tname="User_Rating_".$uid;
			if ($val[$uid]==0)
			{
			   $val[$uid]=1500;
/*			   $result3=mysql_query("SHOW TABLES LIKE '".$tname."'");
				$result3=mysql_fetch_array($result3);
 				if ($result3)
 				mysql_query("DROP TABLE ".$tname);
 				mysql_query("CREATE TABLE ".$tname." (id int primary key auto_increment,contest int,rating int)");
				mysql_query("INSERT INTO ".$tname." (contest,rating) VALUES (0,1500)");
*/
			}
			$val[$uid]=rating_update($val[$uid],($row2['rank']-1)/$totp);
			mysql_query("INSERT INTO ".$tname." (contest,rating) VALUES (".$cid.",".intval($val[$uid]).")");
			fwrite($output[$uid],$cid." ".intval($val[$uid])."\n");
			//echo $uid."->".$val[$uid]."\n";
		 }
	  }
	  array_walk($val,"user_rating");
	  array_walk($output,"close_file");
	  mysql_query("UPDATE schedule SET done=true WHERE id=".$row0['id']);
	  unset($val);
	  unset($output);
	  echo "Finished.\n";
   }
?>
