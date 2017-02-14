<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include ('../inc/global.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
api_protect_admin_script ();

$table_user = Database::get_main_table ( SAI_CONTEST );
$htmlHeadXtra [] = Display::display_kindeditor ( 'description', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'rules', 'normal' );
//部门数据
//$deptObj = new DeptManager ();
//if (isset ( $_GET ['id'] )) { 
//	$one_dept_info = $deptObj->get_dept_info ( intval(getgpc ( 'id' )) );
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
<script language="JavaScript" type="text/JavaScripquestiont">
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

$form = new FormValidator ( 'massage_add' );

//$form->addElement ( 'header', 'header', $tool_name );

// sequence 显示顺序
//$form->addElement ( 'text', 'sequence', get_lang ( "显示顺序" ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) ); 
//$form->addElement('html','<tr class="containerBody"><td class="formLabel">显示顺序</td><td class="formTableTd" align="left"><input
//    style="width:20%;height:20px;" class="inputTes" , name="sequence" type="text">&nbsp;&nbsp;&nbsp;<span 
//    style="color:#999999"><i>(必须为数字)</i></span></td></tr>');
//question  问题
$form->addElement ( 'textarea', 'description', get_lang ( '说明' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );


// answer 答案
$form->addElement ( 'textarea', 'rules', get_lang ( '规则' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
//$form->addElement ( 'textarea', 'description', '描述', array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
//提交
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit', get_lang ( 'Save' ), 'class="save"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
                
                  $description =$exam_list  ['description'];
	$rules = $exam_list  ['rules'];
 
    $sql ="INSERT INTO cn_massage (`description` ,`rules`) VALUES 
        ('".$description."','".$rules."')";
     //var_dump($sql);exit();
    api_sql_query ( $sql, __FILE__, __LINE__ );

    tb_close( 'massage_list.php' );

}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>

<script type="text/javascript">
     $("#faq_add").submit(function(){
        var sai=$(".inputTes").val();
        var matchArray = sai.match(/^[1-9]\d*$/)
        if (matchArray == null) {
          alert("显示顺序必须为数字");
          return false;
        }
     });
</script>
