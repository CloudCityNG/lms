<?php
$language_file = 'admin';
$cidReset = true;

include_once ("../../inc/global.inc.php");

if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
header("content-type:text/html;charset=utf-8");
$htmlHeadXtra [] = Display::display_kindeditor ( 'topic', 'normal' ); 

$occupations='';
$sql="select   `course_id`  from  `skill_course_occupation` ";
$occupat_res= api_sql_query_array_assoc($sql, __FILE__, __LINE__); 
foreach ($occupat_res  as  $value){    
    $occupations[$value['course_id']]=  Database::getval("select  `title`  from  `course`  where  `code`='".$value['course_id']."'");
}

$form = new FormValidator ( 'examtype_new','POST','skill_question_add.php','');
$form->addElement ( 'textarea', 'topic', '题目', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
$form->addElement ( 'select', 'contcat_id', '课程名称', $occupations );
$form->addElement ( 'text', 'answer', '正确答案',array('style'=>'width:30%'));
$form->addElement ( 'text', 'score', '分值');
 
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
 
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );

if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
    $topic   = $exam_list['topic'];
    $contcat_id   = $exam_list['contcat_id'];
    $answer  = $exam_list['answer'];
    $score  = $exam_list['score']; 
     
    $sql ="INSERT INTO `skill_question`(`topic`, `contcat_id`, `type`, `answer`, `score`) VALUES ('{$topic}','{$contcat_id}',1,'{$answer}',{$score})";
    $re=api_sql_query ( $sql, __FILE__, __LINE__ );
     
    tb_close( 'skill_course_exam.php' );
}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
