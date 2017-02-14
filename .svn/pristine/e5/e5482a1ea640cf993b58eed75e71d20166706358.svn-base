<?php
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
 
$rel_id=  getgpc('rel_id');    

$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( $tool_name,FALSE );

$form = new FormValidator ( 'setting_sequent', 'get', '', '' ); 
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '{element} ' ); 

$form->addElement ( 'text', 'sequentially', "技能课程顺序", array ('maxlength' => 50, 'style' => "width:30%", 'class' => 'inputText' ) );
$form->addElement ( 'hidden', 'rel_id', $rel_id );
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$sequentially=  Database::getval("select  sequentially  from  skill_course_occupation   where  id=".$rel_id);
$defaults['sequentially']=$sequentially;
$form->setDefaults ( $defaults );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );

if ($form->validate ()) {   
    $data_list  = $form->getSubmitValues ();
   $rel_id= $data_list['rel_id'];
   $sequentially=$data_list['sequentially'];
    
   $sql="update   `skill_course_occupation`  set   `sequentially`='".$sequentially."'  where `id`=".$rel_id;  
   api_sql_query($sql);
   tb_close();
}
?>

<article class="module width_full hidden">
    
    <div class="managerSearch">
    <div class="seart">
        <?php $form->display (); 
        Display::display_footer ();
        ?>
    </div>
</div>
    
</article>