<?php
include_once ("../../main/inc/global.inc.php");
$ff=trim($_POST['f']);
$mp4path=URL_ROOT."/www".URL_APPEDND."/storage/"; 
$filename=$mp4path.'snapMp4/'.$ff.'.mp4';
if(!file_exists($filename)){
    if($ff){
        $exec1= "cd ".URL_ROOT."/www".URL_APPEDND."/storage/snapshot/; sudo -u root /sbin/cloudfbstomp4.sh ".$ff.".fbs ".$mp4path."snapMp4/".$ff." ;";
        exec($exec1);
        echo URL_APPEDND;
    }
}else{
        echo URL_APPEDND;
}

