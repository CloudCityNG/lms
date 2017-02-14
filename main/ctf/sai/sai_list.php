<?php
/**
 ==============================================================================

 ==============================================================================
 */

$language_file = array ('admin', 'registration' );
$cidReset = true;

include_once ('../../inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();

$htmlHeadXtra [] = Display::display_thickbox ();
                
$action = getgpc ( 'action', 'G' );
$dept_id = isset ( $_GET ['keyword_deptid'] ) ? intval(getgpc ( 'keyword_deptid', 'G' )) : '0';

$sql = "SELECT user_id FROM " . $table_user . " WHERE is_admin=1 AND " . Database::create_in ( $_configuration ['default_administrator_name'], 'username' );
$root_user_id = Database::get_into_array ( $sql, __FILE__, __LINE__ );
$redirect_url = 'main/admin/user/user_list.php';
include_once ('../../inc/header.inc.php');

echo '<aside id="sidebar" class="column ctfinex open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';

function update_state($state,$id){
    $c_table=Database::get_main_table ( tbl_contest );
    $sql = "UPDATE $c_table SET status = $state WHERE id =$id";

    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    
    return $res;
}




if($_GET['changestate']==0 || $_GET['changestate']==1){
    update_state($_GET['changestate'],$_GET['id']);
}

//部门数据
$objDept = new DeptManager ();

function get_sqlwhere() {
     global $objDept;
    $sql_where = "";
    if (isset ( $_GET ['keyword'] ) && ! empty ( $_GET ['keyword'] )) {
        $keyword = escape ( getgpc ( 'keyword', 'G' ), TRUE );
        if($keyword=='输入搜索关键词'){
            $keyword='';
        }
                $sql_where .= " and where (matchName LIKE '%" . $keyword . "%') ";
    }

    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}
                
                
function get_number_of_users() {
	$user_table = Database::get_main_table ( SAI_CONTEST );
	$sql = "SELECT COUNT(*) AS total_number_of_items FROM " . $user_table." " ;
        $sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
	return Database::getval ( $sql, __FILE__, __LINE__ );
}

function get_user_data($from, $number_of_items) {
	$user_table = Database::get_main_table ( tbl_contest );
	$sql = "SELECT id,matchName,id,id,id,id,id,id,id,matchSite,id,id
	FROM  $user_table    ";
	$sql_where = get_sqlwhere ();     
	if ($sql_where) $sql .= $sql_where;
	$sql .= " LIMIT $from,$number_of_items";
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
        //var_dump($res);
	$users = array ();
	//$objDept = new DeptManager ();
	while ( $user = Database::fetch_row ( $res ) ) {
		$users [] = $user;
        }
	return $users;
}

function st_filter($id){
    $c_table=Database::get_main_table ( tbl_contest );
    $sql = "SELECT status FROM $c_table where id=$id";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $status = Database::fetch_row ( $res );
   
    if($status[0]){
       return ' <a href="sai_list.php?changestate=0&id='.$id.'"><img src="../../../themes/img/prev.gif" alt="开启" title="开启" style="vertical-align: middle;"></a>';
    }else{
       return ' <a href="sai_list.php?changestate=1&id='.$id.'"><img src="../../../themes/img/prev_disabled.gif" alt="关闭" title="关闭" style="vertical-align: middle;"></a>';
    }
}

function xm_filter($id) {
  global $_configuration;
    $result ='';
    $result .= link_button ( 'synthese_view.gif', 'Info', 'sai_miaoshu.php?id=' . $id, '90%', '90%', FALSE );
    return $result;
}

function gm_filter($id) {
  global $_configuration;
    $result ='';
    $result .= link_button ( 'synthese_view.gif', 'Info', 'sai_guize.php?id=' . $id, '90%', '90%', FALSE );
    return $result;
}
function tm_filter($id) {
    $user_table = Database::get_main_table ( SAI_CONTEST );
       $result .= Database::getval("select matchStime  FROM  $user_table where id=".$id,__FILE__,__LINE__);
          //   var_dump($result);
       $result=date("Y-m-d H:i:s",$result);
    return $result;
}

function jm_filter($id) {
    $user_table = Database::get_main_table ( SAI_CONTEST );
       $result .= Database::getval("select matchEtime  FROM  $user_table where id=".$id,__FILE__,__LINE__);
       //var_dump($result);
       $result=date('Y-m-d H:i:s',$result);
      
    return $result;
}
function sm_filter($id) {  
    $user_table = Database::get_main_table ( SAI_CONTEST );
       $result .= Database::getval("select matchAard  FROM  $user_table where id=".$id,__FILE__,__LINE__);
             
       $result=date("Y-m-d H:i:s",$result);
    return $result;
}

function dm_filter($id) {
    $user_table = Database::get_main_table ( SAI_CONTEST );
       $result .= Database::getval("select matchSelt  FROM  $user_table where id=".$id,__FILE__,__LINE__);

       $result=date("Y-m-d H:i:s",$result);
       //var_dump($result);
    return $result;
}
function am_filter($id) {
   global $_configuration;
    $result ='';
	$result .= link_button ( 'synthese_view.gif', 'Info', 'sai_bisai.php?id=' . $id, '90%', '90%', FALSE );
    return $result;
}
//var_dump($result);
function modify_filter($id, $url_params) {
	global $_configuration;
    $result ='';
	//$result .= link_button ( 'synthese_view.gif', 'Info', 'user_information.php?user_id=' . $user_id, '90%', '90%', FALSE );
	$result .= '&nbsp;' . link_button ( 'edit.gif', 'ModifyUserInfo', 'sai_edit.php?id=' . $id, '80%', '80%', FALSE );
	if (api_is_platform_admin () && ! in_array ( $id )) {
		$result .= '&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'sai_list.php?action=delete_sai&id=' . $id );
	}
	return $result;
}


if (isset ( $_GET ['action'] )) {
    $id=  intval(getgpc('id'));
	switch (getgpc('action','G')) {
                case 'delete_sai':
                    $sql="delete from tbl_contest where id=".$id;
                    api_sql_query ( $sql, __FILE__, __LINE__ );
                    tb_close( 'sai_list.php' );
	}
}

if (isset ( $_POST ['action'] )) {
	switch ($_POST ['action']) {
		case 'delete' :
			$number_of_selected_users = count ( $_POST ['id'] );
                    $del_id= $_POST['id'];
			foreach ($del_id as  $id ) {
				if (!empty($id)) {
                                    $sql="delete from tbl_contest where id=".$id;
                                     api_sql_query ( $sql, __FILE__, __LINE__ );
				}
			}
        }
}
$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "", 'class' => 'inputText', 'title' => $keyword_tip,'value'=>'输入搜索关键词','id'=>'searchkey') );

