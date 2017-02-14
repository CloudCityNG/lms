<?php
/**
 ==============================================================================
 * @package Add By Jeson logging_list export
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

$tool_name = get_lang ( '导出操作系统日志' );
$htmlHeadXtra[]=Display::display_thickbox();
$form = new FormValidator ( 'logging_list_export', "POST", null, null );

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
                 	$export_encoding = $export ['export_encoding'];
	set_time_limit ( 0 );

                    $table_sys_logging = Database::get_main_table ( TABLE_MAIN_SYS_LOGGING );
                $sql = "SELECT
                         log_date		AS '操作时间',
                         username		AS '登陆名',
                         display_name          	AS '姓名',
                         message		AS '日志信息',
                         access_uri		AS '请求URL'
                         FROM  $table_sys_logging  where 1 ";
                
                	if(is_not_blank($start_time)){
	       $sql .= " AND log_date > binary '".$start_time."'";	
	}
                 if(is_not_blank($end_time)){
	       $sql .= " AND log_date < binary  '".$end_time."'";	
	}
        
                 $sql .= " ORDER BY  log_date desc ";
                 
	$data = array ();
                  $data [] = array ( '操作时间','登陆名','姓名','日志信息','请求URL');        
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	while ( $user = Database::fetch_array ( $res, 'ASSOC' ) ) {
		$data [] = $user;
	}

                    $filename = 'Exportlogging_list_' . date ( 'YmdHi' ); //导出文件名
                    mkdir('../../../storage/archive');

	Export::export_table_data( $data, $filename, 'xls' );
}

Display::display_header($tool_name,FALSE);
Display::display_normal_message('注意: 当导出的日志量比较大时会非常缓慢甚至会失败,出现异常! <br/>建议分批导出,最好一次性导出记录不要超过500条为宜.',false);
$form->display ();

Display::display_footer ();
