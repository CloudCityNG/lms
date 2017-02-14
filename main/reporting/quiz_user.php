<?php
/*
 测验列表
 */

$language_file = array ('exercice', 'admin' );
$cidReset = TRUE;
include_once ('../inc/global.inc.php');
include_once ('../exercice/exercise.class.php');
include_once ('../exercice/question.class.php');
include_once ('../exercice/answer.class.php');
include_once ('../exercice/exercise.lib.php');
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();

require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'tracking.lib.php');
require_once (api_get_path ( SYS_CODE_PATH ) . 'reporting/cls.track_stat.php');

$course_code = isset ( $_REQUEST ['course_code'] ) && $_REQUEST ['course_code'] ? getgpc ( 'course_code' ) : api_get_course_code ();
$action = getgpc ( 'action', 'G' );
$id =intval (  getgpc ( 'id' ));
$objStat = new ScormTrackStat ();
$objDept = new DeptManager ();

if (is_equal ( $_REQUEST ['action'], 'modify_score' )) {
	$sql = "SELECT score FROM " . $tbl_exam_rel_user . " t1 WHERE id=" . Database::escape ( $id );
	$old_score = Database::getval ( $sql );
	$form = new FormValidator ( 'modify_score', 'post' );
	$form->addElement ( 'hidden', 'id', $id );
	$form->addElement ( 'hidden', 'action', 'modify_score_save' );
	$form->add_textfield ( 'score', get_lang ( 'NewScore' ), true, array ('style' => "width:250px", 'class' => 'inputText' ) );
	$form->addRule ( 'score', get_lang ( 'ThisFieldIsRequired' ), 'required' );
	$form->addRule ( 'score', get_lang ( 'ThisFieldIsRequiredNumeric' ), 'numeric' );
	$group = array ();
	$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
	$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
	$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
	$defaults ['score'] = $old_score;
	$form->setDefaults ( $defaults );
	Display::setTemplateBorder ( $form, '99%' );
	Display::display_header ( NULL, false );
	$form->display ();
	Display::display_footer ();
	exit ();
}
if (is_equal ( $_REQUEST ['action'], 'modify_score_save' )) {
	$new_score = getgpc ( 'score' );
	$sql = "UPDATE " . $tbl_exam_rel_user . " SET score=" . Database::escape ( $new_score ) . "  WHERE id=" . Database::escape ( $id );
	api_sql_query ( $sql );
	tb_close ();
	exit ();
}

if (isset ( $_GET ['action'] )) {
	switch ($action) {
		case 'export' :
			$data_header = array (get_lang ( 'ExamTitle' ),
					get_lang ( 'Type' ),
					get_lang ( 'LoginName' ),
					get_lang ( 'FirstName' ),
					get_lang ( 'OfficialCode' ),
					get_lang ( 'UserInDept' ),
					get_lang ( 'FinalScore' ),
					get_lang ( 'MaxScore' ),
					get_lang ( 'MinScore' ),
					get_lang ( 'FirstAttemptScore' ),
					get_lang ( 'LastAttemptScore' ),
					get_lang ( 'FirstAttemptDate' ),
					get_lang ( 'LastAttemptDate' ),
					get_lang ( 'AttemptTimes' ),
					get_lang ( 'IsPased' ) );
			$in_export = true;
			$export_data = get_export_data_list ();
			//var_dump($export_data);exit;
			//$filename = get_lang('Classes').get_lang('StatByUserDetails') .'_'. date ( 'Ymd' ); //导出文件名
			$filename = '在线考试_' . date ( 'Ymd' ); //导出文件名
			array_unshift ( $export_data, $data_header );
			Export::export_data ( $export_data, $filename, 'xls' );
			break;
	}
}

$action=htmlspecialchars($_GET ['action']);

if (isset ($action)) {
    switch ($action) {
        case 'delete' :
            $uname= htmlspecialchars($_GET ['uid']);
            $title=htmlspecialchars($_GET ['title']);

            $exam_id=Database::getval('select id from exam_main where title="'.$title.'"',__FILE__,__LINE__);
            $user_id=Database::getval('select user_id from user where username="'.$uname.'"',__FILE__,__LINE__);
            if ( isset($exam_id) && $user_id!==''){

                $sql = "DELETE FROM `exam_rel_user` WHERE `exam_id`= '".$exam_id."' and `user_id`='".$user_id."'";
                $result = api_sql_query ( $sql, __FILE__, __LINE__ );

                tb_close ( "quiz_user.php" );
            }
            break;
    }
}
$htmlHeadXtra [] = Display::display_thickbox ();

