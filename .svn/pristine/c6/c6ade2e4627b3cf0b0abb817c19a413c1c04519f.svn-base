<?php
$language_file = array ('announcements', 'admin' );
include_once ('../inc/global.inc.php');
api_block_anonymous_users ();
api_protect_course_script ();
$isAllowToEdit = api_is_allowed_to_edit ();
if (! $isAllowToEdit) api_not_allowed ();
include_once ('announcements.inc.php');

//$tbl_announcement = Database::get_course_table ( TABLE_ANNOUNCEMENT );
//$tbl_item_property = Database::get_course_table ( TABLE_ITEM_PROPERTY );
//$tbl_attachment = Database::get_course_table ( TABLE_TOOL_ATTACHMENT );

//$cur_course_url = api_get_path ( WEB_COURSE_PATH ) . api_get_course_code () . "/";

$objAnnouncement = new CourseAnnouncementManager ();
$sql_num = $objAnnouncement->get_announcemet_list_sql ( api_get_user_id () );
$res = api_sql_query ( $sql_num, __FILE__, __LINE__ );
//$announcement_number = Database::num_rows ( $res );
$redirect_url = 'main/announcements/index.php';
if (isset ( $_GET ['action'] )) {
	if (api_is_allowed_to_edit ()) {
		if (getgpc('action','G') == "delete" and isset ( $_GET ['id'] )) { //删除某条课程公告
			$id = intval (getgpc('id','G') );
                        //machao  --删除课程公告时，删除crs_attachment表的数据和上传附件。
                        $course=Database::getval("SELECT  `cc` FROM `crs_announcement` WHERE `id`=".$id, __FILE__, __LINE__);
                        $file=  Database::getval("SELECT  `new_name`  FROM `crs_attachment` WHERE  `cc`=".$course, $file, $line);
                        $path="../../storage/courses/".$course."/attachments/".$file;
                      
                        if(file_exists($path)){
                            unlink($path);
                        }
                        api_sql_query("DELETE FROM `crs_attachment` WHERE `cc`=".$course);
                        CourseAnnouncementManager::del_announcement ( $id );
		
		}
	} 
	api_redirect ( URL_APPEND . $redirect_url );
}

$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( NULL, FALSE );

//$tbl_announcement = Database::get_course_table ( TABLE_ANNOUNCEMENT );
$sql = CourseAnnouncementManager::get_announcemet_list_sql ( api_get_user_id (), api_get_course_code (), '0000-00-00 00:00:00' );
$sql .= " ORDER BY t1.end_date DESC";

$sorting_options = array ();
$sorting_options ['column'] = 1;
$sorting_options ['default_order_direction'] = 'DESC';

$table_header [] = array (get_lang ( 'Title' ) );
//$table_header [] = array (get_lang ( 'Publisher' ), null, array ('width' => '100' ) );
$table_header [] = array (get_lang ( 'AnnouncementPublishedOn' ), null, null, array ('width' => '20%' ) );
$table_header [] = array (get_lang ( 'Actions' ), null, null, array ('width' => '10%' ) );
$result = api_sql_query ( $sql, __FILE__, __LINE__ );
$table_data = array ();
$index = 1;
while ( $data = Database::fetch_array ( $result, 'ASSOC' ) ) {
	$row = array ();
	
	//$row [] = $data ['id'];
	//	$row[]=link_button('',$data ['title'],"show_all.php?todo=view&id=" . $data ['anno_id'] . "&course_code=" . $_course ['sysCode'],420,900);
	

	$row [] = "<a class=\"thickbox\" href='show_all.php?todo=view&id=" . $data ['anno_id'] . "&course_code=" . api_get_course_code () . "&KeepThis=true&TB_iframe=true&height=90%&width=90%&modal=' target='_self'>" . $data ['title'] . '</a>';
	
	$row [] = $data ['end_date'];
	
	//$html_action = "<a href='show_all.php?todo=view&id=" . $data ['anno_id'] . "&course_code=" . $_course ['sysCode'] . "' target='_blank'>" . Display::return_icon ( "info3.gif", get_lang ( 'Info' ), array ('style' => 'vertical-align: middle;' )  ) . "</a>";
	$html_action = "&nbsp;&nbsp;" . link_button ( 'edit.gif', 'Modify', 'announcement_update.php?action=modify&id=' . $data ['anno_id'], '90%', '90%', FALSE );
	$html_action .= "&nbsp;&nbsp;" . confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', "index.php?action=delete&id=" . $data ['anno_id'] );
	$row [] = $html_action;
	$table_data [] = $row;
}
if($row['1']==NULL){
    echo '<div class="actions">';
    echo link_button ( 'announce_add.gif', 'AddCourseAnnouncement', 'announcement_update.php?action=add', '90%', '90%' );
    echo '</div>';
}
unset ( $data, $row );

echo Display::display_table ( $table_header, $table_data );
Display::display_footer ();