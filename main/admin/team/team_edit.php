<?php

//$language_file = array ('admin', 'registration' );
$cidReset = true;
include ('../../inc/global.inc.php');
$this_section = SECTION_PLATFORM_ADMIN;
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
//require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
//include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
//require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
//require_once (api_get_path ( INCLUDE_PATH ) . 'sendmail/SMTP.php');
$talbe_team = Database::get_main_table ( 'tbl_team');
$htmlHeadXtra [] = '<script language="JavaScript" type="text/JavaScript">
function enable_expiration_date() { //v2.0
	document.user_add.radio_expiration_date[0].checked=false;
	document.user_add.radio_expiration_date[1].checked=true;
}

function password_switch_radio_button(form, input){
	var NodeList = document.getElementsByTagName("input");
	for(var i=0; i< NodeList.length; i++){
		if(NodeList.item(i).name=="reset_password" && NodeList.item(i).value=="2"){
			NodeList.item(i).checked=true;
		}
	}
}

function showadv() {
		if(document.user_add.advshow.checked == true) {
			document.getElementById("adv").style.display = "";
		} else {
			document.getElementById("adv").style.display = "none";
		}
}


function change_credeential_state(v){
		if(v!="0") {
			G("credential_no").disabled=false;
			G("credential_no").className="inputText";
			G("credential_no").style.display = "";
			/*document.getElementById("credential_no").style.width="250px";*/
		}
		else {
			G("credential_no").value="";
			G("credential_no").style.display = "none";
			G("credential_no").disabled=true;
		}
}
</script>';

$get_team_id= intval( getgpc('id'));
$team_id = isset ( $get_team_id ) ? intval ( $get_team_id ) : intval ( $_POST ['id'] );
$tool_name = get_lang ( '编辑战队信息' );

//$sql = "SELECT user_id FROM " . $talbe_team . " WHERE is_admin=1 AND " . Database::create_in ( $_configuration ['default_administrator_name'], 'username' );
//$root_user_id = Database::get_into_array ( $sql, __FILE__, __LINE__ );
//$interbreadcrumb [] = array ("url" => api_get_path ( WEB_ADMIN_PATH ) . 'index.php', "name" => get_lang ( 'PlatformAdmin' ) );
//$interbreadcrumb [] = array ('url' => "team_list.php", "name" => get_lang ( 'UserList' ) );
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';

$sql = "SELECT u.id,u.teamName,u.description FROM $talbe_team u  WHERE u.id = '" . $team_id . "'";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
if (Database::num_rows ( $res ) != 1) {
	$redirect_url = "team_list.php";
	tb_close ( $redirect_url );
}
$team_data = Database::fetch_array ( $res, 'ASSOC' );
$team_data ['platform_admin'] = $team_data ['is_admin'];
$team_data ['seatnumber'] = $team_data ['seatnumber'];
$team_data ['send_mail'] = 0;
$team_data ['old_password'] = $team_data ['password'];



$form = new FormValidator ( 'team_edit', 'post', '', '' );

