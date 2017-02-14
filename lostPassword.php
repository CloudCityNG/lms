<?php
/**
 * 忘记密码的处理
 */

$language_file = "registration";
include_once ('main/inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'formvalidator/FormValidator.class.php');
//require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php'); 
require_once (api_get_path ( INCLUDE_PATH ) . 'sendmail/SMTP.php');
/**
 * 末加密的密码处理,直接发送密码
 * @param unknown_type $user
 */
function send_password_to_user($user) {
	global $_configuration;
	
	$emailSubject = get_lang ( 'LostPassword' );
	$emailTo = trim ( $user ["email"] );
	/*	$emailToName = $user ["firstName"];
	$emailFrom = get_setting ( 'emailAdministrator' );
	$emailFromName = addslashes ( get_setting ( 'administratorSurname' ) . " " . get_setting ( 'administratorName' ) );*/
	if ($_configuration ['crypted_method'] == "none") {
		$password = $user ['password'];
	} else {
		$password = api_decrypt ( $user ['password'] );
	}
	$userAccountInfoStr = get_lang ( "FirstName" ) . ':' . $user ["firstName"] . " " . $user ["lastName"] . "<br/>" . get_lang ( 'UserName' ) . " : " . $user ["loginName"] . "<br/>" . get_lang ( 'Password' ) . ' : ' . $password . "<br/>";
	$emailBody = get_lang ( "YourAccountParam" ) . ":<br/>" . $userAccountInfoStr;
	email_body_txt_add ( $emailBody );
	$send_mail_result = api_email_wrapper ( $emailTo, $emailSubject, $emailBody );
	//$send_mail_result = api_mail_html ( '', $emailTo, $emailSubject, $emailBody );
	if ($send_mail_result) {
		Display::display_msgbox ( get_lang ( 'YourPasswordHasBeenEmailed' ), api_get_path ( WEB_PATH ) );
	} else {
		$message = get_lang ( 'SystemUnableToSendEmailContact' ) . Display::encrypted_mailto_link ( get_setting ( 'emailAdministrator' ), get_lang ( 'PlatformAdmin' ) ) . ".</p>";
		Display::display_error_message ( $message, false );
	}
}

Display::display_reduced_header ( null );

if (get_setting ( 'allow_lostpassword' ) == "false") api_not_allowed ();

$tbl_user = Database::get_main_table ( TABLE_MAIN_USER );

$form = new FormValidator ( 'lost_password' );
$form->addElement ( 'header', 'header', get_lang ( 'LostPassword' ) );

$form->addElement ( 'text', 'username', get_lang ( 'UserName' ), array ('style' => "width:60%", 'class' => 'inputText' ) );
$form->addRule ( 'username', get_lang ( 'ThisFieldIsRequired' ), 'required' );

$form->add_textfield ( 'email', get_lang ( 'Email' ), false, 'size="40" style="width:60%" class="inputText"' );
$form->applyFilter ( 'email', 'strtolower' );
$form->addRule ( 'email', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$form->addRule ( 'email', get_lang ( 'EmailWrong' ), 'email' );

$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

Display::setTemplateBorder ( $form, '98%' );

if ($form->validate ()) {
	$values = $form->exportValues ();
	$email = trim ( $values ['email'] );
	$username = trim ( $values ['username'] );
	$sql = "SELECT t1.user_id AS uid, t1.firstname AS firstName,
			t1.username AS loginName, t1.password, t1.email FROM " . $tbl_user . " t1 WHERE LOWER(t1.email) = '" . escape ( $email ) . "' AND t1.username='" . escape ( $username ) . "'";
	$result = api_sql_query ( $sql, __FILE__, __LINE__ );
        $user_info= Database::fetch_array ( $result, "ASSOC" );
	if ($user_info['password'] && Database::num_rows ( $result ) > 0) { 
//		send_password_to_user ( $user );
            
                $sql="SELECT `variable`,`selected_value` FROM  `settings_current` WHERE  `category` =  'MailServer'";
                $re=  api_sql_query($sql);
                while ($row=DATABASE::fetch_row($re)){
                    $data_mail[$row[0]]=$row[1];
                } 
                 $mail = new MySendMail();
                 $mail->setServer($data_mail['smtp_mail_host'], $data_mail['smtp_mail_address'],$data_mail['smtp_mail_password'], $data_mail['smtp_mail_port'], true); //到服务器的SSL连接 
                 $mail->setFrom($data_mail['smtp_mail_address']);
                 $mail->setReceiver($email);
                 $mail->setMail("找回密码成功", "尊敬的".$user_info['loginName']."用户，您好，您目前正在使用的密码为：".$user_info['password']." <br/>请您妥善保管，建议您登陆系统后设置新的密码！！");
                 $mail->sendMail(); 
                            
            $message_success= get_lang ( '您好，您的密码已经发送到您的邮箱，请注意查收！' );
            Display::display_normal_message ($message_success );  
         
	} else {
		Display::display_error_message ( get_lang ( '_no_user_account_with_this_email_address' ) );
	}
} else {
	Display::display_normal_message ( get_lang ( '_enter_email_and_well_send_you_password' ) );
	$form->display ();
}

Display::display_footer ();
