<?php
$language_file = array ('courses','admin');
$cidReset=true;
include_once ("../../inc/global.inc.php");
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
$this_section=SECTION_PLATFORM_ADMIN;
//$countnum=mysql_fetch_row(mysql_query('select count(id) from vmdisk_log'));
//if($countnum['0'] > 2000){
//    $delnum=$countnum['0']-2000;
//    if($delnim < 10000){
//       mysql_query('delete from vmdisk_log order by id desc limit '.$delnum);
//     }else{
//       mysql_query('delete from vmdisk_log order by id desc limit 10000');  
//     }
//}
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
if(isset($_GET['action']) && $_GET['action']=='export_log'){ 
    $file_dir=URL_ROOT."/www".URL_APPEDND."/storage/DATA/logs";
    if(!file_exists($file_dir)){
//        exec("mkdir ".$file_dir);
//        exec("chmod  -R 777 ".$file_dir);
        sript_exec_log("mkdir ".$file_dir);
        sript_exec_log("chmod  -R 777 ".$file_dir);
    }
    $file_name="vmdisk_log.zip";
    $command="mysqldump -u".DB_USER." -p".DB_PWD." -t ".DB_NAME." vmdisk_log > vmdisk_log.lib";  
//    exec(" cd  ".$file_dir.";".$command.";tar  -zcvf   vmdisk_log.zip   vmdisk_log.lib");
    sript_exec_log(" cd  ".$file_dir.";".$command.";tar  -zcvf   vmdisk_log.zip   vmdisk_log.lib");
    $file=$file_dir."/".$file_name;  
    if(file_exists($file)){ 
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
//        exec("rm ".$file);
//        exec("rm ".$file_dir."/vmdisk_log.lib");
        sript_exec_log("rm ".$file);
        sript_exec_log("rm ".$file_dir."/vmdisk_log.lib");
        exit;
    }
        
}
$user_table = Database::get_main_table ( TABLE_MAIN_USER );
$tbl_dept=Database::get_main_table ( TABLE_MAIN_DEPT ); 
$objDept = new DeptManager ();

$display_admin_menushortcuts=(api_get_setting('display_admin_menushortcuts')=='true'?TRUE:FALSE);
include_once(api_get_path(SYS_CODE_PATH).'course/course.inc.php');
require_once (api_get_path(LIBRARY_PATH).'course.lib.php');
$htmlHeadXtra[]='<script type="text/javascript">
if(document.attachEvent)  window.attachEvent("onload",  iframeAutoFit);  
else  window.addEventListener("load",  iframeAutoFit,  false);
</script>

<style type="text/css">
.framePage {border-top-style:none;	width:100%;	padding-top:0px;	text-align:left;}
#Resources {width:100%;}
#Resources #treeview {	float:left;	border:#999 solid 1px;	width:20%;	}
#Resources #frm {	float:left;	width:79%;}
</style>';

$interbreadcrumb [] = array ('url' => api_get_path(WEB_ADMIN_PATH).'index.php', "name" => get_lang ( 'PlatformAdmin' ), 'target' => '_self' );
$interbreadcrumb [] = array ('url' => 'vm_list_iframe.php', "name" => get_lang ( 'AdminCategories' ), 'target' => '_self' );
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header();

require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

$table_user = Database::get_main_table ( TABLE_MAIN_USER );

$action = getgpc ( 'action', 'G' );
$dept_id = isset ( $_GET ['keyword_deptid'] ) ? getgpc ( 'keyword_deptid', 'G' ) : '0';

function get_sqlwhere() {
    global $restrict_org_id, $objCrsMng;
    
    //获取筛选开始时间
    $start_time=$_GET['start_date'];
    $start_date=$start_time['Y']."-".$start_time['m']."-".$start_time['d'];
    $start_date = strtotime($start_date) ;
    $start_date = date("Y-m-d H:i:s",$start_date);
    
    //获取筛选结束时间
    $end_time=$_GET['end_date'];
    $end_date=$end_time['Y']."-".$end_time['m']."-".$end_time['d'];
    $end_date = strtotime($end_date) ;
    $end_date = date("Y-m-d H:i:s",$end_date+24*3600);
    
    $sql_where = "";
    if (is_not_blank ( $_GET ['keyword'] ) && $_GET ['keyword']!="输入用户名") {
        $keyword = Database::escape_string (getgpc("keyword","G"), TRUE );
         $usrre=mysql_query('select user_id from user where username like \'%'.trim($keyword).'%\'');
         while($usrrow=mysql_fetch_row($usrre)){
             $userid.=$usrrow[0];
         }
        $sql_where .= " AND (  `username` LIKE '%" . trim ( $keyword ) . "%'
                            or `id` LIKE '%" . trim ( $keyword ) . "%'
                            or `user_ip` LIKE '%" . trim ( $keyword ) . "%'
                            or `system` LIKE '%" . trim ( $keyword ) . "%'
                            or `lesson_id` LIKE '%" . trim ( $keyword ) . "%'
                            or `vmid` LIKE '%" . trim ( $keyword ) . "%'
                            or `mac_id` LIKE '%" . trim ( $keyword ) . "%'
                            or `proxy_port` LIKE '%" . trim ( $keyword ) . "%'
                            or `mac_id` LIKE '%" . trim ( $keyword ) . "%'
                            or `end_time` LIKE '%" . trim ( $keyword ) . "%'
                            or `start_time` LIKE '%" . trim ( $keyword ) . "%'
                            or `addres` LIKE '%" . trim ( $keyword ) . "%' ";
       if($userid){
           $sql_where.="or `user_id` LIKE '%" .$userid. "%')";
       }else{
           $sql_where.=')';
       } 
    }
    if($start_date!='1970-01-01 08:00:00'){
         $sql_where.=" AND start_time > binary '".$start_date."'";
    }
     if($start_date!='1970-01-01 08:00:00'){
         $sql_where.=" AND start_time < binary '".$end_date."'";
     }
    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}


