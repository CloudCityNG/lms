<?php
/*
 题库
 */
include_once ('exercise.class.php');
include_once ('question.class.php');
include_once ('answer.class.php');

$language_file = 'exercice';
include_once ('../inc/global.inc.php');
include_once ('exercise.lib.php');

$exerciseId = intval ( getgpc ( 'exerciseId' ));
$objExercise = new Exercise ();
$objExercise->read ( $exerciseId );
//api_protect_quiz_script ( $objExercise->exam_manager );

$delete = getgpc ( 'delete' );
$recup = getgpc ( 'recup' );
$fromExercise = getgpc ( 'fromExercise' );

$is_course_exam = (is_object ( $objExercise ) && $objExercise ? ($objExercise->type == 2 && $objExercise->cc ? TRUE : FALSE) : false);

$documentPath = api_get_path ( SYS_COURSE_PATH ) . api_get_course_code () . '/document';
$picturePath = $documentPath . '/images';

if ($delete) { //删除
	if ($objQuestionTmp = Question::read ( $delete )) {
		$objQuestionTmp->delete ();
	}
	unset ( $objQuestionTmp );
} elseif ($recup && $fromExercise) { //增加到测验当中, gets an existing question and copies it into a new exercise
	if ($objQuestionTmp = Question::read ( $recup )) {
		$objQuestionTmp->addToList ( $fromExercise );
	}
	
	unset ( $objQuestionTmp );
	$objExercise->addToList ( $recup );
	
	api_session_register ( 'objExercise' );
	
	api_redirect ( "admin.php" );
}

if (isset ( $_POST ['action'] )) {
	switch ($_POST ['action']) {
		case 'batch_reuse' : //批量使用题库中试题
			$number_of_selected_items = count ( $_POST ['id'] );
			$number_of_deleted_items = 0;
			foreach ( $_POST ['id'] as $index => $item_id ) {
				if ($objQuestionTmp = Question::read ( $item_id )) {
					$objQuestionTmp->addToList ( $fromExercise );
					$number_of_deleted_items ++;
				}
				unset ( $objQuestionTmp );
				
				if (is_object ( $objExercise )) $objExercise->addToList ( $item_id );
			
			}
			api_session_register ( 'objExercise' );
			if (is_object ( $objExercise )) $exerciseId = $objExercise->selectId ();
			tb_close ();
			break;
		case 'batch_delete' : //批量删除
			$number_of_selected_items = count ( $_POST ['id'] );
			$number_of_deleted_items = 0;
			foreach ( $_POST ['id'] as $index => $item_id ) {
				if ($objQuestionTmp = Question::read ( $item_id )) {
					$objQuestionTmp->delete ();
					$number_of_deleted_items ++;
				}
				unset ( $objQuestionTmp );
			}
			tb_close ();
			break;
	}
}

