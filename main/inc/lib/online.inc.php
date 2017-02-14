<?php 
/**
 * 写入在线用户表
 * @param unknown_type $uid
 * @param unknown_type $statistics_database
 */
function LoginCheck($uid) {
    global $_course;
    $online_table = Database::get_statistic_table ( TABLE_STATISTIC_TRACK_E_ONLINE );
    if (! empty ( $uid )) {
        $online_user_sql = mysql_query('select login_user_id,login_date,login_id from '.$online_table.' where login_user_id='.$uid);
        $online_user = Database::fetch_row( $online_user_sql);
        $login_ip = real_ip ();

        if(!empty($online_user[1]) && ( time() - strtotime($online_user[1]) ) > ONLINE_TIME){
               session_destroy();
               mysql_query('delete from '.$online_table.' where login_user_id='.$uid);
               $user_session_file = ini_get('session.save_path').'/sess_'.$online_user[2];
               chmod($user_session_file,0777);
               unlink( $user_session_file );
               rm_user_vm($uid);
               header('Location:'.WEB_ROOT.'portal/sp/login.php');exit;
        }

        if(empty($online_user[0])){
            $query = "INSERT IGNORE INTO " . $online_table . " (login_id,login_user_id,login_date,login_ip) VALUES ('" . session_id () . "',$uid,NOW(),'$login_ip')";
            @api_sql_query($query);
            return false;
        }

        if ($_course) {
            $query = "update " . $online_table . " set login_date=NOW(),course='{$_course['id']}' where login_user_id=".$uid;
        } else {
            $query = "update " . $online_table . " set login_date=NOW() where login_user_id=".$uid;

            if(intval($_SESSION['point_out_status']) === 1){
                rm_user_vm($uid);
                $_SESSION['point_out_status']=0;
                $_SESSION['point_out_cancel']=0;
            }
        }

        @api_sql_query ( $query, __FILE__, __LINE__ );
    }
}

/*
 * 关闭单个用户的所有虚拟机
 * */

function rm_user_vm($uid){
    $sqlb = 'select count(*) from vmtotal';
    $res = Database::getval( $sqlb, __FILE__, __LINE__ );
    if($res){
        $sql = "select `vmid`,`addres`,`proxy_port`,`user_id`,`lesson_id`,`stime`  FROM  `vmtotal` where `user_id`= '{$uid}' and  `manage`='0'";
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        $vm= array ();
        while ($vm = Database::fetch_row ( $res)) {
            $vms [] = $vm;
        }
        if($vms){

                foreach ( $vms as $k1 => $v1){
                    $vmid = $v1[0];
                    $vmaddres = $v1[1];
                    $proxy_port = $v1[2];
                    $lesson = $v1[4];
                    $stime = $v1[5];
                    if(!empty($vmid) && !empty($vmaddres)){

                        $output = "sudo -u root /usr/bin/ssh root@".$vmaddres." /sbin/cloudvmstop.sh ".$vmid." ".$uid;
                        $output1 = "sudo -u root  /sbin/cloudvncstop.sh ".$vmid." ".$uid;
                        usleep(rand(0,1500));
                        exec($output);
                        exec($output1);

                        $sqla = "delete  FROM  `vmtotal` where `user_id`= ".$uid." and `proxy_port`='".$proxy_port."' and `lesson_id`='".$lesson."' and `vmid`='".$vmid."'";
                        $del_res=@api_sql_query ( $sqla, __FILE__, __LINE__ );
                        $isport="sudo -u root   /sbin/cloudhub.sh del ".$proxy_port." ".$uid;
                        if($proxy_port){
                            exec($isport);
                        }
                        if($del_res){
                            $endtime=  date("Y-m-d H:i:s",time());
                            $vm_log="UPDATE `vmdisk_log` SET `end_time`='".$endtime."'  where  `user_id`=".$uid."  and  `vmid`=".$vmid." and `start_time`='".$stime."'";
                            @api_sql_query ( $vm_log, __FILE__, __LINE__ );
                        }
                    }

                }

        }
    }
}
/**
 * 删除在线用户表中的一条记录
 * @param unknown_type $user_id
 */
function LoginDelete($user_id) {
	$online_table = Database::get_statistic_table ( TABLE_STATISTIC_TRACK_E_ONLINE );
	$query = "DELETE FROM " . $online_table . " WHERE login_user_id = '" . Database::escape_string ( $user_id ) . "'";
	@api_sql_query ( $query, __FILE__, __LINE__ );
}

/**
 * 在线用户列表
 * @todo remove parameter $statistics_database which is no longer necessary
 */
function WhoIsOnline($uid, $statistics_database, $valid) {
	global $restrict_org_id;
	$track_online_table = Database::get_statistic_table ( TABLE_STATISTIC_TRACK_E_ONLINE );
	$user_table = Database::get_main_table ( TABLE_MAIN_USER );
	$view_user_dept = Database::get_main_table ( VIEW_USER_DEPT );
	
	if (api_is_platform_admin ()) {
		$query = "SELECT login_user_id,login_date,login_id,t2.* FROM " . $track_online_table . " AS t1," .
            $view_user_dept . " AS t2 WHERE t1.login_user_id=t2.user_id AND DATE_ADD(login_date,INTERVAL $valid MINUTE) >= NOW()  ";
	} else {
		$query = "SELECT login_user_id,login_date,login_id,t2.* FROM " . $track_online_table . " AS t1 LEFT JOIN
		$view_user_dept AS t2 ON t1.login_user_id=t2.user_id WHERE t2.org_id='" . $restrict_org_id . "'
				AND DATE_ADD(login_date,INTERVAL $valid MINUTE) >= NOW()";
	}
	//echo $query;
	$result = @api_sql_query ( $query, __FILE__, __LINE__ );
	return api_store_result ( $result );
}

function get_online_uesr_list($valid = 3) {
	global $restrict_org_id;
	$track_online_table = Database::get_statistic_table ( TABLE_STATISTIC_TRACK_E_ONLINE );
	$query = "SELECT login_user_id,login_date FROM " . $track_online_table . " WHERE DATE_ADD(login_date,INTERVAL $valid MINUTE) >= NOW()  ";
	$result = Database::get_into_array2 ( $query, __FILE__, __LINE__ );
	return $result;
}
