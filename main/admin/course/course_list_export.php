<?php
$cidReset = true;
$language_file = 'admin';
include ('../../inc/global.inc.php');
$this_section = SECTION_PLATFORM_ADMIN;

api_protect_admin_script ();

include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

 
$tool_name = get_lang ( 'ExportUserListXMLCSV' );
$htmlHeadXtra[]=Display::display_thickbox();
$form = new FormValidator ( 'export_course_list', "POST", null, null );
 
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
	$export = $form->exportValues ();
	$export_encoding = $export ['export_encoding'];
	set_time_limit ( 0 );
	$sql = "SELECT
                `code`           AS '课程编号',
                `title` 	 AS '课程名称',
                `category_code`  AS '所属分类',
                `start_date`     AS '发布时间'
                FROM  `course` ";
	$filename = 'ExportCourseList_' . date ( 'YmdHi' ); //导出文件名

	$data = array ();
        $data [] = array ( '课程编号','课程名称', '所属分类', '发布时间');
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	while ( $c = Database::fetch_array ( $res, 'ASSOC' ) ) {
		$data [] = $c;
	}

	Export::export_table_data( $data, $filename, 'xls' );
}

Display::display_header($tool_name,FALSE);
Display::display_normal_message('注意: 当导出的课程比较多时会比较缓慢甚至会失败，请耐心等待！！',false);
$form->display ();



Display::display_footer ();
