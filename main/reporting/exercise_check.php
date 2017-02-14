
<?php
/*
 显示试卷及提交的答案，课程管理员则有批改校正成绩功能
 */

//include_once ('exercise.class.php');
//include_once ('question.class.php');
//include_once ('answer.class.php');

$language_file = array ('exercice', 'tracking' );
include_once ('../inc/global.inc.php');
require_once (api_get_path ( SYS_CODE_PATH ) . "exercice/exercise.lib.php");
require_once (api_get_path ( SYS_CODE_PATH ) . "exercice/exercise.class.php");
//include_once ('exercise.lib.php');

$questionId = intval ( getgpc ( 'questionId') );
$id = intval ( getgpc ( 'id') );
$type = getgpc ( 'type' );
$sql = "SELECT title,exam_manager FROM " . $tbl_exam_main . " as t1 WHERE t1.id=" . Database::escape ( $id );
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
list ( $test, $exam_manager_id ) = Database::fetch_row ( $res );
api_protect_quiz_script ( $exam_manager_id );
$exerciseTitle = api_parse_tex ( $test );

$htmlHeadXtra [] = '<style type="text/css">
ul, li {list-style:none outside none; font-size:13px}
.sectiontitle{float:left;padding-left:10px;width:98%}
#comments {	position: absolute;	left: 795px;	top: 0px;	width: 200px;	height: 75px;	z-index: 1;}
#question { margin-bottom:2px; padding-top:2px;}
#quizTitle { margin-top:10px; margin-bottom:10px;font-size:16px;font-weight:bold }
#qstnName { }
#selectQsnAnswer{ margin-bottom:0px; }
#selectQsnAnswer ul li {float:left;overflow:hidden;padding-left:20px;padding-right:5px;text-indent:8px;white-space:nowrap;}
#correctAnswer {padding-bottom:10px; margin-bottom:4px;margin-top:6px;float:right}
#subQuestion { width:90%;float:right;margin-bottom:10px; }
.clear{clear:both;}
</style>';
Display::display_header ( null, FALSE );

function get_wrong_num($questionId,$id) {
//$sq2="select count(id) from exam_attempt where question_id=$questionId and exe_id=$exe_id and answer!='".$answer."'";
    $sq2="select count(id) from exam_attempt as a left join exam_track as t on a.exe_id = t.exe_id where question_id=$questionId and t.exe_exo_id=$id and marks=0";
    $num=Database::getval($sq2);
//    echo $sq2;
    return $num;
}





