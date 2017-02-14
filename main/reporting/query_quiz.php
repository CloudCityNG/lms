<?php
$language_file = array ('exercice', 'admin' );
$cidReset = true;
include_once ('../inc/global.inc.php');
api_block_anonymous_users ();
require_once (api_get_path ( SYS_CODE_PATH ) . "exercice/exercise.lib.php");

$user_id = api_get_user_id ();
$objExam = new Exercise ();
$action = (isset ( $_REQUEST ['action'] ) ? getgpc ( 'action' ) : "");
$type = (isset ( $_GET ['type'] ) ? getgpc ( 'type', 'G' ) : 0);
$id = (isset ( $_REQUEST ['id'] ) ? intval ( getgpc ( 'id' ) ): "");
$htmlHeadXtra [] = Display::display_thickbox ();
$tool_name = get_lang ( 'Exam' );
//Display::display_header ( $tool_name, true );
include_once("../inc/header.inc.php");

function dir_is_empty($dir){
    if($handle = opendir($dir)){
    while($item = readdir($handle)){
        if ($item != '.' && $item != '..')return false;  }
    }
    return true;
}
$text_dir = APP_ROOT_PATH.URL_APPEND.'storage/exam/';

if($_GET['text'] === 'del'){
    if(!dir_is_empty($text_dir)) {
        exec('sudo rm -rf '.$text_dir.'*');
    }
}

$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{label}&nbsp;{element}</span> ' );
$keyword_tip = get_lang ( 'ExerciseName' );
$form->addElement ( 'text', 'keyword', get_lang ( 'ExerciseName' ), array ('style' => "width:23%", 'class' => 'inputText', 'title' => $keyword_tip,'id'=>'searchkey','value'=>'输入搜索关键词' ) );

$ty = "SELECT id,name FROM  `exam_type` ";
$typ = api_sql_query ( $ty, __FILE__, __LINE__ );
$datatype = api_store_result_array ( $typ );
$all_types =array();
foreach($datatype as $k => $v){
    $all_types[$v['id']] = $v['name'];
}

$form->addElement ( 'select', 'type', get_lang ( "Type" ), $all_types );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );

$table_header [] = array (get_lang ( 'ExerciseName' ), true );
$table_header [] = array (get_lang ( 'ExamProperty' ), true );

$table_header [] = array (get_lang ( '平均分' ), true );
$table_header [] = array (get_lang ( '最高分' ), true );
$table_header [] = array (get_lang ( '最低分' ), true );
$table_header [] = array (get_lang ( '及格人数' ), true );

$table_header [] = array (get_lang ( 'QuizAllowedDuration' ), true );
$table_header [] = array (get_lang ( 'ExerciseAttempts' ), true );
$table_header [] = array (get_lang ( 'QuestionCount' ), true );
$table_header [] = array (get_lang ( 'QuizTotalScore' ), true );
$table_header [] = array (get_lang ( 'ExamAttemptUserCount' ) );
$table_header [] = array (get_lang ( 'ExamToCorrectUserCount' ) );
$table_header [] = array (get_lang ( '分析' ),false,null,array('width'=>'5%'));
$table_header [] = array (get_lang ( '成绩查询' ),false,null,array('width'=>'5%'));

$query_vars = array ();
$sql_where = " t1.active=1 ";
if ($type) $sql_where .= " AND type=" . Database::escape ( $type );

//if($_SESSION['_user']['status'] != '10'){
//	$sql_where .= " AND t1.exam_manager= " . Database::escape ( $user_id );
//}

$g_keyword=  getgpc('keyword');
if (isset ( $g_keyword ) && ! empty ( $g_keyword )) {
	if($g_keyword=='输入搜索关键词'){
	    $g_keyword='';
	}
	$query_vars ['keyword'] = getgpc ( 'keyword' );
	$keyword = trim ( Database::escape_str ( $g_keyword, TRUE ) );
	if (! empty ( $keyword )) {
		$sql_where .= " AND  (title LIKE '%" . $keyword . "%')";
	}
}
$g_type=  getgpc('type');
if (isset ( $g_type ) && is_not_blank ( $g_type )) $query_vars ['type'] = getgpc ( 'type', 'G' );

$userid = $_SESSION['_user']['user_id'];
$tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
if($_SESSION['_user']['status']==1){
$sql = "SELECT t1.* FROM " . $TBL_EXERCICES . " AS t1 WHERE created_user ='".$userid."' ";
}
 else {
    $sql = "SELECT t1.* FROM " . $TBL_EXERCICES . " AS t1 WHERE 1 ";
}
if ($sql_where) $sql .= " AND " . $sql_where;
$sql .= " ORDER BY t1.id DESC";

