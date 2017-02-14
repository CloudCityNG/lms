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
$user_table = Database::get_main_table ( 'user' );
$team_table = Database::get_main_table ( 'tbl_team' );
//获取要显示的数据
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
$user_table = Database::get_main_table ( 'user' );
function get_user_data($from='', $number_of_items='', $column='', $direction='') {
        global $_GET,$user_table;
  
	$sql = "SELECT username FROM  $user_table where teamId=".$_GET['id'];
//	$sql_where = get_sqlwhere ();
//        
//	if ($sql_where) $sql .=    $sql_where;
//	$sql .= " ORDER BY col$column $direction ";
//	$sql .= " LIMIT $from,$number_of_items";  
// echo $sql;
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();
	//$objDept = new DeptManager ();
	while ( $user = Database::fetch_row ( $res ) ) {
	
		$users [] = $user;  
	}
	return $users;
}

$member_name=get_user_data();
//根据战队id查出队长 id
$sql='select teamAdmin from tbl_team where id='.$_GET['id'];
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$leaderId = Database::fetch_row ( $res );
$leaderId=$leaderId[0];
//根据队长id查出队长用户名
$sql='select username from user where user_id='.$leaderId;
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$leaderName = Database::fetch_row ( $res );
$form = new FormValidator ( 'team_edit', 'post', '', '' );
//$form->addElement ( 'header', 'header', get_lang ( 'ModifyUserInfo' ));

foreach($member_name as $k => $v){
if($v[0]!=$leaderName[0]){
    $form->addElement ( 'text', 'name', get_lang ( "队员".($k+1)), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText','value'=>$v[0] ,'readonly'=>'readonly'));
}else{
    $form->addElement ( 'text', 'name', get_lang ( "队长    "), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText','value'=>$v[0] ,'readonly'=>'readonly'));
}
}

$group = array ();
//$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', '返回', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->applyFilter ( '__ALL__', 'trim' );
Display::setTemplateBorder ( $form, '98%' );
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


