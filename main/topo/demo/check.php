<?php
header("content-type:text/html;charset=utf-8");

$language_file = array ('admin', 'registration' );
$cidReset = true;

require ('../../inc/global.inc.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');

//require_once (api_get_path ( LIBRARY_PATH ) . 'networkmap.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
$code = $_POST["code"];//request.open中的url传参
$xml = str_replace('"',"'",$code) ;

$id = intval($_POST['id']);
$table_net = Database::get_main_table ( TABLE_MAIN_NET );

if($id){
    $sql_data = array ('id' => $id,
        'xml' => $xml
    );
    $sql = Database::sql_update ($table_net,$sql_data ,"id='$id'");
    $result = api_sql_query ( $sql, __FILE__, __LINE__ );
    if($result){
        echo 'ok';exit;
    }
}else{
    echo 'err';exit;
}
?>