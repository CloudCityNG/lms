<?php
header("content-type:text/html;charset=utf-8");
require_once ('../../main/inc/global.inc.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');

$system = trim(getgpc('system','G')); 
$nicnum = intval(getgpc('nicnum','G')); 
$manages = intval(getgpc('manage','G'));
$stime=Date("Y-m-d H:i:s",time());
$userId = $_SESSION['_user']['user_id'];
$org=intval(getgpc('org'));
$exercice_id = intval(getgpc('cid'));

$is_org=api_get_setting('enable_modules','clay_oven');
if($is_org=='true'){
	if($_SESSION[$userId.$org.'bo']) {
		$userId = $_SESSION[$userId.$org.'bo'];
	}else{
	    $userId = $_SESSION[$userId.$org.'sh'];
	}
}

$lessonId = $_SESSION['_cid'];
$vlanid =getgpc('vlanid','G');
if($_GET['occupation_id']){
	$lessonId=  getgpc('occupation_id');
}
            
$urls=explode("?",$_SERVER['HTTP_REFERER']);
$filename=substr($urls[0],strrpos($urls[0],'/')+1);
$stringfilename = "lessontop3.php topoview.php topodesign.php lessontop.php course_home.php quiz_paper.php vmdisk_list.php cloudauto.php vmmanage_iframe.php auto.php show_topo.php index.php cloudsearch.php";
if(!strstr($stringfilename,$filename)){
	$html = '<p style="color:red;font-weight:bold">对不起，您的访问非法！</p>';
	Display::display_error_message ( $html, false );
    exit();
}
if(!$userId){
    $html1 = '<p style="color:red;font-weight:bold">对不起，您的用户会话过期，请重新<a href="../../portal/sp/login.php">登录</a>！</p>';
    Display::display_error_message ( $html, false );
    exit();
}
    $user_system = $_SESSION['user_system'];
    $system_arr=array();
    foreach($user_system as  $key => $value)
    {
           $system_arr[] = $value['system'];
    }
if( $manages == 0 )
{
       if(api_get_setting('enable_modules', 'clay_oven') == 'false')
       {
            if(!api_is_admin())
            {
               if(!in_array($system, $system_arr))
               {
                 $html = '<p style="color:red;font-weight:bold">对不起啊，您的访问非法（请检查您的参数）！</p>';
                 Display::display_error_message ( $html, false );
                 exit();
               }
            }
       } 
   
	if(!isset($lessonId)){
		$lessonId = getgpc('cid','G');
	}
	if(!$lessonId){
		header("Location:../../portal/sp/index.php");
	}
	$sqlz="select  `lesson_id`  from  `vmtotal`  where  `user_id`=".api_get_user_id();
	$lessonz=DATABASE::getval($sqlz);
	if($lessonz  &&  $lessonz!=$lessonId){
		 $html = '<p style="color:red;font-weight:bold">对不起，您的操作错误，请重试！</p>';
		 Display::display_error_message ( $html, false );
		 exit(); 
	}
}else{      
	if(isset($_GET['manage'])){
		$lessonId = '0';
	}else{
		header("Location:../../portal/sp/index.php");
	}
}

$vmStartInfo = Database::get_main_table (vmstartinfo);
$vmnum = Database::get_main_table (vmsummary);
$selectsql="system = '$system' and lesson_id = '$lessonId' and user_id = '$userId' ";
if(isset($_GET['manage'])  && $_GET['manage']){
    $selectsql.=" and manage=1 ";
}else{
	$selectsql.=" and manage=0 ";
}



$selectTotal = Database::sql_select("vmtotal",$selectsql,"addres,vmid,proxy_port");
$resault = api_sql_query ($selectTotal, __FILE__,__LINE__);
 
$systems = explode('_',$system);
$name = $systems[0];
$vmdisk = Database::get_main_table (vmdisk);
$sql="select * from $vmdisk where name='".$name."'";

$res = api_sql_query( $sql, __FILE__, __LINE__ );

