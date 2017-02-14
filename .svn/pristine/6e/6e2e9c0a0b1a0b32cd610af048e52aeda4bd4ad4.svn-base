<?php
$cidReset = true;
include_once ("../../inc/global.inc.php");
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
header("content-type:text/html;charset=utf-8");

require_once ('../../../main/inc/global.inc.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
$action = getgpc('action','G');
$add = getgpc('add','G');

$form = new FormValidator ( 'hot_add','POST','hot_add.php?action=add&add=shbdi');
//名称
$form->addElement ( 'text', 'hot_name', "热备节点IP", array ('maxlength' => 50, 'style' => "width:200px", 'class' => 'inputText' ) );
$form->addRule ( 'hot_name', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'hot_name', get_lang ( '最大字符长度为16' ), 'maxlength', 16 );

$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
  
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
 
if ($form->validate ()) { 
    $data_list  = $form->getSubmitValues (); 
    $hot_name = trim($data_list['hot_name']); 
    
    if($hot_name){
        //节点热备设置
        $delcloud_dir = "/etc/cloudschedule/delcloud";
//        $delcloud_dir = "/var/www/delcloud";
        $delcloud_str=file_get_contents($delcloud_dir);//读取文件
        $ini_list2 = explode("\n",$delcloud_str);//按换行拆开,放到数组中.
        $delcloud_str=array_filter($ini_list2);//数组去空
        $delcloud_str=array_unique($delcloud_str);//数组去重
        $delcloud_res = count($delcloud_str);//count
        
        $delcloud_array=$hot_name."\n";
        for($a=0;$a<$delcloud_res;$a++){
            if($delcloud_str[$a] && $delcloud_str[$a]!==$hot_name){
                    $delcloud_array.= $delcloud_str[$a]."\n";
            }
        }
        echo "<pre>".$delcloud_array."</pre>";
        $delcloud_open = fopen($delcloud_dir,'w');
        fwrite($delcloud_open,$delcloud_array);
        fclose($delcloud_open);
    }
    tb_close ( 'cloud_plan.php?category=hot' );
}
if($action=='add' && $add){
    Display::display_header ( $tool_name, FALSE );
    $form->display ();
    Display::display_footer ();  
}else{
   echo "<h1 align='center' style='color:red;'>您的访问非法！</h1>";
}
