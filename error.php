<!DOCTYPE html>
<html>
<?php include "environment-init.php";?>
<link rel="stylesheet" href="style.css" type="text/css"/>
<title>Error!</title>
<?php include"header.php";?>
<div class="container">
<h1>Error!</h1>
Details:<?php if (isset($_GET['error'])) echo $_GET['error']?>
</div>
</html>
