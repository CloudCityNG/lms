<?php
/**
 ==============================================================================
 * 课程信息修改
 ==============================================================================
 */
$language_file = array ('course_info', 'admin', 'create_course' );
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . "usermanager.lib.php");
require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
echo "<style type='text/css'>
.yui-skin-sam .yui-navset .yui-nav #edit a,.yui-skin-sam .yui-navset .yui-nav #edit a:focus,.yui-skin-sam .yui-navset .yui-nav #edit a:hover
    </style>";

$firstExpirationDelay = 31536000; // 课程默认过期时间 <- 86400*365    // 60*60*24 = 1 jour = 86400

function credit_range_check($inputValue) {
	return (intval ( $inputValue ) > 0 );
}
function credit_hours_range_check($inputValue) {
	return (intval ( $inputValue ) > 0);
}
function fee_check($inputValue) {
	if (isset ( $inputValue ) && is_array ( $inputValue )) {
		if ($inputValue ['is_free'] == '0') {
			return floatval ( $inputValue ['payment'] ) > 0;
		} else {
			return true;
		}
	}
	return false;
}
$lessonid=getgpc('cidReq');
$course_table = Database::get_main_table ( TABLE_MAIN_COURSE );
$table_course_user = $course_user_table = Database::get_main_table ( TABLE_MAIN_COURSE_USER );
$table_user = Database::get_main_table ( TABLE_MAIN_USER );
$table_course_category = Database::get_main_table ( TABLE_MAIN_CATEGORY );
$table_course_setting = Database::get_course_table ( TABLE_COURSE_SETTING );

