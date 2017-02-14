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

function get_sqlwhere() {
    $sql = "";
    if (isset ( $_GET ['keyword'] ) && $_GET ['keyword']) {
        $keyword = trim ( Database::escape_string (getgpc("keyword","G") ) );
        $sql .= " AND  (firstname LIKE '%" . $keyword . "%'  OR username LIKE '%" . $keyword . "%' )";
    }
    if (isset ( $_GET ['keyword_start'] ) && $_GET ['keyword_start']) {
        $keyword_start = trim ( Database::escape_string ( getgpc ( 'keyword_start', 'G' ) ) ) . " 00:00:00";
        $sql .= " AND login_date>='" . $keyword_start . "'";
    }

    if (isset ( $_GET ['keyword_end'] ) && $_GET ['keyword_end']) {
        $keyword_end = trim ( Database::escape_string ( getgpc ( 'keyword_end', 'G' ) ) ) . " 23:59:59";
        $sql .= " AND login_date<='" . $keyword_end . "'";
    }
    $sql = trim ( $sql );
    return substr ( $sql, 3 );
}

function get_number_of_data() {
    global $table_login_logging, $table_user,$table_dept;
   $ri=date("Y-m-d",time());
    $sql = "SELECT COUNT(*) AS total_number_of_items FROM $table_login_logging AS t1 LEFT JOIN $table_user AS t2 ON t1.login_user_id=t2.user_id LEFT  JOIN  $table_dept AS t3 ON t2.dept_id = t3.id where left(login_date,10)='{$ri}' ";

    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= "AND" . $sql_where;
    return Database::get_scalar_value ( $sql );
}

function get_data($from, $number_of_items, $column, $direction) {
    global $table_login_logging, $table_user,$table_dept;
   
   
   $ri=date("Y-m-d",time());
    $sql = "SELECT
	login_id		AS col0,
	t2.username	AS col1,
	t2.firstname	AS col2,
	login_date		AS col3,	
	login_ip		AS col4,
	t3.dept_name		AS col5		
    	FROM  $table_login_logging AS t1 LEFT JOIN $table_user AS t2 ON t1.login_user_id=t2.user_id  LEFT  JOIN  $table_dept AS t3 ON t2.dept_id = t3.id where 1 ";
      $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= "AND".  $sql_where;
    $sql .= " and left(login_date,10)='{$ri}'";
    $sql .= " ORDER BY  login_date desc";
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


$form = new FormValidator ( 'search_simple', 'get', '', '', null, false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( ' {element} ' );

$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:150px", 'class' => 'inputText', 'title' => get_lang ( 'LoginSearchKeywordTip' ) ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Search' ), 'class="inputSubmit"' );
$form->setDefaults ( $values );

if (is_not_blank ( $_GET ['keyword'] )) $parameters ['keyword'] = getgpc('keyword');
if (is_not_blank ( $_GET ['keyword_start'] )) $parameters ['keyword_start'] = getgpc('keyword_start');
if (is_not_blank ( $_GET ['keyword_end'] )) $parameters ['keyword_end'] = getgpc('keyword_end');

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
     <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt;  <a href="<?=URL_APPEDND;?>/main/admin/statistics/user_number">用户活跃数统计详情页面</a>
    </h4>
      <ul class="manage-tab boxPublic">
        <?php
        $html .= '<li  class="selected"><a href="user_number.php"><em>用户日度活跃</em></a></li>';
        $html .= '<li ><a href="user_login.php"><em>用户月度活跃</em></a></li>';
   
        echo $html;
        ?>
    </ul>
      <div class="managerSearch">     
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