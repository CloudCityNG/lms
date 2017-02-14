<?php
include_once ('../inc/global.inc.php');
$str=getgpc('str','G');
$eventState=getgpc('eventState','G');
$matchId=getgpc('matchId','G');
$user=$_SESSION['_user']['user_id'];
$strTime=time();
$strarr=explode(',', $str);
$ii=1;
for($i=1;$i<count($strarr);$i++){
    $id_row=mysql_fetch_row(mysql_query('select id from tbl_event where examId='.$strarr[$i].' and matchId='.$matchId));
    if(!$id_row){
        if($ii==1){
            $delimit='';
        }else{
            $delimit=',';
        }
       $sqllist.=$delimit."('', $strarr[$i],$eventState,$user,'',$strTime,$matchId)";
       $ii++;
    }
}
 $sql ="INSERT INTO tbl_event (`id`,`examId` ,`eventState`,`isUser`,`isShow`,`sTime`,`matchId`) VALUES ".$sqllist;
 mysql_query($sql);
