<?php
header("Content-Type:text/html;charset=UTF-8");
$cidReset = true;
include_once ("../../main/inc/global.inc.php");
include_once ("../../main/inc/lib/webservice.lib.php");
$list = array();
$type =  intval($_GET['type']);    //课程类型
$parent_id =  intval($_GET['parent_id']);    //父级id
$page_size = intval($_GET['page_size']);    //每页显示数量
$page = intval($_GET['page']);    //页数
if(api_get_setting ( 'lm_switch' ) == 'true' && api_get_setting( 'lm_nmg' ) == 'true') {
    class course
    {
        function courseAction($type,$prent_id,$page_size,$page)
        {
            $date = webservice::course_interface($type, $prent_id, $page_size, $page);
            if ($date)
            {
                echo $date;
            }
        }
    }
    $course = new course();
    $course->courseAction($type,$parent_id,$page_size,$page);
}