<?php
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
header("content-type:text/html;charset=utf-8");

require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
$table_net_devices = Database::get_main_table (net_devices);
 
$id = intval($_GET ['id']);
if(isset($id)){
    $sql="select * from $table_net_devices where id = '".$id."'";

    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    while($ss = Database::fetch_array ( $res )){
        $default = $ss;
    }
}

$default_slot=explode(';',$default['slot']);    
 
$sql="select `slot_number` from `labs_ios` where `name`='".$default['ios']."'";
$get_slotnumber=DATABASE::getval($sql,__FILE__,__LINE__);

//slot=0
$Sql_slot_0 = "select * from `labs_mod` where `type` like '%".$default['ios']."%' and slot0 =0";
$result_0 = api_sql_query ( $Sql_slot_0, __FILE__, __LINE__ );
$vm_0= array ();
while ( $vm_0 = Database::fetch_row ( $result_0) ) {
    $vms_0[] = $vm_0[1];
    $vs_0[] = $vm_0[0];
}
$array_ab_0=array_combine($vs_0,$vms_0);     
$area_option_0 = "";
$area_option_0 .= "<option value='NO'>NULL</option>";
foreach ($array_ab_0 as $k => $v ){
    $area_option_0 .= "<option value='$v'>$v</option>";
}


//slot=1
$Sql_slot_1 = "select * from `labs_mod` where `type` like '%".$default['ios']."%' and slot0 =1";
$result_1 = api_sql_query ( $Sql_slot_1, __FILE__, __LINE__ );
$vm_1= array ();
while ( $vm_1 = Database::fetch_row ( $result_1) ) {
    $vms_1[] = $vm_1[1];
    $vs_1[] = $vm_1[0];
}
$array_ab=array_combine($vs_1,$vms_1);      
$area_option = "";
$area_option .= "<option value='NO'>NULL</option>";
foreach ($array_ab as $k => $v ){
    $area_option .= "<option value='$v'>$v</option>";
}
 
$slot_default_value='';
for($i=0;$i<$get_slotnumber;$i++){
    if($area_option=="<option value='NO'>NULL</option>"){
        $area_option=$area_option_0;
    }
    if($i==0)
    {
        $slot_default_value.='slot'.$i.'<select id="slot'.$i.'" name="slot'.$i.'">'.$area_option.'</select>&nbsp;';
    }else{
        $slot_default_value.='slot'.$i.'<select id="slot'.$i.'" name="slot'.$i.'">'.$area_option_0.'</select>&nbsp;';
    }

}
 
$htmlHeadXtra [] = '
<script src="../syllabus/jquery.js" type="text/javascript"></script>
<script language="JavaScript" type="text/JavaScript">

var name = "ios_name";
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
</script>';


$form = new FormValidator ( 'labs_device','POST','net_router_edit.php?id='.$id,'');
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
arsort($ios);

$picture = array('cloud'=>'Cloud','desktop'=>'Desktop','framerelay'=>'Frame Relay Switch','l3switch'=>'L3 Switch','mpls'=>'MPLS Router','router'=>'Router','server'=>'Server','switch'=>'Switch');
$form->addElement ( 'select', 'ios', "设备型号", $ios,array ('id' => "ios_name", 'style' => "width:30%;height:22px;",'onChange' => "getarea()" ) );
$slots='<tr class="containerBody"><td class="formLabel">模块设置</td><td id ="modules">'.$slot_default_value.'</td></tr>';
$form->addElement('html',$slots);
$form->addElement ( 'select', 'picture', "设备类型", $picture,array ('maxlength' => 50, 'style' => "width:30%;height:22px;" ) );
$form->addElement ( 'textarea', 'desc', "描述", array ('style' => "width:65%;height:150px;",'class'=>"inputText") );

$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->setDefaults ($default);
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {

    $labs  = $form->getSubmitValues ();
    $name    = $labs['name']; 
    $ios  = $labs['ios'];
    $picture  = $labs['picture'];
    $conf_id  = $labs['desc'];
    $slots='';
    if(isset($labs['slot_number']) && $labs['slot_number']!=='' && $labs['slot_number']!=='0'){
        for($i=0;$i<$labs['slot_number'];$i++){
            $slot='slot'.$i;
            if($labs[$slot]==''){
                $slots.='';
            }else{
                $slots.=$labs[$slot].';';
            }
        }
    }else{
        $sql="select `slot_number` from `labs_ios` where `name`='".$labs['ios']."'";
        $get_slotnumber=DATABASE::getval($sql,__FILE__,__LINE__);

        for($i=0;$i<$get_slotnumber;$i++){
            $slot='slot'.$i;
            if($labs[$slot]==''){
                $slots.='';
            }else{
                $slots.=$labs[$slot].';';
            }
    }}

    $sql = "UPDATE  `net_devices` SET  `name` =  '".$name."',
    `ios` =  '".$ios."',
    `slot` =  '".$slots."',
    `picture` =  '".$picture."',
    `desc` =  '".$conf_id."'
     where `id` =".$id;
     $result = api_sql_query ( $sql, __FILE__, __LINE__ );
     if($result){
         $sql="UPDATE  `course_connection_vmdisk` SET  `net_dev_name` =  '".$name."'  where  `net_dev_id`=".$id." and  `type`=2";
         api_sql_query ( $sql, __FILE__, __LINE__ );
     }
     tb_close ( );
}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
