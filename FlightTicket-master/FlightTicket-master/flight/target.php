<html>
    <body>
        <div class="t1">
        <?php
            //未来1天到60天的价格
            for($j = 1; $j < 61; $j++) {
                $daysLater = mktime(0,0,0,date("m"), date("d") + $j, date("Y"));
                $date = date("Y-m-d", $daysLater);
                $site = "jipiao.kuxun.cn/";
                $city = array (
                        "haerbin",
                        "beijing",
                        "shanghai",
                        "guangzhou",
                        "shenzhen",
                        "chengdu",
                        "chongqing",
                        "hangzhou",
                        "nanjing",
                        "wuhan",
                        "wenzhou",
                        "dalian",
                        "tianjin",
                        "fuzhou",
                );
                for($i = 1; $i < count($city); $i++) {
                    $url = "http://" . $site . $city[0] . "-" . $city[$i] . ".html?" . $date;
                    echo "<a class = \"site\" href = " . $url . ">" . $url . "</a></br>";

                    $url = "http://" . $site . $city[$i] . "-" . $city[0] . ".html?" . $date;
                    echo "<a class = \"site\" href = " . $url . ">" . $url . "</a></br>";
                }
            }
        ?>
        </div>
        <div class="t2">
            <a class="t2" href="xml.php">doxml</a>
        </div>
    </body>
</html>
