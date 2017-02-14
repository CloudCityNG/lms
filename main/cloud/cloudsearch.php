<?php
header("content-type:text/html;charset=utf-8");

require_once ('../inc/global.inc.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

$system = trim(getgpc('system','G'));
$nicnum = intval(getgpc('nicnum','G'));
$manage = intval(getgpc('manage','G'));
$userId = $_SESSION['_user']['user_id'];
$nomachine = (int)$_GET['nomachine'];
$cid = (int)$_GET['cid'];
if($nomachine === 1){
    $nomachine_str = '&nomachine=1';
}else{
    $nomachine_str = null;
}
$sql = "select `vmid`,`addres`,`proxy_port`,`lesson_id`,`stime` FROM  `vmtotal` where `user_id`= '{$userId}' and  `manage`='0'";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$vm= array ();
while ($vm = Database::fetch_row ( $res)) {
    $vms [] = $vm;
}
if(count($vms)) {
    foreach ($vms as $k1 => $v1) {
        $vmid = $v1[0];
        $vmaddres = $v1[1];
        $proxy_port = $v1[2];
        $lesson = $v1[3];
        $stime = $v1[4];
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

                
                $sqle = "select `id` FROM  `vmtotal` where `user_id`= '{$userId}' and  `manage`='0' and `lesson_id`='".$lesson."'";
                $rese = api_sql_query_array ( $sqle, __FILE__, __LINE__ );
                 
                $sqlv = "delete  FROM  `vm_rel_exam` where `vm_id`= " . $rese[0]['id'] . " ";
                $e = @api_sql_query($sqlv, __FILE__, __LINE__);
                
                
                
                $sqla = "delete  FROM  `vmtotal` where `user_id`= " . $userId . " and `proxy_port`='" . $proxy_port . "' and `lesson_id`='" . $lesson . "' and `vmid`='" . $vmid . "'";
                $r = @api_sql_query($sqla, __FILE__, __LINE__);
            }
        }
    }
}
$local_addres = gethostbyname($_SERVER['SERVER_NAME']);
header("Location: http://$local_addres".URL_APPEDND."/main/cloud/cloudvmstart.php?system=$system&nicnum=$nicnum&manage=0&cid=$cid".$nomachine_str."&user_id=".$_SESSION['_user']['user_id']);exit;