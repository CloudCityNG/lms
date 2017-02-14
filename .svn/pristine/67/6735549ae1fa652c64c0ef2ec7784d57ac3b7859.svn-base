<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include ('../inc/global.inc.php');
//require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'smsmanager.lib.php');
//require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
//require_once (api_get_path ( INCLUDE_PATH ) . 'sendmail/SMTP.php');
api_protect_admin_script ();

//$table_user = Database::get_main_table ( TABLE_MAIN_USER );
//$tbl_category = Database::get_main_table ( TABLE_CATEGORY );
//$table_position = Database::get_main_table ( TABLE_MAIN_SYS_POSITION );


$exam_id=$_REQUEST['id'];
$tool_name = get_lang ( '编辑战队信息' );

//？？
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

$htmlHeadXtra [] = '
<script type="text/javascript" src="../../themes/js/kindeditor/kindeditor.js"></script>
<script type="text/javascript">
                        KE.show({
				id : "description",
				afterCreate : function(id) {
					KE.util.focus(id);
				}
			});
                        KE.show({
				id : "description1",
				afterCreate : function(id) {
					KE.util.focus(id);
				}
			});</script>



</script>
 
';

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


//$get_exam_id= intval( getgpc('id'));//获取考题id
//$exam_id = isset ( $get_exam_id ) ? intval ( $get_exam_id ) : intval ( $_POST ['id'] );
//根据id查询,为默认值做准备
$talbe_exam = Database::get_main_table ('tbl_exam');
$sql = "SELECT exam_Name,examBranch,examDesc,isKey,examKey,uploadText,isReport,classId FROM $talbe_exam WHERE id = '" . $exam_id . "'";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$row=Database::fetch_row ( $res );
$interbreadcrumb [] = array ("url" => api_get_path ( WEB_ADMIN_PATH ) . 'index.php', "name" => get_lang ( 'PlatformAdmin' ) );
$tool_name = get_lang ( '编辑考题' );
$form = new FormValidator ( 'user_add' );

function hasCildren($id){//判断分类是否有子分类
    $c_table = Database::get_main_table ( 'tbl_class' );
    $sql = "select count(*) from $c_table where fid= $id"; 
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );//查询子类 
    if($res){
        $rows=Database::fetch_row ($res);
    }
    return $rows[0];
}

//分类下拉列表（仅仅显示最底级分类）
$sel= '<tr class="containerBody">
				<td class="formLabel"> 所属分类</td>
				<td class="formTableTd" align="left"> <select id="dept_id" style="height:22px;" name="classId">';
function get_cname($id=0,$tab=0,$str='--'){
    global $sel,$row;
    //根据传入参数id查询子分类信息
    $c_table = Database::get_main_table ( 'tbl_class' );
    $sql = "select id,className,fid from $c_table where fid= $id";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );//查询子类 
        while($rows=Database::fetch_row ($res)){ //循环记录集
            if(!hasCildren($rows[0])){
               // $pre=str_repeat($str,$tab);
                if($rows[0]==$row[7]){
                    $sel.= "<option value='".$rows['0']."'selected='selected'>".$pre.$rows['1']. "</option>";
                }else{
                    $sel.= "<option value='".$rows['0']."'>".$pre.$rows['1']."</option>";
                }
               //get_cname($rows['0']);  //调用函数，传入参数，继续查询下级 
            }else{
                get_cname($rows[0]);
            }
           
        }
     return $sel;
}
$cname=get_cname();
$cname .='</select>&nbsp;&nbsp;</td>
			</tr>';
//反转义
$row[0]=htmlspecialchars_decode($row[0]);
$row[2]=htmlspecialchars_decode($row[2]);
$row[4]=htmlspecialchars_decode($row[4]);

$form->addElement ( 'text','exam_name',get_lang ('考题名称'),array('style' => 'width:540px;height:150px', 'class' => 'inputText' ,'id'=>'description','value' => "$row[0]"));
$form->addRule ( 'exam_name', get_lang ( '考题名称不能为空' ), 'required' );
$form->addRule ( 'exam_name', '考题名称最多只能输入1000个字符', 'maxlength', 1000 );

$form->addElement ( 'hidden', 'id', $exam_id );

$form->addElement ( 'hidden', 'uploadText', $row[5] );  

$str = '<tr class="containerBody">
	<td class="formLabel"> 考题内容</td>
	<td class="formTableTd" align="left"> <textarea maxlength="65535" style="width:540px;height:150px" class="inputText" name="examDesc" id="description1">'.$row[2].'</textarea>&nbsp;&nbsp;</td>
	</tr>';

