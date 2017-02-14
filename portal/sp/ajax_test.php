<?php
$language_file = array ('courses', 'admin', 'create_course' );
require_once ('../../main/inc/global.inc.php');
api_block_anonymous_users ();

require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . "usermanager.lib.php");
require_once (api_get_path ( LIBRARY_PATH ) . "course.lib.php");
require_once (api_get_path ( LIBRARY_PATH ) . 'course_class_manager.lib.php');

if (isset ( $_GET['ajaxAction'])) {
	switch ($_GET['ajaxAction']) {
		
		case 'get_user_list_without_cur_crsuser' : //获取用户列表(除当前课程用户)
			$tbl_user = Database::get_main_table ( VIEW_USER_DEPT );
			$tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
			$tbl_course_user = Database::get_main_table ( TABLE_MAIN_COURSE_USER );
			$tbl_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
			$course_code = getgpc ( "code" );
			$keyword = getgpc ( "keyword" );
			$dept_id = getgpc ( "dept_id" );$dept_id=(int)$dept_id;
			$sql = "SELECT user_id,username,firstname,dept_name FROM " . $tbl_user . " AS t1 WHERE user_id NOT IN (SELECT cu.user_id FROM " . $tbl_course_user . " cu, " . $tbl_course . " c WHERE cu.course_code = '" . $course_code . "' AND cu.course_code = c.code)  ";
			if ($keyword) $sql .= " AND (username LIKE '%" . escape ( $keyword, TRUE ) . "%' OR firstname LIKE '%" . escape ( $keyword, TRUE ) . "%')";
			if ($dept_id and ! is_equal ( $dept_id, 'null' )) {
				$sql1 = "SELECT dept_sn FROM " . $tbl_dept . " WHERE id=" . Database::escape ( $dept_id );
				$dept_sn = Database::get_scalar_value ( $sql1 );
				if ($dept_sn) $sql .= " AND dept_sn LIKE '%" . $dept_sn . "%'";
			}
			$res = api_sql_query_array ( $sql, __FILE__, __LINE__ );
			$output = api_json_encode ( $res );
			echo $output;
			break;
		
		case "options_get_all_sub_depts" : //某机构下部门下拉框
			$deptObj = new DeptManager ();
			$org_id = getgpc ( "org_id" );$org_id=(int)$org_id;
			if (empty ( $org_id )) exit ();
			$all_sub_depts = $deptObj->get_sub_dept_ddl ( $org_id );
			$html = "";
			foreach ( $all_sub_depts as $item ) {
				$html .= '<option value="' . $item ['id'] . '">' . str_repeat ( "&nbsp;&nbsp;", intval ( $item ['level'] / 2 ) ) . $item ['dept_name'] . '</option>';
			}
			echo $html;
			break;
		
		case 'subscribe' :
            $course_code = getgpc('code');
            $token_key_true = $_SESSION['_user']['code'][$course_code];
            if($_GET[$token_key_true] !== 'token'){
                echo 'err';exit;
            }

            $referer = getgpc('referer');
            if(($referer !== "course_catalog.php") && ($referer !== 'ranking.php')) {
                echo 'err';exit;
            }
            $cate=(int)$_GET['category_id'];
            $cate_count=mysql_fetch_row(mysql_query('select count(*) from view_course_user where user_id='.$_SESSION['_user']['user_id'].' and category_code="'.$cate.'" limit 1'));
            
            if(!$cate_count[0]){
                $category_sun=true;
                $id=(int)$_GET['id'];
                $category_id=(int)$_GET['cate'];
                include './right_list.php';
                unset($category_id);
                include './right_list.php';
            }
            unset($right_list);
            $message = CourseManager::subscribe_applied_user_to_course ( getgpc('code'), api_get_user_id (), getgpc ( 'course_class_id', 'P' ) ); //liyu
            echo $message;
			$sqlsettings = "SELECT DISTINCT selected_value FROM settings_current WHERE  title='lm_switch_title' GROUP BY variable ORDER BY display_order ASC";
            $resl = api_sql_query_array_assoc( $sqlsettings, __FILE__, __LINE__ ); 
            if($message == '您已成功注册选修了该课程!' && api_get_setting ( 'lm_switch' ) == 'true' && api_get_setting ( 'lm_nmg' ) == 'true'){
            $course_code = getgpc('code');
            $u_id = api_get_user_name ();
            $sql = "select title from course where code = ".$course_code;
            $title = api_sql_query_array_assoc($sql);
             $data = array(
                              'type' => 2,
                              'data' => array(
                                  'user_id' => $u_id,
                                  'course' => $course_code,
                                  'course_name' => $title[0]['title'],
                                  'type' => '0',
                              )
                          );
            //$uri = "http://192.168.253.4:8080/ISMC/Ismc_kl_mocha/rss/sendResults.do";
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
                     CourseManager::unsubscribe_user ( api_get_user_id (), getgpc ( 'course_code' ) );
                     echo "数据同步失败，请重新提交或联系系统管理员";
                     exit;
                     }
            }

			break;
		case 'unSubscribe' :
			$message = CourseManager::unsubscribe_user ( api_get_user_id (), getgpc ( 'course_code' ) );
			echo $message ? 1 : 0;
			exit ();
			break;
	}
}

/**
 * since ZLMS V1.1 增加参数$class_id
 * Subscribe the user to a given course
 * @author Patrick Cool <patrick.cool@UGent.be>, Ghent University
 * @param string $course_code the code of the course the user wants to subscribe to
 * @return string we return the message that is displayed when the action is succesfull
 */
function subscribe_user($course_code, $class_id = '0') {
	global $_user;
	
	$all_course_information = CourseManager::get_course_information ( $course_code );
	
	//注册课程不需要注册码时
	if ($all_course_information ['registration_code'] == '' or $_POST ['course_registration_code'] == $all_course_information ['registration_code']) {
		if (CourseManager::add_user_to_course ( api_get_user_id (), $course_code, $_user ['status'], $class_id )) {
			return ('EnrollToCourseSuccess');
		} else {
			return ('ErrorContactPlatformAdmin');
		}
	}
}


?>
