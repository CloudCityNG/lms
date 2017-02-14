<?php
/**
==============================================================================
==============================================================================
 */
header("content-type:text/html;charset=utf-8");
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

$_SESSION['setup_id'] =  htmlspecialchars($_GET ['id']);
$id = intval($_SESSION['setup_id']);
$objDept = new DeptManager ();

$htmlHeadXtra [] = import_assets ( "select.js" );
$tool_name = get_lang ( 'ArrangeCourse' );
Display::display_header ( $tool_name, FALSE );

$action=htmlspecialchars($_GET ['action']);

//删除数组元素
function array_remove(&$arr, $offset){
    array_splice($arr, $offset, 1);
}

if (isset ($action)) {
    switch ($action) {
        case 'delete' :
            $delete_id=  intval(htmlspecialchars($_GET ['code']));
            $task_id=  intval(htmlspecialchars($_GET ['task_id']));
        
            if ($delete_id!==''){

                $all_user=trim(Database::getval("select `subclass` from `setup` where `id`=".$task_id,__FILE__,__LINE__));
                $var_data='';
                $all_data=  explode(',',$all_user);
        
                foreach ($all_data as $v2) {
                    if($v2!==''){
                        if($v2!=$delete_id){
                          $var_data.=$v2.',';  
                        }
                    }
                } 
                $sql="UPDATE   `setup` SET  `subclass` ='".$var_data."' WHERE `id` =".$task_id;
                $res=api_sql_query ( $sql, __FILE__, __LINE__ );
        
            if($res){
               $sql1="UPDATE   `course_category` SET  `status` =0 WHERE `id` =".$delete_id;
               api_sql_query ( $sql1, __FILE__, __LINE__ );
            }
                

                $redirect_url = "setuping.php?id=".$task_id;
               api_redirect ( $redirect_url );
            }
            break;
    }
}

if(isset($_POST['formSent']) && getgpc("formSent","P")==1){
    $new=$_POST['checkbox']; 
    $taskid=  intval(getgpc('task'));
    $all_users =Database::getval("select `subclass` from `setup` where `id`=".$taskid,__FILE__,__LINE__);
    if(strstr($all_users,',',true)){
        $old=explode(",",$all_users);
    }else{
        $old=array();
    }
    $intersection = array_diff($old,$new);
    $result= array_merge($intersection,$new);
    for($p=0;$p<count($result);$p++){
        if($result[$p]==''){
            array_remove($result, $p);
        }
    }
    $r='';
    foreach ($result as $v) {
        if($v!==''){
            $r.=$v.',';
            $sql_u="UPDATE  `course_category` SET  `status`=1 WHERE `id` =".intval($v); 
            api_sql_query($sql_u,__FILE__,__LINE__); 
        }
    }
   
    $sql_vm="UPDATE  `setup` SET  `subclass` =  '".$r."' WHERE `id` =".$taskid;
    $res=api_sql_query($sql_vm,__FILE__,__LINE__);

    $redirect_url = "setuping.php?id=".$taskid;
    api_redirect ( $redirect_url );
   
}
?>
<br>
<form name="theForm" method="post" action="setuping.php" >
    <input type="hidden" name="formSent" value="1" />
    <input type="hidden" name="task" value="<?php echo $id?>" />
    <table border="0" cellpadding="5" cellspacing="0" align="center"  width="98%">
        <tr class="containerBody">
            <td class="formLabel">选择课程分类</td>
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
                <button class="cancel form-can" type="button" onclick="javascript:window.parent.location.href='setup.php'"><?=get_lang ( 'Cancel' )?></button>
            </td>
        </tr> 
    </table>

    <script type="text/javascript">
        $(document).ready(function(){ 
               var url="<?=api_get_path ( WEB_CODE_PATH ) . "admin/ajax_actions.php"?>"; 
            $.ajax({type:"post", data:{action:"get_course_category_list_without"},
                    url:url, dataType:"json",cache:false,
                    success:function(data){ 
                            var obj='';
                            for ( var i = 0; i < data.length; i++) { 
                                obj+="<input type='checkbox' name='checkbox[]' value='"+data[i].id+"'/>"+data[i].name+"<br/>"; 
                            } 
                       $("#source_select").html(obj);      
                    },
                    error:function() { alert("Server is Busy, Please Wait...");}
	      });
         }); 
      
    </script>
</form>
<?php

$table_header [] = array (get_lang ( '编号' ), true );
$table_header [] = array (get_lang ( '分类名称' ), true );
$table_header [] = array (get_lang ( '操作' ) );

$user_data =trim(Database::getval("select `subclass` from `setup` where `id`=".  intval(getgpc('id')),__FILE__,__LINE__));
    
$datas=  explode(',',$user_data);
//去空
foreach ($datas as $v2) {
    if($v2!==''){
        $datass[]=$v2;
    }
}
$count_users_data=count($datass);
if($datass[0]!==''){
    for($i=0;$i<$count_users_data;$i++){
        $row = array ();
        $row [] = $i+1;
        $row [] = DATABASE::getval("select name from course_category where id ='".intval($datass[$i])."'"); 
        $href = 'setuping.php?action=delete&code=' .$datass[$i].'&task_id='.$id;
        $row [] = '&nbsp;&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Unreg', $href );
        $table_data [] = $row;
    }
}
unset ( $data, $row );

echo '<br/>该课程体系的所有分类: ';
echo Display::display_table ( $table_header, $table_data );

Display::display_footer ();