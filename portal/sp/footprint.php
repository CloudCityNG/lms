<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
$user_id=api_get_user_id ();
//学习足迹
$pa=  getgpc("page");
if($pa){
    $page=$pa;
}else{
    $page=1;
}
$pagesize=5;
$start=($page-1)*$pagesize;
$page_url="footprint.php";

$sql="select  `lesson_id`,`vmid`,`start_time`,`end_time`,`close_status`  from  `vmdisk_log`  where    `user_id`=".$user_id."  order by  id  desc  limit  {$start} , {$pagesize}"; 
$course_vms=  api_sql_query_array_assoc($sql);
$count_sql= $sql="select  `lesson_id`,`vmid`,`start_time`,`end_time`,`close_status`  from  `vmdisk_log`  where    `user_id`=".$user_id."  order by  id  desc";
$crs_vm=  api_sql_query_array_assoc($sql);
$counts=count($crs_vm);   
$pages=  ceil($counts/$pagesize);   

?>
<style>
    body{
        color:#444;
    }
    .la{color:#444;}
  input{color:#444;}
  .sp{border-right: 1px #ccc solid;padding-right: 3px;}
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
  </style>
      <?php   }   ?> 
<div class="clear"></div>
<div class="m-moclist">
    <div class="g-flow" id="j-find-main">
           <div class="b-30"></div>
          <!--左侧-->
   <div class="g-container f-cb">
        <div class="g-sd1 nav">
            <div class="m-sidebr" id="j-cates">
                <ul class="u-categ f-cb">
                    <li class="navitm it f-f0 f-cb first cur" data-id="-1" data-name="用户中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                         <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF" title="用户中心">用户中心</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的岗位" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的岗位" href="my_post.php">我的岗位</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="最新动态" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="最新动态" href="c_trends.php">最新动态</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的消息" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的消息" href="my_voice.php">我的消息</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的赛场" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的赛场" href="my_contest.php">我的赛场</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的考勤" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的考勤" href="work_attendance.php">我的考勤</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="学习足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="学习足迹" href="footprint.php" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">学习足迹</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="登录足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="登录足迹" href="login_print.php">登录足迹</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的报告" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的报告" href="labs_report.php">我的报告</a>
                    </li>
                    
                </ul>
                 
            </div>
            
       </div>
 
    <div class="g-mn1" > 
    <div class="g-mn1c m-cnt" style="display:block;">
    <div class="j-list lists" id="j-list" style="margin-bottom:40px;clear:both;"> 
        <div class="userContent">

            <div id="j-all-box">
                 <div class="tabarea j-tabs">
                   <a class="a-tab">已学课程</a>
               </div>
            <div class='j-all-box m-person-course' id="learn-foot">
                <div class='m-data-lists f-cb f-pr j-data-list'>
                    <?php  
                   foreach ($course_vms  as  $v){    
                         //在学人数
                       $sql="select  `user_id`  from  `vmdisk_log`  where  `lesson_id`='".$v['lesson_id']."'"; 
                       $us=  api_sql_query_array_assoc($sql);
                       $uss='';
                       foreach ($us  as  $val){
                           $uss[]=$val['user_id'];
                       }
                       $user_count=count(array_unique($uss));
                       //course-picture
                       $c_cate=  Database::getval("select  `category_code`  from  `course`  where  `code`='".$v['lesson_id']."'");
                       $img=  Database::getval("select  `code`  from  `course_category` where  `id`=".$c_cate);
                       $sql_pic="select  `description9`  from  `course`  where  `code`='". $v['lesson_id']."'";
                        $pic=DATABASE::getval($sql_pic);
                        if($pic){
                            $course_pic=api_get_path ( SYS_PATH ).'storage/courses/'. $v['lesson_id'].'/'.  $pic; 
                            $file_exists=file_exists($course_pic);
                        }
                        if($pic  &&   $file_exists){
                          $imgpath=$course_pic; 
                        }else if($img  && file_exists('../../storage/category_pic/'.$img) ){  
                          $imgpath='../../storage/category_pic/'.$img;  
                        }else{
                           $imgpath= "../../portal/sp/images/default.png";
                        }
                    ?>
                    <div class='u-centerCourse f-cb first'> 
                        <div class='courseImg  f-pr'>
                            <img src='<?=$imgpath?>' class="j-info img">
                        </div>
                        <div class="m-course">
                            <div class='tit f-cb'>
                                <h4 class='j-info courseTit  f-thide'><?php  echo Database::getval("select  `title`  from  `course`  where  `code`=".$v['lesson_id']); ?></h4>
                            </div>
                            <ul class='j-info1'>
                                <li class='li-1 f-ib'>实验场景:  <span><?php  echo Database::getval("select  `name`  from  `vmdisk`  where  `id`=".$v['vmid']); ?></span> </li>
                                <li class='j-enroll li-1  f-ib'>
                                     <span class="numImg f-ib"></span>
                                     <span class="c-num0"><?=$user_count?>人在学</span>
                                </li>
                            </ul>
                            <div>
                                <div>
                                    <span class="sp">登录场景时间：<?php    echo  $v['start_time']; ?></span>
                                    <span class="sp">销毁场景时间：<?php    echo  $v['end_time']; ?></span>
                                    <span>销毁场景状态：<?php  if($v['close_status']==0){ echo "用户关闭";}else if($v['close_status']==1){echo "系统关闭";}else{echo "超时关闭";}  ?></span>
                                </div>
                            </div>
                            <!--<div class="j-join joinTime">2014年8月9日加入学习</div>-->
                        </div>
                    </div>
                    <?php   }  ?>
                </div>
                  
                <?php  
                showpage($pages,$page_url,$page); 
                ?>
            </div>
            
       </div>
     </div>
    </div>
    </div>
    </div>
    </div>
</div>
</div>
<?php 
//fen-ye
function showpage($pages,$page_url,$page){    
    if($pages=="NULL"){
        //echo "没有相关记录";
    }else{
        echo  ' <div class="j-data-pager">
                    <div class="ju-pager">';
        if($pages>10){
            echo "<a href=$page_url?page=1  class='zbtn zprv'> << </a>  ";
            if($page-1>0){
              echo "<a href=$page_url?page=".($page-1)." class='zbtn zprv'> < </a>  ";  
            }  
            $start=$page-5;
            $end=$page+5;          
            if($start >0 &&  $end<=$pages){
                for($j=$start;$j<$end;$j++){
                  echo "<a href=$page_url?page=$j   class='zpgi zpg1 ".($j==$_GET['page']?'selected':'')."'>".$j."</a>  ";  
                }
            }else if($start <=0){
                for($j=1;$j<11;$j++){
                  echo "<a href=$page_url?page=$j   class='zpgi zpg1 ".($j==$_GET['page']?'selected':'')."'>".$j."</a>  ";  
                }
            }else  if($end>$pages){
                 for($j=$pages-9;$j<=$pages;$j++){
                  echo "<a href=$page_url?page=$j  class='zpgi zpg1 ".($j==$_GET['page']?'selected':'')."'>".$j."</a>  ";  
                }
            } 
            if($page+1<$pages){
              echo "<a href=$page_url?page=".($page+1)." class='zbtn zprv'>  > </a>  ";  
            } 
           echo "<a href=$page_url?page=".$pages."  class='zbtn zprv'>  >> </a>      ";
         }else{
             echo "<a href=$page_url?page=1  class='zbtn zprv'>  << </a>  ";
             if($page-1>0){
                echo "<a href=$page_url?page=".($page-1)."   class='zbtn zprv'> < </a>  ";  
             }
             for($j=1;$j<=$pages;$j++){
                echo "<a href=$page_url?page=$j  class='zpgi zpg1 ".($j==$_GET['page']?'selected':'')."'>".$j."</a>  ";  
             }
             if($page+1<=$pages){
                echo "<a href=$page_url?page=".($page+1)."  class='zbtn zprv'> > </a>  ";  
             } 
             echo "<a href=$page_url?page=".$pages."  class='zbtn zprv'>  >> </a>      ";
         }
         echo '</div></div>';
      }
}

include './inc/page_footer.php';
?>
</body>
</html>
