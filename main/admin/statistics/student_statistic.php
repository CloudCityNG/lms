<?php
/**
 *  学生学习统计信息
 * User: ygl
 * Date: 16-5-20

 */
$language_file = array ('admin');
$cidReset=true;
require_once ('../../inc/global.inc.php');
$this_section=SECTION_PLATFORM_ADMIN;
api_protect_admin_script();
if (! isRoot ()) api_not_allowed ();

$required_roles=array(ROLE_TRAINING_ADMIN);
if(validate_role_base_permision($required_roles)===FALSE){
	api_deny_access(TRUE);
}

$tool_name = get_lang ( 'DeptList' );
$interbreadcrumb [] = array ('url' => api_get_path(WEB_ADMIN_PATH).'index.php', "name" => get_lang ( 'PlatformAdmin' ), 'target' => '_self' );
$interbreadcrumb [] = array ('url' => 'dept_index.php', "name" => get_lang ( 'DeptList' ), 'target' => '_self' );

$htmlHeadXtra[]='<script type="text/javascript">
if(document.attachEvent)  window.attachEvent("onload",  iframeAutoFit);  
else  window.addEventListener("load",  iframeAutoFit,  false);
</script>	
	
<style type="text/css">
.framePage {
	/*border:#CACACA solid 1px;*/
	border-top-style:none;
	width:100%;
	padding-top:0px;
	text-align:left;
}

#Resources {
	width:100%;
}
#Resources #treeview {
	float:left;
	border:#999 solid 1px;
	width:20%;
	//height:480px;
}
#Resources #frm {
	float:left;
	width:79%;
}
</style>
';

$htmlHeadXtra [] = Display::display_thickbox ();
$htmlHeadXtra [] = '<script type="text/javascript">
	function refresh_tree(){ parent.Tree.location.reload();parent.Tree.d.openAll(); }
</script>';
include_once ('../../inc/header.inc.php');

require_once (api_get_path ( LIBRARY_PATH ) . "export.lib.inc.php");
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( SYS_CODE_PATH ) . 'admin/admin.lib.inc.php');

$deptObj = new DeptManager ();

$table_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
$table_user = Database::get_main_table ( TABLE_MAIN_USER );
$table_user_role = Database::get_main_table ( TABLE_MAIN_USER_ROLE );
$view_sys_user = Database::get_main_table ( VIEW_USER );

if (isset ( $_GET ['action'] )) {
    switch (getgpc("action","G")) {
        case 'show_message' :
            if (isset ( $_GET ['message'] )) Display::display_normal_message ( stripslashes ( urldecode (getgpc("message","G") ) ) );
            break;
        case 'delete_dept' :
            $res_del = $deptObj->org_del (intval(getgpc ( 'id', 'G' )) );
            switch ($res_del) {
                case 1 :
                    $log_msg = get_lang ( 'DelDeptInfo' ) . "id=" . intval(getgpc ( 'id', 'G' ));
                    api_logging ( $log_msg, 'DEPT' );
                      header ( "Location:".URL_APPEDND."/main/admin/statistics/student_statistic.php?delete=success" ); 
                    break;
            }
            break;
    }
}


function get_sqlwhere() {
    $sql_where = "";
    if(!empty($_GET['keyword']) && $_GET['keyword']!=='请输入查找关键词'){
         $keyword=$_GET['keyword'];
              $sql_where .= " AND  dept_name LIKE '%" . $keyword . "%'";
        }
    $sql_where = $sql_where ;
    if ($sql_where){
        return $sql_where;
    }else{
        return "";
    }
}

