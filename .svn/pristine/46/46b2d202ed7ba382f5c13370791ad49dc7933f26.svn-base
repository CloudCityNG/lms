<?php

//$language_file = array ('admin', 'registration' );
$cidReset = true;
include ('../../inc/global.inc.php');
$this_section = SECTION_PLATFORM_ADMIN;
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();

include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');

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
$team_data['teamName']=htmlspecialchars_decode($team_data['teamName']);
$team_data['description']=htmlspecialchars_decode($team_data['description']);
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

Display::setTemplateBorder ( $form, '98%' );

if ($form->validate ()) {
	$teamInfo = $form->getSubmitValues ();
	if (! can_do_my_bo ( $team_data ['creator_id'] )) {
		Display::display_msgbox ( '对不起,你没有操作的权限!', 'main/admin/user/team_list.php', 'warning' );
	}
        $teamInfo['teamName'] = htmlspecialchars($teamInfo['teamName']);
        $teamInfo['description'] = htmlspecialchars($teamInfo['description']);
        $teamId=$teamInfo['id'];
        unset($teamInfo['id']);
        unset($teamInfo['submit']);
        foreach ($teamInfo as $k => $v){
            $set.="$k = '$v',";
        }
        $set=substr($set,0,-1);
        $sql = "UPDATE $talbe_team "."SET $set where id=$teamId";

	$res = api_sql_query ( $sql, __FILE__, __LINE__ );

	
	$redirect_url = 'team_list.php';
        tb_close($redirect_url);
	Display::display_msgbox ( get_lang ( '修改成功' ), $redirect_url );
}

Display::display_header ( $tool_name, FALSE );



echo '<table width="100%" border=0><tr>';
echo '<td align=left valign=top><img ' . $img_attributes . '/></td>';
echo '<td width=90% valign=top>';
$form->display ();
echo '</td></tr></table>';
echo '<br>';
if ($_configuration ['enable_user_ext_info']) echo '</div></div></div>';
Display::display_footer ();

