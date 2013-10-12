<?php
//连接数据库
$con = mysql_connect('localhost', 'root', '') or 
    die ("connect failed" . mysql_error());
mysql_select_db("flight") or die(mysql_error());
mysql_query("set names 'utf8'");

if(isset($_GET["search_type"])) {
  $start_city = $_GET["from_city"];
  $arrive_city = $_GET["to_city"];
  $from_date = $_GET["from_date"];
  $to_date = $_GET["to_date"];
  $search_type = $_GET["search_type"];
  $arr_from = array(); 
  $arr_to = array(); 
  $arr_draw = array(); 
  /*------------------查去程--------------------*/
  $sql_from = sprintf(
      "SELECT  `company` ,  `priceinfo`.`airlinecode` ,  `mode` ,  `startdrome`, `arrivedrome`, `starttime`,  `arrivetime` ,  `price` 
      FROM  `airlinesinfo` ,  `priceinfo` 
      WHERE  `date` =  '%s'
      AND  `priceinfo`.`airlinecode` =  `airlinesinfo`.`airlinecode` 
      AND  `startcity` =  '%s'
      AND  `arrivecity` =  '%s'",
      $from_date, $start_city, $arrive_city
      );

  $result_from = mysql_query($sql_from, $con);
  while($row_from = mysql_fetch_assoc($result_from)) {
    $arr_from[] = array(
        "company" => $row_from["company"],
        "airlinecode" => $row_from["airlinecode"],
        "mode" => $row_from["mode"],
        "startdrome" => $row_from["startdrome"],
        "arrivedrome" => $row_from["arrivedrome"],
        "starttime" => $row_from["starttime"],
        "arrivetime" => $row_from["arrivetime"],
        "price" => $row_from["price"]
        );
  }
  /*------------------查返程--------------------*/
  if($search_type == "round_trip_flight")
  {
    $sql_to = sprintf(
      "SELECT  `company` ,  `priceinfo`.`airlinecode` ,  `mode` ,  `startdrome`, `arrivedrome`, `starttime` ,  `arrivetime` ,  `price` 
      FROM  `airlinesinfo` ,  `priceinfo` 
      WHERE  `date` =  '%s'
      AND  `priceinfo`.`airlinecode` =  `airlinesinfo`.`airlinecode` 
      AND  `startcity` =  '%s'
      AND  `arrivecity` =  '%s'",
      $to_date, $arrive_city, $start_city
      );
    $result_to = mysql_query($sql_to, $con);
    while($row_to = mysql_fetch_assoc($result_to)) {
      $arr_to[] = array(
          "company" => $row_to["company"],
          "airlinecode" => $row_to["airlinecode"],
          "mode" => $row_to["mode"],
          "startdrome" => $row_to["startdrome"],
          "arrivedrome" => $row_to["arrivedrome"],
          "starttime" => $row_to["starttime"],
          "arrivetime" => $row_to["arrivetime"],
          "price" => $row_to["price"]
          );
    }
  }

  /*------------------汇总发送--------------------*/
  if($search_type == "round_trip_flight") {
    $arr_all = array(
        "from" => $arr_from,
        "to" => $arr_to,
        );
  }  
  else {
    $arr_all = array(
        "from" => $arr_from,
        );
  }
  mysql_close($con);
  echo json_encode($arr_all);
}

?>