//将秒数转换为时间（天、小时、分）
function Sec2Time($time){
    if(is_numeric($time)){
    $value = array(
      "years" => 0, "days" => 0, "hours" => 0,
      "minutes" => 0, "seconds" => 0,
    );
    if($time >= 86400){
      $value["days"] = floor($time/86400);
      $time = ($time%86400);
    }
    if($time >= 3600){
      $value["hours"] = floor($time/3600);
      $time = ($time%3600);
    }
    if($time >= 60){
      $value["minutes"] = floor($time/60);
      $time = ($time%60);
    }
    $t= $value["days"] ."天"." ". $value["hours"] ."小时". $value["minutes"] ."分";
    Return $t;
    
     }else{
    return (bool) FALSE;
    }
 }

 
 $form = new FormValidator ( 'search_simple', 'get', '', '', null, false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( ' {element} ' );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:150px", 'class' => 'searchtxt', 'title' => get_lang ( 'keyword' ) ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Search' ), 'class="inputSubmit"' );
$form->setDefaults ( $values );


if (is_not_blank ( $_GET ['keyword'] )) $parameters ['keyword'] = getgpc('keyword');
if (is_not_blank ( $_GET ['keyword_start'] )) $parameters ['keyword_start'] = getgpc('keyword_start');
if (is_not_blank ( $_GET ['keyword_end'] )) $parameters ['keyword_end'] = getgpc('keyword_end');
 
if (isset ( $_GET ['refresh'] )) echo '<script>refresh_tree();</script>';

$sorting_options = array ();
$sorting_options ['column'] = 0;
$sorting_options ['default_order_direction'] = 'ASC';

$table_header [] = array (get_lang ( '序号' ),FALSE );
$table_header [] = array (get_lang ( '名称/省份' ), FALSE );
$table_header [] = array (get_lang ( '用户数量' ), true );
$table_header [] = array (get_lang ( '学习课程数' ), false );
$table_header [] = array (get_lang ( '总时间' ), false, null, array ('style' => 'width:80px' ) );
$user_id = api_get_user_id();
$lesson_id=api_get_user_courses($user_id) ;
$total_time=0;
foreach ($lesson_id as $lesson_code){
    $sql1="select  end_time,start_time from vmdisk_log  where  user_id=' ". $user_id ." '   and  lesson_id = ' ".$lesson_code['code']. "' ";
   // var_dump($sql1);
    $vm_list1=  api_sql_query_array_assoc($sql1);
    //var_dump($vm_list1);
   foreach($vm_list1 as $vm){
                $total_time  =  strtotime($vm['end_time']) -strtotime($vm['start_time']);  
                if($total_time>0){
                    $total_times +=$total_time;
                }
//                echo $total_times."</br>";
         
        }
 }
 
 

 
 

$all_org = $GLOBALS ['deptObj']->get_all_org ();

$all_org = $GLOBALS ['deptObj']->get_all_org1();

foreach ( $all_org as $item ) {
    $row = array ();
    $row [0] = $item ['id'];
    $row [] = $item ['dept_name'];
    $sql="select  count(firstname) from user ,sys_dept  where  user.dept_id = sys_dept.id and user.dept_id = {$row [0]} ";
    $count=  Database::getval($sql);
    $row [] ='<a href="student_number.php?action=download&id='.$row [0].'">'.$count.'</a>';
    $sql="select  count(course_code) from user , sys_dept , course_rel_user  where  user.dept_id = sys_dept.id and course_rel_user . user_id=user.user_id and user.dept_id = {$row [0]} ";
    
    $course=  Database::getval($sql);
    $row [] ='<a href="course_number.php?action=download&id='.$row [0].'">'.$course.'</a>';
    //  学生学习总时间 
    $sqlu = "select user_id from user where dept_id = $row[0]";
    $userids = api_sql_query_array($sqlu);
    $total_times=0;
    foreach($userids as $uk=>$uv){
  
    $total_user = "select end_time,start_time from vmdisk_log where user_id ='".$uv['user_id']."' ";
    $res = api_sql_query_array($total_user);
    foreach($res as $mk => $mv){
        $total_time  =  strtotime($mv['end_time']) -strtotime($mv['start_time']);  
        if($total_time>0){
                    $total_times +=$total_time;
                    
                }
    }
    }

    $row [] =  ceil($total_times/60).'分钟';
    $total_times=0;
     $dept_user_sql="select user_id from user where dept_id=".$row[0];        
        $dept_users=  api_sql_query_array("$dept_user_sql");
            foreach ($dept_users as $dept_user) {
                $user_id=$dept_user['user_id'];
                $lesson_id=api_get_user_courses($user_id) ;
                $total_time=0;
                foreach ($lesson_id as $lesson_code){
                    $sql1="select  end_time,start_time from vmdisk_log  where  user_id=' ". $user_id ." '   and  lesson_id = ' ".$lesson_code['code']. "' ";                
                    $vm_list1=  api_sql_query_array_assoc($sql1);

                   foreach($vm_list1 as $vm){
                                $total_time  =  strtotime($vm['end_time']) -strtotime($vm['start_time']);  
                                if($total_time>0){
                                    $total_times +=$total_time;
                                    
                                }
                        }
                 }
            }
         $row [] =  Sec2Time($total_times);
  
    $table_data [] = $row;

}

if($platform==3){
    $nav='userlist';
}else{
    $nav='users';
}


?>
<aside id="sidebar" class="column systeminfo  open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>
  <section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; 学生学习统计信息</h4>


   
  <div class="managerSearch">
      
      <?php $form->display ();?>
            
<!--        <span class="searchtxt right">
            <?php
            $report_count=Database::getval('SELECT COUNT(id) FROM `sys_dept`',__FILE__,__LINE__);
            if($report_count > 0){
                echo '&nbsp;&nbsp;' . link_button ( 'return.gif', '导出学生统计信息', 'student_statistic_export.php', '60%', '50%' );
            }
            ?>
        </span>-->
     
    </div>
    <article class="module width_full">
           <?php echo Display::display_table ( $table_header, $table_data );?>
    </article>
</section>
    </body>
</html>
