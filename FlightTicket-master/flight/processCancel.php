<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User input personal info</title>
</head>
<body>
<?php
//连接数据库
$con = mysql_connect('localhost', 'root', '') or 
    die ("connect failed" . mysql_error());
mysql_select_db("flight") or die(mysql_error());
mysql_query("set names 'utf8'");
/*-------mine-----------*/
  $a = array(
              "mail" => "",
              "startcity" => "",
              "endcity" => "",
			  "date" =>"",
            );
  $a["mail"]=trim($_GET["mail"]);
  $a["startcity"]=trim($_GET["startcity"]);
  $a["endcity"]=trim($_GET["endcity"]);
  $a["date"]=trim($_GET["date"]);
  if(strlen($a["startcity"])!=0 && strlen($a["endcity"])!=0)
  {
      $sql_query="SELECT * FROM user where  mailaddress='".$a['mail']."' AND startcity='".$a['startcity']."'AND endcity='".$a['endcity']."'AND date='".$a['date']."'";
	  
	  $query=mysql_query($sql_query,$con);
	  $result=mysql_fetch_array($query);
	  if(!$result)
	      echo '<script language="javascript">window.alert("您还没有设置这样的提醒或是这样的提醒已经删除");</script>';  
      else
      {
		  $sql_cancel="DELETE FROM user where mailaddress='".$a['mail']."' AND startcity='".$a['startcity']."'AND endcity='".$a['endcity']."'AND date='".$a['date']."'";
		  mysql_query($sql_cancel,$con);
	      echo '<script language="javascript">window.alert("您已成功取消提醒");</script>';  
	  }
  }  
  else
  {
      echo '<script language="javascript">window.alert("输入错误");window.history.back(-1);//回到上一页</script>';   
	 // Header("Location:./user.html");
  }
/*-------------------------------------------*/
?>

</body>
</html>