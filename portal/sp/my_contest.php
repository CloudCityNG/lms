<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
$user_id=api_get_user_id ();
 
$sql="SELECT  `id`,`matchName`  FROM  `tbl_contest` WHERE  `status`=1 ";    
$matchs=  api_sql_query_array_assoc($sql, __FILE__, __LINE__);
 
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
.job-description  h4.t-h4{
        color:#357CD2;
    }
    .job-description .contest-b{
        background:#357CD2;
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
                        <a class="f-thide f-f1" title="我的赛场" href="my_contest.php"  style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">我的赛场</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的考勤" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的考勤" href="work_attendance.php">我的考勤</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="学习足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="学习足迹" href="footprint.php">学习足迹</a>
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
 
    <div class="g-mn1"> 
    <div class="g-mn1c m-cnt" style="display:block;">
    <div class="j-list lists" id="j-list" style="margin-bottom:40px;clear:both;"> 
       <div class="u-content" style="border: 1px solid #C5C5C5;box-shadow: 0 1px 6px #999;">
           <div class="m-msglist">
               <div class="tabarea j-tabs">
                   <a class="a-tab">正在参加的赛事</a>
               </div>
               <?php  
        foreach ($matchs  as  $value){ 
            $sql="SELECT  count(`tbl_match`.`event_id`)  FROM `tbl_match`  join  `tbl_event`  on  `tbl_match`.`event_id`=`tbl_event`.`id`      WHERE  `tbl_match`.`user_id`={$user_id}   and   `tbl_match`.`Even_tion`=2  and   `tbl_event`.`matchId`=".$value['id'];
            $right_num=  Database::getval($sql);
            $sql="SELECT  count(`tbl_match`.`event_id`)  FROM `tbl_match`  join  `tbl_event`  on  `tbl_match`.`event_id`=`tbl_event`.`id`      WHERE  `tbl_match`.`user_id`={$user_id}   and   `tbl_match`.`Even_tion`=3  and   `tbl_event`.`matchId`=".$value['id'];
            $wrong_num=  Database::getval($sql);
               ?>
               <div class="job-description">
                   <h4 class="t-h4"><?=$value['matchName']?></h4>
                   <div class="contest-dt">
                       <ul>
                           <li>总题数：<?=  Database::getval("SELECT count(`id`) FROM `tbl_event` WHERE  `matchId`=".$value['id'])?></li>
                           <li>正确：<?=$right_num?></li>
                           <li>错误：<?=$wrong_num?></li>
                       </ul>
                   </div>
               <a href="../../main/ctf/index.php?id=<?=$value['id']?>"  class="contest-b" style="padding: 10px  20px;">进入比赛</a>
               </div>
<?php  } ?>
           </div> 
       </div>
    </div>
    </div>
    </div>
    </div>
</div>
</div>
<?php 
include './inc/page_footer.php';
?>
</body>
</html>


