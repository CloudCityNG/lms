<?php
$cidReset = true;
include_once ('../../inc/global.inc.php');
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
if(!isset($_GET['category']) || $_GET['category']==''){
    header ( "Location:  cloud_plan.php?category=vmnode" );
    exit ();
}
function create_bucket($name){
    /**当有该令牌桶却没有该令牌桶的数据表时要创建表---zd**/
    $sql="select ranges from token_bucket where token_bucket_name='".$name."'";
    $rangess =  Database::getval($sql);
    $ranges  =  unserialize($rangess);
    $ranges1=$ranges[0];
    $ranges2=$ranges[1];
    $ranges=$ranges1-1;

    /**判断建表**/
    if(mysql_num_rows(mysql_query("SHOW TABLES LIKE `".$name."`"))!=1){
        $sql_insert="CREATE TABLE if not exists `".DB_NAME."`.`$name`(
            `Pid` INT NOT NULL AUTO_INCREMENT ,
            `status` smallint(6),
            `values` varchar(256) ,
            PRIMARY KEY ( `Pid` )) ENGINE =MyISAM  charset=utf8 auto_increment=".$ranges;
        api_sql_query ( $sql_insert,__FILE__, __LINE__ );
    }
    $count_sql=Database::getval("select count(*) from `".$name."`",__FILE__,__LINE__);
    /**插入记录**/
    if($count_sql==0){
        for($pid=$ranges1;$pid<=$ranges2;$pid++){
            $sql1="insert into `$name` (`Pid`,`status`,`values`) values(".$pid.",'0','0');";
            api_sql_query ( $sql1, __FILE__, __LINE__ );
        }
    }
}

/**节点虚拟机**/
$vm_max_num = DATABASE::getval("select `id` from `vm_max_num` where `description`='vm_max_num'",__FILE__,__LINE__);
if(!$vm_max_num){
     @api_sql_query ("INSERT INTO  `vm_max_num` (`id`, `number`, `description`) VALUES (NULL, '10', 'vm_max_num')", __FILE__, __LINE__ );
}

        $vm_num= DATABASE::getval("select  `number` from `vm_max_num` where `description`='vm_max_num'",__FILE__,__LINE__);
if($vm_num!==''){
    $default_values['vm_number']  =  $vm_num;
}else{
    $default_values['vm_number']  =  10;
}

/**节点权重设置**/
$cloudweight_dir = "/etc/cloudschedule/cloudweight";
/**$cloudweight_dir = "/var/www/cloudweight";**/
$cloudweight_str=file_get_contents($cloudweight_dir);/**读取文件**/
$ini_list3 = explode("\n",$cloudweight_str);/**按换行拆开,放到数组中.**/
$weight_str1=array_filter($ini_list3);/**数组去空**/
$weight_res = count($weight_str1);

/**节点热备设置**/
$delcloud_dir = "/etc/cloudschedule/delcloud";
/**$delcloud_dir = "/var/www/delcloud";**/
$delcloud_str=file_get_contents($delcloud_dir);/**读取文件**/
$ini_list2 = explode("\n",$delcloud_str);/**按换行拆开,放到数组中**/
$delcloud_str=array_filter($ini_list2);/**数组去空**/
$delcloud_str=array_unique($delcloud_str);//数组去空**/
$delcloud_res = count($delcloud_str);/**count**/

/**节点令牌桶页面设置**/
$sql="select *  from token_bucket ";
$res = api_sql_query( $sql, __FILE__, __LINE__ );
$vmsummarys=array();
while($vmsummary = Database::fetch_row( $res )){
    $vmsummarys[] = $vmsummary;
}
$result = count($vmsummarys);/**addres count**/

/**并发缓冲设置**/
$concurrent_dir = "/etc/cloudschedule/concurrent";
$concurrent_str1=file_get_contents($concurrent_dir);
$concurrent_str1 = trim($concurrent_str1);
$concurrent_str = explode(',',$concurrent_str1);

$default_values['end_buffer']=$concurrent_str[1]; 

/**虚拟机监控设置**/
$monitor_dir = "/etc/cloudschedule/cloudsystemmonitor";
        

