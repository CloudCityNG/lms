<?php
include_once ("../inc/global.inc.php");
$name=getgpc('name','P');
$class=getgpc('class','P');
$class=intval($class);
if(!empty($name)){
 
     $sql="insert tbl_class(className,fid)values('{$name}',$class)";
     $query=mysql_query($sql);
     if($query){
         $id=mysql_insert_id();
         echo $id.','.$class.','.$name;exit;
     }
 
}else{
    echo 'err';exit;
}