$htmlHeadXtra [] = '<script language="JavaScript" type="text/JavaScript">
	$(document).ready( function() {
		$("#org_id").change(function(){
			$.get("' . api_get_path ( WEB_CODE_PATH ) . 'admin/ajax_actions.php",
				{action:"options_get_all_sub_depts",org_id:$("#org_id").val()},
				function(data,textStatus){
					//alert(data);
					$("#dept_id").html(data);
				});
		});
	});
</script>';
$htmlHeadXtra [] = '<style>#center{margin:0}</style>';

// Display::display_header ( NULL );
include_once("../inc/header.inc.php");
//课程的跟踪统计信息


function get_sqlwhere() {
	global $objDept;
	$sql_where = "";
	if (isset ( $_GET ['keyword'] ) && ! empty ( $_GET ['keyword'] )) {
		$keyword = escape ( $_GET ['keyword'], TRUE );
		$sql_where .= " AND (t3.title LIKE '%" . $keyword . "%') ";
	}
	if (isset ( $_GET ['keyword_user'] ) && ! empty ( $_GET ['keyword_user'] )) {
		$keyword = escape ( getgpc ( 'keyword_user' ), TRUE );
		$sql_where .= " AND (t2.firstname LIKE '%" . $keyword . "%'  OR t2.username LIKE '%" . $keyword . "%') ";
	}

	if (isset ( $_GET ['keyword_deptid'] ) and getgpc ( 'keyword_deptid' ) != "0") {
		$dept_id = (escape ( intval ( getgpc ( 'keyword_deptid', 'G' )) ));
		$dept_sn = $objDept->get_sub_dept_sn ( $dept_id );
		if ($dept_sn) $sql_where .= " AND dept_sn LIKE '" . $dept_sn . "%'";
	}

	if (isset ( $_GET ['course_code'] ) && ! empty ( $_GET ['course_code'] )) {
		$sql_where .= " AND t3.cc=" . Database::escape ( getgpc ( 'course_code', 'G' ) );
	}

	if (isset ( $_GET ['type'] ) && is_not_blank ( $_GET ['type'] )) {
		$sql_where .= " AND t3.type=" . Database::escape ( getgpc ( 'type', 'G' ) );
	}

	$sql_where = trim ( $sql_where );
	if ($sql_where)
		return substr ( ltrim ( $sql_where ), 3 );
	else return "";
}

function get_data_count() {
	global $TBL_EXERCICES, $tbl_exam_rel_user;
	$table_user = Database::get_main_table ( VIEW_USER_DEPT );
                  $userid = $_SESSION['_user']['user_id'];
                  if($_SESSION['_user']['status']==1){
	$sql = "SELECT COUNT(*) FROM $tbl_exam_rel_user AS t1 , $TBL_EXERCICES AS t3 , $table_user AS t2 WHERE t1.exam_id=t3.id AND t1.user_id=t2.user_id and t3.created_user='".$userid."' ";
                  }
                  else {
                  $sql = "SELECT COUNT(*) FROM $tbl_exam_rel_user AS t1 , $TBL_EXERCICES AS t3 , $table_user AS t2 WHERE t1.exam_id=t3.id AND t1.user_id=t2.user_id ";    
                  }
                  $sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " AND " . $sql_where;
	//echo $sql."<br/>";
	return Database::get_scalar_value ( $sql );
}

