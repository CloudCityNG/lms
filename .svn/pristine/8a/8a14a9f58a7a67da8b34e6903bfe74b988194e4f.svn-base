<?php
function create_run_device(){
    if(mysql_num_rows(mysql_query("SHOW TABLES LIKE labs_run_devices"))!=1){
        $sql_insert ="CREATE TABLE IF NOT EXISTS `labs_run_devices` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `course_name` varchar(256) CHARACTER SET utf8 NOT NULL,
            `labs_name` varchar(32) CHARACTER SET utf8 NOT NULL,
            `p_id` int(11) NOT NULL,
            `USERID` int(11) NOT NULL,
            `GROUPID` int(11),
            `LEADID` int(11),
            `PORT` int(11) NOT NULL,
            `DEVICEID` varchar(256) NOT NULL,
            `DEVICEDNAME` varchar(256) NOT NULL,
            `ROUTETYPE` varchar(256) NOT NULL,
            `ROUTEMOD` varchar(256) NOT NULL,
            `DEVICEDTYPE` varchar(256) NOT NULL,
            `status` int(11) NOT NULL,
            `uport`  varchar(256) CHARACTER SET utf8 NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";
        api_sql_query ( $sql_insert,__FILE__, __LINE__ );
    }
}
function create_bucket($name){
    if($name){
        /**当有该令牌桶却没有该令牌桶的数据表时要创建表---zd**/
        $sql="select `ranges` from `token_bucket` where `token_bucket_name`='".$name."'";
        $rangess =  Database::getval($sql);
        $ranges  =  unserialize($rangess);
        
        if(!$ranges[0]){
            $ranges1='1024';
        }else{
            $ranges1=$ranges[0];
        }
        if(!$ranges[0]){
            $ranges2='2048';
        }else{
            $ranges2=$ranges[1];
        }
        $ranges=$ranges1-1;

        /**判断建表**/
        if(mysql_num_rows(mysql_query("SHOW TABLES LIKE `".$name."`"))!=1){
            $sql_insert="CREATE TABLE if not exists `".DB_NAME."`.`$name`(
                `Pid` INT NOT NULL AUTO_INCREMENT ,
                `status` smallint(6),
                `values` varchar(256) ,
                PRIMARY KEY ( `Pid` )) ENGINE =MyISAM  charset=utf8 auto_increment=".$ranges;
            @api_sql_query ( $sql_insert,__FILE__, __LINE__ );
        }
        /**插入记录**/
        $count_sql= "select count(*) from  `".DB_NAME."`.`".$name."`";
        $bucket_count=DATABASE::getval($count_sql,__FILE__,__LINE__);
        if($bucket_count=='0'){
            for($pid =$ranges1 ;$pid <$ranges2;$pid++){
                $in_sql = "INSERT INTO  $name (`Pid`,`status`,`values`) values(".$pid.",'0','0');";
                @api_sql_query ( $in_sql, __FILE__, __LINE__ );
            }
        }
    }
}
//获取端口
function get_router_hub_port($hub_type){
    if($hub_type){
        create_bucket($hub_type);
        
        $sql2="SELECT `Pid` FROM ".$hub_type." where `status`=0 ORDER BY RAND() LIMIT 1";
        $bucket_port=DATABASE::getval($sql2,__FILE__,__LINE__);
        if($bucket_port){
            $sql3="UPDATE ".$hub_type." SET `values`= 1 WHERE `Pid`='".$bucket_port."'";
            $res3=api_sql_query ($sql3, __FILE__, __LINE__ );
            if($res3){
               return $bucket_port; 
            }else{
               return ''; 
            }
        }else{
            return '';
        } 
    }else{
        return '';
    }
}

