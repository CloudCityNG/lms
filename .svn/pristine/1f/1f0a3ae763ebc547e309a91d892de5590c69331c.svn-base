<?php

$language_file = array ('admin', 'registration' );
$cidReset = true;

include_once ('../../inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
$team_table = Database::get_main_table ( 'tbl_Rewrad' );
$htmlHeadXtra [] = Display::display_thickbox ();

$action = getgpc ( 'action', 'G' );
$sql = "SELECT user_id FROM " . $table_user . " WHERE is_admin=1 AND " . Database::create_in ( $_configuration ['default_administrator_name'], 'username' );
$root_user_id = Database::get_into_array ( $sql, __FILE__, __LINE__ );
$redirect_url = 'main/admin/team/team_list.php';
include_once ('../../inc/header.inc.php');


echo '<aside id="sidebar" class="column '.$nav.' open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';

//搜索功能
function get_sqlwhere() {
    
    $sql_where = "";
    if (isset ( $_GET ['keyword'] ) && ! empty ( $_GET ['keyword'] )) {
        $keyword = escape ( getgpc ( 'keyword', 'G' ), TRUE );
        if($keyword=='输入搜索关键词'){
            $sql_where=null;
        }else{
            $sql_where .= " AND AND(u.matchName LIKE '%" .$keyword. "%'  OR t.grade LIKE '%" . $keyword . "%')";}
             }
     
        $sql_where = trim ( $sql_where );
        if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
        else return "";
}
//总条数             
function get_number_of_teams() {
	$team_table = Database::get_main_table ( 'tbl_team' );
	$sql = "SELECT COUNT(*) AS total_number_of_items FROM tbl_contest as u,tbl_Reward as t where t.match_id=u.id";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
        $num=Database::getval ( $sql, __FILE__, __LINE__ );
	return $num;
}
//获取要显示的数据
function get_team_data($from, $number_of_items, $column, $direction) {
        $contest_table = Database::get_main_table ( 'tbl_contest' );
	$reward_table = Database::get_main_table ( 'tbl_Reward' );
	$sql = "SELECT  t.id		AS col0,
                 	u.matchName	AS col1,
                 	t.grade 	AS col2,
                 	t.reDesc 	AS col3,
                        t.id            AS col4
	FROM  $reward_table AS t,$contest_table AS u where t.match_id=u.id";
	$sql_where = get_sqlwhere ();
        
	if ($sql_where) $sql .= $sql_where;
	$sql .= " ORDER BY col$column $direction ";
	$sql .= " LIMIT $from,$number_of_items";  
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();
	while ( $user = Database::fetch_row ( $res ) ) {
		$users [] = $user;  
	}
	return $users;
}

//function email_filter($email) {
//	return Display::encrypted_mailto_link ( $email, $email );
//}

//function desc_filter($id){
//    $link = link_button ( 'coachs.gif', '查看战队成员', 'team_desc.php?id=' . $id, '50%', '60%', FALSE );
//    return $link;
//}


//编辑 删除链接 
function active_filter($reward_id) {
        $result = link_button ( 'edit.gif', '修改奖励信息', 'reward_edit.php?id=' . $reward_id, '50%', '60%', FALSE )."&nbsp;&nbsp;".confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'reward_list.php?action=delete_team&id=' . $reward_id );
	return $result;
}

//单个删除
if (isset ( $_GET ['action'])) {
	switch (getgpc('action','G')) {
            case 'delete_team' :
                $reward_id = getgpc('id','G');
                $reward_table = Database::get_main_table ( 'tbl_Reward' );
                $sql = "DELETE FROM $reward_table WHERE id = $reward_id";
                $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                        if($res){
                               tb_close('reward_list.php');
                               //Display::display_msgbox ( get_lang ( 'UserDeleted' ), $redirect_url );
			} else {
                               tb_close('reward_list.php');
                               //Display::display_msgbox ( get_lang ( 'CannotDeleteUser' ), $redirect_url, 'warning' );
			}
			break;
        }
}

//批量删除
if (isset ( $_POST ['action'] )) {
	switch ($_POST ['action']) {
		case 'delete' :
			$number_of_selected_users = count ( $_POST ['id'] );
			$number_of_deleted_users = 0;
                        $del_id= $_POST['id'];
			foreach ($del_id as $index => $reward_id ) {
                             $reward_table = Database::get_main_table ( 'tbl_Reward' );
                             $sql = "DELETE FROM $reward_table WHERE id = $reward_id";
  //                           echo $sql;
                             $res = api_sql_query ( $sql, __FILE__, __LINE__ );
				
//				if ($user_id != api_get_user_id ()) {
                                    
//					if (UserManager::delete_user ( $user_id )) {
//						api_logging ( get_lang ( 'DelUsers' ) . $user_id, 'USER', 'DelUsers' );
						$number_of_deleted_users ++;
					//}
				}
			}
			if ($number_of_selected_users == $number_of_deleted_users) {
                                                                                    tb_close('reward_list.php');
//				Display::display_msgbox ( get_lang ( 'SelectedUsersDeleted' ), $redirect_url );
			} else {
                                                                                     tb_close('reward_list.php');
//				Display::display_msgbox ( get_lang ( 'SomeUsersNotDeleted' ), $redirect_url, 'warning' );
			}
			break;

}

//Display::display_header (NULL, FALSE);

