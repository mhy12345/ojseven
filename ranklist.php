<!DOCTYPE html>
<html>
<title>Ranklist</title>
<?php include "environment-init.php";?>
<?php include "header.php";?>
<div style='width:90%;margin-left:auto;margin-right:auto'>
	<table class='altrowstable' id='alternatecolor'>
		<tr><th>User</th><th><a href='ranklist.php?ACs='>ACs</a></th><th><a href='ranklist.php?Today_ACs='>Today ACs</a></th><th><a href='ranklist.php?Rating='>Rating</a></th></tr>
			<?php
				include 'mysql-inital.php';
				include 'oj7-functions.php';
				/*
				$result=mysql_query("SELECT * FROM users WHERE realname IS NOT NULL AND length(realname)>1");
				while ($row=mysql_fetch_array($result))
				{
					$result2=mysql_query("SELECT count(DISTINCT problem) FROM statuses WHERE user=".$row['id']." and result='Accept'");
					$result2=mysql_fetch_array($result2);
					mysql_query("UPDATE users SET acs=".$result2['count(DISTINCT problem)']." WHERE id=".$row['id']);
				}*/
				if (isset($_GET['ACs']))
				$result=mysql_query("SELECT * FROM users WHERE realname IS NOT NULL AND length(realname)>1 AND NOT forbid ORDER BY acs DESC");
				else if (isset($_GET['Today_ACs']))
				$result=mysql_query("SELECT * FROM users WHERE realname IS NOT NULL AND length(realname)>1 AND NOT forbid ORDER BY acs-Dacs DESC");
				else
				$result=mysql_query("SELECT * FROM users WHERE realname IS NOT NULL AND length(realname)>1 AND NOT forbid ORDER BY rating DESC");
			?>
			<?php
				while ($row=mysql_fetch_array($result))
				{
					echo "<tr>";
						echo "<td><a style='font-weight:bold;color:".get_user_color($row['id'])."'href='user.php?id=".$row['id']."'>".$row['name']."(".$row['realname'].")"."</td>";
							echo "<td>".$row['acs']."</td>";
							echo "<td>".intval($row['acs']-$row['Dacs'])."</td>";
							if($row['rating'] > 1800)
							{
								echo "<td style='color:red;'>".$row['rating']."</td>";
							}
							else
							{
								echo "<td>".$row['rating']."</td>";
							}
							echo "</tr>" ;
					}
				?>

			</table>
			<?php include 'footer.php';?>
		</div>
		<script>window.onload=altRows("alternatecolor");</script>
	</html>
