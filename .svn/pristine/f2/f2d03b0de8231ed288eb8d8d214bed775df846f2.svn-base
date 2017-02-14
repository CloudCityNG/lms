<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include ('../inc/global.inc.php');

include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'smsmanager.lib.php');

api_protect_admin_script ();


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
<script type="text/javascript">KE.show({
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
			});
                        </script>
    <script type="text/javascript">
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
function validataLen(text,childId,index,len){
    var n=0;
   // var idnode=document.getElementById(childId);

    for(var i=0;i<text.length;i++){
    	leg=text.charCodeAt(i);
    	if(leg>255){
    		n+=2;
    	}else{
    		n+=1;
    	}
    }
    if(n>len)
    {
            var str= "输入字数超过最大值"+len;
            alert(str);
            
    }
} 



    



</script>';





if (! empty ( $_GET ['message'] )) {
	$message = urldecode ( getgpc('message'));
}

$interbreadcrumb [] = array ("url" => api_get_path ( WEB_ADMIN_PATH ) . 'index.php', "name" => get_lang ( 'PlatformAdmin' ) );
$tool_name = get_lang ( '添加题目' );
$form = new FormValidator ( 'user_add' );
function get_contestname() {
	$c_table = Database::get_main_table ( 'tbl_contest' );
	$sql = "SELECT  id		AS col0,
                 	matchName	AS col1
	FROM $c_table";
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();
	while ( $user = Database::fetch_row ( $res ) ) {
		$users [$user[0]] = $user[1];
	}
	return $users;
}

$form->addElement ( 'text', 'exam_name', get_lang ( '考题名称' ), array ('style' => "width:540px;height:150px", 'class' => 'inputText' ,'id'=>'description') );
$form->addRule ( 'exam_name', get_lang ( '考题名称不能为空' ), 'required' );
$form->addRule ( 'exam_name', '考题名称最多只能输入1000个字符', 'maxlength', 1000 );
$now=time();

$form->addElement ( 'hidden', 'examStime', $now );
$form->addRule ( 'examBranch', get_lang ( '' ), 'required' );
$form->addRule ( 'examBranch', get_lang ( '' ), 'numeric' );
//考题解析
$form->addElement ( 'textarea', 'examDesc', get_lang ( '考题内容' ), array ('maxlength' => 65535, 'style' => "width:540px;height:150px", 'class' => 'inputText' ,'id'=>'description1') );
$form->addRule ( 'reDesc', '考题解析不能为空', 'required' );
$form->addRule ( 'reDesc', '最多只能输入65535个字符', 'maxlength', 65535 );

$form->addElement ( 'text', 'examBranch', get_lang ( '分数' ), array ('maxlength' => 11,'style' => "width:350px", 'class' => 'inputText','onBlur'=>"validataLen(this.value,'dd',0,11)",'onBlur'=>'onlyNum(this.value)' ) );
$form->addRule ( 'examBranch', get_lang ( '分数不会能为空' ), 'required' );
$form->addRule ( 'examBranch', get_lang ( '只能输入数字' ), 'numeric' );
$form->addRule ( 'examBranch', '考题名称最多只能输入11个字符', 'maxlength', 11 );

//isKey
$group = array ();
$group [] = HTML_QuickForm::createElement ( 'radio', 'isKey', null, get_lang ( '有' ), 1, array ( ) );
$group [] = HTML_QuickForm::createElement ( 'radio', 'isKey', null, get_lang ( '没有' ), 0, array ("checked" => "checked") );
$form->addGroup ( $group, 'isKey', get_lang ( 'key值' ), '&nbsp;' );

$form->addElement ( 'text', 'examKey', get_lang ( '答案' ), array ('maxlength' => 1000, 'style' => "width:250px;height:60px", 'class' => 'inputText' ,'onBlur'=>'checkstr(this.value)') );

$form->addRule ( 'examKey', '答案最多只能输入1000个字符', 'maxlength', 1000 );
//上传
$form->addElement ( 'file', 'uloadText', get_lang ( '上传文件' ), array ('style' => "width:350px", 'class' => 'inputText' ) );

//是否提交报告
$group = array ();
$group [] = HTML_QuickForm::createElement ( 'radio', 'isReport', null, get_lang ( '是' ), 1, array ( ) );
$group [] = HTML_QuickForm::createElement ( 'radio', 'isReport', null, get_lang ( '否' ), 0, array ('value'=>0,"checked" =>'' ) );
$form->addGroup ( $group, 'isReport', get_lang ( '提交报告' ), '&nbsp;' );

