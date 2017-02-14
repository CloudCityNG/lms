<?php
/**
==============================================================================
 * 网络拓扑设计
==============================================================================
 */
$language_file = 'admin';
$cidReset = true;

include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
$USERID=$_SESSION['_user']['user_id'];

if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'labs_labs'"))!=1){
    $sql_insert ="CREATE TABLE IF NOT EXISTS `labs_labs` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` text NOT NULL,
              `labs_category` int(11) NOT NULL,
              `description` text NOT NULL,
              `info` text NOT NULL,
              `netmap` text NOT NULL,
              `diagram` int(11) DEFAULT NULL,
              `folder` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0";
    api_sql_query ( $sql_insert,__FILE__, __LINE__ );
}

$url_routercourse= glob(URL_ROOT."/www".URL_APPEDND."/storage/routecourses");
$routercourse_url=$url_routercourse[0];

sript_exec_log("chmod -R 777 /tmp/");
if(!file_exists($routercourse_url)){
//    exec("cd ".URL_ROOT."/www".URL_APPEDND."/storage/ ; mkdir routecourses ");
//    exec("chmod -R 777 routecourses");
    sript_exec_log("cd ".URL_ROOT."/www".URL_APPEDND."/storage/ ; mkdir routecourses ");
    sript_exec_log("chmod -R 777 routecourses");
}

$topoId=  getgpc("topoId","G");
if(isset($_GET['action']) && $_GET['action']=='save' && $topoId!==''){
    if($topoId && $USERID){
       exec("chmod -R 777 /tmp/");
//       sript_exec_log;
       $url='/tmp/mnt/iostmp/';
       $url_user=$url.$USERID;
       $time_dir=date("YmdHi");
       if(!file_exists($url_user)){
           exec("mkdir -p ".$url_user);
           exec("chmod -R 777 ".$url_user);
//           sript_exec_log;
//           sript_exec_log;
       }
       if(file_exists($url_user)){
            exec("chmod -R 777 $url_user/");
           // sript_exec_log;
            $exec_var1="cd ".$url_user."/; rm  -rf ".$topoId."-*.lib";
            $exec_var2="cd ".$url_user."/; tar -zcvf ".$topoId."-".$time_dir.".lib ".$topoId;
            sript_exec_log($exec_var1);
            sript_exec_log($exec_var2);
            
            $url_tar=$url_user."/".$topoId."-*.lib";
            $url_routercourse=URL_ROOT."/www".URL_APPEDND."/storage/routecourses";
           
            sript_exec_log("chmod -R 777 ".$url_tar);
            sript_exec_log("chmod -R 777 ".$url_routercourse."/*");
            $exec_var3="cd ".$url_routercourse."/;rm -rf ".$topoId."-*.lib ";
            $exec_var4="cd ".$url_user."/; mv -f ".$topoId."-*.lib ".$url_routercourse."/";
            sript_exec_log($exec_var3);
            sript_exec_log($exec_var4);
       }
       header("Location: ./labs_topo.php?action=save&topoId=".$topoId);
   }
   header("Location: ./labs_topo.php");
}
if(isset($_GET['action']) && $_GET['action']=='export' && $topoId!==''){
    echo 'ecport';
}

function description_filter($description){
    $result ='';
    $result.='<span style="float:left">&nbsp;'.$description.'</span>';
    return $result;
}
function get_tgz_path($filename,$cate_id){ 
    $paths=URL_ROOT."/www".URL_APPEDND."/storage/";//local 
    $files1 = glob($paths.$filename."/".$cate_id.'-*.lib');
    $tgz_path=$files1[0];
     return $tgz_path;
}
function saveConf_filter($info,$url_params,$row){
    $result ='';
    $topoId=trim($row[0]);
    $url_router= glob(URL_ROOT."/www".URL_APPEDND."/storage/routecourses/".$topoId."-*.lib");
    if(!file_exists($url_router[0])){
        $save_img='backup.png';
    }else{
        $save_img='backup_na.png';
    }
    $result.=confirm_href ( $save_img, '你确定要保存实验环境吗？', '保存实验环境', 'labs_topo.php?action=save&topoId=' . $topoId)."&nbsp;";
//    $result.=confirm_href ( 'folder_zip.gif', '你确定要导出实验环境吗？', '导出实验环境', 'labs_topo.php?action=export&topoId=' . $topoId);
    return $result;
}

function device_filter($id){ 
    $result = link_button ( 'exe.gif', '', 'labs_device_add.php?action=device_add&id='.$id, '90%', '70%' );
    return $result;
}
function category_filter($labs_category){
    $result='';
    $labs_category_sql="select `name` from  `labs_category` where `id`=".$labs_category;
    $result.=DATABASE::getval($labs_category_sql,__FILE__,__LINE__);
    return $result;
}
function net_filter($id){
    $sql="select `name` from `labs_labs` where `id`='".$id."'";
    $name=DATABASE::getval($sql,__FILE__,__LINE__);
    $result="";
    $topo_design="设计拓扑";
    $result .= link_button ( 'conf.gif', "设计拓扑", '../../../topoDesign/topoDesign.php?action=design&name='.$name.'&id='.$id, '90%', '70%' ,FALSE);
    return $result;
}

