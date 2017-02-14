<?php
include_once ("../inc/global.inc.php");

$user_id=$_GET['teamid'];
$one=$_GET['one'];
//退出战队
if($one === 'one'){
    $user_id=intval($user_id);
    $one_query=mysql_query('update user set teamId=0 where user_id='.$user_id);
    if($one_query){
        $_SESSION['_user']['teamId']=0;
        echo 'location';exit;
    }else{
        echo 'err';
    }
}
//解散战队
if($user_id == 'all' && $one=='casual'){
    
        $teamId=$_SESSION['_user']['teamId'];
        $del_query=mysql_query('delete from tbl_team where id='.$teamId);
        if($del_query){
            
                $all_up_query=mysql_query('update user set teamId=0 where teamId='.$teamId);
                if($all_up_query){
                    $_SESSION['_user']['teamId']=0;
                    echo 'location';exit;
                }else{
                    echo 'err';exit;
                }
                
        }else{
               echo 'err';exit;
        }
        
}else if($one == 'casual'){
    $user_id=intval($user_id);

//删除队员
$team_arr=mysql_fetch_row(mysql_query('select teamId from user where user_id='.$user_id));
$teamId=$_SESSION['_user']['teamId'];
if($team_arr[0] === $teamId){
    $up_query=mysql_query('update user set teamId=0 where user_id='.$user_id);
    if($up_query){
        mysql_query('delete from tbl_cation where teamId='.$teamId.'');
        echo 'ok';exit;
    }else{
        echo 'err';exit;
    }
}else{
        echo 'err';exit;
}
}else{
       echo 'err';exit;
}