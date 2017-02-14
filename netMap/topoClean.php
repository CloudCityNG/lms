<?php
header("content-type:text/html;charset=utf-8");
include_once ("../main/inc/global.inc.php");

$cid=(int)$_COOKIE['cid']; 
$action=trim($_COOKIE['actionss']);
if($action=='cleanMap' && $cid!==''){
    $sql="UPDATE  `".DB_NAME."`.`course` SET  `netMap` =  '' WHERE  `code` =".$cid;   
    @api_sql_query($sql,__FILE__,__LINE__); 
}
api_redirect("topoDesign.php?action=design&cidReq=".$cid);
?>