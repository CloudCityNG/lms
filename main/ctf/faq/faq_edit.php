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

include_once ("../../inc/global.inc.php");
//api_protect_admin_script ();
//if (! isRoot ()) api_not_allowed (); 
header("content-type:text/html;charset=utf-8");
$htmlHeadXtra [] = Display::display_kindeditor ( 'question', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'answer', 'normal' );
$exam_type = Database::get_main_table (FAQ_CONTEST);

$ids=  intval(getgpc('id'));
if($ids!=''){
    $sql="select question,answer,sequence from $exam_type where id = '".$ids."'";
    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    while($ss = Database::fetch_array ( $res )){
         $ss['question']=  htmlspecialchars_decode($ss['question']);
         $ss['answer']=  htmlspecialchars_decode($ss['answer']);
        $values = $ss;
        //var_dump($values);
       
    }
}

$form = new FormValidator ( 'examtype_new','POST','faq_edit.php?id='.$ids.'');

//question  问题
$form->addElement ( 'textarea', 'question', get_lang ( '问题' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );


// answer 答案
$form->addElement ( 'textarea', 'answer', get_lang ( '答案' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );

//sequence   排序时间
//$form->addElement ( 'text', 'sequence', get_lang ( "排序时间" ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
$form->addElement('html','<tr class="containerBody"><td class="formLabel">显示顺序</td><td class="formTableTd" align="left"><input
    style="width:20%;height:20px;" class="inputTes" , name="sequence" type="text"   value="'.$values['sequence'].'">&nbsp;&nbsp;&nbsp;<span 
    style="color:#999999"><i>(必须为数字)</i></span></td></tr>');
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );


$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
   $question   = htmlspecialchars($exam_list['question']);
   $answer   = htmlspecialchars($exam_list['answer']);
    $data = $exam_list  ['sequence'];
        if(is_numeric($data)){
            $sequence=$exam_list["sequence"];
        }else{
            exit("");
        }
    
    
    
    $sql ="UPDATE  `vslab`.`tbl_faq` SET  `question` =  '".$question."',`answer` =  '".$answer."',`sequence`='".$sequence."
                           'WHERE  `id` =".$ids;
    //echo  $sql;exit();
    api_sql_query ( $sql, __FILE__, __LINE__ );
   
           tb_close( 'faq_list.php' );
    
   

}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>
<script type="text/javascript">
     $("#examtype_new").submit(function(){
        var sai=$(".inputTes").val();
        var matchArray = sai.match(/^[1-9]\d*$/)
        if (matchArray == null) {
          alert("显示顺序必须为数字");
          return false;
        }
     });
</script>