$course_code = isset ( $_GET ['course_code'] ) ? getgpc('course_code') : getgpc('code');
$htmlHeadXtra [] = Display::display_thickbox ( TRUE );
$htmlHeadXtra []=  import_assets ( "jquery.js", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';


$description_id = isset ( $_REQUEST ['description_id'] ) ? intval ( getgpc ( 'description_id' ) ) :10;

$html = '<div id="demo" class="yui-navset">';
$html .= '<ul class="yui-nav">';
$html .= '<li  id="edit" ' . ($description_id == 10 ? 'class="selected"' : '') . '><a href="../course/course_edit.php?cidReq='.$lessonid.'&description_id=' . 10 . '"><em>' . get_lang ( '课程设置' ) . '</em></a></li>';

$html .= '<li  ' . ($description_id == 0 ? 'class="selected"' : '') . '><a href="../../course_description/index.php?cidReq='.$lessonid.'&description_id=' . 0 . '"><em>' . get_lang ( '课程信息' ) . '</em></a></li>';
$html .= '<li  ' . ($description_id == 8 ? 'class="selected"' : '') . '><a href="../../course_description/index.php?cidReq='.$lessonid.'&description_id=' . 8 . '"><em>' . get_lang ( '实验步骤' ) . '</em></a></li>';
$html .= '<li  ' . ($description_id == 7 ? 'class="selected"' : '') . '><a href="../../course_description/index.php?cidReq='.$lessonid.'&description_id=' . 7 . '"><em>' . '教学大纲' . '</em></a></li>';
$html .= '<li  ' . ($description_id == 14 ? 'class="selected"' : '') . '><a href="../../course_description/lessontop.php?cidReq='.$lessonid.'"><em>' . get_lang ( '网络拓扑') . '</em></a></li>';
$html .= '</ul>';
$html .= '<div class="yui-content"><div id="tab1">';
echo $html;

//machao 
$sql = "SELECT user.user_id,user.firstname FROM $table_user as user,$table_course_user as course_user WHERE course_user.status='1' AND course_user.is_course_admin='1'  AND course_user.user_id=user.user_id AND course_user.course_code='" . $lessonid . "' LIMIT 1";
$res0 = api_sql_query ( $sql, __FILE__, __LINE__ );
list ( $course_admin_id, $course_admin_name ) = Database::fetch_row ( $res0 );
$course ['course_teachers'] = $course_admin_id;

//获取课程信息

$course = CourseManager::get_course_information ( $lessonid );
$course['course_admin']=$course_admin_id;  

if ($course == false) {echo "<script type='text/javascript'>self.parent.tb_remove();</script>";exit;}

$tool_name = get_lang ( 'ModifyCourseInfo' );

//修改课程信息表单
$form = new FormValidator ( 'update_course' );
$form->addElement ( 'hidden', 'code', $course_code );

//编号
$form->add_textfield ( 'visual_code', get_lang ( 'CourseCode' ), true, array ('style' => "width:40%", 'class' => 'inputText', "readonly" => "true" ) );
$form->applyFilter ( 'visual_code', 'strtoupper' );

//标题
$form->add_textfield ( 'title', get_lang ( 'CourseTitle' ), true, array ('style' => "width:40%", 'class' => 'inputText' ) );
//自定义编号
$form->addElement ( 'text', 'nodeId', get_lang ( '自定义编号' ),array ('style' => "width:200px", 'class' => 'inputText' ) );
$form->addRule ( 'nodeId', get_lang ( 'Max' ), 'maxlength', 50 );

//课程封面
$form->addElement ( 'file', 'course_pic', get_lang ( '课程封面' ),array ('style' => "width:30%", 'class' => 'inputText' ) );
                        
// 课程管理员   machao$course_admin_id, $course_admin_name
$sql_admin="select `user_id`,`firstname` from `user` where `status`='10' or `status`='1'";
$result=Database::get_into_array2 ( $sql_admin, __FILE__, __LINE__ );

$form->addElement ( 'select', 'course_admin', get_lang ( "课程管理员" ), $result, array ('id' => "user", 'style' => 'height:22px;' ) );
//课程独占实验环境
$group = array ();
$group [] = $form->createElement ( 'radio', 'description10', null, get_lang ( 'Yes' ), COURSE_VISIBILITY_REGISTERED );
$group [] = $form->createElement ( 'radio', 'description10', null, get_lang ( 'No' ), COURSE_VISIBILITY_CLOSED );
$form->addGroup ( $group, 'description10', get_lang ( '课程环境隔离' ), '&nbsp;&nbsp;&nbsp;&nbsp;', false );


//学分
$form->addElement ( 'text', 'credit', get_lang ( "CourseCredit" ), array ('id' => 'credit', 'style' => "width:80px;text-align:right", 'class' => 'inputText', 'title' => get_lang ( 'CreditTip' ) ) );
$form->addRule ( 'credit', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'credit', get_lang ( 'ThisFieldIsRequiredNumeric' ), 'numeric' );
$form->addRule ( 'credit', get_lang ( '' ), 'rangelength', array (1, 2 ) );
$form->addRule ( 'credit', get_lang ( 'CreditTip' ), 'callback', 'credit_range_check' );

//学时
$form->addElement ( 'text', 'credit_hours', get_lang ( "CourseCreditHours" ), array ('maxlength' => '4', 'style' => "width:80px;text-align:right", 'class' => 'inputText', 'title' => get_lang ( 'CreditHoursTip' ) ) );
$form->addRule ( 'credit_hours', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'credit_hours', get_lang ( 'ThisFieldIsRequiredNumeric' ), 'numeric' );
$form->addRule ( 'credit_hours', get_lang ( '' ), 'rangelength', array (1, 4 ) );
$form->addRule ( 'credit_hours', get_lang ( 'CreditHoursTip' ), 'callback', 'credit_hours_range_check' );

//V1.4.0
$objCrsMng = new CourseManager ();
$category_tree = $objCrsMng->get_all_categories_tree ( TRUE, $course ['org_id'] );
foreach ( $category_tree as $item ) {
	$parent_cate_option [intval($item ['id'])] = str_repeat ( "&nbsp;&nbsp;&nbsp;&nbsp;", intval ( $item ['level'] ) + 1 ) . $item ['name'];
}
$form->addElement ( 'select', 'category_code', get_lang ( "CourseFaculty" ), $parent_cate_option, array ('id' => "category_code", 'style' => 'height:22px;' ) );

//学员选修学习默认天数
$form->addElement ( 'text', 'default_learing_days', get_lang ( "DefaultLearningDays" ), array ('id' => 'default_learing_days', 'maxlength' => '4', 'style' => "width:80px;text-align:right", 'class' => 'inputText' ) );
$form->addRule ( 'default_learing_days', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'default_learing_days', get_lang ( 'ThisFieldIsRequiredNumeric' ), 'numeric' );
$form->addRule ( 'default_learing_days', get_lang ( '' ), 'rangelength', array (1, 4 ) );


$form->addElement ( 'hidden', 'subscribe' );
$values ['subscribe'] = 1;

//允许注销?
$form->addElement ( "hidden", "unsubscribe", "0" );

//允许注册,注销课程
$form->addElement ( 'checkbox', 'is_subscribe_enabled', get_lang ( "SubscribePriv" ), get_lang ( 'AllowedCourseAdminSubscribeUser' ), array ("id" => "is_subscribe_enabled" ) );

//允许审批,有审批选课申请的
$form->addElement ( 'hidden', 'is_audit_enabled' );
$values ['is_audit_enabled'] = 0;

//TODO: 访问权限
$group = array ();
$group [] = $form->createElement ( 'radio', 'visibility', null, get_lang ( 'Private' ), COURSE_VISIBILITY_REGISTERED );
$group [] = $form->createElement ( 'radio', 'visibility', null, get_lang ( 'CourseVisibilityClosed' ), COURSE_VISIBILITY_CLOSED );
$form->addGroup ( $group, 'visibility', get_lang ( "CourseAccess" ), '&nbsp;&nbsp;&nbsp;&nbsp;', false );

//语言
$form->addElement ( 'hidden', 'course_language', 'simpl_chinese' );
$form->addElement ( 'hidden', 'is_shown', '0' );
$form->addElement ( 'hidden', 'pass_condition', '2' );
$form->addElement ( 'hidden', 'org_id', '-1' );
$form->addElement ( 'hidden', 'old_is_audit_enabled', $course ['is_audit_enabled'] );

//V2.1 讲师
$form->add_textfield ( 'tutor_name', get_lang ( 'CourseTitular' ), FALSE, array ('style' => "width:50%", 'class' => 'inputText' ) );
$form->addRule ( 'tutor_name', get_lang ( 'Max' ), 'maxlength', 100 );

$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Save' ), 'onclick="valide()" class="save"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, null, '&nbsp;' );

