<?php
/**
 ==============================================================================

 ==============================================================================
 */

$language_file = array ('admin', 'registration' );
$cidReset = true;

include_once ('../inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();

$htmlHeadXtra [] = Display::display_thickbox ();

$action = getgpc ( 'action', 'G' );
$dept_id = isset ( $_GET ['keyword_deptid'] ) ? intval(getgpc ( 'keyword_deptid', 'G' )) : '0';

$sql = "SELECT user_id FROM " . $table_user . " WHERE is_admin=1 AND " . Database::create_in ( $_configuration ['default_administrator_name'], 'username' );
$root_user_id = Database::get_into_array ( $sql, __FILE__, __LINE__ );
$redirect_url = 'main/admin/user/organizations_list.php';
include_once ('../inc/header.inc.php');

if($platform==3){
    $nav='userlist';
}else{
    $nav='users';
}
echo '<aside id="sidebar" class="column ctfinex open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';




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
        $sql_where .= " AND and (firstname LIKE '%" . $keyword . "%'  OR username LIKE '%" . $keyword . "%'  OR official_code LIKE '%" . $keyword . "%'  OR dept_id LIKE '%" . $keyword ."%') ";
    }

    

    if (isset ( $_GET ['keyword_org_id'] ) && getgpc ( 'keyword_org_id' )) {
        $sql_where .= " AND org_id=" . escape (intval(getgpc ( "keyword_org_id" )) );
    }

    $action_array = array ('batchChangeDept' );
    if (isset ( $_POST ['id'] ) && is_array ( $_POST ['id'] ) && $_POST ['id']  && (isset ( $_POST ['action'] ) && in_array ( $_POST ['action'], $action_array ))) {
        $post_id= intval( getgpc('id'));
        $sql_where .= " AND user_id IN (" . implode ( ",", $post_id ) . ")";
    }

    

    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}
                
function get_number_of_users() {

	$sql = "SELECT COUNT(*) AS total_number_of_items FROM  `cn_vmmanage` WHERE  1 " ;
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
	return Database::getval ( $sql, __FILE__, __LINE__ );
}

function get_user_data($from, $number_of_items, $column, $direction) {
	$sql = "SELECT  id		AS col0,
                 	nt	AS col1,
                 	vmdisk 	AS col2,
                 	org 	AS col3,
                 	type 	AS col4,
                 	ip 	AS col5,
	luse	AS col6,
	lpasswd	    AS col7,
	status		AS col8,
                  id		AS col9,
                  id		AS col10,
	id		AS col11
	FROM  cn_vmmanage where 1";
	$sql_where = get_sqlwhere ();     
	if ($sql_where) $sql .=   $sql_where;
	$sql .= " ORDER BY col$column $direction ";
	$sql .= " LIMIT $from,$number_of_items";
//  echo $sql;
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();
	//$objDept = new DeptManager ();
	while ( $user = Database::fetch_row ( $res ) ) {

		$users [] = $user;
	}
	return $users;
}

//function email_filter($email) {
//	return Display::encrypted_mailto_link ( $email, $email );
//}

