<?php 
    header("content-type: text/html; charset=utf-8");
    //连接数据库
    $con = mysql_connect('localhost', 'root', '') or 
        die ("connect failed" . mysql_error());
    mysql_select_db("flight") or die(mysql_error());
    mysql_query("set names 'utf8'");

    if(isset($_GET["option"])) {
        $start_city = $_GET["from_city"];
        $arrive_city = $_GET["to_city"];
        $from_date = $_GET["from_date2"];
        $to_date = $_GET["to_date2"];
        $option = $_GET["option"];
      
        if($option == "lowest") {
            $sql_draw = sprintf(
                "SELECT * , MIN(`price`)
                 FROM  `airlinesinfo` ,  `priceinfo` 
                 WHERE  `date` <= '%s'
                 AND  `date` >= '%s'
                 AND  `priceinfo`.`airlinecode` =  `airlinesinfo`.`airlinecode` 
                 AND  `startcity` =  '%s'
                 AND  `arrivecity` =  '%s'
                 group by `date`",
                $to_date, $from_date, $start_city, $arrive_city
            );

            $result_draw = mysql_query($sql_draw, $con);

            while($row = mysql_fetch_assoc($result_draw)) {
                $arr_draw[] = array(
                    "company" => $row["company"],
                    "airlinecode" => $row["airlinecode"],
                    "startdrome" => $row["startdrome"],
                    "arrivedrome" => $row["arrivedrome"],
                    "starttime" => $row["starttime"],
                    "arrivetime" => $row["arrivetime"],
                    "mode" => $row["mode"],
                    "airlinecode" => $row["airlinecode"],
                    "date" => $row["date"],
                    "price" => $row["MIN(`price`)"]
                );
            }
            echo json_encode($arr_draw);

        }
        else if($option == "contrast") {
            $sql_draw = sprintf(
                "SELECT * , MIN(`price`)
                 FROM  `airlinesinfo` ,  `priceinfo` 
                 WHERE  `date` <= '%s'
                 AND  `date` >= '%s'
                 AND  `priceinfo`.`airlinecode` =  `airlinesinfo`.`airlinecode` 
                 AND  `startcity` =  '%s'
                 AND  `arrivecity` =  '%s'
                 group by `airlinesinfo`.`company`, `date`",
                $to_date, $from_date, $start_city, $arrive_city
            );
            //var_dump($sql_draw);

            $result_draw = mysql_query($sql_draw, $con);

            $arr_draw = array();
            $arr_data = array();
            $company = "";
            while($row = mysql_fetch_assoc($result_draw)) {
                if($company && $company != $row["company"]) {
                    $arr_draw[] = array(
                        "company" => $company,
                        "data" => $arr_data
                    );
                    //var_dump($arr_data);
                    $arr_data = array();
                }
                $company = $row["company"];
                //var_dump($company);

                $arr_data[] = array(
                    "airlinecode" => $row["airlinecode"],
                    "startdrome" => $row["startdrome"],
                    "arrivedrome" => $row["arrivedrome"],
                    "starttime" => $row["starttime"],
                    "arrivetime" => $row["arrivetime"],
                    "mode" => $row["mode"],
                    "airlinecode" => $row["airlinecode"],
                    "date" => $row["date"],
                    "price" => $row["MIN(`price`)"]
                );
            }
            $arr_draw[] = array(
                "company" => $company,
                "data" => $arr_data
            );
            //var_dump($arr_draw);
            echo json_encode($arr_draw);
        }
    }
    mysql_close($con);
?>
