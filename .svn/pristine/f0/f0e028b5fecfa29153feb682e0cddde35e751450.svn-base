<?php
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
        
function g_substr($str, $len, $dot = true) {
    $i = 0;
    $l = 0;
    $c = 0;
    $a = array();
    while ($l < $len) {
        $t = substr($str, $i, 1);
        if (ord($t) >= 224) {
            $c = 3;
            $t = substr($str, $i, $c);
            $l += 2;
        } elseif (ord($t) >= 192) {
            $c = 2;
            $t = substr($str, $i, $c);
            $l += 2;
        } else {
            $c = 1;
            $l++;
        }
        $i += $c;
        if ($l > $len) break;
        $a[] = $t;
    }
    $re = implode('', $a);
    if (substr($str, $i, 1) !== false) {
        array_pop($a);
        ($c == 1) and array_pop($a);
        $re = implode('', $a);
        $dot and $re .= '...';
    }
    return $re;
}

function config_filter($conf_id){
    $result = "";
    $result.=g_substr($conf_id,150);
    return $result;
}
function locate_filter($id){
    $result = "";
    $sql="select `top`,`left` from `labs_devices` where `id`=".$id;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    while ( $arr = Database::fetch_row ( $res) ) {
        $arrs [] = $arr;
    }
    $top  =$arrs[0][0];
    $left =$arrs[0][1];
    $result.='('.$top.','.$left.')';
    return $result;
}
function edit_filter($id) {
    $result = "";
    global $_configuration, $root_user_id;
    if (api_is_platform_admin () && ! in_array ( $id, $root_user_id ) || $_SESSION['_user']['status']=='1') {
        $result .=   link_button ( 'edit.gif', 'Edit', 'labs_device_edit.php?id='.$id, '70%', '70%', FALSE );
    }
    return $result;
}
function delete_filter($id) {
    $result = "";
    global $_configuration, $root_user_id;
    if (api_is_platform_admin () && ! in_array ( $id, $root_user_id ) || $_SESSION['_user']['status']=='1') {
        $result .=   confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'net_devices.php?action=delete&id=' . $id."&lab_id=".intval(getgpc('lab_id')));
    }
    return $result;
}
$action=htmlspecialchars($_GET ['action']);

if (isset ($action)) {
    switch ($action) {
        case 'delete' :
            $delete_id=htmlspecialchars($_GET ['id']);
            if ( isset($delete_id)){
                $sql = "DELETE FROM  `labs_devices` WHERE `labs_devices`.`id` = ".$delete_id;
                $result = api_sql_query ( $sql, __FILE__, __LINE__ );
                header("location:net_devices.php?lab_id=".intval(getgpc('lab_id')) ); 
            }
            break;
    }
}
if (isset ( $_POST ['action'] )) {
    switch ($_POST ['action']) {
        case 'deletes' :
            $labs = $_POST['labs'];
            if (count ( $labs ) > 0) {
                foreach ( $labs as $index => $id ) {

                    $sql = "DELETE FROM  `labs_devices` WHERE id='" . $id . "'";
                    api_sql_query ( $sql, __FILE__, __LINE__ );

                    $log_msg = get_lang('删除所选') . "id=" . $id;
                    api_logging ( $log_msg, 'labs', 'labs' );
                }
            }
            break;
    }
}