function modify_filter($id, $url_params) {
	global $_configuration, $root_user_id;
                 $result ='';
//	$result .= link_button ( 'synthese_view.gif', 'Info', 'user_information.php?user_id=' . $user_id, '90%', '90%', FALSE );
	//$result .= '<a class="thickbox" href="subscribe_course2user.php?user_id=' . $user_id . '&KeepThis=true&TB_iframe=true&height=420&width=780&modal=true">' . Display::return_icon('enroll.gif', get_lang('AddCoursesToUser'), array('style'=>'vertical-align: middle;')) . '</a>&nbsp;';
	//$result .= '&nbsp;<a href="user_subscribe_course_list.php?user_id=' . $user_id . '" target="_parent">' . Display::return_icon ( 'enroll.gif', get_lang ( 'CourseListSubAndArrange' ), array ('style' => 'vertical-align: middle;' ) ) . '</a>';
	//$result .= '&nbsp;' . link_button ( 'enroll.gif', 'CourseListSubAndArrange', 'user_subscribe_course_list.php?user_id=' . $user_id, '90%', '94%', FALSE );
	$result .= '&nbsp;' . link_button ( 'edit.gif', 'ModifyUserInfo', 'organizations_edit.php?id=' . $id, '90%', '80%', FALSE );
	if (api_is_platform_admin () && ! in_array ( $user_id, $root_user_id )) {
		$result .= '&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'organizations_list.php?action=delete_user&id=' . $id );
	}

	return $result;
}
//dengxin 
//close  vmdisk
if($_GET['action']=='unlock'  && $_GET['system']!=="" && $_GET['manage']=="1"){
    $system1=  getgpc("system","G");
    $id     =  getgpc("id","G");
    $keyword="?keyword=".$_GET['keyword'];
    $vm_datas=  api_sql_query_array_assoc("select * from  `vmtotal` where `system`='".$system1."' and `manage`='1' and `lesson_id`='managesvm' limit  0,1",__FILE__,__LINE__);
    $Addres=$vm_datas[0]['addres'];
    $Vmid  =$vm_datas[0]['vmid']; 
    sript_exec_log("sudo -u root /usr/bin/ssh root@$Addres /sbin/cloudvmstop.sh $Vmid");
    $delsql="delete from `vmtotal` where `system`='".$system1."' and `manage`='1' and `lesson_id`='managesvm'";
    $result = api_sql_query ( $delsql, __FILE__, __LINE__ );
    
    $redirect_url = "orgianization_list.php".$keyword;
    tb_close ( $redirect_url ); 
}
//join vmdisk
if($_GET['action']=='join'  && $_GET['system']!==""){
    $system1=  getgpc("system","G");
    $id     =  getgpc("id","G");
    $lessonId ='201308975637';
    $manage='0'; 
    $keyword="?keyword=".$_GET['keyword'];
    $vm_datas  =  api_sql_query_array_assoc("select * from  `vmtotal` where `system`='".$system1."'  and `lesson_id`='201308975637' limit  0,1",__FILE__,__LINE__);
    $Addres    =  $vm_datas[0]['addres'];
    $Vmid      =  $vm_datas[0]['vmid']; 
    $proxy_port=  $vm_datas[0]['proxy_port'];
	$local_ix=$_SERVER['HTTP_HOST'];
	$local_addresx = explode(':',$local_ix);
	$local_ip = $local_addresx[0];
    header("Location: http://$local_ix/lms/main/html5/cloudauto.php?lessonId=$lessonId&&host=$local_ip&port=$proxy_port&system=$system1&manage=$manage");
}

function  console_filter($row) {
//    $sql = 'select name from vmdisk where id='.$code;
//    $system  = Database::getval ( $sql, __FILE__, __LINE__ );
    $manage=Database::getval("select `manage` from `vmtotal` where `system`='".$row['2']."_".$row['3']."'",__FILE__ , __LINE__);
    if($manage){
        $html = "";
        $html .='<a href="orgianization_list.php?action=join&system='.$row['2'].'_'.$row['3'].'" target=_blank >连接</a>';
        return $html;
    }else{
        $html = "";
        $html .="无法连接";
        return $html;
    }
}


function org_filter($id){
    $sql="SELECT org FROM `cn_org` WHERE `id`=".$id;
    $re=mysql_query($sql);
    
    $arr=mysql_fetch_row($re);
    return $arr[0];
}



function status_filter($st){
    if($st==1){
        return '开启';
    }else{
        return '关闭';
    }
    
    
}


function active_den($active, $url_params, $row) {
    global $_user, $_configuration;
        $code = $row[0];
        $nicnum=intval($row[11]);
        if($nicnum<1){
            $nicnum=1;
        }
	
        $manage=Database::getval("select `manage` from `vmtotal` where `system`='".$row[2]."_".$row[3]."'",__FILE__ , __LINE__);
    if ($manage) { 
        $action = 'unlock';
        $image = 'right';
 	$title="已启动";
        $Path='organization_list.php?shutvm=1&action='.$action.'&manage=0&id='.$row[0].'&system=' . $row ['2'] . '_'.$row[3].'&keyword='.$_GET ['keyword'];
	 
   // return $result;
    }else {
        $action = 'unlock';
        $image = 'wrong';
	$title="未启动";
        $Path='../cloud/cloudvmstart.php?action=' . $action . '&manage=0&cid=201308975637&id='.$row[0].'&system=' . $row ['2'] . '_'.$row ['3'].'&nicnum='.$nicnum.'&keyword='.$_GET ['keyword'];
    }
    
 
        $result = '<a   href="'.$Path.'">' . Display::return_icon ( $image . '.gif', get_lang ( ucfirst ( $title ) ), array ('style' => 'vertical-align: middle;' ) ) . '</a>';

    return $result;
}

