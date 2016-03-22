<!DOCTYPE html>
<html>
   <title>Oj7 Contest</title>
   <?php include 'environment-init.php';
	  include 'header.php';
	  include 'oj7-functions.php';
	  function array_remove(&$arr, $offset)
	  {
		 array_splice($arr, $offset, 1);
	  }
	  $cnt=0;
	  $rankl=Array();
	  $sum=Array();
	  function walk_print($cont,$name)
	  {
		 $tmps=0;
		 foreach ($cont as $score)
		 {
			$tmps+=$score;
		 }
		 global $rankl;
		 global $sum;
		 $sum[$name]=$tmps;
		 array_push($rankl,$name);
		 unset($tmps);
	  }
	  function rank_cmp($p1, $p2)
	  {
		 global $sum;
		 return $sum[$p1]<$sum[$p2];
	  }
	  function get_user_name($uid)
	  {
		 $result=mysql_query("SELECT * FROM users WHERE id=".$uid);
		 if (!$result)return "NULL";
		 $result=mysql_fetch_array($result);
		 if (!$result)return "NULL";
		 return $result['name'];
	  }
   ?>
   <div class="container" width=100%>
	  <form action='scoreboard-page.php'>
		 <?php 
			if (!isset($_GET['clist']))$_GET['clist']="";
			echo "Generate Scoreboard:<input type='text' name='clist' value='".$_GET["clist"]."'/>";
			$tmp=Array();
			$cres=Array();
			$lst=Array();
			$_GET['clist']=str_replace(' ',',',$_GET['clist']);
			$tmp=explode('-',$_GET['clist']);
			for ($i=0; $i<count($tmp);$i++)
			{
			   $cres=explode(',',$tmp[$i]);
			   if (isset($pre))
			   {
				  for ($j=$pre+1; $j<$cres[0]; $j++)
				  {
					 array_push($lst,$j);
				  }
			   }
			   $pre=$cres[count($cres)-1];
			   for ($j=0; $j<count($cres); $j++)
				array_push($lst,$cres[$j]);
			}
			unset($tmp);
			unset($cres);
		 ?>
		 <input type='submit'/>
	  </form>
	  <?php
		 for ($i=0;$i<count($lst);$i++)
		 {
			$result=mysql_query("select * from Contest_Ranklist_".$lst[$i]);
			if (($lst[$i]=="") or (!$result) or (!mysql_fetch_array($result)))
			{
			   array_remove($lst,$i);
			   $i--;
			}
		 }
		 if (count($lst)==0){
			echo "Please Input Several Contests.<br/>";
			die();
		 }
		 $cres2=Array();
		 $cres=Array();
		 for ($i=0; $i<count($lst); $i++)
		 {
			security($lst[$i],0);
			$result=mysql_query("select * from contests where id=".$lst[$i]);
			if (!$result)
			{
			   echo "Unsuccessful Searching Attempt!";
			   die();
			}
			$row=mysql_fetch_array($result);
			$cres2[$i]=$row['name'];
			$result=mysql_query("select * from Contest_Ranklist_".$lst[$i]);
			if (!$result)
			{
			   echo "NO.".$lst[$i];
			   echo "Unsuccessful Searching Attempt!";
			   die();
			}
			while ($row=mysql_fetch_array($result))
			{
			   $cres[$row['uid']][$lst[$i]]=$row['score'];
			}
		 }
	  ?>
	  <h3>Scoreboard Generated From <?php echo count($lst); ?> Contest<?php if (count($lst)>1) {echo 's';} ?></h3>
	  <table class="altrowstable" id="alternatecolor">
		 <tr>
			<?php
			   echo "<th>Rank</th>";
			   echo "<th>Name</th>";
			   echo "<th>Total</th>";
			   for ($i=0;$i<count($lst);$i++)
			   echo "<th>".$cres2[$i]."</th>";
			?>
		 </tr>
		 <?php
			$cnt=0;
			$save=1;
			array_walk($cres,"walk_print");
			usort($rankl,"rank_cmp");
			for ($j=0; $j<count($rankl); $j++)
			{
			   $uid=$rankl[$j];
			   echo "<tr>";
				  if ($j==0||$sum[$rankl[$j-1]]!=$sum[$uid])
				  {
					 $cnt+=$save;
					 $save=1;
				  }
				  else
				  {
					 $save++;
				  }
				echo "<td>".$cnt."</td>";
				  echo "<td>".get_user_full_name_by_id($uid)."</td>";
				  echo "<td>".$sum[$uid]."</td>";
				  for ($i=0;$i<count($lst);$i++)
				  {
					 if (isset($cres[$uid][$lst[$i]]))
					 echo "<td>".$cres[$uid][$lst[$i]]."</td>";
					 else
					 echo "<td></td>";
				  }
				  echo "</tr>";
			}
			unset($cnt);
			unset($save);
			unset($cres2);
			unset($cres);
			unset($rankl);
			unset($sum);
			//mysql_query("DROP TABLE ".$tn);
		 ?>
	  </table>
	  <script>window.onload=altRows("alternatecolor");</script>
   </div>
