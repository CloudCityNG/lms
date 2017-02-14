<?php
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']);
/************************
 * 查看考生答卷
 ************************/
//ALTER TABLE  `exam_attempt` CHANGE  `marks`  `marks` DOUBLE NULL DEFAULT  '0' COMMENT  '得分'
include_once ('../exercice/exercise.class.php');
include_once ('../exercice/question.class.php');
include_once ('../exercice/answer.class.php');
$language_file = array ('exam', 'exercice', 'admin' );
include_once ('../inc/global.inc.php');
api_block_anonymous_users ();
include_once api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php';
require_once (api_get_path ( SYS_CODE_PATH ) . "exercice/exercise.lib.php");

//var_dump($_POST);
$exerciseId = (isset ( $_REQUEST ['exam_id'] ) ? floatval(getgpc ( 'exam_id' )) : "");
$result_id = (isset ( $_REQUEST ['result_id'] ) ? floatval(getgpc ( 'result_id' )) : "");
$user_id = api_get_user_id ();
$uid = (float)$_REQUEST['user_id'];
$up_fraction = htmlspecialchars($_REQUEST['up_fraction']);
$fraction = floatval($up_fraction);
$question = floatval($_REQUEST['qustion']);

$amp = (float)$_REQUEST['amp'];
if($up_fraction === '0' || (!empty($fraction) && !empty($amp))){
    settype($_POST['weighting'],'float');
    settype($_POST['this_score'],'float');
    $this_score = $_POST['this_score'];
    $weighting = $_POST['weighting'];
    $up_sql = 'select marks from exam_attempt where id='.$amp.' and user_id='.$uid.' and file is not null  limit 1';
    $attem_marks = mysql_fetch_row(mysql_query($up_sql));
    $attem_marks_key = $attem_marks[0];
    $result_fra = 1;
    $question_query = mysql_query('select is_k,key_score from exam_question where id='.$question);
    $question_score_arr = mysql_fetch_assoc($question_query);
    if(count($question_score_arr)){
        if($question_score_arr['is_k']) {
            $question_score_v_score = unserialize($question_score_arr['key_score']);
            $score_count = array_sum(array_values($question_score_v_score));
        }
    }

    $diff_score = $weighting - $score_count;
    if($fraction <= $diff_score){
        if($fraction > $diff_score){
            $fraction = $diff_score;
        }
        if($fraction <= 0){
            $fraction = 0;
        }
        $result_fra = $fraction + $this_score - $attem_marks_key;

        $exam_track_row = mysql_fetch_row(mysql_query('select score,exe_result,exe_weighting from exam_track where exe_id='.$result_id.' limit 1'));
        $exe_result_fra = $exam_track_row[1] + $result_fra;
        $exe_result_fra = $exe_result_fra > $exam_track_row[2] ? $exam_track_row[2] : $exe_result_fra;
        $exe_result_fra = $exe_result_fra < 0 ? 0 : $exe_result_fra;
        $score_fra = floatval($exe_result_fra / $exam_track_row[2] *100);
        $track_up_sql = 'update exam_track set score='.$score_fra.',exe_result='.$exe_result_fra.' where exe_id='.$result_id;
        mysql_query($track_up_sql);
        $in_up_sql = 'update exam_attempt set marks='.($fraction+$this_score).' where id='.$amp;
        mysql_query($in_up_sql);
    }
}
$objExercise = new Exercise ();
$objExercise->read ( $exerciseId );
$results_id=Database::escape ( $result_id );
$sql = "SELECT *  FROM $tbl_exam_result WHERE exe_id=".$results_id;
$exam_result = Database::fetch_one_row ( $sql, FALSE, __FILE__, __LINE__ );
//var_dump($exam_result);
$sql_att= mysql_query("SELECT *  FROM $tbl_exam_attempt WHERE exe_id=".$results_id.' and user_id='.$uid);

while($attr_row=mysql_fetch_row($sql_att)){
    $attr_rows[$attr_row[3]]=$attr_row;
}