$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );

//by changzf
echo '<section id="main" class="column">';
echo '<h4 class="page-mark" >当前位置：平台首页  &gt; 赛事管理</h4>';
echo '<div class="managerSearch">';
$form->display (); //searc form
echo '<span class="searchtxt right">';
$url_add_user='sai_add.php?keyword_deptid='.(is_not_blank($_GET['keyword_deptid'])?intval(getgpc('keyword_deptid','G')):intval(getgpc('keyword_orgid','G')));
echo link_button ( 'add_user_big.gif', '新增赛事', $url_add_user, '80%', '90%' );

echo '</span>';
echo "</div>";

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ["keyword"] )) {
	$parameters ['keyword'] = getgpc ( 'keyword', 'G' );
	$parameters = array ('keyword' => $_GET ['keyword'], 'keyword_status' => $_GET ['keyword_status'], 'keyword_org_id' => intval($_GET ["keyword_org_id"]) );
}

if (is_not_blank ( $_GET ["keyword_org_id"] )) $parameters ['keyword_org_id'] = intval( getgpc("keyword_org_id",'G') );
if ($_GET ['dept_name']) $parameters ['dept_name'] = $_GET ['dept_name'];

$table = new SortableTable ( 'admin_users', 'get_number_of_users', 'get_user_data', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$idx = 0;
$table->set_header ( $idx ++, '',  false, null, null);
$table->set_header ( $idx ++, get_lang ( '赛事名称' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '赛事公告' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '赛事开始时间' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '赛事结束时间' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '赛事简介' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '大赛评选' ), false, null, array ('style' => 'width:10%' ) );
$table->set_header ( $idx ++, get_lang ( '大赛颁奖' ), false, null, array ('style' => 'width:10%' ) );
$table->set_header ( $idx ++, get_lang ( '大赛规则' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '比赛场地' ), false, null, array ('style' => 'width:15%' ) );
$table->set_header ( $idx ++, get_lang ( '比赛状态' ), false, null, array ('style' => 'width:15%' ) );
$table->set_header ( $idx ++, get_lang ( '操作' ), false, null, array ('style' => 'width:10%' ) );
$table->set_column_filter ( 11, 'modify_filter' );
$table->set_column_filter ( 10, 'st_filter' );
$table->set_column_filter ( 2, 'xm_filter' );
$table->set_column_filter ( 5, 'gm_filter' );
$table->set_column_filter ( 3, 'tm_filter' );
$table->set_column_filter ( 4, 'jm_filter' );
$table->set_column_filter ( 7, 'sm_filter' );
$table->set_column_filter ( 6, 'dm_filter' );
$table->set_column_filter ( 8, 'am_filter' );
$actions = array ('delete' => get_lang ( 'BatchDelete' ) );
$table->set_form_actions ( $actions );
//$table->display ();

?>  
    <article class="module width_full">
        <table cellspacing="0" cellpadding="0" class="p-table">
	   <?php $table->display ();?>
<!--            <div class="actions">  --><?php //$form->display ();?><!-- </div>-->
        </table>
    </article>
</section>
<?php

