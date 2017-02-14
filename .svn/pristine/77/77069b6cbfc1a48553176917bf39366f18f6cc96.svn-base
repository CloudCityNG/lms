<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
$user_id = api_get_user_id ();  
include_once './inc/page_header.php';
 if(api_get_setting ( 'enable_modules', 'course_center' ) == 'false'){
//      echo '<script language="javascript"> document.location = "./learning_center.php";</script>';
      exit ();
 }
if(api_get_setting ( 'lnyd_switch' ) == 'true'){
        if(!api_get_user_id ()){
                echo '<script language="javascript"> document.location = "./login.php";</script>';
                exit();
        }
}
$id=(int)getgpc('id');
if(!isset($_GET['id']) && $id==''){
    $sql =  "select id from setup order by id LIMIT 0,1";
    $courseId= DATABASE::getval ( $sql, __FILE__, __LINE__ );
    if($courseId!==''){
        echo '<script language="javascript"> document.location = "./new-index.php?id='.$courseId.'";</script>';
    };
}
$category_id=(int)getgpc("category");
$subclass='';
if($category_id){   //选中某个一级分类
    $subclass[]=$category_id;
}else{  //某课程体系下的所有一级分类
$sql1="select subclass from setup where id=$id";
$re=  Database::getval($sql1);
$rews=explode(',',$re);
    foreach ($rews as $v) {
        if($v!==''){
           $subclass[]=$v; 
        }
    }
}

$tbl_course  = Database::get_main_table(TABLE_MAIN_COURSE);
$tbl_course_openscore = Database::get_main_table ( TABLE_MAIN_COURSE_OPENSCOPE );

if (api_is_platform_admin () OR api_get_setting('course_center_open_scope')==1) {
    $sql = "SELECT category_code,count(*) FROM $tbl_course  GROUP BY category_code";
} else {
    $sql = "SELECT category_code,count(*) FROM $tbl_course WHERE code IN (SELECT course_code FROM " . $tbl_course_openscore . " WHERE user_id='" . api_get_user_id () . "')  GROUP BY category_code";
}
$category_cnt = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
 
$objCrsMng=new CourseManager();//课程分类  对象。
$objCrsMng->all_category_tree = array ();
$category_tree = $objCrsMng->get_all_categories_trees ( TRUE,$subclass);

//Recently Study
$sql="SELECT `code`,`title`  FROM  `course` INNER JOIN `course_rel_user` ON `course_rel_user`.`course_code`=`course`.`code` where `user_id`=".$user_id." ORDER BY  `course_rel_user`.`last_access_time` DESC  limit  0,8";
//echo $sql;
$Recently_Study=  api_sql_query_array_assoc($sql,__FILE__,__LINE__);
$Recently_Study_count=intval(count($Recently_Study));
    
?> 


  <body style="background:#FAFAFA;">  
      <div class="g-flow">
            <div class="h-30"></div>
<!--            <div id="New-in">
<?php
                      $user_img=$_SESSION['_user']['picture_uri'];
                      $userpath=api_get_path ( WEB_PATH ) . 'storage/users_picture/';
                      $user_image=$user_img ? $userpath.$user_img : URL_APPEND."portal/sp/images/user-small.jpg";
                      if($_SESSION['_user']['status']){
?>
                <div class="m-links right" id="j-topnav">
                    <div class="unlogin">
                        <div class="f-thide login-name">
                            <a class="f-fc9" href="javascript::void(0);"><?=$_SESSION['_user']['username']?></a>
                        </div>
                        <div class="user-info" id="nav-info-box">
                            <div class="face">

                                <img id="my-img" src="<?=$user_image?>" width="28px" height="28px" alt="用户头像">
                            </div>
                            <div class="set j-nav-set x-hide">
                                <ul class="u-navbg u-navbg2">
<?php
       if ($_SESSION['_user']['status'] == PLATFORM_ADMIN){
?>
                                    <li class="text">
                                            <a class="s-fc2" href="<?= URL_APPEND?>portal/sp/my_foot.php" title="用户中心">用户中心</a>
                                    </li>
                                    <li>
                                        <a href="<?=URL_APPEDND?>/main/admin/index.php" target="_blank" title="后台" class="self j-uhref">后台</a>
                                    </li>
                                    <li>
                                        <a onclick="closedown();" title="关机" class="self j-uhref">关机</a>
                                    </li>
                                    <li>
                                        <a target="_top" onclick="closebtn();" title="退出" class="exit">退出</a>
                                    </li>
<?php
                        }else if($_SESSION['_user']['status']=='1'){
?>
                                    <li class="text">
                                            <a class="s-fc2" href="<?= URL_APPEND?>portal/sp/user_profile.php" title="用户中心">用户中心</a>
                                    </li>
                                    <li>
                                        <a href="<?=URL_APPEDND?>/user_portal.php" target="_blank" class="self j-uhref" title="后台">后台</a>
                                    </li>
                                    <li>
                                        <a target="_top" onclick="closebtn();" title="退出" class="exit">退出</a>
                                    </li>
<?php
                        }else if(api_get_user_id()){
?>
                                    <li class="text">
                                            <a class="s-fc2" href="<?= URL_APPEND?>portal/sp/user_profile.php" title="用户中心">用户中心</a>
                                    </li>
                                    <li class="text">
                                            <a class="s-fc2" href="<?= URL_APPEND?>storage/manual.pdf" title="用户帮助手册" target="_blank">用户帮助手册</a>
                                    </li>
                                    <li>
                                        <a target="_top" onclick="closebtn();" title="退出" class="exit">退出</a>
                                    </li>
<?php
                        }
?>

                              </ul>
                            </div>
                        </div>
                        </div>
                </div>
<?php
                      }else if(!$_SESSION['_user']['status']){
?>
                <a class='new-login' href="login.php">登录</a>
<?php
                      }?>
            </div> -->

     </div>
 
 
    <!-- 导航结束 -->
     <div class="clear"></div> 
	<div class="m-moclist">
	    <div class="g-flow" id="j-find-main"> 
                <div class="b-30"></div>
                <div class="g-container f-cb">
                    <div class="g-sd1 nav">
              
                         <?php   $SurveyCenter=api_get_setting( 'enable_modules', 'survey_center'); 
                  if($SurveyCenter == 'true'){  ?>
                       
                     <?php } ?>
                            <?php   if(api_get_setting ( 'enable_modules', 'router_center' ) == 'true'){  ?>
                           
                        
                  <?php  } ?>
                        <!--下部-->
