<?php
/**
 ==============================================================================

 ==============================================================================
 */

$language_file = array ("registration", "admin" );
$cidReset = true;
include_once ('../../inc/global.inc.php');
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();

$table_settings_current = Database::get_main_table ( TABLE_MAIN_SETTINGS_CURRENT );

$resultcategories       = array ('Company','Platform', 'MailServer',   'Exam' );//'Security'安全

$select_sql             = "select count(*) from $table_settings_current where `variable`='enable_modules' and `subkey`='router_center'";
$router                 = DATABASE::getval($select_sql,__FILE__,__LINE__);

if(!$router)
{
    $router_sql="INSERT INTO  $table_settings_current  (  `enabled` ,  `variable` ,  `subkey` ,  `type` ,  `category` ,  `display_order` ,  `selected_value` ,  `title` ,  `comment` ,  `scope` ,  `subkeytext` ) 
                 VALUES ( 1,  'enable_modules',  'router_center',  'checkbox',  'Platform', NULL ,  'false',  'EnableModulesTitle',  'EnableModulesComment', NULL ,  '路由交换' )";
    @api_sql_query($router_sql);
}
//51ctf模块
$select_sql = "select count(*) from $table_settings_current where `variable`='enable_modules' and `subkey`='router_center'";
$router1    = DATABASE::getval($select_sql,__FILE__,__LINE__);
if(!$router1)
{
    $router1_sql="INSERT INTO  $table_settings_current  (  `enabled` ,  `variable` ,  `subkey` ,  `type` ,  `category` ,  `display_order` ,  `selected_value` ,  `title` ,  `comment` ,  `scope` ,  `subkeytext` ) 
                  VALUES ( 1,  '51ctf',  'null',  'radio',  'Platform', NULL ,  'true',  '51CTF模式的开关',  '是否开启51ctf模式.如果开启会在前台页面头部显示', NULL ,  'null' )";
    @api_sql_query($router_sql1);
}

$DataShowInBottom  = DATABASE::getval("select count(*) from $table_settings_current where `variable`='DataShowInBottom' ",__FILE__,__LINE__);
if(!$DataShowInBottom)
{
    $DataShowInBottom_sql = "INSERT INTO `settings_current` (`id`, `enabled`, `variable`, `subkey`, `type`, `category`, `display_order`, `selected_value`, `title`, `comment`, `scope`, `subkeytext`) VALUES
                           (NUll, 1, 'DataShowInBottom', NULL, 'textfield', 'Company', 12, '北京易霖博信息技术有限公司  版权所有', 'DataShowInBottomTitle', 'DataShowInBottomComment', NULL, NULL)";
    @api_sql_query($DataShowInBottom_sql);
}

$EditionShowInBottom=DATABASE::getval("select count(*) from $table_settings_current where `variable`='EditionShowInBottom'",__FILE__,__LINE__);
if(!$EditionShowInBottom){
    $EditionShowInBottom_sql="INSERT INTO `settings_current` (`id`, `enabled`, `variable`, `subkey`, `type`, `category`, `display_order`, `selected_value`, `title`, `comment`, `scope`, `subkeytext`) VALUES
(NULL, 1, 'EditionShowInBottom', NULL, 'textfield', 'Company', 13, 'Copyright @2011-2014 51elab.All Rights Reserved.', 'EditionShowInBottomTitle', 'EditionShowInBottomComment', NULL, NULL)";
    @api_sql_query($EditionShowInBottom_sql);
}

$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';

$strCategory = isset ( $_GET ['category'] ) ? getgpc ( 'category', 'G' ) : 'Company';

$my_category = escape ( $strCategory );
$form = new FormValidator ( 'settings', 'post', 'settings.php?category=' . $strCategory ,'', 'enctype="multipart/form-data"' );
Display::setTemplateSettings ( $form, '95%' );
 $lang['login_title']='验证码';
 $lang['login_desc']='登陆页面验证码是否开启';