if($_GET['shutvm']=='1' && $_GET['system']!==''){
    $systems=getgpc('system','G');
    $addres=Database::getval('select `addres` from `vmtotal` where `system`='.$systems,__FILE__,__LINE__);
    $sql1 = "select `id`,`vmid`,`user_id`,`addres` FROM  `vmtotal` where `addres`= '".$addres."'";
    $res = api_sql_query_array_assoc ( $sql1, __FILE__, __LINE__ );

    $vmid  =$res[0]['vmid'];
    $addres=$res[0]['addres'];
        if($vmid && $addres && $_SESSION['_user']['username']){
           sript_exec_log("sudo -u root /usr/bin/ssh root@$addres /sbin/cloudvmstop.sh $vmid");
        }
    $sqla="delete from `vmtotal` where `id` =".$ids;
    api_sql_query ( $sqla, __FILE__, __LINE__ );
}
//dengxin  end 
if (isset ( $_GET ['action'] )) {
    $get_user_id=  intval(getgpc('user_id'));
    $sql="delete from cn_vmmanage where id=".$_GET['id'];
    $ret=mysql_query($sql);
	switch (getgpc('action','G')) {
		case 'delete_user' :
			if (getgpc ( 'user_id', 'G' ) != api_get_user_id () && UserManager::delete_user ($get_user_id)) {
				api_logging ( get_lang ( 'DelUser' ) . $get_user_id, 'USER' );
                                                                                    tb_close('organizations_list.php');
                                                                                   //Display::display_msgbox ( get_lang ( 'UserDeleted' ), $redirect_url );
			} else {
                                                                                    tb_close('organizations_list.php');
                                                                                   //Display::display_msgbox ( get_lang ( 'CannotDeleteUser' ), $redirect_url, 'warning' );
			}
			break;
		case 'lock' :
			$message = lock_unlock_user ( 'lock', $get_user_id );tb_close('organizations_list.php');
			//Display::display_msgbox ( get_lang ( 'OperationSuccess' ), $redirect_url );
			break;
		case 'unlock' :
			$message = lock_unlock_user ( 'unlock',$get_user_id );tb_close('organizations_list.php');
			//Display::display_msgbox ( get_lang ( 'OperationSuccess' ), $redirect_url );
			break;
	}
}
if (isset ( $_POST ['action'] )) {
	switch ($_POST ['action']) {
		case 'delete' :
			$number_of_selected_users = count ( $_POST ['id'] );
			$number_of_deleted_users = 0;
                                                $del_id= $_POST['id'];
			foreach ($del_id as $index => $user_id ) {
				if ($user_id != api_get_user_id ()) {
					if (UserManager::delete_user ( $user_id )) {
						api_logging ( get_lang ( 'DelUsers' ) . $user_id, 'USER', 'DelUsers' );
						$number_of_deleted_users ++;
					}
				}
			}
			if ($number_of_selected_users == $number_of_deleted_users) {
                                                                                        tb_close('organizations_list.php');
//				Display::display_msgbox ( get_lang ( 'SelectedUsersDeleted' ), $redirect_url );
			} else {
                                                                                        tb_close('organizations_list.php');
//				Display::display_msgbox ( get_lang ( 'SomeUsersNotDeleted' ), $redirect_url, 'warning' );
			}
			break;
		

		
	}
}



//Display::display_header (NULL, FALSE);