function show_question($questionId, $idx, $questionPonderation) {
	global $TBL_EXERCICE_QUESTION, $TBL_EXERCICES, $TBL_QUESTIONS;
	$objQuestionTmp = Question::read ( $questionId );
	if (empty ( $objQuestionTmp )) return "";
	
	$answerType = $objQuestionTmp->selectType ();
	$questionName = $objQuestionTmp->selectTitle ();
	$questionName = api_parse_tex ( $questionName );
	$flv_path = $objQuestionTmp->selectPicture ();
	//$questionPonderation=$objQuestionTmp->selectWeighting();
	$objAnswerTmp = new Answer ( $questionId );
	$nbrAnswers = $objAnswerTmp->selectNbrAnswers ();
	$questionScore = 0;
	$s = "";
	if ($answerType == UNIQUE_ANSWER or $answerType == TRUE_FALSE_ANSWER or $answerType == MULTIPLE_ANSWER) {
		$s .= '<div id="question">';
		$s .= '<div class="sectiontitle">' . "&nbsp;" . ($idx);
		$s .= ":&nbsp;" . Question::get_question_type_name ( $answerType );
		$s .= ' (' . ($questionPonderation) . get_lang ( 'Fen' ) . ')';
		$s .= '<span id="qstnName" style="padding-left: 10px">' . $questionName . '</span>';
		$s .= '</div>';
		$s .= '<div class="clear"></div>';
		
		if ($flv_path) {
			$s .= '<div style="clear:both"></div>';
			$s .= '<div style="padding-left:100px">
 <object type="application/x-shockwave-flash"
	data="' . api_get_path ( WEB_CODE_PATH ) . 'courseware/player.swf" width="360" height="270">
	<param name="movie" value="' . api_get_path ( WEB_CODE_PATH ) . 'courseware/player.swf" />
	<param name="allowfullscreen" value="true" />
	<param name="allowscriptaccess" value="always" />
	<param name="flashvars"
		value="file=' . api_get_path ( WEB_PATH ) . $flv_path . '&image=preview.jpg&autostart=false" />
	<p><a href="http://get.adobe.com/flashplayer">Get Flash</a> to see thisplayer.</p>
</object></div>';
		}
		$s .= '<div id="qstnAnswer">';
		$s .= "<div id='selectQsnAnswer'><ul>";
		for($answerId = 1; $answerId <= $nbrAnswers; $answerId ++) {
			$answer = $objAnswerTmp->selectAnswer ( $answerId );
			$answerCorrect = $objAnswerTmp->isCorrect ( $answerId );
			
			if ($answerType == UNIQUE_ANSWER or $answerType == TRUE_FALSE_ANSWER) {
				$s .= "<li><input class='checkbox' type='radio' name='choice[" . $questionId . "]' value='" . $answerId . "'><div style='margin-left:5px;display:inline'>";
				$s .= Question::$alpha [$answerId] . ". " . api_parse_tex ( $answer );
				$s .= "</div></li>";
			
			} elseif ($answerType == MULTIPLE_ANSWER) {
				$s .= "<li><input class='checkbox' type='checkbox' name='choice[" . $questionId . "][" . $answerId . "]' value='1'>&nbsp;";
				
				$s .= Question::$alpha [$answerId] . ". " . api_parse_tex ( $answer );
				$s .= "</li>";
			}
		}
		$s .= "</ul></div>";
		$s .= '</div>';
		
		$s .= '</div>' . "\r\n";
		
		$s .= '<div id="correctAnswer">' . get_lang ( "StandardAsnwer" ) . ": " . $objAnswerTmp->correctAnswer . '</div>';
	} elseif ($answerType == FILL_IN_BLANKS) {
		$s .= '<div id="question">';
		$s .= '<div class="sectiontitle">' . "&nbsp;" . get_lang ( "Question" ) . '' . ($idx);
		$s .= ":&nbsp;" . Question::get_question_type_name ( $answerType );
		$s .= ' (' . ($questionPonderation) . get_lang ( 'Fen' ) . ')';
		$s .= '</div>';
		$s .= '<div id="qstnName">' . $questionName . '</div>';
		
		$s .= '<div id="qstnAnswer">';
		for($answerId = 1; $answerId <= $nbrAnswers; $answerId ++) {
			$answer = $objAnswerTmp->selectAnswer ( $answerId );
			
			// splits text and weightings that are joined with the character '::'
			list ( $answer ) = explode ( '::', $answer );
			
			// because [] is parsed here we follow this procedure:
			// 1. find everything between the [tex] and [/tex] tags
			$startlocations = strpos ( $answer, '[tex]' );
			$endlocations = strpos ( $answer, '[/tex]' );
			
			if ($startlocations !== false && $endlocations !== false) {
				$texstring = api_substr ( $answer, $startlocations, $endlocations - $startlocations + 6 );
				// 2. replace this by {texcode}
				$answer = str_replace ( $texstring, '{texcode}', $answer );
			}
			
			// replaces [blank] by an input field
			$answer = ereg_replace ( '\[[^]]+\]', '<input type="text" name="choice[' . $questionId . '][]" class="inputText">', nl2br ( $answer ) );
			// 4. replace the {texcode by the api_pare_tex parsed code}
			$texstring = api_parse_tex ( $texstring );
			$answer = str_replace ( "{texcode}", $texstring, $answer );
		
		//$s.=$answer;//V2.2 不显示了
		}
		
		$s .= '</div>';
		$s .= '</div>' . "\r\n";
		
		$s .= '<div id="correctAnswer">' . get_lang ( "StandardAsnwer" ) . ": " . $objAnswerTmp->correctAnswer . '</div>';
	} elseif ($answerType == FREE_ANSWER) {
		//$objQuestion=Question::getInstance($answerType);
		$std_answer_str = FreeAnswer::get_correct_answer_str ( $questionId );
		$s .= '<div id="question">';
		$s .= '<div class="sectiontitle">' . "&nbsp;" . get_lang ( "Question" ) . '' . ($idx);
		$s .= ":&nbsp;" . Question::get_question_type_name ( $answerType );
		$s .= ' (' . ($questionPonderation) . get_lang ( 'Fen' ) . ')';
		$s .= '</div>';
		$s .= '<div class="clear"></div>';
		$s .= '<div id="qstnName">' . $questionName . '</div>';
		$s .= '</div>' . "\r\n";
		$s .= '<div class="clear"></div>';
		$s .= '<div style="float:left;"><b>' . get_lang ( "StandardAsnwer" ) . '</b>: <br/><div style="padding:3px 25px;border:1px dotted #666;background-color:#eff">' . nl2br ( $std_answer_str ) . '</div></div>';
	} 
        elseif ($answerType == COMBAT_QUESTION) {
		//$objQuestion=Question::getInstance($answerType);
		$std_answer_str = FreeAnswer::get_correct_answer_str ( $questionId );
		$s .= '<div id="question">';
		$s .= '<div class="sectiontitle">' . "&nbsp;" . get_lang ( "Question" ) . '' . ($idx);
		//$s .= ":&nbsp;" . Question::get_question_type_name ( $answerType );
                $s .= ":&nbsp;实战题";
		$s .= ' (' . ($questionPonderation) . get_lang ( 'Fen' ) . ')';
		$s .= '</div>';
		$s .= '<div class="clear"></div>';
		$s .= '<div id="qstnName">' . $questionName . '</div>';
		$s .= '</div>' . "\r\n";
		$s .= '<div class="clear"></div>';
		$s .= '<div style="float:left;"><b>' . get_lang ( "StandardAsnwer" ) . '</b>: <br/><div style="padding:3px 25px;border:1px dotted #666;background-color:#eff">' . nl2br ( $std_answer_str ) . '</div></div>';
	} 

	elseif ($answerType == COMBO_QUESTION) {
		$s .= '<div id="question">';
		$s .= '<div class="sectiontitle">' . "&nbsp;" . get_lang ( "Question" ) . '' . ($idx);
		$s .= ":&nbsp;" . Question::get_question_type_name ( $answerType );
		$s .= ' (' . ($questionPonderation) . get_lang ( 'Fen' ) . ')';
		$s .= '</div>';
		$s .= '<div id="qstnName">' . $questionName . '</div>';
		$s .= '</div>' . "\r\n";
		
		$s .= '<table width="90%" style="float:right">';
		$s .= "<div id='subQuestion'>";
		$sql = "SELECT * FROM " . $TBL_QUESTIONS . " WHERE pid=" . Database::escape ( $questionId ) . " ORDER BY position";
		//echo $sql;
		$sub_rs = api_sql_query ( $sql, __FILE__, __LINE__ );
		$ii = 1;
		while ( $sub_question_row = Database::fetch_array ( $sub_rs ) ) {
			$subQuestionId = $sub_question_row ['id'];
			$objSubQuestionTmp = Question::read ( $subQuestionId );
			
			$objSubAnswerTmp = new Answer ( $subQuestionId );
			$nbrSubAnswers = $objSubAnswerTmp->selectNbrAnswers ();
			$subAnswerType = $objSubQuestionTmp->selectType ();
			
			//题干
			$s .= '<tr><td>';
			$s .= '<div id="question">';
			$s .= "<div class=\"sectiontitle\">" . Display::return_icon ( "next0.gif" ) . "&nbsp;" . $ii;
			$s .= "&nbsp;" . ($sub_question_row ['ponderation'] >= 0 ? "(" . round ( $sub_question_row ['ponderation'] ) . get_lang ( 'Fen' ) . ")&nbsp;&nbsp;" : "");
			$s .= $objSubQuestionTmp->selectTitle ();
			$s .= "</div>";
			$s .= '<div id="qstnAnswer">';
			if ($subAnswerType == UNIQUE_ANSWER or $subAnswerType == TRUE_FALSE_ANSWER or $subAnswerType == MULTIPLE_ANSWER) {
				$s .= "<div id='selectQsnAnswer'><ul>";
				for($answerId = 1; $answerId <= $nbrSubAnswers; $answerId ++) {
					$subAnswer = $objSubAnswerTmp->selectAnswer ( $answerId );
					$subAnswerCorrect = $objSubAnswerTmp->isCorrect ( $answerId );
					
					if ($subAnswerType == UNIQUE_ANSWER or $subAnswerType == TRUE_FALSE_ANSWER) {
						$s .= "<li><input class='checkbox' type='radio' name='choice[" . $subQuestionId . "]' value='" . $answerId . "'><div style='margin-left:5px;display:inline'>";
						$s .= Question::$alpha [$answerId] . ". " . api_parse_tex ( $subAnswer );
						$s .= "</div></li>";
					} elseif ($subAnswerType == MULTIPLE_ANSWER) {
						$s .= "<li><input class='checkbox' type='checkbox' name='choice[" . $subQuestionId . "][" . $answerId . "]' value='1'><div style='margin-left:5px;display:inline'>";
						$s .= Question::$alpha [$answerId] . ". " . api_parse_tex ( $subAnswer );
						$s .= "</div></li>";
					}
				}
				$s .= "</ul></div>";
			}
			$s .= '</div>';
			$s .= '</div>' . "\r\n";
			$s .= '<div id="correctAnswer">' . get_lang ( "StandardAsnwer" ) . ": " . $objSubAnswerTmp->correctAnswer . '</div>';
			$s .= '</td></tr>';
			$ii ++;
		}
		$s .= '</table>';
		$s .= "</div>";
	} elseif ($answerType == CLOZE_QUESTION) {
		$s .= '<div id="question">';
		$s .= '<div class="sectiontitle">' . "&nbsp;" . get_lang ( "Question" ) . '' . ($idx);
		$s .= ":&nbsp;" . Question::get_question_type_name ( $answerType );
		$s .= ' (' . ($questionPonderation) . get_lang ( 'Fen' ) . ')';
		$s .= '</div>';
		$s .= '<div id="qstnName">' . $questionName . '</div>';
		$s .= '</div>' . "\r\n";
		
		$s .= '<table width="90%" style="float:right">';
		$s .= "<div id='subQuestion'>";
		$sql = "SELECT * FROM " . $TBL_QUESTIONS . " WHERE pid=" . Database::escape ( $questionId ) . " ORDER BY position";
		//echo $sql;
		$sub_rs = api_sql_query ( $sql, __FILE__, __LINE__ );
		$ii = 1;
		while ( $sub_question_row = Database::fetch_array ( $sub_rs ) ) {
			$subQuestionId = $sub_question_row ['id'];
			$objSubQuestionTmp = Question::read ( $subQuestionId );
			$objSubAnswerTmp = new Answer ( $subQuestionId );
			$nbrSubAnswers = $objSubAnswerTmp->selectNbrAnswers ();
			$subAnswerType = $objSubQuestionTmp->selectType ();
			
			//题干
			$s .= '<tr><td>';
			$s .= '<div id="question">';
			$s .= "<div class=\"sectiontitle\">" . Display::return_icon ( "next0.gif" ) . "&nbsp;" . $ii;
			$s .= "&nbsp;" . ($sub_question_row ['ponderation'] >= 0 ? "(" . round ( $sub_question_row ['ponderation'] ) . get_lang ( 'Fen' ) . ")&nbsp;&nbsp;" : "");
			$s .= "</div>";
			$s .= '<div id="qstnAnswer">';
			if ($subAnswerType == UNIQUE_ANSWER) {
				$s .= "<div id='selectQsnAnswer'><ul>";
				for($answerId = 1; $answerId <= $nbrSubAnswers; $answerId ++) {
					$subAnswer = $objSubAnswerTmp->selectAnswer ( $answerId );
					$subAnswerCorrect = $objSubAnswerTmp->isCorrect ( $answerId );
					
					$s .= "<li><input class='checkbox' type='radio' name='choice[" . $subQuestionId . "]' value='" . $answerId . "'><div style='margin-left:5px;display:inline'>";
					$s .= Question::$alpha [$answerId] . ". " . api_parse_tex ( $subAnswer );
					$s .= "</div></li>";
				}
				$s .= "</ul></div>";
			}
			$s .= '</div>';
			$s .= '</div>' . "\r\n";
			$s .= '<div id="correctAnswer">' . get_lang ( "StandardAsnwer" ) . ": " . $objSubAnswerTmp->correctAnswer . '</div>';
			$s .= '</td></tr>';
			$ii ++;
		}
		$s .= '</table>';
		$s .= "</div>";
	
	}
	
	return $s;
}
?>


