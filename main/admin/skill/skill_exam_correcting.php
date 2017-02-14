<?php
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
header("content-type:text/html;charset=utf-8"); 
$id= intval(getgpc('id'));
$sql = "SELECT    `uid`,`occupation_id`,`user_file`  FROM  `skill_examine`  where  `user_answer`='skill_exam'   and  `id`=".$id;  
$res=  api_sql_query_array_assoc($sql, __FILE__,__LINE__);
 
$form = new FormValidator ( 'examtype_new','POST','skill_exam_correcting.php','');
$html_text='<tr class="containerBody">
		<td class="formLabel">报告文件</td>
		<td class="formTableTd" align="left">'.
                                        '<a  href="'.URL_APPEDND.'/storage/occupation_exam_report/'.$res[0]['user_file'].'">'.$res[0]['user_file'].'</a>'
		 .'</td>
	       </tr>';
$form->addElement ( 'html', $html_text);    //'<a  href="'.URL_APPEDND.'/storage/occupation_exam_report/'.$res[0]['user_file'].'">'.$res[0]['user_file'].'</a>'
//$form->addElement ( 'text', 'screenshot_file', '报告文件', array ('type'=>'text' ,'value'=>$res[0]['user_file']) );
//$form->freeze('screenshot_file');
$form->addElement ( 'textarea', 'comment', '教师评语', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );  
$group = array ();
$group [] = $form->createElement ( 'radio', 'status', null, '未通过', '0' );
$group [] = $form->createElement ( 'radio', 'status', null, '通过', '1');
$form->addGroup ( $group, 'status', '结果', '&nbsp;&nbsp;&nbsp;&nbsp;', false );
$form->addRule ( 'status', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addElement("hidden","uid",$res[0]['uid']);
$form->addElement("hidden","occupation_id",$res[0]['occupation_id']);
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
 
 $sql ="select  `comment`,`status` from `skill_line`   WHERE `uid`={$res[0]['uid']}  and `skill_id`={$res[0]['occupation_id']}";
 $default= api_sql_query_array_assoc($sql, __FILE__, __LINE__);
 $defaults=$default[0]; 
 $form->setDefaults ( $defaults );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
 
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
     $occupat_id   = $exam_list['occupation_id'];   
    $uid  = $exam_list['uid'];
    $comment   = $exam_list['comment'];
    $status  = $exam_list['status']; 
 
    $sql ="UPDATE `skill_line` SET  `comment`='{$comment}',`status`={$status}  WHERE `uid`={$uid}  and `skill_id`={$occupat_id}";
    api_sql_query ( $sql, __FILE__, __LINE__ );
 
    tb_close( );
}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
