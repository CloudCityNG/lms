<?php
header("content-type:text/html;charset=utf-8");
require_once ('../../../main/inc/global.inc.php');
$real_time=file_get_contents('./real_time.json');
echo $real_time;