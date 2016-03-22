<!DOCTYPE html>
<html>
   <?php
	  include "oj7-functions.php";
		session_start();
		if (!isset($_SESSION['user']))
		{
			echo "You Are Forbidden!";
			die();
		}
		$content=file_get_contents("./bbs/".get_file_name($_GET['id'])."/content.txt");
	?>
	<pre><code><?php echo htmlspecialchars($content);?></code></pre>
</html>
