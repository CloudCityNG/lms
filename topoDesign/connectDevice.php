<?php
header('Content-type: text/html;charset=UTF-8');
include_once ("../main/inc/global.inc.php");
$device_id = trim(getgpc('nodesId','G'));
$topo_id   = trim(getgpc('topoId','G'));
$USERID=$_SESSION['_user']['user_id'];
if($USERID){

    if($device_id && $topo_id)
    {
        $sql           = "select * from `labs_run_devices` where `labs_name`=".$topo_id." AND `DEVICEID`=".$device_id." AND `USERID`=".$USERID;
        $run_data      = api_sql_query_array_assoc($sql,__FILE__,__LINE__);
        $DEVICEDNAME   = trim($run_data[0]["DEVICEDNAME"]);
        $PORT          = trim($run_data[0]["PORT"]);
        $local_addres  = trim($_SERVER['HTTP_HOST']);

        $local_addresx = explode(':',$local_addres);
        $local_addre   = $local_addresx[0];

        if($PORT && $DEVICEDNAME && $local_addre)
        {
            $url_var="http://".$local_addres."/lib2/term.cgi?ip=".$_SERVER['SERVER_ADDR']."&port=".$PORT."&tit=".$DEVICEDNAME;
        }else{
            $url_var='http://'.$_SERVER['SERVER_NAME'].URL_APPEDND.'/topoDesign/dynamic_map.php?action=show&id='.$topo_id;
        }
        header("Location: ".$url_var);
        exit();

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