<div id="quizTitle"><?=($exerciseTitle) . '&nbsp;&nbsp;&nbsp;&nbsp;(' . get_lang ( "QuizTotalScore" ) . ":" . Exercise::get_quiz_total_score ( $id )?>)</div>

<?php
//查询最高分,最低分,平均分
$score_rel = "select  avg(exe_result), max(exe_result), min(exe_result) from exam_track where exe_exo_id = ".$id." "; 
$dt_sc = api_sql_query_array_assoc($score_rel,__FILE__,__LINE__);

//查询排名前十的学生
$top_ten = "select exe_user_id from exam_track where exe_exo_id = ".$id." order by score desc limit 10 ";
$rel_top = api_sql_query_array($top_ten,__FILE__,__LINE__);

//查询排名后十的学生
$rank_ten = "select exe_user_id from exam_track where exe_exo_id = ".$id." order by score asc limit 10 ";
$rel_rank = api_sql_query_array($rank_ten,__FILE__,__LINE__);

//echo '<span style="float:right; padding-top:5px;">', link_button ( 'excel.gif', 'Export', $url ), '</span>';
    echo '</br>平均分：'.$dt_sc[0]['avg(exe_result)'].'分 &nbsp;&nbsp;
        最高分：'.$dt_sc[0]['max(exe_result)'].'分 &nbsp;&nbsp; 最低分：'.$dt_sc[0]['min(exe_result)'].'分</br><hr/>';
    echo '                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;排名前十： ';
    foreach($rel_top as $tp => $tv){
        $firstname = "select firstname from user where user_id = ".$tv['exe_user_id']." ";
        $dta = api_sql_query_array_assoc($firstname,__FILE__,__LINE__);
        echo $dta[0]['firstname'].',';
    }
