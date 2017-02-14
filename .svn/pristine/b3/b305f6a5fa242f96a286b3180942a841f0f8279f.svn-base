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
 
$code = getgpc ( 'cidReq' );
 
$tool_name = get_lang ( 'CourseList' );
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox (); 
Display::display_header ( $tool_name, FALSE );
 
if($_GET['delete_id']){
    //释放被课程占用的路由设备
    $net_dev_type=DATABASE::getval("select  `type`  from `course_connection_vmdisk` where  `id`=". getgpc("delete_id"));
    if($net_dev_type==2){
        $net_dev_id=DATABASE::getval("select  `net_dev_id`  from `course_connection_vmdisk` where  `id`=". getgpc("delete_id"));
        $sql="UPDATE  `net_devices` SET  `status` =  '' WHERE  `id` =".$net_dev_id;
        api_sql_query($sql); 
    }
    //delete rel
    $sql="DELETE FROM `course_connection_vmdisk` WHERE id=". getgpc("delete_id");
    api_sql_query($sql);
     //clean old netmap for  this  course
    $sql="UPDATE  `".DB_NAME."`.`course` SET  `netMap` = ''  WHERE  `code` ='".$code."'";  
    api_sql_query($sql);
    
    $redirect_url = 'course_rel_vmdisk.php?cidReq=' . $code;
    api_redirect ( $redirect_url );
}

if (isset ( $_POST ['formSent'] ) && is_equal (getgpc("formSent","P"), "1" )) {     
    $form_data=$_POST; 
    $submit1=$form_data['submit1']; 
    $submit2=$form_data['submit2']; 
    $course_code=$form_data['course_code'];
    $cname=DATABASE::getval("select `title`  from  `course`  where  `code`='{$course_code}'");
    $vmdisks=$form_data['vmdisks'];  
    if($vmdisks){ 
        $vmname=DATABASE::getval("select  `name`  from  `vmdisk`  where  `id`=".$vmdisks);
        $sql="INSERT INTO `course_connection_vmdisk`( `cid`, `cname`, `net_dev_id`, `net_dev_name`,`type`) VALUES ('{$course_code}','{$cname}',{$vmdisks},'{$vmname}',1)";
        api_sql_query($sql);
    }
    $routers=$form_data['routers'];
    if($routers){ 
        $router_name=DATABASE::getval("select  `name`  from  `net_devices`  where  `id`=".$routers);
        $sql="INSERT INTO `course_connection_vmdisk`( `cid`, `cname`, `net_dev_id`, `net_dev_name`,`type`) VALUES ('{$course_code}','{$cname}',{$routers},'{$router_name}',2)";
        $r_re=api_sql_query($sql);
        if($r_re){
            $sql="UPDATE  `net_devices` SET  `status` = '{$course_code}' WHERE  `id` =".$routers;
            api_sql_query($sql);
        }
    }
    
     if($submit1){
      tb_close();
     }else if($submit2){
         $redirect_url = "course_rel_vmdisk.php?cidReq=" . $course_code;
         api_redirect ( $redirect_url );
     }
   
}

//vmdisks_list
   $cl_sql="select `id`,`name` from `vmdisk` ";
    $result = api_sql_query ( $cl_sql, __FILE__, __LINE__ );
    $str='';   
    $str.="<option value='0'>选择虚拟模板</option>";
    while ( $row = Database::fetch_array ( $result, 'ASSOC' ) ) {  
        $str.="<option value='".$row['id']."'>".$row['name']."</option>";
       }  
 //routers_list
    $r_sql="select `id`,`name` from `net_devices` where  `status`=''";
    $res = api_sql_query ( $r_sql, __FILE__, __LINE__ );
    $str_router='';   
    $str_router.="<option value='0'>选择路由设备</option>";
    while ( $row = Database::fetch_array ( $res, 'ASSOC' ) ) {  
        $str_router.="<option value='".$row['id']."'>".$row['name']."</option>";
       }  
?>

<form name="theForm" method="post" action="course_rel_vmdisk.php?cidReq=<?=$code?>">
    <input type="hidden" name="formSent" value="1" />
    <input type="hidden" name="course_code" value="<?=$code?>" />