function shiyan($id){
    
    $squ=mysql_query('select id,document_path from labs_document where labs_id='.$id.' order by id desc limit 1');
    $sqarr=mysql_fetch_assoc($squ);
    if(!$sqarr['document_path']){
       return "<a title='上传实验手册' class='thickbox' style='vertical-align:baseline;' href='document_upload.php?sid=$id&&KeepThis=true&TB_iframe=true&modal=true&width=60%&height=50%'>上传</a>";
    }else{
       $src = api_get_path ( WEB_CODE_PATH ) . 'courseware/link_goto.php?manuid='.$sqarr['id'];               
       $document_url=WEB_QH_PATH . 'document_viewer.php?manu=abc&url='.urlencode($src); 
       $str="<a title='查看实验手册' target='_blank' href='$document_url'>查看</a>&nbsp;&nbsp;
             <a title='上传实验手册' class='thickbox' style='vertical-align:baseline;' href='document_upload.php?sid=$id&&KeepThis=true&TB_iframe=true&modal=true&width=60%&height=50%'>上传</a>"; 
       return $str; 
    }    
 }

function net_devices($id){  
    $name_sql="select `name` from `labs_labs` where id='".$id."'";
    $labs_name=Database::getval($name_sql,__FILE__,__LINE__);
    $num_devs=DATABASE::getval("select  count(`id`)  from  labs_devices  where  `lab_id`='".$labs_name."'");
     return link_button ( 'laptop_small.gif', $num_devs.'个', 'net_devices.php?lab_id='.$id, '80%', '80%' ,TRUE);
}
function upload_environment($id){
    $result = link_button ( 'file_zip.gif', '', 'upload_environment.php?id='.$id, '40%', '50%' );
    return $result;
} 
function edit_filter($id) {
    $result = "";
    global $_configuration, $root_user_id;
    if (api_is_admin() && ! in_array ( $id, $root_user_id ) || $_SESSION['_user']['status']=='1') {
        $result .=  link_button ( 'edit.gif', 'Edit', 'labs_topo_edit.php?id='.$id, '90%', '70%', FALSE );
    }
    return $result;
}
function delete_filter($id) {
    $result = "";
    global $_configuration, $root_user_id;
    if (api_is_admin() && ! in_array ( $id, $root_user_id ) || $_SESSION['_user']['status']=='1') {
        $result .= confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'labs_topo.php?action=delete&id=' . $id );
    }
    return $result;
}
$action=getgpc('action','G');

if (isset ($action)) {
    switch ($action) {
        case 'delete' :
            $delete_id= intval(getgpc('id','G'));
            if ( isset($delete_id)){
                $name_sql="select `name` from `labs_labs` where id='".$delete_id."'";
                $labs_name=Database::getval($name_sql,__FILE__,__LINE__);

                $sql = "DELETE FROM  `labs_labs` WHERE `labs_labs`.`id` = {$delete_id}";
                $result = api_sql_query ( $sql, __FILE__, __LINE__ );

                $devices_sql="DELETE FROM  `labs_devices` WHERE `labs_devices`.`lab_id`='".$labs_name."'";
                $a=api_sql_query($devices_sql,__FILE__,__LINE__);

                $redirect_url = "labs_topo.php";
                tb_close ( $redirect_url );
            }
            break;
    }
}
if (isset ( $_POST ['action'] )) {
    switch ($_POST ['action']) {
        case 'deletes' :
            $labs =$_POST['labs']; 
            if (count ( $labs ) > 0) {
                foreach ( $labs as $index => $id ) {

                    $name_sql="select `name` from `labs_labs` where id='".$id."'";
                    $labs_name=Database::getval($name_sql,__FILE__,__LINE__);

                    $sql = "DELETE FROM  `labs_labs` WHERE `labs_labs`.`id` = {$id}";
                    $result = api_sql_query ( $sql, __FILE__, __LINE__ );

                    $devices_sql="DELETE FROM  `labs_devices` WHERE `labs_devices`.`lab_id`='".$labs_name."'";
                    $a=api_sql_query($devices_sql,__FILE__,__LINE__);

                    $log_msg = get_lang('删除所选') . "id=" . $id;
                    api_logging ( $log_msg, 'labs', 'labs' );
                }
            }
            break;
    }
}

function get_sqlwhere() {
    $sql_where = "";
    $keyword = Database::escape_string ( $_GET ['keyword'], TRUE );
    if (is_not_blank ( $keyword )) {
        if($keyword=='输入搜索关键词'){
            $keyword='';
        } 
        $sql_where .= " AND (`id` LIKE '%" . trim ( $keyword ) . "%' OR `name` LIKE '%" . trim ( $keyword ) . "%'
        OR `description` LIKE '%" . trim ( $keyword ) . "%'
        OR `info` LIKE '%" . trim ( $keyword ) . "%')";
    }
    if (is_not_blank ( $_GET ['id'] )) {
        $sql_where .= " AND id=" . Database::escape ( intval(getgpc ( 'id', 'G' )) );
    }
    if (is_not_blank ( $_GET ['lab_cate'] )) {
        $sql_where .= " AND `labs_category`=" . Database::escape ( getgpc ( 'lab_cate', 'G' ) );
    }
    
    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}