while($ss = Database::fetch_array ( $res )){
    $vmdisks = $ss;
}
$memory = $vmdisks['memory'];
$CPU_number = $vmdisks['CPU_number'];
$vmdisknic = $vmdisks['vlan'];
$NIC_type = $vmdisks['NIC_type'];
$boot = $vmdisks['boot'];
$mac = $vmdisks['mac'];
$iso = $vmdisks['ISO'];

if($vmdisks['vlan'] ==''){
    $vmdisknic = 1;
}
if($vmdisks['mac']==''){
    $mac = 1;
}else{
    $mac = $vmdisks['mac'];
}

if($vmdisks['ISO']==''){
    $iso = 1;
}else{
    $iso = $vmdisks['ISO'];
}

if($vmdisks['Ide']==''){  
	$Ide = 1; 
}else{
        $Ide = $vmdisks['Ide'];
}
    
if($vmdisks['Display']==''){
        $Display = "std";
    }else{
        $Display = $vmdisks['Display'];
}


$local_addres=$_SERVER['HTTP_HOST'];
$execstring1 = exec('/sbin/ifconfig vmbr0 | sed -n \'s/^ *.*addr:\\([0-9.]\\{7,\\}\\) .*$/\\1/p\'',$arr);
$loip = $arr[0];
if($manages==1 && isRoot ()){
	$local_addresx = explode(':',$local_addres);
	$local_addres = $local_addresx[0];
	$manage='1';
	$vm_cont=Database::getval("select count(*) from `vmtotal` where `manage`='".$manage."' and `lesson_id`= '".$lessonId."' and `system`='".$system."'",__FILE__ , __LINE__);
    if(!$vm_cont){
		$macstr='sudo -u root /sbin/cloudmac.sh';
		exec("$macstr",$macinfo);
		$mac=$macinfo[0];
        
            $proxy_port=get_hub_port("add","proxyhub","admin");
            $filecheck="";
            do{
                    $sql_poxy = "select id from vmtotal where proxy_port = '".$proxy_port."'";
                    $sql_poxy=DATABASE::getval("$sql_poxy", __FILE__,__LINE__);
                    if($sql_poxy){
                        $proxy_port=get_hub_port("add","proxyhub","admin");
                        $isport='sudo -u root   /sbin/cloudhub.sh add  '.$proxy_port.' '.$userId.' ';
                        exec("$isport");
                    }else{
                        $isport="sudo -u root   /sbin/cloudhub.sh add '$proxy_port'  '$userId'";
                        exec("$isport"); 
                    }

                    $isport='sudo -u root   /sbin/cloudhub.sh check '.$proxy_port.' '.$userId.' ';
                      exec("$isport",$filecheck); 
                      $filecheck = $filecheck[0];
                      if($filecheck=='error'){
                          sleep (1);
                      }
            }while($filecheck=='error') ;   

            $nicnum = $vmdisknic;
	    $command='sudo -u root /usr/bin/ssh root@'.$loip.' /sbin/cloudvmstart.sh addres='.$loip.'___nicnum='.$nicnum.'___system='.$system.'___user_id='.$userId.'___lesson_id='.$lessonId.'___mem='.$memory.'___cpu_num='.$CPU_number.'___nic_type='.$NIC_type.'___boot='.$boot.'___iso='.$iso.'___vlanid='.$vlanid.'___mac='.$mac.'___Ide='.$Ide.'___Display='.$Display.'___manage=1';
	    exec("$command",$info);
	    $sidres = $info[0];
	    $pot = $sidres+100+5900;
	    $vmid = $sidres + 100;
	    $ins = "INSERT INTO vmtotal (`id`, `addres`, `nicnum`, `system`, `user_id`, `lesson_id`, `vmid`, `port`, `group_id`, `mac_id`, `proxy_port`, `manage`,`stime`) values(null,'$loip','$nicnum','$system','$userId','$lessonId','$vmid','$pot','1','$mac' ,'$proxy_port','1','$stime');";
	    $r = api_sql_query ( $ins, __FILE__, __LINE__ );
        if($r){
            //vmdisk-log
            $u_name=DATABASE::getval("select  username  from user  where  user_id=".$userId);
            $u_ip=DATABASE::getval("select  login_ip  from  track_e_online  where  login_user_id=".$userId);
            $ins_log = "INSERT INTO vmdisk_log values(null,'$loip','$nicnum','$system','$userId','$lessonId','$vmid','$pot','1','$mac' ,'$proxy_port','1','$u_name','$u_ip','$stime','',0);";
			api_sql_query ( $ins_log, __FILE__, __LINE__ );
         }
	}else{
        $vm_data=api_sql_query_array_assoc("select  * from `vmtotal` where `manage`='".$manage."' and `lesson_id`= '".$lessonId."' and `system`='".$system."'",__FILE__,__LINE__);
        $proxy_port=$proxy_port[0];
	    $vmaddres = $vm_data[0]['addres'];
	    $vmid = $vm_data[0]['id'];
	    $pot = $vmid+100+5900;
	}
    $command2='sudo -u root  /sbin/cloudvnc.sh addres='.$local_addres.'___port='.$pot.'___proxy_port='.$proxy_port.'___userId='.$userId.'___manage=1';
	exec("$command2 &");

	$local_addres=$_SERVER['HTTP_HOST'];
	$pp = "Location: http://$local_addres".URL_APPEDND."/main/admin/vmdisk/vmdisk_list.php";
		if($_GET ['keyword']=='输入搜索关键词'){
			$_GET ['keyword']='';
			}
		if(isset($_GET['keyword'])  && $_GET ['keyword']!=='' ){
			$pp.="?keyword=".$_GET ['keyword'];
			}
		header($pp);    
}else{
	$sqlgap="select  `description10`  from  `course`  where  `code`=".$lessonId;
	$GAP = DATABASE::getval($sqlgap);
	$vm= array (); 
	while ( $vm = Database::fetch_row ( $resault) ) {
		$addres[]  = $vm[0];
		$vms[]= $vm[1];
		$proxy_port[] = $vm[2];
	}
	if(!$vms){
             $vm_num=DATABASE::getval("SELECT  `vm_num`   FROM  `vmsummary` where `addres`='".$vmaddres."'", __FILE__,__LINE__);
            $vmnumber=DATABASE::getval("SELECT `number` FROM  `vm_max_num`", __FILE__,__LINE__);
            if(!$vmnumber){
                $vmnumber=10;
            }
            if($vm_num > $vmnumber){
                 echo "请注意，开启的虚拟机已经达到最大数量" ;
                 exit();
            }
            $concurrent_dir = "/etc/cloudschedule/concurrent";
            $concurrent_str1=file_get_contents($concurrent_dir);//读取文件
            $concurrent_str2 = trim($concurrent_str1);
            $concurrent_str = explode(',',$concurrent_str2);
            if($concurrent_str[0]=='' || $concurrent_str[0]<=0){
                $concurrent_str[0]='0';
            }
            if($concurrent_str[1]=='' || $concurrent_str[1]<=0){
                $concurrent_str[1]='3';
            }
            usleep(rand($concurrent_str[0],$concurrent_str[1]*1000));
            $macstr='sudo -u root /sbin/cloudmac.sh';
            exec("$macstr",$macinfo);
            $mac=$macinfo[0];
            
            $proxy_port=get_hub_port("add","proxyhub","1");
            $filecheck="";
            do{
                    $sql_poxy = "select id from vmtotal where proxy_port = '".$proxy_port."'";
                    $sql_poxy=DATABASE::getval("$sql_poxy", __FILE__,__LINE__);
                    if($sql_poxy){
                        $proxy_port=get_hub_port("add","proxyhub","1");
                        $isport='sudo -u root   /sbin/cloudhub.sh add  '.$proxy_port.' '.$userId.' ';
                        exec("$isport");
                    }else{
                        $isport="sudo -u root   /sbin/cloudhub.sh add '$proxy_port'  '$userId'";
                        exec("$isport"); 
                    }

                    $isport='sudo -u root   /sbin/cloudhub.sh check '.$proxy_port.' '.$userId.' ';
                      exec("$isport",$filecheck); 
                      $filecheck = $filecheck[0];
                      if($filecheck=='error'){
                          sleep (1);
                      }
            }while($filecheck=='error') ;
            
           
            $small = 'sudo -u root   /sbin/cloudsmall.sh ';
	        exec("$small",$small_info);
            $vmaddres = $small_info[0];
            $vm_num   = DATABASE::getval("SELECT  `vm_num`   FROM  `vmsummary` where `addres`='".$vmaddres."'", __FILE__,__LINE__);
            $vmnumber = DATABASE::getval("SELECT `number` FROM  `vm_max_num`", __FILE__,__LINE__);
            if(!$vmnumber)
            {
                $vmnumber = 10;
            }
            if($vm_num > $vmnumber){
                echo "非常抱歉，平台开启控制台数目已经达到上限，为保证大家流畅使用实验场景，请您耐心等待一段时间，稍后进行学习" ;
                exit();
            }
            if(!$vmaddres){
		    $html = '<p style="color:red;font-weight:bold">对不起，您的操作错误，请重试！</p>';
		    Display::display_error_message ( $html, false );
		    exit();
	    }
	    $command='sudo -u root /usr/bin/ssh root@'.$vmaddres.' /sbin/cloudvmstart.sh addres='.$vmaddres.'___nicnum='.$nicnum.'___system='.$system.'___user_id='.$userId.'___lesson_id='.$lessonId.'___mem='.$memory.'___cpu_num='.$CPU_number.'___nic_type='.$NIC_type.'___boot='.$boot.'___iso='.$iso.'___vlanid='.$vlanid.'___mac='.$mac.'___Ide='.$Ide.'___Display='.$Display.'___GAP='.$GAP;
	    exec("$command",$info);
	    $sidres = $info[0];
	    $pot    = $sidres+100+5900;
	    $vmid   = $sidres + 100;
	    $ins    = "INSERT INTO vmtotal (`id`, `addres`, `nicnum`, `system`, `user_id`, `lesson_id`, `vmid`, `port`, `group_id`, `mac_id`, `proxy_port`, `manage`,`stime`) values(null,'$vmaddres','$nicnum','$system','$userId','$lessonId','$vmid','$pot','1','$mac' ,'$proxy_port','0','$stime');";
	    $r      = api_sql_query ( $ins, __FILE__, __LINE__ );
            if($r && $exercice_id!=0)
            {
                $vm_id   = mysql_insert_id();
                $vm_exam = "insert into vm_rel_exam(exam_id,vm_id) values(".$exercice_id.",".$vm_id.")";
                api_sql_query ( $vm_exam, __FILE__, __LINE__ );
            }
            
            if($r)
            {
               //vmdisk-log
               $u_name  = DATABASE::getval("select  username  from user  where  user_id=".$userId);
               $u_ip    = DATABASE::getval("select  login_ip  from  track_e_online  where  login_user_id=".$userId);
               $ins_log = "INSERT INTO vmdisk_log values(null,'$vmaddres','$nicnum','$system','$userId','$lessonId','$vmid','$pot','1','$mac' ,'$proxy_port','0','$u_name','$u_ip','$stime','',0);";
	           api_sql_query ( $ins_log, __FILE__, __LINE__ );
            }
	}else{

        $proxy_port = $proxy_port[0];
	    $vmaddres   = $addres[0];
        if(!$vmaddres)
        {
		    $html = '<p style="color:red;font-weight:bold">对不起，您的操作错误，请重试！</p>';
		    Display::display_error_message ( $html, false );
		    exit();
	    }

	    $vmid = $vms[0];
	    $pot  = $vmid+5900;
	}
	$command2='sudo -u root  /sbin/cloudvnc.sh addres='.$vmaddres.'___port='.$pot.'___proxy_port='.$proxy_port.'___userId='.$userId;
	exec("$command2 &");
	
	if($_GET['nomachine']){
	    echo '<script>window.close();</script>';
	}else{
            $local_addresx = explode(':',$local_addres);
            $local_addresd = $local_addresx[0];
            header("Location: http://$local_addres".URL_APPEDND."/main/html5/auto.php?lessonId=$lessonId&host=$local_addresd&port=$proxy_port&system=$system");
        }
}	
exit();
?>
