FlightTicket-航班价格动态展示
==============
项目背景
------------
- 国内航空市场火热，购买机票的用户数量很大
- 航班价格不同于火车票，存在不确定性的价格波动
- 人们需要直观的掌握最近的未来机票变化趋势，以买到价格合适的机票

项目功能
--------
- 周期性从[酷讯机票网](http://jipiao.kuxun.cn/)上动态获取国内航班信息(航线、时间、价格等)
- 对任意时间段内任意两个城市之间的航班价格的变化趋势进行可视化展示
- 对多个航空公司在同一航线的航班价格进行动态对比
- 支持单程/往返的机票查询

Windows下环境搭建
--------
1. 安装[XAMPP](http://www.apachefriends.org/zh_cn/xampp.html)，安装完成之后的根目录名叫`xampp`
2. 安装[FireFox](http://firefox.com.cn/)
3. 分别安装两个FireFox插件，分别是[MetaStudio](http://www.metacamp.cn/goomaster/secure/releaselist.htm?language=zh&nego_client=MetaStudio&nego_version=4.10.0)和[DataScraper](http://www.metacamp.cn/goomaster/secure/releaselist.htm?language=zh&nego_client=MetaStudio&nego_version=4.10.0)
4. 将项目根目录下`flight`，`Highcharts-2.3.3`，`jquery-ui-1.9.1.custom`文件夹拷贝到`xampp\htdocs\`下
5. 将项目根目录下`crontab.xml`文件拷贝到C盘用户目录下的`.datascraper\`文件夹下，如`C:\Users\你的用户名\.datascraper\`
6. 用MySQL命令行或用XAMPP自带的phpMyAdmin创建数据库`flight`
7. 将项目根目录下`flight_structure.sql`文件导入`flight`数据库，使其生成所需的table
8. 运行FireFox已安装的插件DataScraper实现每隔半小时的抓取工作
   
使用说明
------------------------
通过浏览器访问`flight/index.html`即可
有问题？
-------
发邮件至`scorpio147@126.com`
