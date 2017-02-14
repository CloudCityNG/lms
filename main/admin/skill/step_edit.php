<?php
$language_file = 'admin';
$cidReset = true;

include_once ("../../inc/global.inc.php");
$id= intval(getgpc("id"));
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
header("content-type:text/html;charset=utf-8");
$htmlHeadXtra [] = Display::display_kindeditor ( 'step_desc', 'normal' );
$exam_type = Database::get_main_table (exam_type);

$occupations='';
$sql="select  `id`,`skill_name`  from  `skill_occupation` ";
$occupat_res= api_sql_query_array_assoc($sql, __FILE__, __LINE__); 
foreach ($occupat_res  as  $value){
    $occupations[$value['id']]=$value['skill_name'];
}

$form = new FormValidator ( 'examtype_new','POST','step_edit.php','');
$form->addElement ( 'textarea', 'step_desc', '技能阶段描述', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
$form->addElement ( 'select', 'occupat_id', '所属职业技能', $occupations );
$form->addElement ( 'text', 'step_time', '阶段学习时间');
$form->addElement ( 'text', 'step_sequentially', '技能阶段顺序');
$form->addElement('hidden','step_id',$id);
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$sql="select  `step_desc`,`occupat_id`,`step_time`,`step_sequentially`  from  `skill_rel_step`  where  `step_id`=".$id;  
$values= api_sql_query_array_assoc($sql); 
$defaults='';
$defaults=$values[0];
 $form->setDefaults ( $defaults );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );

if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
    $step_desc   = $exam_list['step_desc'];
    $occupat_id   = $exam_list['occupat_id'];
    $step_time  = $exam_list['step_time'];
    $step_sequentially  = $exam_list['step_sequentially']; 
    $step_id=$exam_list['step_id'];
     
    $sql ="UPDATE `skill_rel_step` SET  `occupat_id`={$occupat_id},`step_desc`='{$step_desc}',`step_time`='{$step_time}',`step_sequentially`={$step_sequentially}   WHERE   step_id=".$step_id;
    api_sql_query ( $sql, __FILE__, __LINE__ );
     
    tb_close( 'step_manage.php' );
}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
