<?php
/*
    单点登陆 获取单点用户登陆验证信息页面
 */
include_once ("../../login.inc.php");
$id=trim($_GET['id']);
$code=intval($_GET['code']);
 if(api_get_setting ( 'lm_switch' ) == 'true' && api_get_setting ( 'lm_nmg' ) == 'true'){
if($_user ['user_id']){
	include_once ("../../main/inc/global.inc.php");
	$status=Database::getval("select status from user where user_id=".$_user ['user_id']);
	if($code && $code!== ""){
		require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
		$user_id=$_user ['user_id'];
		$course=$code;
		$is_required_crs=1;
		CourseManager::subscribe_user ( $user_id, $course, STUDENT, getgpc ( "begin_date", "P" ), getgpc ( "finish_date", "P" ), $is_required_crs );
        
            $course_code = getgpc('code');
            $u_id = api_get_user_name ();
            $sql = "select title from course where code = ".$course_code;
            $title = Database::getval($sql);
             $data = array(
                              'type' => 2,
                              'data' => array(
                                  'user_id' => $u_id,
                                  'course' => $course_code,
                                  'course_name' => $title,
                                  'type' => '0',
                              )
                          );
            $uri = "http://10.217.209.81:8080/ISMC/Ismc_kl_mocha/rss/sendResults.do";
            $data_str = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $post_data = array(
                "json" => $data_str,
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $data_sours = curl_exec($ch);
            $data_result = json_decode($data_sours, true);
            $return_arr=json_decode($data_result,true);
            $return=$return_arr['Return'];
            if($return!="1(Success)"){
                     CourseManager::unsubscribe_user ( $user_id, $code );
                     echo "选修错误，请重新操作或者联系系统管理员";
                     exit;
                     }

	header("Location:"."course_home.php?cidReq=".$code."&action=introduction");  
	exit;
}
if($status==5){
	if($id=="KSZX"){  header("Location:"."exam_list.php");  exit;
	}
	elseif($id=="JJZX"){  
	    header("Location:"."index.php");  exit;
	}
}elseif($status==10){
	if($id=="KSGL"){  header("Location:"."../../main/admin/index.php");  exit;
	}
	elseif($id=="JJGL"){  
	    header("Location:"."../../main/admin/index.php");  exit;
	}
}
	header("Location:"."index.php");
	exit;
}else{
   echo  "获取用户信息失败，请检查单点以及人员同步，服务器DNS问题！";
}
}else{
    echo "请确认绿盟和绿盟内蒙古移动开关开启状态！";
}
