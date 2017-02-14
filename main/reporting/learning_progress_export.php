<?php
/**
 ==============================================================================
 * @package Add By Jeson  Learning_progress Export
 ==============================================================================
 */

$cidReset = true;
$language_file = 'admin';
include ('../inc/global.inc.php');
$this_section = SECTION_PLATFORM_ADMIN;

api_protect_admin_script ();

include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'tracking.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( SYS_CODE_PATH ) . 'reporting/cls.track_stat.php');

$tbl_dept=Database::get_main_table ( TABLE_MAIN_DEPT );
$table_course_user = Database::get_main_table ( VIEW_COURSE_USER );
$table_user = Database::get_main_table ( VIEW_USER_DEPT );
$objStat = new ScormTrackStat ();
$objDept = new DeptManager ();

$dept_id = isset ( $_REQUEST ['keyword_deptid'] ) ? getgpc ( 'keyword_deptid' ) : '0';

$tool_name = get_lang ( '导出学习情况列表' );
$htmlHeadXtra[]=Display::display_thickbox();
$form = new FormValidator ( 'export_vmdisk_log', "POST", null, null );

$all_orgs=$objDept->get_all_org();
$orgs['']='---'.api_get_setting('Institution').'(所有用户)---';
foreach($all_orgs as $org_info){
	$orgs[$org_info['id']]=$org_info['dept_name'];
}
$form->addElement ( 'select', 'keyword_deptid', '导出部门范围', $orgs, array ('id' => "dept_id", 'style' => 'height:22px;' ) );

//时间范围
$form->addElement ( 'calendar_datetime', 'start_date', '开始日期时间：', array (), array ('show_time' => TRUE ) );
$form->addElement ( 'calendar_datetime', 'end_date', '结束日期时间：', array (), array ('show_time' => TRUE ) );

$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Export' ), array ("id" => "sub", 'class' => "inputSubmit" ) );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$defaults ['export_encoding'] = get_default_encoding ();
$defaults ['file_type'] = 'xls';
$form->setDefaults ( $defaults );

Display::setTemplateBorder ( $form, '98%' );
$form->add_progress_bar ();
if ($form->validate ()) {

                 $export = $form->getSubmitValues ();
                 $start_time = $export['start_date'];                 
                 $end_time  = $export['end_date'];
                 $keyword_deptid=$export['keyword_deptid'];
	$export_encoding = $export ['export_encoding'];
	set_time_limit ( 0 );
	$sql = "SELECT t1.title      AS '课程名称',
                                t1.username      AS '登录名',
                                 t1.firstname      AS '姓名',
                             t2.official_code      AS '用户编号',
                                     t2.dept_id       AS '用户部门',
                                 t1.got_credit       AS '获得学分',
	                    t1.user_id       AS '进度(%)',
                             t1.course_code       AS '学习总时间',
                  DATE_FORMAT(FROM_UNIXTIME(t3.last_access_time),'%Y-%m-%d %H:%i')     as '最后学习时间'
	FROM $table_course_user AS t1 INNER JOIN $table_user AS t2 INNER JOIN track_e_cw AS t3 ON t1.user_id=t2.user_id WHERE t3.cc=t1.course_code AND t3.user_id=t1.user_id ";
		
                    if($keyword_deptid){
                            $sql.=" and ".Database::create_in($keyword_deptid,'t2.dept_id');
                    }
	if(is_not_blank($start_time)){
	       $sql .= " AND DATE_FORMAT(FROM_UNIXTIME(t3.last_access_time),'%Y-%m-%d %H:%i') > binary '".$start_time."'";	
	}
                 if(is_not_blank($end_time)){
	       $sql .= " AND DATE_FORMAT(FROM_UNIXTIME(t3.last_access_time),'%Y-%m-%d %H:%i') < binary  '".$end_time."'";	
	}
    
	$sql .= " ORDER BY t3.last_access_time desc ";
	$filename = 'Exportvmdisk_log_' . date ( 'YmdHi' ); //导出文件名
  
	$data = array ();
                  $data [] = array ( '课程名称','登录名','姓名','用户编号','用户部门','获得学分','进度(%)','学习总时间','最后学习时间');
        
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	while ( $user = Database::fetch_array ( $res, 'ASSOC' ) ) {
                                  $student_id = $user ['进度(%)'];
	                 $course_code = trim ( $user ['学习总时间'] );
                         
                                  $avg_time_spent = $avg_student_score = $avg_student_progress = 0;  //初始化

                                   //本课程学习时间
		$avg_time_spent = ($objStat->get_total_learning_time ( $student_id, $course_code ));

		//学习进度
		$avg_student_progress = $objStat->get_course_progress ( $course_code, $student_id );

                		//考试得分
		if (api_get_setting ( 'enable_modules', 'exam_center' ) == 'true') {
			$avg_student_score = $objStat->get_course_exam_score ( $student_id, $course_code );
		}
                		$user ['进度(%)'] = $avg_student_progress;
		if (api_get_setting ( 'enable_modules', 'exam_center' ) == 'true') {
			$user ['学习总时间'] = empty ( $avg_time_spent ) ? "" : api_time_to_hms ( $avg_time_spent );
		} else {
			$user ['学习总时间'] = empty ( $avg_time_spent ) ? "" : api_time_to_hms ( $avg_time_spent );
		}           
                
                                  $user['用户部门']=Database::getval("select dept_name from sys_dept where id=".$user['用户部门']);
		$data [] = $user;
	}
        
                  mkdir('../../storage/archive');
	Export::export_table_data( $data, $filename, 'xls' );
}

Display::display_header($tool_name,FALSE);
Display::display_normal_message('注意: 当导出的日志量比较大时会非常缓慢甚至会失败,出现异常! <br/>建议分批导出,最好一次性导出记录不要超过500条为宜.',false);
$form->display ();



Display::display_footer ();
