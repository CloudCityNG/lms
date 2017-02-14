<?php
$language_file = array ('courses','admin');
$cidReset=true;
include_once ("../../inc/global.inc.php");
$this_section=SECTION_PLATFORM_ADMIN;

api_protect_admin_script ();

//if(!isset($_GET['dhcp_page_nr']) && !isset($_GET ['keyword']) && !isset($_GET ['vmaddres'])) {
//    exec("sudo -u root /sbin/clouddhcplease.sh;");
//    exec("sudo -u root /sbin/cloudscanning.sh dhcp;");
//}

$display_admin_menushortcuts=(api_get_setting('display_admin_menushortcuts')=='true'?TRUE:FALSE);
include_once(api_get_path(SYS_CODE_PATH).'course/course.inc.php');
require_once (api_get_path(LIBRARY_PATH).'course.lib.php');
$htmlHeadXtra[]='<script type="text/javascript">
if(document.attachEvent)  window.attachEvent("onload",  iframeAutoFit);  
else  window.addEventListener("load",  iframeAutoFit,  false);
</script>';

$interbreadcrumb [] = array ('url' => api_get_path(WEB_ADMIN_PATH).'index.php', "name" => get_lang ( 'PlatformAdmin' ), 'target' => '_self' );
$interbreadcrumb [] = array ('url' => 'vm_list_iframe.php', "name" => get_lang ( 'AdminCategories' ), 'target' => '_self' );


$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();
//Display::display_header ( NULL, FALSE );
//Display::display_header();

include ('../../inc/header.inc.php');

/*
 *
 * 获取远程服务器信息
 */

$table = Database::get_main_table ( vmtotal);
$sql = "select distinct addres,id FROM  $table group by addres";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );

$vm= array ();
$j = 0;
//var_dump($res);
while ( $vm = Database::fetch_row ( $res) ) {
    $j++;
    $vms [$j] = $vm[0];
}

$objCrsMng = new CourseManager ();
$vmaddres = getgpc('vmaddres');
$objCrsMng = new CourseManager ();

/**search USER**/
$sql1 = "SELECT `user_id`,`username` FROM `vslab`.`user`";
$data_list = api_sql_query_array_assoc ( $sql1, __FILE__, __LINE__ );
$data=array();
foreach ( $data_list as $tem){
    $uid=' '.$tem['user_id']." ";
    $user=$tem['username'];
    $data[$uid]=$user;
}
/**COURSE**/
$sql1 = "SELECT `code`,`title` FROM `vslab`.`course`";
$data_list = api_sql_query_array_assoc ( $sql1, __FILE__, __LINE__ );
$category_tree=array();
foreach ( $data_list as $tem){
    $code=' '.$tem['code']." ";
    $title=$tem['title'];
    $category_tree[$code]=$title;
}
/**EXAM**/
$sql1 = "SELECT `id`,`title` FROM `vslab`.`exam_main`";
$data_list = api_sql_query_array_assoc ( $sql1, __FILE__, __LINE__ );
$all_exam=array();
foreach ( $data_list as $tem){
    $ids=' '.$tem['id']." ";
    $title=$tem['title'];
    $all_exam[$ids]=$title;
}

function  lesson_filter($code) {
    $user = "select title from course where code = $code";
    $result =Database::getval($user , __FILE__, __LINE__);
    if($result==''){
        $result =Database::getval("select `title` from `exam_main` where id=".$code, __FILE__, __LINE__);
    }
    return $result;

}
function  con_filter($id, $url_params, $row) {
    $result='';
    $System   =  $row[3];
    $LocalIps = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
    $Locaarr=  explode(':',$LocalIps);
    $LocalIp=$Locaarr[0];
    $LessonId = Database::getval ( 'select `lesson_id` from `vmtotal` where id='.$id, __FILE__, __LINE__ );
    $ProxyPort= Database::getval ( 'select `proxy_port` from `vmtotal` where id='.$id, __FILE__, __LINE__ );
    
    $result="<a href='http://$LocalIps/lms/main/html5/cloudauto.php?lessonId=$LessonId&host=$LocalIp&port=$ProxyPort&system=$System' target='_blank'>远程协助</a>";
    return $result;
}