if (isset ( $_POST ['action'] )) {
	switch ($_POST ['action']) {
		case 'batchChangeDept' : //批量修改用户部门表单
			$number_of_selected_users = count ( $_POST ['id'] );
			if ($number_of_selected_users > 0) {
				$user_id_str = implode ( ",", $_POST ['id'] );
				$form = new FormValidator ( 'batch_change_dept', 'post' );
				//$form->addElement('header', 'header', get_lang('SearchAUser'));
				$form->addElement ( "hidden", "action", 'batchChangeDeptSave' );
				$form->addElement ( "hidden", "user_id_str", $user_id_str );
				$renderer = $form->defaultRenderer ();
				$renderer->setElementTemplate ( '<span>{element}</span> ' );
				$form->addElement ( 'static', 'text1', null, get_lang ( "PlsSelectTheDeptMoveTo" ) );
				//unset($dept_options[0]);
				$dept_options [0] = NULL;
				//$form->addElement ( 'select', 'org_id', get_lang ( 'InOrg' ), $orgs, array ('id' => "org_id", 'style' => 'height:22px;' ) );
				$depts = $objDept->get_sub_dept_ddl2 ( 0, 'array' );
				$form->addElement ( 'select', 'department_id', get_lang ( 'UserInDept' ), $depts, array ('id' => "department_id", 'style' => 'height:22px;' ) );
				$form->addElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
				$form->addElement ( 'button', 'btn', get_lang ( 'Cancel' ), 'class="cancel" onclick="javascript:location.href=\'organizations_list.php\';"' );
				echo '<div class="actions">';
				$form->display ();
				echo '</div>';
			} else {
                            tb_close('organizations_list.php');
//				Display::display_msgbox ( get_lang ( 'PlsSelectedUsers' ), $redirect_url );
			}
			exit ();
			break;
	}
}

$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "", 'class' => 'inputText', 'title' => $keyword_tip,'value'=>'输入搜索关键词','id'=>'searchkey') );



//array_unshift($arrss,"---所有部门---");
//$form->addElement ( 'select', 'dept_name', get_lang ( 'UserInDept' ), $arrss, array ('style' => 'min-width:150px;height:30px;border: 1px solid #999;' ) );

$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );

//by changzf
echo '<section id="main" class="column">';
echo '<h4 class="page-mark" >当前位置：平台首页  &gt; 大赛场景管理</h4>';
echo '<div class="managerSearch">';
$form->display (); //searc form
echo '<span class="searchtxt right">';
$url_add_user='organizations_add.php?keyword_deptid='.(is_not_blank($_GET['keyword_deptid'])?intval(getgpc('keyword_deptid','G')):intval(getgpc('keyword_orgid','G')));
echo link_button ( 'add_user_big.gif', '添加场景', $url_add_user, '90%', '90%' );
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

$table = new SortableTable ( 'admin_users', 'get_number_of_users', 'get_user_data', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$idx = 0;


$table->set_header ( $idx ++, '',  false, null, null);
$table->set_header ( $idx ++, get_lang ( '网络靶场' ), false, null, array ('style' => 'width:8%' ));
$table->set_header ( $idx ++, get_lang ( '虚拟模板机名' ), false, null, array ('style' => 'width:9%' ));
$table->set_header ( $idx ++, get_lang ( '组织' ), false, null, array ('style' => 'width:7%' ));
$table->set_header ( $idx ++, get_lang ( '标识模板类型' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '设置的ip' ), false, null, array ('style' => 'width:9%' ));
$table->set_header ( $idx ++, get_lang ( '登录用户' ), false, null, array ('style' => 'width:9%' ));
$table->set_header ( $idx ++, get_lang ( '登录密码' ), false, null, array ('style' => 'width:9%' ) );
$table->set_header ( $idx ++, get_lang ( '状态' ), false, null, array ('style' => 'width:9%' ));
$table->set_header ( $idx ++, get_lang ( '启动' ), false, null, array ('style' => 'width:9%' ));
$table->set_header ( $idx ++, get_lang ( '连接' ), false, null, array ('style' => 'width:9%' ));
$table->set_header ( $idx ++, get_lang ( 'Actions' ), false, null, array ('style' => 'width:20%' ) );
//$table->set_column_filter ( 8, 'active_filter' );
$table->set_column_filter ( 3, 'org_filter' );
$table->set_column_filter ( 8, 'status_filter' );
$table->set_column_filter ( 9, 'active_den' );
$table->set_column_filter ( 10, 'console_filter' );
$table->set_column_filter ( 11, 'modify_filter' );
$actions = array ('delete' => get_lang ( 'BatchDelete' ),
 );
$table->set_form_actions ( $actions );

?>
    <article class="module width_full">
        <table cellspacing="0" cellpadding="0" class="p-table">
	   <?php $table->display ();?>
        </table>
    </article>
</section>
<?php

