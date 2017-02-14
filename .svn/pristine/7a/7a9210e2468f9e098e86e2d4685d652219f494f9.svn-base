<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include_once ('../../inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
$team_table = Database::get_main_table ( 'tbl_team' );
$htmlHeadXtra [] = Display::display_thickbox ();

$action = getgpc ( 'action', 'G' );


$sql = "SELECT user_id FROM " . $table_user . " WHERE is_admin=1 AND " . Database::create_in ( $_configuration ['default_administrator_name'], 'username' );
$root_user_id = Database::get_into_array ( $sql, __FILE__, __LINE__ );
$redirect_url = 'main/admin/team/team_list.php';
include_once ('../../inc/header.inc.php');

echo '<aside id="sidebar" class="column ctfinex open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';

//部门数据
$objDept = new DeptManager ();
//搜索功能
function get_sqlwhere() {
    global $objDept;
    $sql_where = "";
    if (isset ( $_GET ['keyword'] ) && ! empty ( $_GET ['keyword'] )) {
        $keyword = escape ( getgpc ( 'keyword', 'G' ), TRUE );
        if($keyword=='输入搜索关键词'){
            $sql_where=null;
        }else{
            $sql_where .= " AND where t.teamName LIKE '%" . $keyword . "%'  OR t.teamNode LIKE '%" . $keyword . "%' ";}
    }

    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
        else return "";
}
//总条数             
function get_number_of_teams() {
        $user_table = Database::get_main_table ( 'user' );
	$team_table = Database::get_main_table ( 'tbl_team' );
	$sql = "SELECT COUNT(*) AS total_number_of_items FROM $team_table as t ";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
        $num=Database::getval ( $sql, __FILE__, __LINE__ );
	return $num;
}
//获取要显示的数据
function get_team_data($from, $number_of_items, $column, $direction) {
        $user_table = Database::get_main_table ( 'user' );
	$team_table = Database::get_main_table ( 'tbl_team' );
	$sql = "SELECT  t.id		AS col0,
                 	t.teamNode	AS col1,
                 	t.teamName 	AS col2,
                 	t.teamAdmin     AS col3,
                 	t.id 	        AS col4,
                        t.id            AS col5
	FROM  $team_table AS t ";
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


function get_teamname($id){
    $sql = 'SELECT username FROM user WHERE user_id ='.$id;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $user = Database::fetch_row ( $res );
    if(!empty($user)){	
        return $user[0];
    }else{
        return '队长不存在';
    }
}

function desc_filter($id){
    $link = link_button ( 'coachs.gif', '查看战队成员', 'team_desc.php?id=' . $id, '45%', '45%', FALSE );
    return $link;
}


//编辑 删除链接 
function active_filter($team_id) {
        $result = link_button ( 'edit.gif', '修改战队信息', 'team_edit.php?id=' . $team_id, '60%', '50%', FALSE )."&nbsp;&nbsp;".confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'team_list.php?action=delete_team&id=' . $team_id );
	return $result;
}

//单个删除
if (isset ( $_GET ['action'])) {
	switch (getgpc('action','G')) {
            case 'delete_team' :
                $team_id = getgpc('id','G');
                
                $team_table = Database::get_main_table ( 'tbl_team' );
                $sql = "DELETE FROM $team_table WHERE id = $team_id";
                $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                if($res){
                    $sql = "UPDATE user SET teamId = NULL WHERE teamId = $team_id";
                    $res2 = api_sql_query ( $sql, __FILE__, __LINE__ );
                }
                if($res2){
                    $sql = "DELETE FROM tbl_cation WHERE teamId = $team_id";
                    $res3 = api_sql_query ( $sql, __FILE__, __LINE__ );
                }
                        if($res3){
                               tb_close('team_list.php');
                               Display::display_msgbox ( get_lang ( '删除成功' ), $redirect_url );
			} else {
                               tb_close('team_list.php');
                               Display::display_msgbox ( get_lang ( '删除失败' ), $redirect_url, 'warning' );
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
			foreach ($del_id as $index => $team_id ) {
                             $team_table = Database::get_main_table ( 'tbl_team' );
                             $sql = "DELETE FROM $team_table WHERE id = $team_id";
                             $res = api_sql_query ( $sql, __FILE__, __LINE__ );
//				if ($user_id != api_get_user_id ()) {
//					if (UserManager::delete_user ( $user_id )) {
//						api_logging ( get_lang ( 'DelUsers' ) . $user_id, 'USER', 'DelUsers' );
						$number_of_deleted_users ++;
//}
				}
                        if($res){
                                $sql = "UPDATE user SET teamId = NULL WHERE teamId = $team_id";
                                $res2 = api_sql_query ( $sql, __FILE__, __LINE__ );
                         }
                         if($res2){
                             $sql = "DELETE FROM tbl_cation WHERE teamId = $team_id";
                             $res3 = api_sql_query ( $sql, __FILE__, __LINE__ );
                         }
                        
			}
			if ($number_of_selected_users == $number_of_deleted_users) {
                                                                                    tb_close('team_list.php');
				Display::display_msgbox ( get_lang ( '删除成功' ), $redirect_url );
			} else {
                                                                                     tb_close('team_list.php');
				Display::display_msgbox ( get_lang ( '删除失败' ), $redirect_url, 'warning' );
			}
			break;

}

//搜索下拉菜单
$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );

$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text','keyword', get_lang ( 'keyword' ), array ('style' => "", 'class' => 'inputText', 'title' => $keyword_tip,'value'=>'输入搜索关键词','id'=>'searchkey') );


$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );


//面包屑导航
echo '<section id="main" class="column">';
echo '<h4 class="page-mark" >当前位置：平台首页  &gt; 战队管理</h4>';
echo '<div class="managerSearch">';
$form->display (); //searc form
echo '<span class="searchtxt right">';
$url_add_user='team_add.php';
//echo link_button ( 'add_user_big.gif', 'AddUsers', $url_add_user, '90%', '90%' );
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
$table->set_header ( $idx ++, get_lang ( '战队编号' ), false, null, array ('style' => 'width:15%' ));
$table->set_header ( $idx ++, get_lang ( '战队名称' ), false, null, array ('style' => 'width:25%' ));
$table->set_header ( $idx ++, get_lang ( '队长' ), false, null, array ('style' => 'width:25%' ));
$table->set_header ( $idx ++, get_lang ( '查看战队成员' ), false, null, array ('style' => 'width:20%' ));
$table->set_header ( $idx ++, get_lang ( '操作' ), false, null, array ('style' => 'width:30%' ));
$table->set_column_filter (3,'get_teamname');
$table->set_column_filter (4,'desc_filter');

$table->set_column_filter ( 5, 'active_filter' );

$actions = array ('delete' => get_lang ( 'BatchDelete' ),
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
