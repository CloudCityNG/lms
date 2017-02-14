<?php
/**
 ==============================================================================
 ==============================================================================
 */
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

$user_id = intval(getgpc ( 'user_id' ));
$action = getgpc ( "action" );
$code = getgpc ( 'code' );

$tbl_user = Database::get_main_table ( TABLE_MAIN_USER );
$tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
$tbl_course_user = Database::get_main_table ( TABLE_MAIN_COURSE_USER );
$objDept = new DeptManager ();

$htmlHeadXtra [] = import_assets ( "select.js" );
$tool_name = get_lang ( 'ArrangeCourse' );
Display::display_header ( $tool_name, FALSE );

if (isset ( $_GET ['action'] ) && is_equal (getgpc("action","G"), 'unregister' )) {
	if (isset ( $_GET ['user_id'] ) && is_numeric ( $_GET ['user_id'] )) {
		if (CourseManager::is_allowed_to_unsubscribe ( $code, $user_id )) {
			//CourseManager::unsubscribe_user ( $user_id, $code );
			$sql_data = array ('status' => STUDENT, 'is_course_admin' => '0', 'tutor_id' => '0' );
			$sqlwhere = "course_code = " . Database::escape ( $code ) . " AND user_id = " . Database::escape (intval(getgpc ( 'user_id', 'G' )) );
			$sql = Database::sql_update ( $tbl_course_user, $sql_data, $sqlwhere );
			api_sql_query ( $sql, __FILE__, __LINE__ );
		}
		$redirect_url = "course_admins.php?code=" . $code;
		api_redirect ( $redirect_url );
	}
}

if (isset ( $_POST ['formSent'] ) && is_equal (getgpc("formSent","P"), "1" )) {
	$rel_users =$_POST['checkbox']; 
	if ($rel_users && is_array ( $rel_users )) {
		//$is_required_crs=getgpc ( "is_required_course", "P" );
		$is_required_crs = 1;
		
		foreach ( $rel_users as $user_id ) {
			CourseManager::subscribe_user ( $user_id, $code, STUDENT, getgpc ( "begin_date", "P" ), getgpc ( "finish_date", "P" ), $is_required_crs );
			$log_msg = get_lang ( 'SubscribeUserToCourse' ) . "code=" . $code . ",user_id=" . $user_id;
			api_logging ( $log_msg, 'COURSE', 'SubscribeUserToCourse' );
			
			$sql_data = array ('status' => COURSEMANAGER, 'is_course_admin' => '1', 'tutor_id' => '0' );
			$sqlwhere = "course_code = " . Database::escape ( $code ) . " AND user_id = " . Database::escape ( $user_id );
			$sql = Database::sql_update ( $tbl_course_user, $sql_data, $sqlwhere );
			api_sql_query ( $sql, __FILE__, __LINE__ );
		}
		//Display :: display_normal_message(get_lang('CoursesAreSubscibedToUser'));
		$redirect_url = "course_admins.php?code=" . $code;
		api_redirect ( $redirect_url );
	} else {
		$error_message = get_lang ( 'AtLeastOneUser' );
		Display::display_error_message ( $error_message );
	}
}

$depts = $objDept->get_sub_dept_ddl2 ( 0, 'array' );
?>

<form name="theForm" method="post" action="course_admins.php">
    <input type="hidden" name="formSent" value="1" />
    <input type="hidden" name="code" value="<?=$code?>" />

