<?php
/**
 ==============================================================================
 * @package zllms.admin
 ==============================================================================
 */
include_once ('exercise.class.php');
include_once ('question.class.php');
include_once ('answer.class.php');

$language_file = 'exercice';
include_once ('../inc/global.inc.php');
include_once ('exercise.lib.php');
api_protect_admin_script ();
define ( "QUESTION_OPTION_SPLIT_CHAR", "|" );
set_time_limit ( 0 );
include (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');

$alpha = array ('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );

$tool_name = get_lang ( 'Export' );
$interbreadcrumb [] = array ("url" => 'exercice.php', "name" => get_lang ( 'Exercices' ) );
$interbreadcrumb [] = array ("url" => 'question_base.php', "name" => get_lang ( 'QuestionPool' ) );

$form = new FormValidator ( 'export_questions' );
$form->addElement ( 'hidden', 'type', getgpc ( 'type', 'G' ) );
// $form->addElement ( 'header', 'header', get_lang ( 'ExportQuestions' ) );
$form->addElement ( 'hidden', 'file_type', 'xls' );

$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'checkbox', 'by_pool', null, get_lang ( 'ByPool' ), array ('id' => 'by_pool' ) );
//$group [] = & HTML_QuickForm::createElement ( 'checkbox', 'by_course', null, get_lang ( 'ByCourse' ), array ('id' => 'by_course' ) );
$form->addGroup ( $group, null, get_lang ( 'FilterCondition' ), '&nbsp;' );
$defaults ['status'] = STUDENT;

$sql = "SELECT id,pool_name FROM " . $tbl_exam_question_pool . "  ORDER BY display_order ASC";
$all_pools = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
$form->addElement ( 'select', 'pool_id', get_lang ( "QuestionPool" ), $all_pools );
$defaults ['pool_id'] = intval ( getgpc ( 'pool_id' ));

$sql = "SELECT code,title FROM " . $tbl_course . "";
$all_courses = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
$form->addElement ( 'select', 'cc', get_lang ( "Courses" ), $all_courses );

$list_arr = array (UNIQUE_ANSWER => get_lang ( "UniqueSelect" ), MULTIPLE_ANSWER => get_lang ( "MultipleSelect" ), TRUE_FALSE_ANSWER => get_lang ( "TrueFalseAnswer" ) );
if ($_configuration ['enable_question_freeanswer']) $list_arr [FREE_ANSWER] = get_lang ( "FreeAnswer" );

//实战题

if ($_configuration ['enable_question_combatquestion']) $list_arr [COMBAT_QUESTION] = get_lang ( "实战题" ); 

if ($_configuration ['enable_question_fillblanks']) $list_arr [FILL_IN_BLANKS] = get_lang ( "FillBlanks" );
$ams = & $form->addElement ( 'advmultiselect', "question_type", get_lang ( 'ExportQuestionType' ), $list_arr, array ('size' => 5, 'class' => 'pool', 'style' => 'width:150px;' ) );
//$ams->setLabel(array($label_upload_filetype, get_lang('AvailableFileType'),'', get_lang('SelectedFileType')));
$ams->setButtonAttributes ( 'add', array ('value' => '>>', 'class' => 'inputSubmitShort' ) );
$ams->setButtonAttributes ( 'remove', array ('value' => '<<', 'class' => 'inputSubmitShort' ) );
include (api_get_path ( INCLUDE_PATH ) . "conf/templates.php");
$template = $template ["html"] ["advmultiselect"];
$ams->setElementTemplate ( $template );
$form->addRule ( 'question_type', get_lang ( 'ThisFieldIsRequired' ), 'required' );

$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->setDefaults ( $defaults );

Display::setTemplateBorder ( $form, '98%' );
//$form->add_progress_bar();
if ($form->validate ()) {
	$export = $form->exportValues ();
	$export = $form->getSubmitValues ();
	$file_type = $export ['file_type'];
	$question_type = $export ['question_type'];
	//var_dump($question_type);exit;
	$cc = trim ( $export ['cc'] );
	$pool_id = trim ( $export ['pool_id'] );
	$data = array ();
	$data [] = array ('试题类型','试题内容',"题目分数","题目难度","答案", "题目解析","课程编号","选项A","选项B","选项C","选项D","选项E","选项F","选项G","选项H","选项I","选项J","是否有key值","是否有报告","科目场景","key1","key2","key3","key4","key5" );
	$option_arr= array("选项A","选项B","选项C","选项D","选项E","选项F","选项G","选项H","选项I","选项J");
    $key_title= array("key1","key2","key3","key4","key5");
    $filename = 'ExportQuestions_' . api_get_course_code () . '_' . date ( 'YmdHis' ); //导出文件名
	//description AS QuestionDescription,
	//t1.type IN (".UNIQUE_ANSWER.",".MULTIPLE_ANSWER.",".FILL_IN_BLANKS.",".FREE_ANSWER.",".TRUE_FALSE_ANSWER.")";
	$sql = "SELECT t1.type AS QuestionType, level AS Difficulty, ponderation AS Score,question AS QuestionTitle,
				 t2.Options,'' AS Answer, t1.comment AS QuestionAnalysis,'' AS QuestionTrunkLineNumber,t1.id AS question_id,t1.pid,t1.is_k,t1.is_up,t1.vm_name,t1.keyss,t1.key_score FROM $TBL_QUESTIONS AS t1
  				LEFT JOIN (SELECT GROUP_CONCAT(answer ORDER BY id SEPARATOR '" . QUESTION_OPTION_SPLIT_CHAR . "') AS Options,question_id
             	FROM $TBL_REPONSES AS t GROUP BY question_id) AS t2	ON t1.id = t2.question_id WHERE t1.pid=0 ";
	$sql .= " AND " . Database::create_in ( $question_type, "type" );
	if ($export ['by_course'] && $cc) $sql .= " AND t1.cc='" . $cc . "' ";
	if ($export ['by_pool'] && $pool_id) $sql .= " AND t1.pool_id='" . $pool_id . "' ";
	$sql .= "  ORDER BY question_code";

	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$questions = array ();
	$line = 2;
	while ( $row = Database::fetch_array ( $res, 'ASSOC' ) ) {
		if ($row ['QuestionType'] == UNIQUE_ANSWER) { //单选
            $row_1 = array();
			$row_1 ['试题类型'] = get_lang ( "UniqueSelect" );
            $row_1 ["试题内容"] = $row ["QuestionTitle"];
            $row_1 ["题目分数"] = $row ["Score"];
			$row_1 ["题目难度"] = $row ["Difficulty"];
			$sql = "SELECT id FROM " . $TBL_REPONSES . " WHERE question_id='" . $row ['question_id'] . "' AND correct=1";
			$anid = Database::get_scalar_value ( $sql );
			$row_1 ['答案'] = $alpha [$anid];
			$row_1 ['题目解析'] = $row ['QuestionAnalysis'];
			$row_1 ["课程编号"] = "";
            if($row ["Options"]){
                $options_arrs = array();
                $options_arrs = explode(QUESTION_OPTION_SPLIT_CHAR,$row ["Options"]);
                foreach($options_arrs as $option_k=>$option_v){
                    $row_1[$option_arr[$option_k]] = $option_v;
                }
            }
			unset ( $row ["question_id"], $row ["pid"] );
			$questions [] = $row_1;
			$line ++;
		}
		if ($row ['QuestionType'] == TRUE_FALSE_ANSWER) { //判断题
            $row_1 = array();
            $row_1 ['试题类型'] = get_lang ( "TrueFalseAnswer" );
            $row_1 ["试题内容"] = $row ["QuestionTitle"];
            $row_1 ["题目分数"] = $row ["Score"];
            $row_1 ["题目难度"] = $row ["Difficulty"];
			$sql = "SELECT answer FROM " . $TBL_REPONSES . " WHERE question_id='" . $row ['question_id'] . "' AND correct=1";
			$anid = Database::get_scalar_value ( $sql );
            $row_1 ['答案'] = $anid;
            $row_1 ['题目解析'] = $row ['QuestionAnalysis'];
            $row_1 ["课程编号"] = "";
			unset ( $row ["question_id"], $row ["pid"] );
			$questions [] = $row_1;
			$line ++;
		} elseif ($row ['QuestionType'] == MULTIPLE_ANSWER) { //多选
            $row_1 = array();
            $row_1 ['试题类型'] = get_lang ( "MultipleSelect" );
            $row_1 ["试题内容"] = $row ["QuestionTitle"];
            $row_1 ["题目分数"] = $row ["Score"];
            $row_1 ["题目难度"] = $row ["Difficulty"];
			$sql = "SELECT id FROM " . $TBL_REPONSES . " WHERE question_id='" . $row ['question_id'] . "' AND correct=1";
			$res2 = api_sql_query ( $sql, __FILE__, __LINE__ );
			while ( $row2 = Database::fetch_array ( $res2, 'ASSOC' ) ) {
                $row_1 ['答案'] .= $alpha [$row2 ['id']] . "|";
			}
			Database::free_result ( $res2 );
			unset ( $row2 );
            $row_1 ['题目解析'] = $row ['QuestionAnalysis'];
            $row_1 ["课程编号"] = "";
            if($row ["Options"]){
                $options_arrs = array();
                $options_arrs = explode(QUESTION_OPTION_SPLIT_CHAR,$row ["Options"]);
                foreach($options_arrs as $option_k=>$option_v){
                    $row_1[$option_arr[$option_k]] = $option_v;
                }
            }
			unset ( $row ["question_id"], $row ["pid"] );
			$questions [] = $row_1;
			$line ++;
		} elseif ($row ['QuestionType'] == FILL_IN_BLANKS) { //填空
			if ($_configuration ['enable_question_fillblanks']) {
                $row_1 = array();
				$fb_answer = $row ['Options'];
                $row_1 ['试题类型'] = get_lang ( "FillBlanks" );
                $row_1 ["试题内容"] = $row ["QuestionTitle"];
                $row_1 ["题目分数"] = $row ["Score"];
                $row_1 ["题目难度"] = $row ["Difficulty"];
				
				$fb_answers = explode ( "::", $fb_answer );
                $row_1 ['答案'] = $fb_answers [0]; //答案
                $row_1 ['题目解析'] = $row ['QuestionAnalysis'];
                $row_1 ["课程编号"] = "";
				unset ( $row ["question_id"], $row ["pid"] );
				$questions [] = $row_1;
				$line ++;
			}
		} elseif ($row ['QuestionType'] == FREE_ANSWER) { //简答
			if ($_configuration ['enable_question_freeanswer']) {
                $row_1 = array();
                $row_1 ['试题类型'] = get_lang ( "FreeAnswer" );
                $row_1 ["试题内容"] = $row ["QuestionTitle"];
                $row_1 ["题目分数"] = $row ["Score"];
                $row_1 ["题目难度"] = $row ["Difficulty"];
				$sql = "SELECT answer FROM " . $TBL_REPONSES . " WHERE question_id='" . $row ['question_id'] . "'";
                $row_1 ['答案'] = Database::get_scalar_value ( $sql );
                $row_1 ['题目解析'] = $row ['QuestionAnalysis'];
                $row_1 ["课程编号"] = "";
				unset ( $row ["question_id"], $row ["pid"] );
				$questions [] = $row;
				$line ++;
			}


		
			} elseif ($row ['QuestionType'] == COMBAT_QUESTION) { //实战题
                    if ($_configuration ['enable_question_combatquestion']) {
                               $row_1 = array();
                               $row_1 ['试题类型'] = get_lang ( "实战题" );
                               $row_1 ["试题内容"] = $row ["QuestionTitle"];
                               $row_1 ["题目分数"] = $row ["Score"];
                               $row_1 ["题目难度"] = $row ["Difficulty"];
                               $sql = "SELECT answer FROM " . $TBL_REPONSES . " WHERE question_id='" . $row ['question_id'] . "'";
                               $row_1 ['答案'] = Database::get_scalar_value ( $sql );
                               $row_1 ['题目解析'] = $row ['QuestionAnalysis'];
                               $row_1 ["课程编号"] = "";
                               $row_1 ["选项A"] = '';$row_1 ["选项B"] = '';$row_1 ["选项C"] = '';$row_1 ["选项D"] = '';$row_1 ["选项E"] = '';
                               $row_1 ["选项F"] = '';$row_1 ["选项G"] = '';$row_1 ["选项H"] = '';$row_1 ["选项I"] = '';$row_1 ["选项J"] = '';
                               if($row['is_k'] == 1) {
                                   $row_1['是否有key值'] = '是';
                               }else{
                                   $row_1['是否有key值'] = '否';
                               }
                               $row_1 ['是否有报告'] = $row ['is_up'];
                               $row_1 ['科目场景'] = $row ['vm_name'];
                        if($row['is_k'] == 1){
                            $keyss_arr = array();
                            $key_score = array();
                            $key_con = '';
                            $keyss_arr = unserialize($row['keyss']);
                            $key_score = unserialize($row['key_score']);
                            foreach($keyss_arr as $keyss_k => $keyss_v){
                                $row_1 [$key_title[$keyss_k]] = $keyss_v.'--->'.$key_score[$keyss_k];
                            }
                        }
                               unset ( $row ["question_id"], $row ["pid"] );
                               $questions [] = $row_1;
                               $line ++;
                    }

		} elseif ($row ["QuestionType"] == CLOZE_QUESTION) {//完形填空
			if ($_configuration ['enable_question_cloze']) {
                $row_1 = array();
                $row_1 ['试题类型'] = get_lang ( "ComboQuestion" );
                $row_1 ["试题内容"] = $row ["QuestionTitle"];
                $row_1 ["题目分数"] = $row ["Score"];
                $row_1 ["题目难度"] = $row ["Difficulty"];
                $row_1 ['答案'] = "";
                $row_1 ['题目解析'] = $row ['QuestionAnalysis'];
                $row_1 ["课程编号"] = "";
				$trunk_question_id = $row ['question_id'];
				unset ( $row ["question_id"], $row ["pid"] );
				$questions [] = $row_1;
				$line ++;
				
				$sql1 = "SELECT t1.type AS QuestionType, level AS Difficulty, ponderation AS Score,question AS QuestionTitle,
				 t2.Options,'' AS Answer, t1.comment AS QuestionAnalysis,'' AS QuestionTrunkLineNumber,t1.id AS question_id FROM $TBL_QUESTIONS AS t1
  				LEFT JOIN (SELECT GROUP_CONCAT(answer ORDER BY id SEPARATOR '" . QUESTION_OPTION_SPLIT_CHAR . "') AS Options,question_id
             	FROM $TBL_REPONSES  GROUP BY question_id) AS t2	ON t1.id = t2.question_id WHERE t1.pid='" . $trunk_question_id . "'";
				if ($export ['by_course'] && $cc) $sql .= " AND t1.cc='" . $cc . "' ";
				if ($export ['by_pool'] && $pool_id) $sql .= " AND t1.pool_id='" . $pool_id . "' ";
				$res1 = api_sql_query ( $sql1, __FILE__, __LINE__ );
				$line2 = 1;
				while ( $row1 = Database::fetch_array ( $res1, 'ASSOC' ) ) {
					if ($row1 ['QuestionType'] == UNIQUE_ANSWER) { //单选
                        $row_1 = array();
                        $row_1 ['试题类型'] = get_lang ( "UniqueSelect" );
                        $row_1 ["试题内容"] = $row1 ["QuestionTitle"];
                        $row_1 ["题目分数"] = $row1 ["Score"];
                        $row_1 ["题目难度"] = $row1 ["Difficulty"];
						$sql2 = "SELECT id FROM " . $TBL_REPONSES . " WHERE question_id='" . $row1 ['question_id'] . "' AND correct=1";
						$anid = Database::get_scalar_value ( $sql2 );
                        $row_1 ['答案'] = $alpha [$anid];
                        $row_1 ['题目解析'] = $row1 ['QuestionAnalysis'];
                        $row_1 ["课程编号"] = strval ( $line - $line2 );
                        if($row1 ["Options"]){
                            $options_arrs = array();
                            $options_arrs = explode(QUESTION_OPTION_SPLIT_CHAR,$row1 ["Options"]);
                            foreach($options_arrs as $option_k=>$option_v){
                                $row_1[$option_arr[$option_k]] = $option_v;
                            }
                        }
						unset ( $row1 ["question_id"], $row1 ["pid"] );
						$questions [] = $row_1;
						$line ++;
						$line2 ++;
					}
				}
			}
		} elseif ($row ["QuestionType"] == COMBO_QUESTION) {
			if ($_configuration ['enable_question_combo']) {
                $row_1 = array();
                $row_1 ['试题类型'] = get_lang ( "ComboQuestion" );
                $row_1 ["试题内容"] = $row ["QuestionTitle"];
                $row_1 ["题目分数"] = $row ["Score"];
                $row_1 ["题目难度"] = $row ["Difficulty"];
                $row_1 ['答案'] = "";
                $row_1 ['题目解析'] = $row ['QuestionAnalysis'];
                $row_1 ["课程编号"] = "";
				$trunk_question_id = $row ['question_id'];
				unset ( $row ["question_id"], $row ["pid"] );
				$questions [] = $row_1;
				$line ++;
				
				$sql1 = "SELECT t1.type AS QuestionType, level AS Difficulty, ponderation AS Score,question AS QuestionTitle,
				 t2.Options,'' AS Answer, t1.comment AS QuestionAnalysis,'' AS QuestionTrunkLineNumber,t1.id AS question_id FROM $TBL_QUESTIONS AS t1
  				LEFT JOIN (SELECT GROUP_CONCAT(answer ORDER BY id SEPARATOR '" . QUESTION_OPTION_SPLIT_CHAR . "') AS Options,question_id
             	FROM $TBL_REPONSES  GROUP BY question_id) AS t2	ON t1.id = t2.question_id WHERE t1.pid='" . $trunk_question_id . "'";
				if ($export ['by_course'] && $cc) $sql .= " AND t1.cc='" . $cc . "' ";
				if ($export ['by_pool'] && $pool_id) $sql .= " AND t1.pool_id='" . $pool_id . "' ";
				$res1 = api_sql_query ( $sql1, __FILE__, __LINE__ );
				$line2 = 1;
				while ( $row1 = Database::fetch_array ( $res1, 'ASSOC' ) ) {
					if ($row1 ['QuestionType'] == UNIQUE_ANSWER) { //单选
                        $row_1 = array();
                        $row_1 ['试题类型'] = get_lang ( "UniqueSelect" );
                        $row_1 ["试题内容"] = $row1 ["QuestionTitle"];
                        $row_1 ["题目分数"] = $row1 ["Score"];
                        $row_1 ["题目难度"] = $row1 ["Difficulty"];
						$sql2 = "SELECT id FROM " . $TBL_REPONSES . " WHERE question_id='" . $row1 ['question_id'] . "' AND correct=1";
						$anid = Database::get_scalar_value ( $sql2 );
                        $row_1 ['答案'] = $alpha [$anid];
                        $row_1 ['题目解析'] = $row1 ['QuestionAnalysis'];
                        $row_1 ["课程编号"] = strval ( $line - $line2 );
                        if($row1 ["Options"]){
                            $options_arrs = array();
                            $options_arrs = explode(QUESTION_OPTION_SPLIT_CHAR,$row1 ["Options"]);
                            foreach($options_arrs as $option_k=>$option_v){
                                $row_1[$option_arr[$option_k]] = $option_v;
                            }
                        }
						unset ( $row1 ["question_id"], $row1 ["pid"] );
						$questions [] = $row_1;
						$line ++;
						$line2 ++;
					}
					if ($row1 ['QuestionType'] == TRUE_FALSE_ANSWER) { //判断题
                        $row_1 = array();
                        $row_1 ['试题类型'] = get_lang ( "TrueFalseAnswer" );
                        $row_1 ["试题内容"] = $row1 ["QuestionTitle"];
                        $row_1 ["题目分数"] = $row1 ["Score"];
                        $row_1 ["题目难度"] = $row1 ["Difficulty"];
						$sql = "SELECT answer FROM " . $TBL_REPONSES . " WHERE question_id='" . $row ['question_id'] . "' AND correct=1";
						$anid = Database::get_scalar_value ( $sql );
                        $row_1 ['答案'] = $anid;
                        $row_1 ['题目解析'] = $row1 ['QuestionAnalysis'];
                        $row_1 ["课程编号"] = strval ( $line - $line2 );
						unset ( $row1 ["question_id"], $row1 ["pid"] );
						$questions [] = $row_1;
						$line ++;
						$line2 ++;
					} elseif ($row ['QuestionType'] == MULTIPLE_ANSWER) { //多选
                        $row_1 = array();
                        $row_1 ['试题类型'] = get_lang ( "MultipleSelect" );
                        $row_1 ["试题内容"] = $row1 ["QuestionTitle"];
                        $row_1 ["题目分数"] = $row1 ["Score"];
                        $row_1 ["题目难度"] = $row1 ["Difficulty"];
						$sql2 = "SELECT id FROM " . $TBL_REPONSES . " WHERE question_id='" . $row ['question_id'] . "' AND correct=1";
						$res2 = api_sql_query ( $sql2, __FILE__, __LINE__ );
						while ( $row2 = Database::fetch_array ( $res2, 'ASSOC' ) ) {
                            $row_1 ['答案'] .= $alpha [$row2 ['id']] . "|";
						}
						Database::free_result ( $res2 );
						unset ( $row2 );
                        $row_1 ['题目解析'] = $row1 ['QuestionAnalysis'];
                        $row_1 ["课程编号"] = strval ( $line - $line2 );
                        if($row1 ["Options"]){
                            $options_arrs = array();
                            $options_arrs = explode(QUESTION_OPTION_SPLIT_CHAR,$row1 ["Options"]);
                            foreach($options_arrs as $option_k=>$option_v){
                                $row_1[$option_arr[$option_k]] = $option_v;
                            }
                        }
						unset ( $row1 ["question_id"], $row1 ["pid"] );
						$questions [] = $row_1;
						$line ++;
						$line2 ++;
					}
				}
			}
		}
	}
	
	//}

	foreach ( $questions as $question ) {
		if ($file_type == 'csv') {
			$export_encoding = 'GBK';
			$question ['试题内容'] = mb_convert_encoding ( $question ['试题内容'], $export_encoding, SYSTEM_CHARSET );
			$question ['题目解析'] = mb_convert_encoding ( $question ['题目解析'], $export_encoding, SYSTEM_CHARSET );
		}
		$data [] = $question;
	}
	

	switch ($file_type) {
		case 'csv' :
			Export::export_table_data ( $data, $filename, 'csv' );
			break;
		case 'xls' :
			Export::export_table_data ( $data, $filename, 'xls', false );
			break;
	}
	tb_close ();
}

$htmlHeadXtra [] = $ams->getElementJs ( false );

$htmlHeadXtra [] = '<script type="text/javascript">
	$(document).ready( function() {
		$("tr.containerBody:eq(1)").hide();
		$("tr.containerBody:eq(2)").hide();
	
		$("#by_pool").click(function(){
			if($("#by_pool").attr("checked")){
				$("tr.containerBody:eq(1)").show();
			}else{
				$("tr.containerBody:eq(1)").hide();
			}
		});
		
		$("#by_course").click(function(){
				if($("#by_course").attr("checked")){
				$("tr.containerBody:eq(2)").show();
			}else{
				$("tr.containerBody:eq(2)").hide();
			}
		});
	});</script>';

Display::display_header ( $tool_name, FALSE );

$form->display ();

Display::display_footer ();
