<?php
header('Content-type:text/html;charset=utf-8');
include_once ('../../main/exercice/exercise.class.php');
include_once ('../../main/exercice/question.class.php');
include_once ('../../main/exercice/answer.class.php');
include_once ("inc/app.inc.php");
include_once (api_get_path ( SYS_CODE_PATH ) . 'exercice/exercise.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'text.lib.php');
include (api_get_path ( LIBRARY_PATH ) . 'mail.lib.inc.php');

$api_url = '';//考试答案接口路径

if (api_get_setting ( 'enable_modules', 'exam_center' ) != 'true') {
	api_redirect ( 'learning_center.php' );
}
$type = intval( $_GET['type'] );
$user_id = api_get_user_id ();
$username =  Database::getval("select `firstname` from user where user_id=".$user_id,__FILE__,__LINE__);
$name =  $_SESSION['_user']['username'];
$formSent = $_POST['formSent'];
$exerciseId =$_POST['exerciseId']; 
$choices = $_POST['choice']; //提交的答案
if($_POST['keyss']) {
    $keyss = serialize($_POST['keyss']);
}
$filey = $_FILES['choice'];
$fname = $filey["name"];//考题号=》上传文件的名字
$tpname = $filey["tmp_name"];
function get_extension($fname)
{
   return substr(strrchr($fname, '.'), 1);
}
foreach ($fname as $k=>$v)
{
      $c =  get_extension($v);
      $extension = array("doc","docx");
      if(! in_array($c,$extension,true))
      {
          echo '<script type="text/javascript">alert("报告,请上传word文档！！");history.back();</script>';exit();
      }
}        

//创建文件保存的地址$url2
     $url2= URL_ROOT.'/www'.URL_APPEDND."/storage/exam/".$name."/".$exerciseId;
if(!file_exists($url2)){
     exec("mkdir -p $url2");
}

$objExercise = new Exercise ();
$objExercise->read ( $exerciseId );

//处理提交的测验表单
if ($formSent) {
	$exerciseResult = array ();
	if (is_array ( $choices )) $exerciseResult = $choices;
}

 array_push($exerciseResult, $fname);
 array_push($exerciseResult, $keyss);
$data_tracking = serialize ( $exerciseResult );//序列化用户提交的答案
$exeId = Exercise::get_quiz_track_id ( $exerciseId );//exam_track的id

if ($exerciseResult) { //先保存用户提交的答案,防止丢失
     Exercise::update_event_exercice ( $exeId, $objExercise->id, $user_id, 0, 0, $keyss);
}

$questionList = $questionList = $objExercise->selectQuestionList ();

$exerciseTitle = $objExercise->selectTitle (); //测验名称
$test_duration = $objExercise->selectDuration ();
if ($objExercise->feedbacktype == 0) $isAllowedToSeeAnswer = TRUE;
if ($objExercise->feedbacktype == 2) $isAllowedToSeeAnswer = FALSE;
if ($objExercise->results_disabled == 0) $isAllowedToSeePaper = TRUE;
if ($objExercise->results_disabled == 1) $isAllowedToSeePaper = FALSE;
$courseName = $_SESSION ['_course'] ['name'];

$quiz_question_type = $objExercise->getQuizQuestionTypes ( $exerciseId );

$quiz_qt = array_keys ( $quiz_question_type );
                
$questionListArr = $objExercise->getAllQuestionsByType ();
                
foreach ( $quiz_question_type as $qtype => $qcount ) {
	$total_question_cnt += $qcount [0];
}
$i = $totalScore = $totalWeighting = 0;
if ($objExercise->results_disabled) {
	ob_start ();
}

$totalScoreKgt = $totalScoreZgt = 0;
$containZgt = false;

foreach ( $quiz_question_type as $qtype => $qcount )
{
	$questionListByType = $questionListArr [$qtype];
	if ($questionListByType && count ( $questionListByType ) > 0)
    {
		$counter = 0;
		foreach ( $questionListByType as $questionId => $questionItem ) {
			$counter ++;
			$choice = $exerciseResult [$questionId]; //$choice保存的为当前题目学生提交的答案（多选为数组）,key为question.id;value为答案值,多选,填空则为数组,其它为单个值
            $questionName = $questionItem ['question'];
			//$questionComment = $questionItem ['comment'];
			$answerType = $questionItem ['type'];
			$questionWeighting = $questionItem ['question_score'];
			$isQuestionCorrect = FALSE;
            //key值验证
			if($answerType == COMBAT_QUESTION){
                $score_exam=  Database::getval("SELECT ponderation FROM  `exam_rel_question` AS t1,`exam_question` AS t2 WHERE t1.question_id = t2.`id` AND t1.`exercice_id` =  ".$exerciseId);
                $key_score_query=mysql_query("SELECT t1.question_id,key_score FROM  `exam_rel_question` AS t1,`exam_question` AS t2 WHERE t1.question_id = t2.`id`  AND t1.`exercice_id` = ".$exerciseId);
               while($key_score_row=mysql_fetch_assoc($key_score_query)){
                     $key_score_rows[$key_score_row['question_id']]=$key_score_row['key_score'];
               }
                $keyss_query =mysql_query("SELECT t1.question_id,keyss FROM  `exam_rel_question` AS t1,`exam_question` AS t2 WHERE t1.question_id = t2.`id`  AND t1.`exercice_id` = ".$exerciseId);
               while($keyss_row=mysql_fetch_assoc($keyss_query)){
                 $keyss_rows[$keyss_row['question_id']]=$keyss_row['keyss'];
               }
                $arr_key_exam=  unserialize($keyss_rows[$questionId]);
                $key_scores=  unserialize($key_score_rows[$questionId]);
                 $key_input=  unserialize($keyss);
                $key_inp=$key_input[$questionId];
                $count_key=count($key_inp);
                $key_total = 0;
                $scores_all = 0;
                 for($i=0;$i<$count_key;$i++){
                     if($arr_key_exam[$i]==$key_inp[$i]){
                           $key_total +=$key_scores[$i];
                     }
                     $scores_all+=$key_scores[$i];
                 }
            }
			//显示答案
			$objAnswerTmp = new Answer ( $questionId );
			$nbrAnswers = $objAnswerTmp->selectNbrAnswers ();
			$questionScore = 0;

	if (in_array ( $answerType, array (UNIQUE_ANSWER, MULTIPLE_ANSWER, TRUE_FALSE_ANSWER ) )) {
				switch ($answerType) {
					case TRUE_FALSE_ANSWER :
						$isQuestionCorrect = TrueFalseAnswer::is_correct ( $questionId, $choice );
                        break;
					case UNIQUE_ANSWER :
						$isQuestionCorrect = UniqueAnswer::is_correct ( $questionId, $choice );
						break;
					case MULTIPLE_ANSWER :
						$isQuestionCorrect = MultipleAnswer::is_correct ( $questionId, $choice );
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
							$studentChoice = ($choice == $answerId) ? 1 : 0;
							break;
						
						case MULTIPLE_ANSWER :
							$studentChoice = $choice [$answerId];
							break;
					}
				}
	} elseif ($answerType == FILL_IN_BLANKS) {
				$answer = $objAnswerTmp->selectAnswer ( 1 );
				$rtn_data = display_fill_blank_answer ( $choice, $answer, $questionWeighting, $isAllowedToSeeAnswer, $isAllowedToSeePaper );
				$questionScore = $rtn_data ['score'];
				$totalScore += $questionScore;
	}

	if ($answerType == FREE_ANSWER) {
				$containZgt = true;
	}
            unset ( $objAnswerTmp );
			$totalWeighting += $questionWeighting;
			//以下大段主要功能: 将答题结果及成绩保存
               $studentSubAnswer = '';     //声名保存答案变量
				if (is_string ( $choice )) {
					if ($answerType == UNIQUE_ANSWER){
						$studentSubAnswer = Question::$alpha [$choice];
                    }else {
						$studentSubAnswer = $choice;
					}
				}
				if (is_array ( $choice )) {
					if ($answerType == MULTIPLE_ANSWER) {
                        $ans = array();
						foreach ( $choice as $c ) {
							$ans [] = Question::$alpha [$c];
						}
						$studentSubAnswer = implode ( "|", $ans );
					} else {
						$studentSubAnswer = implode ( "|", $choice );
					}
				}
            //实战题
			if ($answerType == COMBAT_QUESTION){

                   if($key_total)
                   {
                         $questionScore = $key_total;
				         $totalScore += $questionScore;
				         $totalScoreKgt += $questionScore;
                   }

					$studentSubAnswer = $choice;
                    $furl = $fname[$questionId];
                    $src1 = $tpname[$questionId];
             
                    if($furl && $src1) {
                        if ($src1 != "") {
                            $file = URL_ROOT . '/www' . URL_APPEDND . "/storage/exam/" . $_SESSION['_user']['username'] . "/" . $exerciseId . "/" . $furl;
                            $file2 = URL_APPEDND."/storage/exam/".$_SESSION['_user']['username']."/".$exerciseId."/".$furl;
                            exec("chmod -R 777 " . $file);
                        } else {
                            $file = "";
                        }
                                        
                        move_uploaded_file($src1, $file);
                    }else{
                        $file2 = "";
                    }
                    $src1="";
            }
				
            if ($answerType == FREE_ANSWER)
            {
                   $answer_free_query = mysql_query('select answer from exam_question where id='.$questionId);
                   $answer_free_row=mysql_fetch_row($answer_free_query);
                   if($answer_free_row[0] == $choice){
                       $questionScore = $questionWeighting;
					   $totalScore += $questionScore;
					   $totalScoreKgt += $questionScore;
                   }
				   $studentSubAnswer = $choice;
                   $src1=$tpname[$questionId];
                   $furl = $fname[$questionId];
                   if($src1!="")
                   {
                      $file=URL_ROOT.'/www'.URL_APPEDND."/storage/exam/".$_SESSION['_user']['username']."/".$exerciseId."/".$furl;
                      $file2 = URL_APPEDND."/storage/exam/".$_SESSION['_user']['username']."/".$exerciseId."/".$furl;
                      exec("chmod -R 777 ".$file);
                      move_uploaded_file($src1,$file);
                   }else{
                      $file = "";
                      $file2 = "";
                   }
                      $src1="";
            }
			    $file=(!isset($file)) ? '' : $file;

                if($exeId)
                {
                    $true_str = Exercise::exercise_attempt ( $questionScore, $studentSubAnswer, $questionId, $exeId, $i ,$file2);
                }
                    unset($file);
	        }
    }

}
//成绩显示