$is_allowed = FALSE;
if ($objExercise->feedbacktype == 0 and $exam_result ['exe_user_id'] == $user_id && $exam_result ['exe_exo_id'] == $exerciseId) $is_allowed = TRUE;
if ($user_id == $objExercise->exam_manager) $is_allowed = TRUE;
if (api_is_platform_admin ()) $is_allowed = TRUE;
if (! $is_allowed) api_not_allowed ();

$exerciseResult = unserialize ( $exam_result ['data_tracking'] );

$exerciseTitle = $objExercise->selectTitle (); //测验名称
$test_duration = $objExercise->selectDuration ();
if ($objExercise->feedbacktype == 0) $isAllowedToSeeAnswer = TRUE;
if ($objExercise->feedbacktype == 2) $isAllowedToSeeAnswer = FALSE;
if (api_is_platform_admin () or $user_id == $objExercise->exam_manager) $isAllowedToSeeAnswer = TRUE;
if ($objExercise->results_disabled == 0) $isAllowedToSeePaper = TRUE;
if ($objExercise->results_disabled == 1) $isAllowedToSeePaper = FALSE;
if (api_is_platform_admin () or $user_id == $objExercise->exam_manager) $isAllowedToSeePaper = TRUE;

$exam_user = UserManager::get_user_info_by_id ( $exam_result ['exe_user_id'], TRUE );

$quiz_question_type = $objExercise->getQuizQuestionTypes ( $exerciseId );
$quiz_qt = array_keys ( $quiz_question_type );
$questionListArr = $objExercise->getAllQuestionsByType ();

foreach ( $quiz_question_type as $qtype => $qcount ) {
	$total_question_cnt += $qcount [0];
}

$test_duration = $objExercise->timeLimit;
$examAttempts = $objExercise->attempts;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$exerciseTitle?></title>
<link href="../../portal/sp/index.css" rel="stylesheet" type="text/css" />
<link href="../../portal/sp/ext.css" rel="stylesheet" type="text/css" />
<?php
echo import_assets ( "commons.js" );
echo import_assets ( "jquery-latest.js" );
echo import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
echo import_assets ( "jquery-plugins/jquery.wtooltip.js" );
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
<script type="text/javascript">
    $(document).ready( function() {
		$("body").addClass("yui-skin-sam");
		$(".yui-content > div").fadeOut("fast").eq(0).fadeIn("normal");
		$("#tab li").eq(0).addClass("selected");
		
		$("#tab li").click(function(){ 
			$(this).addClass("selected").siblings().removeClass("selected");
			var cur_idx=$("#tab li").index(this);
			$(".yui-content > div").eq(cur_idx).addClass("selected").siblings().removeClass("selected");
			$(".yui-content > div").fadeOut("normal").eq(cur_idx).fadeIn("normal");
		});
});
</script>
</head>

<body>

<div class="register_body">
<div class="emax_content">

<div class="register_title dc2" style="text-align: center;"><strong><?=$exerciseTitle?></strong></div>
<div class="emax_title de1"><span style="margin-right: 30px;">考生：<?=$exam_user ["firstname"]?>(<?=$exam_user ["username"] . ', ' . $exam_user ['dept_path']?>)</span><span
	style="margin-right: 30px;">试题总数:<?=$total_question_cnt?></span><span
	style="margin-right: 30px;">答题时间：<?=($test_duration > 0 ? ($test_duration / 60) . "分钟" : "不限制");?></span></div>

<?php
//不显示答案
if (! $isAllowedToSeeAnswer) ob_start ();
?>

<div id="tab" class="yui-navset" style="margin-top: 1px"><!-- Tab显示大题题型 -->
<ul class="yui-nav">
	<?php
	if ($quiz_qt && is_array ( $quiz_qt ) && count ( $quiz_qt ) > 1) {
		$idx = 0;
		foreach ( $quiz_question_type as $qtype => $qcount ) {
			echo '<li id="qt_' . ($idx ++) . '"><a href="#"><em>' . $_question_types [$qtype] . "(" . $qcount [0] . ')</em></a></li>';
		}
	}

	?>
