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
    $file_name="track_e_login.zip";
    $command="mysqldump -u".DB_USER." -p".DB_PWD." -t ".DB_NAME." track_e_login > track_e_login.lib";  
    sript_exec_log(" cd  ".$file_dir.";".$command.";tar  -zcvf   track_e_login.zip   track_e_login.lib");
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
        sript_exec_log("rm ".$file_dir."/track_e_login.lib");
        exit;
    }
        
}

$table_login_logging = Database::get_main_table ( TABLE_STATISTIC_TRACK_E_LOGIN );
$table_user = Database::get_main_table ( TABLE_MAIN_USER );
$table_dept = Database::get_main_table ( TABLE_MAIN_DEPT);

$action = getgpc ( 'action', 'G' );
$dept_id = isset ( $_GET ['keyword_deptid'] ) ? getgpc ( 'keyword_deptid', 'G' ) : '0';

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
    if (isset ( $_GET ['keyword'] ) && $_GET ['keyword']) {
        $keyword = trim ( Database::escape_string (getgpc("keyword","G") ) );
        $sql_where .= " AND  (firstname LIKE '%" . $keyword . "%'  OR username LIKE '%" . $keyword . "%' )";
    }

     if($start_date!='1970-01-01 08:00:00'){
         $sql_where.=" AND login_date > binary '".$start_date."'";
    }
     if($start_date!='1970-01-01 08:00:00'){
         $sql_where.=" AND login_date < binary '".$end_date."'";
     }
    $sql_where = trim ( $sql_where );
    return substr ( $sql_where, 3 );
}

function get_number_of_data() {
    global $table_login_logging, $table_user;

    $sql = "SELECT COUNT(*) AS total_number_of_items FROM $table_login_logging AS t1 LEFT JOIN $table_user AS t2 ON t1.login_user_id=t2.user_id ";

    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;

    return Database::get_scalar_value ( $sql );
}

function get_data($from, $number_of_items, $column, $direction) {
    global $table_login_logging, $table_user,$table_dept;
   
   $login_date =date("Y-m-d h:i:s ",strtotime (date("Y-m-d ",time())));
    $sql = "SELECT
	login_id		AS col0,
	t2.username	AS col1,
	t2.firstname	AS col2,
	login_date		AS col3,	
	login_ip		AS col4,
	t3.dept_name		AS col5
	
	FROM  $table_login_logging AS t1 LEFT JOIN $table_user AS t2 ON t1.login_user_id=t2.user_id  LEFT  JOIN  $table_dept AS t3 ON t2.dept_id = t3.id  ";

    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;
        if(is_not_blank($onedate)){
	       $sql .= " AND login_date > binary '".$start_time."'";	
	}
    $sql .= " ORDER BY  login_date desc, col$column $direction ";
    $sql .= " LIMIT $from,$number_of_items";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $data = array ();
    while ( $adata = Database::fetch_array ( $res, 'NUM' ) ) {
        $data [] = $adata;
    }
    return $data;
}
 

function delete_log($log_id) {
    global $table_login_logging;
    $sql = "DELETE FROM $table_login_logging WHERE login_id='" . Database::escape_string ( $log_id ) . "'";
    return api_sql_query ( $sql, __FILE__, __LINE__ );
}

$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( NULL );
        
if(isset($_GET['action']) && base64_decode($_GET['action'])=='all'){  
   $query=mysql_query('truncate table  `track_e_login`');
   if($query){
       tb_close('access_login_logs.php');
   }
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

$table = new SortableTable ( 'admin_loggings', 'get_number_of_data', 'get_data', 3, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$table->set_header ( 0, get_lang ( '序号' ), false, null, array ('style' => 'width:12%' )  );
$table->set_header ( 1, get_lang ( 'LoginName' ), false, null, array ('style' => 'width:12%' )  );
$table->set_header ( 2, get_lang ( 'FirstName' ), false, null, array ('style' => 'width:12%' )  );
$table->set_header ( 3, get_lang ( 'LoginDate' ), false, null, array ('style' => 'width:13%' )  );
$table->set_header ( 4, get_lang ( 'LoginIP' ), false, null, array ('style' => 'width:13%' )  );
$table->set_header ( 5, get_lang ( '部门/省份' ), false, null, array ('style' => 'width:13%' )  );

?>
 
<aside id="sidebar" class="column systeminfo   open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
     <h4 class="page-mark" style="width:98%;margin:20px 1% 0 2%;">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt;  <a href="<?=URL_APPEDND;?>/main/admin/statistics/user_number">用户月活跃数统计详情页面</a>
    </h4>
      <ul class="manage-tab boxPublic">
        <?php
        $html .= '<li  ><a href="user_number.php"><em>用户日度活跃</em></a></li>';
        $html .= '<li  class="selected"><a href="user_login.php"><em>用户月度活跃</em></a></li>';
       
        echo $html;
        ?>
    </ul>
      <div class="managerSearch" style="width:95%;margin:8px 1% 0 2%;">     
             <?php $form->display ();?>
      </div>
    <div class="manage-tab-content">
        <div class="manage-tab-content-list" >
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
 .manage-tab li.xueba  a:visited {background-color: #13A654 !important}
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