function loading_labs($USERID,$topoid){
    create_run_device();
    if(!$USERID){
        $USERID=$_SESSION['_user']['user_id'];
    }
    $url='/tmp/mnt/iostmp/';
    $urlhome='/tmp/mnt/iostmp/'.$USERID;
    if(!file_exists($url.$USERID)){ 
        exec(" mkdir -p ".$url.$USERID);
    }
    
    $url_routercourse=glob(URL_ROOT."/www".URL_APPEDND."/storage/routecourses/".$topoid."-*.lib");
    $url_router=$url_routercourse[0];
    $url_user=$url.$USERID."/".$topoid; 
            
    $topoid=trim($topoid);
    $sql1="select * from `labs_labs` where id=".$topoid;
    $topo_data= api_sql_query_array_assoc($sql1,__FILE__,__LINE__);
    $id     =$topo_data[0]['id'];
    $name   =$topo_data[0]['name'];
    $netmap =$topo_data[0]['netmap'];
    $diagram=$topo_data[0]['diagram'];
    
    $netmap  =  str_replace('"', '', $netmap);
    $netmap  =  str_replace('E', '', $netmap);
    $netmap1 =  str_replace(" ", "_", $netmap);
    $netmap2 =  str_replace("\r\n", "_", $netmap1);
    $netmap3 =  str_replace(":", "A", $netmap2);
    $netmap4 =  str_replace("/", "B", $netmap3);
    $netmap4 =  str_replace("\n", "_", $netmap4);
    $netmap6 =  str_replace(",", "_", $netmap4);
    $netmap5 =  explode('_',$netmap6);
    
    $diagram_data=explode(';',$diagram);
    $diagram_data=array_unique($diagram_data);
    foreach($diagram_data as $k=>$v){
        if($v!==''){
            $netmap5[]=$v."A0B0";
        }
    } 
    array_pop($netmap5);
    foreach($netmap5 as $kk=>$net){
        $uport= get_router_hub_port('uporthub');
        if($i%2==0){
            $ehub.= $net."C".$uport."_";
        }else{
            $ehub.= $net."C".$uport."__";
        }
        $i++;
	if($uporta==''){
	    $uporta.= $uport;
	}else{
	   $uporta.=';'.$uport;
	}
	$deviceid=explode('A',$net);
	$deviceidnet=$deviceid[0];	
	$deviceidnetmod=$deviceid[1];	
        $values=$USERID."_".$name."_".$deviceidnet."_".$deviceidnetmod;
        $sql2="UPDATE `uporthub` SET  `status` =  '1', `values`='".$values."' WHERE  `Pid` ='".$uport."'";
        @api_sql_query($sql2,__FILE__,__LINE__);
    }
    $LINKDATA=$ehub;
    
    $sql3 = "select * FROM  `labs_devices` where `lab_id`='".$name."'";
    $devices_data = api_sql_query_array_assoc ( $sql3, __FILE__, __LINE__ );
    
    for($j=0;$j<count($devices_data);$j++){
        $PORT        = get_router_hub_port('tporthub');/**get tport**/
        $DEVICEID    = trim($devices_data[$j]["id"]);
        $DEVICEDNAME = trim($devices_data[$j]["name"]);
        $ROUTETYPE   = trim($devices_data[$j]["ios"]);
        $DEVICEDTYPE = trim($devices_data[$j]["picture"]);
        $slots       = trim($devices_data[$j]["slot"]);
        $slot        = str_replace(";","__",$slots);
        
        
        $sql4="select `filename`,`idle`,`type`,`ram`,`nvram` from `labs_ios` where `name`='".$ROUTETYPE."'";
        $iso_data=api_sql_query_array_assoc($sql4,__FILE__,__LINE__);
 
        $IOSFILENAME = $iso_data[0]["filename"];
        $idlepc      = $iso_data[0]["idle"];
        $ROUTEMOD    = $iso_data[0]["type"];
        $MEM         = $iso_data[0]["ram"];
        $NVRAM       = $iso_data[0]["nvram"]; 
        $p_id=''; 

        $sql_count="select count(*) from `labs_run_devices` where `labs_name`='".$id."' and `DEVICEID` ='".$DEVICEID."' and `USERID`='".$USERID."'";
        $devices=DATABASE::getval($sql_count,__FILE__,__LINE__);

        if($devices==0){
            $run_device_sql = "INSERT INTO `labs_run_devices` (`id`, `course_name`, `labs_name`, `p_id`, `USERID`, `PORT`, `DEVICEID`, `ROUTETYPE`, `ROUTEMOD`, `DEVICEDTYPE`,`DEVICEDNAME`,`status`) VALUES(NULL,'','".$id."','".$p_id."','".$USERID."','".$PORT."','".$DEVICEID."','".$ROUTETYPE."','".$ROUTEMOD."','".$DEVICEDTYPE."','".$DEVICEDNAME."','1');";
            @api_sql_query($run_device_sql,__FILE__,__LINE__);
        }else{
            $delete_port_sql="UPDATE  `labs_run_devices` SET  `p_id` =  '".$p_id."',`PORT`='".$PORT."',`ROUTETYPE`='".$ROUTETYPE."',`ROUTEMOD`='".$ROUTEMOD."',`DEVICEDTYPE`='".$DEVICEDTYPE."',`status`='1' WHERE `labs_name`='".$id."' and `DEVICEID` ='".$DEVICEID."' and `USERID`='".$USERID."'";
            @api_sql_query($delete_port_sql,__FILE__,__LINE__);
        }
        
        $update_port_sql="UPDATE  `tporthub` SET  `status` =  '1',`values`='".$USERID."_".$name."_".$DEVICEDNAME."' WHERE  `Pid` ='".$PORT."'";
        @api_sql_query($update_port_sql,__FILE__,__LINE__);

    	$sql_vmdisks="select `vmdisks` from `labs_devices` where `name`='".$DEVICEDNAME."' and `lab_id`='".$name."'" ;
        $sql_systemmod= DATABASE::getval($sql_vmdisks,__FILE__,__LINE__);
        
        $command='sudo -u root /sbin/cloudvmroute.sh  system='.$sql_systemmod.'___LABSNAME='.$topoid.'___USERID='.$USERID.'___PORT='.$PORT.'___DEVICEDTYPE='.$DEVICEDTYPE.'___DEVICEDNAME='.$DEVICEDNAME.'___DEVICEID='.$DEVICEID.'___ROUTETYPE='.$ROUTETYPE.'___IOSFILENAME='.$IOSFILENAME.'___ROUTEMOD='.$ROUTEMOD.'___MEM='.$MEM.'___NVRAM='.$NVRAM.'___idlepc='.$idlepc.'___LINKDATA='.$LINKDATA.'__slot='.$slot;
        $command =  str_replace("____", "___", $command);
        exec("echo $command '&' >> /tmp/www/$USERID'_'$topoid'.sh' ; chmod 777 /tmp/www/$USERID'_'$topoid'.sh' ");
        
    }
	$tophome = $urlhome."/".$topoid;
        if(!file_exists($tophome)){
            exec(" mkdir -p ".$urlhome);
		if(!empty($url_router)){
			$exec_var0="cd ".$urlhome."/;sudo -u root  tar -zxf ".$url_router." ".$topoid;
			exec($exec_var0);
		}
	}
    
        exec("/tmp/www/$USERID'_'$topoid'.sh' > /dev/null & ");
        exec("sleep 2 ;rm -rf  /tmp/www/$USERID'_'$topoid'.sh'");
    
    $uport_sql="UPDATE   `labs_run_devices` SET  `uport` = '".$uporta."'  WHERE  `USERID` ='".$USERID."' and  `labs_name`='".$id."'";
    @api_sql_query($uport_sql,__FILE__,__LINE__);
}

