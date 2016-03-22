<!DOCTYPE html>
<html>
	<?php 
		include 'environment-init.php';
		include 'header.php';
		include 'oj7-functions.php';
	?>
	<head>
		<link href="include/prism.css" rel="stylesheet" />
		<script type="text/javascript" src="include/prism.js"></script>
		<script type="text/javascript" src="javascript/MathJax-master/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
		<script type="text/x-mathjax-config">
			MathJax.Hub.Config({
					tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
			});
		</script>
		<script type="text/javascript" src="javascript/jquery.js"></script>
		<script type="text/javascript" src="javascript/jquery.zclip.js"></script>
		<style type="text/css">
			.line{margin-bottom:20px;}
			/* 复制提示 */
			.copy-tips{position:fixed;z-index:999;bottom:50%;left:50%;margin:0 0 -20px -80px;background-color:rgba(0, 0, 0, 0.2);filter:progid:DXImageTransform.Microsoft.Gradient(startColorstr=#30000000, endColorstr=#30000000);padding:6px;}
			.copy-tips-wrap{padding:10px 20px;text-align:center;border:1px solid #F4D9A6;background-color:#FFFDEE;font-size:14px;}
		</style>
		<script>
			function deletemessage(bbsid)
			{
					var back=confirm("Floor #" + bbsid + " will be deleted and you cannot undo this.\nContinue to delete it?");
					if (back==false) return;
					location.href='bbs-delete.php?bbsid=' + bbsid;
			}
		</script>
	</head>
	<body>
		<title>Oj7 BBS</title>
		<div class="container">
			<?php
				if (!isset($_SESSION['user']))
				{
					echo "You Are Forbidden!";
					die();
				}
				$result=mysql_query("SELECT * FROM users WHERE id=".$_SESSION['user']);
				$result=mysql_fetch_array($result);
				if ($result['forbid'])
				{
					echo "You Are Forbidden!";
					die();
				}
				if (isset($_GET['from']))
				$fromid=$_GET['from'];
				else
				$fromid=0;
				if (isset($_GET['id']))
				{
					$prob="problem=".$_GET['id'];
				?>
				<center><i><a href="problem.php?id=<?php echo $_GET['id']?>" style="color:blue">Back to the problem</a></i></center>
				<?php
				}
				else
				{
					$prob="1";
				}
				$result=mysql_query("SELECT * FROM bbs WHERE ".$prob." and type!='del' ORDER BY ID DESC LIMIT ".$fromid.",10");
				while ($row=mysql_fetch_array($result))
				{
					echo '<hr/>';
					if ($row['problem']==0)
					echo '<font size=2>#'.$row['id'].' posted by <a href="user.php?id='.$row['user'].'" style="color:blue">'.get_user_name_by_id($row['user']).' </a>at '.$row['time'].'</font>';
					else
					echo '<font size=2>#'.$row['id'].' posted by <a href="user.php?id='.$row['user'].'" style="color:blue">'.get_user_name_by_id($row['user']).' </a>in <a href="problem.php?id='.$row['problem'].'" style="color:green">problem '.$row['problem'].'</a> at '.$row['time'].'</font>';
				?>
				<a href="view-bbs.php?id=<?php echo $row['id'] ?>" target="_blank" style="color:blue">View</a>
				<?php
				   $content=file_get_contents("./bbs/".get_file_name($row['id'])."/content.txt");
					if ($row['type']=='code') 
						$row['type']='cpp';
					if ($row['type']=='text')
					{ ?>
						<a href="#none" class="copy-input" style='color:green'>Copy</a>
					<?php }
					else if ($row['type']!='html')
					{ ?>
					<a href="#none" class="copy-next" style='color:green'>Copy</a>	
					<script>
						var nlang="<?php echo $row['type']?>";
						document.write("<i>");
							switch (nlang)
							{
									case "pascal":document.write("Pascal");break;
									case "cpp":document.write("C++");break;
									case "c":document.write("C");break;
									case "latex":document.write("LaTex");break;
									case "java":document.write("Java");break;
									case "javascript":document.write("Javascript");break;
									case "python":document.write("Python");break;
									case "css":document.write("CSS");break;
							}
							document.write("</i>");
					</script>
					<?php } else { ?>
					<i>HTML</i>
					<?php }
					if (((isset($_SESSION['admin'])) and ($_SESSION['admin']==1)) or ($row['user'] == $_SESSION['user']))
					{ ?>
					<a href="javascript:deletemessage(<?php echo $row['id'] ?>);" style="color:red">Del</a>
					<?php
					}
					else
					{?>
						<a></a>
					<?php }
					if ($row['type']=='text')
					{
					?>
					<div>
					<?php
					$content=htmlspecialchars($content);
					$content=str_replace("\n","<br/>",$content);
					preg_match_all('/@(\w)+/',$content,$atcont);
					for ($i=0; $i<count($atcont[0]); $i++)
					{
						$atname=substr($atcont[0][$i],1);
						$res=mysql_query("SELECT * FROM users WHERE name=\"".$atname."\"");
						$res=mysql_fetch_array($res);
						if (!$res) continue;
						$content=str_replace($atcont[0][$i],"<a href='user.php?id=".$res['id']."' style='color:blue'>".$atcont[0][$i]."</a>",$content);
					}
					echo $content;
					?>
					</div>
					<?php
					}else if ($row['type']=='html')
					{
					?>
					<div><?php echo $content;?></div>
					<?php
					}else {
					?>
					<pre><code class='language-<?php echo $row['type']?> line-numbers'><?php echo htmlspecialchars($content);?></code></pre>
					<?php
					}
				}
				echo '<hr/>';
			?>
			<center>
				<button onclick="window.location.href='bbs.php?from=<?php echo max(0,$fromid-10);
					if (isset($_GET['id']))
					echo "&id=".$_GET['id'];
				?>'">Prev</button>
			<button onclick="window.location.href='bbs.php?from=<?php echo $fromid+10;
					if (isset($_GET['id']))
					echo "&id=".$_GET['id'];
				?>'">Next</button>
			</center>
			<?php
				if (!isset($_SESSION['user']))die();
			?>
			<h2>Comment!</h2>
			<form method='post' action='bbs-submit.php'>
				<input type='hidden' name='problem' value='
				<?php
					if (isset($_GET['id']))
						echo $_GET['id'];
					else
						echo 0;
				?> '/>
				<textarea name='content' style='width:100%;height:200pt' id='content'></textarea><br/>
				Type:
				<select name='language'>
					<option value="text" selected="selected">Text</option>
					<?php if ($_SESSION['ulevel'] > 1){ ?>
					<option value="html">Text / HTML</option>
					<?php } ?>
					<option value="cpp">C++</option>
					<option value="c">C</option>
					<option value="pascal">Pascal</option>
					<option value="python">Python</option>
					<option value="latex">LaTex</option>
					<option value="java">Java</option>	
					<option value="javascript">Javascript</option>
					<option value="css">CSS</option>
				</select>
				<br/>
				<input type='submit' value='comment'/>
			</form>
			<?php include 'footer.php';?>
		</div>
	</body>
	<script type="text/javascript">
		var onTextErea=function(e)
		{
				if (e.keyCode==9)
				{
						e.preventDefault();
						var start=this.selectionStart, end=this.selectionEnd;
						var text=this.value;
						var tab='    ';
						text=text.substr(0,start)+tab+text.substr(start);
						this.value=text;
						this.selectionStart=start+tab.length;
						this.selectionEnd=end+tab.length;
				}
		}
		document.getElementById('content').onkeydown=onTextErea;
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
				/* 定义所有class为copy-input标签，点击后可复制后继元素文本*/
				$(".copy-input").zclip({
						path: "javascript/ZeroClipboard.swf",
						copy: function(){
								return $(this).next().next().text();
						},
						afterCopy:function(){/* 复制成功后的操作 */
							var $copysuc = $("<div class='copy-tips'><div class='copy-tips-wrap'>Okay</div></div>");
							$("body").find(".copy-tips").remove().end().append($copysuc);
							$(".copy-tips").fadeOut(3000);
					}
			});
			$(".copy-next").zclip({
					path: "javascript/ZeroClipboard.swf",
					copy: function(){
							return $(this).next().next().next().next().text();
					},
					afterCopy:function(){
							var $copysuc = $("<div class='copy-tips'><div class='copy-tips-wrap'>Okay</div></div>");
							$("body").find(".copy-tips").remove().end().append($copysuc);
							$(".copy-tips").fadeOut(3000);
					}
			});
	});
</script>
</html>

