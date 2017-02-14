<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
$user_id=api_get_user_id ();
 //page
 $total_rows = DATABASE::getval("SELECT  count(`user_ip`)   FROM `vmdisk_log` where  `user_id`=".$user_id );
$url = 'vm_log.php';
$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );

$sql = "select `user_ip`,`addres`,`system`,`lesson_id`,`mac_id`,`proxy_port`,`close_status`,`start_time`,`end_time` FROM  vmdisk_log  where  user_id=".$user_id;
$offset = (int)getgpc ( "offset", "G" );
if (empty ( $offset )) $offset = 0;
$sql .= " LIMIT " . $offset . "," . NUMBER_PAGE;
$logs=  api_sql_query_array_assoc($sql, __FILE__, __LINE__);
 

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
                     <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="虚拟机日志" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="虚拟机日志" href="vm_log.php" style="background-color:#13a654;color:#FFF">虚拟机日志</a>
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
                   <a class="a-tab">虚拟机日志</a>
               </div>
               <div class="vm-table">
             <table class="p-table">
	      <tbody>
            <tr style="background-color: rgb(240, 240, 240);">
                                        <th style="width:4%;">用户ip</th>
		<th style="width:5%;">虚拟机ip</th>
		<th style="width:8%;">虚拟机名称</th>
		<th style="width:16%;">课程名称</th>
		<th style="width:7%;">mac地址</th>
		<th style="width:3%;">端口</th>
		<th style="width:6%;">关闭状态</th>
		<th style="width:8%;">开启时间</th>
		<th style="width:8%;">关闭时间</th>
	</tr>
        <?php   
        foreach ($logs  as  $val){  
        ?>
	<tr>
                                        <td><?=$val['user_ip']?></td>
		<td><?=$val['addres']?></td>
		<td><?=$val['system']?></td>
                                        <td><?php  echo Database::getval("select  title  from  course  where code='".$val['lesson_id']."'")?></td>
		<td><?=$val['mac_id']?></td>
		<td><?=$val['proxy_port']?></td>
		<td><?=($val['close_status']==1?"系统关闭":($val['close_status']==0?"用户关闭":"未知"))?></td>
		<td><?=$val['start_time']?></td>
		<td><?=$val['end_time']?></td>
	</tr>
        <?php  }  ?>
     </tbody>
  </table>
     </div>
      </div> 
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


