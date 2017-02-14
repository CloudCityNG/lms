<?php
/**
 ==============================================================================
 * @package Add By Jeson  vmdisk_log export
 ==============================================================================
 */

$cidReset = true;
$language_file = 'admin';
include ('../../inc/global.inc.php');
$this_section = SECTION_PLATFORM_ADMIN;

api_protect_admin_script ();

include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

$user_table = Database::get_main_table ( TABLE_MAIN_USER );
$tbl_dept=Database::get_main_table ( TABLE_MAIN_DEPT );
$objDept = new DeptManager ();

$dept_id = isset ( $_REQUEST ['keyword_deptid'] ) ? getgpc ( 'keyword_deptid' ) : '0';

$tool_name = get_lang ( '导出虚拟机日志列表' );
$htmlHeadXtra[]=Display::display_thickbox();
$form = new FormValidator ( 'export_vmdisk_log', "POST", null, null );

//$all_orgs=$objDept->get_all_org();
//$orgs['']='---'.api_get_setting('Institution').'(所有用户)---';
//foreach($all_orgs as $org_info){
//	$orgs[$org_info['id']]=$org_info['dept_name'];
//}
//$form->addElement ( 'select', 'keyword_deptid', '导出部门范围', $orgs, array ('id' => "dept_id", 'style' => 'height:22px;' ) );

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
      //           $start_time= isset ($export['start_date'] ) ? $export['start_date'] : getgpc ( 'start_date' );
                 $start_time = $export['start_date'];                 
                 $end_time  = $export['end_date'];
	$export_encoding = $export ['export_encoding'];
	set_time_limit ( 0 );
	$sql = "SELECT
                                        u.username       AS '用户名',
                                        u.user_ip            AS '用户IP',
                                        u.addres            AS '虚拟机IP',
                                        u.system            AS '虚拟机名称',
                                        u.lesson_id         AS '课程id',
                                        u.vmid                AS '虚拟机编号',
                                        u.mac_id             AS 'MAC地址',
                                        u.proxy_port       AS '端口', 
                                        u.start_time        AS '开启时间', 
                                        u.end_time         AS '关闭时间'";
	$sql .= " FROM vmdisk_log AS u LEFT JOIN user AS t ON u.user_id=t.user_id WHERE 1 ";
	$filename = 'Exportvmdisk_log_' . date ( 'YmdHi' ); //导出文件名

	if(is_not_blank($start_time)){
	       $sql .= " AND u.start_time > binary '".$start_time."'";	
	}
                 if(is_not_blank($end_time)){
	       $sql .= " AND u.start_time < binary  '".$end_time."'";	
	}
    
	$sql .= " ORDER BY u.username ";
  
	$data = array ();
                  $data [] = array ( '用户名','用户IP','虚拟机IP','虚拟机名称','课程id','虚拟机编号','MAC地址','端口','开启时间','关闭时间');
        
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	while ( $user = Database::fetch_array ( $res, 'ASSOC' ) ) {
		$data [] = $user;
	}
        
        mkdir('../../../storage/archive');

	Export::export_table_data( $data, $filename, 'xls' );
}

Display::display_header($tool_name,FALSE);
Display::display_normal_message('注意: 当导出的日志量比较大时会非常缓慢甚至会失败,出现异常! <br/>建议分批导出,最好一次性导出记录不要超过500条为宜.',false);
$form->display ();



Display::display_footer ();