//if (isset ( $_POST ['action'] )) {
//	switch ($_POST ['action']) {
//		case 'batchChangeDept' : //批量修改用户部门表单
//			$number_of_selected_users = count ( $_POST ['id'] );
//			if ($number_of_selected_users > 0) {
//				$user_id_str = implode ( ",", $_POST ['id'] );
//				$form = new FormValidator ( 'batch_change_dept', 'post' );
//				//$form->addElement('header', 'header', get_lang('SearchAUser'));
//				$form->addElement ( "hidden", "action", 'batchChangeDeptSave' );
//				$form->addElement ( "hidden", "user_id_str", $user_id_str );
//				$renderer = $form->defaultRenderer ();
//				$renderer->setElementTemplate ( '<span>{element}</span> ' );
//				$form->addElement ( 'static', 'text1', null, get_lang ( "PlsSelectTheDeptMoveTo" ) );
//				//unset($dept_options[0]);
//				$dept_options [0] = NULL;
//				//$form->addElement ( 'select', 'org_id', get_lang ( 'InOrg' ), $orgs, array ('id' => "org_id", 'style' => 'height:22px;' ) );
//				$depts = $objDept->get_sub_dept_ddl2 ( 0, 'array' );
//				$form->addElement ( 'select', 'department_id', get_lang ( 'UserInDept' ), $depts, array ('id' => "department_id", 'style' => 'height:22px;' ) );
//				$form->addElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
//				$form->addElement ( 'button', 'btn', get_lang ( 'Cancel' ), 'class="cancel" onclick="javascript:location.href=\'user_list.php\';"' );
//				echo '<div class="actions">';
//				$form->display ();
//				echo '</div>';
//			} else {
//                            tb_close('team_list.php');
////				Display::display_msgbox ( get_lang ( 'PlsSelectedUsers' ), $redirect_url );
//			}
//			exit ();
//			break;
//	}
//}

//搜索下拉菜单
$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );

$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text','keyword', get_lang ( 'keyword' ), array ('style' => "", 'class' => 'inputText', 'title' => $keyword_tip,'value'=>'输入搜索关键词','id'=>'searchkey') );

//$sql1 = "SELECT `id`,`dept_name` FROM `vslab`.`sys_dept`";
//$result1 = api_sql_query ( $sql1, __FILE__, __LINE__ );
//$arr= array ();

//while ( $arr = Database::fetch_row ( $result1) ) {
//    $arrs [] = $arr;
//}
//foreach ( $arrs as  $v){
//    $arrss[$v[1]]=$v[1];
//}

//array_unshift($arrss,"---所有部门---");
 
 
//$form->addElement ( 'select', 'dept_name', get_lang ( 'UserInDept' ), $arrss, array ('style' => 'min-width:150px;height:30px;border: 1px solid #999;' ) );

$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );


//面包屑导航
echo '<section id="main" class="column">';
echo '<h4 class="page-mark" >当前位置：平台首页  &gt; 战队管理</h4>';
echo '<div class="managerSearch">';
$form->display (); //searc form
echo '<span class="searchtxt right">';
$url_add_user='reward_add.php';
echo link_button ( 'add_user_big.gif', '添加赛事奖励', $url_add_user, '40%', '35%' );
//echo link_button ( 'excel.gif', '导入明文用户',($_configuration ['enable_user_ext_info']?'user_import2.php':'user_import.php'), '70%', '80%' );
//echo link_button ( 'excel.gif', '导入密文用户',($_configuration ['enable_user_ext_info']?'user_import_m.php':'user_import.php'), '70%', '80%' );
//echo link_button ( 'excel.gif', 'ExportUserListXMLCSV', 'user_export.php', '50%', '80%' );
echo '</span>';
echo "</div>";

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ["keyword"] )) {
	$parameters ['keyword'] = getgpc ( 'keyword', 'G' );
	$parameters = array ('keyword' => $_GET ['keyword'], 'keyword_status' => $_GET ['keyword_status'], 'keyword_org_id' => intval($_GET ["keyword_org_id"]) );
}

if (is_not_blank ( $_GET ["keyword_org_id"] )) $parameters ['keyword_org_id'] = intval( getgpc("keyword_org_id",'G') );
if ($_GET ['dept_name']) $parameters ['dept_name'] = $_GET ['dept_name'];

$table = new SortableTable ( 'admin_users', 'get_number_of_teams', 'get_team_data', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$idx = 0;
$table->set_header ( $idx ++, '',  false, null, null);
$table->set_header ( $idx ++, get_lang ( '赛事名称' ), false, null, array ('style' => 'width:15%' ));
$table->set_header ( $idx ++, get_lang ( '奖励等级' ), false, null, array ('style' => 'width:25%' ));
$table->set_header ( $idx ++, get_lang ( '奖励描述' ), false, null, array ('style' => 'width:25%' ));
//$table->set_header ( $idx ++, get_lang ( '查看战队成员' ), false, null, array ('style' => 'width:20%' ));
$table->set_header ( $idx ++, get_lang ( '操作' ), false, null, array ('style' => 'width:30%' ));
//$table->set_column_filter (4,'desc_filter');
//$table->set_column_filter ( 7, 'seatnumber_filter' );
$table->set_column_filter ( 4, 'active_filter' );
$actions = array ('delete' => get_lang ( 'BatchDelete' ),
//    'resetPassword' => get_lang ( 'ResetToDefaultPassword' ),
//    'batchChangeDept' => get_lang ( "BatchChangeUsersDept" ),
//    'batchLock' => get_lang ( "BatchLockUser" ),
//    'batchUnlock' => get_lang ( "BatchUnLockUser" ) 
   );
$table->set_form_actions ( $actions );
//$table->display ();get_team_data
?>
    <article class="module width_full">
        <table cellspacing="0" cellpadding="0" class="p-table">
	   <?php $table->display ();?>
<!--            <div class="actions">  --><?php //$form->display ();?><!-- </div>-->
        </table>
    </article>
</section>


?>
