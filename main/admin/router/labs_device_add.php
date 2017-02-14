<?php
/**
 * This is an add routing and switching page
 * @changzf
 * on 2013/01/10
 */

$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
header("content-type:text/html;charset=utf-8");

require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
$table_labs_device = Database::get_main_table (labs_devices);

$labs_id=  intval(getgpc("id","G"));
if(isset( $_GET['action']) && $labs_id!=''){
$labs_id=Database::getval("select `name` from `labs_labs` where `id`=".$labs_id,__FILE__,__LINE__);
    $values['lab_id']=$labs_id;
}

$up_url=$_SERVER['HTTP_REFERER'];
$url_str= end(explode("/", $up_url));
$url_arr=  explode("?", $url_str);
$up_url=$url_arr[0];
 
$htmlHeadXtra [] = '
<script src="../syllabus/jquery.js" type="text/javascript"></script>
<script language="JavaScript" type="text/JavaScript">

//var name = "ios_name";

function getarea(){
 var region_id = $("#ios_name").val();//获得下拉框中大区域的值

 if(region_id != ""){
  $.ajax({
  type: "post",
  url: "device_check.php",
  data:"region_id="+region_id,
  cache:false,
  beforeSend: function(XMLHttpRequest){
  },
  success: function(data, textStatus){ 
    $("#labs_device>table>tbody .containerBody #modules").empty();//清空
    $("#labs_device>table>tbody .containerBody #modules").append(data);//给下拉框添加option
     },
  complete: function(XMLHttpRequest, textStatus){
  },
  error: function(){

  }
 });
 }
}


function getarea1(){
//当选中Server类型--->则型号只有pc-------Zdan
    var device_type=$("#device_type").val(); //获得设备类型
     if(device_type != ""){
          $.ajax({
              type: "post",
              url: "device_check.php",
              data:"device_type="+device_type,
              cache:false,
              beforeSend: function(XMLHttpRequest){
              },
              success: function(data, textStatus){
                $("#labs_device>table>tbody .containerBody #ios_nm").empty();//清空
                $("#labs_device>table>tbody .containerBody #ios_nm").append(data);//给下拉框添加option

                 },
              complete: function(XMLHttpRequest, textStatus){
              },
              error: function(){

              }
             });
  }

//当选中Server类型--->出现虚拟模板下拉框
    var device_type1=$("#device_type").val(); //获得设备类型
     if(device_type1 != ""){
          $.ajax({
              type: "post",
              url: "device_check.php",
              data:"device_type1="+device_type1,
              cache:false,
              beforeSend: function(XMLHttpRequest){
              },
              success: function(data1, textStatus){
                $("#labs_device>table>tbody .containerBody #lab_vm").empty();//清空
                $("#labs_device>table>tbody .containerBody #lab_vm").append(data1);//给下拉框添加option

                 },
              complete: function(XMLHttpRequest, textStatus){
              },
              error: function(){

              }
             });
  }
}

</script>';

$form = new FormValidator ( 'labs_device','POST','labs_device_add.php?action='.$labs_id,'');
$form->addElement ( 'html', '<div style="margin-top:2px;"></div>');
$form->addElement ( 'text', 'name', "名字", array ('maxlength' => 50, 'style' => "width:30%", 'class' => 'inputText' ) );
$form->addRule ( 'name', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'name', get_lang ( '最大字符长度为30' ), 'maxlength', 30 );

$form->registerRule('name_only','function','check_name');
//$form->addRule('name','您输入的内容已存在，请重新输入', 'name_only');
function check_name($element_name, $element_value) {
    $table_labs_device = Database::get_main_table ( labs_devices );
    $sql="select name from $table_labs_device";
    $vmdisk_name=Database::get_into_array ( $sql );

    if (in_array($element_value,$vmdisk_name)) {
        return false;
    } else {
        return true;
    }
}
$labs_ios = Database::get_main_table ( labs_labs );
$sql = "select name FROM  $labs_ios ";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$vm= array ();
while ( $vm = Database::fetch_row ( $res) ) {
    $vms [] = $vm;
}
foreach ( $vms as $k1 => $v1){
    foreach($v1 as $k2 => $v2){
        $lab[$v2]  = $v2;
    }
}

$sql = "select `name` FROM  `labs_ios` ";
$ress = api_sql_query ( $sql, __FILE__, __LINE__ );
$vms= array ();
while ( $vms = Database::fetch_row ( $ress) ) {
    $vmss [] = $vms;
}
foreach ( $vmss as $k1 => $v1){
    foreach($v1 as $k2 => $v2){
        $ios[$v2]  = $v2;
    }
}
////array_push($ios,'请选择设备型号');
//arsort($ios);