function user_filter($active, $url_params, $row) {

    $user = "select username from user where user_id = $row[4]";
    $res = api_sql_query($user , __FILE__, __LINE__);
    $result =  Database::fetch_row ( $res);
    return $result[0];
}
function monitor_filter($id, $url_params, $row) {
    $result='';
    $user_id=Database::getval("select `user_id` from `vmtotal` where id=".$row[0], __FILE__, __LINE__);
    $system =Database::getval("select `system` from `vmtotal` where id=".$row[0], __FILE__, __LINE__);
    $addres=$row[1];
    $sql='select count(*) from `vm_monitor` where  `system`="'.$system.'" and `addres`="'.$addres.'" and `user_id`="'.$user_id.'" and `manage`=0 ';
    if($user_id!=='' && $system!=='' && $addres!==''){
        $vm_monitor=Database::getval ( $sql, __FILE__, __LINE__ );
    }

    if($user_id!=='' && $system!==''){
        $vm_monitor=Database::getval ( 'select count(*) from `vm_monitor` where  `system`="'.$system.'" and `user_id`="'.$user_id.'" and `manage`=0 ', __FILE__, __LINE__ );
    }
    
    if($vm_monitor){
        $result .=  link_button ( 'conf.gif', '监控&nbsp;'.$system , 'vmmonitor.php?vm=' . $row[0], '100%', '85%', FALSE );
    }else{
        $result .= Display::return_icon ( 'conf_na.gif', "没有监控信息", array ('style' => 'vertical-align: middle;', 'width' => 22, 'height' => 22 ));
    }
    return $result;
}
function close_filter($id) {
    $result = confirm_href ( 'stop_small.png', '您确定要关闭该虚拟实验吗？', '', 'vmmanage_iframe.php?action=shutvm&id='.$id);
    return $result;
}
function time_filter($id) { 
    $enddate=Date("Y-m-d H:i:s");
    $startdate    = Database::getval("select `stime` from `vmtotal` where `id`=".$id,__FILE__,__LINE__);
    $result=floor((strtotime($enddate)-strtotime($startdate))%86400/60);
    return $result."min";
}
if($_GET['action']=='shutvm' && $_GET['id']!==''){
    $ids=intval(getgpc('id','G'));
    $addres=Database::getval('select `addres` from `vmtotal` where `id`='.$ids,__FILE__,__LINE__);
    $sql1 = "select `id`,`vmid`,`user_id`,`addres` FROM  `vmtotal` where `addres`= '".$addres."'";
    $res = api_sql_query_array_assoc ( $sql1, __FILE__, __LINE__ );

    $vmid  =$res[0]['vmid'];
    $addres=$res[0]['addres'];
        if($vmid && $addres && $_SESSION['_user']['username']){
//            $output = exec("sudo -u root /usr/bin/ssh root@$addres /sbin/cloudvmstop.sh $vmid");
           sript_exec_log("sudo -u root /usr/bin/ssh root@$addres /sbin/cloudvmstop.sh $vmid");
        }
    $sqla="delete from `vmtotal` where `id` =".$ids;
    api_sql_query ( $sqla, __FILE__, __LINE__ );
}
function get_sqlwhere() {
    global $restrict_org_id, $objCrsMng;

    $sql_where = "";
    $vmaddres = getgpc('vmaddres');
    if($vmaddres){
        $sql_where.="AND addres = '".$vmaddres."'";
    }

    $g_keyword=  getgpc('keyword','G');
    if($g_keyword=='请输入节点地址'){
        $g_keyword='';
    }
    if (is_not_blank ( $g_keyword )) {
        $keyword = Database::escape_string ($g_keyword, TRUE );
        $sql_where .= " AND (id LIKE '%" . intval( $keyword ) . "%' OR addres LIKE '%" . trim ( $keyword ) . "%' OR nicnum LIKE '%" . trim ( $keyword ) . "%' OR system LIKE '%" . trim ( $keyword ) . "%')";
    }
    $user=  intval(getgpc("user"));
    if (is_not_blank ( $user)) {
        $sql_where .= " AND user_id=" .$user;
    }
    $course=  intval(getgpc('course'));
    if (is_not_blank ( $course)) {
        $sql_where .= " AND `lesson_id`=" .$course;
    }
    $exam=  intval(getgpc('exam'));
    if (is_not_blank ( $exam)) {
        $sql_where .= " AND `lesson_id`=" .$exam;
    }
    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}