//     $begin = Exercise::get_begin($id);
//     $end = Exercise::get_end($id);
//    foreach ( $begin as $data ) {
//        echo '</br>'.$data['firstname'];
//    }
    echo "<hr/>";
    //echo $end;
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;          排名后十：";
    foreach($rel_rank as $re => $rv){
        $firstname_rank = "select firstname from user where user_id = ".$rv['exe_user_id']." ";
        $dta_r = api_sql_query_array_assoc($firstname_rank,__FILE__,__LINE__);
        echo $dta_r[0]['firstname'].',';
    }
//    foreach ( $end as $data1 ) {
//        echo '</br>'.$data1['firstname'];
//    }
echo '<div>';
$k = 1;
$questionList = array ();
$query = "SELECT t3.question_id,t3.question_score FROM  $TBL_EXERCICE_QUESTION AS t3 WHERE t3.exercice_id=" . Database::escape ( $id ) . " ORDER BY t3.question_type, t3.question_order";
$result = api_sql_query ( $query, __FILE__, __LINE__ );
while ( $row = Database::fetch_array ( $result, "ASSOC" ) ) {
	$questionList [] = $row ['question_id'];
	echo '<div>';
	echo show_question ( $row ['question_id'], $k, $row ['question_score'] );
	echo '</div>';
	echo '<div style="clear:both"></div>';
        echo "答错人数：",get_wrong_num( $row ['question_id'],$id);
     //   echo "<div id='correctAnswer'>","答错人数:",get_wrong_num( $row ['question_id'],$id),"&nbsp;&nbsp;&nbsp;&nbsp;</div>";
        echo '<div style="clear:both"></div>'; 
	$k ++;
}
echo '</div>';

Display::display_footer ();


