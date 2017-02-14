<?php
header("content-type:text/html;charset=utf-8");
require_once ('../../main/inc/global.inc.php');
include_once ("inc/app.inc.php");
include_once ("inc/page_header.php");
include_once("../../main/inc/lib/main_api.lib.php");
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
 
$user_id = api_get_user_id ();//获取用户id
$sel=addslashes(htmlspecialchars($_POST['auto-id-rTOGAi3MiQOM7HrB']));
$sel1=getgpc('sel','G');
//超级管理员删除
if(isset($_GET['lessonid'])&&isset($_GET['status'])&&isset($_GET['s_user_id'])){
    $lessonid=$_GET['lessonid'];
    $s_user_id=$_GET['s_user_id'];
    $sql_filename="select filename from snapshot where `user_id` = '$s_user_id' and `lesson_id`='$lessonid'";
    $result= api_sql_query ( $sql_filename, __FILE__, __LINE__ );
    while($vm = Database::fetch_row ( $result)){
          $filename=$vm[0];
          exec("sudo rm -rf ".URL_ROOT."/www".URL_APPEDND."/storage/snapshot/".$filename."*");    
          }
    $sql="DELETE FROM `snapshot` WHERE `user_id` = '$s_user_id' and `lesson_id`='$lessonid'" ;
    api_sql_query ( $sql, __FILE__, __LINE__ );
}

//普通用户删除
if(isset($_GET['lessonid'])&&isset($_GET['user_id'])){
    $lessonid=$_GET['lessonid'];
    $s_user_id=$_GET['user_id'];
    $sql_filename="select filename from snapshot where `user_id` = '$s_user_id' and `lesson_id`='$lessonid'";
    $result= api_sql_query ( $sql_filename, __FILE__, __LINE__ );
    while($vm = Database::fetch_row ( $result)){
          $filename=$vm[0];
          exec("sudo rm -rf ".URL_ROOT."/www".URL_APPEDND."/storage/snapshot/".$filename."*");  
          }
    $sql="DELETE FROM `snapshot` WHERE `user_id` = '$user_id' and `lesson_id`='$lessonid'" ;
    api_sql_query ( $sql, __FILE__, __LINE__ );
}

//一键清空
if(isset($_GET['delete'])&&isset($_GET['user_id'])&&($_GET['user_id'])!==5){
    //清空snapshot表
    $sql="truncate table `snapshot` ";
    $res=api_sql_query ( $sql, __FILE__, __LINE__ );
    //echo $res;
    if($res){
       //清空截屏录屏文件
        exec("sudo rm -rf ".URL_ROOT."/www".URL_APPEDND."/storage/snapshot/");
    }
    $url = "course_snapshot_list.php";
    echo "<script language='javascript' type='text/javascript'>";
    echo "window.location.href='$url'";
    echo "</script>";
}

$vm = Database::getval ( "select status from user where user_id='$user_id'", __FILE__, __LINE__ );  
$status=$vm[0];

//$objStat = new ScormTrackStat ();

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
                            <a class="f-thide f-f1" title="我的实验报告"   href="labs_report.php" >我的实验报告</a>
                        </li> 
                        <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的实验图片录像" id="auto-id-D1Xl5FNIN6cSHqo0">
                            <a class="f-thide f-f1" title="我的实验图片录像" style="color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;font-weight:bold" href="course_snapshot_list.php" >我的实验图片录像</a>
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
                
<!--                <div class="top f-cb j-top">
                    <h3 class="left f-thide j-cateTitle title">
                        <span class="f-fc6 f-fs1" id="j-catTitle">我的实验图片录像</span>
                    </h3>
                </div>-->
    <div class="j-list lists" id="j-list"> 
          <div class="u-content">
              <h3 class="sub-simple u-course-title"></h3>
            <table cellspacing="0" border="0" width="100%" class="tbl_course"> 
                <tr>
                   <th height=50>&nbsp;&nbsp;编号</th>
                   <th>课程名称</th>
                   <th>用户</th>
                   <th>图片数量</th>
                   <th>录像数量</th>
                   <th>查看图片</th>
                   <th>查看录像</th>
                   <th>编辑</th>
                </tr> 
                <tr>
                    <td colspan="10"><div  class="sub-simple u-course-title"></div> </td>
                </tr>
    <?php 
    if($status==5){
         $sql="select lesson_id,user_id from snapshot where user_id='$user_id' ";
    }else{
         $sql="select lesson_id,user_id from snapshot where 1"; 
    } 
    if($sel){  
        $sqlwheree="  `title` LIKE '%" . trim($sel) . "%'";
    }else if($sel1){
        $sqlwheree="  `title` LIKE '%" . trim($sel1) . "%'";
    }
    if($sqlwheree){
        $sql .=" and lesson_id in(SELECT `code` FROM `course` WHERE ".$sqlwheree.")";
    }
