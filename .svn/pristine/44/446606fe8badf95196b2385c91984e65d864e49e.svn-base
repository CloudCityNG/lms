<?php
include_once ('main/inc/global.inc.php');
$check=  Database::getval("select id from settings_current where variable='51ctf'");

if(!$check){
$sql="INSERT INTO `vslab`.`settings_current` (`id`, `enabled`, `variable`, `subkey`, `type`, `category`, `display_order`, `selected_value`, `title`, `comment`, `scope`, `subkeytext`) VALUES (NULL, '1', '51ctf', NULL, 'radio', 'Platform', '0', 'true', '51CTF模式的开关', '是否开启51ctf模式.如果开启会在前台页面头部显示.', 'null', NULL);";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$sql1="INSERT INTO `vslab`.`settings_options` (`id`, `variable`, `value`, `display_text`) VALUES ('', '51ctf', 'true', '开启'), ('', '51ctf', 'false', '关闭');";
$res = api_sql_query ( $sql1, __FILE__, __LINE__ );
}

