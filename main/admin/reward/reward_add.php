<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include ('../../inc/global.inc.php');
//require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'smsmanager.lib.php');
//require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
//require_once (api_get_path ( INCLUDE_PATH ) . 'sendmail/SMTP.php');
api_protect_admin_script ();

$table_user = Database::get_main_table ( TABLE_MAIN_USER );
$tbl_category = Database::get_main_table ( TABLE_CATEGORY );
$table_position = Database::get_main_table ( TABLE_MAIN_SYS_POSITION );

////部门数据
//$deptObj = new DeptManager ();
//if (isset ( $_GET ['dept_id'] )) {
//	$one_dept_info = $deptObj->get_dept_info ( intval(getgpc ( 'dept_id' )) );
//}

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
$tool_name = get_lang ( '新增奖励' );
$form = new FormValidator ( 'user_add' );
function get_contestname() {
	$c_table = Database::get_main_table ( 'tbl_contest' );
	$sql = "SELECT  id		AS col0,
                 	matchName	AS col1
	FROM $c_table";
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();

	while ( $user = Database::fetch_row ( $res ) ) {
		$users [$user[0]] = $user[1];
	}
	return $users;
}

$cName=  get_contestname();

$form->addElement ( 'select', 'match_id', get_lang ( '赛事名称' ), $cName, array ('id' => "dept_id", 'style' => 'height:22px;' ) );
//if (isset ( $_GET ['keyword_deptid'] ) and is_not_blank($_GET ['keyword_deptid'])) $defaults['dept_id']=intval(getgpc ( 'keyword_deptid' ,'G'));
$form->addElement ( 'text', 'grade', get_lang ( '奖励等级' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->addRule ( 'garade', get_lang ( '只能输入数字' ), 'numeric' );
$form->addRule ( 'garade', get_lang ( '只能输入数字' ), 'required' );
//奖励描述
$form->addElement ( 'textarea', 'reDesc', get_lang ( '奖励描述' ), array ('maxlength' => 50, 'style' => "width:250px;height:60px", 'class' => 'inputText' ) );
$form->addRule ( 'reDesc', '奖励描述不能为空', 'required' );
$form->addRule ( 'reDesc', '最多只能输入50个字符', 'maxlength', 50 );
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit', get_lang ( 'Save' ), 'class="save"' );
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit_plus', get_lang ( 'SaveAdd' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, null, '&nbsp;', '&nbsp;&nbsp;' );
$form->applyFilter ( '__ALL__', 'trim' );
// Set default values 默认值
//$defaults ['admin'] ['platform_admin'] = 0;
//$defaults ['mail'] ['send_mail'] = 0;
//
//$defaults ['password'] ['password_auto'] = 2;
//
//$defaults ['active'] = 1;
//$defaults ['sex'] = 0;
//
//$defaults ['expiration_date'] = array ();
//$days = api_get_setting ( 'account_valid_duration' );
//$defaults ['expiration_date'] = date ( 'Y-m-d H:i', strtotime ( "+ $days day" ) );
//$defaults ['radio_expiration_date'] = 0;


$form->setDefaults ( $defaults );

$form->addFormRule ( "_license_user_count" );

Display::setTemplateBorder ( $form, '98%' );

// Validate form
if ($form->validate ()) {
//	if (_license_user_count () == FALSE or _check_org_user_quota () == FALSE) {
//		api_redirect ( 'user_add.php?message=' . urlencode ( get_lang ( 'UserCountExcess' ) ) );
//	}
	
	$c = $form->getSubmitValues ();
	$matchId = $c ['match_id'];
	$reDesc = $c ['reDesc'];
        $grade =$c['grade'];
   // echo $seatnumber;
//	$status = intval ( $user ['status'] );
//	$platform_admin = ($status == PLATFORM_ADMIN ? 1 : 0);
//	$active = intval ( $user ['active'] );
//	$send_mail = intval ( $user ['mail'] ['send_mail'] ); //发送邮件
//	$dept_id = $user ['dept_id'];
//	$dept_in_org=$deptObj->get_dept_in_org($dept_id );
//	$dept_org=array_pop($dept_in_org);
//	$org_id=$dept_org['id'];
//	
//	$phone = $user ['phone'];
//	$mobile = $user ['mobile'];
//	$msn = $user ['msn'];
//	$qq = $user ['qq'];
//	$sex = $user ['sex'];
//	$zip_code = $user ['zip_code'];
//	$address = $user ['address'];
//	$credential_type = $user ['credential'] ['credential_type'];
//	$credential_no = ($credential_type == '0' ? '' : $user ['credential'] ['credential_no']);
//	$description = $user ['description'];
//	
      
$sql= "INSERT INTO tbl_Reward(match_id,grade,reDesc) VALUES ($matchId,$grade,$reDesc)";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
echo $sql;
if (isset ( $c ['submit_plus'] )) {
	api_redirect ( 'reward_add.php?message=' . urlencode ( get_lang ( '奖励已添加' ) ) );
} else {
        tb_close ( 'reward_list.php?action=show_message&message=' . urlencode ( get_lang ( '奖励已添加' ) ) );
	}
}

Display::display_header($tool_name,FALSE);

if (! empty ( $message )) {
	Display::display_normal_message ( stripslashes ( $message ), false );
}

$form->display ();

Display::display_footer ();