$sqlsettings = "SELECT DISTINCT * FROM $table_settings_current WHERE enabled=1 and category='$my_category' GROUP BY variable ORDER BY display_order ASC";
$resultsettings = api_sql_query ( $sqlsettings, __FILE__, __LINE__ );
while ( $row = Database::fetch_array ( $resultsettings, 'ASSOC' ) ) {
	
	$title = '<span style="font-weight:bold;font-size:14px">' . get_lang ( $row ['title'] ) . "</span><br/>";
	$comment = '<span style="font-style:italic;font-size:11px">' . get_lang ( $row ['comment'] ) . "</span>";
	switch ($row ['type']) {
		case 'textfield' :
			$form->addElement ( 'text', $row ['variable'], $title . $comment, array ('style' => "width:500px", 'class' => 'inputText' ) );
			$default_values [$row ['variable']] = $row ['selected_value'];
			break;
		case 'password' :
			$form->addElement ( 'password', $row ['variable'], $title . $comment, array ('style' => "width:500px", 'class' => 'inputText' ) );
			$default_values [$row ['variable']] = $row ['selected_value'];
			break;
		case 'textarea' :
			$form->addElement ( 'textarea', $row ['variable'], $title . $comment );
			$default_values [$row ['variable']] = $row ['selected_value'];
			break;
		case 'radio' :
			$values = get_settings_options ( $row ['variable'] );
			$group = array ();
			foreach ( $values as $key => $value ) {
				$group [] = $form->createElement ( 'radio', $row ['variable'], '', get_lang ( $value ['display_text'] ), $value ['value'] );
			}
			$form->addGroup ( $group, $row ['variable'], $title . $comment, str_repeat ( '&nbsp;', 6 ), false );
                        $default_values [$row ['variable']] = $row ['selected_value'];
			break;
		case 'checkbox' :
			$sql = "SELECT * FROM $table_settings_current WHERE enabled=1 and variable='" . $row ['variable'] . "'";

			$result = api_sql_query ( $sql, __FILE__, __LINE__ );
			$group = array ();
			while ( $rowkeys = Database::fetch_array ( $result ) ) {
				$element = $form->createElement ( 'checkbox', $rowkeys ['subkey'], '', get_lang ( $rowkeys ['subkeytext'] ) );
				if ($rowkeys ['selected_value'] == 'true' && ! $form->isSubmitted ()) {
					$element->setChecked ( true );
				}
				$group [] = $element;
			}
			$form->addGroup ( $group, $row ['variable'], $title . $comment, str_repeat ( '&nbsp;', 4 ) . "\n" );
			break;
		case "link" :
			$form->addElement ( 'static', null, $title . $comment, get_lang ( 'CurrentValue' ) . ' : ' . $row ['selected_value'] );
	}
}
if($_GET['category']=='Company' || $_GET['category']=='')
{
    $form->addElement ( 'file', 'user_upload', '上传logo图片', array ('class' => 'inputText', 'style' => 'width:30%', 'id' => 'upload_file_local' ) );
    $form->addElement ( 'file', 'logo_upload', '登陆页logo上传', array ('class' => 'inputText', 'style' => 'width:30%', 'id' => 'upload_file_local' ) );
}
$form->addElement ( 'style_submit_button', null, get_lang ( 'SaveSettings' ), 'class="save"' );

$form->setDefaults ( $default_values );
$color1 = $default_values['bgcolor'];
if ($form->validate ()) { //处理保存
	$values = $form->exportValues (); 
	$sql = "UPDATE $table_settings_current SET selected_value='false' WHERE category='$my_category' AND type='checkbox'";
	$result = api_sql_query ( $sql, __FILE__, __LINE__ );
	// Save the settings
	foreach ( $values as $key => $value ) {    
                if($key=="system_date_set")
                {
                    //设置系统时间
                    $sys_date_time=  explode(';', $value);
                    $sys_date=$sys_date_time[0];
                    $sys_time=$sys_date_time[1];
                    exec ("sudo -u root date -s $sys_date");
                    exec ("sudo -u root date -s $sys_time" );
                    exec("sudo -u root hwclock —systohc");

                }else if($key=="vm_overdue_times")
                {
                    $vm_overdue_time = intval($value);    //设置虚拟机过期时间频率
                    $sql             = "UPDATE $table_settings_current SET selected_value='" . ($vm_overdue_time?$vm_overdue_time:'300') . "' WHERE variable='$key'";
                    api_sql_query ( $sql, __FILE__, __LINE__ );

                }
                else if($key=="clouse_vm_witch")
                {
                    $clouse_vm_witch = intval($value);    //设置虚拟机运行时间
                    $path            = "/etc/cloudschedule/cloudvmdowncheck";
                    $content         = $clouse_vm_witch;
                    file_put_contents($path,$content);
                    $sql             = "UPDATE $table_settings_current SET selected_value='" . $close_vm_witch . "' WHERE variable='$key'";
                    api_sql_query ( $sql, __FILE__, __LINE__ );
                }
                else
                {
                    if (! is_array ( $value ))
                    {
                        $sql    = "UPDATE $table_settings_current SET selected_value='" . Database::escape_string ( $value ) . "' WHERE variable='$key'";
                        $result = api_sql_query ( $sql, __FILE__, __LINE__ );
                    } else {
                        foreach ( $value as $subkey => $subvalue )
                        {
                            $sql = "UPDATE $table_settings_current SET selected_value='true' WHERE variable='$key' AND subkey = '$subkey'";
                            $result = api_sql_query ( $sql, __FILE__, __LINE__ );
                        }
                    }
                } 
	}
    $dir = URL_ROOT;
/**index logo */
    ini_set ( 'memory_limit', '256M' );
    ini_set ( 'max_execution_time', 1800 ); //设置执行时间
    exec(" rm -rf /etc/oem/logo4.gif");
    exec("cd $dir/www".URL_APPEDND."/; chmod -R 777 panel/");

    $upfile = '/etc/oem/logo4.gif';
    $cp     = "$dir/www".URL_APPEDND."/panel/default/assets/images/logo4.gif";

    move_uploaded_file ( $_FILES ['user_upload'] ['tmp_name'], $cp );
    copy($cp,$upfile);

    $color2 = $values['bgcolor'];
    $des    = $color2;
    $fh     = fopen("/etc/oem/color","w");
    fwrite($fh,$des);
    fclose($fh);

/**login logo */

    exec("rm -rf /etc/oem/logo3.gif");
    $logo   = "/etc/oem/logo3.gif";
    $cplogo = "$dir/www".URL_APPEDND."/panel/default/assets/images/logo3.gif";
    move_uploaded_file ( $_FILES ['logo_upload'] ['tmp_name'], $cplogo );
    copy($cplogo,$logo);
                
	$log_msg = get_lang ( 'UpdateSettings' );
	api_logging ( $log_msg, 'SYS_SETTINGS', 'UpdateSettings' );
	
	cache(CACHE_KEY_PLATFORM_SETTINGS,NULL);
	cache(CACHE_KEY_PLATFORM_SETTINGS,get_platform_settings ());
	
	//配置的相关处理
	hook_after_saving ();
}


