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
if (isset ( $_GET ['dept_id'] )) {
	$one_dept_info = $deptObj->get_dept_info ( intval(getgpc ( 'dept_id' )) );
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

$form = new FormValidator ( 'contest_add' );

//org 组织
$form->addElement ( 'text', 'org', get_lang ( '组织' ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
$form->addRule ( 'org', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'org', get_lang ( 'OnlyLettersAndNumbersAllowed' ), 'org' );
$form->addRule ( 'org', '', 'maxlength', 20 );
$form->addRule ( 'org', get_lang ( 'UserTaken' ), 'username_available', $user_data ['org'] );

// passpor 提交证书
$form->addElement ( 'text', 'passport', get_lang ( '提交证书' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->applyFilter ( 'passport', 'html_filter' );
$form->applyFilter ( 'passport', 'trim' );
$form->addRule ( 'passport', get_lang ( 'ThisFieldIsRequired' ), 'required' );

// roe 角色
$form->addElement ( 'text', 'roe', get_lang ( '角色' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->applyFilter ( 'roe', 'html_filter' );
$form->addRule ( 'roe', get_lang ( 'ThisFieldIsRequired' ), 'required' );

//提交
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit', get_lang ( 'Save' ), 'class="save"' );
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit_plus', get_lang ( 'SaveAdd' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, null, '&nbsp;', '&nbsp;&nbsp;' );
$form->applyFilter ( '__ALL__', 'trim' );

Display::setTemplateBorder ( $form, '98%' );


// Validate form
if ($form->validate ()) {
	if (_license_user_count () == FALSE or _check_org_user_quota () == FALSE) {
		api_redirect ( 'contest_add.php?message=' . urlencode ( get_lang ( 'UserCountExcess' ) ) );
	}
	
	$user = $form->getSubmitValues ();
	array_pop($user);
	$sql = Database::sql_insert ( 'cn_org', $user );
	$result = api_sql_query ( $sql, __FILE__, __LINE__ );

	if (isset ( $user ['submit_plus'] )) {
		api_redirect ( 'contest_add.php?message=' . urlencode ( get_lang ( 'UserAdded' ) ) );
	} else {
		tb_close ( 'contest_list.php?action=show_message&message=' . urlencode ( get_lang ( 'UserAdded' ) ) );
	}
}

Display::display_header($tool_name,FALSE);

if (! empty ( $message )) {
	Display::display_normal_message ( stripslashes ( $message ), false );
}

$form->display ();

Display::display_footer ();
