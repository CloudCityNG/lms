<?php
$cidReset = true;
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
include_once './inc/page_header.php';   
$occupat_id= intval(getgpc('id'));
$path="../../storage/occupation_picture"; 
$sql="select  `id`,`skill_name`  from  `skill_occupation`";
$skills= api_sql_query_array_assoc($sql, __FILE__,__LINE__);

$sql="SELECT `skill_description`,`position_description`,`postition_requirement` FROM `skill_occupation` WHERE `id` =".$occupat_id;
$occupation_info= api_sql_query_array_assoc($sql, __FILE__,__LINE__);
 
$sql="SELECT * FROM `skill_rel_step`  WHERE  `occupat_id`=".$occupat_id."   ORDER  BY  `step_sequentially`";
$steps= api_sql_query_array_assoc($sql, __FILE__,__LINE__);


?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
                      <?php   if(api_get_setting ( 'lm_switch' ) == 'true'){   ?>
  <style>
.l-profile .skill-ul li.Default p {
    color: #357cd1;
    border-bottom: 3px solid #357cd1;
}
  </style>
      <?php   }   ?> 
<div class="clear"></div> 
<div class="m-moclist">
    <div class="g-flow" id="j-find-main"> 
      <div class="b-30"></div>
      <div class="g-container f-cb">
          <div class="l-profile"  style="height:auto; ">
              <div class="profile-in">
   <!--  skills-->
          <div class="skillfud">
                  <ul class="skill-ul">
          <?php   
          $count_skill=count($skills)-1;
            foreach ($skills  as   $key=>$value){
                $sql="select  `occupat_picture`  from  `skill_occupation`  where  `id`=".$value['id'];
                $pic=  Database::getval($sql); 
                   if($value['id']==$occupat_id  &&  ($key+1)%7!=0 ){
                       ?>
                       <li class="Default">
                          <a href="profile_line.php?id=<?=$value['id']?>">
                              <img src="<?=$path.'/'.$pic?>"  style="width: 70px;height: 60px;">
                              <p class="skillname_e"><?=$value['skill_name']?></p>
                          </a>
                      </li>
                      <?php
                   }else  if(($key+1)%7==0  &&  $key!=$count_skill  &&  $value['id']!=$occupat_id){
                       ?>
                        <li style="margin-right:0px;">
                          <a href="profile_line.php?id=<?=$value['id']?>">
                              <img src="<?=$path.'/'.$pic?>"  style="width: 70px;height: 60px;">
                              <p class="skillname_e"><?=$value['skill_name']?></p>
                          </a>
                      </li>
                         </ul>
                        </div>
                    <div class="skillfud">
                         <ul class="skill-ul">
                      <?php
                   }else  if(($key+1)%7==0  &&  $key!=$count_skill   &&  $value['id']==$occupat_id){
                   ?>
                           <li  class="Default"  style="margin-right:0px;">
                          <a href="profile_line.php?id=<?=$value['id']?>">
                              <img src="<?=$path.'/'.$pic?>"  style="width: 70px;height: 60px;">
                              <p class="skillname_e"><?=$value['skill_name']?></p>
                          </a>
                      </li>
                         </ul>
                        </div>
                    <div class="skillfud">
                         <ul class="skill-ul">      
                  <?php
                   }else if($value['id']==$occupat_id &&  $key==$count_skill ){
                   ?>
                          <li class="Default">
                          <a href="profile_learn.php?id=<?=$value['id']?>">
                              <img src="<?=$path.'/'.$pic?>"  style="width: 70px;height: 60px;">
                              <p class="skillname_e"><?=$value['skill_name']?></p>
                          </a>
                      </li>
                    <?php
                   }else{
                       ?>
                      <li>
                          <a href="profile_line.php?id=<?=$value['id']?>">
                              <img src="<?=$path.'/'.$pic?>"  style="width: 70px;height: 60px;">
                              <p class="skillname_e"><?=$value['skill_name']?></p>
                          </a>
                      </li>
                      <?php
                   }
             }
          ?>
                  </ul>
              </div>
