<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
$user_id=api_get_user_id ();
$my_skill=  intval(getgpc("skill_id"));
$path="../../storage/occupation_picture"; 

$sql="select  skill_id  from  skill_line  where  uid= ".$user_id."  order  by  skill_id ";
$skills=  api_sql_query_array_assoc($sql, __FILE__, __LINE__);
//page
 $total_rows = DATABASE::getval("SELECT  count(`course_id`)   FROM `skill_course_occupation` where  `skill_id`=".$my_skill );
$url = 'my_post.php?skill_id='.$my_skill;
$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );
//skill-courses     skill_course_occupation
$sql="SELECT `course_id` FROM `skill_course_occupation` WHERE `skill_id`=".$my_skill."     order  by    `sequentially` ";
$offset = (int)getgpc ( "offset", "G" );
if (empty ( $offset )) $offset = 0;
$sql .= " LIMIT " . $offset . "," . NUMBER_PAGE;
$course_info=  api_sql_query_array_assoc($sql, __FILE__, __LINE__);

$sql="select  line_content  from  skill_line  where  uid= ".$user_id."  and  skill_id=".$my_skill;
 $content=  Database::getval($sql);
 $content1=  unserialize($content);

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
.post-ul li.Default a p{
    color:#357cd2;
    font-weight:bold;
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
                        <a class="f-thide f-f1" title="我的岗位" href="my_post.php" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">我的岗位</a>
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
                    
                </ul>
                 
            </div>
            
       </div>
 
    <div class="g-mn1" > 
    <div class="g-mn1c m-cnt" style="display:block;">
    <div class="j-list lists" id="j-list" style="margin-bottom:40px;clear:both;"> 
       <div class="u-content" style="border: 1px solid #C5C5C5;box-shadow: 0 1px 6px #999;">
           <div class="m-msglist">
               <div class="tabarea j-tabs">
                   <a class="a-tab">已选择岗位</a>
               </div>
               <div class="job-description">
                   <ul class="post-ul">
                       <?php  
                       foreach ($skills  as  $skill_val){  
                            $sql="select  `occupat_picture`  from  `skill_occupation`  where  `id`=".$skill_val['skill_id'];
                            $pic=  Database::getval($sql); 
                           ?>
                       <li class="<?=($my_skill==$skill_val['skill_id']?"Default":"")?>">
                           <a href="my_post.php?skill_id=<?=$skill_val['skill_id']?>">
                              <img src="<?=$path.'/'.$pic?>" style="width: 70px;height:60px;">
                              <p class="skill"><?=  Database::getval("select  skill_name  from  skill_occupation  where  id=".$skill_val['skill_id'])?></p>
                          </a>
                      </li>
                       <?php  }   ?>
                    </ul>
               </div>
               <div class="job-tab">
                   <div class="u-content">
                       <h3 class="sub-simple u-course-title">
                           <span class="u-title-next">课程目录</span>
                       </h3>
                   <div class="u-content-bottom">
                       <?php  
                           foreach ($course_info  as  $key=>$val){ 
                               $c_name=  Database::getval("select  title  from  course  where  code='".$val['course_id']."'");
                               $grade=  Database::getval("select description   from  course  where  code='".$val['course_id']."'"); 
                               $grade1='';
                               if($grade==1){
                                   $grade1="中级";
                               }else if($grade==2){
                                   $grade1="高级";
                               }else{
                                   $grade1="初级";
                               }
                               $status='';
                               if(isset($content1[$val['course_id']])  ){
                                if($content1[$val['course_id']]==1){
                                        $status="已学";
                                }else  if(  $content1[$val['course_id']]==2){
                                         $status="通过";
                                }
                             }else{
                                         $status="未开始";
                                }
                         $href='';
                         $href="course_home.php?cidReq=".$val['course_id']."&action=introduction&from=skill_line&skill_id=".$my_skill;
                       ?>
                       <ul class="u-course-time">
                           <li class="title-time p-514 ">课程<?=$key+1?></li>
                           <li class="title-name">
                               <a title="<?=$c_name?>"  href="<?=$href?>"  onclick="javascript:chooseCourse('<?=$val['course_id']?>');"><?=$c_name?> </a>
                           </li>
                           <li class="add-lab p-514 "> <?=$grade1?> </li>
                           <li class="lab-time f-13">
                             <span style="color:#FF0000"><?=$status?></span> 
                           </li> 
                           <!--<li class="lab-time f-13">2人</li>--> 
                        </ul>
                       <?php } ?>
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
    </div>
    </div>
</div>
</div>
<?php 
include './inc/page_footer.php';
?>
</body>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript">
function chooseCourse(code){     
 $.ajax({
	 type:"get", 
         url:"ajax_test.php",
         data:"ajaxAction=subscribe"+"&code="+code+"&course_class_id= ",
	 dataType:"html",
	 success:function(data){
		 if(data){
                     $('#chooid').html('<a target="_top" href="course_home.php?cidReq='+code+'&action=introduction" class="go">进入课程</a><input type="hidden" id="goreferen" value="'+code+'" />');
                     var countnum=$('#countnumber').html();
                     var pepol=parseInt(countnum);
                     pepol++;
                     $('#countnumber').html(pepol);
                     $('#tishi').html('你已选修该课程');
                 }
            }
       })
}
 
</script>
</html>


