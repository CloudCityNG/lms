 <?php
/*
 ==============================================================================
 学习进度(按学生(默认)及课程统计)
 ==============================================================================
 */
$language_file = array ('tracking', 'scorm', 'create_course', 'admin' );
include_once ('../inc/global.inc.php');

if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
if(isset($_GET['action']) && $_GET['action']=='export_log'){ 
    $file_dir=URL_ROOT."/www".URL_APPEDND."/storage/DATA/logs";
    if(!file_exists($file_dir)){
        exec("mkdir ".$file_dir);
        exec("chmod  -R 777 ".$file_dir);
    }
    $command="";
    $command="mysqldump -u".DB_USER." -p".DB_PWD." -t ".DB_NAME." track_e_cw > track_e_cw.lib;";   
    $command.="mysqldump -u".DB_USER." -p".DB_PWD." -t ".DB_NAME." sys_dept > sys_dept.lib;";    
    $command.="mysqldump -u".DB_USER." -p".DB_PWD." -t ".DB_NAME." course_rel_user > course_rel_user.lib;"; 
    exec(" cd  ".$file_dir.";".$command."tar  -zcvf   study_info.zip   track_e_cw.lib  sys_dept.lib  course_rel_user.lib");
    $file=$file_dir."/study_info.zip";   
    if(file_exists($file)){  
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exec("rm ".$file);
        exec("rm ".$file_dir."/track_e_cw.lib");
        exec("rm ".$file_dir."/sys_dept.lib");
        exec("rm ".$file_dir."/course_rel_user.lib");
        exit;
    }
        
}
require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'tracking.lib.php');
require_once (api_get_path ( SYS_CODE_PATH ) . 'reporting/cls.track_stat.php');

$course_code = isset ( $_REQUEST ['course_code'] ) && $_REQUEST ['course_code'] ? getgpc ( 'course_code' ) : api_get_course_code ();
$action = getgpc ( 'action', 'G' );
$objStat = new ScormTrackStat ();
$objDept = new DeptManager ();

$g_action=  getgpc('action'); 
if (isset ( $g_action )) {  
    switch ($action) {
        case 'export' :
            $data_header = array (get_lang ( 'CourseTitle' ), get_lang ( 'LoginName' ), get_lang ( 'FirstName' ), get_lang ( 'OfficialCode' ), get_lang ( 'UserInDept' ), get_lang ( 'GotCredit' ), get_lang ( 'LPProgress' ) . '(%)', get_lang ( 'LearningTime' ), get_lang ( 'LastLearningTime' ) );
            if (api_get_setting ( 'enable_modules', 'exam_center' ) == 'true') {
                    $data_header [] = get_lang ( 'ExamScore' );
            }
            $total_item_count = get_data_count ();
            $in_export = true;
            $export_data = get_data_list ( 0, $total_item_count, 0, "ASC" );
            //$filename = get_lang('Classes').get_lang('StatByUserDetails') .'_'. date ( 'Ymd' ); //导出文件名
            $filename = '在线课程学习情况_' . date ( 'Ymd' ); //导出文件名
            array_unshift ( $export_data, $data_header ); 
            Export::export_data ( $export_data, $filename, 'xls' );
            break;
    }
}

//JQuery,Thickbox
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

$nameTools = get_lang ( 'Tracking' );
Display::display_header ( $nameTools );

//课程的跟踪统计信息
function  title_filter($title){
	$result ='';
        $result.='<span style="float:left">&nbsp;&nbsp;'.$title.'</span>';
	return $result;
}