//     if($status==5){
//         $sql .=" order by lesson_id";
//    }else{
//         $sql .=" order by user_id";
//    }
    $sql.=" group by lesson_id";
    $personal_list= api_sql_query_array_assoc ( $sql, __FILE__, __LINE__ );
    $total_rows = intval(count($personal_list));
    if($sel){$parms="sel=".$sel;}
    if($sel1){$parms="sel=".$sel1;}
    $url = WEB_QH_PATH . "course_snapshot_list.php?".$parms;
    $pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
    $pagination = new Pagination ( $pagination_config );
    $offset='';
    if( $_GET['offset']==''){
        $offset=0;
    }else{
        $offset=getgpc ( "offset", "G" );
    }
    $sql .= " LIMIT " . $offset . "," . NUMBER_PAGE;
    $ress= api_sql_query ( $sql, __FILE__, __LINE__ );
    while($vm = Database::fetch_row ( $ress)){
          $vms[]=$vm;  
          }
 
    $lesson_num=count($vms);
    for($i=0;$i<$lesson_num;$i++){
        $lesson_id=$vms[$i];
        $sql="select title from course where code=$lesson_id[0]";
        if($sqlwhere)  $sql .=$sqlwhere;
        $title = Database::getval ( $sql, __FILE__, __LINE__ );  
        $sqla="select count(*) from snapshot where user_id='$user_id' and lesson_id='$lesson_id[0]' and type='1'";
        $num1 = Database::getval ( $sqla, __FILE__, __LINE__ );  
        $sqlb="select count(*) from snapshot where user_id='$user_id' and lesson_id='$lesson_id[0]' and type='2' and status=0";
        $num2 = Database::getval ( $sqlb, __FILE__, __LINE__ );  
        ?>
            <tr>								
                <td  height=50>&nbsp;&nbsp;<?php echo $i+1+$offset;?></td>
                <td><?php echo $title;?></td>
                <td><?php  echo Database::getval("select  username from user where  user_id=". $lesson_id[1],__FILE__,__LINE__);  ?></td>
                <td><?php echo $num1[0];?></td>
                <td><?php echo $num2[0];?></td>
                <td>
                    <?php
                   $count1 =intval($num1[0]);
                    if($count1>0){?>
                    <a href="course_snapshot_content.php?type=1&lessonid=<?php echo $lesson_id[0]; ?>&userid=<?php echo $user_id; ?>&status=<?php echo $status;?>">
                        <img src="../../themes/img/message_normal.gif" height="24px" width="24px" align="center">
                    </a>
                  <?php 
                  }else{
                    echo ' <img src="../../themes/img/message_normal.png" height="24px" width="24px" align="center">';
                      } ?>
                </td> 
                <td>
                    <?php
                   $count2 =intval($num2[0]);
                    if($count2>0){?>
                    <a href="course_snapshot_content.php?type=2&lessonid=<?php echo $lesson_id[0]; ?>&userid=<?php echo $user_id; ?>&status=<?php echo $status;?>">
                        <img src="../../themes/img/message_normal.gif" align="center">
                    </a>
                  <?php 
                  }else{
                    echo ' <img src="../../themes/img/message_normal.png" height="24px" width="24px" align="center">';
                      } ?>

                </td>
                <td><a href="course_snapshot_list.php?lessonid=<?= $lesson_id[0] ?>&user_id=<?= $user_id ?>"><img src="../../themes/img/delete.gif" align="center"></a></td>
            </tr> 
            <tr>
               <td colspan="10"><div  class="sub-simple u-course-title"></div> </td>
           </tr>
        <?php                
     } ?>
        </table>
            <?php if($i){ ?>
            <div class="page">
                <ul class="page-list"><li class="page-num">总计<?=$total_rows?> 条记录</li><?php  echo $pagination->create_links (); ?> </ul>
            </div>
            </div> <?php }else{?>
                <div class="error">没有相关记录</div>
            <?php } ?>
       </div> </div> </div>
   </div>
    </div>
</div>

<?php  include_once './inc/page_footer.php'; ?>
