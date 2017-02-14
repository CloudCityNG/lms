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
function convertip($ip) { 
  $ip1num = 0;
  $ip2num = 0;
  $ipAddr1 ="";
  $ipAddr2 ="";
  $dat_path = './qqwry.dat';        
  if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) { 
    return $ip; 
  }  
  if(!$fd = @fopen($dat_path, 'rb')){ 
    return $ip; 
  }  
  $iparr = explode('.', $ip); 
  $ipNum = $iparr[0] * 16777216 + $iparr[1] * 65536 + $iparr[2] * 256 + $iparr[3];  
  $DataBegin = fread($fd, 4); 
  $DataEnd = fread($fd, 4); 
  $ipbegin = implode('', unpack('L', $DataBegin)); 
  if($ipbegin < 0) $ipbegin += pow(2, 32); 
    $ipend = implode('', unpack('L', $DataEnd)); 
  if($ipend < 0) $ipend += pow(2, 32); 
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1; 
  $BeginNum = 0; 
  $EndNum = $ipAllNum;  
  while($ip1num>$ipNum || $ip2num<$ipNum) { 
    $Middle= intval(($EndNum + $BeginNum) / 2); 
    fseek($fd, $ipbegin + 7 * $Middle); 
    $ipData1 = fread($fd, 4); 
    if(strlen($ipData1) < 4) { 
      fclose($fd); 
      return $ip; 
    }
    $ip1num = implode('', unpack('L', $ipData1)); 
    if($ip1num < 0) $ip1num += pow(2, 32); 

    if($ip1num > $ipNum) { 
      $EndNum = $Middle; 
      continue; 
    } 
    $DataSeek = fread($fd, 3); 
    if(strlen($DataSeek) < 3) { 
      fclose($fd); 
      return $ip; 
    } 
    $DataSeek = implode('', unpack('L', $DataSeek.chr(0))); 
    fseek($fd, $DataSeek); 
    $ipData2 = fread($fd, 4); 
    if(strlen($ipData2) < 4) { 
      fclose($fd); 
      return $ip; 
    } 
    $ip2num = implode('', unpack('L', $ipData2)); 
    if($ip2num < 0) $ip2num += pow(2, 32);  
      if($ip2num < $ipNum) { 
        if($Middle == $BeginNum) { 
          fclose($fd); 
          return $ip; 
        } 
        $BeginNum = $Middle; 
      } 
    }  
    $ipFlag = fread($fd, 1); 
    if($ipFlag == chr(1)) { 
      $ipSeek = fread($fd, 3); 
      if(strlen($ipSeek) < 3) { 
        fclose($fd); 
        return $ip; 
      } 
      $ipSeek = implode('', unpack('L', $ipSeek.chr(0))); 
      fseek($fd, $ipSeek); 
      $ipFlag = fread($fd, 1); 
    } 
    if($ipFlag == chr(2)) { 
      $AddrSeek = fread($fd, 3); 
      if(strlen($AddrSeek) < 3) { 
      fclose($fd); 
      return $ip; 
    } 
    $ipFlag = fread($fd, 1); 
    if($ipFlag == chr(2)) { 
      $AddrSeek2 = fread($fd, 3); 
      if(strlen($AddrSeek2) < 3) { 
        fclose($fd); 
        return $ip; 
      } 
      $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0))); 
      fseek($fd, $AddrSeek2); 
    } else { 
      fseek($fd, -1, SEEK_CUR); 
    } 
    while(($char = fread($fd, 1)) != chr(0)) 
    $ipAddr2 .= $char; 
    $AddrSeek = implode('', unpack('L', $AddrSeek.chr(0))); 
    fseek($fd, $AddrSeek); 
    while(($char = fread($fd, 1)) != chr(0)) 
    $ipAddr1 .= $char; 
  } else { 
    fseek($fd, -1, SEEK_CUR); 
    while(($char = fread($fd, 1)) != chr(0)) 
    $ipAddr1 .= $char; 
    $ipFlag = fread($fd, 1); 
    if($ipFlag == chr(2)) { 
      $AddrSeek2 = fread($fd, 3); 
      if(strlen($AddrSeek2) < 3) { 
        fclose($fd); 
        return $ip; 
      } 
      $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0))); 
      fseek($fd, $AddrSeek2); 
    } else { 
      fseek($fd, -1, SEEK_CUR); 
    } 
    while(($char = fread($fd, 1)) != chr(0)){ 
      $ipAddr2 .= $char; 
    } 
  } 
  fclose($fd);  
  if(preg_match('/http/i', $ipAddr2)) { 
    $ipAddr2 = ''; 
  } 
  $ipaddr = "$ipAddr1 $ipAddr2"; 
  $ipaddr = preg_replace('/CZ88.NET/is', '', $ipaddr); 
  $ipaddr = preg_replace('/^s*/is', '', $ipaddr); 
  $ipaddr = preg_replace('/s*$/is', '', $ipaddr); 
  if(preg_match('/http/i', $ipaddr) || $ipaddr == '') { 
    $ipaddr = 'Unknown'; 
  } 
  return $ipaddr; 
}
function getIPLoc_QQ($login_ip){
	 $str=convertip($login_ip);
         return iconv('GBK','UTF-8',$str);
}

