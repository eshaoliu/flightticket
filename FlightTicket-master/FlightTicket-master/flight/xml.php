<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        date_default_timezone_set("Asia/Harbin");
        header("content-type: text/html; charset=utf-8");
        set_time_limit(10000);  //允许处理时间
        //连接数据库
        $con = mysql_connect('localhost', 'root', '') or 
            die ("connect failed" . mysql_error());
        mysql_select_db("flight") or die(mysql_error());
        mysql_query("set names 'utf8'");

        //处理每个XML文件,写入数据库
        foreach(glob("C:\\Users\\Administrator\\DataScraperWorks\\wbh_test2\\*.xml") 
                as $xmlfile)
        {
            $fp = fopen($xmlfile, "r");  //打开文件
            $xmlparser = xml_parser_create();  //创建解析XML解析器
            $xmldata = fread($fp, filesize($xmlfile));  //读取整个文件
            fclose($fp);  //关闭文件
            //将内容解析到数组
            //$values保存内容 $index保存索引
            xml_parse_into_struct($xmlparser, $xmldata, $values, $index);
            //var_dump($values);
            //var_dump($index);
            //保存元组
            $a = array(
              "PRICE" => "",
              "CODE" => "",
              "DATE" => ""
            );
            //在url中找到日期
            $pattern = "/\d{4}-\d{2}-\d{2}/";
            $p = $index["FULLPATH"][0];
            $subject = $values[$p]["value"];
            if(preg_match($pattern, $subject, $matches) == 1) 
                $a["DATE"] = $matches[0];  //存到$a里
            else
                continue;
            //提取code price
            for($i = 0; $i < count($index["PRICE"]); $i++) {
                if(isset($values[($index["PRICE"][$i])]["value"])) {
                    $a["CODE"] = $values[($index["CODE"][$i])]["value"];
                    $a["PRICE"] = $values[($index["PRICE"][$i])]["value"];
                    $sql = sprintf("insert into priceinfo
                        values('%s', '%s', '%s') on duplicate key update 
                        `price`=values(`price`)", $a["PRICE"], $a["CODE"], $a["DATE"]);
                    //加入数据库
                    mysql_query($sql, $con);
                }
            }       
        }       
        xml_parser_free($xmlparser);
        mysql_close($con);
        //删除所有XML文件
        $dir = "C:\\Users\\Administrator\\DataScraperWorks\\wbh_test2";
        deldir($dir);

        function deldir($dir) {
            $dh=opendir($dir);
            while ($file=readdir($dh)) {
                if($file!="." && $file!="..") {
                    $fullpath=$dir."/".$file;
                    if(!is_dir($fullpath)) {
                        unlink($fullpath);
                    } else {
                        deldir($fullpath);
                    }
                }
            }
            closedir($dh);
            if(rmdir($dir)) {
                return true;
            } else {
                return false;
            }
        }
        //}
        ?>
        <div class="ok">
            <p><?php echo date("Y-m-d H:i", $_SERVER["REQUEST_TIME"]); ?></p>
        </div>
    </body>
</html>