<!--skills_end-->
                 <h3 class="p-home">职位技能</h3>
                 <div class="row-fluid">
                     <div class="post-d">
                         <?php if($occupation_info[0]['skill_description'] || $occupation_info[0]['position_description'] || $occupation_info[0]['postition_requirement']){
                            if(trim($occupation_info[0]['skill_description'])){ ?>
                         <div class="list-con">
                            <div class="job-des">技能描述:</div>
                            <div    style="line-height: 20px;font-size: 14px;text-indent:20px;">
                                <p><?=  trim($occupation_info[0]['skill_description'])?></p>
                            </div>
                        </div>
                         <?php   }  
                          if(trim($occupation_info[0]['position_description'])){   ?>
                         <div class="list-con">
                            <div class="job-des">职位描述:</div>
                             <div    style="line-height: 20px;font-size: 14px;text-indent:20px;">
                                 <p><?=  trim($occupation_info[0]['position_description'])?></p>
                            </div>
                        </div>
                          <?php   }    
                           if(trim($occupation_info[0]['postition_requirement'])){   ?>
                        <div class="list-con">
                            <div class="job-des">职位需求:</div>
                             <div    style="line-height: 20px;font-size: 14px;text-indent:20px;">
                                 <p><?=  trim($occupation_info[0]['postition_requirement'])?></p>
                            </div>
                        </div>
                         <?php } }else {?>
                             <div    style="color: #848584;font-weight: bold">
                                 <p>该职位暂没有相关技能描述！</p><br>
                            </div>
                           <?php  }?>
                        <div class="job-des">学习路线: <a  href="profile_learn.php?id=<?=$occupat_id?>"  target="_blank"  style="color: #76C2AF;">查看技能全部课程</a></div>
                      
                     </div>
                     <!--每周学习课程-->
                     <div class="week-line">
                         <?php  
                             if($steps){?>
                         <ul class="w-route">
                             <?php  
                             
                             foreach ($steps  as  $val){       
                                 $sql="select  count(`course_id`)  from  `skill_course_occupation`   where  `step_id`={$val['step_id']}      and    `skill_id`= {$occupat_id} ";
                                 $n= Database::getval($sql, __FILE__, __LINE__);
                              ?>
                             <li>
                                 <span class="route-time" style="font-size: 12px;"> <?="学习时间：<br/>".$val['step_time']?></span>
                                 <div class="route-down"></div>
                                 <a  href="part_course.php?step_id=<?=$val['step_id']?>&occupat_id=<?=$occupat_id?>"  target="_blank">
                                 <div class="route-text">
                                     <p style="color:#76C2AF;">该技能阶段包含<?=$n?>个课程，点击查看</p>
                                    <p><?=$val['step_desc']?></p>
                                 </div>
                                 </a>
                             </li>
                             <?php   }}else{  ?>
                                 <div    style="color: #848584;font-weight: bold">
                                 <p>该职位暂没有相关课程！</p><br>
                            </div>                                 
                                <?php  }  ?>

                         </ul>
                     </div>
                 </div>
             <?php
             //技能线路综合测试审核批改 
             $all_skill_c=  Database::getval("select  count(`course_id`)   from   `skill_course_occupation`  where  `skill_id`=".$occupat_id); 
             $stu2_skill_c= "select  `line_content`  from  `skill_line`   where  `uid`=".api_get_user_id()."   and  `skill_id`=".$occupat_id;    
             $content=  Database::getval($stu2_skill_c); 
             $content_arr=  unserialize($content); 
             $stu2_count=0;
             foreach ($content_arr  as  $val){
                 if($val==2){
                     $stu2_count++;
                 }
             }    
             if($stu2_count==$all_skill_c){
                 $sql="update  `skill_line`  set  `status`=1   where  `uid`=".api_get_user_id()."   and  `skill_id`=".$occupat_id;
                 api_sql_query($sql);
             }
             //end 
            $sql="select  `status`  from  `skill_line`   where  `uid`=".api_get_user_id()."   and  `skill_id`=".$occupat_id;
                  $skill_status=  Database::getval($sql); 
                  if($skill_status==1){
            ?>
                 <div style="font-weight: bold;font-size: 16px;">
                     <?php  
                    $htmlHeadXtra [] = Display::display_thickbox ();
                    Display::display_header ('',FALSE); 
                    echo link_button('', '点击进入技能测试', 'skill_exam.php?skill_id='.$occupat_id, '50%', '60%') ; 
                    ?>
                     <span  style="margin-left: 50px;">
                         <?php  
                          $u_id=  api_get_user_id();
                          $path=URL_ROOT.'/www'.URL_APPEDND.'/storage/occupation_exam_report';
                           $sql="select  `user_file`  from   `skill_examine`  where  `user_answer`='skill_exam'    and `uid`={$u_id}   and   `occupation_id`={$occupat_id} ";
                         $filename=  Database::getval($sql);  
                           echo link_button('', '查看技能报告结果', 'skill_report.php?id='.$occupat_id.'&fp='.$path.'&fn='.$filename, '40%', '50%') ;  
                         ?>
                   </span>
                  </div>
       <?php    }  ?>
             </div>
    </div>
</div>
</div>
</body>
</html>