$picture = array('cloud'=>'Cloud','desktop'=>'Desktop','framerelay'=>'Frame Relay Switch','l3switch'=>'L3 Switch','mpls'=>'MPLS Router','router'=>'Router','server'=>'Server','switch'=>'Switch');

$form->addElement ( 'select', 'lab_id', "实验拓扑", $lab,array ('maxlength' => 50, 'style' => "width:30%;height:22px;" ) );
$form->freeze('lab_id');
$form->addElement ( 'select', 'picture', "设备类型", $picture,array ('id'=>"device_type",'maxlength' => 50, 'style' => "width:30%;height:22px;",'onChange'=>"getarea1()" ) );
$form->addElement('html','<tr id="lab_vm0" class="containerBody"><td class="formLabel">虚拟模板</td><td id ="lab_vm"></td></tr>');
//$form->addElement ( 'select', 'ios', "设备型号", $ios,array ('id' => "ios_name", 'style' => "width:30%;height:22px;",'onChange' => "getarea()" ) );
$form->addElement('html','<tr class="containerBody"><td class="formLabel">设备型号</td><td id ="ios_nm"></td></tr>');
$form->addElement('html','<tr class="containerBody"><td class="formLabel">模块设置</td><td id ="modules"></td></tr>');


//$form->addElement ( 'text', 'top', "上外边距", array ('maxlength' => 2, 'style' => "width:30%", 'class' => 'inputText' ) );
//$form->addElement ( 'text', 'left', "左外边距", array ('maxlength' => 2, 'style' => "width:30%", 'class' => 'inputText' ) );

$form->addElement('html','<tr class="containerBody"><td class="formLabel">上边距</td><td class="formTableTd" align="left"><input maxlength="3" style="width:30%" class="inputText" name="top" type="text">&nbsp;&nbsp;&nbsp;<span style="color:#999999"><i></i></span></td></tr>');
$form->addRule ( 'top', '您输入的内容不是数字,请重新输入！', 'numeric' );

$form->addElement('html','<tr class="containerBody"><td class="formLabel">左边距</td><td class="formTableTd" align="left"><input maxlength="3" style="width:30%" class="inputText" name="left" type="text">&nbsp;&nbsp;&nbsp;<span style="color:#999999"><i></i></span></td></tr>');
$form->addRule ( 'left','您输入的内容不是数字,请重新输入！', 'numeric' );

$form->addElement ( 'textarea', 'desc', "描述", array ('style' => "width:65%;height:150px;",'class'=>"inputText") );
$form->addElement("hidden","hidden",$up_url);
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
if(isset( $_GET['action'])=='device_add'){
    $form->freeze ( array ("lab_id" ) );
    $labs['lab_id'] = $values['lab_id'];
}

$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );

if ($form->validate ()) {
    $labs  = $form->getSubmitValues ();
    $name    = $labs['name'];
    $lab_id  = $labs['lab_id'];
    $ios  = $labs['ios_name'];
    $vmdisks=$labs['vm_name'];
    $picture  = $labs['picture'];
    $conf_id  = $labs['desc'];
    $left           = $labs['left'];
    $top           = $labs['top'];
    $hidden=$labs['hidden']; 
    if($top==0){
        $top=rand(200,500);
    }
    if($left==0){
        $left=rand(200,500);
    }

    $slots='';
    if($labs['slot_number']!=='' && $labs['slot_number']!=='0'){
        for($i=0;$i<$labs['slot_number'];$i++){
            $slot='slot'.$i;
            if($labs[$slot]==''){
                $slots.='';
            }else{
                $slots.=$labs[$slot].';';
            }
        }
    }

    $sql = "INSERT  INTO  `labs_devices` (`name`,`lab_id`,`ios`,`vmdisks`,`slot`,`picture`,`desc`,`top`,`left`) VALUES ('".$name."','".$lab_id."','".$ios."','".$vmdisks."','".$slots."','".$picture."','".$conf_id."','".$top."','".$left."')";

    $result = api_sql_query ( $sql, __FILE__, __LINE__ );

    if($result){ 
        //清除旧的拓扑
        $sql1="update  `labs_labs`  set  `netmap`='' where  `name`='".$lab_id."'";
        $re=api_sql_query($sql1,__FILE__,__LINE__);

    }
 
         tb_close ();
 
}
if(isset($_GET['lab_id'])){ 
    $sql="select `name` from `labs_labs`  where `id` = '".intval(getgpc("lab_id"))."'";
    $default['lab_id']=DATABASE::getval($sql);
}
$form->setDefaults ($default);
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>
