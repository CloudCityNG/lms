<?php
$language_file = 'admin';
$cidReset = true;

include_once ("../../inc/global.inc.php");

$path=URL_ROOT."/www".URL_APPEDND."/storage/occupation_picture";    
if(!file_exists($path)){   
    exec(" mkdir   $path  ; chmod  -R 777   $path");
}

if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
header("content-type:text/html;charset=utf-8");
//$htmlHeadXtra [] = Display::display_kindeditor ( 'skill_description', 'normal' );
//$htmlHeadXtra [] = Display::display_kindeditor ( 'position_description', 'normal' );
//$htmlHeadXtra [] = Display::display_kindeditor ( 'postition_requirement', 'normal' );
//$htmlHeadXtra [] = Display::display_kindeditor ( 'exam_desc', 'normal' );
$exam_type = Database::get_main_table (exam_type);
$id=  getgpc('id');
$form = new FormValidator ( 'examtype_new','POST','occupation_edit.php?id='.$id,'');
$form->addElement ( 'text', 'skill_name', "职业技能名称", array ('maxlength' => 50, 'style' => "width:30%", 'class' => 'inputText' ) );
$form->addRule ( 'skill_name', get_lang ( '最大字符长度为30' ), 'maxlength', 30 );
$form->addRule ( 'skill_name', get_lang ( 'ThisFieldIsRequired' ), 'required' );

$form->addElement ("file","occupat_picture","职业技能图片", array ('style' => "width:30%", 'class' => 'inputText' ) ); 

$form->addElement ( 'textarea', 'skill_description', '技能描述', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
$form->addElement ( 'textarea', 'position_description', '职业描述', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
$form->addElement ( 'textarea', 'postition_requirement', '职位需求', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
$form->addElement ( 'textarea', 'exam_desc', '职业技能考核描述', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
$form->addElement ( 'hidden', 'hide_id',$id );

$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$sql="select  `skill_name`,`skill_description`,`position_description`,`postition_requirement`,`exam_desc`  from  `skill_occupation`  where  `id`=".$id;  
$values= api_sql_query_array_assoc($sql); 
$defaults='';
$defaults=$values[0];
 
$form->setDefaults ( $defaults );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {   
    $exam_list  = $form->getSubmitValues ();
    $skill_name   = $exam_list['skill_name'];
    $skill_description   = $exam_list['skill_description'];
    $position_description  = $exam_list['position_description'];
    $postition_requirement  = $exam_list['postition_requirement'];
    $exam_desc=$exam_list['exam_desc'];
    $hide_id=$exam_list['hide_id'];
    
    $sql =" update `skill_occupation`  set  `skill_name`='{$skill_name}',`skill_description`='{$skill_description}',`position_description`='{$position_description}',`postition_requirement`='{$postition_requirement}',`exam_desc`='{$exam_desc}'  where  id=".$hide_id;
    $re=api_sql_query ( $sql, __FILE__, __LINE__ );
 
     if ($re  &&  $_FILES ['occupat_picture'] ['size'] > 0 && is_uploaded_file ( $_FILES ['occupat_picture'] ['tmp_name'] )) {           
           $exp=explode(".", $_FILES ['occupat_picture'] ['name']); 
            $destination=URL_ROOT."/www".URL_APPEDND."/storage/occupation_picture/occupat_".$hide_id.".".end($exp);
            move_uploaded_file($_FILES ['occupat_picture'] ['tmp_name'], $destination);
            
            $sql="update  `skill_occupation`  set  `occupat_picture`='"."occupat_".$hide_id.".".end($exp)."'  where  `id`=".$hide_id;
            api_sql_query($sql);
       }
    
    tb_close( 'occupation_manage.php' );

}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
