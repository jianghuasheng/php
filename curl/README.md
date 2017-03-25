
# PHP模拟登录学校教务系统并获取数据 #
> 这个小功能主要的代码是当时创业校联圈（大概在2016-09月）的时候写的，当时这个功能模块没有真正上线运营，所以今天开源给大家，和大家聊聊其中的技术^^。
> 这里主要是运用了PHP中的一个CURL库。CURL是一个功能强大的PHP库，可以简单和有效地抓取网页并采集内容，设置cookie完成模拟登录网页。

<a href="http://exam.jianghuasheng.cn/course/index.html" target="_blank">这里是测试传送门（仅限<b style='color:red;'>广东石油化工学院同学</b>哟）</a>

> 好了，以下主要介绍下实现的过程，有错误或者有更好的办法欢迎留言交流^^

## 这里主要分为两个比较关键点的步骤： ##


### 一、爬出登陆教务系统时要提交的数据 ###
> 登陆教务系统的时候要输入学号和密码，但是这两肯定是不够的，如下图。所以这里用了Fiddler来抓提交的数据（不知道Fiddler的使用的同学可以百度下哈），然后就设置curl时要提交的数据来模拟登陆。（不知道curl的具体使用的同学可以看看这里[http://blog.csdn.net/j_h_s/article/details/65698343](http://blog.csdn.net/j_h_s/article/details/65698343 "http://blog.csdn.net/j_h_s/article/details/65698343")）

- **分析登陆时要提交的数据**
![教务系统登陆](http://on225liw3.bkt.clouddn.com/course_login.png)

- **登陆时fiddler抓包的数据**
![fiddler的数据](http://on225liw3.bkt.clouddn.com/course_fiddler.png)

- **curl设置的数据**
![curl设置的数据](http://on225liw3.bkt.clouddn.com/course_curl.png)
- **登陆成功后台主页**
![登陆成功后台主页](http://on225liw3.bkt.clouddn.com/course_index.png)


### 二、模拟登陆后抓取的数据处理 ###

> 登陆成功后关键是怎样去爬我们需要排课或者成绩等信息呢？
> 通过分析，这个教务系统的后台主要是通过生成json数据存储课程等信息。所以我用curl_exec函数返回的页面的数据通过特定字符串来截取我们需要的信息。大概步骤如下图：

- **通过分析排课页面获得的数据**
![通过分析排课页面获得的数据](http://on225liw3.bkt.clouddn.com/course_paike.png)

- **接收接受返回的数据**
![接收接受返回的数据](http://on225liw3.bkt.clouddn.com/course_curl3.png)![登陆成功后台主页](http://on225liw3.bkt.clouddn.com/course_curl4.png)

- **特定字符串截取关键数据**
- ![特定字符串截取关键数据1](http://on225liw3.bkt.clouddn.com/course_curl2.png)
![特定字符串截取关键数据2](http://on225liw3.bkt.clouddn.com/course_curl5.png)

- **到这就基本实现目的了，接下来就是简单的页面显示爬得的数据了，这里就不做介绍了，附上测试的结果图和地址^^**
![测试结果1](http://on225liw3.bkt.clouddn.com/course_test1.png)
![测试结果2](http://on225liw3.bkt.clouddn.com/course_test2.png)
<a href="http://exam.jianghuasheng.cn/course/index.html" target="_blank">这里是测试传送门（仅限<b style='color:red;'>广东石油化工学院同学</b>哟）</a>

> **后语：** 当然，真正上线运营可能就起来并没有这么简单，例如我们这里是直接每次登录来获得用户的学号和密码，为了更便捷，可以用数据库来保存用户的信息或者绑定到其他账号，就好像微信那里一样。还有，就是当用户输入错误的学号或者密码怎么判断等等，这里只做简单的介绍，欢迎留言讨论^^!

**最后附上测试地址和github地址**
- [测试地址：http://exam.jianghuasheng.cn/course/index.html](http://exam.jianghuasheng.cn/course/index.html "http://exam.jianghuasheng.cn/course/index.html")
- [源码github:https://github.com/jianghuasheng/php/curl](https://github.com/jianghuasheng/php/curl "https://github.com/jianghuasheng/php/curl")



