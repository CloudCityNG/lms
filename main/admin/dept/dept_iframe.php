<?php
$language_file = array ('admin');
$cidReset=true;
require_once ('../../inc/global.inc.php');
$this_section=SECTION_PLATFORM_ADMIN;
api_protect_admin_script();
if (! isRoot ()) api_not_allowed ();

$required_roles=array(ROLE_TRAINING_ADMIN);
if(validate_role_base_permision($required_roles)===FALSE){
	api_deny_access(TRUE);
}

$tool_name = get_lang ( 'DeptList' );
$interbreadcrumb [] = array ('url' => api_get_path(WEB_ADMIN_PATH).'index.php', "name" => get_lang ( 'PlatformAdmin' ), 'target' => '_self' );
$interbreadcrumb [] = array ('url' => 'dept_index.php', "name" => get_lang ( 'DeptList' ), 'target' => '_self' );

$htmlHeadXtra[]='<script type="text/javascript">
if(document.attachEvent)  window.attachEvent("onload",  iframeAutoFit);  
else  window.addEventListener("load",  iframeAutoFit,  false);
</script>	
	
<style type="text/css">
.framePage {
	/*border:#CACACA solid 1px;*/
	border-top-style:none;
	width:100%;
	padding-top:0px;
	text-align:left;
}

#Resources {
	width:100%;
}
#Resources #treeview {
	float:left;
	border:#999 solid 1px;
	width:20%;
	//height:480px;
}
#Resources #frm {
	float:left;
	width:79%;
}
</style>
';

$htmlHeadXtra [] = Display::display_thickbox ();
$htmlHeadXtra [] = '<script type="text/javascript">
	function refresh_tree(){ parent.Tree.location.reload();parent.Tree.d.openAll(); }
</script>';
include_once ('../../inc/header.inc.php');

require_once (api_get_path ( LIBRARY_PATH ) . "export.lib.inc.php");
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( SYS_CODE_PATH ) . 'admin/admin.lib.inc.php');

$deptObj = new DeptManager ();

$table_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
$table_user = Database::get_main_table ( TABLE_MAIN_USER );
$table_user_role = Database::get_main_table ( TABLE_MAIN_USER_ROLE );
$view_sys_user = Database::get_main_table ( VIEW_USER );

if (isset ( $_GET ['action'] )) {
    switch (getgpc("action","G")) {
        case 'show_message' :
            if (isset ( $_GET ['message'] )) Display::display_normal_message ( stripslashes ( urldecode (getgpc("message","G") ) ) );
            break;
        case 'delete_dept' :
            $res_del = $deptObj->org_del (intval(getgpc ( 'id', 'G' )) );
            switch ($res_del) {
                case 1 :
                    $log_msg = get_lang ( 'DelDeptInfo' ) . "id=" . intval(getgpc ( 'id', 'G' ));
                    api_logging ( $log_msg, 'DEPT' );
                    //api_redirect ( "dept_iframe.php?refresh=1&message=" . get_lang ( 'DeptDeleteSuccess' ) );
		    header ( "Location:".URL_APPEDND."/main/admin/dept/dept_iframe.php?delete=success" ); 
                    break;
            }
            break;
    }
}



//Display::display_header ( NULL, FALSE );
if (isset ( $_GET ['refresh'] )) echo '<script>refresh_tree();</script>';



$sorting_options = array ();
$sorting_options ['column'] = 0;
$sorting_options ['default_order_direction'] = 'ASC';

$table_header [] = array (get_lang ( '编号' ), true );
$table_header [] = array (get_lang ( '名称' ), true );
$table_header [] = array (get_lang ( 'Description' ), false );
$table_header [] = array (get_lang ( '管理员' ), false );

$table_header [] = array (get_lang ( 'Actions' ), false, null, array ('style' => 'width:80px' ) );

$all_org = $GLOBALS ['deptObj']->get_all_org ();

foreach ( $all_org as $item ) {
    $row = array ();
    $row [] = $item ['dept_no'];
    $row [] = $item ['dept_name'];
    $row [] = nl2br ( $item ['dept_desc'] );
 $sql="select firstname from user where user_id=".$item ['dept_admin'];
    $dept_admin=  Database::getval($sql);
    $row [] =$dept_admin;
    $action = "";
    $action .= link_button ( 'edit.gif', 'Edit', 'org_update.php?action=edit&id=' . intval($item ['id']), '50%', '70%', FALSE );
    $href='dept_iframe.php?action=delete_dept&amp;id=' . intval($item ['id']) . '&amp;pid=' . $item ['pid'];
    $action .= '&nbsp;&nbsp;' . confirm_href ( 'delete.gif', 'OrgDelConfirm', 'Delete', $href );
    $row [] = $action;

    $table_data [] = $row;
}

if($platform==3){
    $nav='userlist';
}else{
    $nav='users';
}
?>
<aside id="sidebar" class="column <?=$nav?> open">
    <div id="flexButton" class="closeButton close">

    </div>
</aside>
<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/user/user_list.php">用户管理</a> &gt;组织管理</h4>
    <div class="managerSearch">
        <span class="searchtxt right"><?php echo link_button ( 'add_dept.gif', '新建组织', 'org_update.php?action=add&pid=' . DEPT_TOP_ID, '50%', '70%' );?></span>
    </div>
    <article class="module width_full">
           <?php echo Display::display_table ( $table_header, $table_data );?>
    </article>
</section>
    </body>
            </html>
