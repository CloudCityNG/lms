<?php
header("content-type:text/html;charset=utf-8");
include_once ("../main/inc/global.inc.php");

$topoId=(int)$_COOKIE['tid'];
$action=trim($_COOKIE['actionss']);
if($action=='cleanMap' && $topoId!==''){
    $sql="UPDATE  `".DB_NAME."`.`labs_labs` SET  `netmap` =  '' WHERE  `id` =".$topoId;
    if(api_is_admin()){
        @api_sql_query($sql,__FILE__,__LINE__);
    }
}
api_redirect("topoDesign.php?action=design&id=".$topoId);
?>