function get_number_of_labs_topo() {
    $labs_topo = Database::get_main_table (labs_labs);
    $sql = "SELECT COUNT(id) AS total_number_of_items FROM " . $labs_topo;
    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;
    return Database::getval ( $sql, __FILE__, __LINE__ );
}
function get_labs_topo_data($from, $number_of_items, $column, $direction) {
    $labs_topo = Database::get_main_table (labs_labs);
    $sql = "select `id`,`id`,`name`,`labs_category`,`description`,`id`,`id`,`id`,`id`,`id`,`id`,`id` FROM ".$labs_topo;

    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;

    $sql .= " order by `id`";
    $sql .= " LIMIT $from,$number_of_items";  
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $arr= array ();

    while ( $arr = Database::fetch_row ( $res) ) {
        $arrs [] = $arr;
    }
    return $arrs;
}

$tool_name = get_lang ( 'CourseList' );
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( $tool_name );



$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:200px", 'class' => 'inputText', 'value'=>'输入搜索关键词','id'=>'searchkey','title' => $keyword_tip ) );
        
$sql = "SELECT  `id`,`name` FROM  `labs_category`";
$res=  api_sql_query_array($sql);
foreach ( $res as  $v1){ 
   $labs_cates[$v1['id']]  = $v1['name'];
}
$labs_cates[""] = "---所有分类---";
ksort($labs_cates);
$form->addElement ( 'select', 'lab_cate', get_lang ( '拓扑' ), $labs_cates, array ('style' => 'min-width:150px;height:23px;border: 1px solid #999;' ) );

$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );



$table = new SortableTable ( 'labs', 'get_number_of_labs_topo', 'get_labs_topo_data',2, NUMBER_PAGE  );

$table->set_additional_parameters ( $parameters );
$idx=0;
$table->set_header ( $idx ++, '', false );
$table->set_header ( $idx ++, '编号', false, null, array ('style' => ' text-align:center;width:3%' ));
$table->set_header ( $idx ++, '课程名称', false, null, array ('style' => 'text-align:left;width:20%' ) );
$table->set_header ( $idx ++, '课程分类', false, null, array ('style' => 'text-align:center;width:9%' ) );
$table->set_header ( $idx ++, '课程描述' , false, null, array ('style' => 'width:30%;text-align:left;' ) );
//$table->set_header ( $idx ++, '实验环境', false, null, array ('style' => 'width:5%; text-align:center;' ) );
$table->set_header ( $idx ++, '添加设备', false, null, array ('style' => 'width:5%; text-align:center;' ) );
$table->set_header ( $idx ++, '拓扑设计', false, null, array ('style' => 'width:5%; text-align:center;' ) );
$table->set_header ( $idx ++, '设备数量', false, null, array ('style' => 'width:5%; text-align:center;' ) );
$table->set_header ( $idx ++, '上传指导书', false, null, array ('style' => 'width:6%;text-align:center' ) );
$table->set_header ( $idx ++, '上传实验环境', false, null, array ('style' => 'width:7%; text-align:center;' ) );
$table->set_header ( $idx ++, '编辑', false, null, array ('style' => 'width:4%;text-align:center' ) );
$table->set_header ( $idx ++, '删除', false, null, array ('style' => 'width:4%;text-align:center' ) );

$table->set_form_actions ( array ('deletes' => '删除所选项' ), 'labs' );
$table->set_column_filter ( 3, 'category_filter' );
$table->set_column_filter ( 4, 'description_filter' );
//$table->set_column_filter ( 5, 'saveConf_filter' );
$table->set_column_filter ( 5, 'device_filter' );
$table->set_column_filter ( 6, 'net_filter' );       
$table->set_column_filter ( 7, 'net_devices' );
$table->set_column_filter ( 8, 'shiyan' ); 
$table->set_column_filter ( 9, 'upload_environment' ); 
$table->set_column_filter ( 10, 'edit_filter' );
$table->set_column_filter ( 11, 'delete_filter' );


//Display::display_footer ( TRUE );
?>

<aside id="sidebar" class="column course open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/router/labs_ios.php">路由管理</a> &gt; 路由交换课程</h4>
    <div class="managerSearch">
        <?php $form->display ();?>
        <span class="searchtxt right">
        <?php echo '&nbsp;&nbsp;' . link_button ( 'add.gif', '添加', 'labs_topo_add.php', '90%', '70%' );?>
        </span>
    </div>
    <article class="module width_full hidden">
        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">

                <?php $table->display ();?>
            </table>
        </form>
    </article>

</section>