//$form->addElement ( 'header', 'header', get_lang ( 'ModifyUserInfo' ));
$form->addElement ( 'hidden', 'id', $team_id );
$form->addElement ( 'text', 'teamName', get_lang ( '战队名称' ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText','value'=>$team_data['teamName']) );


$form->addRule ( 'teamName', get_lang ( '战队名称不能为空' ), 'required' );

$form->addRule ( 'teamName', '战队名称最大长度20个字符', 'maxlength', 20 );

$textarea="<tr class='containerBody'>
    <td class='formLabel'>战队描述</td>
    <td class='formTableTd' align='left'> 
    <textarea name='description' style='width:250px;height:300px;' class='inputText'>".$team_data['description']."</textarea></td></tr >";
$form->addElement ( 'html', $textarea);

//$form->addElement ( 'textarea', 'description', get_lang ( '战队描述' ), array ('style' => "width:250px;height:350px;padding-top:5px;" ,'class' => 'inputText'),$team_data['teamName'] );
//$form->addRule ( 'username', get_lang ( 'UserTaken' ), 'username_available', $team_data ['username'] );
//$form->addElement ( 'text', 'teamName', get_lang ( '战队名称' ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText','value'=>$team_data['teamName']) );
$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->applyFilter ( '__ALL__', 'trim' );

// Set default values


////liyu:更新成新日历控件后
//$team_data ['expiration_date'] = $expiration_date;
//
//$team_data ['reset_password'] = '0';
//$team_data ['credential'] ['credential_type'] = $team_data ['credential_type'];
//$team_data ['credential'] ['credential_no'] = $team_data ['credential_no'];

//$form->setDefaults ( $team_data );
Display::setTemplateBorder ( $form, '98%' );

if ($form->validate ()) {
	//$user = $form->exportValues();
	$teamInfo = $form->getSubmitValues ();
	//var_dump($user);exit;
	if (! can_do_my_bo ( $team_data ['creator_id'] )) {
		Display::display_msgbox ( '对不起,你没有操作的权限!', 'main/admin/user/team_list.php', 'warning' );
	}
        
        $teamId=$teamInfo['id'];
        unset($teamInfo['id']);
        unset($teamInfo['submit']);
        foreach ($teamInfo as $k => $v){
            $set.="$k = '$v',";
        }
        $set=substr($set,0,-1);
        $sql = "UPDATE $talbe_team "."SET $set where id=$teamId";
//echo $sql;
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
//	if (isRoot ()) { //只有root可以修改这个属性
//		if (isRoot ( $username )) $platform_admin = 1;
//		if ($platform_admin == 1) {
//			$sql = "UPDATE " . $talbe_team . " SET is_admin=1 WHERE user_id=" . Database::escape ( $team_id );
//			api_sql_query ( $sql, __FILE__, __LINE__ );
//		} else {
//			$sql = "UPDATE " . $talbe_team . " SET is_admin=0,status='" . $status . "' WHERE user_id=" . Database::escape ( $team_id );
//			api_sql_query ( $sql, __FILE__, __LINE__ );
//		}
//	}
//	
//	//部门变更之后选修课程的审核
//	if ($user ["old_dept_id"] != $dept_id) {
//		$table_course = Database::get_main_table ( TABLE_MAIN_COURSE );
//		$table_course_subscribe_requisition = Database::get_main_table ( TABLE_MAIN_COURSE_SUBSCRIBE_REQUISITION );
//		
//		//该用户的所有没有通过的课程选修申请,需要变更审批的部门经理
//		$sql = "SELECT t1.course_code FROM " . $table_course_subscribe_requisition . " AS t1 LEFT JOIN " . $table_course . " AS t2 ON t1.course_code=t2.code WHERE t1.user_id='" . escape ( $team_id ) . "' AND t2.is_audit_enabled=2";
//		$course_code = Database::get_into_array ( $sql );
//		if ($course_code && is_array ( $course_code )) {
//			$table_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
//			$sql = "SELECT dept_admin FROM " . $table_dept . " WHERE dept_id='" . escape ( $dept_id ) . "'";
//			$dept_admin = Database::get_scalar_value ( $sql );
//			$sql = "UPDATE " . $table_course_subscribe_requisition . " SET audit_user='" . $dept_admin . "' WHERE user_id='" . escape ( $team_id ) . "' AND " . Database::create_in ( $course_code, "course_code" );
//			$res = api_sql_query ( $sql, __FILE__, __LINE__ );
//		}
//	}
//	
//	if ($user ["old_org_id"] != $org_id) {
//		$table_course = Database::get_main_table ( TABLE_MAIN_COURSE );
//		$table_course_subscribe_requisition = Database::get_main_table ( TABLE_MAIN_COURSE_SUBSCRIBE_REQUISITION );
//		
//		//该用户的所有没有通过的课程选修申请,需要变更审批的培训管理员
//		$sql = "SELECT t1.course_code FROM " . $table_course_subscribe_requisition . " AS t1 LEFT JOIN " . $table_course . " AS t2 ON t1.course_code=t2.code WHERE t1.user_id='" . escape ( $team_id ) . "' AND t2.is_audit_enabled=3";
//		$course_code = Database::get_into_array ( $sql );
//		if ($course_code && is_array ( $course_code )) {
//			$table_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
//			$sql = "SELECT dept_admin FROM " . $table_dept . " WHERE dept_id='" . escape ( $dept_id ) . "'";
//			$dept_admin = Database::get_scalar_value ( $sql );
//			$sql = "UPDATE " . $table_course_subscribe_requisition . " SET audit_user='" . $dept_admin . "' WHERE user_id='" . escape ( $team_id ) . "' AND " . Database::create_in ( $course_code, "course_code" );
//			$res = api_sql_query ( $sql, __FILE__, __LINE__ );
//		}
//	}
	
//	api_logging ( get_lang ( 'EditUser' ) . $username, 'USER', 'EditUser' );
//	if (! empty ( $email ) && $send_mail) {
//		$sql="SELECT `variable`,`selected_value` FROM  `settings_current` WHERE  `category` =  'MailServer'";
//                $re=  api_sql_query($sql);
//                while ($row=DATABASE::fetch_row($re)){
//                    $data_mail[$row[0]]=$row[1];
//                } 
//                $mail = new MySendMail();
//                $mail->setServer($data_mail['smtp_mail_host'], $data_mail['smtp_mail_address'],$data_mail['smtp_mail_password'], $data_mail['smtp_mail_port'], true); //到服务器的SSL连接 
//                $mail->setFrom($data_mail['smtp_mail_address']);
//                $mail->setReceiver($email);
//                $mail->setMail("修改用户信息", "[".api_get_setting ( 'siteName' )."]系统管理员于". date('Y-m-d H:i:s',time())."修改了您的个人信息，登录名为".$username."，密码为".$password."，建议您登陆后即使修改密码！！");
//                $mail->sendMail();  
//	}
	
	$redirect_url = 'team_list.php';
        tb_close($redirect_url);
	Display::display_msgbox ( get_lang ( '修改成功' ), $redirect_url );
}

Display::display_header ( $tool_name, FALSE );

//if ($_configuration ['enable_user_ext_info']) {
//	$html = '<div id="demo" class="yui-navset">';
//	$html .= '<ul class="yui-nav">';
//	$html .= '<li class="selected"><a href="team_edit.php?user_id='.$team_id.'"><em>基本信息</em></a></li>';
//	$html .= '<li><a href="team_edit_ext.php?user_id=' . $team_id . '"><em>扩展信息</em></a></li>';
//	$html .= '</ul>';
//	$html .= '<div class="yui-content"><div id="tab1">';
////	echo $html;
//}
//$image = $team_data ['picture_uri'];
//if (strlen ( $image ) > 0 && file_exists ( api_get_path ( SYS_PATH ) . "storage/users_picture/{$image}" )) {
//	$picture_url = api_get_path ( WEB_PATH ) . 'storage/users_picture/' . $team_data ['picture_uri'];
//} else {
//	$picture_url = api_get_path ( WEB_IMG_PATH ) . "unknown.jpg";
//}
//$img_attributes = 'src="' . $picture_url . '?rand=' . time () . '" ' . 'alt="' . $team_data ['lastname'] . ' ' . $team_data ['firstname'] . '" ' . 'style="float:right; padding:5px;" ';
//$image_size = getimagesize ( $picture_url );
//if ($image_size [0] > 150) $img_attributes .= 'width="150" ';


echo '<table width="100%" border=0><tr>';
echo '<td align=left valign=top><img ' . $img_attributes . '/></td>';
echo '<td width=90% valign=top>';
$form->display ();
echo '</td></tr></table>';
echo '<br>';
if ($_configuration ['enable_user_ext_info']) echo '</div></div></div>';
Display::display_footer ();

