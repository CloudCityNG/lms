<?php
$cidReset = true;
include_once ("inc/app.inc.php"); 
include_once './inc/page_header.php';   
$u_id=  api_get_user_id();

//user-face
  $user_img=$_SESSION['_user']['picture_uri'];
  $userpath=api_get_path ( WEB_PATH ) . 'storage/users_picture/';
  $user_image=$user_img ? $userpath.$user_img : URL_APPEND."portal/sp/images/user-small.jpg";
 //learn-course-number
  $sql="select  count(`course_code`)   from  `course_rel_user`  where  `user_id`=".$u_id;
  $course_num=  Database::getval($sql);
  //vmtotal
  $sql="select  count(`system`)  from  `vmtotal` where `user_id`=".$u_id;
  $vmtotal=  Database::getval($sql);
  //login-count
  $sql="SELECT  count(`login_ip`)  FROM `track_e_login`  where  `login_user_id`=".$u_id;
  $login_num=  Database::getval($sql);
  //vm-log
  $sql="select  count(`id`)   from  `vmdisk_log`   where  `user_id`=".$u_id;
  $vm_num=  Database::getval($sql);
  //report
  $sql="select  count(id)   from  report  where  user='".  api_get_user_name()."'";
  $report_num=  Database::getval($sql);
  
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
<div class="clear"></div> 
<div class="m-moclist">
    <div class="g-flow" id="j-find-main" style="width:1200px;"> 
         <div class="my-container">
             <div class="find-top">
                 <div class="b-20"></div>
                 <div class="pro-head">
                   <div  class="pro-inset">
                     <p style="margin-top:-3px;">
                         <img src="<?=$user_image?>" style="width:90px;height:90px; border:1px solid #fff"/>
                     </p>
                     <p class="pro-name">
                         <span id="proName-p"><?= api_get_user_name()?></span>
                     </p>
                     <p class="pro-infor"> 
                         <a href="footprint.php">
                             <span class="proHs-p" style="display: inline; padding-right: 20px;">
                                 <img src="images/note.png" />&nbsp;
                                 <b><?=$course_num?></b>  <br/>
                                 <font>学习课程</font>
                             </span>
                         </a>
                         <a href="vm_open.php">
                             <span class="proNote-p" style="display: inline; padding-right: 20px;">
                                 <img src="images/vmware.png" />&nbsp;
                                 <b><?=$vmtotal?></b>  <br/>
                                 <font>开启虚拟机</font>
                             </span>
                         </a>
                         <a href="login_print.php">
                             <span class="proNote-p" style="display: inline; padding-right: 20px;">
                                 <img src="images/login.png" />&nbsp;
                                 <b><?=$login_num?></b>  <br/>
                                 <font>登录次数</font>
                             </span>
                         </a>
                         <a href="labs_report.php">
                             <span class="proNote-p" style="display: inline; padding-right: 20px;">
                                 <img src="images/report.png" />&nbsp;
                                 <b><?=$report_num?></b>  <br/>
                                 <font>我的报告</font>
                             </span>
                         </a>
                         <a href="vm_log.php">
                             <span class="proQues-p" style="display: inline; padding-right: 20px;">
                                 <img src="images/vm-log.png" />&nbsp;
                                 <b><?=$vm_num?></b>  <br/>
                                 <font>虚拟机日志</font>
                             </span>
                         </a>
                     </p>
                 </div>
             </div>
             </div>
             <div class="pw_container">
                 <div class="pw_inner">
                     <div class="pw_profile">
                         <?php  
                         $skill_id=  Database::getval("select  skill_id  from  skill_line  where  uid= ".$u_id."  order  by  skill_id     limit  1"); 
                         ?>
                         <a href="my_post.php?skill_id=<?=$skill_id?>">
                             <span class="l-img pw_img1"></span>
                             <p class="pw_name">我的岗位</p>
                         </a>
                         
                     </div>
                 </div>
                 <div class="pw_inner">
                     <div class="pw_profile">
                         <a href="c_trends.php">
                             <span class="l-img pw_img2"></span>
                             <p class="pw_name">最新动态</p>
                         </a>
                         
                     </div>
                 </div>
                 <div class="pw_inner">
                     <div class="pw_profile">
                         <a href="my_voice.php">
                             <span class="l-img pw_img3"></span>
                             <p class="pw_name">我的消息</p>
                         </a>
                         
                     </div>
                 </div>
                 <div class="pw_inner pw_right">
                     <div class="pw_profile">
                         <a href="my_contest.php">
                             <span class="l-img pw_img4"></span>
                             <p class="pw_name">我的赛场</p>
                         </a>
                         
                     </div>
                 </div>
                  <div class="pw_inner">
                     <div class="pw_profile">
                         <a href="work_attendance.php">
                             <span class="l-img pw_img5"></span>
                             <p class="pw_name">我的考勤</p>
                         </a>
                         
                     </div>
                 </div>
                 <div class="pw_inner">
                     <div class="pw_profile">
                         <a href="footprint.php">
                             <span class="l-img pw_img6"></span>
                             <p class="pw_name">学习足迹</p>
                         </a>
                         
                     </div>
                 </div>
                 <div class="pw_inner">
                     <div class="pw_profile">
                         <a href="login_print.php">
                             <span class="l-img pw_img7"></span>
                             <p class="pw_name">登录足迹</p>
                         </a>
                         
                     </div>
                 </div>
                 <div class="pw_inner pw_right">
                     <div class="pw_profile">
                         <a href="labs_report.php">
                             <span class="l-img pw_img8"></span>
                             <p class="pw_name">我的报告</p>
                         </a>
                         
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
