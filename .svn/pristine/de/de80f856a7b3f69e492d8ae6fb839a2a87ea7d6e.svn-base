<?php
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
header("content-type:text/html;charset=utf-8");

require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php'); 
$cid=  getgpc("cidReq"); 
$occupation_id=  getgpc('occupation_id');
$act=  getgpc('action');
$htmlHeadXtra [] = '
<script src="../syllabus/jquery.js" type="text/javascript"></script>
<script language="JavaScript" type="text/JavaScript">

//var name = "ios_name";

function getarea(){
 var region_id = $("#ios_name").val();//获得下拉框中大区域的值

 if(region_id != ""){
  $.ajax({
  type: "post",
  url: "../router/device_check.php",
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
              url: "../router/device_check.php",
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
              url: "../router/device_check.php",
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

$form = new FormValidator ( 'labs_device','POST','net_router_add.php');
$form->addElement ( 'html', '<div style="margin-top:2px;"></div>');
$form->addElement ( 'text', 'name', "名字", array ('maxlength' => 50, 'style' => "width:30%", 'class' => 'inputText' ) );
$form->addRule ( 'name', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'name', get_lang ( '最大字符长度为30' ), 'maxlength', 30 );

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
 
$picture = array('cloud'=>'Cloud','desktop'=>'Desktop','framerelay'=>'Frame Relay Switch','l3switch'=>'L3 Switch','mpls'=>'MPLS Router','router'=>'Router','server'=>'Server','switch'=>'Switch');
 
$form->addElement ( 'select', 'picture', "设备类型", $picture,array ('id'=>"device_type",'maxlength' => 50, 'style' => "width:30%;height:22px;",'onChange'=>"getarea1()" ) );
$form->addElement('html','<tr id="lab_vm0" class="containerBody"><td class="formLabel">虚拟模板</td><td id ="lab_vm"></td></tr>');
$form->addElement('html','<tr class="containerBody"><td class="formLabel">设备型号</td><td id ="ios_nm"></td></tr>');
$form->addElement('html','<tr class="containerBody"><td class="formLabel">模块设置</td><td id ="modules"></td></tr>');
$form->addElement ( 'textarea', 'desc', "描述", array ('style' => "width:65%;height:150px;",'class'=>"inputText") );
$form->addElement("hidden","ccode",$cid);
$form->addElement("hidden","occupation_id",$occupation_id);
$form->addElement("hidden",'action',$act);
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );
 
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );

if ($form->validate ()) {
    $labs  = $form->getSubmitValues ();
    $name    = $labs['name']; 
    $ios  = $labs['ios_name'];
    $vmdisks=$labs['vm_name'];
    $picture  = $labs['picture'];
    $conf_id  = $labs['desc'];
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
    $ccode=$labs['ccode'];
    $occupation_id=$labs['occupation_id'];
    $act=$labs['action'];
    if($act=="occupat_rel_vmdisk" ){
        $sql = "INSERT  INTO  `net_devices` (`name`,`ios`,`vmdisks`,`slot`,`picture`,`desc`,`status`) VALUES ('".$name."','".$ios."','".$vmdisks."','".$slots."','".$picture."','".$conf_id."','".$occupation_id."')";
    }else{
         $sql = "INSERT  INTO  `net_devices` (`name`,`ios`,`vmdisks`,`slot`,`picture`,`desc`,`status`) VALUES ('".$name."','".$ios."','".$vmdisks."','".$slots."','".$picture."','".$conf_id."','".$ccode."')";
    }
    $re=api_sql_query ( $sql, __FILE__, __LINE__ );
    if($re){
          if($act=="occupat_rel_vmdisk"){   
                $router_id=DATABASE::getval("select  `id`  from  `net_devices`  where  `name`='".$name."'  and  `status`='".$occupation_id."'");
                $sql="INSERT INTO `skill_occupation_vmdisk`( `occupat_id`,`vm_id`,`vm_type`) VALUES ({$occupation_id},{$router_id},2)";
                api_sql_query($sql);
            }else{
                $cname=DATABASE::getval("select  `title`  from `course`  where `code`='".$ccode."'");
                $router_id=DATABASE::getval("select  `id`  from  `net_devices`  where  `name`='".$name."'  and  `status`='".$ccode."'");
                $sql="INSERT INTO `course_connection_vmdisk`( `cid`, `cname`, `net_dev_id`, `net_dev_name`,`type`) VALUES ('{$ccode}','{$cname}',{$router_id},'{$name}',2)";
                api_sql_query($sql);
            }
    }
   
     tb_close ();
}

Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>