function get_number_of_vm() {
    $vm_log = Database::get_main_table (vmdisk_log);
    $sql = "SELECT COUNT(id) AS total_number_of_items FROM " . $vm_log;
    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;

    return Database::getval ( $sql, __FILE__, __LINE__ );
}

function get_vm_data($from, $number_of_items, $column, $direction) {
    $vm_log = Database::get_main_table ( vmdisk_log);
    $sql = "select `id`,`username`,`user_ip`,`addres`,`system`,`lesson_id`,`vmid`,`mac_id`,`proxy_port`,`manage`,`close_status`,`start_time`,`end_time`,`id` FROM  $vm_log ";
    
    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;
    $sql.= " ORDER BY  `start_time` DESC ";
    $sql .= " LIMIT $from,$number_of_items";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $vm= array ();

    while ( $vm = Database::fetch_row ( $res) ) {
        $vm['9']=$vm['9']=='0' ? "前台" : "后台";
        $vms [] = $vm;
    }

    return $vms;
}
function course_info($lessonid){
    $sql="select  `title`  from  `course`  where  `code`=".$lessonid;
    $title=DATABASE::getval($sql);
    return  $title;
}  
function close_status($close_status){
    if($close_status==0){
        return  "用户关闭";
    }else if($close_status==1){
        return  "系统关闭";
    }
}
//处理批量操作 
if (isset ( $_POST ['action'] )) {  
    switch (getgpc("action","P")) {
        case 'delete_vms' :   
            $deleted_vm_count = 0;
            $vm_id = $_POST ['vmdisk_log'];     
            if (count ( $vm_id ) > 0) {
                foreach ( $vm_id as $index => $id ) {
        
                    $sql = "DELETE FROM  `vmdisk_log` WHERE id='" .$id. "'";
                    api_sql_query ( $sql, __FILE__, __LINE__ );

                    $log_msg = get_lang('删除所选') . "id=" .$id;
                    api_logging ( $log_msg, 'vmdisk_log', 'dfgdfgdfg' );
                }
            }
        
    }
}

//delete vmlog
if(isset($_GET['action']) && $_GET['action']=='delete_vm'){
    $id=(int)$_GET['id'];
    $query=mysql_query('delete from vmdisk_log where id='.$id);
    if($query){
        tb_close('vmdisk_log_list.php');
    }
}

//export vmlog
if(isset($_GET['action']) && $_GET['action']=='export_log_xls'){

	Export::export_data ( $export_data, $filename, 'xls' );
}

//clear all logs
if(isset($_GET['action']) && base64_decode($_GET['action'])=='all'){  
   $query=mysql_query('truncate table vmdisk_log');
   if($query){
       tb_close('vmdisk_log_list.php');
   }
}

function deleterow($id){
    if (api_is_platform_admin ()) {
        $result .= '&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'vmdisk_log_list.php?action=delete_vm&id=' . intval($id) );
    }
    return $result;
}
$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:140px", 'class' => 'inputText', 'id'=>'searchkey','value'=>'输入用户名' ) );
$form->addElement ( 'date', 'start_date', '开始日期时间：', array ('format' => '        开始时间:Y 年m 月d'), array ('show_time' => TRUE ) );
$form->addElement ( 'date', 'end_date', '开始日期时间：', array ('format' => '        截止时间:Y 年m 月d'), array ('show_time' => TRUE ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ["keyword"] )) {
    $parameters ['keyword'] = getgpc ( 'keyword', 'G' );
    $parameters = array ('keyword' => getgpc('keyword'), 'keyword_status' => getgpc('keyword_status'), 'keyword_org_id' => getgpc("keyword_org_id") );
}

