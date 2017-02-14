<?php
$language_file = 'admin';
$cidReset = true;
include_once ("../main/inc/global.inc.php");
header("content-type:text/html;charset=utf-8"); 

$data=trim($_COOKIE['data']);
$id=(int)$_COOKIE['topoid'];
if($id){
    
    $rep1=array('}','"','px',' ');
    $rep2=array('','','','');
    
    //echo $id;
    $data = str_replace('\\', '', $data);
    $data = str_replace('\"', '"', $data);
    $data = str_replace('"{', '{', $data);
    $data = str_replace('}"', '}', $data);
    //netmap
    $topo_data=  explode('"netMap":[', $data);
    $net_var=   str_replace(']}', '', $topo_data[1]);
    $devices_var= $topo_data[0];
    $devices_arr= explode('}},{', $data);

    $net_arr = explode('","', $net_var);
    foreach($net_arr as $net_value){
       $net_var1 = explode('","', $net_value);
       $net_var2 = explode(' ', $net_var1[0]);
       foreach($net_var2 as $ss){
           $net_var3 = explode(':', $ss);
           $nodeIdArr[]=str_replace($rep1, $rep2, $net_var3[0]);
       }
    }
    $deviceArr=array_unique($nodeIdArr);
    foreach($devices_arr as $node){
        $device_arr=explode(',', $node);
        $nodeDesc_desc_var=$device_arr[7];
        $nodeDesc_tops_var=$device_arr[5];
        $nodeDesc_left_var=$device_arr[6];
        $nodeId=$device_arr[0];
         
        //desc
        $nodeId_arr = explode('":"', $device_arr[0]);
        $nodeId     = str_replace($rep1, $rep2, $nodeId_arr[1]); 
        //desc
        $nodeDesc_descs= explode('":"', $nodeDesc_desc_var);
        $nodeDesc_desc=str_replace('"', '', $nodeDesc_descs[1]);
        //top
        $devices_arr= explode(':', $nodeDesc_tops_var);
        $nodeDesc_topo=trim($devices_arr[2]);
        $nodeDesc_topo=str_replace($rep1, $rep2, $nodeDesc_topo); 
        //left
        $devices_arr= explode(':', $nodeDesc_left_var);
        $nodeDesc_left=trim($devices_arr[1]);
        $nodeDesc_left=str_replace($rep1, $rep2, $nodeDesc_left);
        
        
        if($nodeId){
           if($nodeDesc_desc OR $nodeDesc_topo OR $nodeDesc_left){
               $sql1="UPDATE  `".DB_NAME."`.`labs_devices` SET `top` =  '".$nodeDesc_left."',`left` =  '".$nodeDesc_topo."', `desc` =  '".$nodeDesc_desc."'  WHERE  `id` =".$nodeId;
               $r1= @api_sql_query($sql1,__FILE__,__LINE__);
//               echo $sql1."<hr>";
           }
        }
        
        if(in_array($nodeId, $deviceArr)){
            $devicesss= '包含该设备';
        }else{
            $no_link_dId[]=$nodeId;
        }
    }
    
 
if($no_link_dId){
    foreach($no_link_dId as  $did){
        $net1_var.=$did.';';
    }
}

    $sql="UPDATE  `".DB_NAME."`.`labs_labs` SET  `netmap` =  '".$net_var."',`diagram`='".$net1_var."'  WHERE  `labs_labs`.`id` =".$id;
    $r2= @api_sql_query($sql,__FILE__,__LINE__);

    tb_close ();
}else{
    echo "<p style='color:red'>您的访问非法！</p>";
}
?>