function get_data_list($from, $number_of_items, $column, $direction) {
	global $objStat, $in_export, $TBL_EXERCICES, $tbl_exam_rel_user;
	$table_user = Database::get_main_table ( VIEW_USER_DEPT );
	$fields = array ('t3.title',
			't3.cc',
			't2.username',
			't2.firstname',
			't2.official_code',
			"CONCAT(t2.org_name,'/',t2.dept_name)",
			't1.score',
			't1.paper_score',
			'is_pass',
			't3.type'
            );
                  $userid = $_SESSION['_user']['user_id'];
                  if($_SESSION['_user']['status']==1){
	$sql = "SELECT " . sql_field_list ( $fields ) . " FROM $tbl_exam_rel_user AS t1 , $TBL_EXERCICES AS t3 ,
	$table_user AS t2 WHERE t1.exam_id=t3.id AND t1.user_id=t2.user_id and t3.created_user='".$userid."' ";
                  }
                  else {
                            $sql = "SELECT " . sql_field_list ( $fields ) . " FROM $tbl_exam_rel_user AS t1 , $TBL_EXERCICES AS t3 ,
                            $table_user AS t2 WHERE t1.exam_id=t3.id AND t1.user_id=t2.user_id ";
                    }
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " AND " . $sql_where;
	$sql .= " ORDER BY col0 ASC ";
	$sql .= " LIMIT $from,$number_of_items";//$number_of_items";

	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	while ( $row = Database::fetch_array ( $res, 'NUM' ) ) {
		$type = Database::getval ("select `type` from `exam_main` where `title`='".$row[0]."'", __FILE__, __LINE__ );
		$exam_id = Database::getval ("select `id` from `exam_main` where `title`='".$row[0]."'", __FILE__, __LINE__ );
		$row [1] = Database::getval ("select `name` from `exam_type` where `id`=".$type, __FILE__, __LINE__ );
        
        $query1 = "SELECT SUM(`question_score`) FROM  `vslab`.`exam_rel_question` AS t3 WHERE t3.`exercice_id`=".$exam_id;
		$row [7] =  Database::getval( $query1, __FILE__, __LINE__ );
		$row [8] = ($row [8] == 1 ? get_lang ( 'Yes' ) : get_lang ( 'No' ));
                

       
        $actives='';
         if(isRoot()){
                     $actives.=link_button ( 'edit.gif', 'ModifyScore', 'quiz_user_edit.php?action=modify_score&title=' . $row [0].'&uid='.$row [2], '30%', '40%', 0 );
                     $actives.= confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'quiz_user.php?action=delete&title=' . $row [0].'&uid='.$row [2] );
         }
         $userid = Database::getval("select created_user from exam_main  where title = '".$row[0]."' ");
         if($_SESSION['_user']['status'] == 1 && $_SESSION['_user']['user_id'] == $userid){
                     $actives.=link_button ( 'edit.gif', 'ModifyScore', 'quiz_user_edit.php?action=modify_score&title=' . $row [0].'&uid='.$row [2], '30%', '40%', 0 );
                     $actives.= confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'quiz_user.php?action=delete&title=' . $row [0].'&uid='.$row [2] );
         }

        $row [9] =$actives;
		$rows [] = $row;
	}
	return $rows;
}

function get_export_data_list() {
	global $objStat, $in_export, $TBL_EXERCICES, $tbl_exam_rel_user;
	$table_course_user = Database::get_main_table ( VIEW_COURSE_USER );
	$table_user = Database::get_main_table ( VIEW_USER_DEPT );
	$fields = array ('t3.title',
			't3.cc',
			't2.username',
			't2.firstname',
			't2.official_code',
			"CONCAT(t2.org_name,'/',t2.dept_name)",
			't1.score',
			't1.paper_score',
			'is_pass',
			't3.type' );
	$sql = "SELECT " . sql_field_list ( $fields ) . " FROM $tbl_exam_rel_user AS t1 , $TBL_EXERCICES AS t3 , $table_user AS t2 WHERE t1.exam_id=t3.id AND t1.user_id=t2.user_id ";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " AND " . $sql_where;
	$sql .= " ORDER BY col1";
	//echo $sql;
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	while ( $row = Database::fetch_array ( $res, 'NUM' ) ) {
		$type = $row [16];
		if ($type == 3)
			$nameTools = '自测练习';
		elseif ($type == 2)
			$nameTools = '课程毕业考试';
		elseif ($type == 1)
			$nameTools = '综合考试';
		$row [1] = Database::getval ("select `name` from `exam_type` where `id`=".$type, __FILE__, __LINE__ );
		$row [11] = substr ( $row [11], 0, 16 );
		$row [12] = substr ( $row [12], 0, 16 );
		$row [14] = ($row [15] == 1 ? get_lang ( 'Yes' ) : get_lang ( 'No' ));
		unset($row[15]);
		unset ( $row [16] );
		$rows [] = $row;
	}
	return $rows;
}

if (isset ( $_GET ['keyword_deptid'] ) and getgpc ( 'keyword_deptid' ) != "0") {
	$all_sub_depts = $objDept->get_sub_dept_ddl ( intval ( $_GET ['keyword_deptid'] ));
	foreach ( $all_sub_depts as $item ) {
		$depts [$item ['id']] = str_repeat ( "&nbsp;&nbsp;", intval ( $item ['level'] / 2 ) ) . $item ['dept_name'];
	}
}

