<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chang
 * Date: 13-6-20
 * Time: 上午9:15
 * To change this template use File | Settings | File Templates.
 */
$language_file = 'admin';
$cidReset = true;

include_once ("../inc/global.inc.php");
//api_protect_admin_script ();
//if (! isRoot ()) api_not_allowed (); 
header("content-type:text/html;charset=utf-8");
$htmlHeadXtra [] = Display::display_kindeditor ( 'description', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'rules', 'normal' );
$exam_type = Database::get_main_table (FAQ_CONTEST);

$ids=  intval(getgpc('id'));

if($ids!=''){
    $sql="select description,rules from cn_massage where id = ".$ids;
    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    while($ss = Database::fetch_array ( $res )){
         $ss['description']= $ss['description'];
         $ss['rules']=$ss['rules'];
        $values = $ss;
    }
}

$form = new FormValidator ( 'examtype_new','POST','massage_edit.php?id='.$ids.'');

//question  问题
$form->addElement ( 'textarea', 'description', get_lang ( '说明' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );


// answer 答案
$form->addElement ( 'textarea', 'rules', get_lang ( '规则' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );

$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );


$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
   $description   = $exam_list['description'];
   $rules  = $exam_list['rules'];
        
    $sql ="UPDATE cn_massage SET description =  '{$description}' , rules  =  '{$rules}'  WHERE id =".$ids;

    api_sql_query ( $sql, __FILE__, __LINE__ );
   
           tb_close( 'massage_list.php' );
    
   

}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>