function get_sqlwhere() {
	global $objDept;
	$sql_where = "";
        $g_keyword=  getgpc('keyword');
	if (isset ( $g_keyword ) && ! empty ( $g_keyword ) && $g_keyword!="输入搜索关键词") {
		$keyword = escape ( $g_keyword, TRUE );
		$sql_where .= " AND (t1.firstname LIKE '%" . $keyword . "%'  OR t1.username LIKE '%" . $keyword . "%') ";
	}
	
        $g_keyword_deptid=  getgpc('keyword_deptid');
	if (isset ( $g_keyword_deptid ) and getgpc ( 'keyword_deptid' ) != "0") {
		$dept_id = intval ( escape ( getgpc ( 'keyword_deptid', 'G' ) ) );
		$dept_sn = $objDept->get_sub_dept_sn ( $dept_id );
		if ($dept_sn) $sql_where .= " AND dept_sn LIKE '" . $dept_sn . "%'";
	}
	
        $g_course_code=  getgpc('course_code');
	if (isset ( $g_course_code ) && ! empty ( $g_course_code )) {
		$sql_where .= " AND t1.course_code=" . Database::escape ( getgpc ( 'course_code', 'G' ) );
	}
	
	$sql_where = trim ( $sql_where );
	if ($sql_where)
		return substr ( ltrim ( $sql_where ), 3 );
	else return "";
}

function get_data_count() { 
        $count=  Database::getval("select count(id)  from track_e_cw");
        if($count==0) return  0;   
        $table_course_user = Database::get_main_table ( VIEW_COURSE_USER );
        $table_user = Database::get_main_table ( VIEW_USER_DEPT );
        $tbl_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
         $sql = "SELECT  COUNT(*)  FROM  $table_course_user AS t1 INNER JOIN $table_user AS t2 INNER JOIN track_e_cw AS t3 ON t1.user_id=t2.user_id WHERE t3.cc=t1.course_code AND t3.user_id=t1.user_id ";
        
        $sql_where = get_sqlwhere ();
        if ($sql_where) $sql .= " AND " . $sql_where; 
        return Database::get_scalar_value ( $sql );
}

function get_data_list($from, $number_of_items, $column, $direction) {
	global $objStat, $in_export;
	$table_course_user = Database::get_main_table ( VIEW_COURSE_USER );
	$table_user = Database::get_main_table ( VIEW_USER_DEPT );
	$sql = "SELECT t1.title  AS col0,
                                t1.username  AS col1,
                                 t1.firstname  AS col2,
                             t2.official_code  AS col3,
                             CONCAT(t2.org_id,'/',t2.dept_id)  AS col4,
                                     t1.got_credit  AS col5,
	                    t1.user_id  AS col6,
                             t1.course_code  AS col7,
                  DATE_FORMAT(FROM_UNIXTIME(t3.last_access_time),'%Y-%m-%d %H:%i') as time
	FROM $table_course_user AS t1 INNER JOIN $table_user AS t2 INNER JOIN track_e_cw AS t3 ON t1.user_id=t2.user_id WHERE t3.cc=t1.course_code AND t3.user_id=t1.user_id ";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " AND " . $sql_where;
        
	$sql .= " ORDER BY t3.last_access_time desc ";
	$sql .= " LIMIT $from,$number_of_items";

	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	while ( $row = Database::fetch_array ( $res, 'NUM' ) ) {
		$student_id = $row [6];
		$course_code = trim ( $row [7] );
		//$student_datas = UserManager::get_user_info_by_id ( $student_id );
		

		$avg_time_spent = $avg_student_score = $avg_student_progress = 0;
		$total_assignments = $total_messages = 0;
		
		//本课程学习时间
		$avg_time_spent = ($objStat->get_total_learning_time ( $student_id, $course_code ));
		
		//学习进度
		$avg_student_progress = $objStat->get_course_progress ( $course_code, $student_id );
		
		//考试得分
		if (api_get_setting ( 'enable_modules', 'exam_center' ) == 'true') {
			$avg_student_score = $objStat->get_course_exam_score ( $student_id, $course_code );
		}
		
		//$row [5] = $in_export?$avg_student_progress.'%':Display::display_progress_bar ( $avg_student_progress, '120px' );
		$row [6] = $avg_student_progress;
		if (api_get_setting ( 'enable_modules', 'exam_center' ) == 'true') {
			$row [7] = empty ( $avg_time_spent ) ? "" : api_time_to_hms ( $avg_time_spent );
			$row [8] = $row [8];
			//$row [9] = $avg_student_score ? $avg_student_score : '';
		} else {
			$row [7] = empty ( $avg_time_spent ) ? "" : api_time_to_hms ( $avg_time_spent );
			$row [8] = $row [8];
		}
		//$row [10] = empty ( $total_messages ) ? "" : $total_messages;
		$rows [] = $row;
	}
	return $rows;
}