<?php
//Recently Study
$sql="SELECT `code`,`title`  FROM  `course` INNER JOIN `course_rel_user` ON `course_rel_user`.`course_code`=`course`.`code` where `user_id`=".$user_id." ORDER BY  `course_rel_user`.`last_access_time` DESC  limit  0,8";
//echo $sql;
$Recently_Study=  api_sql_query_array_assoc($sql,__FILE__,__LINE__);
$Recently_Study_count=intval(count($Recently_Study));
?>
                        <div class="m-university" id="j-university" style="margin-top:0">
                            <div>
                                   <div class="bar f-cb" style="background-color:#13a654;">
                                          <h3 class="left f-fc3 rece-h3"  style='color:#FFF;'>最近学习</h3>
                                   </div>
                                 <div class="us">
                                 <?php
                                    if($Recently_Study_count>0){
                                        foreach ($Recently_Study as $values1) { ?>
                                            <div class="Recently_Study">
                                               <a class="recently1" href="<?=URL_APPEND?>portal/sp/course_home.php?cidReq=<?=$values1['code']?>&action=introduction" class="logo" >
                                                 <?=api_trunc_str2($values1['title'],18)?> 
                                               </a>
                                           </div>
                                    <?php
                                        }
                                    }else{?>
                                        <div class="Recently_Study">
                                                 没有最近学习
                                           </div>
                                   <?php
                                   }
                                    ?>
                                </div> 
                            </div>
                        </div>
                    </div>

           <!--  右侧 -->
		   <div class="g-mn1" >
		        <div class="g-mn1c m-cnt" style="display:block;">
<!--				    <div class="top f-cb j-top">
					   <h3 class="left f-thide j-cateTitle title">
					      <span class="f-fc6 f-fs1" id="j-catTitle">
                                              <?php  
                                              $catgory_name=DATABASE::getval("select  name  from  course_category  where  id=".$category_id);
                                              $setup_name=DATABASE::getval("select  title from setup where id=".$id);
                                              $course_count=DATABASE::getval("select  count(*) from course ",__FILE__,__LINE__);
                                              echo  ($category_id?$catgory_name:$setup_name);
                                              ?>
                                              </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#CE1515;font-size:14px">当前系统课件总数：<?=$course_count?>个</span>
					   </h3>
					</div>-->

    <div class="j-list lists" id="j-list">
          
             <div id="wrapper1">
   
			<a href="select_study.php?id=3" title="网安选课中心"><div id="thumb1-1">网安选课中心</div></a>
			<a href="select_study.php?id=4" title="攻防实训"><div id="thumb1-2">攻防实训</div></a>
			<a href="select_study.php?id=1" title="基础选课中心"><div id="thumb1-5">基础选课中心</div></a>
			<a href="select_study.php?id=9" title="中国移动网络安全专业技能课程实验"><div id="thumb1-6">中国移动网络安全专业技能课程实验</div></a>
			<a href="../../main/ctf/index_2.php" title="CTF"><div id="thumb1-7">CTF</div></a>
			<a href="video-more.php" title="信息安全意识"><div id="thumb1-8">信息安全意识</div></a>
	  </div>
					 
     </div>
 
  </div>



			 </div>
		</div>
	    </div>
     </div>
	<!-- 底部 -->
<?php
        include_once './inc/page_footer.php';
?>
        

<script type="text/javascript">
  $(function(){
     var footerHeight=0;
     var footerTop=0;
     var footer=$("#footer");

     function positionFooter(){
         
         footerHeight=footer.height();
         footerTop=($(window).height()-footerHeight-2)+'px';
         
         //如果页面内容高度小于屏幕高度，div#footer将绝对定位到屏幕底部，否则div#footer保留它的正常静态定位。

         if(($(document.body).height()< $(window).height()) || ($(document.body).height()== $(window).height())){
            footer.css('position','absolute');
            footer.css('left','0');
            footer.css('top',footerTop);
         }     
     }  
     positionFooter();
     $(window).scroll(positionFooter).resize(positionFooter);
  })
    //上面的退出中jBox和$(function()在页面中都存在冲突
  function closebtn(){
             if(confirm("你确定退出系统吗？")){
                 location.href="<?=URL_APPEND?>main/cloud/cloudvmstop.php?user_id=<?=$user_id?>";
             }
  }

    function closedown(){
      if(confirm("你确定关机吗？")){
                 location.href="<?=$url?>?action=shutdown";
      }
  }
  
  $("#nav-info-box").mouseover(function(){
    $(".set").css("display","block");
})
$("#nav-info-box").mouseout(function(){
    $(".set").css("display","none");
})

 </script>
        
 </body>
</html>
