<?php
$cidReset = true;
$language_file = array ('index', 'registration', 'admin' );
include_once ('../../inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

api_block_anonymous_users ();
api_protect_admin_script ();

$track_user_table = Database::get_main_table ( TABLE_MAIN_USER );
$view_user_dept = Database::get_main_table ( VIEW_USER_DEPT );
$tbl_track_online = Database::get_main_table ( TABLE_STATISTIC_TRACK_E_ONLINE );

if (isset ( $_GET ["action"] ) && is_equal ( $_GET ["action"], "set_offline" )) {
	$session_id = intval(getgpc ( "sid" ));
	$sql = "DELETE FROM " . $tbl_track_online . " WHERE login_id='" . escape ( $session_id ) . "'";
	api_sql_query ( $sql, __FILE__, __LINE__ );
	
	$sql = "DELETEl FROM php_session WHERE session_id='" . escape ( $session_id ) . "'";
	api_sql_query ( $sql, __FILE__, __LINE__ );
}

function display_user_list($user_list) {

	$table_header [] = array (get_lang ( 'LoginName' ), true );
	$table_header [] = array (get_lang ( 'FirstName' ), true );
	$table_header [] = array (get_lang ( '登陆IP' ), true );
	$table_header [] = array (get_lang ( 'LastActivityDate' ), true );
	$table_header [] = array (get_lang ( 'Email' ), true );
	$table_header [] = array ( get_lang ( 'InDept' ), true );
	$table_header [] = array (get_lang ( 'Actions' ), false );
	
	$extra_params = array ();
	$course_url = '';
	foreach ( $user_list as $user_info ) {
		$uid = $user_info [0];
		//$user_info = api_get_user_info($uid);
		//$user_info=UserManager::get_user_info_by_id($uid,TRUE);
		$table_row = array ();
		$url = '?id=' . $uid . $course_url;
		
		$image = $user_info ['picture_uri'];
		$image_file = ((! empty ( $image ) && file_exists ( api_get_path ( SYS_PATH ) . "storage/users_picture/{$image}" )) ? api_get_path ( WEB_PATH ) . "storage/users_picture/{$image}" : api_get_path ( WEB_IMG_PATH ) . 'unknown.jpg');
		
		$table_row [] = $user_info ['username'];
		$table_row [] = $user_info ['firstname'];

        $table_row [] = $user_info ['login_ip'];
        $table_row [] = $user_info ["login_date"];
		$table_row [] = Display::encrypted_mailto_link ( $user_info ['email'] );
		$table_row [] = $user_info ['dept_name'];

		$actions = "";
		$actions .= '&nbsp;&nbsp;' . link_button ( 'synthese_view.gif', 'Informations', api_get_path ( WEB_CODE_PATH ) . 'user_info.php?uid=' . $user_info ['user_id'], '70%', '60%', false,false );
		$table_row [] = $actions;
		$table_data [] = $table_row;
	}
	
	$sorting_options ['column'] = (isset ( $_GET ['column'] ) ? $_GET ['column'] : 2);
	Display::display_sortable_table ( $table_header, $table_data, $sorting_options, array ('per_page_default' => NUMBER_PAGE ), $extra_params, null, 'bottom' );

}

$sql1="SELECT t2.username,t2.firstname, login_user_id,login_ip,login_date,email,t2.dept_name,user_id,t1.login_id,t1.login_date FROM $tbl_track_online AS t1,$view_user_dept AS t2 WHERE t1.login_user_id=t2.user_id  ";
$user_data = api_sql_query_array_assoc($sql1,__FILE__,__LINE__);
foreach($user_data as $user_data_k=>$user_data_v){

    if( ( time() - strtotime($user_data_v['login_date']) ) > ONLINE_TIME){
         $user_session = ini_get('session.save_path').'/sess_'.$user_data_v['login_id'];
         chmod($user_session, 0777);
         unlink($user_session);
         mysql_query('delete from '.$tbl_track_online.' where login_user_id='.$user_data_v['login_user_id']);
    }else{
        $user_data_now[] = $user_data_v;
    }
}
$total = count ( $user_data_now );
$htmlHeadXtra [] = Display::display_thickbox ();
include_once ('../../inc/header.inc.php');

if($platform==3){
    $nav='userlist';
}else{
    $nav='users';
}
echo '<aside id="sidebar" class="column '.$nav.' open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';
?>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/user/user_list.php">用户管理</a> &gt; 在线用户</h4>
    <div class="managerSearch">
        <form action="#" method="post" id="searchform">
            <span class="searchtxt"><span class="hight"><?php echo '<b>' . get_lang ( 'TotalOnLine' ) . ' : ' . $total . '</b>' . str_repeat ( '&nbsp;', 10 );?></span></span>
    <?php if (empty ( $_GET ['id'] )) {
            echo '<div style="float:right"><button type="button" value="' . get_lang ( 'Refresh' ) . '"  onclick="javascript:window.location.reload();">' . get_lang ( 'Refresh' ) . "</button>&nbsp;</div>";
        }?>
    </div>
    <article class="module width_full hidden">
        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">
                <?php display_user_list ( $user_data_now );?>
            </table>
        </form>
    </article>
</section>
</body>
</html>
