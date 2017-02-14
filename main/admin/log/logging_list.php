<?php
$language_file = array ('admin' );
$cidReset = true;
include_once ('../../inc/global.inc.php');
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
if(isset($_GET['action']) && $_GET['action']=='export_log'){ 
    $file_dir=URL_ROOT."/www".URL_APPEDND."/storage/DATA/logs";
    if(!file_exists($file_dir)){
        sript_exec_log("mkdir ".$file_dir);
        sript_exec_log("chmod  -R 777 ".$file_dir);
    }
    $file_name="sys_logging.zip";
    $command="mysqldump -u".DB_USER." -p".DB_PWD." -t ".DB_NAME." sys_logging > sys_logging.lib";   
    sript_exec_log(" cd  ".$file_dir.";".$command.";tar  -zcvf   sys_logging.zip   sys_logging.lib");
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
        sript_exec_log("rm ".$file);
        sript_exec_log("rm ".$file_dir."/sys_logging.lib");
        exit;
    }
        
}
function get_sqlwhere() {

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
        if (is_not_blank ( $_GET ['keyword'] ) && $_GET ['keyword'] != "搜索关键词") {
            $keyword = trim ( Database::escape_str (getgpc("keyword","G"), TRUE ) );
            $sql_where .= " AND  (display_name LIKE '%" . $keyword . "%'  OR username LIKE '%" . $keyword . "%'  OR message LIKE '%" . $keyword . "%')";
        }
            if($start_date!='1970-01-01 08:00:00'){
             $sql_where.=" AND log_date > binary '".$start_date."'";
        }
         if($start_date!='1970-01-01 08:00:00'){
             $sql_where.=" AND log_date < binary '".$end_date."'";
         }
        $sql_where = trim ( $sql_where );
        return substr ( $sql_where, 3 );
}

function get_number_of_data() {
    $table_sys_logging = Database::get_main_table ( TABLE_MAIN_SYS_LOGGING );
    $sql = "SELECT COUNT(id) AS total_number_of_items FROM $table_sys_logging ";
    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;
    return Database::get_scalar_value ( $sql );
}

function get_data($from, $number_of_items, $column, $direction) {
    $table_sys_logging = Database::get_main_table ( TABLE_MAIN_SYS_LOGGING );
    $sql = "SELECT
	id		AS col0,
	log_date		AS col1,
	username		AS col2,
	display_name	AS col3,
	message		AS col4,
	access_uri		AS col5,
	id		AS col6
	FROM  $table_sys_logging  ";
    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;
    $sql .= " ORDER BY  log_date desc,  col$column $direction ";
    $sql .= " LIMIT $from,$number_of_items";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $data = array ();
    while ( $adata = Database::fetch_array ( $res, 'NUM' ) ) {
        $data [] = $adata;
    }
    return $data;
}

function modify_filter($log_id, $url_params) {
    $result .= '<a href="logging_list.php?action=delete_log&amp;id=' . intval($log_id) . '&amp;' . $url_params . '" onclick="javascript:if(!confirm(' . "'" . addslashes ( htmlentities ( get_lang ( "ConfirmYourChoice" ), ENT_NOQUOTES, SYSTEM_CHARSET ) ) . "'" . ')) return false;">' . Display::return_icon (
        'delete.gif', get_lang ( 'Delete' ), array ('style' => 'vertical-align: middle;' ) ) . '</a>';
    return $result;
}

function delete_log($log_id) {
    $table_sys_logging = Database::get_main_table ( TABLE_MAIN_SYS_LOGGING );
    $sql = "DELETE FROM $table_sys_logging WHERE id='" . Database::escape_string ( intval($log_id) ) . "'";
    return api_sql_query ( $sql, __FILE__, __LINE__ );
}
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header();

if (isset ( $_GET ['action'] )) {
    switch (getgpc("action","G")) {
        case 'show_message' :
            Display::display_normal_message ( stripslashes (getgpc("message","G") ) );
            break;
        case 'delete_log' : //删除单条记录
            $delId=getgpc('id','G');
            $sql = "DELETE FROM `sys_logging` WHERE `id`='" . Database::escape_string ( intval($delId) ) . "'";
            @api_sql_query ( $sql, __FILE__, __LINE__ );
            break;
    }
}
if (isset ( $_POST ['action'] )) {
    switch (getgpc("action","P")) {
        case 'delete' : //批量删除
            $del_id= $_POST['id'];
                foreach ($del_id as $index => $log_id ){
                    $sql = "DELETE FROM `sys_logging` WHERE `id`='" . Database::escape_string ( intval($log_id) ) . "'";
                    @api_sql_query ( $sql, __FILE__, __LINE__ );
                }
                tb_close('logging_list.php'); 
            break;
    }
}
if(isset($_GET['action']) && base64_decode($_GET['action'])=='all'){  
   $query=mysql_query('truncate table  `sys_logging`');
   if($query){
       tb_close('logging_list.php');
   }
}
        
