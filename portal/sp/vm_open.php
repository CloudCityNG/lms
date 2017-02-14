<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
$user_id=api_get_user_id ();
  
 $sql = "select `addres`,`proxy_port`,`system`,`lesson_id` FROM  `vmtotal` where `user_id`= '{$user_id}' and  `manage`='0'"; 
 $vms=  api_sql_query_array_assoc($sql, __FILE__, __LINE__);
 
?>
<style>
    body{
        color:#444;
    }
    .la{color:#444;}
  input{color:#444;}
  .sp{border-right: 1px #ccc solid;padding-right: 3px;}
</style>
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
                         <a class="f-thide f-f1" style="background-color:#13a654;color:#FFF" title="用户中心">用户中心</a>
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
                        <a class="f-thide f-f1" title="学习足迹" href="footprint.php">学习足迹</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="登录足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="登录足迹" href="login_print.php">登录足迹</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的报告" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的报告" href="labs_report.php">我的报告</a>
                    </li>
                     <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="开启虚拟机" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="开启虚拟机" href="vm_log.php" style="background-color:#13a654;color:#FFF">开启虚拟机</a>
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
                   <a class="a-tab">您已开启的虚拟机</a>
               </div>
               <?php if(count($vms)>0){ ?>
               <div class="vm-table">
               <table cellspacing="0" border="0" width="100%" class="tbl_course"> 
                                    <tbody><tr>
                                        <th width="10%" align="center">序号</th>
                                        <th width="40%">课程名称</th> 
                                        <th width="30%">虚拟机名称</th> 
                                        <th width="20%" align="center">打开虚拟机</th>
                                        <!--<th width="20%" align="center">关闭虚拟机</th>-->
                                    </tr>
                            <?php 
                            $local_addres  = $_SERVER['HTTP_HOST'];
                            $local_addresx = explode(':',$local_addres);
                            $local_addresd = $local_addresx[0];
                            foreach ($vms  as   $k=>$value){
                            ?>
                                    <tr>
                                        <td colspan="10"><h3 class="sub-simple u-course-title"></h3> </td>
                                    </tr>
                                  <tr>
                                        <td align="center"><?=$k+1?></td>
                                        <td><?=  Database::getval("select  title  from  course  where  code='".$value['lesson_id']."'")?></td>
                                        <td><?=$value['system'] ?></td>
                                        <td align="center">
                                            <a href="<?php  echo 'http://'.$local_addres.'/lms/main/html5/auto.php?lessonId='.$value['lesson_id'].'&host='.$local_addresd.'&port='.$value['proxy_port'].'&system='.$value['system'].'&sign=startvm';?>" target="_blank">
                                                <img src="images/open.png" width="20" height="22">
                                            </a>
                                        </td>
<!--                                        <td align="center">
                                            <a href="#">
                                                <img src="images/close.png" width="20" height="22">
                                            </a>                                     
                                        </td>-->
                                 </tr>
                                 <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="5" >
                                            <span class="l">
                                                总计：<strong><?=count($vms)?></strong> 条记录
                                            </span>
                                            <span class="num">
                                                <ul class="pages">
                                                </ul>
                                            </span>  
                                        </td>
                                    </tr>
                               </tfoot>
                         </table>
     </div>
               <?php }else{  echo  "<div  style='color:red;font-weight:bolod;'>您好，目前您没有开启虚拟机！！</div>";   } ?>
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


