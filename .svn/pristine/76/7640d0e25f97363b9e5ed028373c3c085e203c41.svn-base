<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
	echo '<script language="javascript"> document.location = "./login.php";</script>';
        exit();
}
include_once ('../../main/assignment/assignment.lib.php');
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();
include_once ("inc/page_header.php");

if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'work_attendance'"))!=1){
    $sql_insert ="CREATE TABLE IF NOT EXISTS `work_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL COMMENT '用户名称',
  `name` varchar(128) NOT NULL COMMENT '姓名',
  `dept_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '部门名称',
  `sign_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '签到时间',
  `sign_return_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '签退时间',
  `mode` int(128) NOT NULL COMMENT '出勤状态',
  `status` int(11) NOT NULL COMMENT '状态',
  `range` int(11) NOT NULL COMMENT '上课时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='考勤表' AUTO_INCREMENT=0 ;";
    $result= api_sql_query ( $sql_insert,__FILE__, __LINE__ );
}

function mode_filter($mode){
    $result = "";
    if($mode==1){
        $result.='签到成功';
    }elseif($mode==2){
        $result.='签退成功';
    }else{
        $result.='旷课';
    }
    return $result;
}

function time_filter($id){
    $sql="select sign_date,sign_return_date from work_attendance where id =".$id;

    $res=api_sql_query($sql,__FILE__,__LINE__);
    $dates=Database::fetch_row($res);
    $startdate= $dates[0];
    $enddate= $dates[1];
    if($enddate!='0000-00-00 00:00:00'){
        $minute=floor((strtotime($enddate)-strtotime($startdate))%86400/60);
        return $minute;
    }else{
        return '0';
    }
}
/**select sign_date from work_attendance where id =
SELECT DATEDIFF('2008-8-21,'2009-3-21');

 **/
function status_filter($status){
    $s='';
    if($status==1){
        $s.='迟到';
    }elseif($status==2){
        $s.='旷课';
    }else{
        $s.='完成考勤';
    }
    return $s;
}
$p_action=  trim(getgpc("action","G"));
$p_id=  intval(getgpc('id'));
if (isset ( $p_action )) {
    switch ($p_action) {
        case 'deletes' :
            $number_of_selected_users = count ( $p_id );
            $number_of_deleted_users = 0;
            foreach ( $p_id as $index => $id ) {
                $sql = "DELETE FROM `vslab`.`work_attendance` WHERE id='" . $id . "'";
                api_sql_query ( $sql, __FILE__, __LINE__ );

                $log_msg = get_lang('删除所选') . "id=" . $id;
                api_logging ( $log_msg, 'labs', 'labs' );
            }
            break;
        case 'Belate' :
            $number_of_selected_users = count ( $p_id );
            $number_of_deleted_users = 0;
            foreach ( $p_id as $index => $id ) {

                $sql = "UPDATE  `vslab`.`work_attendance` SET  `status` =  '1' WHERE  `work_attendance`.`id` ='" . $id . "'";
                api_sql_query ( $sql, __FILE__, __LINE__ );
            }
            break;
        case 'Truancy' :
            $number_of_selected_users = count ( $p_id );
            $number_of_deleted_users = 0;
            foreach ($p_id as $index => $id ) {

                $sql = "UPDATE  `vslab`.`work_attendance` SET  `status` =  '2' WHERE  `work_attendance`.`id` ='" . $id . "'";
                api_sql_query ( $sql, __FILE__, __LINE__ );
            }
            break;
        case 'normal_attendance' :
            $number_of_selected_users = count ( $p_id );
            $number_of_deleted_users = 0;
            foreach ( $p_id as $index => $id ) {

                $sql = "UPDATE  `vslab`.`work_attendance` SET  `status` =  '0' WHERE  `work_attendance`.`id` ='" . $id . "'";
                api_sql_query ( $sql, __FILE__, __LINE__ );
            }
            break;
    }
}
function get_sqlwhere() {
    $sql_where = "";
    $keywords=  getgpc("keyword","G");
    if (is_not_blank ($keywords )) {
        if($keywords=='输入搜索关键词'){
            $keywords='';
        }
        $keyword = Database::escape_string ( $keywords, TRUE );
        $sql_where .= " AND (id LIKE '%" . trim ( $keyword ) . "%'
                             OR username LIKE '%" . trim ( $keyword ) . "%'
                             OR sign_date LIKE '%" . trim ( $keyword ) . "%'
                             OR sign_return_date LIKE '%" . trim ( $keyword ) . "%'
                             OR mode LIKE '%" . trim ( $keyword ) . "%'
                             OR status LIKE '%" . trim ( $keyword ). "%')";
    }

    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}




function get_number_of_work_attendance() {
    $work_attendance = Database::get_main_table ( work_attendance );
        $u_status=$_SESSION['_user']['status']; 
        if($u_status==10){
            $sql = "SELECT COUNT(id) AS total_number_of_items FROM " . $work_attendance;
        }else{
            $sql = "SELECT COUNT(id) AS total_number_of_items FROM " . $work_attendance." WHERE `username`='".$_SESSION['_user']['username']."'";
        }
    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " AND " . $sql_where;
    return Database::getval ( $sql, __FILE__, __LINE__ );
}

function get_work_attendance_data($from, $number_of_items, $column, $direction) {
    $work_attendance = Database::get_main_table ( work_attendance );
    $u_status=$_SESSION['_user']['status']; 
    if($u_status==10){
        $sql = "select  `id`, `username`, `sign_date`, `sign_return_date`, `mode`,`id`,`status` FROM ".$work_attendance ;
     }else{ 
        $sql = "select  `id`, `username`, `sign_date`, `sign_return_date`, `mode`,`id`,`status` FROM  $work_attendance WHERE `username`='".$_SESSION['_user']['username']."'";
     }
    $sql_where = get_sqlwhere ();
    if ($sql_where) $sql .= " AND  " . $sql_where;
    $sql .= " LIMIT $from,$number_of_items";
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $vm= array ();

    while ( $vm = Database::fetch_row ( $res) ) {
        $vms [] = $vm;
    }
    return $vms;
}

$interbreadcrumb [] = array ("url" => 'index.php', "name" => "首页" );
$interbreadcrumb [] = array ("url" => 'user_profile.php', "name" => "用户中心" );
$interbreadcrumb [] = array ("url" => 'work_attendance.php', "name" => "我的考勤" );
//$nameTools="我的课程";
display_tab ( TAB_LEARNING_CENTER );

$tool_name = get_lang ( 'CourseList' );
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();


$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "width:200px", 'class' => 'inputText','value'=>'输入搜索关键词','id'=>'searchkey', 'title' => $keyword_tip ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );


if (isset ( $keywords ) && is_not_blank ( $keywords )) $parameters ['keyword'] = $keywords; 

$table = new SortableTable ( 'work_attendance', 'get_number_of_work_attendance', 'get_work_attendance_data',2, NUMBER_PAGE  );
$table->set_additional_parameters ( $parameters );
$idx=0;
//$table->set_header ( $idx ++, '序号', false );
$table->set_header ( $idx ++, '编号', false  ,null);
$table->set_header ( $idx ++, '用户名', false, null, array ('style' => ' text-align:center;width:15%' ));
$table->set_header ( $idx ++, '签到时间', false, null, array ('style' => ' text-align:center;width:15%' ) );
$table->set_header ( $idx ++, '签退时间', false, null, array ('style' => ' text-align:center;width:15%' ) );
$table->set_header ( $idx ++, '状态', false, null, array ('style' => ' text-align:center;width:10%' ) );
$table->set_header ( $idx ++, '上课时间', false, null, array ('style' => ' text-align:center;width:15%' ) );
$table->set_header ( $idx ++, '状态', false, null, array ('style' => ' text-align:center;width:15%' ) );
$table->set_column_filter ( 4, 'mode_filter' );
$table->set_column_filter ( 5, 'time_filter' );
$table->set_column_filter ( 6, 'status_filter' );
?>
      <?php      if(api_get_setting ( 'lm_switch' ) == 'true'){
                ?>
  <style>
.m-moclist .nav .u-categ .navitm.it a:hover{
	color:#357CD2;
	background:#fff;
} 
.m-moclist .nav .u-categ .navitm.it.course-mess:hover{
    border-right-color: #357CD2;
}
.m-moclist .nav .u-categ .navitm.it.cur:hover .f-f1:hover{
background:#357CD2;
color:#fff;
}
.m-moclist .nav .u-categ .navitm.it.cur:hover .i-mc a:hover{
    color:#357CD2;
}
input[type=submit] {

background: #357CD2;
border: 1px solid #357CD2;
}

  </style>
      <?php   }   ?> 
<div class="clear"></div>
<div class="m-moclist">
    <div class="g-flow" id="j-find-main">
  <!--左侧-->
  <div class="b-30"></div>
	<div class="g-container f-cb">
            <div class="g-sd1 nav">
            <div class="m-sidebr" id="j-cates">
                <ul class="u-categ f-cb">
                    <li class="navitm it f-f0 f-cb first cur" data-id="-1" data-name="用户中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                         <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF" title="用户中心">用户中心</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的足迹" href="my_foot.php" style="color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;font-weight:bold">我的足迹</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="选课记录" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="选课记录" href="course_applied.php">选课记录</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="信息修改" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="信息修改" href="user_profile.php">信息修改</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="密码修改" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="密码修改" href="user_center.php">密码修改</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的考勤" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的考勤" href="work_attendance.php">我的考勤</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="站内信" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="站内信" href="msg_view.php">站内信</a>
                    </li>
                    
                </ul>
                 <ul class="u-categ f-cb" style="margin-top:15px;">
                               <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="学习中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF" title="学习中心">学习中心</a>
                               </li>
                               <?php  
                                $sql="select id,title from setup order by custom_number";
                                  $res=  api_sql_query_array($sql);
                                  foreach ($res as $value) {
                                      ?>
                            <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                            <a class="f-thide f-f1" <?=$value['id']==$id?' style="color:green;font-weight:bold"':''?> title="<?=$value['title']?>" href="<?=URL_APPEND."portal/sp/learning_before.php?id=".$value['id']?>"><?=$value['title']?></a>
                                <div class="i-mc">
                                                    <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                                            <?php    
                                                            $sql1="select subclass from setup where id=".$value['id'];
                                                              $re1=  Database::getval($sql1);
                                                              $rews1=explode(',',$re1);
                                                                  $subclass1='';
                                                                  foreach ($rews1 as $v1) {
                                                                      if($v1!==''){
                                                                         $subclass1[]=$v1; 
                                                                      }
                                                                  }
                                                              $objCrsMng1=new CourseManager();//课程分类  对象。
                                                              $objCrsMng1->all_category_tree = array (); 
                                                              $category_tree1 = $objCrsMng1->get_all_categories_trees ( TRUE,$subclass1);
                                                              $i = 0;   $j = 0;   $o = array(); //标记循环变量， 数组 ;
                                                              foreach ( $category_tree1 as $category ) { ///父类循环
                                                                $url = "learning_before.php?id=".$value['id']."&category=" . $category ['id'];
                                                                  $cate_name = $category ['name'] . (($category_cnt [$category ['id']]) ? "&nbsp;(" . $category_cnt [$category ['id']] . ")" : "");
                                                                  if($category['parent_id']==0) {
                                                                  ?>
                                                                <a class="j-subit f-ib f-thide" href="<?=$url?>"><?=$cate_name?></a>
                                                                  <?php  if($i==3){$i=0;}
                                                                    }  
                                                                 }
                                                                  if(!$category_tree1){    
                                                                      echo "<p align='center'>没有相关课程分类，请联系课程管理员</p>";
                                                                  }
                                                                  ?>

                                                        </div>
                                                </div>

                                        </li>
                                        
                                       
                               <?php  }  ?>
                                <li class="navitm it f-f0 f-cb haschildren course-mess"  data-id="-1" data-name="课程表">
                                     <a class="f-thide f-f1" title="课程表" href="./syllabus.php">课程表</a>
                                </li>
                               </ul>
            </div>
            <div class="m-university u-categ f-cb" id="j-university">
                <div style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">
                   <div class="bar f-cb">
                   <h3 class="f-thide f-f1">报告管理</h3>
                </div>
                <ul class="u-categ f-cb">
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的实验报告" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的实验报告" href="labs_report.php" >我的实验报告</a>
                    </li> 
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的实验图片录像" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的实验图片录像" href="course_snapshot_list.php" >我的实验图片录像</a>
                    </li>
                     <li class="navitm it f-f0 f-cb haschildren couse-mess" data-id="-1" data-name="系统公告" id="auto-id-D1Xl5FNIN6cSHqo0">
                         <a class="f-thide f-f1" title="系统公告" href="announcement.php" >系统公告</a>
                    </li>
                </ul>
               </div>
           </div>       
       </div>


<div class="g-mn1" > 
         <div class="g-mn1c m-cnt" style="display:block;">
    <div class="j-list lists" id="j-list"> 
    <div class="managerSearch" style="width:98.8%;margin-left:0px;">
        <?php $form->display ();?>

    </div>
   
        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">

                <?php $table->display ();?>
            </table>
        </form>
    </div> 
    </div>
    </div>
    </div>
    </div>
</div>
<?php include './inc/page_footer.php'; ?>
</body>
<style type="text/css">
    body{
        min-height:80%;
    }
    #searchkey{
        height:30px;
    }
    
    th{
        text-align:center;
    }
</style>
</html>

