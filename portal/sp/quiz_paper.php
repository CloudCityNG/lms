<?php
include_once ('../../main/exercice/exercise.class.php');
include_once ('../../main/exercice/question.class.php');
include_once ('../../main/exercice/answer.class.php');

$language_file [] = 'exercice';
include_once ("inc/app.inc.php");
include_once ('../../main/exercice/exercise.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'text.lib.php');

if (api_get_setting ( 'enable_modules', 'exam_center' ) != 'true') {
    api_redirect ( 'learning_center.php' );
}
$user_id = api_get_user_id ();
$debug = 10;
$is_allowedToEdit = api_is_allowed_to_edit (); //liyu
$table_quiz_test = Database::get_main_table ( TABLE_QUIZ_TEST );
$typeId = $_GET['type'];
$exerciseId = (int)getgpc ( 'exerciseId' );
$exe_start_date = time ();
$_SESSION ['exercice_start_date'] = $exe_start_date;

/***********************************
 * 以下功能为显示试卷
 ***********************************/
$objExercise = new Exercise ();
$available_time = $objExercise->get_exam_time ( $user_id, $exerciseId, $objExercise );
$exerciseStartTime = $available_time ['start_date'];
$exerciseEndTime = $available_time ['end_date'];
//测验不存在或不可用时
$result_access_check = Exercise::do_exam_available ( $exerciseId, $user_id ,$exerciseStartTime,$exerciseEndTime); // echo $result_access_check;
$redirect_url = api_get_path ( WEB_PORTAL_PATH ) . 'exam_center.php';
//echo $redirect_url ;
if ($result_access_check != SUCCESS) {
    unset ( $objExercise );
    switch ($result_access_check) {
        case 101 : //测验不存在
//             echo '<script>alert("测验不存在")</script>';
            header("location:".$_SERVER['HTTP_REFERER']."&noction=101");
            //Display::display_msgbox ( get_lang ( 'ErrorExamNotFound' ), $redirect_url, 'warning' );
            break;
        case 102 : //测验不可用
             header("location:".$_SERVER['HTTP_REFERER']."&noction=102");
            //Display::display_msgbox ( get_lang ( 'ErrorExamNotAvailable' ), $redirect_url, 'warning' );
            break;
        case 103 : //不是考试考生
            header("location:".$_SERVER['HTTP_REFERER']."&noction=103");
            //Display::display_msgbox ( get_lang ( 'ErrorExamUserNotExists' ), $redirect_url, 'warning' );
            break;
        case 104 : //考试时间不允许,可参加考试时间段限制
            header("location:".$_SERVER['HTTP_REFERER']."&noction=104");
            //Display::display_msgbox ( get_lang ( 'ErrorExamTimeNotAllowed' ), $redirect_url, 'warning' );
            break;
        case 105 : //超过最大允许考试次数,考试次数限制: 0:不限制
//            echo '<script>alert("超过最大允许考试次数")</script>';
//            header("location:http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
            header("location:".$_SERVER['HTTP_REFERER']."&noction=105");
            //Display::display_msgbox ( get_lang ( 'ErrorReachedMaxAttempts' ), $redirect_url, 'warning' );
            break;
        case 106 : //已通过
           header("location:".$_SERVER['HTTP_REFERER']."&noction=106");
            //Display::display_msgbox ( get_lang ( 'ErrorPassTheExamNotAllowed' ), 'exam_center.php', 'warning' );
            break;
        default :
            header("location:".$_SERVER['HTTP_REFERER']."&noction=default");
            //Display::display_msgbox ( get_lang ( 'NotAllowedHere' ), $redirect_url, 'error' );
    }
}

// 保存到session当中
$objExercise->read ( $exerciseId );

if (is_null ( $objExercise ) or ! is_object ( $objExercise )) {
    api_redirect ( "course_home.php?" . api_get_cidreq () );
}

//var_dump ( $objExercise );
$exerciseTitle = $objExercise->selectTitle ();
$exerciseDescription = $objExercise->selectDescription ();
$exerciseDescription = stripslashes ( $exerciseDescription );
$exerciseSound = $objExercise->selectSound ();
//$randomQuestions = $objExercise->isRandom ();
$exerciseType = $objExercise->selectType ();
$quizID = $objExercise->selectId ();
$exerciseAttempts = $objExercise->selectAttempts ();
$exerciseActive = $objExercise->active;
$exerciseStartTime = $objExercise->selectStartTime ();
$exerciseEndTime = $objExercise->selectEndTime ();
$my_exe_id = Security::remove_XSS ( $exerciseId );

//得到试题列表

$questionList = $objExercise->selectQuestionList ();

$quizStartTime = time ();
api_session_register ( 'quizStartTime' );
$nbrQuestions = sizeof ( $questionList );

$last_track_info = $objExercise->get_incomplete_attempt ( $user_id );
$track_id = $last_track_info ['exe_id'];

$htmlHeadXtra [] = import_assets ( "jquery.easing.1.2.js", "js" );
$htmlHeadXtra [] = import_assets ( "jquery-plugins/scrolltopcontrol.js" );

$htmlHeadXtra [] = import_assets ( "exam/disable.js" );
$htmlHeadXtra [] = import_assets ( "courseware/swfobject.js", api_get_path ( WEB_CODE_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">

swfobject.registerObject("player","9.0.98","' . api_get_path ( WEB_CODE_PATH ) . 'courseware/expressInstall.swf");
</script>';
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {
		$("body").addClass("yui-skin-sam");
		$(".yui-content > div").fadeOut("fast").eq(0).fadeIn("normal");
		$("#tab li").eq(0).addClass("selected");
		
		//$("#slider").anythingSlider({width:960});
		
		$("#tab li").click(function(){ 
			$(this).addClass("selected").siblings().removeClass("selected");
			var cur_idx=$("#tab li").index(this);
			$(".yui-content > div").eq(cur_idx).addClass("selected").siblings().removeClass("selected");
			$(".yui-content > div").fadeOut("normal").eq(cur_idx).fadeIn("normal");
		});
});</script>';

$htmlHeadXtra [] = '<script type="text/javascript">
	$(document).ready( function() {
		$("#sub").click( function() {
			$.prompt("' . get_lang ( "ConfirmSubmitQuiz" ) . '",{
					buttons:{\'确定\':true, \'取消\':false},
					callback: function(v,m,f){
						if(v){
							//$.prompt("' . get_lang ( "SubmittingPlsDoNothing" ) . '");
 							btnSumbit_onclick();
						}else{
						}
					}
			});
		});
	});
	</script>';

$htmlHeadXtra [] = '<script language="JavaScript" type="text/javascript">
function btnSumbit_onclick(){//提交试卷
	G("formSub").value="1";
	G("sub").disabled=true;
	G("frm_exercise").submit();//将答卷提交到ExamSave.php里面进行后台数据库操作
}</script>';

//liyu:
$max_time = $objExercise->selectDuration();
if($last_track_info) {
    $test_duration = strtotime($last_track_info['start_date'])+$max_time-time();
}else {
    $test_duration = $max_time;
}
if ($test_duration > 0) {
    $test_time_hour = floor ( $test_duration / 3600 );
    $test_time_min = floor ( ($test_duration - ($test_time_hour * 3600)) / 60 );
    $test_time_sec = floor ( $test_duration - ($test_time_hour * 3600) - ($test_time_min * 60) );//echo $test_time_sec;exit;
    $htmlHeadXtra [] = '<script language="JavaScript" type="text/javascript">
					var hours   = "' . $test_time_hour . '";
            		var minutes = "' . $test_time_min . '";
                    var seconds = "'.$test_time_sec.'";
                    var duration = "' . $test_duration . '";

                    function js_printTimer() {
                        if (hours <= 0 && minutes <= 0 && seconds <= 0 && duration) {
                            //alert("' . get_lang ( "TimeReached" ) . '!");
                            if(G("formSub")) G("formSub").value="1";
                       		G("frm_exercise").submit();
                        } else {
                            if (seconds >= 1) {seconds--;}
                            else {
                                if (seconds == 0 ) {seconds = 59;}
                                if (minutes >= 1)  {minutes--;}
                                else {
                                    if (minutes == 0) {minutes = 59;}
                                    if (hours >= 1)   {hours--;}
                                    else              {hours = 0;}
                                }
                            }
                            min = minutes.toString();
                            sec = seconds.toString();
                            if (min.length == 1) {min = "0" + min;}
                            if (sec.length == 1) {sec = "0" + sec;}

                            if(G("time_left")) G("time_left").innerHTML=(hours + ":" + min + ":" + sec);
                            setTimeout("js_printTimer()", 1000);
                        }
                    }
                   js_printTimer();
		</script>';
}

$htmlHeadXtra [] = import_assets ( "jquery-plugins/jquery.timers-1.2.js" );
$htmlHeadXtra [] = import_assets ( "jquery-plugins/jquery.form.js" );
$htmlHeadXtra [] = '<script language="JavaScript" type="text/javascript">
		function showResponse(responseText, statusText, xhr, $form)  {
			if(responseText==1)	$("#autosave_saving").hide();
		}
		
		function showRequest(formData, jqForm, options) {
			$("#autosave_saving").show();
		}
		
		function autoSave(){
			var options = { url:"ajax_actions.php?action=paper_auto_save_hanlder",type:"post",
			success:showResponse,beforeSubmit:showRequest};
			$("#frm_exercise").ajaxSubmit(options);
		}
		
		$(function(){
			$("#save_paper").click(function(){autoSave();});
		});
		</script>';

$htmlHeadXtra [] = '<style type="text/css">
ul, li {list-style:none outside none; font-size:13px}
#selectQsnAnswer ul li {float:left;overflow:hidden;padding-left:20px;
padding-right:5px;text-indent:8px;white-space:nowrap;/*width:45%;*/}
		
table.doneTestHeader{vertical-align:top;}
table.doneTestInfo{text-align:left;width:100%;}
table.doneTestHeader td#doneTestImage{width:10%;text-align:center;}
table.doneTestHeader #time_left{font-weight:bold;}
table.doneTestHeader #testName{
	font-size:16px;
	vertical-align:top;
	padding-bottom:10px;
}
table.doneTestHeader td#timer{
	text-align:left;
	vertical-align:middle;
	white-space:nowrap;
}
table.doneTestHeader td#timer *{vertical-align:middle;white-space:nowrap;}
.feedback{
	background:rgb(245,220,133);
	border:1px dotted gray;
	width:100%;
}
#time_left{font-family:system; font-size:24px}
.example {	float: left;	margin: 15px;}
.demo {	width: 120px;	height: 200px;	border: solid 1px #000;background: #FFF;	overflow: scroll;	padding: 5px;}
.button05 {
	position: relative;
	display: inline-block;
	min-width: 100px;
	padding: 10px 15px;
	margin: 0px 30px;
	background-color: #3E9ED6;
	background-image: -webkit-gradient(
		linear,
		left top,
		left bottom,
		color-stop(0, rgb(112,181,242)),
		color-stop(0.49, rgb(84,164,238)),
		color-stop(0.5, rgb(54,144,240)),
		color-stop(1, rgb(26,97,219))
	);
	background-image: -moz-linear-gradient(
		center top,rgb(112,181,242) 0%,rgb(84,164,238) 49%,rgb(54,144,240) 50%,rgb(26,97,219) 100%
	);


	color: #fff;
	font-weight: bold;
	font-size: 18px;
	text-decoration: none;
	text-align: center;
	line-height: 1;
	text-shadow: 0px -1px 0px rgba(0,0,0,0.5);

	border: 1px solid;
	border-color: #2f71a3 #265a82 #1e4766 #265a82;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	box-shadow: 		inset 0px -4px 5px rgba(255,255,255,0.2),
						inset 0px 1px 5px rgba(255,255,255,0.2),
						0px 2px 0px #1e4766,
						0px 4px 0px #1e4766,
						0px 6px 0px #1e4766,
						0px 8px 5px rgba(0,0,0,0.5);

	-moz-box-shadow: 	inset 0px -4px 5px rgba(255,255,255,0.2),
						inset 0px 1px 5px rgba(255,255,255,0.2),
						0px 2px 0px #1e4766,
						0px 4px 0px #1e4766,
						0px 6px 0px #1e4766,
						0px 8px 5px rgba(0,0,0,0.5);
	-webkit-box-shadow: inset 0px -4px 5px rgba(255,255,255,0.2),
						inset 0px 1px 5px rgba(255,255,255,0.2),
						0px 2px 0px #1e4766,
						0px 4px 0px #1e4766,
						0px 6px 0px #1e4766,
						0px 8px 5px rgba(0,0,0,0.5);
}

.button05:hover {
	background-color: #8AC5FD;

	background-image: -webkit-gradient(
		linear,
		left top,
		left bottom,
		color-stop(0, rgb(134,193,253)),
		color-stop(0.49, rgb(153,213,255)),
		color-stop(0.5, rgb(130,194,253)),
		color-stop(1, rgb(108,170,229))
	);
	background-image: -moz-linear-gradient(
		center top,rgb(134,193,253) 0%,rgb(153,213,255) 49%,rgb(130,194,253) 50%,rgb(108,170,229) 100%
	);
}

.button05:active {
	top: 4px;
	box-shadow: 		inset 0px 4px 5px rgba(255,255,255,0.4),
						inset 0px -1px 5px rgba(255,255,255,0.2),
						/**/
						0px 2px 0px #1e4766,
						/**/
						0px 4px 5px rgba(0,0,0,0.5);

	-moz-box-shadow: 	inset 0px 4px 5px rgba(255,255,255,0.4),
						inset 0px -1px 5px rgba(255,255,255,0.2),
						/**/
						0px 2px 0px #1e4766,
						/**/
						0px 4px 5px rgba(0,0,0,0.5);

	-webkit-box-shadow: inset 0px 4px 5px rgba(255,255,255,0.4),
						inset 0px -1px 5px rgba(255,255,255,0.2),
						/**/
						0px 2px 0px #1e4766,
						/**/
						0px 4px 5px rgba(0,0,0,0.5);
}
</style>';

include_once ("inc/page_header.php");
$exerciseTitle = api_parse_tex ( $exerciseTitle );
?>
<div class="index">
    <div class="xww">
        <div class="aa">

            <?php
            $quiz_question_type = $objExercise->getQuizQuestionTypes ( $exerciseId );
            $quiz_qt = array_keys ( $quiz_question_type );

//各大题型对应题目
            $questionListAll = $objExercise->getAllQuestionsByType ();

            if ($last_track_info) {
                $last_save_tracking_data = unserialize ( $last_track_info ['data_tracking'] );
                if (empty ( $last_save_tracking_data )) $last_save_tracking_data = array ();
            } else {
//初始记录本次测验
                $exeId = Exercise::create_event_exercice ( $exerciseId, 'incomplete' );
            }

            foreach ( $quiz_question_type as $qtype => $qcount ) {
                $total_question_cnt += $qcount [0];
            }

            $hiddens = array ("formSent" => "1", "formSub" => "1", 'exerciseId' => $exerciseId, 'result_id' => $track_id, 'exerciseType' => $exerciseType, 'ErrTimes' => 3, 'MoveOutTimes' => 0, 'MoveOutEnabled' => 1 );
            echo form_open ( "quiz_do.php?type=$typeId", array ("method" => "post", "name" => "frm_exercise", "id" => "frm_exercise","enctype"=>"multipart/form-data" ), $hiddens );
            if($last_track_info['start_date']) {
                $timerc = time() - strtotime($last_track_info['start_date']);
            }else{
                $timerc = 0;
            }
?>

            <script type="text/javascript">
                var timerc="<?=$timerc?>";
                var frace;
                function add(){
                        ++timerc;
                        $("#hour").html(parseInt(timerc/3600));
                        if(parseInt(timerc/60) >= 60) {
                            $("#min").html(parseInt(timerc / 60 % 60));
                        }else{
                            $("#min").html(parseInt(timerc / 60));
                        }
                        $("#sec").html(Number(parseInt(timerc%60/10)).toString()+(timerc%10));
                        setTimeout("add()", 1000);
                };
                add();
            </script>
            <div class="exampHead">
                <h3 class="estyle eTitle"><?=$exerciseTitle?></h3>
                <p class="estyle">
                    <span>学员： <?php echo $_user ["firstName"]?></span>
                    <span>试题总数： <?=$total_question_cnt?></span>
                    <span>答题时间： <?php echo ($test_duration > 0 ? ($max_time / 60) . "分钟" : "不限制");?></span>
                    <?php
                    if ($test_duration > 0) {
                        ?>
                        <span>剩余时间：<span id="time_left"></span></span><?php
                    }
                    ?>

                </p>
            </div>
            <div class="examfunction">

                <div class="examTime">正在作答:<span id="hour"></span>:<span id="min"></span>:<span id="sec"></span></div>
<!--               <a href="javascript:;" id="sub" class="cursor exampbutton">试卷提交</a>-->
            </div>
            <div id="tab" class="yui-navset" style="margin-top: 1px">
                <ul class="yui-nav">
                    <?php
                    if ($objExercise->display_type == 2) {
                        if ($quiz_qt && is_array ( $quiz_qt ) && count ( $quiz_qt ) > 1) {
                            $idx = 0;
                            foreach ( $quiz_question_type as $qtype => $qcount ) {
                                echo '<li id="qt_' . ($idx ++) . '"><a href="#"><em>' . $_question_types [$qtype] . "(" . $qcount [0] . ')</em></a></li>';
                            }
                        }
                    }
                    if ($objExercise->display_type == 1) {
                        for($i = 1; $i <= $total_question_cnt; $i ++) {
                            echo '<li id="q_' . ($i - 1) . '"><a href="#"><em>' . $i . '</em></a></li>';
                        }
                    }
                    ?>
                </ul>

                <div class="yui-content" id="slider" style="min-height: 300px">
                    <?php
//一屏显示一个题型
                    if ($objExercise->display_type == 2) {
                        foreach ( $quiz_question_type as $qtype => $qcount ) {
                            echo '<div id="tab_' . $qtype . '" class="xyz">';
                            $questionListByType = $questionListAll [$qtype];
                            echo '<div class="register_hint dd2">' . $_question_types [$qtype] . '(本题型共' . count ( $questionListByType ) . '题，共' . $quiz_question_type [$qtype] [1] . '分)</div>';
                            echo '<div class="exam_block_screen">';
                            if ($questionListByType && count ( $questionListByType ) > 0) {
                                if ($objExercise->random == 1) $questionListByType = shuffle_assoc ( $questionListByType );
                                $questionList = array_keys ( $questionListByType );
                                $i = 1;
                                foreach ( $questionListByType as $questionId => $questionItem ) {
                                    if ($last_track_info) $user_answer = $last_save_tracking_data [$questionId];
                                    display_question ( $questionItem, $i, $user_answer );
                                    $i ++;
                                }
                            }
                            echo '</div></div>';
                        }
                    }

//一屏显示一题
                    if ($objExercise->display_type == 1) {
                        foreach ( $quiz_question_type as $qtype => $qcount ) {
                            $questionListByType = $questionListAll [$qtype];
                            foreach ( $questionListByType as $questionId => $questionItem ) {
                                $questionList [$questionId] = $questionItem;
                            }
                        }

                        if ($questionList && count ( $questionList ) > 0) {
                            if ($objExercise->random == 1) $questionList = shuffle_assoc ( $questionList );
                            $i = 1;
                            foreach ( $questionList as $questionId => $questionItem ) {
                                $questionListByType = $qcount;
                                echo '<div id="tab_' . ($i - 1) . '">';
                                echo '<div class="exam_block_screen">';
                                if ($last_track_info) $user_answer = $last_save_tracking_data [$questionId];
                                display_question ( $questionItem, $i, $user_answer );
                                echo '</div></div>';
                                $i ++;
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="examePage">
            <?php
            if ($quiz_qt && is_array ( $quiz_qt ) && count ( $quiz_qt ) > 1) {
                ?>
                <span class="curson" id="last_btn">上一题</span>
                <span class="curson" id="next_btn">下一题</span>
                <?php
                 
            }
            ?>
                 <a href="javascript:;" id="sub" class="cursor exampbutton">试卷提交</a>
            <!--
            <td><span class="btn cursor" id="save_paper"
                style="vertical-align: middle; float: left; padding-left: 1px">保存答卷</span></td>
            <td><span class="btn cursor" id="sub"
                style="vertical-align: middle; float: left; padding-left: 1px">提交答卷</span></td>-->
            </tr>
            </table>
        </div>

        <div class="clearall"></div>
    </div>

    <?=form_close ()?>
</div>
<script>
    var next = $('#next_btn');
    var last = $('#last_btn');
    var ctl = $("#tab li");
    var block = $('.exam_block_screen');
    var examType_num = block.size();
    var cur_panel=0;

    function getNowNum(){
        var now = ctl.filter('.selected');

        var now_id = now.attr('id');
        var id_num = parseInt(now_id.split('_')[1]);
        return id_num;
    };

    $('#next_btn').bind('click',function(event){
        var now_num = getNowNum();
        //console.info(now_num);
        if(now_num == (examType_num-1)){
            var goNum = 0;
        }else{
            var goNum = now_num+1;
        }

        ctl.eq(goNum).addClass("selected").siblings().removeClass("selected");
        $(".yui-content > div").eq(goNum).addClass("selected").siblings().removeClass("selected");
        $(".yui-content > div").fadeOut('fast').eq(goNum).fadeIn('fast');
    });

    $('#last_btn').bind('click',function(event){
        var now_num = getNowNum();
        if(now_num == 0){
            var goNum = (examType_num-1);
        }
        else{
            var goNum = now_num-1;
        }
        ctl.eq(goNum).addClass("selected").siblings().removeClass("selected");
        $(".yui-content > div").eq(goNum).addClass("selected").siblings().removeClass("selected");
        $(".yui-content > div").fadeOut('fast').eq(goNum).fadeIn('fast');
    });
</script>

<?php

function display_question($questionItem, $i, $user_answer) {
    $questionId = $questionItem ['id'];
    $questionName = $questionItem ['question'];
    $contents = $questionItem ['contents'];
    $answerType = $questionItem ['type'];
    $questionPonderation = $questionItem ['question_score'];
    $flv_path = trim ( $questionItem ['picture'] );
    $table_attachment = Database::get_main_table ( TABLE_MAIN_SYS_ATTACHMENT );
    $sql = "SELECT * FROM " . $table_attachment . " WHERE TRIM(url)='" . escape ( $flv_path ) . "'";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $attachment = Database::fetch_one_row ( $sql, true, __FILE__, __LINE__ );
    $attachment_url = api_get_path ( WEB_PATH ) . "main/course/download.php?doc_url=" . urlencode ( $attachment ['url'] );
    //显示答案
    $objAnswerTmp = new Answer ( $questionId );
    $nbrAnswers = $objAnswerTmp->selectNbrAnswers ();

    echo '<div class="exam_problem dd7"><div>
              <h2>'. $i ."、题目概要：" . $questionName .'</h2>
          </div>
          <div>';
    if ($answerType== FREE_ANSWER or $answerType==COMBAT_QUESTION ) {
        echo '<br/><h2 style="margin-left:30px;width:105px;">题目描述：</h2><h3 style="margin-left:135px;">'.$contents.'</h3><br/>';
    }

    if ($flv_path) {
        $aa=  explode(".", $flv_path);
        $arr=$aa[1];
        if($arr=="doc"||$arr=="docx"){
?>
            <h2 style="display:inline-block;margin-left:30px;width:105px;">文档下载：</h2><a href="<?=$attachment_url?>"><?=$attachment['old_name']?></a><br/>

<?php
        }else{
?>
        <object type="application/x-shockwave-flash"
                data="<?=api_get_path ( WEB_CODE_PATH )?>courseware/player.swf"
                width="360" height="270">
            <param name="movie"
                   value="<?=api_get_path ( WEB_CODE_PATH )?>courseware/player.swf" />
            <param name="allowfullscreen" value="true" />
            <param name="allowscriptaccess" value="always" />
            <param name="flashvars"
                   value="file=<?=api_get_path ( WEB_PATH ) . $flv_path?>&image=preview.jpg&autostart=false" />
            <p><a href="http://get.adobe.com/flashplayer">Get Flash</a> to see
                thisplayer.</p>
        </object>


        <?php
        }
    }

    if($answerType==COMBAT_QUESTION){

        if($questionItem["vm_name"]!=='0'){
            $get_exercise_id=  (int)getgpc("exerciseId");
                echo "<br/><h2><a class='button05' href=/lms/main/cloud/cloudsearch.php?system=".$questionItem["vm_name"]."_".$_SESSION['_user']['user_id']."&nicnum=1&cid=".$get_exercise_id."&manage=0&user_id=".$_SESSION['_user']['user_id']." target='_new'>开启虚拟机场景</a>";
            $xmls['system']=$questionItem["vm_name"]."_".$_SESSION['_user']['user_id'];
            $xmls['nicnum']=1;
        }
        $sql_key="select is_k from exam_question where id=".$questionItem['id'];

        $is_key=  Database::getval($sql_key);

        $keyss_dat=  Database::getval("select keyss from exam_question where id=".$questionItem['id']);

        $keyss_count= count(unserialize($keyss_dat));
        if($is_key === "1"){
               echo '<h2 style="margin-left:30px;"><br>';
                for($i=0;$i<$keyss_count;$i++){
                  echo 'key'.($i+1).':&nbsp;<input style="border:1px solid #000;color:black;" type="text" name="keyss[' . $questionId . ']['.$i.']"/><br>';
                }
               echo '<br></h2>';
        }
        $_SESSION['user_system'][]= $xmls;
        if($questionItem['is_up'] != 0) {
            echo "<h2>实验报告：</h2><br><input type='file' name='choice[$questionId]' accept='image/doc'><span style='color: red'>选择实验报告，考试提交后实验报告也会一并提交(仅支持doc格式)。</span> ";
        }
    }

    if($answerType == FREE_ANSWER){
        echo '<textarea style="width:60%;height:100px;margin-bottom:10px" name="choice[' . $questionId . ']">' . $user_answer . '</textarea>';
        echo   "</br><input type='file' name='choice[$questionId]'>";
    }
    else {
        for($answerId = 1; $answerId <= $nbrAnswers; $answerId ++) {
            $answer = $objAnswerTmp->selectAnswer ( $answerId );
            $answerCorrect = $objAnswerTmp->isCorrect ( $answerId );
            if ($answerType == UNIQUE_ANSWER or $answerType == TRUE_FALSE_ANSWER) {
                $is_checked = ($answerId == $user_answer ? 1 : 0);
                ?>
            <div><input class='checkbox' type='radio'
                        name='choice[<?=$questionId?>]' value="<?=$answerId?>"
                        id="q_<?=$questionId . "_" . $answerId?>"
                <?=$is_checked ? 'checked' : ''?>> <label
                    for="q_<?=$questionId . "_" . $answerId?>"><?=Question::$alpha [$answerId]?>.<?=api_parse_tex ( $answer )?></label>
            </div>
            <?php
            } elseif ($answerType == MULTIPLE_ANSWER) {
                $is_checked = ($answerId == $user_answer ? 1 : 0);
                ?>
            <div><input class='checkbox' type='checkbox'
                        id='<?='q_' . $questionId . '_' . $answerId?>'
                        name='choice[<?=$questionId?>][<?=$answerId?>]' value='<?=$answerId?>'
                <?=$is_checked ? 'checked' : ''?> /> <label
                    for='<?='q_' . $questionId . '_' . $answerId?>'><?=Question::$alpha [$answerId]?>.<?=api_parse_tex ( $answer )?></label>
            </div>
            <?php
            } elseif ($answerType == FILL_IN_BLANKS) {
                $answer = $objAnswerTmp->getAnswer ( $answerId );
                list ( $answer ) = explode ( '::', $answer );
                $startlocations = strpos ( $answer, '[tex]' );
                $endlocations = strpos ( $answer, '[/tex]' );

                if ($startlocations !== false && $endlocations !== false) {
                    $texstring = api_substr ( $answer, $startlocations, $endlocations - $startlocations + 6 );
                    $answer = str_replace ( $texstring, '{texcode}', $answer );
                }
                $answer = ereg_replace ( '\[[^]]+\]', '<input type="text" name="choice[' . $questionId . '][]" class="inputText" style="width:20%">', nl2br ( $answer ) );
                // 4. replace the {texcode by the api_pare_tex parsed code}
                $texstring = api_parse_tex ( $texstring );
                $answer = str_replace ( "{texcode}", $texstring, $answer );

                echo "<div>" . $answer . "</div>";
            }
        }
    }
    echo '</div>

<div class="clearall"></div>
</div>';
}
