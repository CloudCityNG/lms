<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
Display::display_thickbox(false,true);
$user_id=api_get_user_id ();

$msgcount=  Database::getval("select count(`id`)  from `sys_announcement` where  `visible`=1 ");
//pages---all_notice
 $total_rows = DATABASE::getval("SELECT  count(`id`)   FROM `message` where  `recipient`=".$user_id );
$url = 'my_voice.php';
$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );

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
.m-msglist .tabarea a.cur{
    background:#357cd2;
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
                        <a class="f-thide f-f1" title="我的消息" href="my_voice.php" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">我的消息</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的赛场" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的赛场" href="my_contest.php">我的赛场</a>
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
 
    <div class="g-mn1" > 
    <div class="g-mn1c m-cnt" style="display:block;">
    <div class="j-list lists" id="j-list" style="margin-bottom:40px;clear:both;"> 
       <div class="u-content" style="border: 1px solid #C5C5C5;box-shadow: 0 1px 6px #999;">
           <div class="m-msglist">
               <div class="tabarea j-tabs">
                   <a id="big-type-all" class="a-tab cur">全部通知</a>
                   <a id="big-type-sys" class="a-tab">
                       系统通知
                       <div class="msgcou" title="有未读通知"><?=$msgcount?></div>
                   </a>
               </div>
               <div class="tabcon" id="j-all-box">
                   <div class="dobar f-cb m-person-course" id="all-notice">
                       <?php   
                    $sql = "SELECT `id`,`created_user`,`date_start`   FROM `message` where  recipient=".$user_id."   GROUP BY `created_user` ORDER BY `message`.`date_start` DESC";  
                    $offset = (int)getgpc ( "offset", "G" );
                    if (empty ( $offset )) $offset = 0;
                    $sql .= " LIMIT " . $offset . "," . NUMBER_PAGE;
                    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                    $ress = array ();
                    while ( $ress = Database::fetch_row ( $res ) ) { 
                        $cu=$ress[1];
                        $firsname=Database::getval("select `username` from `user` where `user_id`=$ress[1]");
                        ?>
                        <div class="m-data-lists msg-cnt f-cb">
                           <p class="msg-txt">
                               您好，‘<?=$firsname?>’向您发送消息！  <?php  echo  link_button ( 'announce_add.gif', '', 'msg_show.php?created_user=' .$cu , '80%', '60%', TRUE ); ?>
                           </p>
                           <div class="msg-time"><?=$ress[2]?></div>
                       </div>
                            <?php  } ?>
                       <div class="j-data-pager">
                       <div class="page">
                          <ul class="page-list">
                              <li class="page-num">总计<?=$total_rows?>个课程</li>
                              <?php
                              echo $pagination->create_links ();
                              ?>
                          </ul>
                      </div>
                       </div>
                   </div>
                      <div class="dobar f-cb m-person-course" id="sys-notice" style="display:none;">
                <?php
                    $sql="select `id`, `title`,`created_user` ,`date_start`,`content` from `sys_announcement` where  `visible`=1 order by  `date_start` DESC ";
                    $result = api_sql_query($sql, __FILE__, __LINE__ );
                    while ( $rst = Database::fetch_row ( $result) ) {  
                 ?>
                       <div class="m-data-lists msg-cnt f-cb">
                           <p class="msg-txt">
                               <?=$rst[1] ?>
                                <?php
                                if($rst[4]!==''){
                                  echo link_button ( 'message_normal.gif', '查看内容', 'index1_content.php?id='.$rst[0], '80%', '80%', FALSE ); 
                                }
                               ?>
                           </p>
                           <div class="msg-time"><?=$rst[3] ?></div>
                       </div>
                  <?php  } ?>
<!--                       <div class="j-data-pager">
                        <div class="ju-pager">
                            <a href="my_voice.php?page=1" class="zbtn zprv"> &lt;&lt; </a> 
                            <a href="my_voice.php?page=1" class="zpgi zpg1">1</a>  
                            <a href="my_voice.php?page=2" class="zpgi zpg1 selected">2</a>  
                            <a href="my_voice.php?page=3" class="zpgi zpg1">3</a>  
                            <a href="my_voice.php?page=2" class="zbtn zprv">  &gt; </a>  
                            <a href="my_voice.php?page=46" class="zbtn zprv">  &gt;&gt; </a>     
                        </div>
                       </div>-->
                   </div>
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
include './inc/page_footer.php';
?>
</body>
</html>


