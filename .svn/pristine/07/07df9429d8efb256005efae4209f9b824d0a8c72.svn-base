<?php
header('Content-type: text/html;charset=UTF-8');
include_once ("../main/inc/global.inc.php");
$device_id = trim(getgpc('nodesId','G'));
$c_code  = trim(getgpc('cidReq','G'));
$USERID=$_SESSION['_user']['user_id'];

if($USERID){
    if($device_id && $c_code){
        $sql="select * from `labs_run_devices` where `course_name`=".$c_code." AND `DEVICEID`=".$device_id." AND `USERID`=".$USERID;
        $run_data= api_sql_query_array_assoc($sql,__FILE__,__LINE__);
        $DEVICEDNAME=trim($run_data[0]["DEVICEDNAME"]);
        $PORT       =trim($run_data[0]["PORT"]);
        $local_addres=trim($_SERVER['HTTP_HOST']);
        $local_addresx = explode(':',$local_addres);
        $local_addre  = $local_addresx[0];
        if($PORT && $DEVICEDNAME && $local_addre){
            $url_var="http://".$local_addres."/lib2/term.cgi?ip=".$local_addre."&port=".$PORT."&tit=".$DEVICEDNAME;
            header("Location: ".$url_var); 
        }else{
            $html = "访问被拒绝：可能数据不正常，请重试";
            Display::display_error_message ( $html, false );
            exit();
        }
    }else{
        $html = "访问被拒绝：您的操作错误，请重试";
        Display::display_error_message ( $html, false );
        exit();
    }
}else{
        $html = "访问被拒绝：您的用户已过期，请重新";
        $html .= '<a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].URL_APPEDND.'/portal/sp/login.php">登陆</a>!<br/>';
        Display::display_error_message ( $html, false );
        exit();
}
