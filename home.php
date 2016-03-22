<!DOCTYPE html>
<html>
	<?php 
		include 'environment-init.php';
		include 'header.php';
		include 'home-notice.php';
		include 'home-problem.php';
		//include 'home-buildlog.html';
	?>
	<title>Welcome to OjSeven</title>
	<link href="include/prism.css" rel="stylesheet" />
		<script type="text/javascript" src="include/prism.js"></script>
		<script type="text/javascript" src="javascript/MathJax-master/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
		<script type="text/x-mathjax-config">
			MathJax.Hub.Config({
					tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
			});
		</script>

	<div class="container">
		<?php
			if (!isset($_SESSION['user']))
			die();
		?>
		<?php 
			include 'upload-list.php';
		?>
		<link rel="Stylesheet" type="text/css" href="css/DialogBySHF.css" />
		<script type="text/javascript" src="javascript/jquery.js"></script>
		<script type="text/javascript" src="javascript/DialogBySHF.js"></script>
		<?php include 'footer.php';?>
	</div><!--container-->
</html>

