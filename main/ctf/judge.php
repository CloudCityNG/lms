<?php
include_once ('../inc/global.inc.php');

$judge=$_POST['judge'];
$caid=intval($_POST['id']);
if($judge === 'agree'){
    $agree_query=mysql_query('update tbl_cation set status=1 where id='.$caid);
    if($agree_query){
        $user_query=mysql_query('select user_id,teamId from tbl_cation where id='.$caid);
        $user_arr=mysql_fetch_row($user_query);
        $upuser_query=mysql_query('update user set teamId='.$user_arr[1].' where user_id='.$user_arr[0]);
        if($upuser_query){
           echo 'agree';exit;
        }
    }else{
        echo 'err';exit;
    }
}else if($judge === 'refusal'){
    $refusal_query=mysql_query('update tbl_cation set status=2 where id='.$caid); 
    if($refusal_query){
        echo 'refusal';exit;
    }else{
        echo 'err';exit;
    }
}
