<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include ('../../inc/global.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
api_protect_admin_script ();
$table_user = Database::get_main_table ( SAI_CONTEST );
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchRule', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchDesc', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchRewad', 'normal' );
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

$interbreadcrumb [] = array ("url" => api_get_path ( WEB_ADMIN_PATH ) . '
    index.php', "name" => get_lang ( 'PlatformAdmin' ) );
$tool_name = get_lang ( 'AddUsers' );

$t=date ( "Y-m-d H:i" );
$t2=date( "Y-m-d H:i", strtotime ( "+ 30 days" ));


$form = new FormValidator ( 'sai_add' );

// matchName 赛事名称
$form->addElement ( 'text', 'matchName', get_lang ( "赛事名称" ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
 
// matchStime   赛事开启时间
$form->addElement('html','<tr class="containerBody"><td class="formLabel">赛事开启时间</td><td class="formTableTd" align="left"><div id="append_parent"></div>  <input
    style="width:21%;height:20px;" class="inputTexts" , name="matchStime" type="text" id="begin_time" readonly="readonly"  onclick="showcalendar(event,this,true,\'' .$t. '\', \''.$t2 .'\')">&nbsp;&nbsp;&nbsp;<span 
    style="color:#999999"><i>(格式如下:2014-11-11 10:10:10)</i></span></td></tr>');

// matchEtime 赛事结束时间
$form->addElement('html','<tr class="containerBody"><td class="formLabel">赛事结束时间</td><td class="formTableTd" align="left"><input
    style="width:21%;height:20px;" class="inputTex" , name="matchEtime" type="text" id="begin_time2" readonly="readonly"  onclick="showcalendar(event,this,true,\'' .$t. '\', \''.$t2 .'\')">&nbsp;&nbsp;&nbsp;<span 
    style="color:#999999"><i>(格式如下:2014-11-11 10:10:10)</i></span></td></tr>');

//matchSelt   大赛选平
$form->addElement('html','<tr class="containerBody"><td class="formLabel">大赛选平</td><td class="formTableTd" align="left"><input
    style="width:21%;height:20px;" class="inputTest" , name="matchSelt" type="text" id="begin_time3" readonly="readonly"  onclick="showcalendar(event,this,true,\'' .$t. '\', \''.$t2 .'\')">&nbsp;&nbsp;&nbsp;<span 
    style="color:#999999"><i>(格式如下:2014-11-11 10:10:10)</i></span></td></tr>');

//matchAard  大赛颁奖
$form->addElement('html','<tr class="containerBody"><td class="formLabel">大赛颁奖</td><td class="formTableTd" align="left"><input
    style="width:21%;height:20px;" class="inputTet" , name="matchAard" type="text" id="begin_time4" readonly="readonly"  onclick="showcalendar(event,this,true,\'' .$t. '\', \''.$t2 .'\')">&nbsp;&nbsp;&nbsp;<span 
    style="color:#999999"><i>(格式如下:2014-11-11 10:10:10)</i></span></td></tr>');
//matchSite  比赛场地
$form->addElement ( 'text', 'matchSite', get_lang ( '比赛场地' ), array ('style' => "width:250px", 'class' => 'inputText', 'id' => 'lastname' ) );

//matchRewad  大赛规则
$form->addElement ( 'textarea', 'matchRewad', get_lang ( '大赛规则' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );

//matchRule  大赛简介
$form->addElement ( 'textarea', 'matchRule', get_lang ( '大赛简介' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );

// matchDesc 大赛公告
$form->addElement ( 'textarea', 'matchDesc', get_lang ( '大赛公告' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );

//提交
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button','submit', get_lang ( 'Save' ), 'class="save"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
	
	$matchName =  htmlspecialchars($exam_list  ['matchName']) ;
	$matchDesc = htmlspecialchars($exam_list  ['matchDesc']);
       $data=$exam_list  ['matchStime'];
       $is_date=strtotime($data)?strtotime($data):false;
 
       if($is_date===false){
            exit();
       }else{
               $matchStime = strtotime(date('Y-m-d H:i:s',$is_date));
      }
	$data=$exam_list  ['matchEtime'];
       $is_date=strtotime($data)?strtotime($data):false;

       if($is_date===false){
            exit();
       }else{
               $matchEtime = strtotime(date('Y-m-d H:i:s',$is_date));
      }
      $data=$exam_list  ['matchSelt'];
       $is_date=strtotime($data)?strtotime($data):false;
       if($is_date===false){
            exit();
       }else{
               $matchSelt = strtotime(date('Y-m-d H:i:s',$is_date));
      }
	$data=$exam_list  ['matchAard'];
       $is_date=strtotime($data)?strtotime($data):false;
 
       if($is_date===false){
            exit();
       }else{
               $matchAard = strtotime(date('Y-m-d H:i:s',$is_date));
      }
	$matchSite = htmlspecialchars($exam_list  ['matchSite']);
                 $matchRule = htmlspecialchars($exam_list  ['matchRule']);
	$matchRewad = htmlspecialchars($exam_list  ['matchRewad']);

    $sql ="INSERT INTO tbl_contest (`matchName` ,`matchDesc`,`matchStime`,`matchEtime` ,`matchSelt`,`matchAard`,`matchSite`,`matchRule`,`matchRewad`) VALUES 
        ('".$matchName."','".$matchDesc."','".$matchStime."','".$matchEtime."','".$matchSelt."','".$matchAard."','".$matchSite."','".$matchRule."','".$matchRewad."')";
     //var_dump($sql);exit();
    api_sql_query ( $sql, __FILE__, __LINE__ );

    tb_close( 'sai_list.php' );

}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>
<script src="<?=api_get_path( WEB_JS_PATH );?>js_calendar.js" type="text/javascript"></script>
<script type="text/javascript">
     $("#sai_add").submit(function(){
        var sai=$(".inputTexts").val();
        var matchArray = sai.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/);
        if (matchArray == null) {
          alert("赛事开启时间的格式错误 " + sai);
          return false;
        }
        var saishi=$(".inputTex").val();
         var matchArray = saishi.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/);
         if (matchArray == null) {
           alert("赛事结束时间的格式错误: " + saishi);
           return false;
         }
          var aa=$(".inputTest").val();
        var matchArray = aa.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/);
        if (matchArray == null) {
          alert("大赛评选格式错误: " + aa);
          return false;
        }
        var aa=$(".inputTet").val();
          var matchArray = aa.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/);
        if (matchArray == null) {
          alert("大赛评选格式错误: " + aa);
          return false;
        }
        return true;
       
     });
</script>