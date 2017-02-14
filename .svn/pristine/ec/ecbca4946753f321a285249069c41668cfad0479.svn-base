<style>
    
   .jbox-content-cse{padding: 20px;}
</style>
<?php
header("content-type:text/html;charset=utf-8");
require_once ('../../main/inc/global.inc.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
$vlanid='';  
if(!isset($lessonId)){
$lessonId = "20140630".$_SESSION['cid'];
}
if(!$lessonId){
    header("Location:../../portal/sp/login.php");
}
$userId = $_SESSION['_user']['user_id'];
$system = $_GET['system'];
$nicnum = intval($_GET['nicnum']);
//获取ip的链接加密判断--zd
 $stype=Database::getval ( "select selected_value from  settings_current where id=24", __FILE__, __LINE__ );
 if($stype=='false'){  
    //single_fight
    $cid=$_SESSION['cid'];
    $question_vm_name=Database::getval ( "select vm_name from  exam_question where id=".$cid, __FILE__, __LINE__ );  
    $sys3=md5($question_vm_name."_".$userId);    
    if($system==$sys3){
         $system=$question_vm_name."_".$userId;
       }else{
           $system='';
           echo "<strong>有异常注入!!!</strong>";
           exit();
       }
    
 }
 
$vmStartInfo = Database::get_main_table (vmstartinfo);
$vmnum = Database::get_main_table (vmsummary);
$selectTotal = Database::sql_select("vmtotal","system = '$system' ","addres,vmid,proxy_port");
$resault = api_sql_query ($selectTotal, __FILE__,__LINE__);
$vm= array ();

while ( $vm = Database::fetch_row ( $resault) ) {
    $addres [] = $vm[0];
    $vms [] = $vm[1];
    $proxy_port[] = $vm[2];
}
/**
 * chang 12-11-10 14:45  start
 */

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
$NIC_type = $vmdisks['NIC_type'];
$boot = $vmdisks['boot'];
$mac = $vmdisks['mac'];
$iso = $vmdisks['ISO'];
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
        $Display = 'std';
}else{
        $Display = $vmdisks['Display'];
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
                $concurrent_str[0]='1';
            }
            if($concurrent_str[1]=='' || $concurrent_str[1]<=1){
                $concurrent_str[1]='3';
            }
            sleep(rand($concurrent_str[0],$concurrent_str[1]));
            
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
            $small='sudo -u root   /sbin/cloudsmall.sh ';
	    exec("$small",$small_info);
            $vmaddres = $small_info[0];
	    $sidres = '';
            do{
                $command='sudo -u root /usr/bin/ssh root@'.$vmaddres.' /sbin/cloudvmstart.sh addres='.$vmaddres.'___nicnum='.$nicnum.'___system='.$system.'___user_id='.$userId.'___lesson_id='.$lessonId.'___mem='.$memory.'___cpu_num='.$CPU_number.'___nic_type='.$NIC_type.'___boot='.$boot.'___iso='.$iso.'___vlaid='.$vlanid.'___mac='.$mac.'___Ide='.$Ide.'___Display='.$Display;
                exec("$command",$info);
                $sidres = $info[0];  
                if($sidres==''){
                    sleep(rand(1,5));
                }
            }while($sidres=='');
	    $pot = $sidres+100+5900;
	    $vmid = $sidres + 100;  
	    $ins = "INSERT INTO vmtotal values(null,'$vmaddres','$nicnum','$system','$userId','$lessonId','$vmid','$pot','$group_id','$mac' ,'$proxy_port','0');";
	    $r = api_sql_query ( $ins, __FILE__, __LINE__ );
}else{
              $proxy_port=$proxy_port[0];
	    $vmaddres = $addres[0];
	    $vmid = $vms[0];
	    $pot = $vmid+100+5900;
}
$command2='sudo -u root  /sbin/cloudvnc.sh addres='.$vmaddres.'___port='.$pot.'___proxy_port='.$proxy_port.'___userId='.$userId;
exec("$command2 &");
if(isset($system) && $system!=''){
    $mac1= Database::getval("select `mac_id` from `vmtotal` where `system`='".$system."'",__FILE__,__LINE__);
    $mac1= substr($mac1,0,14);
    $ip_sql="select `IP_address` from `clouddesktopscan` where `physical_address` like '".$mac1."%'";
    $ip= Database::getval($ip_sql,__FILE__,__LINE__);
    if($ip==''){
        $jran = rand(15000,30000);
        echo '<div class="jbox-content-cse">服务器正在启动,请耐心等待目标服务器地址的显示........</div>';
        echo'<script language="javascript">
            setTimeout( "self.location.reload(); ",'.$jran.'); 
            </script>';
    }else{
        echo '<div class="jbox-content-cse">您当前的目标服务器地址为：<b>'.$ip.'</b>,请进行相关操作！</div>';
    }
}
?>