$score = $totalWeighting > 0 ? (round ( round ( $totalScore ) / $totalWeighting * 100 )) : 0; //百分比成绩
/*
 ==============================================================================
 主要功能:  记录提交答案及结果  保存到 exam_track表
 ==============================================================================
 */

if ($_configuration ['tracking_enabled']) 
{
	Exercise::update_event_exercice ( $exeId, $objExercise->id, api_get_user_id (), $totalScore, $totalWeighting, $keyss);
}

    //成绩提交接口
    if(api_get_setting ( 'lm_switch' ) == 'true' && api_get_setting ( 'lm_nmg' ) == 'true')
    {
        $result_new_id = api_sql_query_array("select max(exe_id) from exam_track");
        $track_papers = "select exe_exo_id, exe_user_id, title, exe_weighting from exam_track left join exam_main on exam_track.exe_exo_id = exam_main.id where exe_id = '".$result_new_id[0][0]."' ";
        $sub_papers = api_sql_query_array($track_papers,__FILE__,__LINE__);  //试卷内容，用户id，试卷id，试卷名称

        $empts = "select question_id, marks, type, question from exam_attempt as pt, exam_question as qu where pt.question_id = qu.id and exe_id ='".$result_new_id[0][0]."' ";
        $ret_ep = api_sql_query_array_assoc($empts,__FILE__,__LINE__);//题目id，题目名称，题目类型

        $total_score = "select sum(question_score) from exam_rel_question where exercice_id = ".$sub_papers[0]['exe_exo_id']."";
        $result_dats = Database::getval($total_score,__FILE__,__LINE__);//总题的分数

        $total_question = "select count(question_id) from exam_rel_question where exercice_id = '".$sub_papers[0]['exe_exo_id']."' ";
        $num = Database::getval($total_question,__FILE__,__LINE__);//总题数
        $u_id = api_get_user_name ();
        //ini_set('soap.wsdl_cache_enabled',0);
        //ini_set('soap.wsdl_cache_ttl',0);

        //$soap_client = new SoapClient( WSDL_URL );

        foreach($ret_ep as $key => $val)
        {
            $data = array(
                'type' => 3,
                'user_id' => $u_id,
                'examination' => $sub_papers[0]['exe_exo_id'],
                'exam_name' => $sub_papers[0]['title'],
                'question_id' => $val['question_id'],
                'question_name' => $val['question'],
                'question_type' => $val['type'],
                'mark' => $val['marks'],
                'sum_fraction' => $result_dats,
                'sum_question' => $num,
            );
            //$uri = "http://localhost/lms/portal/sp/post.php";
            $uri = "http://10.217.209.81:8080/ISMC/Ismc_kl_mocha/rss/sendResults.do";
            $data_str = json_encode($data);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $post_data = array(
                "json" => $data_str,
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $data_sours = curl_exec($ch);
            $data_result = json_decode($data_sours, true);
            $return_arr=json_decode($data_result,true);
            $return=$return_arr['Return'];
            if($return!="1(Success)"){
                     $stat_table = Database::get_statistic_table ( TABLE_STATISTIC_TRACK_E_EXERCICES );
                     $exercice_attemp_table = Database::get_statistic_table ( TABLE_STATISTIC_TRACK_E_ATTEMPT );
                     $sql = "DELETE FROM " . $exercice_attemp_table . " WHERE exe_id=" . Database::escape ( $exe_id );
                     api_sql_query ( $sql, __FILE__, __LINE__ );
                     $sql = "DELETE FROM " . $stat_table . " WHERE exe_id=" . Database::escape ( $exe_id );
                     api_sql_query ( $sql, __FILE__, __LINE__ );
                    echo "数据同步失败，请重新提交或联系系统管理员";
                    exit;
	}
        }
    }
header ( "Location: ./exam_center.php?type=".$type );exit;