<?php
$cidReset = true;
include_once ("inc/app.inc.php");
$user_id = api_get_user_id ();

$occupat_id=  getgpc('skill_id');
$sql="select  `exam_desc`  from  `skill_occupation`  where  `id`=".$occupat_id;
$exam_desc=  Database::getval($sql);  
$form = new FormValidator ( '' );
 $form->addElement('text','exam_desc','技能测试描述', array ( 'value'=>$exam_desc ));
 $form->freeze('exam_desc');
 $form->addElement ( 'file', 'screenshot_file', "报告文件", array ('style' => "width:30%", 'class' => 'inputText','id'=>'screenshot_file' ) ); 
 $allowed_file_types = array ('doc' ,'docx');
$form->addRule ( 'screenshot_file', get_lang ( '限定文件格式' ) . ' (' . implode ( ',', $allowed_file_types ) . ')', 'filetype', $allowed_file_types );

$form->addElement("hidden","skill_id",$occupat_id);
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit_plus', '保存', 'class="save"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, null, '&nbsp;', '&nbsp;&nbsp;' );
$form->applyFilter ( '__ALL__', 'trim' );

$form->setDefaults ($default);
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '100%' );
if ($form->validate ()) {
    $labs_report = $form->getSubmitValues ();
    $skill_id=$labs_report['skill_id'];
    $u_id=api_get_user_id ();
    $exp=explode(".", $_FILES ['screenshot_file'] ['name']);   
    $name=$u_id."_".$skill_id."_report.". end($exp);
    $tmp_name=$_FILES["screenshot_file"]["tmp_name"]; 
    $path=URL_ROOT.'/www'.URL_APPEDND.'/storage/occupation_exam_report';
    $file=$path."/".$name;
    if(!file_exists($path)){ 
         exec("mkdir  ".$path); 
         exec("chmod -R 777 ".$path); 
    }
      move_uploaded_file($tmp_name, $file);
 
      $sql="select  count(`id`)  from   `skill_examine`  where  `user_answer`='skill_exam'  and  `uid`={$u_id}   and   `occupation_id`={$skill_id} ";
      $is_exa=  Database::getval($sql);
      if($is_exa>0){
          $exam_sql="UPDATE `skill_examine` SET  `user_file`='{$name}'   WHERE  `user_answer`='skill_exam'  and  `uid`={$u_id}   and   `occupation_id`={$skill_id} ";
      }else{
           $exam_sql="INSERT INTO `skill_examine`(`uid`, `occupation_id`, `user_answer`, `user_file`) VALUES ({$u_id},{$skill_id},'skill_exam','{$name}')";
      } 
      api_sql_query ( $exam_sql, __FILE__, __LINE__ );
      tb_close (  );
}

Display::display_header ( null, FALSE );
$form->display (); 
?>
<div  style="float: right;margin-right: 50px;"> <a href="../../main/admin/skill/show_topo.php?action=show&occupation_id=<?=$occupat_id?>"  target="_blank"><strong>点击进入技能考核场景</strong></a></div>