<?php
$cidReset = true;
$cidReset = true;include_once ("inc/app.inc.php");
include_once ("../../main/inc/global.inc.php");
include_once ('../../main/assignment/assignment.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileDisplay.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'document.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'tablesort.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . "export.lib.inc.php");
include_once (api_get_path ( SYS_CODE_PATH ) . 'document/document.inc.php');

include_once ("inc/page_header.php");
display_tab ();
if (api_is_platform_admin () OR api_get_setting('course_center_open_scope')==1) {}

Display::display_thickbox(false,true);
$learn_status = $_GET ['learn_status'] ;
$u_sql="select lastname,mobile,official_code,firstname,email,dept_name from user,sys_dept where user.dept_id=sys_dept.id and  user_id=".$_SESSION ['_user']['user_id'];
$data_list=api_sql_query_one_row($u_sql,__FILE__,__LINE__);
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
                             <a class="f-thide f-f1" title="用户中心" style="background: <?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">用户中心</a>
                        </li>
                        <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                             <a class="f-thide f-f1" title="我的足迹" href="my_foot.php">我的足迹</a>
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
                            <a class="f-thide f-f1" title="我的考勤" href="work_attendance.php" >我的考勤</a>
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
                            <a class="f-thide f-f1" title="我的实验图片录像"  href="course_snapshot_list.php" >我的实验图片录像</a>
                        </li>
                         <li class="navitm it f-f0 f-cb haschildren couse-mess" data-id="-1" data-name="系统公告" id="auto-id-D1Xl5FNIN6cSHqo0">
                             <a class="f-thide f-f1" title="系统公告" style="color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;font-weight:bold" href="announcement.php" >系统公告</a>
                        </li>
                    </ul>
                   </div>
                </div> 
            </div>
              
            <div class="g-mn1" >
                <div class="g-mn1c m-cnt" style="display:block;">
<!--                    <div class="top f-cb j-top">
                        <h3 class="left f-thide j-cateTitle title">
                            <span class="f-fc6 f-fs1" id="j-catTitle">系统公告</span>
                        </h3>
                    </div>-->
                    <div class="u-content">
                         <h3 class="sub-simple u-course-title"></h3> 
                                <table cellspacing="0" border="0" width="100%" class="tbl_course" style="text-align:center;"> 
                                    <tr>
                                        <th >标题</th>
                                        <th >创建用户</th>
                                        <th >发布时间</th>
                                        <th align="center">内容</th>
                                    </tr>
                                    <tr>
                                        <td colspan="10"><h3  class="sub-simple u-course-title"></h3> </td>
                                    </tr>
                                    <?php
                                    $sql="select `id`, `title`,`created_user` ,`date_start`,`content` from `sys_announcement` where  `visible`=1 order by  `date_start` DESC ";
                                    $result = api_sql_query($sql, __FILE__, __LINE__ );
                                    while ( $rst = Database::fetch_row ( $result) ) {
                                    ?>
                                    <tr class="u-course-time"> 
                                        <td ><?=$rst[1] ?></td> 
                                        <td >
                                            <?php
                                            $userName= Database::getval("select `firstname` from `user` where `user_id`=$rst[2]");
                                            if($userName!==''){
                                                echo $userName; 
                                            }else{
                                                echo "&nbsp;"; 
                                            }

                                            ?>
                                        </td>
                                        <td >
                                            <?php
                                            if($rst[1]!==''){
                                                echo $rst[3]; 
                                            }else{
                                                echo "&nbsp;";
                                            }
                                            ?>
                                        </td> 
                                        <td align="center">
                                            <?php
                                             if($rst[4]!==''){
                                               echo link_button ( 'message_normal.gif', '查看内容', 'index1_content.php?id='.$rst[0], '80%', '80%', FALSE ); 
                                             }
                                            ?>

                                        </td> 
                                    </tr>
                                    <tr>
                                        <td colspan="10"><h3  class="sub-simple u-course-title"></h3> </td>
                                    </tr>
                                    <?php 
                                    }
                                    $announcement_count= DATABASE::getval("select count(id)  from `sys_announcement` where  `visible`=1 order by  `date_start` DESC ");
                                    if(!$announcement_count){  ?>
                                    <tr>
                                            <td colspan="4" class="error">没有最新公告信息!</td>
                                    </tr>
                                    <?php  
                                    }
                                    ?>
                            </table>      
                    </div>
                </div>
            </div>      
        </div>
    </div>      
</div>
<?php
    include_once('./inc/page_footer.php');
?>
</body>
</html>