if (is_not_blank ( $_GET ["keyword_org_id"] )) $parameters ['keyword_org_id'] = trim ( getgpc("keyword_org_id") );
if ($dept_id) $parameters ['keyword_deptid'] = $dept_id;


$table = new SortableTable ( 'vmdisk_log', 'get_number_of_vm', 'get_vm_data', 2, NUMBER_PAGE );
$table->set_additional_parameters ( $parameters );

$actions = array ('delete' => get_lang ( 'BatchDelete' ) );
$table->set_form_actions ( $actions );

$idx = 0;
$table->set_header ( $idx ++, '', false, null,null );
//$table->set_header ( $idx ++, get_lang ('编号'), false, null, array ('style' => 'width:4%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ( '用户名' ), false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ( '用户IP' ), false, null, array ('style' => 'width:7%;text-align:center' ));
$table->set_header ( $idx ++, get_lang ('虚拟机ip'), false, null, array ('style' => 'width:8%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ( '虚拟机名称' ) , false, null, array ('style' => 'width:12%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ( '课程名称' ), false, null, array ('style' => 'width:18%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ('虚拟机编号'), false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ('mac地址'), false, null, array ('style' => 'width:8%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ('端口'), false, null, array ('style' => 'width:3%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ('前台/后台'), false, null, array ('style' => 'width:7%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ('关闭状态'), false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ( '开启时间' ), false, null, array ('style' => 'width:10%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ( '关闭时间' ), false, null, array ('style' => 'width:10%;text-align:center' ) );
$table->set_header ( $idx ++, get_lang ( '删除' ), false, null, array ('style' => 'width:4%;text-align:center' ) );
$table->set_form_actions ( array ('delete_vms' => '删除所选项' ), 'vmdisk_log' );
$table->set_column_filter ( 5, 'course_info' );
$table->set_column_filter ( 10, 'close_status' );
$table->set_column_filter (13,'deleterow');
?>
<aside id="sidebar" class="column systeminfo  open">
    <div id="flexButton" class="closeButton close">
    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark" style="width:98%;margin:20px 1% 0 2%;">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/log/logging_list.php">日志管理</a> &gt; 虚拟机日志</h4>
    <div class="managerSearch" style="width:97%;margin:20px 1% 0 2%;">
      <?php $form->display ();?>
     <a class="one-delete" href="vmdisk_log_list.php?action=<?php echo base64_encode("all");?>" onclick="javascript:if(!confirm('是否全部删除?')) return false;"><span class="img-span"></span><span class="span-text">一键清除</span></a>
     <!--<a class="one-delete" href="vmdisk_log_list.php?action=export_log" onclick="javascript:if(!confirm('是否导出?')) return false;"><span  class="img-span1"></span><span class="span-text">导出</span></a>&nbsp;-->
      
     <!--<div class="one-delete one-import"><?php echo link_button ( 'import.png', '导入','../import_logs.php', '30%', '40%' ); ?></div>-->
             <span class="searchtxt right">
            <?php
            $report_count=Database::getval('SELECT COUNT(id) FROM `vmdisk_log`',__FILE__,__LINE__);
            if($report_count > 0){
                echo '&nbsp;&nbsp;' . link_button ( 'excel.gif', '导出虚拟机日志', 'vmdisk_log_export.php', '50%', '50%'  );
            }
            ?>
        </span>
    </div>
    
    <article class="module width_full hidden" style="width:98%;margin:20px 1% 0 2%;">
        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">
               <?php $table->display ();?>
            </table>
        </form>
    </article>
</section>

<style>
 
   .managerSearch select {
    background: #FFFFFF;
    border: 0 none;
    height: 27px;
    vertical-align: middle;
    border: 1px solid #CCCCCC;
    text-align: center;
    margin-left: 10px;
    width: auto;
}
    
    .one-delete {
        float:right;
        display:block;
/*        margin-right:8px;*/
        padding:0 7px;
        position:relative;
    }
    .one-import{
/*       height:30px;*/
    }
    .one-import a{
        vertical-align:top;
    }
    .one-import a img{
        margin-top:-4px;
    }
    .one-delete  span{
        margin:0;
    }
    .one-delete  .img-span{
        display:inline-block;
        position:absolute;
        top:11px;
        left:0px;
        height:25px;
        width:25px;
        margin-right:5px;
        background:url("<?=api_get_path ( WEB_PATH )?>themes/images/one_delete.png")  no-repeat;
        
    }   
    .one-delete  .span-text{
       display:inline-block;
       padding:0 0 0 15px;
    }
    .img-span1{
        display:inline-block;
        position:absolute;
        top:11px;
        left:0px;
        height:25px;
        width:25px;
        margin-right:5px;
        background:url("<?=api_get_path ( WEB_PATH )?>themes/img/export.png")  no-repeat;
        
    }   
</style>
</body>
</html>