</ul>
<!-- Tab显示大题题型 -->

<div class="yui-content" id="slider">
<?php
$totalScoreKgt = $totalScoreZgt = 0;
foreach ( $quiz_question_type as $qtype => $qcount ) {
	$questionListByType = $questionListArr [$qtype];
?>
<div id="tab_<?=$qtype?>">
<div class="exam_block_screen">
<div class="register_hint dd2" style="width: auto; margin: 10px 0 15px;"><?=$_question_types [$qtype]?>
    (本题型共<?=count ( $questionListByType )?>题，共<?=$qcount[1]?>分)</div>
<?php
    $sub_score = false;
    if ($questionListByType && count ( $questionListByType ) > 0) {
        $counter = 0;
        foreach ( $questionListByType as $questionId => $questionItem ) {
            $counter ++;
            $questionWeighting = null;
            $choice = $attr_rows[$questionId][5]; //$choice保存的为当前题目学生提交的答案（多选为数组）,key为question.id;value为答案值,多选,填空则为数组,其它为单个值
            $questionName = $questionItem ['question'];
            $contents = $questionItem ['contents'];
            $questionComment = $questionItem ['comment'];
            $answerType = $questionItem ['type'];
            $questionWeighting = $questionItem ['question_score'];
            $isQuestionCorrect = FALSE;
            if($answerType == COMBAT_QUESTION && $questionItem['is_k'] === '1'){
                $key_score=unserialize($questionItem['key_score']);
                $score_count=0;
                foreach($key_score as $key_score_k => $key_score_v){
                  $score_count+=floatval($key_score_v);
                }
            }
//显示答案
            $objAnswerTmp = new Answer ( $questionId );
            $nbrAnswers = $objAnswerTmp->selectNbrAnswers ();
            $questionScore = 0;
            ?>
            <div class="exam_problem dd7"
                 style="border-bottom: #c3c3c3 0px dashed">
                <div
                        style="height: auto; border-right: 0px dashed #c3c3c3; width: 750px; padding: 10px 0;">
                    <div>
                        <?=$counter . "、" . $questionName;

                        if($answerType==COMBAT_QUESTION or $answerType==FREE_ANSWER){
                            echo "<br/>";
                            echo $contents;
                        }

                        ?>
                        (<?=$questionWeighting?> 分)
                    </div>


<?php
			if (in_array ( $answerType, array (UNIQUE_ANSWER, MULTIPLE_ANSWER, TRUE_FALSE_ANSWER ) )) {
				echo '<table style="text-align: center; margin-top: 10px;" cellspacing="0"><tr>
		<td width="70">考生答案</td>
		<td width="70" style="color: #A0001B">正确答案</td>
		<td width="500">选项</td></tr>';
                $arr_key=array ('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
                $choicess=array_search($choice,$arr_key);

                switch ($answerType) {
					case TRUE_FALSE_ANSWER :
						$isQuestionCorrect = TrueFalseAnswer::is_correct ( $questionId, $choice );
                        break;
					case UNIQUE_ANSWER :
						$isQuestionCorrect = UniqueAnswer::is_correct ( $questionId, $choicess );
						break;
					case MULTIPLE_ANSWER :
                        $arr_key2 = array ('','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
                        $exam_choice = explode('|',$choice);
                        foreach($exam_choice as $exam_cho_k => $exam_cho_v){
                            $choicess = array_search($exam_cho_v,$arr_key2);
                            $exam_choice[$exam_cho_k] = $choicess;
                        }
						$isQuestionCorrect = MultipleAnswer::is_correct ( $questionId, $exam_choice );
						break;
				}
			}
			
			if (in_array ( $answerType, array (UNIQUE_ANSWER, MULTIPLE_ANSWER, TRUE_FALSE_ANSWER ) )) {
				if ($isQuestionCorrect) {
					$questionScore = $questionWeighting;
					$totalScore += $questionScore;
					$totalScoreKgt += $questionScore;
				} else {
					$questionScore = 0;
				}
				
				for($answerId = 1; $answerId <= $nbrAnswers; $answerId ++) {
					$answer = $objAnswerTmp->selectAnswer ( $answerId );
					$answerCorrect = $objAnswerTmp->isCorrect ( $answerId );
					$answerWeighting = $objAnswerTmp->selectWeighting ( $answerId );
					switch ($answerType) {
						case TRUE_FALSE_ANSWER :
                            $studentChoice =($choice == $answerId) ? 1 : 0;
                            break;
						case UNIQUE_ANSWER :
							$studentChoice =($choice == Question::$alpha [$answerId]) ? 1 : 0;
							break;
						case MULTIPLE_ANSWER :
                            $studentChoice=in_array($answerId,$exam_choice);
							break;
					}
					display_unique_or_multiple_answer ( $answerType, $studentChoice, $answer, $answerCorrect );
				}
				echo '</table>';
			} elseif ($answerType == FILL_IN_BLANKS) {
				$answer = $objAnswerTmp->selectAnswer ( 1 );
				$rtn_data = display_fill_blank_answer ( $choice, $answer, $questionWeighting, $isAllowedToSeeAnswer, $isAllowedToSeePaper );
				$questionScore = $rtn_data ['score'];
				$totalScore += $questionScore;
				echo $rtn_data ['html'];
			} elseif ($answerType == FREE_ANSWER) {
				$html = '<div style="height: 9px; overflow: hidden;"></div>';
				$html .= '<div>' . ($isAllowedToSeePaper ? $choice : "");
				$html .= '<div class="clearall"></div><div style="float:left"><span style="font-weight:bold;color:red;font-size:14px">' . get_lang ( "QuestionStdAnswer" ) . '</span>:&nbsp;<br/>' . nl2br ( FreeAnswer::get_correct_answer_str ( $questionId ) ) . '</div>';
				$html .= '<div class="clearall"></div></div>';
				$questionScore = FreeAnswer::get_score ( $result_id, $questionId, $user_id );
				$teacher_comment = FreeAnswer::get_teacher_comment ( $result_id, $questionId, $user_id );
				$totalScore += $questionScore;
				$totalScoreZgt += $questionScore;
				echo $html;
			}
			?>
</div>

<div
	style="float: left; width: 160px; text-align: center; margin-top: 10px; margin-left: 10px">
<?php
			if ($isAllowedToSeePaper) {
				if ($answerType == FREE_ANSWER || $answerType == UNIQUE_ANSWER || $answerType == MULTIPLE_ANSWER || $answerType == TRUE_FALSE_ANSWER) {
?>
                        <div style="float: right"><br />
                           <strong>此题<?=($questionWeighting)?>分,得<?=$questionScore?>分</strong>
                        </div>
<?php
                      if ($teacher_comment) {
?>
                            <br/>阅卷评语:<br/>
                            <div style="border: 1px solid #666; padding: 4px"><?=$teacher_comment?></div>
<?php
                      }
			   } else if($answerType == COMBAT_QUESTION){
                        if($questionItem['is_k'] === '1'){
                               $this_score=0;
                               $choice = $exerciseResult [$questionId];
                               $this_key_arr = $choice;
                               $keyss_arr = unserialize($questionItem['keyss']);

                               if(count($this_key_arr)){
                                  foreach($keyss_arr as $key_arr_k=>$key_arr_v){
                                     if($key_arr_v === $this_key_arr[$key_arr_k]){
                                        $this_score+=$key_score[$key_arr_k];
                                     }
                                  }
                               }

?>
                                  <div style="float: right">
                                     <img src="../../portal/sp/images/<?= ($questionWeighting == $attr_rows[$questionId][4] ? "correct.gif" : "cross.gif") ?>"/><br/>
                                     <strong>此题<?= ($questionWeighting) ?>分,得<?= $attr_rows[$questionId][4]?>分</strong>
                                  </div>
<?php
                       }

                }else{
?>
			<div style="float: right"><img src="../../portal/sp/images/<?=($questionScore == $questionWeighting ? "correct.gif" : "cross.gif")?>" /><br/>
              <strong>此题<?=($questionWeighting)?>分,得<?=$questionScore?>分</strong>
            </div>
<?php
			}
			}
?>
</div>
</div>
<div class="clearall"></div>
            <?php
            if(!empty($attr_rows[$questionId][9])){
                if(file_exists(APP_ROOT_PATH.$attr_rows[$questionId][9])) {
                    $sub_score = true;
                    $upload_name = end(explode('/', $attr_rows[$questionId][9]));
                    echo '<div>提交的报告&nbsp;:&nbsp;<span>'.$upload_name.'</span><a href="http://' . $_SERVER['HTTP_HOST'] . $attr_rows[$questionId][9] . '" style="text-decoration:none;">&nbsp;&nbsp;&nbsp;<input type="button" class="btn-style-01" value="下载" /></a></div>';
                    echo '<div>分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;数&nbsp;:&nbsp;
                               <form style="display: inline;" action="exam_view.php" method="POST">
                                  <input type="text" name="up_fraction" value="" style="width:20px;"/>
                                  <input type="hidden" name="result_id" value="'.$result_id.'" />
                                  <input type="hidden" name="exam_id" value="'.$exerciseId.'" />
                                  <input type="hidden" name="user_id" value="'.$uid.'" />
                                  <input type="hidden" name="qustion" value="'.$questionItem['id'].'" />
                                  <input type="hidden" name="this_score" value="'.$this_score.'" />
                                  <input type="hidden" name="weighting" value="'.$questionWeighting.'" />
                                  <input type="hidden" name="amp" value="'.$attr_rows[$questionId][0].'"/>
                                  <input type="submit" value="确定"/>
                               </form>
                          </div>';
                }
            }
            ?>
<?php if($answerType == COMBAT_QUESTION && $questionItem['is_k'] === '1')
      {?>
     <div class="analyze">
         <span style="font-size: 14px; color: #A0001B">key值：</span>
         <br/>
         <span class="dd2">答&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;案&nbsp;:&nbsp;
             <?php
               foreach($keyss_arr as $keyss_arr_k=>$keyss_arr_v){
                  echo 'key'.$keyss_arr_k.'&nbsp;: ( '.$keyss_arr_v.' )&nbsp;&nbsp;&nbsp;';
               }
             ?>
         </span>
         <br/>
         <span class="dd2">考生答案&nbsp;:&nbsp;
             <?php
             foreach($this_key_arr as $this_key_arr_k=>$this_key_arr_v){
                 echo 'key'.$this_key_arr_k.'&nbsp;: ( '.$this_key_arr_v.' )&nbsp;&nbsp;&nbsp;';
             }
             ?>
         </span>
         <div class="clearall"></div>
     </div>
<?php }
	unset ( $objAnswerTmp );
		}
	}
?>
	</div>
</div>
	<?php
}
?>
</div>.
</div>
<?php
//成绩显示
$score = $exam_result['exe_weighting'] > 0 ? (round ( round ($exam_result['exe_result']) / round($exam_result['exe_weighting']) * 100 )) : 0; //百分比成绩
$exercise_result = $exam_result['exe_result'] .'&nbsp;&nbsp;试卷面总分:' . $exam_result['exe_weighting'] . " - 百分制成绩为: <span style='font-weight:bold;color:red;font-size:18px'>" . $score . '</span>';

if (! $isAllowedToSeeAnswer) { //不显示答案
	ob_end_clean ();
}
$result_msg = ($isAllowedToSeePaper ? get_lang ( 'YourTotalScore' ) . $exercise_result : "");
?>
<div class="register_hint dd2" style="width: auto; margin: 10px 0 15px;"><?=$result_msg?></div>
<div class="clearall"></div>
</div>
<div class="clearall"></div>
</div>
</body>
</html>
