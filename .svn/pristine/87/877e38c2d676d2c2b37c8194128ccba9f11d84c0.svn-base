<?php
$language_file = array ('tracking', 'scorm', 'create_course', 'admin' );
include_once ('../inc/global.inc.php');
api_protect_admin_script ();
require_once (api_get_path ( LIBRARY_PATH ) . 'import.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'mail.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php'); 
 
 
$form = new FormValidator ( 'user_import' ); 
//选择文件
$form->addElement ( 'file', 'import_file', '导入文件', array ('style' => "width:60%", 'class' => 'inputText' ) );
$form->addRule ( 'import_file', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$allowed_file_types = array ('zip' );
$form->addRule ( 'import_file', get_lang ( 'InvalidExtension' ) . ' (' . implode ( ',', $allowed_file_types ) . ')', 'filetype', $allowed_file_types );
$form->addElement ( 'text', 'notice', '注意：', array ('style' => "width:60%;", 'class' => 'inputText','value'=>'导入之前，原有记录会被清除!!' ) );
$form->freeze('notice');
$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
 
Display::setTemplateBorder ( $form, '98%' );
$form->add_progress_bar(1);
if ($form->validate ()) {
  if($_FILES ['import_file'] ['size']!=0){ 
      $file_dir=URL_ROOT."/www".URL_APPEDND."/storage/DATA/logs";
      $filename=$file_dir."/".$_FILES['import_file']['name'];
      move_uploaded_file($_FILES['import_file']['tmp_name'],$filename );
      exec("cd ".$file_dir.";tar  -xvf ".$_FILES['import_file']['name']); 
      $name=explode(".", $_FILES['import_file']['name']);
      $name_file=$name[0].".lib"; 
      api_sql_query("truncate  table  ".$name[0]);
      exec("mysql  -u ".DB_USER." -p".DB_PWD." ".DB_NAME."  <  ".$file_dir."/".$name_file);  
      exec("rm ".$file_dir."/".$_FILES['import_file']['name']);
      exec("rm ".$file_dir."/".$name_file);
  }  
  tb_close ();
}
$tool_name = get_lang ( 'ImportUserListXMLCSV' );
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( $tool_name, FALSE );
 
$form->display (); 
Display::display_footer (); 
?>