//所属分类

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
				<td class="formTableTd" align="left"> <select id="dept_id" style="height:22px;" name="match_id">';
function get_cname($id=0,$tab=0,$str='--'){
    global $sel;
    $c_table = Database::get_main_table ( 'tbl_class' );
    $sql = "select id,className,fid from $c_table where fid= $id"; 
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );//查询子类 
    $rows = array(); 
        while($rows=Database::fetch_row ($res)){ //循环记录集
            if(!hasCildren($rows[0])){
                $sel.= "<option value='".$rows['0']."'>".$pre.$rows['1']. "</option>";
            }else{
                get_cname($rows['0']);
            }
           
        }
     return $sel;
}
$cname=get_cname();
$cname .='</select>&nbsp;&nbsp;</td>
			</tr>';
$form->addElement ( 'html', $cname );

$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit', get_lang ( 'Save' ), 'class="save"' );
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit_plus', get_lang ( 'SaveAdd' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, null, '&nbsp;', '&nbsp;&nbsp;' );
$form->applyFilter ( '__ALL__', 'trim' );

$values['isReport']['isReport']=0;
$values['isKey']['isKey']=1;
$form->setDefaults ( $values );

$form->addFormRule ( "_license_user_count" );

Display::setTemplateBorder ( $form, '98%' );

// Validate form
if ($form->validate ()) {
	$c = $form->getSubmitValues ();
        $eName=htmlspecialchars($c['exam_name']);
        $mark=htmlspecialchars($c['examBranch']);
        $eDesc=htmlspecialchars($c['examDesc']);
        
        $isKey=$c['isKey']['isKey'];
        $isR=$c['isReport']['isReport'];
        $key=htmlspecialchars($c['examKey']);
        $sTime=$c['examStime'];
        $classId=$c['match_id'];

//文件上传
if(!empty($_FILES['uloadText']['name'])){
        $fileInfo = $_FILES['uloadText'];
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
if ($error == 0) {
    if($size>$Maxsize){
       $err= '上传文件的文件大小不能超过100MB';
    }else{
    if(!in_array($ext,$allowext)){
        $err="$ext"."不是允许的文件类型";
    }else{
    if(!file_exists($path)){
        chmod($path,0777);
        mkdir($path,0777,TRUE);
    }
    if(move_uploaded_file($tmp_name, $destination)){
             $t_tbl = Database::get_main_table ( 'tbl_exam' );
             $sql= 'INSERT INTO '.$t_tbl.'(exam_Name,examBranch,examDesc,iskey,isReport,examKey,examStime,classId,uploadText) VALUES ("'.$eName.'",'.$mark.',"'.$eDesc.'",'.$isKey.','.$isR.',"'.$key.'",'.$sTime.','.$classId.',"'.$destination.'")';
             $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    }
    }
    }
} else {
    switch ($error) {
        case 1:
            $err='上传文件超过了PHP配置文件中upload_max_filesize选项的值';
            break;
        case 2:
            $err='超过了表单MAX_FILE_SIZE限制的大小';
            break;
        case 3:
            $err='文件部分被上传';
            break;
        case 4:
            $err='没有选择上传文件';
            break;
        case 6:
            $err= '没有找到临时目录';
            break;
        case 7:
        case 8:
            $err='系统错误';
            break;
    }
}    
        }else{
            //没有上传的插入       
            $sql= 'INSERT INTO tbl_exam(exam_Name,examBranch,examDesc,iskey,isReport,examKey,examStime,classId) VALUES ("'.$eName.'",'.$mark.',"'.$eDesc.'",'.$isKey.','.$isR.',"'.$key.'",'.$sTime.','.$classId.')';
            $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        }     

if ($res) {
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
            echo 'alert("'.$err.'")';
        }
?>    
$(function(){
    $(":input[name='isKey[isKey]'][value='0']").click(function(){
       $(".containerBody:eq(4)").slideUp(0); 
       $(".containerBody:eq(6)").slideDown();
    });
    
    $(":input[name='isKey[isKey]'][value='1']").click(function(){
        $(".containerBody:eq(6)").slideUp();
        $(".containerBody:eq(4)").slideDown();
    });
    $(".containerBody:eq(6)").hide();
    $(":input[name='uloadText']").after('&nbsp;&nbsp;&nbsp;<div style="display:inline-block;">上传rar、zip压缩包</div>');
});



//禁止输入中文字符串
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