var chart1;
var chart2;
var options1 = {
    chart: {
        renderTo: 'diagram',
        type: 'line'
      },
    title: {
        text: '每日最低票价图'
    },
    xAxis: {
        type: 'datetime'
    },
    yAxis: {
        title: {
            text: '价格(人民币)'
        },
    },
    tooltip: {
		crosshairs: true,
        formatter: function() {
        return Highcharts.dateFormat('%b %e',this.x) + '<br><b>最低价格:' + this.y + '元<br><b>' + this.point.name;
        }
    },
    series: [{}]
};

var options2 = {
    chart: {
        renderTo: 'diagram',
        type: 'line'
      },
    title: {
        text: '航空公司票价对比图'
    },
    xAxis: {
        type: 'datetime'
    },
    yAxis: {
        title: {
            text: '价格(人民币)'
        },
    },
    tooltip: {
		crosshairs: true,
        formatter: function() {
        return Highcharts.dateFormat('%b %e',this.x) + '<br><b>最低价格:' + this.y + '元<br><b>' + this.point.name;
        }
    },
    series: [{}]
};
$(function() {
    $( document ).tooltip();
});

$(document).ready(function() {
    //tabs
    $("#tabs").tabs();
    //datepicker
    $("#from_date").datepicker({dateFormat: "yy-mm-dd"});
    $("#from_date2").datepicker({dateFormat: "yy-mm-dd"});
    $("#to_date").datepicker({dateFormat: "yy-mm-dd"});
    $("#to_date2").datepicker({dateFormat: "yy-mm-dd"});

    $("#search_type :radio").click(function() {
        if($(this).val()=="round_trip_flight") {
            $("#to").show();
        }
        else {
            $("#to").hide();
        }
        });
    $("#form").submit(function() {
        var formData = $(this).serialize();
        $.getJSON("process.php", formData, processData);
        function processData(data) {
            $("#diagram").hide();
            $("#result_list").show();
            //round_trip
            if(data.to) {
                var info = "<div class=\"Round_trip_list\"><div class=\"Header\"><div class=\"Go\" id=\"go\"><ul class=\"Titlex\"><li class=\"Titlex1\">起 / 降时间</li><li class=\"Titlex2\">航空公司</li><li class=\"Titlex3\">最低价(不含税)</li></ul></div><div class=\"Return\" id=\"return\"><ul class=\"Titlex\"><li class=\"Titlex1\">起 / 降时间</li><li class=\"Titlex2\">航空公司</li><li class=\"Titlex3\">最低价(不含税)</li></ul></div></div><div class=\"List\"><div class=\"Go\" id=\"result_list1\">"; 

                $.each(data.from, function(res, detail) {
                    var temp = "\"航班号:<b>" + detail.airlinecode +
                        "</b><br />起飞机场:<b>" + detail.startdrome +
                        "</b><br />降落机场:<b>" + detail.arrivedrome + "</b>\"" ;
                    info += "<div class=\"Flightx\" title=" + temp + ">";
                    info += "<ul class=\"TicketsListx\">";

                    info += "<li class=\"Timex\">" + detail.starttime + "</br>" + detail.arrivetime + "</li>";
                    info += "<li class=\"Companyx\">" + detail.company + "</li>";  

                    info += "<li class=\"Pricex\">" + detail.price + "</li>";

                    info += "</ul></div>";
                });

                info += "</div><div class=\"Return\" id=\"result_list2\">";

                $.each(data.to, function(res, detail) {
                    var temp = "\"航班号:<b>" + detail.airlinecode +
                        "</b><br />起飞机场:<b>" + detail.startdrome +
                        "</b><br />降落机场:<b>" + detail.arrivedrome + "</b>\"" ;
                    info += "<div class=\"Flightx\" title=" + temp + ">";
                    info += "<ul class=\"TicketsListx\">";

                    info += "<li class=\"Timex\">" + detail.starttime + "</br>" + detail.arrivetime + "</li>";
                    info += "<li class=\"Companyx\">" + detail.company + "</li>";  

                    info += "<li class=\"Pricex\">" + detail.price + "</li>";

                    info += "</ul></div>";
                });

                info += "</div></div></div>";
                $("#result_list").html(info);
            }
            //single_way
            else {
                var from_info = "<ul class=\"Title\"><li class=\"Title1\">航空公司</li><li class=\"Title2\">航班号 / 机型</li><li class=\"Title3\">起 / 降机场</li><li class=\"Title4\">起 / 降时间</li><li class=\"Title5\">最低价(不含税)</li></ul>";

                $.each(data.from, function(res, detail) {
                    from_info += "<div class=\"Flight\">";
                    from_info += "<ul class=\"TicketsList\">";

                    from_info += "<li class=\"Company\">" + detail.company + "</li>";  
                    from_info += "<li class=\"Code\">" + detail.airlinecode + "<p>" + detail.mode + "</p></li>";

                    from_info += "<li class=\"Drome\">" + detail.startdrome + "</br>" + detail.arrivedrome + "</li>";  

                    from_info += "<li class=\"Time\">" + detail.starttime + "</br>" + detail.arrivetime + "</li>";

                    from_info += "<li class=\"Price\">" + detail.price + "</li>";

                    from_info += "</ul></div>";
                });

                $("#result_list").html(from_info);
            }

        }
        return false;
    });
    $("#form2").submit(function() {
        var formData = $(this).serialize();
        $.getJSON("test.php", formData, function(data) {
            $("#diagram").show();
            $("#result_list").hide();
            if($("input[name='option']:checked").val() == "lowest") {
                var series_data = [];
                $.each(data, function(res, detail) {
                    var temp = detail.date.split("-"); 
                    var info =	'航班号:'+detail.airlinecode + 
                                '<br><b>航空公司:'+detail.company +
								'<br><b>起飞时间:'+detail.starttime + 
								'<br><b>降落时间:'+detail.arrivetime +
								'<br><b>起飞机场:'+detail.startdrome +
								'<br><b>降落机场:'+detail.arrivedrome;
					series_data.push({name: info,x: Date.UTC(temp[0], temp[1] - 1, temp[2]), y: parseInt(detail.price, 10)});
                    });
                options1.series[0].data = series_data;
				options1.series[0].name = '每日最低票价';
                chart1 = new Highcharts.Chart(options1);
            }
            else if($("input[name='option']:checked").val() == "contrast") {
                var series_data = [];
                $.each(data, function(res, detail) {
                    var small = [];
                    $.each(detail.data, function(index, element) {
                        var temp = element.date.split("-");
						var info =	'航班号:'+element.airlinecode + 
									'<br><b>起飞时间:'+element.starttime + 
									'<br><b>降落时间:'+element.arrivetime +
									'<br><b>起飞机场:'+element.startdrome +
									'<br><b>降落机场:'+element.arrivedrome;
                        small.push({name: info,
									x: Date.UTC(temp[0], temp[1] - 1, temp[2]), 
									y: parseInt(element.price, 10)});
                        });
                    series_data.push({name: detail.company, data: small});
                    });
                options2.series = series_data;

                chart2 = new Highcharts.Chart(options2);
            }

        });
        return false;

    });
});