$form->addELement('html',$str);
$form->addElement ( 'text', 'examBranch', get_lang ( '分数' ), array ('id'=>'dd','style' => "width:350px", 'class' => 'inputText','value' => "$row[1]"));
$form->addRule ( 'examBranch', get_lang ( '分数不会能为空' ), 'required');
$form->addRule ( 'examBranch', get_lang ( '只能输入数字' ), 'numeric' );
$form->addRule ( 'examBranch', '考题名称最多只能输入11个字符', 'maxlength', 11 );

//isKey
$group = array ();
if($row[3]==0){
    $group [] = HTML_QuickForm::createElement ( 'radio', 'isKey', null, get_lang ( '有' ), 1, array ( ) );
    $group [] = HTML_QuickForm::createElement ( 'radio', 'isKey', null, get_lang ( '没有' ), 0, array ("checked" => "checked") );
}elseif($row[3]==1){
    $group [] = HTML_QuickForm::createElement ( 'radio', 'isKey', null, get_lang ( '有' ), 1, array ( "checked" => "checked") );
    $group [] = HTML_QuickForm::createElement ( 'radio', 'isKey', null, get_lang ( '没有' ), 0, array () );
}
$form->addGroup ( $group, 'isKey', get_lang ( 'key值' ), '&nbsp;' );
//答案
$form->addElement ( 'text', 'examKey', get_lang ( '答案' ), array ('maxlength' => 1000, 'style' => "width:250px;height:60px", 'class' => 'inputText' ,'value'=>"$row[4]" ,'onBlur'=>'checkstr(this.value)'));

$form->addRule ( 'examKey', '答案最多只能输入1000个字符', 'maxlength', 1000 );
//上传
$form->addElement ( 'file', 'uploadText2', get_lang ( '上传文件' ), array ('style' => "width:350px", 'class' => 'inputText' ) );

if($row[5]){
    $a='<a href="exam_edit_1.php?action=delete_report&id='.$exam_id.'">删除当前报告</a>';


    $str='<tr class="containerBody">
				<td class="formLabel"> 删除报告</td>
				<td class="formTableTd" align="left"> &nbsp;&nbsp;&nbsp;
                                '.$a.'
                                    </td>
			</tr>';

    $form->addElement ('html',$str);
}
//$button=link_button ( 'edit.gif', '修改题目信息', 'exam_edit_1.php?id=' . $team_id, '75%', '55%', FALSE )."&nbsp;&nbsp;".confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'exam_list_1.php?action=delete_exam&id=' . $team_id );

//删除当前报告

if($row[5] && $_GET['action']=='delete_report'){
    
    if(unlink($row[5])){
        $sql="update tbl_exam set uploadText = '' where id = ".$exam_id;
  
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        if(!$res){
            exit('文件已经删除，但删除数据失败');
        }else{
            echo '<script>confirm("确定删除报告？")</script>';
        }
    }else{
        echo '删除失败';
    }
}


$form->addElement ( 'hidden', 'oldfile', $row[5] );



//是否提交报告
$group = array ();
$group [] = HTML_QuickForm::createElement ( 'radio', 'isReport', null, get_lang ( '是' ), 1, array ( ) );
$group [] = HTML_QuickForm::createElement ( 'radio', 'isReport', null, get_lang ( '否' ), 0, array ("checked" => "checked") );
$form->addGroup ( $group, 'isReport', get_lang ( '提交报告' ), '&nbsp;' );

//是否有控制台
//$group = array ();
//$group [] = HTML_QuickForm::createElement ( 'radio', 'isConso', null, get_lang ( '有' ), 1, array ( ) );
//$group [] = HTML_QuickForm::createElement ( 'radio', 'isConso', null, get_lang ( '无' ), 0, array ("checked" => "checked") );
//$form->addGroup ( $group, 'isConso', get_lang ( '控制台' ), '&nbsp;' );

//所属分类
$form->addElement ( 'html', $cname );

$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit', get_lang ( '更改' ), 'class="save"' );
//$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit_plus', get_lang ( 'SaveAdd' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, null, '&nbsp;', '&nbsp;&nbsp;' );
$form->applyFilter ( '__ALL__', 'trim' );

$values['isReport']['isReport']=$row[6];
$values['isKey']['isKey']=$row[3];
$form->setDefaults ( $values );

$form->addFormRule ( "_license_user_count" );

Display::setTemplateBorder ( $form, '98%' );

