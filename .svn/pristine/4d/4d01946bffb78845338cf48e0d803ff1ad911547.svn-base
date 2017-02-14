<?php
include_once ("../../main/inc/global.inc.php");
$fname=$_GET['fname'];
$mp4path=URL_ROOT."/www".URL_APPEDND."/storage/"; 
$filename=$mp4path.'snapMp4/'.$fname.'.mp4';
function delfun($filename){
    if(file_exists($filename)){
        $iss=unlink($filename);
        if(!$iss){
            delfun($filename);
        }else{
             return true;
        }
    }
}
if(file_exists($filename)){
    $isok=delfun($filename);
    echo 'ok';
}