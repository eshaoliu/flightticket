<?php
    header("content-type: text/html; charset=utf-8");
    //允许处理时间
    set_time_limit(10000);
    $city = array("哈尔滨","北京","上海","广州","深圳","成都","重庆","杭州",
            "南京","武汉","温州","大连","天津","福州");
    $wsdl = "http://www.webxml.com.cn/webservices/DomesticAirline.asmx?WSDL";
    $client = new SoapClient($wsdl);
    $param = array(
        "startCity" => "",
        "lastCity" => "",
        "theDate" => "",
        "userID" => ""
    );
    //连接数据库
    $con = mysql_connect('localhost', 'root', '') or 
    die ("connect failed" . mysql_error());
    mysql_select_db("flight") or die(mysql_error());
    mysql_query("set names 'utf8'");

    //var_dump(count($city));
    //设置出发地和目的地
    //从webservice得到信息
    for($i = 1; $i < count($city); $i++)
    {
        for($x = 0; $x < 2; $x++) {
            if($x == 0) {
                $param["startCity"] = $city[0];
                $param["lastCity"] = $city[$i];
            }
            elseif($x == 1) {
                $param["startCity"] = $city[$i];
                $param["lastCity"] = $city[0];
            }
            $result = $client->getDomesticAirLinesTime($param);
            //把对象数据转为数组
            $a = obj_to_arr($result);
            //只要查询结果的那部分
            $b = $a["getDomesticAirlinesTimeResult"]["any"];
            //解析XML
            $xmlparser = xml_parser_create();
            //将内容解析到数组
            //$values保存内容 $index保存索引
            xml_parse_into_struct($xmlparser, $b, $values, $index);

            $a = array(
                "COMPANY" => "",
                "AIRLINECODE" => "",
                "STARTDROME" => "",
                "ARRIVEDROME" => "",
                "STARTTIME" => "",
                "ARRIVETIME" => "",
                "MODE" => "",
                "AIRLINESTOP" => "",
                "WEEK" => ""
            );
            //提取信息
            for($j = 0; $j < count($index["COMPANY"]); $j++)
            {
                foreach($a as $key => $val)
                {
                    $a[$key] = $values[$index[$key][$j]]["value"];
                }
                $sql = sprintf(
                    "insert into airlinesinfo 
                     values('%s','%s','%s','%s','%s','%s','%s', '%s','%s','%s','%s')
                     on duplicate key update `airlinecode`=values(`airlinecode`) and 
                     `mode`=values(`mode`) and `week`=values(`week`)",
                     $a["COMPANY"],$a["AIRLINECODE"],$a["STARTDROME"],
                     $a["ARRIVEDROME"], $a["STARTTIME"], $a["ARRIVETIME"],$a["MODE"],
                     $a["AIRLINESTOP"], $a["WEEK"], $param["startCity"], $param["lastCity"]
                );
                //加入数据库
                mysql_query($sql, $con);
            }
        }
    }
    mysql_close($con);
    xml_parser_free($xmlparser);

    function obj_to_arr($obj)
    {
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach($arr as $key => $val)
        {
            $val = (is_array($val) || is_object($val)) ? obj_to_arr($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }
?>

