<?php
include_once ("../../inc/global.inc.php");

 $sql_where = "";
if (is_not_blank ( $_POST['theweek'] )) { 
    $oneweek=date("Y-m-d H:i:s",time()-3600*24*7);
    $sql_where .= "  and  (start_time < '".date("Y-m-d H:i:s",time())."'  and  start_time >= '".$oneweek."')"; 
}else if(is_not_blank ( $_POST['themonth'] )){
    $oneweek=date("Y-m-d H:i:s",time()-3600*24*30);
    $sql_where .= "  and  (start_time < '".date("Y-m-d H:i:s",time())."'  and  start_time >= '".$oneweek."')"; 
}
if($_POST['action']=='show' &&  $_POST['e']){
    $e= getgpc('e');
    $e= str_replace("&lt;br/&gt;", "-", $e);
    $sql="select  count(user_id) as user_num,user_id  from  vmdisk_log ";
    if($e){
        $sql.=" where  lesson_id=".DATABASE::getval("select  code  from  course  where  title='".$e."'");
    }
    if($sql_where){
        $sql.=$sql_where;
    }
    $sql.=" group by  user_id  order by  count(user_id) desc";
    $res= api_sql_query_array_assoc($sql);
    $ress='';
    foreach ($res as $key=>$value){  
      $row='';
      $row['user_id']=DATABASE::getval("select  `username`  from  `user`  where  `user_id`=".$value['user_id']);
      $row['user_num']=$value['user_num']; 
      $ress[]=$row;
    } 
    echo  api_json_encode($ress);
}
if($_POST['action']=='shows' &&  $_POST['es']){
    $es= getgpc('es'); 
    $es=  str_replace("时", "", $es);
    $ess=explode("--", $es);
    $sql="select  lesson_id,count(user_id) as user_num,start_time  from  vmdisk_log  where  1 ";
    if($sql_where){
        $sql.=$sql_where;
    }
    $sql.="  group  by  lesson_id ";
    $res1= api_sql_query_array_assoc($sql);
    foreach ($res1 as $k=>$val){
        $v=end(explode(" ", $val['start_time']));
        $va=explode(":", $v);
        $res1[$k]['start_time']=$va[0];
    }  
    $ress1='';
    foreach ($res1 as $value1){ 
        $row1='';
        if($value1['start_time']>=$ess[0] && $value1['start_time']<$ess[1]){
            $row1['user_num']=$value1['user_num'] ;
            $r=explode('-', DATABASE::getval("select  `title`  from  `course`  where  `code`=".$value1['lesson_id']));
            $row1['lesson_id']=$r[1];
            $ress1[]=$row1;
        } 
    } 
    echo  api_json_encode($ress1);
}
?>