function get_number_of_vmtotal() {
    $vmtotal = Database::get_main_table ( vmtotal);
    $sql = "SELECT COUNT(id) AS total_number_of_items FROM " . $vmtotal." WHERE `manage`=0 ";

    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " and  " . $sql_where;
    return Database::getval ( $sql, __FILE__, __LINE__ );
}
function get_vm_data($from, $number_of_items, $column, $direction) {
    $sql = "select `id` ,`addres` , `nicnum` , `system` ,`user_id` ,`lesson_id` ,`vmid` ,`port` ,`monitor` ,`mac_id`, `id`, `id` ,`id` FROM vmtotal WHERE `manage`=0 ";
    $sql_where = get_sqlwhere ();
    if ($sql_where){
        $sql .= " and " . $sql_where;
    }

    $sql .= " ORDER BY  `id` DESC ";
    $sql .= " LIMIT $from,$number_of_items";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );

    $vm= array ();

    while ( $vm = Database::fetch_row ( $res) ) {
        $vms [] = $vm;
    }
    return $vms;
}

$tool_name = get_lang ( 'CourseList' );

$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:150px", 'class' => 'inputText','value'=>'请输入节点地址','id'=>'searchkey', 'title' => $keyword_tip ) );

array_unshift($data,'---所有用户---');
$form->addElement ( 'select', 'user', get_lang ( '用户' ), $data, array ('style' => 'min-width:150px;height:27px;border: 1px solid #999;' ) );

if($platform==3){
    array_unshift($all_exam,'---所有试卷---');
    $form->addElement ( 'select', 'exam', get_lang ( '试卷名称' ), $all_exam, array ('style' => 'min-width:150px;height:27px;border: 1px solid #999;' ) );
}else{
    array_unshift($category_tree,'---所有课程---');
    $form->addElement ( 'select', 'course', get_lang ( '课程名称' ), $category_tree, array ('style' => 'min-width:150px;height:27px;border: 1px solid #999;' ) );
}

$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );

$parameters = array ( 'keyword' => getgpc ( 'keyword', 'G' ), 'vmaddres' => $vmaddres );
$table = new SortableTable ( 'vmdisk', 'get_number_of_vmtotal', 'get_vm_data', 0, 10, 'ASC' );
$table->set_additional_parameters ( $parameters );
$table->set_header ( 0, '序号', false, null);
$table->set_header ( 1, '地址', false, null, array ('style' => 'width:10%;text-align:center' ) );
$table->set_header ( 2, 'nicnum', false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( 3, '系统' , false, null, array ('style' => 'width:10%;text-align:center' ) );
$table->set_header ( 4, '用户名', false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( 5, '项目名称', false, null, array ('style' => 'width:25%;text-align:center' ) );
$table->set_header ( 6, 'vmid', false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( 7, 'port', false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( 8, '监控', false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( 9, 'MAC地址', false, null, array ('style' => 'width:8%;text-align:center' ) );
$table->set_header ( 10, '运行时间', false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( 11, '远程协助', false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( 12, '关闭', false, null, array ('style' => 'text-align:center' ) );

$table->set_column_filter ( 4, 'user_filter' );
$table->set_column_filter ( 5, 'lesson_filter' );
$table->set_column_filter ( 8, 'monitor_filter' );
$table->set_column_filter ( 10, 'time_filter' );
$table->set_column_filter ( 11, 'con_filter' );
$table->set_column_filter ( 12, 'close_filter' );

?>
<aside id="sidebar" class="column cloud open">
    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/cloud_menu.php">云平台</a> &gt;虚拟化管理</h4>
    <div  class="managerSearch">
        <?php  $form->display ();?>
        <span width="90%" class="searchtxt right"></span>
    </div>
    <article class="module width_full hidden ip">
        <h3 class="vmip"><img src="<?=URL_APPEDND;?>/themes/images/ipicon.gif" width="20px" height="20px">  节点地址</h3>
        <div class="vmContent">

<?php
            foreach ( $vms as $k1 => $v1){
                $vmaddres_count='';
                if($v1!==''){
                    $vmaddres_count=DATABASE::getval("select count(*) from `vmtotal` where `addres`='".$v1."' and `manage`=0",__FILE__,__LINE__);
                }
                echo "<dl>
                <dt>$vmaddres_count</dt>
                <dd><a href=vmmanage_iframe.php?vmaddres=$vms[$k1]>$v1</a></dd>
            </dl>";
        }?>

        </div>
    </article>

    <article class="module width_full hidden">


        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">
           <?php $table->display ();
                ?>
            </table>
        </form>
    </article>

</section>
</body>
</html>
