<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Refresh" content="1800" > 
    </head>
    <body>
        <?php
		require_once "../mailinphp/email.class.php";
        date_default_timezone_set("Asia/Harbin");
        header("content-type: text/html; charset=utf-8");
        set_time_limit(10000);  //允许处理时间
        //连接数据库
        $con = mysql_connect('localhost', 'root', '') or 
            die ("connect failed" . mysql_error());
        mysql_select_db("flight") or die(mysql_error());
        mysql_query("set names 'utf8'");
        //处理每个XML文件,写入数据库
        foreach(glob("C:\\Users\\lenovo\\DataScraperWorks\\lhr_test3\\*.xml") 
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
            //var_dump($index);            //保存元组
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
            {
     			$a["DATE"] = $matches[0];  //存到$a里
			}
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
            xml_parser_free($xmlparser);
        }
        //删除所有XML文件
        $dir = "C:\\Users\\lenovo\\DataScraperWorks\\lhr_test3";
        deldir($dir);

        function deldir($dir) {
            $dh=@opendir($dir);
            while ($file=@readdir($dh)) {
                if($file!="." && $file!="..") {
                    $fullpath=$dir."/".$file;
                    if(!is_dir($fullpath)) {
                        unlink($fullpath);
                    } else {
                        deldir($fullpath);
                    }
                }
            }
            @closedir($dh);
            if(@rmdir($dir)) {
                return true;
            } else {
                return false;
            }
        }
        
			/*-----------------------MINE-------------------*/		
	    
	    $sql_flightprice = " SELECT `mailaddress` ,`airlinesinfo`.`startcity`, `airlinesinfo`.`arrivecity`,`company`,`priceinfo`.`airlinecode` , `priceinfo`.`date`, `starttime`, `arrivetime` , `priceinfo`.`price`,`user`.`price` as `uspr`
	    FROM  `airlinesinfo` ,  `user` , `priceinfo`
	    WHERE 
	    `airlinesinfo`.`startcity` = `user`.`startcity`
		AND `airlinesinfo`.`arrivecity` = `user`.`endcity`
        AND `priceinfo`.`date` = `user`.`date`
		AND `airlinesinfo`.`airlinecode` = `priceinfo`.`airlinecode` 
        ";
		$sql_low=$sql_flightprice."AND  `user`.`price` > `priceinfo`.`price`";
	    $sql_high=$sql_flightprice."AND  `user`.`price` < `priceinfo`.`price`";
	    $result_fp_low = mysql_query($sql_low, $con);
		$result_fp_high= mysql_query($sql_high, $con);
	    while($row_fr = mysql_fetch_assoc($result_fp_low)) {
		    echo $row_fr["mailaddress"].'<br />';
		    echo $row_fr["startcity"].'<br />';
		    echo $row_fr["arrivecity"].'<br />';
			echo $row_fr["company"].'<br />';
		    echo $row_fr["airlinecode"].'<br />';
		    echo $row_fr["date"].'<br />';
		    echo $row_fr["starttime"].'<br />';
		    echo $row_fr["arrivetime"].'<br />';
		    echo $row_fr["price"].'<br />';
		    echo $row_fr["uspr"].'<br />';
			$smtpserver = "smtp.163.com";//SMTP服务器
			$smtpserverport =25;//SMTP服务器端口
			$smtpusermail = "flightticketnofify@163.com";//SMTP服务器的用户邮箱
			$smtpemailto = $row_fr["mailaddress"];//发送给谁
			$smtpuser = "flightticketnofify";//SMTP服务器的用户帐号
			$smtppass = "liueshao";//SMTP服务器的用户密码
			$mailtitle = "您查询的票价有更新";//邮件主题
			$mailcontent = "<h1>"."您想预定的".$row_fr["date"]."从".$row_fr["startcity"]."到".$row_fr["arrivecity"]."的低于预期票价".$row_fr["uspr"]."元的最低机票新价格为".$row_fr["price"]."元，航空公司是".$row_fr["company"]."，航班号是".$row_fr["airlinecode"]."起飞时刻为".$row_fr["starttime"]."降落时刻是".$row_fr["arrivetime"]."</h1>";//邮件内容
			$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
			//************************ 配置信息 ****************************
			$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
			$smtp->debug = false;//是否显示发送的调试信息
			$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

			echo "<div style='width:300px; margin:36px auto;'>";
			if($state==""){
				echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
				echo "<a href='index.html'>点此返回</a>";
				exit();
			}
			echo "恭喜！邮件发送成功！！";
			echo "<a href='index.html'>点此返回</a>";
			echo "</div>";
	    }
		while($row_fr = mysql_fetch_assoc($result_fp_high)) {
		    $smtpserver = "smtp.163.com";//SMTP服务器
			$smtpserverport =25;//SMTP服务器端口
			$smtpusermail = "flightticketnofify@163.com";//SMTP服务器的用户邮箱
			$smtpemailto = $row_fr["mailaddress"];//发送给谁
			$smtpuser = "flightticketnofify";//SMTP服务器的用户帐号
			$smtppass = "liueshao";//SMTP服务器的用户密码
			$mailtitle = "您查询的票价有更新";//邮件主题
			$mailcontent = "<h1>"."您想预定的".$row_fr["date"]."从".$row_fr["startcity"]."到".$row_fr["arrivecity"]."的高于预期票价".$row_fr["uspr"]."元的最低机票新价格为".$row_fr["price"]."元，航空公司是".$row_fr["company"]."，航班号是".$row_fr["airlinecode"]."起飞时刻为".$row_fr["starttime"]."降落时刻是".$row_fr["arrivetime"]."</h1>";//邮件内容
			$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
			//************************ 配置信息 ****************************
			$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
			$smtp->debug = false;//是否显示发送的调试信息
			$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

			echo "<div style='width:300px; margin:36px auto;'>";
			if($state==""){
				echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
				echo "<a href='index.html'>点此返回</a>";
				exit();
			}
			echo "恭喜！邮件发送成功！！";
			echo "<a href='index.html'>点此返回</a>";
			echo "</div>";
	    }
	    mysql_close($con);
	 //******************** 配置信息 ********************************/
        ?>
        <div class="ok">
            <p><?php echo date("Y-m-d H:i", $_SERVER["REQUEST_TIME"]); ?></p>
        </div>
    </body>
</html>