$course['tutor_name'] = htmlspecialchars_decode( $course['tutor_name'] );
// Set some default values
$form->setDefaults($course);

//
$form->addRule ( 'visual_code', get_lang ( 'CourseCodeMustBeAlphanumeric' ), 'alphanumeric' );
$form->addRule ( 'visual_code', get_lang ( 'CourseCodeNopunctuation' ), 'nopunctuation' );

Display::setTemplateBorder ( $form, '100%' );

// Validate form
if ($form->validate ()) {
	$course = $form->getSubmitValues ();
	$course_code = $course ['code'];
	$course_info = CourseManager::get_course_information ( $course_code );
	if (! can_do_my_bo ( $course_info ['created_user'] )) {
                echo "<script type='text/javascript'>self.parent.tb_remove();</script>";exit;        
	}
	
	$visual_code = $course ['visual_code'];
	 $course_pic=$_FILES['course_pic'];
	//讲师
	$tutor_id = $course ['tutorId'] ['TO_ID'];
	$tutor_name = $course ['tutorId'] ['TO_NAME'];
	$tutor_name = htmlspecialchars( $course ['tutor_name'] );
	$description10 = $course ['description10'];

	//课程管理员
	$teachers = $course ['course_admin'];
        
	$course_managers = explode ( ',', $teachers );
	
	$nodeId=$course['nodeId'];  //自定义编号
	$title = $course ['title'];
	$category_code = $course ['category_code'];

	$course_language = $course ['course_language'];
	$visibility = $course ['visibility'];
	$subscribe = $course ['subscribe'];
	$unsubscribe = $course ['unsubscribe'];
	
	$credit = $course ['credit']; //学分
	$credit_hours = $course ['credit_hours']; //学时
	$default_learing_days = $course ["default_learing_days"]; //选修学习默认天数
	$is_free = $course ['property'] ['is_free']; //是否免费课程
	$fee = $course ['property'] ['payment']; //价格
	if ($is_free) $fee = '0.00';
	if (floatval ( $fee ) == 0) $is_free = 1;
	$is_audit_enabled = $course ['is_audit_enabled'];
	$is_subscribe_enabled = $course ['is_subscribe_enabled'];
	$is_shown = $course ['is_shown'];
	$pass_condition = $course ['pass_condition'];
	$org_id = (empty ( $course ['is_shared'] ) ? $course ['org_id'] : - 1);
	
	$sql_data = array ('course_language' => $course_language, 
			'title' => $title,
			'nodeId'=>$nodeId,
			'category_code' => $category_code, 
			'tutor_name' => $tutor_name, 
			'visual_code' => $visual_code, 
			'visibility' => $visibility, 
			'subscribe' => $subscribe, 
			'unsubscribe' => $unsubscribe, 
			'credit' => $credit, 
			'credit_hours' => $credit_hours, 
			'is_free' => $is_free, 
			'fee' => $fee, 
			'is_audit_enabled' => $is_audit_enabled, 
			'is_subscribe_enabled' => $is_subscribe_enabled, 
			'is_shown' => $is_shown, 
			'pass_condition' => $pass_condition, 
			'org_id' => $org_id, 
			'default_learing_days' => $default_learing_days,
			'description10' => $description10,
                         'description9'=> $visual_code.'.'.end(explode(".", $course_pic['name']))
	    );
	$sql = Database::sql_update ( $course_table, $sql_data, "code=" . Database::escape ( $course_code ) );
	$re=api_sql_query ( $sql, __FILE__, __LINE__ );
        if($re){
             $parent_arr=mysql_fetch_row(mysql_query('select parent_id from course_category where id='.$category_code.' limit 1'));
             $parent_id=$parent_arr[0];
             if($parent_id){
             if(file_exists('/tmp/'.$parent_id)){
                 unlink('/tmp/'.$parent_id);
             }
             $set_query=mysql_query('select id,subclass from setup');
             while($set_row=mysql_fetch_assoc($set_query)){
                 $set_rows[]=$set_row;
             }
             foreach($set_rows as $set_k => $set_v){
                 $set_str=explode(',',$set_v['subclass']);
                 if(in_array($parent_id,$set_str)){
                     $p_id=$set_v['id'];
                     break;
                 }
             }
             if(file_exists('/tmp/'.$p_id.'r')){
                  unlink('/tmp/'.$p_id.'r');
             }
             }
            $file_tmp=$course_pic['tmp_name'];  
            $file_path=api_get_path ( SYS_PATH ).'storage/courses/'.$visual_code.'/';
            exec("mkdir ".$file_path);
            $file_name=  $visual_code.'.'.end(explode(".", $course_pic['name']));
            move_uploaded_file($file_tmp,$file_path.$file_name);
        }
	//V2.4 处理课程管理员
	//将课程管理员全部设置为普通用户
	$sql = "UPDATE $course_user_table SET is_course_admin=0,tutor_id=0  WHERE course_code = " . Database::escape ( $course_code ) . " AND is_course_admin =1 ";
	api_sql_query ( $sql, __FILE__, __LINE__ );
	foreach ( $course_managers as $admin_id ) {
		if ($admin_id) {
			if (CourseManager::is_user_subscribe ( $course_code, $admin_id )) {
				$sql_data = array ('status' => COURSEMANAGER, 'is_course_admin' => '1','tutor_id' => '1',  );
				$sqlwhere = "course_code = " . Database::escape ( $course_code ) . " AND user_id = " . Database::escape ( $admin_id );
				$sql = Database::sql_update ( $course_user_table, $sql_data, $sqlwhere );
                                echo '<hr>'.$sql;
				api_sql_query ( $sql, __FILE__, __LINE__ );
			} else {
				$sql_data = array ('course_code' => $course_code, 
						'user_id' => $admin_id, 
						'status' => COURSEMANAGER, 
						'role' => get_lang ( "CourseAdmin" ), 
						'is_course_admin' => '1', 
						'tutor_id' => '1', 
						'is_required_course' => 1, 
						'begin_date' => date ( "Y-m-d" ), 
						'finish_date' => date ( "Y-m-d", strtotime ( "+ $firstExpirationDelay seconds" ) ) );
				$sql = Database::sql_insert ( $course_user_table, $sql_data );
                                
				api_sql_query ( $sql, __FILE__, __LINE__ );
			}
		}
	}
	
	//处理课程选课审批
	if ($course ['old_is_audit_enabled'] != $course ['is_audit_enabled']) {
		$table_course_subscribe_requisition = Database::get_main_table ( TABLE_MAIN_COURSE_SUBSCRIBE_REQUISITION );
		$sql = "SELECT user_id FROM " . $table_course_subscribe_requisition . " WHERE course_code='" . escape ( $course_code ) . "' AND audit_result<>1";

		$all_req_users = Database::get_into_array ( $sql );
		switch ($is_audit_enabled) {
			case 1 : //课程管理员审批
				$cousre_admin_info = CourseManager::get_course_admin ( $course_code );
				$course_admin_id = $cousre_admin_info ['user_id'];
				$sql = "UPDATE " . $table_course_subscribe_requisition . " SET audit_user='" . $course_admin_id . "' WHERE course_code='" . $course_code . "' AND " . Database::create_in ( $all_req_users, "user_id" );
				api_sql_query ( $sql, __FILE__, __LINE__ );
				break;
			case 2 : //部门经理审批
				foreach ( $all_req_users as $req_user ) {
					$dept_admin = UserManager::get_user_dept_admin ( $req_user );
					$sql = "UPDATE " . $table_course_subscribe_requisition . " SET audit_user='" . $dept_admin . "' WHERE course_code='" . $course_code . "' AND  user_id='" . $req_user . "'";
					api_sql_query ( $sql, __FILE__, __LINE__ );
				}
				break;
			case 3 : //培训管理员审批
				$view_user_dept = Database::get_main_table ( VIEW_USER_DEPT );
				foreach ( $all_req_users as $req_user ) {
					$sql = "SELECT org_admin FROM " . $view_user_dept . " WHERE user_id='" . escape ( $req_user ) . "'";
					$org_admin = Database::get_scalar_value ( $sql );
					$sql = "UPDATE " . $table_course_subscribe_requisition . " SET audit_user='" . $org_admin . "' WHERE course_code='" . $course_code . "' AND user_id='" . $req_user . "'";
					api_sql_query ( $sql, __FILE__, __LINE__ );
				}
				break;
			case 0 :
				
				break;
		}
	}
	
	$log_msg = get_lang ( 'EditCourseInfo' ) . "code=" . $course_code;
	api_logging ( $log_msg, 'COURSE', 'EditCourseInfo' );     
        echo "<script type='text/javascript'>self.parent.tb_remove();</script>";exit;
}

Display::display_header ( $tool_name, FALSE );

$form->display ();

Display::display_footer ();
