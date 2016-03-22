<?php
   function get_color_by_result($st)
   {
	  if ($st=='Accept')return 'blue';
	  if ($st=='Wrong Answer')return 'red';
	  if ($st=='Time Limit Exceed')return 'orange';
	  if ($st=='Memory Limit Exceed')return 'gray';
	  if ($st=='Runtime Error')return 'green';
	  if ($st=='Complie Error')return '#b503af';
	  if ($st=='Pending')return 'green';
   }
   function get_user_color($v)
   {
	  $result=mysql_query("SELECT * from users where id=".$v);
	  $result=mysql_fetch_array($result);
	  if ($result['rating']==0)
	  $cc='grey';
	  else if ($result['rating']<1300)
	  $cc='green';
	  else if ($result['rating']<1450)
	  $cc='blue';
	  else if ($result['rating']<1550)
	  $cc='purple';
	  else if ($result['rating']<1700)
	  $cc='orange';
	  else if ($result['rating']<1800)
	  $cc='rgba(255,0,0,1)';
	  else if ($result['rating']>=1800)
	  $cc='rgba(10,10,10,1)';
	  return $cc;
   }

   function get_user_full_name_by_id($uid)
   {
	  security($uid,0);
	  $result=mysql_query("SELECT * FROM users WHERE id=".$uid);
	  $result=mysql_fetch_array($result);
	  return $result['name']."(".$result['realname'].")";
   }
   function get_user_name_by_id($uid)
   {
	  security($uid,0);
	  $result=mysql_query("SELECT * FROM users WHERE id=".$uid);
	  $result=mysql_fetch_array($result);
	  return $result['name'];
   }
   function file_transfer($sourced,$sourcef)
   {
	  $dest="./files/".md5("lala".$sourced."/".$sourcef);
	  if (!file_exists($dest."/".$sourcef))
	  {
		 mkdir($dest);
		 chmod($dest,0770);
		 copy($sourced."/".$sourcef,$dest."/".$sourcef);
		 chmod($dest."/".$sourcef,0640);
	  }
	  return $dest."/".$sourcef;
   }
   function file_release($sourced,$sourcef)
   {
	  $dest="./files/".md5("lala".$sourced."/".$sourcef);
	  if (file_exists($dest."/".$sourcef))
	  {
		 unlink($dest."/".$sourcef);
		 rmdir($dest);
	  }
   }
   function get_problem_name_by_id($pid)
   {
	  $result=mysql_query("SELECT * FROM problems WHERE id='".$pid."'");
	  $result=mysql_fetch_array($result);
	  return $result['name'];
   }
   function get_score($ccc)
   {
	  list($a,$b)=split("/",$ccc);
	  if ($a==0 and $b==0)return 0;
	  return 100/$b*$a;
   }
   function get_contest_info_by_problem_id($pid)
   {
	  $result=mysql_fetch_array(mysql_query("SELECT * FROM problems WHERE id=".$pid));
	  if ($result['contest']==0 or !isset($result['contest']))
	  return NULL;
	  $result=mysql_fetch_array(mysql_query("SELECT * FROM contests WHERE id=".$result['contest']));
	  return $result;
   }
   function check_for_login()
   {
	  if (!isset($_SESSION['user']))
	  {
		 echo "You Are Forbidden!";
		 die();
	  }
   }
   function check_for_name($str)
   {
	  $length=strlen($str);
	  for($i=0;$i<$length;$i++)
	  {
		 if((!is_numeric($str[$i])) and (!($str[$i]=='_')) and !(($str[$i]>='a' and $str[$i]<='z') or ($str[$i]>='A' and $str[$i]<='Z')))
		 {
			return 0;
		 }
	  }
	  return 1;
   }
   function check_for_realname($str)
   {
	  if(preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$str))
		return 0;
	  return 1;
   }
   function may_hacker()
   {
   ?>
   <script>
	  window.location.href="error.php?error=Don't hack OJ7!";
   </script>
   <?php
	  die();
   }
   function security($str,$type)
   {
	  if($type==0)
	  {
		 if(is_numeric($str))
		 {
			return 1;
		 }
		 else
		 {
			may_hacker();
		 }
	  }
	  else if($type==1)
	  {
		 if(is_numeric($str) or check_for_name($str))
		 {
			return 1;
		 }
		 else
		 {
			die();
			may_hacker();
		 }
	  }
	  else
	  {
		 if(check_for_realname($str))
		 {
			return 1;
		 }
		 else
		 {
			may_hacker();
		 }
	  }
   }
   function get_file_name($now_name)
   {
	  $re="";
	  $now_name=intval($now_name);
	  while($now_name!=(int)0)
	  {
		 $re=strval($now_name%1024)."/".$re;
		 $now_name=(int)($now_name/1024);
	  }
	  $re=substr($re,0,strlen($re)-1);
	  return $re;
   }
?>
<script type="text/javascript">
   function altRows(id){
		 if(document.getElementsByTagName){
			   var table = document.getElementById(id);
			   var rows = table.getElementsByTagName("tr");
			   for(i = 1; i < rows.length; i++){
					 if(i % 2 == 0){
						   rows[i].className = "evenrowcolor";
						}else{
						   rows[i].className = "oddrowcolor";
					 }
			   }
		 }
   }
   function copyToClipBoard(s) {
		 alert(s);
		 if (window.clipboardData) {
			   window.clipboardData.setData("Text", s);
			   alert("已经复制到剪切板！"+ "\n" + s);
			} else if (navigator.userAgent.indexOf("Opera") != -1) {
			   window.location = s;
			} else if (window.netscape) {
			   try {
					 netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
				  } catch (e) {
					 alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
			   }
			   var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
			   if (!clip)
			   return;
			   var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
			   if (!trans)
			   return;
			   trans.addDataFlavor('text/unicode');
			   var str = new Object();
			   var len = new Object();
			   var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
			   var copytext = s;
			   str.data = copytext;
			   trans.setTransferData("text/unicode", str, copytext.length * 2);
			   var clipid = Components.interfaces.nsIClipboard;
			   if (!clip)
			   return false;
			   clip.setData(trans, null, clipid.kGlobalClipboard);
			   alert("已经复制到剪切板！" + "\n" + s)
		 }
   }
</script>