$form = new FormValidator ( 'search_simple', 'get', '', '', null, false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( ' {element} ' );

$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:150px", 'class' => 'searchtxt', 'title' => get_lang ( 'keyword' ) ,'value'=>'搜索关键词') );
$form->addElement ( 'date', 'start_date', '开始日期时间：', array ('format' => '        开始时间:Y 年m 月d'), array ('show_time' => TRUE ) );
$form->addElement ( 'date', 'end_date', '开始日期时间：', array ('format' => '        截止时间:Y 年m 月d'), array ('show_time' => TRUE ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Search' ), 'class="inputSubmit"' );
$form->setDefaults ( $values );


if (is_not_blank ( $_GET ['keyword'] )) $parameters ['keyword'] = getgpc('keyword');
if (is_not_blank ( $_GET ['keyword_start'] )) $parameters ['keyword_start'] = getgpc('keyword_start');
if (is_not_blank ( $_GET ['keyword_end'] )) $parameters ['keyword_end'] = getgpc('keyword_end');

$table = new SortableTable ( 'adminLoggings', 'get_number_of_data', 'get_data', 1, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$header_idx = 0;
$table->set_header ( $header_idx ++, '', false );
$table->set_header ( $header_idx ++, get_lang ( 'OperationDate' ), false, null, array ('style' => 'width:15%' ) );
$table->set_header ( $header_idx ++, get_lang ( 'LoginName' ), false, null, array ('style' => 'width:12%' ) );
$table->set_header ( $header_idx ++, get_lang ( 'FirstName' ), false, null, array ('style' => 'width:12%' ) );
$table->set_header ( $header_idx ++, get_lang ( 'LogMessage' ), false, null, array ('style' => 'width:20%' )  );
$table->set_header ( $header_idx ++, get_lang ( 'AccessPath' ), false, null, array ('style' => 'width:30%' )   );
$table->set_header ( $header_idx ++, get_lang ( 'Actions' ), false, null, array ('style' => 'width:8%' )  );
$table->set_column_filter ( 6, 'modify_filter' );
$table->set_form_actions ( array ('delete' => get_lang ( 'BatchDelete' ) ) );
//$table->set_dispaly_style_navigation_bar(NAV_BAR_BOTTOM);

//Display::display_footer ( TRUE );
?>

<aside id="sidebar" class="column systeminfo  open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/log/logging_list.php">日志管理</a> &gt; 系统日志</h4>
    <ul class="manage-tab boxPublic">

        <?php
        $html .= '<li  class="selected"><a href="logging_list.php"><em>系统操作日志</em></a></li>';
        $html .= '<li><a href="access_login_logs.php"><em>' . get_lang ( "AccessLoginLog" ) . '</em></a></li>';
         $html .= '<li><a href="ranking.php"><em>' . get_lang ( "学霸排名日志" ) . '</em></a></li>';
        echo $html;
        ?>
    </ul>
    <div class="manage-tab-content">
        <div class="manage-tab-content-list" >
            <div class="managerSearch">
                <?php $form->display ();?>
                 <div style="display:block;float:right;">
                   <a  class="one-delete" href="logging_list.php?action=<?php echo base64_encode("all");?>" onclick="javascript:if(!confirm('是否全部删除?')) return false;"><span class="img-span"></span><span class="span-text">一键清除</span></a> 
                   <!--<a class="one-delete" href="logging_list.php?action=export_log" onclick="javascript:if(!confirm('是否导出?')) return false;"><span  class="img-span1"></span><span class="span-text">导出</span></a>&nbsp;-->
                  <!--<div class="one-delete one-import"><?php echo link_button ( 'import.png', '导入','../import_logs.php', '30%', '40%' ); ?></div>-->
                 
                  <span class="searchtxt right">
                            <?php
                            $report_count=Database::getval('SELECT COUNT(id) FROM `sys_logging`',__FILE__,__LINE__);
                            if($report_count > 0){
                                echo '&nbsp;&nbsp;' . link_button ( 'excel.gif', '导出系统操作日志', 'logging_list_export.php?action=export_log_xls', '50%', '50%'  );
                            }
                            ?>
               </span>
                  </div>
            </div>
            <article class="module width_full hidden">
                <?php $table->display ();?>

            </article>
        </div>
        </div>
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