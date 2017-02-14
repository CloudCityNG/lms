<?php
header("content-type:text/html;charset=utf-8");
include_once ("../../inc/global.inc.php");

$cid=(int)$_COOKIE['occupat_id']; 
$action=trim($_COOKIE['actionss']);
if($action=='cleanMap' && $cid!==''){
    $sql="UPDATE  `".DB_NAME."`.`skill_occupation` SET  `netMap` =  '' WHERE  `id` =".$cid;   
    @api_sql_query($sql,__FILE__,__LINE__); 
}
api_redirect("design_topo.php?action=design&occupation_id=".$cid);
?>