<table border="0" cellpadding="5" cellspacing="0" align="center" width="98%">
	<tr class="containerBody">
		<!--<td class="formLabel">选择虚拟模板</td>--> 
		<td style="text-align: left;" class="formTableTd">
		<table id="linkgoods-table" align="center" >
			<tr>
                            <td> 
                                <input type="text" id="keyword" class="inputText" onkeyup="search()"/>
                                <select  name="vmdisks" id="vms_id">
                                    <?=$str?>
                                </select> 
                            </td>
			</tr>
                        <tr>
                            <td> 
                                <input type="text" id="keyword_router" class="inputText" onkeyup="search_router()"/>
                                <select  name="routers" id="routers_id">
                                    <?=$str_router?>
                                </select> 
                            </td>
			</tr>
                        <tr class="containerBody"> 
                             <td  align="center" class="formTableTd" style="text-align:right;">
                                <input type='hidden' id="options_values" name="option_values" value=""/>
                                <input type="submit" name="submit1"  class="inputSubmit input-sure" value="<?=get_lang ( "保存" )?>"/>&nbsp;&nbsp;
                                <input type="submit" name="submit2"   class="inputSubmit input-sure" value="<?=get_lang ( "保存并继续添加" )?>"/>&nbsp;&nbsp;
                                <button class="cancel form-can" type="button" onclick="javascript:window.parent.location.href='course_list.php'"><?=get_lang ( 'Cancel' )?></button>
                            </td>
                        </tr> 
		</table>
		</td>
                <td>
                  <?php  echo link_button ( 'lp_webcs_chapter_add.gif', '新建虚拟模板', '../vmdisk/vmdisk_new.php?action=rel_vmdisk&cidReq='.$code, '80%', '70%' );  ?>
                </td>
                <td>
                  <?php  echo link_button ( 'exe.gif', '新建路由设备', 'net_router_add.php?action=rel_vmdisk&cidReq='.$code, '80%', '70%' );  ?>
                </td>
	</tr>
</table>
</form>
<script type="text/javascript"> 
function search(){  
         var url="<?=api_get_path ( WEB_CODE_PATH ) . "admin/ajax_actions.php"?>"; 
         var keyword_val= $("#keyword").val();  
        $.ajax({type:"post", data:{action:"get_vmdisks",keyword:keyword_val},
                url:url, dataType:"html",cache:false,
                success:function(data){   
                   $("#vms_id").html(data);      
                },
                error:function() { alert("Server is Busy, Please Wait...");}
          });
}
function search_router(){
        var url="<?=api_get_path ( WEB_CODE_PATH ) . "admin/ajax_actions.php"?>"; 
         var keywords_val= $("#keyword_router").val();  
        $.ajax({type:"post", data:{action:"get_routers",keywords:keywords_val},
                url:url, dataType:"html",cache:false,
                success:function(data){   
                   $("#routers_id").html(data);      
                },
                error:function() { alert("Server is Busy, Please Wait...");}
          });
}
</script>
<?php
 
function get_user_data($code) {
	$sql = "SELECT `id`,`cid`,`cname`,`type`,`net_dev_name`,`id` FROM `course_connection_vmdisk` WHERE  `cid`=".$code;
	$course_vmdisk = api_sql_query_array_assoc ( $sql, __FILE__, __LINE__ );
	return $course_vmdisk;
}

$table_header [] = array (get_lang ( '编号' ), true );
$table_header [] = array (get_lang ( '课程名称' ), true );
$table_header [] = array (get_lang ( '设备类型' ), true );
$table_header [] = array (get_lang ( '拓扑设备名称' ), true ); 
$table_header [] = array (get_lang ( '操作' ), true ); 
$all_vm = get_user_data ($code);
foreach ( $all_vm as   $data ) {
        $vmid=DATABASE::getval("select `net_dev_id`  from `course_connection_vmdisk` where  `id`=".$data ['id']);
	$row = array ();
	$row [] = $data ['id'];
	$row [] = $data ['cname'];
        $row [] = ($data ['type']==2?'路由设备':'虚拟模板');
        $htm='';
        if($data ['type']==1){
            $row [] ="<a  href='../vmdisk/vmdisk_list.php?keyword=".$data ['net_dev_name']."&act=rel_vm' target='_blank'>". $data ['net_dev_name']."</a>"; 
            $htm.=link_button ( 'edit.gif', 'Edit', '../vmdisk/vmdisk_edit.php?id=' . $vmid, '70%', '80%', FALSE );
        }else if($data ['type']==2){
            $row [] =$data ['net_dev_name']; 
            $htm.=link_button ( 'edit.gif', 'Edit', 'net_router_edit.php?id=' . $vmid, '70%', '80%', FALSE );
        } 
        $htm.='&nbsp;&nbsp;&nbsp;&nbsp;'.confirm_href ( 'delete.gif', '你确定要执行该操作吗？', '', 'course_rel_vmdisk.php?delete_id=' .$data ['id'].'&cidReq='.$code );
        $row [] = $htm;
	$table_data [] = $row;
}
unset ( $data, $row );

echo '<br/>该课程已经调度的模板/路由: ';
echo Display::display_table ( $table_header, $table_data );

Display::display_footer ();
