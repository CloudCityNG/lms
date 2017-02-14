<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include ('../../inc/global.inc.php');
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

$form = new FormValidator ( 'user_add' );

//$form->addElement ( 'header', 'header', $tool_name );

// Username 登录名
$form->addElement ( 'text', 'username', get_lang ( 'LoginName' ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
$form->addRule ( 'username', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'username', get_lang ( 'OnlyLettersAndNumbersAllowed' ), 'username' );
$form->addRule ( 'username', '', 'maxlength', 20 );
$form->addRule ( 'username', get_lang ( 'UserTaken' ), 'username_available', $user_data ['username'] );

// Password 密码
$group = array ();
$auth_sources = 0; //make available wider as we need it in case of form reset (see below)
if (count ( $extAuthSource ) > 0) {
	$group [] = & HTML_QuickForm::createElement ( 'radio', 'password_auto', null, get_lang ( 'ExternalAuthentication' ) . ' ', 3 );
	$auth_sources = array ();
	foreach ( $extAuthSource as $key => $info ) {
		$auth_sources [$key] = $key;
	}
	$group [] = & HTML_QuickForm::createElement ( 'select', 'auth_source', null, $auth_sources );
	$group [] = & HTML_QuickForm::createElement ( 'static', '', '', '<br />' );
}
$group [] = & HTML_QuickForm::createElement ( 'radio', 'password_auto', null, get_lang ( 'UseDefaultPassword' ), 2, array ('onclick' => 'document.getElementById(\'password\').value=\'\';' ) );
$group [] = & HTML_QuickForm::createElement ( 'radio', 'password_auto', null, null, 0 );
$group [] = & HTML_QuickForm::createElement ( 'password', 'password', null, array ('id' => 'password', 'style' => "width:150px", 'class' => 'inputText', 'onkeydown' => 'password_switch_radio_button(document.user_add,"password[password_auto]")' ) );
$form->addGroup ( $group, 'password', get_lang ( 'Password' ), '&nbsp;' );

// Firstname 姓名
$form->addElement ( 'text', 'firstname', get_lang ( 'FirstName' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->applyFilter ( 'firstname', 'html_filter' );
$form->applyFilter ( 'firstname', 'trim' );
$form->addRule ( 'firstname', get_lang ( 'ThisFieldIsRequired' ), 'required' );

// Email
$form->addElement ( 'text', 'email', get_lang ( 'Email' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->addRule ( 'email', get_lang ( 'EmailWrong' ), 'required' );
$form->addRule ( 'email', get_lang ( 'EmailWrong' ), 'email' );
//$form->addRule ( 'email', get_lang ( 'EmailTaken' ), 'email_available' );

// Official code 编号
$form->addElement ( 'text', 'official_code', get_lang ( 'OfficialCode' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->applyFilter ( 'official_code', 'html_filter' );

$form->addElement ( 'text', 'seatnumber', get_lang ( '座位号' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
$form->applyFilter ( 'seatnumber', 'html_filter' );


$form->addElement ( 'text', 'lastname', get_lang ( 'LastName' ), array ('style' => "width:230px", 'class' => 'inputText', 'id' => 'lastname' ) );
//$form->addRule ( 'lastname', get_lang ( 'ThisFieldIsRequired' ), 'required' );


//角色
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'radio', 'status', null, get_lang ( 'Student' ), STUDENT );
$group [] = & HTML_QuickForm::createElement ( 'radio', 'status', null, get_lang ( 'Teacher' ), COURSEMANAGER );
if (isRoot ()) {
	$group [] = & HTML_QuickForm::createElement ( 'radio', 'status', null, get_lang ( 'PlatformAdministrator' ), PLATFORM_ADMIN );
}
$form->addGroup ( $group, null, get_lang ( 'UserRole' ), '&nbsp;' );
$defaults ['status'] = STUDENT;

//所属部门
$depts = $deptObj->get_sub_dept_ddl2 ( 0, 'array' );
$form->addElement ( 'select', 'dept_id', get_lang ( 'UserInDept' ), $depts, array ('id' => "dept_id", 'style' => 'height:22px;' ) );
if (isset ( $_GET ['keyword_deptid'] ) and is_not_blank($_GET ['keyword_deptid'])) $defaults['dept_id']=intval(getgpc ( 'keyword_deptid' ,'G'));

//失效日期
$group = array ();
$group [] = $form->createElement ( 'radio', 'radio_expiration_date', null, get_lang ( 'NeverExpires' ), 0 );
$group [] = $form->createElement ( 'radio', 'radio_expiration_date', null, get_lang ( 'ExpirationDate' ), 1 );
//$group[] = $form->createElement('datepicker','expiration_date', null, array ('form_name' => $form->getAttribute('name'), 'onChange'=>'enable_expiration_date()'));
$group [] = $form->createElement ( 'calendar_datetime', 'expiration_date', null, array (), array ('show_time' => TRUE ) );
$form->addGroup ( $group, 'max_member_group', get_lang ( 'ExpirationDate' ), '&nbsp;&nbsp;', false );

//是否激活
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'radio', 'active', null, get_lang ( 'Yes' ), 1 );
$group [] = & HTML_QuickForm::createElement ( 'radio', 'active', null, get_lang ( 'No' ), 0 );
$form->addGroup ( $group, 'active', get_lang ( 'ActiveAccount' ), '', false );

//性别
$group = array ();
$group [] = $form->createElement ( 'radio', 'sex', null, get_lang ( 'Male' ), 1 );
$group [] = $form->createElement ( 'radio', 'sex', null, get_lang ( 'Female' ), 2 );
$group [] = $form->createElement ( 'radio', 'sex', null, get_lang ( 'Secrect' ), 0 );
$form->addGroup ( $group, 'sex', get_lang ( 'Sex' ), null, false );

$form->addElement ( 'hidden', 'credential_type', '3' );
$form->addElement ( 'text', 'credential_no', get_lang ( 'IDCard' ), array ('style' => "width:230px", 'class' => 'inputText', 'id' => 'credential_no' ) );

// Phone 电话
$form->addElement ( 'text', 'phone', get_lang ( 'PhoneWithAreaCode' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
/*$form->addRule ( 'phone', get_lang ( 'ThisFieldIsRequiredNumeric' ), 'numeric' );
$form->addRule ( 'phone', get_lang ( 'ThisFieldMinLengthIs' ) . ':11', 'minlength', 10 );*/

//手机号码
$form->addElement ( 'text', 'mobile', get_lang ( 'MobilePhone' ), array ('style' => 'width:250px', 'class' => 'inputText', 'title' => get_lang ( 'MobilePhoneTip' ) ) );
//if (api_get_setting ( 'registration', 'mobile' ) == 'true')
//$form->addRule ( 'mobile', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'mobile', get_lang ( 'ThisFieldIsRequiredNumeric' ), 'numeric' );
$form->addRule ( 'mobile', get_lang ( 'ThisFieldMinLengthIs' ) . ':11', 'minlength', 11 );

// Send email 发送邮件
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'radio', 'send_mail', null, get_lang ( 'Yes' ), 1, array ('id' => 'send_mail_yes' ) );
$group [] = & HTML_QuickForm::createElement ( 'radio', 'send_mail', null, get_lang ( 'No' ), 0, array ('id' => 'send_mail_no' ) );
$form->addGroup ( $group, 'mail', get_lang ( 'SendMailToUsers' ), '&nbsp;' );
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
	if (_license_user_count () == FALSE or _check_org_user_quota () == FALSE) {
		api_redirect ( 'user_add.php?message=' . urlencode ( get_lang ( 'UserCountExcess' ) ) );
	}
	
	$user = $form->getSubmitValues ();
	$username = $user ['username'];
	$lastname = $user ['lastname'];
	$firstname = $user ['firstname'];
	$email = $user ['email']; 
	$official_code = $user ['official_code'];
	$seatnumber = $user ['seatnumber'];
   // echo $seatnumber;
	$status = intval ( $user ['status'] );
	$platform_admin = ($status == PLATFORM_ADMIN ? 1 : 0);
	$active = intval ( $user ['active'] );
	$send_mail = intval ( $user ['mail'] ['send_mail'] ); //发送邮件
	$dept_id = $user ['dept_id'];
	$dept_in_org=$deptObj->get_dept_in_org($dept_id );
	$dept_org=array_pop($dept_in_org);
	$org_id=$dept_org['id'];
	
	$phone = $user ['phone'];
	$mobile = $user ['mobile'];
	$msn = $user ['msn'];
	$qq = $user ['qq'];
	$sex = $user ['sex'];
	$zip_code = $user ['zip_code'];
	$address = $user ['address'];
	$credential_type = $user ['credential_type'];
	$credential_no = ($credential_type == '0' ? '' : $user ['credential_no']);
	$description = $user ['description'];
	
	if (count ( $extAuthSource ) > 0 && $user ['password'] ['password_auto'] == '3') {
		$auth_source = $user ['password'] ['auth_source'];
		$password = api_get_setting ( "default_password" );

	} else {
		$auth_source = PLATFORM_AUTH_SOURCE;
		if ($user ['password'] ['password_auto'] == '1') { //
			if (strlen ( $username ) >= 6) {
				$password = substr ( $username, strlen ( $username ) - 6 );
			} else {
				$password = $username;
			}
		} elseif ($user ['password'] ['password_auto'] == '2') {
			$password = api_get_setting ( "default_password" );

		} else {
			$password = $user ['password'] ['password'];
		}
	}
	$password = crypt(md5(api_get_encrypted_password ( $password )),md5($username));
	$expiration_date = ($user ['radio_expiration_date'] == '1' ? $user ['expiration_date'] : '0000-00-00 00:00:00');
	                                                        
	$user_id = UserManager::create_user ( $firstname, $lastname, $status, $teamId='',$email, $username, $password, $official_code , $seatnumber, api_get_setting ( 'platformLanguage' ), $phone, NULL, $auth_source, $expiration_date, $active, $dept_id, $description, $sex, $credential_type, $credential_no, $zip_code,
			$address, $mobile, $qq, $msn, "", $org_id );
	
	if ($platform_admin) {
		$sql = "UPDATE " . $table_user . " SET is_admin=1 WHERE user_id=" . Database::escape ( $user_id );
		api_sql_query ( $sql, __FILE__, __LINE__ );
	}
	
	if (! empty ( $email ) && $send_mail) {
            $sql="SELECT `variable`,`selected_value` FROM  `settings_current` WHERE  `category` =  'MailServer'";
                $re=  api_sql_query($sql);
                while ($row=DATABASE::fetch_row($re)){
                    $data_mail[$row[0]]=$row[1];
                } 
                $mail = new MySendMail();
                $mail->setServer($data_mail['smtp_mail_host'], $data_mail['smtp_mail_address'],$data_mail['smtp_mail_password'], $data_mail['smtp_mail_port'], true); //到服务器的SSL连接 
                $mail->setFrom($data_mail['smtp_mail_address']);
                $mail->setReceiver($email);
                $mail->setMail("新增用户成功", "[".api_get_setting ( 'siteName' )."]系统管理员于". date('Y-m-d H:i:s',time())."添加了新的平台用户，登录名为".$username."，密码为".$password."，请登陆后即使修改密码！！");
                $mail->sendMail();  
	}
	
	if (isset ( $user ['submit_plus'] )) {
		api_redirect ( 'user_add.php?message=' . urlencode ( get_lang ( 'UserAdded' ) ) );
	} else {
		tb_close ( 'user_list.php?action=show_message&message=' . urlencode ( get_lang ( 'UserAdded' ) ) );
	}
}

Display::display_header($tool_name,FALSE);

if (! empty ( $message )) {
	Display::display_normal_message ( stripslashes ( $message ), false );
}

$form->display ();

Display::display_footer ();