if (isset ( $_GET ['action'] )) {
    switch ($_GET ['action']) {
        /**令牌桶删除**/
        case 'delete_token_bucket' :
            if ( $_GET ['action'] =='delete_token_bucket') {
                $id = intval(getgpc('id'));
                $sql="select `token_bucket_name` from `token_bucket`  where id=".$id;    
                $token_bucket_name=  Database::getval($sql);   
                $sq="drop table `".$token_bucket_name."`";    
                $res=@api_sql_query ( $sq, __FILE__, __LINE__ );
                if($res){
                    $sql = "DELETE FROM `".DB_NAME."`.`token_bucket` WHERE `token_bucket`.`id` = $id";
                    $re=api_sql_query ( $sql, __FILE__, __LINE__ );   
                }
                tb_close ( "cloud_plan.php?category=bucket");
            }
            break; 
        /**权重删除**/
        case 'delete_weight' :
            if ( $_GET ['action'] =='delete_weight') {
                $weightname=getgpc("weight_name","G");
                if($weightname){
                    $weight_var='';
                    for($b=0;$b<$weight_res;$b++){
                        $weight_array=explode(" ",$weight_str1[$b]);
                        $end_weight=$weight_res-1;
                        if($weight_array[0]==$weightname){}else{
                            if($weight_array[0]){
                                    $weight_var.= $weight_array[0]." ".$weight_array[1]."\n";
                            }
                        }
                    }
                    $cloudweight_open = fopen($cloudweight_dir,'w');
                    fwrite($cloudweight_open,$weight_var);
                    fclose($cloudweight_open);
                }
                tb_close ( "cloud_plan.php?category=weight");
            }
            break; 
        /**热备节点删除**/
        case 'delete_hot' :
            if ( $_GET ['action'] =='delete_hot') {
                $hotname=getgpc("hot_name","G");
                if($hotname){
                    $delcloud_array='';
                    for($a=0;$a<$delcloud_res;$a++){
                        $end_hot=$delcloud_res-1;
                        if($delcloud_str[$a]==$hotname){}else{
                            if($delcloud_str[$a]){
                                    $delcloud_array.= $delcloud_str[$a]."\n";
                            }
                        }
                    }
                    $delcloud_open = fopen($delcloud_dir,'w');
                    fwrite($delcloud_open,$delcloud_array);
                    fclose($delcloud_open); 
                }
                tb_close ( "cloud_plan.php?category=hot");
            }
            break; 
    }
}

$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';

$strCategory = isset ( $_GET ['category'] ) ? getgpc ( 'category', 'G' ) : 'vmnode';

$my_category = escape ( $strCategory );
$form = new FormValidator ( 'settings', 'post', 'cloud_plan.php?category=' . $strCategory );
Display::setTemplateSettings ( $form, '97%' );
 