function action_filter($student_id) {
	global $course_code;
	$html = link_button ( 'statistics.gif', 'Details', '../reporting/user_learning_stat.php?user_id=' . $student_id . '&course_code=' . $course_code, 420, 970, FALSE, TRUE );
	return $html;
}

if(isset($_GET['action']) && base64_decode($_GET['action'])=='all'){  
   $query=mysql_query('truncate table  `track_e_cw`');
   if($query){
       tb_close('learning_progress.php');
   }
}

/*$all_org = $objDept->get_all_org ();
$orgs [''] = get_lang ( 'All' );
foreach ( $all_org as $org ) {
	$orgs [$org ['id']] = $org ['dept_name'];
}*/

$g_keyword_diptid=  getgpc('keyword_deptid');
if (isset ( $g_keyword_diptid ) and getgpc ( 'keyword_deptid' ) != "0") {
	$all_sub_depts = $objDept->get_sub_dept_ddl ( $g_keyword_diptid );
	foreach ( $all_sub_depts as $item ) {
		$depts [$item ['id']] = str_repeat ( "&nbsp;&nbsp;", intval ( $item ['level'] / 2 ) ) . $item ['dept_name'];
	}
}

$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span class="searchtxt">{label}&nbsp;{element}</span> ' );

$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" );
$form->addElement ( 'text', 'keyword', $keyword_tip, array ('style' => "width:150px", 'class' => 'inputText','id'=>'searchkey','value'=>'输入搜索关键词', 'title' => $keyword_tip ) );

//$form->addElement ( 'select', 'keyword_org', get_lang ( 'InOrg' ), $orgs, array ('id' => "org_id", 'style' => 'height:22px;min-width:120px', 'title' => get_lang ( 'InOrg' ) ) );


$depts = $objDept->get_sub_dept_ddl2 ( DEPT_TOP_ID, 'array' );
$form->addElement ( 'select', 'keyword_deptid', get_lang ( 'InDept' ), $depts, array ('id' => "dept_id", 'style' => 'height:30px;min-width:120px' ) );

$sql = "SELECT t1.code,CONCAT(t1.title,'-',t1.code) FROM " . Database::get_main_table ( TABLE_MAIN_COURSE ) . " as t1 , course_rel_user as t2 where t1.code=t2.course_code";
$all_courses = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
$all_courses = array_insert_first ( $all_courses, array ('' => '' ) );
$form->addElement ( 'select', 'course_code', get_lang ( "Courses" ), $all_courses );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit",id="searchbutton"' );

$parameters = array ('action' => $action, 'course_code' => $course_code, 'keyword' => getgpc ( 'keyword', 'G' ), 'keyword_deptid' => $_GET ['keyword_deptid'] );
$url = api_add_url_querystring ( 'learning_progress.php?action=export', $parameters );



$table = new SortableTable ( 'lp_reporting', 'get_data_count', 'get_data_list', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );

