<?php
$language_file = 'admin';
$cidReset = true;

include_once ("../../inc/global.inc.php");
//api_protect_admin_script ();
//if (! isRoot ()) api_not_allowed ();
header("content-type:text/html;charset=utf-8");
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchDesc', 'normal' );
$exam_type = Database::get_main_table (SAI_CONTEST);

$ids=  intval(getgpc('id'));
if( $ids!=''){
    $sql="select matchDesc from $exam_type where id = '".$ids."'";
    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    while($ss = Database::fetch_array ( $res )){
        $values = $ss;
    }
}

$form = new FormValidator ( 'examtype_new','POST','exam_edit.php?ids='.$ids,'');

function check_name( $element_value,$exam_type) {
    $sql="select `matchDesc` from ".$exam_type;
    $Host_name=Database::get_into_array ( $sql );
    if (in_array($element_value,$Host_name)) {
        return false;
    } else {
        return true;
    }
}
$form->addElement ( 'textarea', 'matchDesc', '描述', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );



$group = array ();
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );


$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );


Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
