<?php
/*
 * 待批改的考生
 */

$language_file = array ('exam', 'exercice', 'admin' );
include_once ('../../inc/global.inc.php');
api_block_anonymous_users ();
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( SYS_CODE_PATH ) . "exercice/exercise.lib.php");
$objExam = new Exercise ();
$exam_id = (isset ( $_REQUEST ['exam_id'] ) ? getgpc ( 'exam_id' ) : "");
$is_allowed = ($objExam->is_exam_manager ( $exam_id ) or api_is_platform_admin ());
if (! $is_allowed) api_deny_access ( FALSE );

$action = (isset ( $_REQUEST ['action'] ) ? getgpc ( 'action' ) : "");
$exam_id = (isset ( $_REQUEST ['exam_id'] ) ? getgpc ( 'exam_id' ) : "");
$dept_id = isset ( $_GET ['keyword_deptid'] ) ? getgpc ( 'keyword_deptid', 'G' ) : '0';

$objDept = new DeptManager ();
$sql = "SELECT * FROM " . $tbl_exam_main . " WHERE id=" . Database::escape ( $exam_id );
$exam_info = Database::fetch_one_row ( $sql, false, __FILE__, __LINE__ );

$htmlHeadXtra [] = Display::display_thickbox ();

Display::display_header ( null, true );
echo dispaly_intro_title ( $exam_info ['title'] . '<span style="padding-left:100px">'.get_lang ( 'QuizTotalScore' ).':'.$objExam->get_quiz_total_score($exam_id) .'</span>' );

$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{label}&nbsp;{element}</span> ' );

$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" );
$form->addElement ( 'text', 'keyword', $keyword_tip, array ('style' => "width:20%", 'class' => 'inputText', 'title' => $keyword_tip ) );

$depts = $objDept->get_sub_dept_ddl2 ( DEPT_TOP_ID, 'array' );
$form->addElement ( 'select', 'keyword_deptid', get_lang ( 'InDept' ), $depts, array ('id' => "dept_id", 'style' => 'height:22px;min-width:120px' ) );

$form->addElement ( 'hidden', 'exam_id', $exam_id );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );
echo '<div class="actions">';
$form->display ();
echo '</div>';

$query_vars = array ();
$query_vars ['exam_id'] = getgpc ( "exam_id", "G" );
$sql_where = "";
if (isset ( $_GET ['keyword'] ) && ! empty ( $_GET ['keyword'] )) {
	$query_vars ['keyword'] = trim ( getgpc('keyword') );
	$keyword = trim ( Database::escape_str ( $_GET ['keyword'], TRUE ) );
	if (! empty ( $keyword )) {
		$sql_where .= " AND  (t2.firstname LIKE '%" . $keyword . "%'  OR t2.username LIKE '%" . $keyword . "%')";
	}
}
if ($dept_id and ! is_equal ( $dept_id, 'null' )) {
	$query_vars ['keyword_deptid'] = $dept_id;
	$dept_sn = $objDept->get_sub_dept_sn ( $dept_id );
	if ($dept_sn) $sql_where .= " AND t2.dept_sn LIKE '" . $dept_sn . "%'";
}

$table_header [] = array (get_lang ( 'FirstName' ), true );
$table_header [] = array (get_lang ( 'LoginName' ), true );
$table_header [] = array (get_lang ( 'OfficialCode' ), true );
$table_header [] = array (get_lang ( 'InOrg' ) . '/' . get_lang ( 'InDept' ), true );
$table_header [] = array (get_lang ( 'ExamStartDate' ), true );
$table_header [] = array (get_lang ( 'ExamEndDate' ), true );
$table_header [] = array (get_lang ( 'YourScore' ) );
$table_header [] = array (get_lang ( 'GotScore' ) );
$table_header [] = array (get_lang ( 'Actions' ) );

$tbl_user_dept = Database::get_main_table ( VIEW_USER_DEPT );
$sql = "SELECT t1.*,t1.exe_user_id,t2.username,t2.firstname,t2.lastname,t2.dept_name,t2.org_name,t2.official_code ";
$sql .= " FROM " . $tbl_exam_result . " AS t1 LEFT JOIN " . $tbl_user_dept . " AS t2 ON t1.exe_user_id=t2.user_id  ";
$sql .= " WHERE t1.status='completed' AND t1.exe_exo_id=" . Database::escape ( $exam_id );
if ($sql_where) $sql .= $sql_where;
$rs = api_sql_query ( $sql, __FILE__, __LINE__ );
$datalist = api_store_result_array ( $rs );
foreach ( $datalist as $data ) {
	$row = array ();
	$row [] = invisible_wrap ( $data ['firstname'], $data ['fb_status'] == 0 );
	$row [] = invisible_wrap ( $data ['username'], $data ['fb_status'] == 0 );
	$row [] = invisible_wrap ( $data ['official_code'], $data ['fb_status'] == 0 );
	$row [] = invisible_wrap ( $data ['org_name'] . ' / ' . $data ['dept_name'], $data ['fb_status'] == 0 );
	$row [] = invisible_wrap ( $data ['start_date'], $data ['fb_status'] == 0 );
	$row [] = invisible_wrap ( $data ['exe_date'], $data ['fb_status'] == 0 );
	$row [] = invisible_wrap ( $data ['exe_result'], $data ['fb_status'] == 0 );
	$row [] = invisible_wrap ( round ( $data ['exe_result'] * 100 / $data ['exe_weighting'], 1 ), $data ['fb_status'] == 0 );
	
	//查看答卷
	$action = '&nbsp;&nbsp;' . icon_href ( 'edit_group.gif', 'ExamViewSub', '../exam_view.php?exam_id=' . $data ['exe_exo_id'] . '&result_id=' . $data ["exe_id"], '_blank' );
	
	//手工阅卷
	if ($data ['fb_status'] == 0) {
		$action .= '&nbsp;&nbsp;' . link_button ( 'plugin.gif', 'ExamCorrectedByHand', 'exam_judge.php?exam_id=' . $data ['exe_exo_id'] . '&result_id=' . $data ["exe_id"], '95%', '94%', FALSE, FALSE );
	}
	
	$row [] = $action;
	
	$table_data [] = $row;
}
unset ( $data, $row );

$sorting_options = array ('column' => 0, 'default_order_direction' => 'ASC' );
$query_vars = array ('exam_id' => $exam_id );
Display::display_sortable_table ( $table_header, $table_data, $sorting_options, array (), $query_vars );

Display::display_footer ( 1 );