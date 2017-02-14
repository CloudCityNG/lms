<?php
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
 
$vmid = getgpc ( 'vmid' );    
  
$htmlHeadXtra [] = import_assets ( "select.js" );
$tool_name = get_lang ( 'ArrangeCourse' );
Display::display_header ( $tool_name, FALSE );
 
if($_GET['delete_id']){
    //clean old netmap for  this  course
    $code=DATABASE::getval("select `cid`  from  `course_connection_vmdisk`  where  `id`=". getgpc("delete_id"));
    $sql="UPDATE  `".DB_NAME."`.`course` SET  `netMap` = ''  WHERE  `code` ='".$code."'";  
    api_sql_query($sql);
    
    $sql="DELETE FROM `course_connection_vmdisk` WHERE `id`=". getgpc("delete_id");
    api_sql_query($sql); 
    $redirect_url = 'vmdisk_rel_course.php?vmid=' . $vmid;
    api_redirect ( $redirect_url );
}

if (isset ( $_POST ['formSent'] ) && is_equal (getgpc("formSent","P"), "1" )) {     
    $form_data=$_POST; 
    $submit1=$form_data['submit1']; 
    $submit2=$form_data['submit2']; 
    $vm_id=$form_data['vm_id'];
    $courses=$form_data['courses'];  
    $cname=DATABASE::getval("select `title`  from  `course`  where  `code`='{$courses}'");
    $vmname=DATABASE::getval("select  `name`  from  `vmdisk`  where  `id`=".$vm_id);
     $sql="INSERT INTO `course_connection_vmdisk`( `cid`, `cname`, `net_dev_id`, `net_dev_name`) VALUES ('{$courses}','{$cname}',{$vm_id},'{$vmname}')";
     api_sql_query($sql);   
     if($submit1){
      tb_close();
     }else if($submit2){
         $redirect_url = "vmdisk_rel_course.php?vmid=" . $vm_id;
         api_redirect ( $redirect_url );
     }
   
}

//courses_list
   $cl_sql="select `code`,`title` from `course` ";
    $result = api_sql_query ( $cl_sql, __FILE__, __LINE__ );
    $str='';   
    $str.="<option value='0'>选择课程名称</option>";
    while ( $row = Database::fetch_array ( $result, 'ASSOC' ) ) {  
        $str.="<option value='".$row['code']."'>".$row['title']."</option>";
       }  
 
?>

<form name="theForm" method="post" action="vmdisk_rel_course.php?vmid=<?=$vmid?>">
    <input type="hidden" name="formSent" value="1" />
    <input type="hidden" name="vm_id" value="<?=$vmid?>" />

<table border="0" cellpadding="5" cellspacing="0" align="center" width="98%">
	<tr class="containerBody">
		<td class="formLabel">选择虚拟模板</td>
		<td style="text-align: left;" class="formTableTd">
		<table id="linkgoods-table" align="left">
			<tr>
                            <td> 
                                <input type="text" id="keywords" class="inputText" onkeyup="searching()"/>
                                <select  name="courses" id="course_id">
                                    <?=$str?>
                                </select> 
                            </td>
			</tr>
                        <tr class="containerBody"> 
                             <td  align="center" class="formTableTd" style="text-align:right;">
                                <input type='hidden' id="options_values" name="option_values" value=""/>
                                <input type="submit" name="submit1"  class="inputSubmit input-sure" value="<?=get_lang ( "保存" )?>"/>&nbsp;&nbsp;
                                <input type="submit" name="submit2"   class="inputSubmit input-sure" value="<?=get_lang ( "保存并继续添加" )?>"/>&nbsp;&nbsp;
                                <button class="cancel form-can" type="button" onclick="javascript:window.parent.location.href='vmdisk_list.php'"><?=get_lang ( 'Cancel' )?></button>
                            </td>
                        </tr> 
		</table>
		</td>
	</tr>
</table>
</form>
<script type="text/javascript"> 
function searching(){  
         var url="<?=api_get_path ( WEB_CODE_PATH ) . "admin/ajax_actions.php"?>"; 
         var keyword_val= $("#keywords").val();   
        $.ajax({type:"post", data:{action:"get_cours",keyword:keyword_val},
                url:url, dataType:"html",cache:false,
                success:function(data){   
                   $("#course_id").html(data);      
                },
                error:function() { alert("Server is Busy, Please Wait...");}
          });
}

</script>
<?php
 
function get_user_data($vmid) {
	$sql = "SELECT `id`,`cid`,`cname`,`net_dev_name`,`id` FROM `course_connection_vmdisk` WHERE  `net_dev_id`=".$vmid;
	$course_vmdisk = api_sql_query_array_assoc ( $sql, __FILE__, __LINE__ );
	return $course_vmdisk;
}

$table_header [] = array (get_lang ( '编号' ), true );
$table_header [] = array (get_lang ( '课程编号' ), true );
$table_header [] = array (get_lang ( '课程名称' ), true );
$table_header [] = array (get_lang ( '虚拟模板名称' ), true ); 
$table_header [] = array (get_lang ( '操作' ), true ); 
$all_vm = get_user_data ($vmid); 
foreach ( $all_vm as   $data ) {    
	$row = array ();
	$row [] = $data ['id'];
	$row [] = $data ['cid'];
	$row [] = $data ['cname'];
	$row [] = $data ['net_dev_name']; 
        $row [] = confirm_href ( 'delete.gif', '你确定要执行该操作吗？', '', 'vmdisk_rel_course.php?delete_id=' .$data ['id'].'&vmid='.$vmid );
	$table_data [] = $row;
}
unset ( $data, $row );

echo '<br/>已经设置的课程管理员: ';
echo Display::display_table ( $table_header, $table_data );

Display::display_footer ();