$rs = api_sql_query ( $sql, __FILE__, __LINE__ );
$datalist = api_store_result_array ( $rs );
foreach ( $datalist as $data ) {
	//var_dump($data);exit;
	$tbl_row = array ();
	$exam_id = intval ( $data ['id'] );
	$tbl_row [] = $data ['title'];
       // $tbl_row [] = $questionCount > 0 ? '&nbsp;&nbsp;' . link_button ( 'preview.gif', 'Preview', '../exercice/exercise_preview.php?type=exercise&id=' . $exam_id, '90%', '90%', FALSE ) : '';
	 
	$tbl_row [] = Database::getval ("select `name` from `exam_type` where `id`=".$data ['type'], __FILE__, __LINE__ );
	 
	$tbl_row [] = Exercise::get_average($data ['id']);
    $tbl_row [] = Exercise::get_highest($data['id']);
    $tbl_row [] = Exercise::get_lowest($data['id']);
    $tbl_row [] = Exercise::get_passing($data['id']);

	$tbl_row [] = $data ['max_duration'] == 0 ? get_lang ( "Infinite" ) : ($data ['max_duration'] / 60) . "&nbsp;" . get_lang ( "Minites" );
    $tbl_row [] = ($data ['max_attempt'] == 0 ? get_lang ( "Infinite" ) : $data ['max_attempt']);
	
	$sqlquery = "SELECT count(*) FROM $TBL_EXERCICE_QUESTION WHERE `exercice_id` = '" . $exam_id . "'";
	$questionCount = Database::get_scalar_value ( $sqlquery, __FILE__, __LINE__ );
	$tbl_row [] = $questionCount;
	
	$tbl_row [] = Exercise::get_quiz_total_score ( $data ['id'] );//总分
	
	$tbl_row [] = $objExam->stat_exam_attempt_user_count ( $data ["id"] );
	//$tobecorrect_user_count = $objExam->stat_exam_tobecorrect_user_count ( $data ["id"] );
	$tobecorrect_user_count = $objExam->stat_exam_tobecorrect_user_count ( $exam_id );
	$tbl_row [] = $tobecorrect_user_count;
        if(isRoot()){
	$tbl_row [] = $questionCount > 0 ? '&nbsp;&nbsp;' . link_button ( 'info.gif', '考试结果分析', '../reporting/exercise_check.php?type=exercise&id=' . $exam_id, '90%', '90%', FALSE ) : '';
        }
        $userid = Database::getval("select created_user from exam_main  where title = '".$tbl_row[0]."' ");
         if($_SESSION['_user']['status'] == 1 && $_SESSION['_user']['user_id'] == $userid){
                $tbl_row [] = $questionCount > 0 ? '&nbsp;&nbsp;' . link_button ( 'info.gif', '考试结果分析', '../reporting/exercise_check.php?type=exercise&id=' . $exam_id, '90%', '90%', FALSE ) : '';
         }
	//考试管理
	$action='';
         if(isRoot()){
             $action .= '&nbsp;&nbsp;' . link_button ( 'statistics.gif', 'ExamResultQuery', '../exercice/exercise_result.php?exerciseId=' .$exam_id, '90%', '96%', FALSE );
         
         }
         $userid = Database::getval("select created_user from exam_main  where title = '".$tbl_row[0]."' ");
         if($_SESSION['_user']['status'] == 1 && $_SESSION['_user']['user_id'] == $userid){
	$action .= '&nbsp;&nbsp;' . link_button ( 'statistics.gif', 'ExamResultQuery', '../exercice/exercise_result.php?exerciseId=' .$exam_id, '90%', '96%', FALSE );
         }
	$tbl_row [] = $action;
	$table_data [] = $tbl_row;
}
unset ( $data, $tbl_row );

if($platform==3){
    $nav='exercices';
}else{
    $nav='exercice';
}
?>
<style type="text/css">
    .btn-style-01{
        border-style:none;
        padding:4px 10px;
        line-height:24px;
        color:#fff;
        font:12px "Microsoft YaHei", Verdana, Geneva, sans-serif;
        cursor:pointer;
        border:1px #14AD61 solid;
        -webkit-box-shadow:inset 0px 0px 1px #fff;
        -moz-box-shadow:inset 0px 0px 1px #fff;
        box-shadow:inset 0px 0px 1px #fff;/*内发光效果*/
        -webkit-border-radius:4px;
        -moz-border-radius:4px;
        border-radius:4px;/*边框圆角*/
        text-shadow:1px 1px 0px #18E241;/*字体阴影效果*/
        background-color:#25DD20;
        background-image: -webkit-linear-gradient(top, #1DDB27 0%, #67E801 100%);
    }
    .btn-style-01:hover {
        background-color:#18E241;
        background-image: -webkit-linear-gradient(top, #18E241 0%, #48CF8C 100%);
    }
</style>
<aside id="sidebar" class="column exercices open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt;<a href="<?=URL_APPEDND;?>/main/exercice/exercice.php" title="考试管理">考试管理</a>  &gt; 考试成绩查询</h4>
    <div class="managerSearch">
    <?php
            $form->display ();
    if(!dir_is_empty($text_dir)) {
        echo '<span class="searchtxt right" style=""><input type="button" style="width:80px;padding:0px;line-height:25px;" onclick="textdel();" class="btn-style-01" value="清空文档" /></span>';
    }
    ?>
    </div>
    <article class="module width_full hidden">
        <table cellspacing="0" cellpadding="0" class="p-table">
         <?php Display::display_sortable_table ( $table_header, $table_data, array (), array (), $query_vars, array (), NAV_BAR_BOTTOM );
            ?>
        </table>
    </article>
</section>
</body>
</html>
<?php
   if(!dir_is_empty($text_dir)) {
       ?>
       <script type="text/javascript">
           function textdel() {
                   if (confirm('确定删除所有考试报告？')){
                       location.href = 'query_quiz.php?text=del';
                   }
           };
       </script>
<?php
   }
?>
