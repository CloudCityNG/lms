<?php
/**
 *  课程用户数统计
 * User:  guojg
 * Date: 16-5-24

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

$table_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
$table_user = Database::get_main_table ( TABLE_MAIN_USER );
$table_user_role = Database::get_main_table ( TABLE_MAIN_USER_ROLE );
$view_sys_user = Database::get_main_table ( VIEW_USER );

$sorting_options = array ();
$sorting_options ['column'] = 0;
$sorting_options ['default_order_direction'] = 'ASC';
$table_header [] = array (get_lang ( '排名' ),FALSE );
$table_header [] = array (get_lang ( '课程名称' ), FALSE );
$table_header [] = array (get_lang ( '课程id' ), true );
$table_header [] = array (get_lang ( '学习用户总数' ), false );
$table_header [] = array (get_lang ( '学习总时间' ), false,null, array ('style' => 'width:80px' ) );
$sys_dept_id = addslashes($_GET['id']);
 
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
    //return (array) $value;
    $t= $value["days"] ."天"." ". $value["hours"] ."小时". $value["minutes"] ."分";
    Return $t;
    
     }else{
    return (bool) FALSE;
    }
 }
 
$sql="select lesson_id,code from vmdisk_log,course where vmdisk_log.lesson_id=course.code group by lesson_id order by count(lesson_id) desc limit 10 ";
$all_org=api_sql_query_array($sql, $file = '', $line = 0);
$i=1;
foreach ( $all_org as $item) {
     //排名
     $row = array();  
     $row[] =$i;
     $i++;
     //课程名称
     $sql="select title from course where code = ".$item ['lesson_id'];
     $title=  Database::getval($sql);
     $row [] =$title;
     //课程id
     $row [] = $item ['lesson_id'];
     //学习用户总数
     $sql = "select count(lesson_id) from vmdisk_log where lesson_id =".$item ['lesson_id'];
     $usernum=  Database::getval($sql);
     $row [] =$usernum;
     //学习总时间
                $lesson_id=$item ['lesson_id'];
                    $sql1="select  end_time,start_time from vmdisk_log  where lesson_id = ' ".$item ['lesson_id']. "' ";
                    $vm_list1=  api_sql_query_array_assoc($sql1);
                   foreach($vm_list1 as $vm){
                                $total_time  =  strtotime($vm['end_time']) -strtotime($vm['start_time']);  
                                if($total_time>0){
                                    $total_times +=$total_time;                                  
                                }
                        }
         $row [] =  Sec2Time($total_times);
         $table_data [] = $row;
}
?>
<aside id="sidebar" class="column systeminfo  open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>
  <section id="main" class="column">
      <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/log/logging_list.php">日志管理</a> &gt; 
        &gt; 课程学习使用排名前10名
    </h4>
    <article class="module width_full">
           <?php echo Display::display_table ( $table_header, $table_data );?>
    </article>
</section>
    </body>
</html>