function get_number_of_labs_devices() {
    $labs_devices = Database::get_main_table (labs_devices);
    $lab_id= getgpc ( 'lab_id', 'G' ); 
    $name_sql="select `name` from `labs_labs` where id='".$lab_id."'";
    $labs_name=Database::getval($name_sql,__FILE__,__LINE__);
    $sql = "SELECT COUNT(id) AS total_number_of_items FROM " . $labs_devices."  where  `lab_id`='".$labs_name."'";
    return Database::getval ( $sql, __FILE__, __LINE__ );
}
function get_labs_devices_data($from, $number_of_items, $column, $direction) {
    $labs_devices = Database::get_main_table (labs_devices);
    $lab_id= getgpc ( 'lab_id', 'G' ); 
    $name_sql="select `name` from `labs_labs` where id='".$lab_id."'";
    $labs_name=Database::getval($name_sql,__FILE__,__LINE__);
    $sql = "select `id`,`id`,`name`,`lab_id`,`ios`,`vmdisks`,`slot`,`picture`,`desc`,`id`,`id`,`id` FROM ".$labs_devices."  where  `lab_id`='".$labs_name."'";
        
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
         
echo import_assets('themes/js/html5.js',api_get_path ( WEB_PATH ));
echo import_assets('themes/js/html5.js',api_get_path ( WEB_PATH ));
echo import_assets('themes/js/jquery-1.5.2.min.js',api_get_path ( WEB_PATH ));
echo import_assets('themes/js/hideshow.js',api_get_path ( WEB_PATH ));
echo import_assets('themes/js/jquery.tablesorter.min.js',api_get_path ( WEB_PATH ));
echo import_assets('themes/js/jquery.equalHeight.js',api_get_path ( WEB_PATH ));
if (isset ( $htmlHeadXtra ) && $htmlHeadXtra) {
    foreach ( $htmlHeadXtra as $this_html_head ) {
        echo ($this_html_head);
    }
}

if (isset ( $htmlIncHeadXtra ) && $htmlIncHeadXtra) {
    foreach ( $htmlIncHeadXtra as $this_html_head ) {
        include ($this_html_head);
    }
}       
        
foreach ( $category_cnt as $item ) {
    $cate_name = $item ['name'] . (($category_cnt [$item ['id']]) ? "&nbsp;(" . $category_cnt [$item ['id']] . ")" : "");
    $cate_options [$item ['id']] = str_repeat ( "&nbsp;&nbsp;&nbsp;&nbsp;", intval ( $item ['level'] ) + 1 ) . trim ( $cate_name );
}
        

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ['keyword'] )) $parameters ['keyword'] = getgpc ( 'keyword' );
if (isset ( $_GET ['lab_name'] ) && is_not_blank ( $_GET ['lab_name'] )) $parameters ['lab_name'] = getgpc ( 'lab_name' );

$table = new SortableTable ( 'labs', 'get_number_of_labs_devices', 'get_labs_devices_data',2, NUMBER_PAGE  );

$table->set_additional_parameters ( $parameters );
$idx=0;
$table->set_header ( $idx ++, '', false, null);
$table->set_header ( $idx ++, '编号', false, null, array ('style' => 'width:3%;text-align:center' ));
$table->set_header ( $idx ++, '名称', false, null, array ('style' => 'width:10%; text-align:center' ) );
$table->set_header ( $idx ++, '实验拓扑', false, null, array ('style' => 'width:17%;text-align:center' ) );
$table->set_header ( $idx ++, '设备型号', false, null, array ('style' => 'width:6%; text-align:center' ) );
$table->set_header ( $idx ++, '虚拟模板', false, null, array ('style' => 'width:8%; text-align:center' ) );
$table->set_header ( $idx ++, '模块设置', false, null, array ('style' => 'width:20%;text-align:center' ) );
$table->set_header ( $idx ++, '设备类型', false, null, array ('style' => 'width:6%;text-align:center' ) );
$table->set_header ( $idx ++, '描述', false, null, array ('style' => 'width:12%') );
$table->set_header ( $idx ++, '定位', false, null, array ('style' => 'width:6%') );
$table->set_header ( $idx ++, '编辑', false, null, array ('style' => 'width:5%;text-align:center' ) );
$table->set_header ( $idx ++, '删除', false, null, array ('style' => 'width:5%;text-align:center' ) );

$table->set_form_actions ( array ('deletes' => '删除所选项' ), 'labs' );
$table->set_column_filter ( 8, 'config_filter' );
$table->set_column_filter ( 9, 'locate_filter' );
$table->set_column_filter ( 10, 'edit_filter' );
$table->set_column_filter ( 11, 'delete_filter' );

    ?>
 <link rel="stylesheet" href="<?=api_get_path ( WEB_PATH )?>themes/css/layout.css" type="text/css" media="screen" />
<section  class="column">
     <div  style="float: right;padding-right: 50px;"> 
        <?php  echo   link_button ( 'add.gif', '添加','labs_device_add.php?lab_id='.intval(getgpc("lab_id")),'90%', '70%' );?>
    </div>
    <article class='module width_full hidden">
        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">

                <?php $table->display ();
    Display::display_footer ();
    ?>
            </table>
        </form>
    </article>

</section>
