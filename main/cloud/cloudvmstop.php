<?php
/**
 * This is exit page
 * by changzf
 * on 2012/06/09
 */
header("content-type:text/html;charset=utf-8");

require_once ('../inc/global.inc.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

$uid=intval(getgpc('user_id','G'));

$sql = "select `vmid`,`addres`,`proxy_port`,`user_id`,`lesson_id`,`stime` FROM  `vmtotal` where `user_id`= '{$uid}' and  `manage`='0'"; 
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$vm= array (); 
while ($vm = Database::fetch_row ( $res)) { 
    $vms [] = $vm; 
}
foreach ( $vms as $k1 => $v1){ 
        $vmid = $v1[0]; $vmaddres = $v1[1]; $proxy_port = $v1[2]; $userId = $v1[3]; $lesson = $v1[4]; $stime = $v1[5];
        if($vmid && $vmaddres)
        {
            $platforms = file_get_contents(URL_ROOT.'/www'.URL_APPEDND.'/storage/DATA/platform.conf');
            $platform_array = explode(':',$platforms);
            $platform = intval(trim( $platform_array[1] ));

            if($platform>3){
                $output = "sudo -u root /usr/bin/ssh root@".$vmaddres." /sbin/cloudvmstop.sh ".$vmid." ".$uid;
                $output1 = "sudo -u root  /sbin/cloudvncstop.sh ".$vmid." ".$uid;
		
		        usleep(rand(0,1500));
                exec($output,$execinfo);
		       $execinfo1 = $execinfo[0];
		       if($execinfo1 !== 'ok')
               {
                  exec($output,$execinfo);
               }
                  exec($output1);

               if($proxy_port)
               {
                    $isport = "sudo -u root   /sbin/cloudhub.sh del ".$proxy_port." ".$uid;
                    exec($isport);
               }
                
           
	           $endtime = date("Y-m-d H:i:s",time());
               $vm_id = $vmid;
               $vm_log = "UPDATE `vmdisk_log` SET `end_time`='".$endtime."'  where  `user_id`=".$uid."  and  `vmid`=".$vm_id." and `start_time`='".$stime."'";
               @api_sql_query ( $vm_log, __FILE__, __LINE__ );
	
                $sqla = "delete  FROM  `vmtotal` where `user_id`= ".$uid." and `proxy_port`='".$proxy_port."' and `lesson_id`='".$lesson."' and `vmid`='".$vmid."'";
                @api_sql_query ( $sqla, __FILE__, __LINE__ );
            
            } 
        }
}

setcookie('webcs_test_cookie','', time() - 3600 , URL_APPEDND.'/');
setcookie('zlms-sid','', time() - 3600 , URL_APPEDND);
setcookie('zlms-sid','', time() - 3600 , URL_APPEDND.'/');
setcookie('lms_login_name','', time() - 3600 , URL_APPEDND);
setcookie('PHPSESSID','', time() - 3600 , '/');
session_destroy();

$true = base64_encode('true');
 
$a = explode("/", $_SERVER['HTTP_REFERER']);
$b = explode("?", end($a));
if($b[0] != "auto.php")
{
      if(api_get_setting('enable_modules', 'clay_oven') == 'true')
      {
          header('Location:../../portal/sp/cn/login.php?logout=true');exit;
      }else{
          header('Location:../../portal/sp/login.php?logout=true');exit;
      }

}
?>