// Validate form
if ($form->validate ()) {
        $c = $form->getSubmitValues ();
        $name=htmlspecialchars($c['exam_name']);
        $examBranch=$c['examBranch'];
        $examDesc=htmlspecialchars($c['examDesc']);
        $isKey=$c['isKey']['isKey'];
        $isReport=$c['isReport']['isReport'];
        $examKey=htmlspecialchars($c['examKey']);
        $classId=$c['classId'];
        $uploadText=$c['uploadText'];
        $id=$c['id'];
        $oldFile=$c['oldfile'];
        $table='tbl_exam';

//如果有 文件上传 或者 更改过文件上传路径 则删除原来的文件，并且重新上传  
   if(!empty($_FILES['uploadText2']['name'])){ 
        $fileInfo = $_FILES['uploadText2'];
        $type = substr($fileInfo['name'],(strrpos($fileInfo['name'],'.')+1));
        $tmp_name = $fileInfo['tmp_name'];
        $filename = substr(md5(uniqid(rand())),0,16).'.'.$type;
        $size = $fileInfo['size'];
        $error = $fileInfo['error'];
        $m=100;
        $Maxsize=1024*1024*$m;
        ini_set('upload_max_filesize',$m.'M');
        $ext=pathinfo($filename,PATHINFO_EXTENSION);
        $path='../../storage/report';
        $destination=$path.'/'.$filename;
        $allowext=array('rar','zip');
        $sql = 'UPDATE tbl_exam SET exam_Name ="'.$name.'",examBranch = '.$examBranch.',examDesc = "'.$examDesc.'",iskey = '.$isKey.', isReport = '.$isReport.' ,examKey = "'.$examKey.'",classId = '.$classId.',uploadText="'.$destination.'" WHERE id = '.$id;
        
if ($error == 0) {
    if($size>$Maxsize){
        $err='上传文件的文件大小不能超过'.$m.'MB';
    }
    if(!in_array($ext, $allowext)){
        exit('');
    }
    if(!file_exists($path)){
        mkdir($path,0777,TRUE);
        chmod($path,0777);
    }
    if(@move_uploaded_file($tmp_name, $destination)){
             $res = api_sql_query ( $sql, __FILE__, __LINE__ );
             if(!$res){
                exit("更新数据失败"); 
            }else{
              if($oldFile!=''){
                if(!unlink($oldFile)){
                    exit('删除原文件失败');
                }
              }
            }
    }else {
                $err='上传失败';
    }
} else {
    switch ($error) {
        case 1:
             $err= '上传文件超过了PHP配置文件中upload_max_filesize选项的值';
            break;
        case 2:
             $err= '超过了表单MAX_FILE_SIZE限制的大小';
            break;
        case 3:
             $err= '文件部分被上传';
            break;
        case 4:
             $err= '没有选择上传文件';
            break;
        case 6:
             $err= '没有找到临时目录';
            break;
        case 7:
        case 8:
             $err= '系统错误';
            break;
    }
}    
        }else{
            $sql = 'UPDATE tbl_exam SET exam_Name = "'.$name.'",examBranch = "'.$examBranch.'",examDesc = "'.$examDesc.'",iskey = '.$isKey.', isReport = '.$isReport.',examKey ="'.$examKey.'",classId = '.$classId.' WHERE id = '.$id;
            $res = api_sql_query ( $sql, __FILE__, __LINE__ );
            }
       

if (isset ( $c ['submit_plus'] )) {
	api_redirect ( 'exam_add.php?message=' . urlencode ( get_lang ( '' ) ) );
} else {
        tb_close ( 'exam_list_1.php?action=show_message&message=' . urlencode ( get_lang ( '' ) ) );
	}
}
Display::display_header($tool_name,FALSE);

if (! empty ( $message )) {
	Display::display_normal_message ( stripslashes ( $message ), false );
}

$form->display ();

Display::display_footer ();
?>
<script type="text/javascript">
    <?php 
        if($err){
            echo  alert($err);
        }
        
        ?>
$(function(){
    if($(":input[name='isKey[isKey]'][value='0']").attr('checked')){
         $(".containerBody:eq(4)").slideUp(0);
    }
    if( $(":input[name='isKey[isKey]'][value='1']").attr('checked')){
         $(".containerBody:eq(7)").slideUp();
    }
    $(":input[name='isKey[isKey]'][value='0']").click(function(){
       $(".containerBody:eq(4)").slideUp(0); 
       $(".containerBody:eq(7)").slideDown();
    });
    
    $(":input[name='isKey[isKey]'][value='1']").click(function(){
        $(".containerBody:eq(7)").slideUp();
        $(".containerBody:eq(4)").slideDown();
    });
    $(".containerBody:eq(7)").hide();
});

function checkstr(str){
    for(i=0;i<str.length;i++){
        var state;
        
        var leg=str.charCodeAt(i);
        if(leg>255){
            var state=true ;
        }
    }
    if(state){
       alert('答案只能为中文字符');
    }
  } 




</script>

