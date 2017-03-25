<?php

// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.jianghuasheng.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 江华生 <jianghuasheng333gmail.com>  2017-03-23
// +----------------------------------------------------------------------
// | Desc: 本接口为测试专用，如有未经允许调用必追究责任！
// |       本接口主要通过curl模拟登陆教务系统和通过截取特定字符来获取信息
// +----------------------------------------------------------------------


// 判断是否post数据
if (isset($_POST['xuehao']) && isset($_POST['password'])) {
    $xuehao = $_POST['xuehao'];
    $pwd = $_POST['password'];
    //判断是否为空
    if (empty($xuehao) || empty($pwd)) {
        // 不能为空
        $ret = array('code' => 401,'msg'=>"学号或者密码不能为空"); 
    }else{
        // 查询的类型(默认为成绩)
        $type = isset($_POST['type']) ? $_POST['type'] : '1';

         #POST 验证登陆，获取登录标志
        $curl = curl_init();
        $url = 'http://教务系统IP地址:端口号/login.aspx';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_NOBODY, 1);
         
        $post_data = array(
            '__EVENTTARGET' => 'Window1$Toolbar1$btn_login', 
            '__EVENTARGUMENT' => '', 
            '__VIEWSTATE' => '', 
            'Window1$SimpleForm1$tbx_XueHao' => $xuehao, 
            'Window1$SimpleForm1$tbx_pwd' => $pwd, 
            'Window1$SimpleForm1$rdl_shenFen' => '学生', 
            'Window1_Hidden' => false, 
            'X_TARGET' => 'Window1_Toolbar1_btn_login', 
            'Window1_SimpleForm1_Collapsed' => false, 
            'Window1_Collapsed' => false, 
            'X_CHANGED' => false, 
            'X_STATE' => 'e30=', 
            'X_AJAX' => true, 
            );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //设定返回的数据是否自动显示
        curl_setopt($curl, CURLOPT_HEADER, false);//设定是否显示头信息
        curl_setopt($curl, CURLOPT_NOBODY, false); //设定是否输出页面内容
        curl_exec($curl);
        // curl_close($curl);
        if (curl_errno($curl))
        {
            $errno1 =  curl_error($curl);
            $ret = array('code' => 402,'msg'=>$errno1); 
            echo json_encode($ret);
            exit;
        }
        else
        {
            curl_close($curl);
        }

        #进入教务系统后台主页
        $curl = curl_init();
        // 查询课程
        if ($type == "1") {
            $url = 'http://教务系统IP地址:端口号/PaiKeXiTong.aspx';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $ret = curl_exec($curl);    //接受返回的数据
            // echo strlen($ret)."<br>";
            $a = strpos($ret,'"Values":')+10;   //获取指定位置
            // echo strlen($a);
            // echo $a."<br>";
            $b = strpos($ret,'"]],');         //获取指定位置
            // echo $b;             
            $match = substr($ret,$a,$b-$a);      //截取指定位置的内容
            $matchData = $match.'"]]';

        // 查询成绩
        }else  if($type == "2"){
            $url = 'http://教务系统IP地址:端口号/ChengJiChaXun.aspx';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $ret = curl_exec($curl);    //接受返回的数据
            $a = stripos($ret,'"Values":')+10;     //获取指定位置
            $b = stripos($ret,']],');              //获取指定位置
            $match = substr($ret,$a,$b-$a);      //截取指定位置的内容
            $matchData = $match."]]";
        }else {
            $ret = array('code' => 402,'msg'=>"查询出错，请重新选择您要查询的类型！"); 
            echo json_encode($ret);
            exit;
        }

        if (curl_errno($curl))
        {
            $errno2 =  curl_error($curl);
            $ret = array('code' => 402,'msg'=>$errno2); 
            echo json_encode($ret);
            exit;
        }
        else
        {
            curl_close($curl);
        }

        $ret = array('code' => 201,'msg'=>"查询成功","data"=>$matchData,"type"=>$type);
        echo json_encode($ret);
        exit;
    }
}else{
    //非法传参
    $ret = array('code' => 400,'msg'=>"非法传参");  
    echo json_encode($ret);
    exit;
}