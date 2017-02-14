<?php

/* ==============================================================================
 用户导入
 ==============================================================================*/

function doAction() {
	if ($_FILES ['import_file'] ['size'] !== 0) {
		$save_path = $_FILES ['import_file'] ['tmp_name'];
		set_time_limit ( 0 );
		$file_type = getFileExt ( $_FILES ['import_file'] ['name'] );
		$file_type = strtolower ( $file_type );
		if ($file_type == 'xls') {
			$users = parse_upload_data ( $save_path );
		} else {
			api_redirect ( 'user_import.php?message=' . urlencode ( get_lang ( 'FileImported' ) ) );
		}
		
		my_delete ( $_FILES ['import_file'] ['tmp_name'] );
		
		$errors = validate_data ( $users );

		$users = complete_missing_data ( $users, getgpc ( '      /.,lkmjnbgvcfdsxzaimport_dept' ) );
		save_data ( $users );
		
		return $errors;
	}
}

function parse_upload_data($file) {
	$data = Import::parse_to_array ( $file, 'xls' );
	$users = $data ['data'];
	$import_encoding = getgpc ( 'import_encoding', 'P' );
	if (is_array ( $users ) && count ( $users ) > 0) {
		foreach ( $users as $index => $user ) {
			 $user ['active'] = _convert_charset ( $user ['是否启用'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['FirstName'] = _convert_charset ( $user ['姓名'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['UserName'] = _convert_charset ( $user ['登录名'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['lastName'] = _convert_charset ( $user ['职务'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['Password'] = _convert_charset ( $user ['密码'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['Email'] = _convert_charset ( $user ['邮箱'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['DeptNo'] = _convert_charset ( $user ['部门'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['official_code'] = _convert_charset ( $user ['工号，学号，编号'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['seatnumber'] = _convert_charset ( $user ['座位号'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['Phone'] = _convert_charset ( $user ['固话'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['Mobile'] = _convert_charset ( $user ['手机'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['Sex'] = _convert_charset ( $user ['性别'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['credential_no '] = _convert_charset ( $user ['证件号码'], SYSTEM_CHARSET, $import_encoding );
                                                       $user ['teamId '] = _convert_charset ( $user ['战队ID'], SYSTEM_CHARSET, $import_encoding );
                                                      if($user ['用户Id']) {
                                                      $user ['user_id'] = _convert_charset($user ['用户Id'], SYSTEM_CHARSET, $import_encoding);
                                                      }else{
                                                       $user ['user_id'] = null;
                                                       }
                                                       $users [$index] = $user;
                                                      }
		return $users;
	}
	return array ();
}

function _convert_charset($str, $to_encoding = SYSTEM_CHARSET, $from_encoding = NULL) {
	return mb_convert_encoding ( $str, SYSTEM_CHARSET, $from_encoding );
}

/**
 * 验证导入的用户数据
 * @param unknown_type $users
 */
function validate_data($users) {
	global $defined_auth_sources;
	global $_configuration;
	$errors = array ();
	$usernames = array ();
	$emails = array ();
	foreach ( $users as $index => $user ) {
		$tmp_cur_user = $user ['UserName'];
		if (! empty ( $tmp_cur_user ) and ! is_root ( $tmp_cur_user )) {
			$user ['Status'] = STUDENT;
			
			//1. 检查必填字段
			$mandatory_fields = array ('UserName' ); //某些不能为空的字段
			if (api_get_setting ( 'registration', 'email' ) == 'true') $mandatory_fields [] = 'Email';
			foreach ( $mandatory_fields as $key => $field ) {
				if (empty ( $user [$field] ) || strlen ( $user [$field] ) == 0) { //UserName值
					$user ['error'] = get_lang ( $field . 'Mandatory' );
					$errors [$index] = $user;
				}
			}
			
			//2. 检查登录名
			if (! empty ( $tmp_cur_user ) && strlen ( $tmp_cur_user ) != 0) {
				if (strlen ( $tmp_cur_user ) > 40) {
					$user ['error'] = get_lang ( 'UserNameTooLong' );
					$errors [$index] = $user;
				}
			}
			
			//5. Check authentication source
			if ($user ['AuthSource'] && strlen ( $user ['AuthSource'] ) != 0) {
				if (! in_array ( $user ['AuthSource'], $defined_auth_sources )) {
					$user ['error'] = get_lang ( 'AuthSourceNotAvailable' );
					$errors [$index] = $user;
				}
			}
			
			//6. check email
			if (! empty ( $user ['Email'] ) && strlen ( $user ['Email'] ) != 0) {
				$emails [$user ['Email']] = 1;
				
				if (! is_email ( trim ( $user ['Email'] ) )) {
					$user ['error'] = '邮箱地址格式错误';
					$errors [$index] = $user;
				}
		
			}
		}
	}
	return $errors;
}

function complete_missing_data($users, $import_dept = 0) {
	foreach ( $users as $index => $user ) {
		$login_name = trim ( $user ['UserName'] );
		if (! isset ( $login_name ) || strlen ( $login_name ) == 0) {
			$users [$index] ['UserName'] = $username;
		}
		//2. generate a password if necessary
		if (empty ( $user ['Password'] ) || strlen ( $user ['Password'] ) == 0) {
			$users [$index] ['Password'] = api_generate_password ();
		}
		
		$users [$index] ['Status'] = STUDENT;
		
		//4. set authsource if not allready set
		if (! isset ( $user ['AuthSource'] ) || strlen ( $user ['AuthSource'] ) == 0) {
			$users [$index] ['AuthSource'] = PLATFORM_AUTH_SOURCE;
		}

		if (empty ( $import_dept )) { //使用文件中部门
			if (is_not_blank ( $user ['DeptNo'] )) {
				$tbl_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
				$sql = "SELECT id FROM " . $tbl_dept . " WHERE dept_no=" . Database::escape ( trim ( $user ['DeptNo'] ) );
				$users [$index] ['DeptID'] = Database::get_scalar_value ( $sql );
			} else {
				$users [$index] ['DeptID'] = 0;
			}
		} else { // 导入到某部门中去
			$users [$index] ['DeptID'] = $import_dept;
		}
	}
	return $users;
}

function save_data($users) {
	global $_configuration, $restrict_org_id;
	$user_table = Database::get_main_table ( TABLE_MAIN_USER );
	$platformLanguage = api_get_setting ( 'platformLanguage' );
	$sendMail = getgpc ( 'sendMail', 'P' ) ? 1 : 0;
	$ip = get_onlineip ();
	foreach ( $users as $index => $user ) {
		$username = trim ( $user ["UserName"] );
                if($username!="root"){ 
                $pass=crypt(md5($user ["Password"]),md5($username));
		if (in_array ( $username, $_configuration ['default_administrator_name'] )) continue;
		if (UserManager::is_username_available ( $username )) { //不存在时
            //27个
            if(!$user ["战队ID"]){
                $user ["战队ID"] = 0;
            }
			$user_id = UserManager::create_user (
                                                 $user ["姓名"],$user ["职务"],5,$user ["战队ID"],$user ["邮箱"],
                                                 $username,$pass, $user ["工号，学号，编号"],$user ["seatnumber"], $platformLanguage,$user ["固话"],
                                                 $user ['Picture'],PLATFORM_AUTH_SOURCE, '9999-12-31 23:59:59',$user ["是否启用"],$user ["DeptID"],
                                                 '', $user ["性别"], 1, $user ["证件号码"],$user ['ZipCode'],
                                                 $user ['Address'], $user ["手机"], $user ['QQ'], $user ['MSN'],'',
                                                 $restrict_org_id, $ip
                                                );
                        $sql_row = array ( 
					       'phone' => $user ['Phone'],
					       'mobile' => $user ['Mobile'] ,
					       'credential_no' => $user ['credential_no '] ,
                        );
			$sql = Database::sql_update ( $user_table, $sql_row, "username=" . Database::escape ( $username ) );
			api_sql_query ( $sql, __FILE__, __LINE__ );

			$log_msg = get_lang ( 'ImportUser' ) . "id=" . $user_id;
			api_logging ( $log_msg, 'User' );
		}
		
		if ($sendMail) {
			$emailTo = trim ( $user ['Email'] );
			$emailSubject = get_lang ( 'YourReg' );
			$emailBody = get_lang ( 'Dear' ) . ' ' . $user ['FirstName'] . ": <br/>" . get_lang ( 'YouAreReg' ) . ' "' . get_setting ( 'siteName' ) . '". <br/>' . get_lang ( 'Settings' ) . "<br/>";
			$emailBody .= get_lang ( 'TheU' ) . ' : ' . $user ['UserName'] . "<br/>" . get_lang ( 'siteAddr' ) . ' : ' . api_get_path ( WEB_PATH ) . " <br/><br/>";
			
			email_body_txt_add ( $emailBody );
			api_email_wrapper ( $emailTo, $emailSubject, $emailBody );
		}
            }
	}
}

$language_file = array ('admin', 'registration' );
include ('../../inc/global.inc.php');
api_protect_admin_script ();
require_once (api_get_path ( LIBRARY_PATH ) . 'import.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'mail.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

$defined_auth_sources [] = PLATFORM_AUTH_SOURCE;
if (is_array ( $extAuthSource )) $defined_auth_sources = array_merge ( $defined_auth_sources, array_keys ( $extAuthSource ) );
$form = new FormValidator ( 'user_import' );

//选择文件
$form->addElement ( 'file', 'import_file', get_lang ( 'ImportFileLocation' ), array ('style' => "width:60%", 'class' => 'inputText' ) );
$form->addRule ( 'import_file', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$allowed_file_types = array ('csv', 'xls' );
$form->addRule ( 'import_file', get_lang ( 'InvalidExtension' ) . ' (' . implode ( ',', $allowed_file_types ) . ')', 'filetype', $allowed_file_types );

$objDept = new DeptManager ();
$depts = $objDept->get_all_dept_tree ( FALSE );
foreach ( $depts as $dept ) {
	$dept_options [$dept ['id']] = str_repeat ( '&nbsp;&nbsp;', $dept ['level'] ) . $dept ['dept_name'];
}
$dept_options = array_insert_first ( $dept_options, array ('0' => '---' . get_lang ( 'UserDeptCodeInXls' ) . '---' ) );

//是否发送邮件
$group = array ();
$group [] = $form->createElement ( 'radio', 'sendMail', null, get_lang ( 'Yes' ), 1 );
$group [] = $form->createElement ( 'radio', 'sendMail', null, get_lang ( 'No' ), 0 );

$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$defaults ['sendMail'] = '0';
$form->setDefaults ( $defaults );
Display::setTemplateBorder ( $form, '98%' );
$form->add_progress_bar(1);
if ($form->validate ()) {
    $errors = doAction ($import_dept);
    tb_close ( 'user_list.php' );
}
$tool_name = get_lang ( 'ImportUserListXMLCSV' );
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( $tool_name, FALSE );

$user = array ();
$users = array ();
if (count ( $errors ) > 0) {
	$error_message = '<ul>';
	$error_message .= '<li>' . get_lang ( 'ErrorsWhenImportingFile' ) . ' :</li>';
	foreach ( $errors as $index => $error_user ) {
		$error_message .= '<li>' . sprintf ( get_lang ( "LineN" ), $index ) . ":&nbsp;" . $error_user ['登录名'] . ' -- ' . $error_user ['姓名'] . ': ';
		$error_message .= $error_user ['error'];
		$error_message .= '</li>';
	}
	$error_message .= '</ul>';
	Display::display_error_message ( $error_message, false );
}

//提示帮助
echo '<div id="myOnPageContent" style="display:none"><p>' . get_lang ( 'UserImportNotes' ) . '</p></div>';
echo '<div style="float:left"><a href="' . api_get_path ( WEB_PATH ) . 'storage/examples/import_files/tpl_import_users.xls">Excel导入模板</a></div>';
echo '</div><div style="clear:both"></div>';
$form->display ();

Display::display_footer ();
