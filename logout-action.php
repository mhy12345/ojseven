<!DOCTYPE html>
<?php session_start();?>
<html>
<title> Logout - Oj7 </title>
<link rel="stylesheet" href="style.css" type="text/css"/>
<div class="container">
<?php 
session_destroy();
//header("location:index.php");
?>
<script>
history.back();
</script>
</div>
</html>
