<?php
/**
==============================================================================
==============================================================================
 */
header("content-type:text/html;charset=utf-8");
$language_file = 'admin';
$cidReset = true;
include_once ('../../main/inc/global.inc.php');
//include_once('inc/page_header.php');
Display::display_header ( $tool_name, FALSE );
//api_protect_admin_script ();
require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

$_SESSION['red_user'] =  htmlspecialchars($_GET ['id']);
$id = $_SESSION['red_user'];

$objDept = new DeptManager ();

$htmlHeadXtra [] = import_assets ( "select.js" );
$tool_name = get_lang ( 'ArrangeCourse' );
Display::display_header ( $tool_name, FALSE );

$action=htmlspecialchars($_GET ['action']);

if (isset ($action)) {
    switch ($action) {
        case 'delete' :
            $delete_id=htmlspecialchars($_GET ['code']);
            $group_id=htmlspecialchars($_GET ['group_id']);
            if ( isset($delete_id)){

                //delete mysql
                //$sql = "DELETE FROM `vslab`.`vmdisk` WHERE `vmdisk`.`id` = {$delete_id}";
                $sql = "UPDATE  `vslab`.`user` SET  `type` =  '0',group_id='0' WHERE  `user`.`user_id` ={$delete_id}";
                $result = api_sql_query ( $sql, __FILE__, __LINE__ );

                $redirect_url = "red_user_control.php?id=".$group_id;
                api_redirect ( $redirect_url );
            }
            break;
    }
}

$get_formsent=  getgpc('formSent');
$get_str=  getgpc('str');
$get_user=  getgpc('user');
if(isset($get_formsent) && $get_formsent==1){
    $str=$get_str;
    $userid=$get_user;
    $red_tem=explode(",",$str);
    // var_dump($red_tem);
    foreach($red_tem as $k){
        $vms=explode("(",$k);
        $username=$vms[0];
        $status=$vms[1];
        $status =str_replace(')','',$status);
        if($status=="学员"){
            $status=5;
        }else if($status=="超级管理员"){
            $status=10;
        }else if($status=="老师"){
            $status=1;
        }

        $sql_vm="UPDATE  `vslab`.`user` SET  `type` =  '1',`group_id` = '".$userid."' WHERE status='".$status."'  and  username='".$username."';";
        api_sql_query($sql_vm,__FILE__,__LINE__);
    }
    $redirect_url = "red_user_control.php?id=".$userid;
    api_redirect ( $redirect_url );
}
?>
<aside id="sidebar" class="column open control">
    <div id="flexButton" class="closeButton close"></div>
</aside>

<section id="main" class="column">

<?php
function get_user_data($condition = '') {

    /*$_SESSION['red_user'] =  htmlspecialchars($_GET ['id']);
    $id = $_SESSION['red_user'];*/
    $get_id=  getgpc('id');
    $sql = "SELECT user_id,status,username,type,user_id FROM user where type=1 and group_id=".$get_id;

    /*$keyword = escape ( getgpc ( 'keyword' ), TRUE );
    if ($keyword) {
        $sql .= " AND (name LIKE '%" . $keyword . "%' OR blue_template LIKE '%" . $keyword . "%')";
    }*/
    $all_tasks = api_sql_query_array_assoc ( $sql, __FILE__, __LINE__ );
    return $all_tasks;
}


$table_header [] = array (get_lang ( '编号' ), true );
$table_header [] = array (get_lang ( '用户身份' ), true );
$table_header [] = array (get_lang ( '用户名称' ), true );
$table_header [] = array (get_lang ( '用户类型' ), true );
//$table_header [] = array (get_lang ( 'OfficialCode' ), true );
//$table_header [] = array (get_lang ( '操作' ) );

$all_users = get_user_data ();


foreach ( $all_users as $admin_id => $data ) {
    $row = array ();
    if($data ['type']==1){
        $data ['type']='红方';
    }else{
        $data ['type']='蓝方';
    }
    if($data ['status']==1){
       $data ['status']='教师';
    }elseif($data ['status']==10){
       $data ['status']='超级管理员';
    }else{
        $data ['status']='学生';
    }
    $row [] = $data ['user_id'];
    $row [] = $data ['status'];
    $row [] = $data ['username'];
    $row [] = $data ['type'];
    $href = 'red_user_control.php?action=delete&code=' . $data ['user_id'].'&group_id='.$id;
    //$row [] = '&nbsp;&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', '踢出用户', $href );
    $table_data [] = $row;
}
unset ( $data, $row );
    ?>
    <article class="module width_full hidden">
            <?php
echo '该红方小组的所有成员: ';
echo Display::display_table ( $table_header, $table_data );
        ?>
    </article>
</section>
<?php
Display::display_footer ();