function get_sqlwhere() {
	global $fromExercise, $TBL_EXERCICE_QUESTION;
	$sql_where = " AND t1.id NOT IN (SELECT question_id FROM $TBL_EXERCICE_QUESTION AS t2 WHERE exercice_id=" . Database::escape ( $fromExercise ) . ") ";
	
	if (isset ( $_GET ['type'] ) && ! empty ( $_GET ['type'] )) {
		$sql_where .= " AND t1.type=" . Database::escape ( getgpc ( 'type', 'G' ) );
	}
	
	if (isset ( $_GET ["pool_id"] ) && ! empty ( $_GET ["pool_id"] )) {
		$sql_where .= " AND t1.pool_id=" . Database::escape ( intval ( getgpc ( 'pool_id', 'G' )) );
	}
	
	if (is_not_blank ( $_GET ["level"] )) {
		$sql_where .= " AND level=" . Database::escape ( getgpc ( 'level' ) );
	}

	//使用率
                 if(!empty($_GET ["minimum_range"]) && !empty($_GET ["maximum_range"])){
                                        $min = $_GET ["minimum_range"]/100;//最小范围
                                        $max = $_GET ["maximum_range"]/100;//最大范围
                          if($max > $min){
                                        $rest_num = Database::getval("select count(question_id) from exam_rel_question",__FILE__,__LINE__);//统计已使用试题总次数
                                        $exam_ques = "SELECT count(*)num ,`question_id` FROM `exam_rel_question` group by`question_id`";
                                        $dat_sql = api_sql_query_array($exam_ques,__FILE__,__LINE__);
                           foreach($dat_sql as $dk => $dv){
                                        $tos = $dv['num']/$rest_num;
                                        if($tos > $min && $tos < $max){
                                            $ios[] = $dv['question_id'];
                                        }
                           }
                           $sql_where .= ' AND t1.id in (' . implode($ios, ',') . ')';
                           }else if($max < $min){
                                      echo "<script>alert('最大范围必须大于最小范围')</script>";
                          }
                 }else if(isset ( $_GET ["minimum_range"] ) && !empty ( $_GET ["minimum_range"])){
                             if(isset ( $_GET ["maximum_range"] ) && empty ( $_GET ["maximum_range"])){
                                      echo "<script>alert('最小范围和最大范围,其中一项不能为空!')</script>";
                             }
                 }else if(isset ( $_GET ["maximum_range"] ) && !empty ( $_GET ["maximum_range"])){
                             if(isset ( $_GET ["minimum_range"] ) && empty ( $_GET ["minimum_range"])){
                                      echo "<script>alert('最小范围和最大范围,其中一项不能为空!')</script>";
                             }
                 }

                 if(isset ( $_GET ["error_rate"] ) && ! empty ( $_GET ["error_rate"] )){//根据出错率筛选试题
                                   $post_duty = $_GET ["error_rate"]/100;
                                   //统计每道题出错总次数和每道题被使用的总次数
                                   $qs_error = "SELECT count(*)totle ,a.`question_id`,b.num FROM `exam_attempt` a left join (select count(*)num ,question_id FROM `exam_attempt` where marks=0 group by`question_id`) b ON a.question_id =b.question_id group by`question_id`";
                                   $err_da = api_sql_query_array($qs_error,__FILE__,__LINE__);
                          foreach($err_da as $vs => $va){
                                   $error_rate = $va['num']/$va['totle'];//每道题出错总次数/每道题被使用的总次数
                                   if($error_rate > $post_duty){
                                     $smarty[] = $va['question_id'];  
                                   }
                          }
                          $sql_where .= ' AND t1.id in (' . implode($smarty, ',') . ')';
                 }
        
	if (is_not_blank ( $_GET ["keyword"] )) {
		$keyword = trim ( Database::escape_str ( getgpc ( 'keyword' ), TRUE ) );
		$sql_where .= " AND  question_code LIKE '%" . $keyword . "%'";
	}
	
	if (is_not_blank ( $_GET ['cc'] )) {
		$sql .= " AND cc=" . Database::escape ( getgpc ( 'cc', 'G' ) );
	}
	return trim ( $sql_where );
}

function get_number_of_items() {
	global $tbl_exam_question;
	$sql = "SELECT COUNT(id) FROM $tbl_exam_question AS t1  WHERE t1.pid=0 ";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;

	return Database::get_scalar_value ( $sql );
}

function get_data_list($from, $number_of_items, $column, $direction) {
	global $_question_types, $_question_level, $tbl_exam_question;
	$tbl_exam_question = Database::get_main_table ( TABLE_MAIN_EXAM_QUESTION );
	$sql = "SELECT id AS col0,question AS col1,type AS col2,level AS col3 FROM $tbl_exam_question AS t1  WHERE t1.pid=0 ";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
	$sql .= " ORDER BY col$column $direction ";
	$sql .= " LIMIT $from,$number_of_items";
	$rs = api_sql_query ( $sql, __FILE__, __LINE__ );
	$datalist = array ();
	while ( $row = Database::fetch_row ( $rs ) ) {
		$row [1] = strip_tags($row [1]) ;
		$row [2] = $_question_types [$row [2]];
		$row [3] = $_question_level [$row [3]];
		$datalist [] = $row;
	}
	return $datalist;
}

$nameTools = get_lang ( 'ChoseFromQuestionPool' );
Display::display_header ( $nameTools, FALSE );

$form = new FormValidator ( 'question_pool_form', 'get', $_SERVER ['PHP_SELF'] );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$form->addElement ( 'hidden', 'fromExercise', $fromExercise );
$form->addElement ( 'static', '', '', get_lang ( '题目：' ) );
$form->addElement ( "text", "keyword", null, array ("class" => "inputText" ) );
$form->addElement ( 'static', '', '', get_lang ( '类型：' ) );
$form->addElement ( 'select', 'type', null, $_question_types );

//难度
$form->addElement ( 'static', '', '', get_lang ( '难度：' ) );
$level_options = array ('0' => get_lang ( "All" ), '1' => get_lang ( "DifficultyEasier" ), '2' => get_lang ( "DifficultyEasy" ), '3' => get_lang ( "DifficultyNormal" ), '4' => get_lang ( "DifficultyHard" ), '5' => get_lang ( "DifficultyHarder" ) );
$form->addElement ( "select", "level", get_lang ( "DifficultyLevel" ), $level_options, array ("style" => '' ) );

