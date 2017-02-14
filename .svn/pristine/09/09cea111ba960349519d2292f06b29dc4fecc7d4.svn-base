<?php
header("content-type:text/html;charset=utf-8");
$language_file = array ('admin', 'registration' );
$cidReset = true;
require ('../../inc/global.inc.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
        $device = "select * from device_type ";
        $result = api_sql_query($device, __FILE__, __LINE__ );
        while ( $rst = Database::fetch_row ( $result) ) {
            $ste [] = $rst;
        }
        foreach ( $ste as $k1 => $v1){
            foreach($v1 as $k2 => $v2){
                $arr[$k1][]  = $v2;
            }
        }
$initName = getgpc("initName","P");   
$initName =  urldecode($initName);

foreach ($arr as $k1 => $v1){
    if($initName == $arr[$k1][1]){    
        $sql = "SELECT name FROM vmdisk where CD_mirror!='' &&  category='".$arr[$k1][1]."'";
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        $vm= array ();
        while ( $vm = Database::fetch_row ( $res) ) {
            $vms [] = $vm[0];
        }
        $design = array_combine($vms,$vms);  
    }
}
  foreach ( $design as $v1){ 
            $ios.="<option value='$v1'>$v1</option>";
        }
     $designs="<select id='Flagnames' name='ios_name' onChange='getarea()' > ".$ios." </select>";
        $Interface = array();
        for($i = 1 ;$i <7 ;$i++){
            $Interface[$i] = $i;
        }
        $off_on = array('1' => '开启控制台','0' => '关闭控制台');
        $form = new FormValidator ( 'view','post','topodesign.php' );
        $form->addElement ('select','off_on','',$off_on,array ( 'style' => "width:100px;font-weight:bold", 'id' => 'off_on' ) );
        $form->addElement ( 'text', 'keywords', "<b>请输入设备标识关键字：</b>", array ( 'id' => 'keywords' ,onkeyup=>'checkdesign()' ) );
        $form->addElement ( 'html', '<div id="Flags">'.$designs.'</div>');
        $form->addElement ('select','InterNum','<b>请选择设备接口数:</b>',$Interface,array ( 'style' => "width:60px;font-weight:bold", 'id' => 'InterNum' ) );
        $form->addElement ('button', 'Determine' ,'确定',array ('id'=>'Determine','style' => "font-weight:bold" ) );
        $form->display ();
        echo '<input type="hidden" id="hidn" value="'.$initName.'" />';
?>