<table border="0" cellpadding="5" cellspacing="0" align="center" width="98%">
	<tr class="containerBody">
		<td class="formLabel">要设置的课程管理员</td>
		<td style="text-align: left;" class="formTableTd">
		<table id="linkgoods-table" align="left">
			<tr>
                            <td colspan="2">
                            <div class="actions"><input type="text" name="keyword"
                                    class="inputText" />
                            <?php 
                            echo form_dropdown ( "dept_id", $depts, NULL, 'id="dept_id" style="height:22px;"' );
                            ?> <input type="button" value=" 搜索 " class="inputSubmit"
                                    onclick="search()" /></div>
                            </td>
			</tr>
                        <tr class="containerBody"> 
                            <td style="text-align: left;" class="formTableTd">
                                <table id="linkgoods-table" align="left">
                                        <tr>
                                            <td id="source_select"> 

                                            </td>
                                        </tr>
                                </table>
                            </td>
                             <td  align="center" class="formTableTd" style="text-align:right;">
                                <input type='hidden' id="options_values" name="option_values" value=""/>
                                <input type="submit"  class="inputSubmit input-sure" value="<?=get_lang ( "Ok" )?>"/>&nbsp;&nbsp;
                                <button class="cancel form-can" type="button" onclick="javascript:window.parent.location.href='course_plan.php'"><?=get_lang ( 'Cancel' )?></button>
                            </td>
                        </tr> 
		</table>
		</td>
	</tr>
</table>
<script type="text/javascript">

var elements = document.forms['theForm'].elements;

function search(){
         var url="<?=api_get_path ( WEB_CODE_PATH ) . "admin/ajax_actions.php"?>"; 
         var keyword_val=elements['keyword'].value;
         if(keyword_val=="undefined") keyword_val="";
        $.ajax({type:"post", data:{action:"get_user_list_without_cur_crsadmin",keyword:keyword_val,
        dept_id:$("#dept_id").val(),org_id:$("#org_id").val(),code:"<?=$code?>"},
                url:url, dataType:"json",cache:false,
                success:function(data){ 
                        var obj='';
                        for ( var i = 0; i < data.length; i++) { 
                            obj+="<input type='checkbox' name='checkbox[]' value='"+data[i].user_id+"'/>"+data[i].firstname+" ("+data[i].username+", "+data[i].dept_name+")"+"<br/>"; 
                        } 
                   $("#source_select").html(obj);      
                },
                error:function() { alert("Server is Busy, Please Wait...");}
          });
}

</script></form>
<?php

function get_user_data($condition = '') {
	global $deptObj, $code, $tbl_course_user, $tbl_user;
	$sql = "SELECT t2.username,t2.firstname,t2.official_code,t2.dept_id,t2.user_id,t1.tutor_id FROM $tbl_course_user AS t1 ";
	$sql .= "LEFT JOIN $tbl_user AS t2 ON t1.user_id=t2.user_id WHERE t1.course_code=" . Database::escape ( $code ) . " AND is_course_admin=1";
	$keyword = escape ( getgpc ( 'keyword' ), TRUE );
	if ($keyword) {
		$sql .= " AND (username LIKE '%" . $keyword . "%' OR firstname LIKE '%" . $keyword . "%')";
	}
	
	$all_course_users = api_sql_query_array_assoc ( $sql, __FILE__, __LINE__ );
	return $all_course_users;
}

$table_header [] = array (get_lang ( 'LoginName' ), true );
$table_header [] = array (get_lang ( 'FirstName' ), true );
$table_header [] = array (get_lang ( 'OfficialCode' ), true );
$table_header [] = array (get_lang ( 'UserInDept' ), true );
$table_header [] = array (get_lang ( 'Actions' ) );

$all_users = get_user_data ();
foreach ( $all_users as $admin_id => $data ) {
	$row = array ();
	$row [] = $data ['username'];
	$row [] = $data ['firstname'];
	$row [] = $data ['official_code'];
	$row [] = get_dept_path ( $data ['dept_id'] );
	$action_html = '';
	if (empty ( $data ['tutor_id'] )) {
		$href = 'course_admins.php?action=unregister&code=' . $code . '&amp;user_id=' . $data ['user_id'];
		$action_html .= '&nbsp;&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Unreg', $href );
	}
	$row [] = $action_html;
	$table_data [] = $row;
}
unset ( $data, $row );

echo '<br/>已经设置的课程管理员: ';
echo Display::display_table ( $table_header, $table_data );

Display::display_footer ();