if(getgpc('category','G') == 'vmnode'){
    $form->addElement ( 'html','<tr><td class="settingcomment" align="left" colspan=2 style="width:45%;color: #FF0000;border-bottom:1px dotted #666;border-right:1px dotted #666">请注意不填或者0时，默认最大数10个！</td></tr>');
    $form->addElement ( 'text', 'vm_number', "<span style='font-weight:bold;font-size:12px'>每台服务器虚拟机限制数量（请输入数字）</span>",array('type'=>'textarea','rows'=>'5','cols'=>'80'));
    $form->addRule ( 'vm_number', get_lang ( '您的输入不是数字，请重试！' ), 'numeric' );
}elseif(getgpc('category','G')== 'weight'){
    $weight_add_var='<div class="managerSearch" style="margin: 0 0 0;border:1px solid #9BA0AF"> <span class="searchtxt right"> '.link_button ( "view_more_stats.gif", "新建权重", "weight_add.php?action=add", "60%", "70%" ).'</span> </div>';
    $weight_table=$weight_add_var.'<table cellspacing="0" cellpadding="0" class="p-table">
               <tr style="background-color: rgb(240, 240, 240); "><th>序号</th><th>节点IP地址</th><th>花费</th><th>操作</th></tr>';
    if($weight_res>0){
        foreach($weight_str1 as $k1=>$v1){
            $weight_id=$k1+1;
            $weight_arr=  explode(" ", $v1);
            $weight_name=$weight_arr[0];
            $weight_del =  confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'cloud_plan.php?action=delete_weight&category=weight&weight_name=' .  $weight_name);
            if($weight_arr[0]){
                $weight_table.='<tr class="row_even">
                                    <td style="width:100px;text-align:center">'.$weight_id.'</td>
                                    <td style="width:100px;text-align:center">'.$weight_arr[0].'</td>
                                    <td style="width:100px;text-align:center">'.$weight_arr[1].'</td>
                                    <td style="width:100px;text-align:center">'.$weight_del.'</td>
                                </tr>';
            }
        }
    }else{
        $weight_table.='<tr><td colspan="10">没有节点权重信息</td></tr>';
    }
    $weight_table.="</table>";
      $form->addElement ( 'html', $weight_table );
}elseif(getgpc('category','G')== 'hot'){
    $hot_add_var='<div class="managerSearch" style="margin: 0 0 0;border:1px solid #9BA0AF"> <span class="searchtxt right"> '.link_button ( "view_more_stats.gif", "新建热备节点", "hot_add.php?action=add&add=asdfsada", "60%", "70%" ).'</span> </div>';
    $hot_table=$hot_add_var.'<table cellspacing="0" cellpadding="0" class="p-table">
               <tr style="background-color: rgb(240, 240, 240); "><th>序号</th><th>热备节点IP</th><th>操作</th></tr>';
    if($delcloud_res>0){
        for($i=0;$i<$delcloud_res;$i++){
            $hot_id=$i+1;
            $delcloud_var = str_replace(" ","",$delcloud_str[$i]);
            $delcloud_var = str_replace("\n","",$delcloud_var);
            $hot_del =  confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'cloud_plan.php?action=delete_hot&category=hot&hot_name=' . trim($delcloud_var) );
            $hot_table.='<tr class="row_even">
                                <td style="width:100px;text-align:center">'.$hot_id.'</td>
                                <td style="width:100px;text-align:center">'.trim($delcloud_var).'</td>
                                <td style="width:100px;text-align:center">'.$hot_del.'</td>
                             </tr>';
        }
    }else{
        $hot_table.='<tr><td colspan="10">没有节点热备信息</td></tr>';
    }
    $hot_table.="</table>";
    $form->addElement ( 'html', $hot_table );
}elseif(getgpc('category','G')== 'bucket'){
    $token_add_var='<div class="managerSearch" style="margin: 0 0 0;border:1px solid #9BA0AF"> <span class="searchtxt right"> '.link_button ( "view_more_stats.gif", "新建令牌桶", "bucket_add.php?action=add&edit_id=".$id, "60%", "70%" ).'</span> </div>';
    $bucket_table=$token_add_var.'<table cellspacing="0" cellpadding="0" class="p-table">
               <tr style="background-color: rgb(240, 240, 240); "><th>序号</th><th>名称</th><th>类型</th><th>令牌桶范围</th><th>查看使用端口</th><th>操作</th></tr>';
    if($result>0){
        for($i=0;$i<$result;$i++){
            $j=$i+1;
            $type='';
            if($vmsummarys[$i][1]=='1'){
                $type="系统默认 ";
            }else{
                $type="自定义 ";
            }
            $ranges ='';
            $range  = unserialize($vmsummarys[$i][3]);
            $ranges = $range[0]." 至 ".$range[1];
            $status = link_button ( "chat.gif", $vmsummarys[$i][2]."令牌桶", "bucket_status.php?action=status&edit_id=".$vmsummarys[$i][0], "70%", "80%" ,FALSE);
            $token_bucket_del =  confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'cloud_plan.php?category=bucket&action=delete_token_bucket&id=' . $vmsummarys[$i][0] );
            if($vmsummarys[$i][2]!==''){
                create_bucket($vmsummarys[$i][2]);
            }
            $bucket_table.='<tr class="row_even">
                                <td style="width:100px;text-align:center">'.$j.'</td>
                                <td style="width:100px;text-align:center">'.$vmsummarys[$i][2].'</td>
                                <td style="width:100px;text-align:center">'.$type.'</td>
                                <td style="width:100px;text-align:center">'.$ranges.'</td>
                                <td style="width:100px;text-align:center">'.$status.'</td>
                                <td style="width:100px;text-align:center">'.$token_bucket_del.'</td>
                             </tr>';
        }
    }else{
        $bucket_table.='<tr><td colspan="10">没有令牌桶信息</td></tr>';
    }
    $bucket_table.="</table>";
    $form->addElement ( 'html', $bucket_table );
}elseif(getgpc('category','G')== 'concurrent'){
    $form->addElement ( 'html','<tr><td class="settingcomment" align="left" colspan=2 style="width:45%;color: #FF0000;border-bottom:1px dotted #666;border-right:1px dotted #666">请注意不填时，默认为3秒！</td></tr>');
    $form->addElement ( 'text',"end_buffer","<span style='font-weight:bold;font-size:12px'>缓冲时间(单位：秒）</span>",array('type'=>'textarea','rows'=>'5','cols'=>'50'));
    $form->addRule ( 'end_buffer', get_lang ( '您的输入不是数字，请重试！' ), 'numeric' );
}elseif(getgpc('category','G')== 'monitor'){
    $monitornotice_html='<tr><td class="settingcomment" align="left" colspan=2 style="width:45%;color: #FF0000;border-bottom:1px dotted #666;border-right:0px dotted #666">在平台后台首页下部显示显示虚拟机监控信息, 这个选项控制显示/隐藏</td></tr>'; 
    $form->addElement ( 'html',$monitornotice_html);
    $group = array ();
    $group [] = $form->createElement ( 'radio', 'monitor', null, '是', '1' ,array('id' => 'underlyingMirror','onclick'=>'check1()'));
    $group [] = $form->createElement ( 'radio', 'monitor', null, '否', '2',array('id' => 'incrementalMirror','onclick'=>'check2()'));
    $form->addGroup ( $group, 'monitor', '<span style="font-weight:bold;font-size:12px">显示虚拟机监控信息</span>', '&nbsp;&nbsp;&nbsp;&nbsp;', false );
}else{
    $form->addElement ( 'html','<tr><td class="settingcomment" align="left" colspan=2 style="width:45%;color: #FF0000;border-bottom:1px dotted #666;border-right:1px dotted #666">请注意不填或者0时，默认最大数10个！</td></tr>');
    $form->addElement ( 'text', 'vm_number', '每台服务器虚拟机限制数量（请输入数字）',array('type'=>'textarea','rows'=>'5','cols'=>'80'));
    $form->addRule ( 'vm_number', get_lang ( '您的输入不是数字，请重试！' ), 'numeric' );
}
if(getgpc('category','G') == 'hot' or getgpc('category','G') == 'weight' or getgpc('category','G') == 'bucket'){
    $form->addElement ( 'html', '' );
}else{
    $form->addElement ( 'style_submit_button', null, get_lang ( '确定' ), 'class="save"' );
}
if(file_exists("/etc/cloudschedule/rsyncstart")){
    $default_values['open_synchronization']=1;
}
if(file_exists($monitor_dir)){
    $default_values['monitor']=2;
}else{
    $default_values['monitor']=1;
}
$form->setDefaults ( $default_values );