$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span class="searchtxt">{label}&nbsp;{element}</span> ' );
$form->addElement ( 'text', 'keyword', get_lang ( 'ExamTitle' ), array ('style' => "width:100px", 'class' => 'inputText', 'title' => get_lang ( 'ExamTitle' ) ) );

$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" );
$form->addElement ( 'text', 'keyword_user', $keyword_tip, array ('style' => "width:100px", 'class' => 'inputText', 'title' => $keyword_tip ) );

//$form->addElement ( 'select', 'keyword_org', get_lang ( 'InOrg' ), $orgs, array ('id' => "org_id", 'style' => 'height:22px;min-width:100px', 'title' => get_lang ( 'InOrg' ) ) );
$depts = $objDept->get_sub_dept_ddl2 ( DEPT_TOP_ID, 'array' );
$form->addElement ( 'select', 'keyword_deptid', get_lang ( 'InDept' ), $depts, array ('id' => "dept_id", 'style' => 'height:22px;min-width:100px' ) );

$ty = "SELECT id,name FROM  `exam_type` ";
$typ = api_sql_query ( $ty, __FILE__, __LINE__ );
$datatype = api_store_result_array ( $typ );
$all_types =array();
foreach($datatype as $k => $v){
    $all_types[$v['id']] = $v['name'];
}
$form->addElement ( 'select', 'type', get_lang ( "Type" ), $all_types );

$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit" id="searchbutton"' );

$parameters = array ();
if (isset ( $_GET ['action'] ) && is_not_blank ( $_GET ['action'] )) $parameters ['action'] = getgpc ( 'action', 'G' );
if (isset ( $_GET ['type'] ) && is_not_blank ( $_GET ['type'] )) $parameters ['type'] = getgpc ( 'type', 'G' );
if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ['keyword'] )) $parameters ['keyword'] = getgpc ( 'keyword', 'G' );
if (isset ( $_GET ['keyword_deptid'] ) && is_not_blank ( $_GET ['keyword_deptid'] )) $parameters ['keyword_deptid'] = intval ( getgpc ( 'keyword_deptid', 'G') );
if (isset ( $_GET ['keyword_user'] ) && is_not_blank ( $_GET ['keyword_user'] )) $parameters ['keyword_user'] = getgpc ( 'keyword_user', 'G' );
$url = api_add_url_querystring ( 'query_quiz.php?action=export', $parameters );



$table = new SortableTable ( 'query_quiz', 'get_data_count', 'get_data_list', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );

$idx = 0;
$table->set_header ( $idx ++, get_lang ( 'ExamTitle' ) );
$table->set_header ( $idx ++, get_lang ( 'Type' ) );
$table->set_header ( $idx ++, get_lang ( 'LoginName' ) );
$table->set_header ( $idx ++, get_lang ( 'FirstName' ) );
$table->set_header ( $idx ++, get_lang ( 'OfficialCode' ) );
$table->set_header ( $idx ++, get_lang ( 'UserInDept' ) );
$table->set_header ( $idx ++, get_lang ( 'FinalScore' ) );
$table->set_header ( $idx ++, get_lang ( 'ExamPaperScore' ) );
$table->set_header ( $idx ++, get_lang ( 'IsPased' ) );
$table->set_header ( $idx ++, get_lang ( 'Actions' ) );

//Display::display_footer ( TRUE );
if($platform==3){
    $nav='exercices';
}else{
    $nav='exercice';
}
?>
<aside id="sidebar" class="column exercices open">
    <div id="flexButton" class="closeButton close">
    </div>
</aside>


<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a>
        &gt; <a href="<?=URL_APPEDND;?>/main/exercice/exercice.php" title="考试管理">考试管理</a>
        &gt; 考试汇总</h4>

    <div class="managerSearch">
            <span style="float:right; padding-top:5px;margin-right:15px">
               <?php
               // echo link_button ( 'excel.gif','Export', 'result_export.php?action=add', '30%', '30%' ).'</span>';
                $form->display ();
                ?>
    </div>
    <article class="module width_full hidden">
        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">
<?php $table->display ();  ?>
            </table>
        </form>
    </article>

</section>
</body>
</html>