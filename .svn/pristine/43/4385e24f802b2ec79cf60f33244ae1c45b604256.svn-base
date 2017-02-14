<?php

include_once ("inc/app.inc.php");
$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();
include_once ("inc/page_header.php");

$user_list = WhoIsOnline ( api_get_user_id (), null, api_get_setting ( 'time_limit_whosonline' ) );
$total = count ( $user_list );
        

$tbl_attachment = Database::get_main_table ( TABLE_MAIN_SYS_ATTACHMENT );
$sys_attachment_path = api_get_path ( SYS_ATTACHMENT_PATH );
$http_www = api_get_path ( WEB_PATH ) . $_configuration ['attachment_folder'];

$id = getgpc ( 'id' );

//$tool_name = get_lang ( 'SystemAnnouncements' );
//  $htmlHeadXtra [] = Display::display_thickbox ();
//Display::display_header ( $tool_name ); 



$form_action = getgpc ( "action" );

function edit_filter($id, $url_params) {
    global $_configuration, $root_user_id;
    $created_user=Database::getval("select created_user from message where id=$id");
    $result ='';
    $result.=link_button ( 'announce_add.gif', "查看消息", 'msg_show.php?created_user=' .$created_user , '80%', '60%',FALSE );
    return $result;
}
function action_filter($id, $url_params) {
    global $_configuration, $root_user_id;
//    $created_user=Database::getval("select created_user from message where id=$id");
    $result ='';
//    $result.=link_button ( 'announce_add.gif', "查看消息", 'msg_show.php?created_user=' .$created_user , '90%', '60%',FALSE );
    //当状态为隐藏时可以编辑，显示时不能编辑
    $sql1="select visible from sys_announcement where id=$id";
    $visible=Database::getval($sql1);
    if (! in_array ( $id, $root_user_id )) {
        $result .= '&nbsp;' . confirm_href ( 'delete.gif', 'ConfirmYourChoice', '清除会话', 'msg_view.php?action=delete&id=' .$id );
    }

    return $result;
}
//批量处理
if (isset ( $_POST ['action'] )) { 
    switch ($_POST ['action']) {
        case 'deletes' :
            $number_of_deleted_users = 0;
            foreach ($_POST['id']   as $index => $id ) {
//                $content= DATABASE::getval("select content from message where id =".$id,__FILE__,__LINE__);
                $sqlm=  "select  created_user,recipient  from message where  id='".intval($id)."'";
                $message_arrs=api_sql_query_array_assoc ( $sqlm,__FILE__,__LINE__);
                if($message_arrs[0]['created_user']!=='' && $message_arrs[0]['recipient']!=='' ){
                    $sql= "DELETE FROM `message` WHERE  `created_user`=".$message_arrs[0]['created_user']." and `recipient`=".$message_arrs[0]['recipient'];
                    //echo $sqld;
                    $res1=api_sql_query ( $sql, __FILE__, __LINE__ );
                    if($res1){
                        tb_close('msg_view.php');
                    }
                }
                
            }
            break;
    }
}
if ($_GET ['id']!=='' && $_GET ['action']=='delete') {
                $sql_select=  "select  created_user,recipient  from message where  id='".intval(getgpc('id','G'))."'";
                $message_arr=api_sql_query_array_assoc ( $sql_select,__FILE__,__LINE__);
//                var_dump($message_arr);
                if($message_arr[0]['created_user']!=='' && $message_arr[0]['recipient']!=='' ){
                    $sql_delete= "DELETE FROM `message` WHERE  `created_user`='".$message_arr[0]['created_user']."' and `recipient`='".$message_arr[0]['recipient']."'";
//                    echo $sql_delete;
                    $res=api_sql_query ( $sql_delete, __FILE__, __LINE__ );
//                    echo $res;
                    if($res){
                        tb_close('msg_view.php');
                    }
                }
}
      
$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{label}&nbsp;{element}</span> ' );
$form->addElement ( 'text', 'keyword', null, array ('style' => "width:200px", 'title' => '内容/时间','class' => 'inputText','id'=>'searchkey','value'=>'输入搜索关键词' ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );

//新增按钮

//列表
$sql_where = "";
if (isset ( $_GET ['keyword'] )) {
    if($_GET ['keyword']=='输入搜索关键词'){
        $_GET ['keyword']='';
    }
	$keyword = trim ( Database::escape_str ( $_GET ['keyword'], TRUE ) );
	if (! empty ( $keyword )) {
		$sql_where .= " date_start LIKE '%" . $keyword . "%'
                                or content LIKE '%" . $keyword . "%'
                                or id LIKE '%" . $keyword . "%'";
	}
}
       $usid=api_get_user_id ();  
       
       function read_filter($created_user){
           global $usid;
           $sql="select count(*) from message where created_user=$created_user and recipient=$usid and status=1";
           $count=Database::getval($sql);
           
           return $count;
       }
       
       function unread_filter($created_user){
           global $usid;
           $sql="select count(*) from message where created_user=$created_user and recipient=$usid and status=0";
           $count=Database::getval($sql);
           
           return $count;
       }
       
//获取系统公告数据 
    $sql = "SELECT `id`,`created_user`, `created_user`,`created_user`,  `id` ,  `id` 
        FROM `message` where recipient=$usid"; 
    if ($sql_where) $sql .= " WHERE " . $sql_where;
    $sql.=' GROUP BY `created_user` ORDER BY `message`.`date_start` DESC' ;
    //echo $sql;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $ress = array ();
    while ( $ress = Database::fetch_row ( $res ) ) {
        //获取公布者
        $cu=$ress[1];
        $firsname=Database::getval("select `firstname` from `user` where `user_id`=$ress[1]");
        $ress[1]=link_button ( '', "$firsname", 'msg_show.php?created_user=' .$cu , '90%', '60%', TRUE );
        $announcement_data [] = $ress;
    }

$table = new SortableTableFromArray ( $announcement_data, 2, NUMBER_PAGE, 'array_system_announcements' );

$idx = 0;
$table->set_header ( $idx ++, '', false );
$table->set_header ( $idx ++, get_lang ( '发信人' ), false, null, array ('style' => 'width:15%' ) );
$table->set_header ( $idx ++, get_lang ( '已读' ), false   , null, array ('style' => 'width:20%' ) );
$table->set_header ( $idx ++, get_lang ( '未读' ), false, null, array ('style' => 'width:20%' ) );
$table->set_header ( $idx ++, get_lang ( '查看消息' ), false, null, array ('style' => 'width:20%' ) );
$table->set_header ( $idx ++, get_lang ( 'Actions' ), false, null, array ('style' => 'width:20%' ) );
//$table->set_column_filter ( 1, 'look_filter' );
$table->set_column_filter ( 2, 'read_filter' );
$table->set_column_filter ( 3, 'unread_filter' );
$table->set_column_filter ( 4, 'edit_filter' );
$table->set_column_filter ( 5, 'action_filter' );

$actions = array ('deletes' => get_lang ( 'BatchDelete' ) );
$table->set_form_actions ( $actions );

if($platform==3){
    $nav='system';
}else{
    $nav='systeminfo';
}
?>
<!--<aside id="sidebar" class="column msg open">
    <div id="flexButton" class="closeButton close"></div>
</aside>
--><style>
    .p-table{
        width:100%;
    }
</style>
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
                            <h3 class="sub-simple u-course-title"></h3> 
                            <?php $table->display ();?>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include_once './inc/page_footer.php';
?>
</body>
</html>