$table_login_logging = Database::get_main_table ( TABLE_STATISTIC_TRACK_E_LOGIN );
$table_user = Database::get_main_table ( TABLE_MAIN_USER );
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
        if (isset ( $_GET ['keyword'] ) && $_GET ['keyword'] != "搜索关键词") {
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
    global $table_login_logging, $table_user;

    $sql = "SELECT
	login_id		AS col0,
	t2.username		AS col1,
	t2.firstname	AS col2,
	login_date		AS col3,
	logout_date	    AS col4,
	login_ip		AS col5,
	login_ip		AS col6,
	login_id		AS col7
	FROM  $table_login_logging AS t1 LEFT JOIN $table_user AS t2 ON t1.login_user_id=t2.user_id ";

    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " WHERE " . $sql_where;
    $sql .= " ORDER BY  login_date desc, col$column $direction ";
    $sql .= " LIMIT $from,$number_of_items";

    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $data = array ();
    while ( $adata = Database::fetch_array ( $res, 'NUM' ) ) {
        $data [] = $adata;
    }
    return $data;
}

function actions_filter($log_id, $url_params) {
    $result .= '<a href="access_login_logs.php?action=delete_log&amp;id=' . intval($log_id) . '&amp;' . $url_params . '" onclick="javascript:if(!confirm(' . "'" . addslashes ( htmlentities ( get_lang ( "ConfirmYourChoice" ), ENT_NOQUOTES, SYSTEM_CHARSET ) ) . "'" . ')) return false;">' . Display::return_icon (
        'delete.gif', get_lang ( 'Delete' ), array ('style' => 'vertical-align: middle;' ) ) . '</a>';
    return $result;
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
        
if (isset ( $_GET ['action'] )) {
    switch (getgpc("action","G")) {
        case 'show_message' :
            Display::display_normal_message ( stripslashes (getgpc("message","G") ) );
            break;
        case 'delete_log' : //删除单条记录
            delete_log ( getgpc('id'));
            break;
    }
}
if (isset ( $_POST ['action'] )) {
    switch (getgpc("action","P")) {
        case 'delete' : //批量删除
            case 'delete' :
                $del_id= $_POST['id'];
                foreach ($del_id as $index => $log_id ){
                    $sql="DELETE FROM `track_e_login` WHERE `login_id`='" . Database::escape_string ( $log_id ) . "'";
                    $res=api_sql_query ( $sql, __FILE__, __LINE__ );
                }
                tb_close('access_login_logs.php');
            break;
    }
}
if(isset($_GET['action']) && base64_decode($_GET['action'])=='all'){  
   $query=mysql_query('truncate table  `track_e_login`');
   if($query){
       tb_close('access_login_logs.php');
   }
}
//$html = '<div id="demo" class="yui-navset">';
//$html .= '<ul class="yui-nav">';
//$html .= '<li><a href="logging_list.php"><em>' . get_lang ( "LoggingList" ) . '</em></a></li>';
//$html .= '<li  class="selected"><a href="access_login_logs.php"><em>' . get_lang ( "AccessLoginLog" ) . '</em></a></li>';
//$html .= '</ul>';
//$html .= '<div class="yui-content"><div id="tab1">';
//echo $html;

$form = new FormValidator ( 'search_simple', 'get', '', '', null, false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( ' {element} ' );

//$form->addElement ( 'calendar_datetime', 'keyword_start', get_lang ( "From" ), array ('title' => get_lang ( "LoginDateDuration" ) . get_lang ( "StartTime" ) ), array ('show_time' => FALSE ) );
//$form->addElement ( 'calendar_datetime', 'keyword_end', get_lang ( "To" ), array ('title' => get_lang ( "LoginDateDuration" ) . get_lang ( "EndTime" ) ), array ('show_time' => FALSE ) );
//$form->addRule('valid_date', get_lang('StartDateShouldBeBeforeEndDate'),'callback','calendar_compare_lte');
//$values ['keyword_start'] = date ( 'Y-m-d', time () - (7 * 24 * 3600) );
//$values ['keyword_end'] = date ( 'Y-m-d', time () );

$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:150px", 'class' => 'inputText', 'title' => get_lang ( 'LoginSearchKeywordTip' ) ,'value'=>'搜索关键词') );
$form->addElement ( 'date', 'start_date', '开始日期时间：', array ('format' => '        开始时间:Y 年m 月d'), array ('show_time' => TRUE ) );
$form->addElement ( 'date', 'end_date', '开始日期时间：', array ('format' => '        截止时间:Y 年m 月d'), array ('show_time' => TRUE ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Search' ), 'class="inputSubmit"' );
//$form->addElement('style_submit_button','submit',get_lang('Search'),'class="search"');
$form->setDefaults ( $values );


if (is_not_blank ( $_GET ['keyword'] )) $parameters ['keyword'] = getgpc('keyword');
if (is_not_blank ( $_GET ['keyword_start'] )) $parameters ['keyword_start'] = getgpc('keyword_start');
if (is_not_blank ( $_GET ['keyword_end'] )) $parameters ['keyword_end'] = getgpc('keyword_end');

$table = new SortableTable ( 'admin_loggings', 'get_number_of_data', 'get_data', 3, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$table->set_header ( 0, '', false );
$table->set_header ( 1, get_lang ( 'LoginName' ), false, null, array ('style' => 'width:12%' )  );
$table->set_header ( 2, get_lang ( 'FirstName' ), false, null, array ('style' => 'width:12%' )  );
$table->set_header ( 3, get_lang ( 'LoginDate' ), false, null, array ('style' => 'width:13%' )  );
$table->set_header ( 4, get_lang ( 'LogoutDate' ), false, null, array ('style' => 'width:13%' )  );
$table->set_header ( 5, get_lang ( '地区' ), false, null, array ('style' => 'width:20%' )  );
$table->set_header ( 6, get_lang ( 'LoginIP' ), false, null, array ('style' => 'width:13%' )  );
$table->set_header ( 7, get_lang ( 'Actions' ), false, null, array ('style' => 'width:13%' ) );
$table->set_column_filter ( 5, 'getIPLoc_QQ' );
$table->set_column_filter ( 7, 'actions_filter' );
$table->set_form_actions ( array ('delete' => get_lang ( 'BatchDelete' ) ) );

//Display::display_footer ( TRUE );
?>
 
<aside id="sidebar" class="column systeminfo   open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/log/logging_list.php">日志管理</a> &gt; 登录访问日志 </h4>
    <ul class="manage-tab boxPublic">

        <?php
        $html .= '<li><a href="logging_list.php"><em>系统操作日志</em></a></li>';
        $html .= '<li class="selected"><a href="access_login_logs.php"><em>' . get_lang ( "AccessLoginLog" ) . '</em></a></li>';
         $html .= '<li ><a href="ranking.php"><em>' . get_lang ( "学霸排名日志" ) . '</em></a></li>';
        echo $html;
        ?>
    </ul>
    <div class="manage-tab-content">
        <div class="manage-tab-content-list" >
            <div class="managerSearch">
                <?php $form->display ();?>
                 <div style="display:block;float:right;height: 50px;">
                  <a class="one-delete" href="access_login_logs.php?action=<?php echo base64_encode("all");?>" onclick="javascript:if(!confirm('是否全部删除?')) return false;"><span class="img-span"></span><span class="span-text">一键清除</span></a>&nbsp;
                  <!--<a class="one-delete" href="access_login_logs.php?action=export_log" onclick="javascript:if(!confirm('是否导出?')) return false;"><span  class="img-span1"></span><span class="span-text">导出</span></a>-->
                  <!--<span class="one-delete one-import"><?php echo link_button ( 'import.png', '导入','../import_logs.php', '30%', '40%' ); ?></span>-->
                        <span class="searchtxt right">
                            <?php
                            $report_count=Database::getval('SELECT COUNT(id) FROM `sys_dept`',__FILE__,__LINE__);
                            if($report_count > 0){
                                echo '&nbsp;&nbsp;' . link_button ( 'excel.gif', '导出系统操作日志', 'access_login_logs_export.php', '50%', '50%'  );
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