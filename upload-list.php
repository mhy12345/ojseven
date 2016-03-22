<?php
   include 'mysql-inital.php';
?>
<?php
   if ($_SESSION['ulevel'] > 1)
   {?>
   <div class='content'>
	  <?php
		 /*	$result=mysql_query("SELECT * FROM uploads WHERE type='resource'");
		 while($row=mysql_fetch_array($result))
		 {
			echo "<a href='uploads/resource/".$row['name']."'>".$row['name']."</a><br/>";
		 }*/
	  }
   ?>
   <!--<br/>-->
   <?php
	  $result=mysql_query("SELECT * FROM uploads WHERE type='software'");
	  while($row=mysql_fetch_array($result))
	  {
		 echo "<a href='uploads/software/".$row['name']."'>".$row['name']."</a><br/>";
	  }
   ?>
</div>