/**
 * 配置保存到setting表后的相关处理
 */
function hook_after_saving() {
    global $table_settings_current;

    //上传
    $upload_extensions_whitelist_default = array ("zip", "rar", "flv", "mp3", "ppt",'doc','xls','pptx','xlsx','pptx','pdf' );
    $upload_extensions_whitelist = api_get_setting ( 'upload_extensions_whitelist' );
    $upload_extensions_whitelist_arr = (empty ( $upload_extensions_whitelist ) ? $upload_extensions_whitelist_default : explode ( ";", $upload_extensions_whitelist ));
    $upload_extensions_whitelist_arr = array_merge ( $upload_extensions_whitelist_arr, $upload_extensions_whitelist_default );
    $sql_data = array ("selected_value" => implode ( ";", array_unique ( $upload_extensions_whitelist_arr ) ) );
    $sql = Database::sql_update ( $table_settings_current, $sql_data, "variable='upload_extensions_whitelist'" );
    api_sql_query ( $sql, __FILE__, __LINE__ );

    $upload_extensions_blacklist_default = array ("exe", "php", "com", "bat", "sh", 'dll', 'so', 'ocx' );
    $upload_extensions_blacklist = api_get_setting ( "upload_extensions_blacklist" );
    $upload_extensions_blacklist_arr = (empty ( $upload_extensions_blacklist ) ? $upload_extensions_blacklist_default : explode ( ";", $upload_extensions_blacklist ));
    $upload_extensions_blacklist_arr = array_merge ( $upload_extensions_blacklist_arr, $upload_extensions_blacklist_default );
    $sql_data = array ("selected_value" => implode ( ";", array_unique ( $upload_extensions_blacklist_arr ) ) );
    $sql = Database::sql_update ( $table_settings_current, $sql_data, "variable='upload_extensions_blacklist'" );
    api_sql_query ( $sql, __FILE__, __LINE__ );

    require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
    $objDept = new DeptManager ();
    $objDept->init ();

    cache(CACHE_KEY_PLATFORM_SETTINGS,NULL);
    cache(CACHE_KEY_PLATFORM_SETTINGS,get_platform_settings ());
}

function get_settings_options($var) {
    $table_settings_options = Database::get_main_table ( TABLE_MAIN_SETTINGS_OPTIONS );
    $sql = "SELECT * FROM $table_settings_options WHERE  variable='$var'";
    $result = api_sql_query ( $sql, __FILE__, __LINE__ );
    while ( $row = Database::fetch_array ( $result, 'ASSOC' ) ) {
        $temp_array = array ('value' => $row ['value'], 'display_text' => $row ['display_text'] );
        $settings_options_array [] = $temp_array;
    }
    return $settings_options_array;
}

include_once ('../../inc/header.inc.php');

if ($_GET ['action'] == "stored") {
	Display::display_normal_message ( get_lang ( 'SettingsStored' ) );
}

if($platform==3){
$nav='system';
}else{
$nav='systeminfo';
}
?>
<aside id="sidebar" class="column <?=$nav?> open">
    <div id="flexButton" class="closeButton close"></div>
</aside>

<section id="main" class="column">
<h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/systeminfo.php">系统管理</a> &gt; 系统设置</h4>
<ul class="manage-tab boxPublic">
   <?php
    foreach ( $resultcategories as $value ) {
    $strClass = ($strCategory == $value ? 'class="selected"' : '');
    $html .= '<li  ' . $strClass . '><a href="' . $_SERVER ['PHP_SELF'] . '?category=' . $value . '">' . get_lang ( $value ) . '</a></li>';
        }
    echo $html;?>
</ul>
<div class="manage-tab-content">
<div class="manage-tab-content-list" >
    <div class="tabcontent boxPublic" style="background:#FFF;">
            <table cellpadding="0" cellspacing="0" class="settingstable">
                <tbody>

                <?php  $form->display (); ?>

                </tbody>
            </table>
    </div>
</div>

</div>
</section>
</body>
</html>
