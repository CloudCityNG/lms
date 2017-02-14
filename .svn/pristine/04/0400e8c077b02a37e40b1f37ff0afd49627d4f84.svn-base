<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include ('../inc/global.inc.php');
//require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'smsmanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'sendmail/SMTP.php');
api_protect_admin_script ();

$table_user = Database::get_main_table ( TABLE_MAIN_USER );
$tbl_category = Database::get_main_table ( TABLE_CATEGORY );
$table_position = Database::get_main_table ( TABLE_MAIN_SYS_POSITION );

//部门数据
$deptObj = new DeptManager ();
if (isset ( $_GET ['id'] )) {
	$one_dept_info = $deptObj->get_dept_info ( intval(getgpc ( 'id' )) );
}

function _license_user_count($values = NULL) {
	global $table_user;
	if (LICENSE_USER_COUNT == 0)
		return true;
	else {
		$sql = "SELECT COUNT(*) FROM " . $table_user;
		$user_count = Database::get_scalar_value ( $sql );
		return ($user_count <= LICENSE_USER_COUNT);
	}
}

function _check_org_user_quota() {
	return true;
}

$htmlHeadXtra [] = '<script type="text/javascript">
	$(document).ready( function() {
		$("#org_id").change(function(){
			$.get("' . api_get_path ( WEB_CODE_PATH ) . 'admin/ajax_actions.php",
				{action:"options_get_all_sub_depts",org_id:$("#org_id").val()},
				function(data,textStatus){
					//alert(data);
					$("#dept_id").html(data);
				});
		});
	});
</script>';

$htmlHeadXtra [] = '
<script language="JavaScript" type="text/JavaScript">
function enable_expiration_date() { //v2.0
	document.user_add.radio_expiration_date[0].checked=false;
	document.user_add.radio_expiration_date[1].checked=true;
}

function password_switch_radio_button(form, input){
	var NodeList = document.getElementsByTagName("input");
	for(var i=0; i< NodeList.length; i++){
		if(NodeList.item(i).name=="password[password_auto]" && NodeList.item(i).value=="0"){
			NodeList.item(i).checked=true;
		}
	}
}

function showadv() {
		if(document.user_add.advshow.checked == true) {
			G("adv").style.display = "";
		} else {
			G("adv").style.display = "none";
		}
}

function change_credeential_state(v){
		if(v!="0") {
			G("credential_no").disabled=false;
			G("credential_no").className="inputText";
			G("credential_no").style.display = "";
		}
		else {
			G("credential_no").value="";
			G("credential_no").className="";
			G("credential_no").style.display = "none";
			G("credential_no").disabled=true;
		}
}
</script>';

if (! empty ( $_GET ['message'] )) {
	$message = urldecode ( getgpc('message'));
}

$interbreadcrumb [] = array ("url" => api_get_path ( WEB_ADMIN_PATH ) . 'index.php', "name" => get_lang ( 'PlatformAdmin' ) );
$tool_name = get_lang ( 'AddUsers' );

$form = new FormValidator ( 'organizations_add');

//$form->addElement ( 'header', 'header', $tool_name );

// nt 网络靶场
$form->addElement ( 'text', 'nt', get_lang ( '网络靶场' ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
$form->addRule ( 'nt', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'nt', get_lang ( 'OnlyLettersAndNumbersAllowed' ), 'nt' );
$form->addRule ( 'nt', '', 'maxlength', 20 );
$form->addRule ( 'nt', get_lang ( 'UserTaken' ), 'username_available', $user_data ['nt'] );

// vmdisk 虚拟模板机名
$form->addElement ( 'text', 'vmdisk', get_lang ( '虚拟模板机名' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->applyFilter ( 'vmdisk', 'html_filter' );
$form->applyFilter ( 'vmdisk', 'trim' );
$form->addRule ( 'vmdisk', get_lang ( 'ThisFieldIsRequired' ), 'required' );

//org 所属组织
    $sql="select * from cn_org";
    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    $org = array();
    while($ss = Database::fetch_array ( $res )){
        $defaults = $ss;
	$org[$defaults['id']] = $defaults['org'];
    }
$form->addElement ( 'select', 'org', get_lang ( 'org' ), $org, array ('id' => "id", 'style' => 'height:22px;' ) );
if (isset ( $_GET ['keyword_deptid'] ) and is_not_blank($_GET ['keyword_deptid'])) $defaults['dept_id']=intval(getgpc ( 'keyword_deptid' ,'G'));

// type 标识模板类型
$stsys = array('渗透服务器','受保护服务器');
$form->addElement ( 'select', 'type', get_lang ( '标识模板类型' ), $stsys, array ('id' => "id", 'style' => 'height:22px;' ) );
$form->applyFilter ( 'type', 'html_filter' );

//ip 设置的ip
$form->addElement ( 'text', 'ip', get_lang ( '设置的ip' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->applyFilter ( 'ip', 'html_filter' );

//luse 登录用户
$form->addElement ( 'text', 'luse', get_lang ( '登录用户' ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
$form->addRule ( 'luse', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'luse', get_lang ( 'OnlyLettersAndNumbersAllowed' ), 'luse' );
$form->addRule ( 'luse', '', 'maxlength', 20 );

// lpasswd  登录密码
$form->addElement ( 'text', 'lpasswd', get_lang ( '登录密码' ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
$form->addRule ( 'lpasswd', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'lpasswd', get_lang ( 'OnlyLettersAndNumbersAllowed' ), 'lpasswd' );
$form->addRule ( 'lpasswd', '', 'maxlength', 20 );

// status 状态
$stat = array('无控制台','有控制台');
$form->addElement ( 'select', 'status', get_lang ( '状态' ), $stat, array ('id' => "id", 'style' => 'height:22px;' ) );
$form->addRule ( 'status', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'status', get_lang ( 'OnlyLettersAndNumbersAllowed' ), 'status' );
$form->addRule ( 'status', '', 'maxlength', 20 );


//提交
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit', get_lang ( 'Save' ), 'class="save"' );
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit_plus', get_lang ( 'SaveAdd' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, null, '&nbsp;', '&nbsp;&nbsp;' );
$form->applyFilter ( '__ALL__', 'trim' );

// Set default values 默认值
$defaults ['admin'] ['platform_admin'] = 0;
$defaults ['mail'] ['send_mail'] = 0;

$defaults ['password'] ['password_auto'] = 2;

$defaults ['active'] = 1;
$defaults ['sex'] = 0;

$defaults ['expiration_date'] = array ();
$days = api_get_setting ( 'account_valid_duration' );
$defaults ['expiration_date'] = date ( 'Y-m-d H:i', strtotime ( "+ $days day" ) );
$defaults ['radio_expiration_date'] = 0;


$form->setDefaults ( $defaults );

$form->addFormRule ( "_license_user_count" );

Display::setTemplateBorder ( $form, '98%' );

// Validate form
if ($form->validate ()) {
	
	$user = $form->getSubmitValues ();

	array_pop($user);
	$sql = Database::sql_insert ( 'cn_vmmanage', $user );
       
	$result = api_sql_query ( $sql, __FILE__, __LINE__ );

	
	if (isset ( $user ['submit_plus'] )) {
		api_redirect ( 'organizations_add.php?message=' . urlencode ( get_lang ( 'UserAdded' ) ) );
	} else {
		tb_close ( 'organizations_list.php?action=show_message&message=' . urlencode ( get_lang ( 'UserAdded' ) ) );
	}
}

Display::display_header($tool_name,FALSE);

if (! empty ( $message )) {
	Display::display_normal_message ( stripslashes ( $message ), false );
}

$form->display ();

Display::display_footer ();