$idx = 0;
$table->set_header ( $idx ++, get_lang ( 'CourseTitle' ), false, null, array ('style' => 'width:25%' ) );
//$table->set_header ( $idx ++, get_lang ( 'CourseCode' ) );
$table->set_header ( $idx ++, get_lang ( 'LoginName' ) );
$table->set_header ( $idx ++, get_lang ( 'FirstName' ) );
$table->set_header ( $idx ++, get_lang ( 'OfficialCode' ) );
//$table->set_header ( $idx ++, get_lang ( 'JobTitle' ) );
$table->set_header ( $idx ++, get_lang ( 'UserInDept' ) );
$table->set_header ( $idx ++, get_lang ( 'GotCredit' ) );
$table->set_header ( $idx ++, get_lang ( 'Progress' ) . '(%)', null, FALSE );
$table->set_header ( $idx ++, get_lang ( 'LearningTime' ), null, FALSE );
$table->set_header ( $idx ++, get_lang ( 'LastLearningTime' ), null, FALSE, array ('style' => 'width:10%' ) );
//if (api_get_setting ( 'enable_modules', 'exam_center' ) == 'true') {
	//$table->set_header ( $idx ++, get_lang ( 'ExamScore' ), null, FALSE );
//}

//$table->set_header ( $idx ++, get_lang ( 'Messages' ) );
//$table->set_header ( $idx ++, get_lang ( 'Actions' ), false );
//$table->set_column_filter ( 11, 'action_filter' );
$table->set_column_filter ( 0, 'title_filter' );

//Display::display_footer ( TRUE );
?>

<aside id="sidebar" class="column systeminfo  open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a>  &gt; <a href="<?=URL_APPEDND;?>/main/admin/log/logging_list.php">日志管理</a> &gt; 学习情况</h4>
    <div class="managerSearch"> 
        <?php $form->display ();?>
        <span class="searchtxt right">
        <?php // echo link_button ( 'excel.gif', 'Export', $url );?>
        </span>
        <div style="display:block;float:right;">
            <a class="one-delete" href="learning_progress.php?action=<?php echo base64_encode("all");?>" onclick="javascript:if(!confirm('是否全部删除?')) return false;"><span class="img-span"></span><span class="span-text">一键清除</span></a>&nbsp;
                  <!--<a class="one-delete" href="learning_progress.php?action=export_log" onclick="javascript:if(!confirm('是否导出?')) return false;"><span  class="img-span1"></span><span class="span-text">导出</span></a>-->
                  <!--<div class="one-delete one-import"><?php echo link_button ( 'import.png', '导入','../admin/logs_import.php', '30%', '40%' ); ?></div>-->
                                <span class="searchtxt right">
                            <?php
                            $report_count=Database::getval('SELECT COUNT(course_code) FROM `course_rel_user`',__FILE__,__LINE__);
                            if($report_count > 0){
                                echo '&nbsp;&nbsp;' . link_button ( 'excel.gif', '导出学习情况', 'learning_progress_export.php', '50%', '50%'  );
                            }
                            ?>
                    </span>
        </div>
    </div>
    <article class="module width_full hidden">
        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">
                <?php $table->display ();?>
            </table>
        </form>
    </article>

</section>
<style>
    .one-delete {
        float:right;
        display:block;
/*        margin-right:8px;*/
        padding:0 7px;
        position:relative;
    }
    .one-import{
/*       height:30px;*/
    }
    .one-import a{
        vertical-align:top;
    }
    .one-import a img{
        margin-top:-4px;
    }
    .one-delete  span{
        margin:0;
    }
    .one-delete  .img-span{
        display:inline-block;
        position:absolute;
        top:11px;
        left:0px;
        height:25px;
        width:25px;
        margin-right:5px;
        background:url("<?=api_get_path ( WEB_PATH )?>themes/images/one_delete.png")  no-repeat;
        
    }   
    .one-delete  .span-text{
       display:inline-block;
       padding:0 0 0 15px;
    }
    .img-span1{
        display:inline-block;
        position:absolute;
        top:11px;
        left:0px;
        height:25px;
        width:25px;
        margin-right:5px;
        background:url("<?=api_get_path ( WEB_PATH )?>themes/img/export.png")  no-repeat;
        
    }   
</style>
</body>
</html>