$sql = "SELECT id,pool_name FROM " . $tbl_exam_question_pool . "  ORDER BY display_order ASC";
$all_pools = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
$all_pools = array_insert_first ( $all_pools, array ('0' => get_lang ( "All" ) ) );
$form->addElement ( 'static', '', '', get_lang ( '题库：' ) );
$form->addElement ( 'select', 'pool_id', get_lang ( "QuestionPool" ), $all_pools );
$defaults ['pool_id'] = intval ( getgpc ( 'pool_id' ));

//使用频率
$form->addElement ( 'static', '', '', get_lang ( '频率:' ) );
$form->addElement ( 'static', '', '', get_lang ( '' ) );
$form->addElement ( 'static', '', '', get_lang ( '最小范围:' ) );

$form->addElement ( "text", "minimum_range", null, array ("class" => "inputText",'pattern'=>"([0-9])|([1-9][0-9])|100" ) );

//$form->addElement ( "text", "minimum_range", null, array ("class" => "inputText",'pattern'=>"^[0-9].*$" ) );

$form->addElement ( 'static', '', '', get_lang ( '最大范围:' ) );

$form->addElement ( "text", "maximum_range", null, array ("class" => "inputText",'pattern'=>"([0-9])|([1-9][0-9])|100") );



//$form->addElement ( "text", "maximum_range", null, array ("class" => "inputText",'pattern'=>"^[0-9].*$" ) );


//出错率
$form->addElement ( 'static', '', '', get_lang ( '出错率:' ) );
$form->addElement ( "text", "error_rate", null, array ("class" => "inputText",'pattern'=>"([0-9])|([1-9][0-9])|100") );

$sql = "SELECT code,title FROM " . $tbl_course . "";
$all_courses = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
$all_courses = array_insert_first ( $all_courses, array ('' => get_lang ( "All" ) ) );
?>
<b class="round" style="display: none">最大范围不能小于最小范围</b>
<?php
$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Filter' ), 'class="inputSubmit"' );
if ($fromExercise) {
	$group [] = & HTML_QuickForm::createElement ( 'style_button', 'rtn', null, array ('type' => 'button', 'value' => get_lang ( 'GoBackToEx' ), 'class' => "cancel", 'onclick' => 'javascript:self.parent.tb_remove();' ) );
}
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
$form->setDefaults ( $defaults );
echo '<div class="actions">';
$form->display ();
echo '</div>';

$query_vars = array ('fromExercise' => $fromExercise, 'type' => getgpc ( 'type', 'G' ), 'keyword' => getgpc ( 'keyword', 'G' ), 'pool_id' => intval ( getgpc ( 'pool_id', 'G' )) );
$table = new SortableTable ( 'question_list', 'get_number_of_items', 'get_data_list', 2, NUMBER_PAGE, 'ASC' );
$table->set_additional_parameters ( $query_vars );
$idx = 0;
$table->set_header ( $idx ++, '', false );
$table->set_header ( $idx ++, get_lang ( '题目' ) );
$table->set_header ( $idx ++, get_lang ( 'Type' ) );
$table->set_header ( $idx ++, get_lang ( 'DifficultyLevel' ) );
$actions = array ('batch_reuse' => get_lang ( 'ReuseToQuiz' ) );
$table->set_form_actions ( $actions );
$table->display ();

Display::display_footer ();
?>
<style>
    #question_pool_form{padding: 15px 10px 15px 30px;}
    .h5{height:5px;}
    .actions span{display: inline-block;width: 100px;}
    .actions span input,.actions span select{width:300px;box-sizing: border-box;}
    .actions span .cancel,.actions span .inputSubmit{display: inline-block;width: 196px;}
    form[name="form_question_list"]{padding: 10px;}
    form[name="form_question_list"] .p-table tr:first-child th:nth-child(2){width: 80%;}
    form[name="form_question_list"] .searchform .inputSubmit{margin: 0 10px;}
</style>
<script>
    $(function(){
        $('.actions span:odd').after('<div class="h5"></div>');
        $('.actions span:last').css('width','auto');
        $('[name="maximum_range"]').blur(function(event) {
            var minimum_range = parseInt($('[name="minimum_range"]').val());
            var maximum_range = parseInt($('[name="maximum_range"]').val());
         if(maximum_range<minimum_range){
            $(".round").show();
        }
        else
        {
            $(".round").hide();
        }
        })
    });
</script>

