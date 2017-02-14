<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include ('../../inc/global.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
api_protect_admin_script ();

$table_user = Database::get_main_table ( SAI_CONTEST );
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchRule', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'matovchDesc', 'normal' );
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

$form = new FormValidator ( 'sai_add' );

//$form->addElement ( 'header', 'header', $tool_name );

// matchName 赛事名称
$form->addElement ( 'text', 'matchName', get_lang ( "赛事名称" ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
 
		
// matchStime   赛事开启时间
$form->addElement ( 'text', 'matchStime', get_lang ( '赛事开启时间' ), array ('style' => "width:250px", 'class' => 'inputText' ) );
  
// matchEtime 赛事开启时间
$form->addElement ( 'text', 'matchEtime', get_lang ( '赛事结束时间' ), array ('style' => "width:250px", 'class' => 'inputText' ) );

//matchSelt   大赛选平
$form->addElement ( 'text', 'matchSelt', get_lang ( '大赛选平' ), array ('style' => "width:250px", 'class' => 'inputText' ) );

//matchAard  大赛颁奖
$form->addElement ( 'text', 'matchAard', get_lang ( '大赛颁奖' ), array ('style' => "width:230px", 'class' => 'inputText', 'id' => 'lastname' ) );

//matchSite  比赛场地
$form->addElement ( 'text', 'matchSite', get_lang ( '比赛场地' ), array ('style' => "width:230px", 'class' => 'inputText', 'id' => 'lastname' ) );


//matchRewad  比赛奖励
$form->addElement ( 'text', 'matchRewad', get_lang ( '比赛奖励' ), array ('style' => "width:230px", 'class' => 'inputText', 'id' => 'lastname' ) );

//matchRule  比赛规程
$form->addElement ( 'textarea', 'matchRule', get_lang ( '比赛规程' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );


// matchDesc 赛事描述
$form->addElement ( 'textarea', 'matchDesc', get_lang ( '赛事描述' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
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
	
	$matchName = $exam_list  ['matchName'];
	$matchDesc = $exam_list  ['matchDesc'];
       $data=$exam_list  ['matchStime'];//这里可以任意格式，因为strtotime函数很强大
       $is_date=strtotime($data)?strtotime($data):false;
 
       if($is_date===false){
            exit('赛事开始时间日期格式非法');
       }else{
               //echo date('Y-m-d',$is_date);
               $matchStime = strtotime(date('Y-m-d',$is_date));
      }
	$data=$exam_list  ['matchEtime'];//这里可以任意格式，因为strtotime函数很强大
       $is_date=strtotime($data)?strtotime($data):false;
 
       if($is_date===false){
            exit('赛事结束时间日期格式非法');
       }else{
               $matchEtime = strtotime(date('Y-m-d',$is_date));
      }
	
	$matchSelt = $exam_list  ['matchSelt'];
	$matchAard = $exam_list  ['matchAard'];
	$matchSite = $exam_list  ['matchSite'];
	$matchRule = $exam_list  ['matchRule'];
	$matchRewad = $exam_list  ['matchRewad'];


    $sql ="INSERT INTO tbl_contest (`matchName` ,`matchDesc`,`matchStime`,`matchEtime` ,`matchSelt`,`matchAard`,`matchSite`,`matchRule`,`matchRewad`) VALUES 
        ('".$matchName."','".$matchDesc."','".$matchStime."','".$matchEtime."','".$matchSelt."','".$matchAard."','".$matchSite."','".$matchRule."','".$matchRewad."')";
//     /var_dump($sql);exit();
    api_sql_query ( $sql, __FILE__, __LINE__ );

    tb_close( 'sai_list.php' );

}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>
<script src="<?=api_get_path ( WEB_JS_PATH )?>js_calendar.js"></script>
