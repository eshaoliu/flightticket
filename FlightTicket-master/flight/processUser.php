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
			  "price" =>""
            );
  $a["mail"]=trim($_GET["mail"]);
  $a["startcity"]=trim($_GET["startcity"]);
  $a["endcity"]=trim($_GET["endcity"]);
  $a["date"]=trim($_GET["date"]);
  $a["price"]=trim($_GET["price"]);
  if(strlen($a["startcity"])!=0 && strlen($a["endcity"])!=0)
  {
      $sql_user= sprintf(
      " insert into user values('%s','%s','%s','%s','%s') "
	  ,$a["mail"],$a["startcity"],$a["endcity"],$a["date"],$a["price"]);
	  mysql_query("set names utf8");
	 if(mysql_query($sql_user,$con)==false)
	   echo '<script language="javascript">window.alert("您已经提交过了"); </script>';   
	 else
	   echo '<script language="javascript">window.alert("您已成功开启提醒"); </script>';    
  }
  else
  {
      echo '<script language="javascript">
	    window.alert("输入错误");
		window.history.back(-1);//回到上一页
        </script>';   
	 // Header("Location:./user.html");
 }
/*-------------------------------------------*/
?>

</body>
</html>