if($form->validate()){
    $values = $form->exportValues (); 
    
    if(getgpc('category','G') == 'vmnode'){
        $max_vm_number=intval(getgpc('vm_number','P'));
        $sql_max="UPDATE `vm_max_num` SET  `number`= '".$max_vm_number."' WHERE  `description`='vm_max_num'";
        $res=@api_sql_query ($sql_max, __FILE__,__LINE__);
        if($res){
            $values=array();
        }
    }elseif(getgpc('category','G')== 'concurrent'){
        $start_buffer=1;
        $concurrent = $start_buffer.','.$values['end_buffer'];
        $monitor_openconcurrent_open = fopen($concurrent_dir,'w');
        fwrite($monitor_openconcurrent_open,$concurrent);
        fclose($monitor_openconcurrent_open);
        $values=array();
    }elseif(getgpc('category','G')== 'monitor'){
        $monitor_value=intval(getgpc('monitor','P'));
        $exec_add='touch /etc/cloudschedule/cloudsystemmonitor';
        $exec_del='rm -rf /etc/cloudschedule/cloudsystemmonitor';
/**  
 *       $exec_add='cd /var/www/ ;touch cloudsystemmonitor';
 *       $exec_del='cd /var/www/ ;rm  cloudsystemmonitor';
 */
        if($monitor_value=='1'){
//            exec($exec_del);
            sript_exec_log($exec_del);
        }else{
//            exec($exec_add); 
            sript_exec_log($exec_add); 
        }
    }else{
        $max_vm_number=intval(getgpc('vm_number','P'));
        $sql_max="UPDATE `vm_max_num` SET  `number`= '".$max_vm_number."' WHERE  `description`='vm_max_num'";
        $res=@api_sql_query ($sql_max, __FILE__,__LINE__);
        if($res){
            $values=array();
        }
    }
    api_redirect ( 'cloud_plan.php?category='.getgpc('category','G') );
}
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( NULL );
 
?>
<aside id="sidebar" class="column cloud open">
    <div id="flexButton" class="closeButton close">

    </div>
</aside>
<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> 
        &gt; <a href="<?=URL_APPEDND;?>/main/admin/cloud_menu.php">云平台</a> &gt; 弹性云计算</h4>
    <ul class="manage-tab boxPublic">
        <li<?=$strCategory=='vmnode'?' class="selected"':''?>><a href="cloud_plan.php?category=vmnode"><em>节点虚拟机设置</em></a></li>
        <li<?=$strCategory=='weight'?' class="selected"':''?>><a href="cloud_plan.php?category=weight"><em>节点权重设置</em></a></li>
        <li<?=$strCategory=='hot'?' class="selected"':''?>><a href="cloud_plan.php?category=hot"><em>节点热备设置</em></a></li>
        <li<?=$strCategory=='bucket'?' class="selected"':''?>><a href="cloud_plan.php?category=bucket"><em>节点令牌桶</em></a></li>
        <li<?=$strCategory=='concurrent'?' class="selected"':''?>><a href="cloud_plan.php?category=concurrent"><em>并发缓冲设置</em></a></li>
        <li<?=$strCategory=='monitor'?' class="selected"':''?>><a href="cloud_plan.php?category=monitor"><em>虚拟机监控设置</em></a></li>
    </ul>
    <div class="manage-tab-content">
        <div class="manage-tab-content-list">
            <article class="synchro ip">
                <form action="#" method="post">
                    <table cellpadding="0" cellspacing="0" class="settingstable">
                        <tbody>
                       <?php $form->display ();?>
                        </tbody>
                    </table>
                </form>
            </article>
        </div>
</section>

</body>

</html>
