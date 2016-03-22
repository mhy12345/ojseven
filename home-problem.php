<div class="content">
   <center><h3>每（INF）日一问</h3></center>
   <?php
	  $content=file_get_contents("everyday_problem.txt");
	  $content=str_replace("\n","<br/>",$content);
	  echo $content;
	  echo '<br/>';
   ?>
</div>
<br/>
