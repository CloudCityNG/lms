<?php
include_once ("../inc/global.inc.php");
$fid=$_POST['class'];
$ffid=  intval($fid);
   function classsun($fid){
       $query=mysql_query('select count(*) from tbl_class where fid='.$fid);
       $claarr=mysql_fetch_row($query);
       if($claarr[0] > 1){
           $calque=mysql_query('select id from tbl_class where fid='.$fid.' order by id desc limit 2');
           while($calrow=mysql_fetch_row($calque)){
               $calrows[]=$calrow;
           }
           $fid=classsun($calrows[1][0]);
       }
       return $fid;
   }
   $flarr=classsun($ffid);
   echo $flarr;