function stoping_labs($USERID,$topoid){
    if(!$USERID){
        $USERID=$_SESSION['_user']['user_id'];
    }
    $topoid=trim($topoid);
    $stop_sql="select `id`,`labs_name`,`USERID`,`DEVICEID`, `DEVICEDTYPE` , `PORT`,`uport` from `labs_run_devices` where `labs_name`='".trim($topoid)."' and `USERID`=".$USERID;
    $running_data=api_sql_query_array_assoc($stop_sql,__FILE__,__LINE__);

    foreach($running_data as $k=>$v){
        $d_id        = $v['id'];
        $labsName    = $v['labs_name'];
        $userId      = $v['USERID'];
        $deviceId    = $v['DEVICEID'];
        $DEVICEDTYPE = $v['DEVICEDTYPE'];
        $PORT        = $v['PORT'];
        $uports      = $v['uport'];
        $uports_array=explode(";",$uports);
        foreach($uports_array as $k1=>$v1){
            if($v1){
                $delete_uport_sql="UPDATE `uporthub` SET  `status` =  '0',`values`='' WHERE  `Pid` =".$v1;
                @api_sql_query($delete_uport_sql,__FILE__,__LINE__);
            }
        }
        if($d_id){
            $delete_sql="DELETE FROM `labs_run_devices` WHERE `labs_run_devices`.`id` = ".$d_id;
            @api_sql_query($delete_sql,__FILE__,__LINE__);
        }

        $deletecommand="sudo -u root /sbin/cloudvmroutestop.sh  ".$userId." ".$topoid." ".$userId."_".$topoid."_".$DEVICEDTYPE."_".$deviceId." ".$userId."_".$topoid."_".$PORT;
        exec("$deletecommand  >/dev/null &");

        //delete port
        if($PORT){
            $delete_tport_sql="UPDATE `tporthub` SET  `status` =  '0',`values`='0' WHERE  `Pid` =".$PORT;
            @api_sql_query($delete_tport_sql,__FILE__,__LINE__);
        }
    }
}

function tar_labs_conf($topoId,$USERID){
    if(!$USERID){
        $USERID=$_SESSION['_user']['user_id'];
    }
    $local_addres=trim($_SERVER['HTTP_HOST']);
    $url='/tmp/mnt/iostmp/';
    $urlhome='/tmp/mnt/iostmp/'.$USERID;
    $url_user=$url.$USERID;
    $time_dir=date("YmdHi");
    $fileName=$topoId.'_'.$USERID.'.lib';
    
    $url_router=URL_ROOT."/www".URL_APPEDND."/storage/routecourses/";
    
    $exec_var2="cd ".$urlhome.";sudo -u root rm -rf ".$fileName." ; sudo -u root tar -zcvf ".$fileName."  ".$topoId."; sudo -u root chmod 777 ".$fileName ."; sudo -u root cp -raf ".$fileName." ".$url_router;
    exec($exec_var2);
     
  
}