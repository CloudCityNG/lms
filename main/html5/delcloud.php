<?php
require_once ('../inc/global.inc.php');
if(!api_get_user_id ()){
    exit;
}else{
    $user_id = $_SESSION['_user']['user_id'];
    $lession_id = intval($_GET['lession']);
    $sql = "select `vmid`,`addres`,`proxy_port`,`lesson_id`,`stime` FROM  `vmtotal` where `user_id`= '{$user_id}' and  `lesson_id`=$lession_id and `manage`='0'";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $vmrow = mysql_fetch_row($res);
    if($vmrow[4]){
        $total_hours = (api_get_setting("lnyd_switch")=="true"?'1':'4');
        $endtime = strtotime($vmrow[4])+(intval($total_hours)*60*60);
        $nowtime = time();
        if($nowtime >= $endtime){
            $vmid = $vmrow[0];
            $vmaddres = $vmrow[1];
            $proxy_port = $vmrow[2];
            $lesson = $vmrow[3];
            $stime = $vmrow[4];
            if ($vmid && $vmaddres) {
                $platforms = file_get_contents(URL_ROOT . '/www' . URL_APPEDND . '/storage/DATA/platform.conf');
                $platform_array = explode(':', $platforms);
                $platform = intval(trim($platform_array[1]));

                if ($platform > 3) {
                    $output = "sudo -u root /usr/bin/ssh root@" . $vmaddres . " /sbin/cloudvmstop.sh " . $vmid . " " . $userId;
                    $output1 = "sudo -u root  /sbin/cloudvncstop.sh " . $vmid . " " . $userId;

                    usleep(rand(0, 1500));
                    exec($output, $execinfo);
                    $execinfo1 = $execinfo[0];
                    if ($execinfo1 !== 'ok') {
                        exec($output, $execinfo);
                    }
                    exec($output1);

                    if ($proxy_port) {
                        $isport = "sudo -u root   /sbin/cloudhub.sh del " . $proxy_port . " " . $userId;
                        exec($isport);
                    }

                    $endtime = date("Y-m-d H:i:s", time());
                    $vm_id = $vmid;
                    $vm_log = "UPDATE `vmdisk_log` SET `end_time`='" . $endtime . "'  where  `user_id`=" . $userId . "  and  `vmid`=" . $vm_id . " and `start_time`='" . $stime . "'";
                    @api_sql_query($vm_log, __FILE__, __LINE__);

                    $sqla = "delete  FROM  `vmtotal` where `user_id`= " . $userId . " and `proxy_port`='" . $proxy_port . "' and `lesson_id`='" . $lesson . "' and `vmid`='" . $vmid . "'";
                    $r = @api_sql_query($sqla, __FILE__, __LINE__);
                }
            }
        }
    }
}