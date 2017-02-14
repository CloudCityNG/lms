<?php
$language_file = 'admin';
$cidReset = true;
include_once ("../main/inc/global.inc.php");
header("content-type:text/html;charset=utf-8"); 

$data=trim($_COOKIE['data']);
$id=$_COOKIE['c_code']; 
if($id){
    
    $rep1=array('}','"','px',' ');
    $rep2=array('','','','');
     
    $data = str_replace('\\', '', $data);
    $data = str_replace('\"', '"', $data);
    $data = str_replace('"{', '{', $data);
    $data = str_replace('}"', '}', $data);
    //netmap
    $topo_data=  explode('"netMap":[', $data);
    $net_var=   str_replace(']}', '', $topo_data[1]);
    
    //////offset-desc   
    $dev_info=str_replace('{"nodes":[', '', $topo_data[0]);  
    $dev_info=str_replace('],', '', $dev_info);  
    $devices_arr= explode('},{', $dev_info);  
    $devs_offset='';
    foreach($devices_arr as $key=>$node){   
        if($key==0){
            $node=ltrim($node,'{');   
        }else if($key==(count($devices_arr)-1)){
            $node=str_replace('}}', '}', $node);   
        }else { 
            $node=$node;    
        }
        $node=str_replace('"offset":{', '', $node);
        $node=str_replace('"nodeDesc":{', '', $node);
        $node=str_replace('}', '', $node);
        
        $node_arr=explode(',', $node);
        $nodeid=str_replace(array('"nodeId":"','"'),array('',''), $node_arr[0]);
        $left=str_replace(array('"left":"','px"'),array('',''), $node_arr[5]);
        $top=str_replace(array('"top":"','px"'),array('',''), $node_arr[6]);
        $desc=str_replace(array('"desc":"','"'),array('',''), $node_arr[7]);
        
        $devs_offset[$nodeid]=array( 
                'top'=>$top,
                'left'=>$left,
                'desc'=>$desc
                    ) ; 
    }
    $devices_offset=serialize($devs_offset);
    //////////end
   if($id){
           if($devices_offset){
               $sql1="UPDATE  `".DB_NAME."`.`course` SET `devices_offset` =  '".$devices_offset."'  WHERE  `code` =".$id;
               $r1= @api_sql_query($sql1,__FILE__,__LINE__);
 
           }
           
            $sql="UPDATE  `".DB_NAME."`.`course` SET  `netMap` =  '".$net_var."'  WHERE  `code` =".$id;
            $r2= @api_sql_query($sql,__FILE__,__LINE__);
        }
   api_redirect("topoDesign.php?action=design&cidReq=".$id);
}else{
    echo "<p style='color:red'>您的访问非法！</p>";
}
?>