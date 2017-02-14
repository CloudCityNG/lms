<?php
/**
 * Created by PhpStorm.
 * User: hanfuyin
 * Date: 16/6/7
 * Time: 下午4:14
 * 设备运行状态统计
 */
$language_file = array ('admin' );
$cidReset = true;
include_once ('../../inc/global.inc.php');
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();

$table_login_logging = Database::get_main_table ( TABLE_STATISTIC_TRACK_E_LOGIN );
$table_user = Database::get_main_table ( TABLE_MAIN_USER );
$table_dept = Database::get_main_table ( TABLE_MAIN_DEPT);


function get_number_of_data() {
    $client_list_file = file_get_contents('/tmp/clientlist');

    $client_ip_arr = explode("\n",$client_list_file );
    array_pop( $client_ip_arr );
    return count( $client_ip_arr );
}

function get_data($from, $number_of_items, $column, $direction)
{
    $client_list_file = file_get_contents('/tmp/clientlist');

    $client_ip_arr = explode("\n",$client_list_file );
    $server_ip = $client_ip_arr[0];

    foreach ($client_ip_arr as $client_ip_arr_k => $client_ip_arr_v)
    {
         $line_row = array();
         if( !empty( $client_ip_arr_v ))
         {
             $status_arr = array();
             if ($client_ip_arr_k == 0) {
                 $type = '主设备';
             } else {
                 $type = '从设备';
             }

             exec('sudo -u root /sbin/cloudlcping.sh ' . $server_ip . '' . $client_ip_arr_v, $ip_status_arr);
             $ip_status = $ip_status_arr[1] == 'OK' ? '已连通' : '未连通';
             exec('sudo -u root /usr/bin/ssh root@' . $client_ip_arr_v . ' cat /tmp/statusinfo', $status_arr);
             $equipment_arr = explode(' ', $status_arr[0]);
             $line_row[] = $client_ip_arr_v;
             $line_row[] = $equipment_arr[2] ? $equipment_arr[2] . '%' : '0%';
             $line_row[] = $equipment_arr[3] ? $equipment_arr[3] . '%' : '0%';
             $line_row[] = $equipment_arr[0] ? $equipment_arr[0] . '%' : '0%';
             $line_row[] = $equipment_arr[1] ? $equipment_arr[1] . '%' : '0%';
             $line_row[] = $type;
             $line_row[] = $ip_status;
             $line_rows[] = $line_row;
         }
    }
         return $line_rows;
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

if (is_not_blank ( $_GET ['keyword'] )) $parameters ['keyword'] = getgpc('keyword');
if (is_not_blank ( $_GET ['keyword_start'] )) $parameters ['keyword_start'] = getgpc('keyword_start');
if (is_not_blank ( $_GET ['keyword_end'] )) $parameters ['keyword_end'] = getgpc('keyword_end');

$table = new SortableTable ( 'admin_loggings', 'get_number_of_data', 'get_data', 3, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$table->set_header ( 0, 'ip地址', false, null, array ('style' => 'width:20%' )  );
$table->set_header ( 1, 'CPU使用率', false, null, array ('style' => 'width:10%' )  );
$table->set_header ( 2, '内存使用率', false, null, array ('style' => 'width:10%' )  );
$table->set_header ( 3, '磁盘使用率1' , false, null, array ('style' => 'width:10%' )  );
$table->set_header ( 4, '磁盘使用率2', false, null, array ('style' => 'width:10%' )  );
$table->set_header ( 5, '类别' , false, null, array ('style' => 'width:20%' )  );
$table->set_header ( 6, '连通性' , false, null, array ('style' => 'width:10%' )  );
?>
<aside id="sidebar" class="column systeminfo   open">
    <div id="flexButton" class="closeButton close">
    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt;  <a href="#">设备运行状态统计</a>
    </h4>
    <div class="manage-tab-content">
        <div class="manage-tab-content-list" >
            <article class="module width_full hidden">
                <?php $table->display ();?>
            </article>
        </div>
    </div>
</section>
</body>
</html>