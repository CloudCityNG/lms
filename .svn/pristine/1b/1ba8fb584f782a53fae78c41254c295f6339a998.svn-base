<?php
$cidReset = true;
include_once ("inc/app.inc.php");
$user_id = api_get_user_id ();
$occupat_id=  getgpc('id');
 $sql ="select  `comment`,`status` from `skill_line`   WHERE `uid`={$user_id}  and `skill_id`={$occupat_id}";
 $default= api_sql_query_array_assoc($sql, __FILE__, __LINE__);
 $defaults=$default[0];
 $defaults['status']=($default[0]['status']==1?'通过':'未通过');
 
$form = new FormValidator ( '' );
 $form->addElement('textarea','comment','教师评语');
 $form->freeze('comment');
 $form->addElement('text','status','结果');
 $form->freeze('status');
$group = array (); 
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( '确定' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, null, '&nbsp;', '&nbsp;&nbsp;' );
$form->applyFilter ( '__ALL__', 'trim' );

 $form->setDefaults ($defaults);
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '100%' );
if ($form->validate ()) {
  
} 
Display::display_header ( null, FALSE );
$form->display (); 
 
$sql="select  `user_file`  from   `skill_examine`  where  `user_answer`='skill_exam'    and `uid`=".api_get_user_id()."  and   `occupation_id`={$occupat_id} ";
$filename=  Database::getval($sql); 
?>
<div  style="float: right;margin-right: 50px;">  <a   href="<?=URL_APPEDND.'/storage/occupation_exam_report/'.$filename?>"   style="vertical-align: middle;">下载我的技能报告</a> </div>