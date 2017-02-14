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

$form = new FormValidator ( 'weight_add','POST','weight_add.php?action=add&add=asdas');
//名称
$form->addElement ( 'text', 'weight_name', "节点IP地址", array ('maxlength' => 50, 'style' => "width:200px", 'class' => 'inputText' ) );
$form->addRule ( 'weight_name', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'weight_name', get_lang ( '最大字符长度为16' ), 'maxlength', 16 );

$form->addElement ( 'text', 'weight', "花费", array ('maxlength' => 50, 'style' => "width:200px", 'class' => 'inputText' ) );
$form->addRule ( 'weight', get_lang ( '您的输入不是数字，请重试！' ), 'numeric' );
$form->addRule ( 'weight', get_lang ( 'ThisFieldIsRequired' ), 'required' );

$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
  
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
 
if ($form->validate ()) { 
    $data_list   = $form->getSubmitValues (); 
    $weight_name = trim($data_list['weight_name']); 
    $weight      = (int)$data_list['weight'];
    
    if($weight_name!=='' && $weight!==''){
        //节点权重设置
        $cloudweight_dir = "/etc/cloudschedule/cloudweight";
//        $cloudweight_dir = "/var/www/cloudweight";
        $cloudweight_str=file_get_contents($cloudweight_dir);//读取文件
        $ini_list3 = explode("\n",$cloudweight_str);//按换行拆开,放到数组中.
        $weight_str1=array_filter($ini_list3);//数组去空
        $weight_res = count($weight_str1);//count
        
        $weight_var=$weight_name." ".$weight."\n";
        for($b=0;$b<$weight_res;$b++){
            $weight_array=explode(" ",$weight_str1[$b]);
            if($weight_array[0] && $weight_array[0]!==$weight_name){
                    $weight_var.= $weight_array[0]." ".$weight_array[1]."\n";
            }
        }
        $cloudweight_open = fopen($cloudweight_dir,'w');
        fwrite($cloudweight_open,$weight_var);
        fclose($cloudweight_open);
    }
    tb_close ( 'cloud_plan.php?category=weight' );
}
if($action=='add'){
    Display::display_header ( $tool_name, FALSE );
    $form->display ();
    Display::display_footer ();  
}else{
   echo "<h1 align='center' style='color:red;'>您的访问非法！</h1>";
}
