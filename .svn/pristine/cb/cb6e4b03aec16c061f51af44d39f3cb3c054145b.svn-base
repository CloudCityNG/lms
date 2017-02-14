<?php
$cidReset = true;
include_once ("inc/app.inc.php"); 
include_once './inc/page_header.php';   
$u_id=  api_get_user_id();
$sql="select  `id`,`skill_name`  from  `skill_occupation`";
$skills= api_sql_query_array_assoc($sql, __FILE__,__LINE__);
$occupat_id= intval(getgpc('id')); 
$sql="SELECT `course_id`,`sequentially`,`skill_id`   FROM `skill_course_occupation` WHERE  `skill_id`=".$occupat_id."   ORDER  BY  `sequentially` ";
$skill_courses= api_sql_query_array_assoc($sql, __FILE__,__LINE__);

$path="../../storage/occupation_picture";
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
          <div class="l-profile">
              <div class="profile-in">
<!--  skills-->
          <div class="skillfud">
                  <ul class="skill-ul">
          <?php   
          $count_skill=count($skills)-1;
            foreach ($skills  as   $key=>$value){
                $sql="select  `occupat_picture`  from  `skill_occupation`  where  `id`=".$value['id'];
                $pic=  Database::getval($sql); 
                   if($value['id']==$occupat_id && ($key+1)%7!=0){
                       ?>
                       <li class="Default">
                          <a href="profile_learn.php?id=<?=$value['id']?>">
                              <img src="<?=$path.'/'.$pic?>"  style="width: 70px;height: 60px;">
                              <p class="skillname_e"><?=$value['skill_name']?></p>
                          </a>
                      </li>
                      <?php
                   }else  if(($key+1)%7==0  &&  $key!=$count_skill  &&  $value['id']!=$occupat_id ){   
                       ?>
                        <li style="margin-right:0px;">
                          <a href="profile_learn.php?id=<?=$value['id']?>">
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
                          <a href="profile_learn.php?id=<?=$value['id']?>">
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
            <?php   if(count($skill_courses)>0){
                 $sql="select  `line_content`  from  `skill_line`   where  `uid`=".$u_id."   and  `skill_id`=".$occupat_id;
                 $total_course=  Database::getval($sql);
                  $finished_course=unserialize($total_course); 
                  $total=0;
                  foreach ($finished_course  as  $val){
                      if($val==2){
                          $total++;
                      }
                  }
                ?>
                  <p id="expcounts">已完成<?=$total?>个实验 / 共<?=count($skill_courses)?>个实验</p>
                  <div class="experall">
                      <img src="images/profile-start.png" class="pro-start">
                      <div class="pro-line">
 <!--all-->
                              <ul class="ps_cont ps_left"> 
                          <?php  
                          $num=count($skill_courses);   
                          for ($k=1;$k<=$num;$k++){   
                            $key=$k-1;
                            $sql="select  `title` from  `course`  where  `code`='".$skill_courses[$key]['course_id']."'";
                            $course_tile=  Database::getval($sql); 
                            $course_tile=  substr($course_tile, 11);
                            $c_status="select  `line_content`  from  `skill_line`   where  `uid`=".$u_id."   and  `skill_id`=".$occupat_id;
                            $content=  Database::getval($c_status);
                            $line_content=unserialize($content);
                            $line_course_status=$line_content[$skill_courses[$key]['course_id']];
                            if($line_course_status==1){
                                $c_sta="学习中";
                            }else if($line_course_status==2){
                                 $c_sta="通过 ";
                            }else{
                                $c_sta="未开始";
                            }
                           
                            $href="course_home.php?cidReq=".$skill_courses[$key]['course_id']."&action=introduction&from=skill_line&skill_id=".$occupat_id;
                            $href1="login.php";
                            
                            $color='';
                            if($line_course_status==1){  
                                $color="pro-c".  rand(1,6)." color";  
                            }else  if( $line_course_status==2){
                                $color="pro-c7 color"; 
                            }
                            
                            $i=$k%7;    
                            $j=($k/7)%2;
                              if($i==0  &&  $j==0   &&  $k!=$num){
                              ?>  
                                  </ul>
                                    <ul class="ps_cont ps_left ul-sec" > 
                                        <li class="pro-li  <?=$color?>"  onclick="javascript:chooseCourse('<?=$skill_courses[$key]['course_id']?>');">
                                        <a class="expc" href="<?=($u_id?$href:$href1)?>" target="_blank">
                                            <p>
                                                <b>课程：</b>
                                                <span title="<?=$course_tile?>"><?=$course_tile?></span>
                                            </p>
<!--                                            <p>
                                                <b>实验：</b>
                                                <span title="密码学原理">密码学原理密码学原理密码学原理</span>
                                            </p>-->
                                             <p>
                                                <b>学习状态：</b>
                                                <span ><?=$c_sta?></span>
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="ps_cont ps_left"> 
                              <?php
                              }else  if($i==0  &&  $j==0   &&  $k=$num){
                                  ?>
                                        </ul>
                                    <ul class="ps_cont ps_left ul-sec" > 
                                    <li class="pro-li <?=$color?>"  onclick="javascript:chooseCourse('<?=$skill_courses[$key]['course_id']?>');">
                                        <a class="expc" href="<?=($u_id?$href:$href1)?>" target="_blank">
                                            <p>
                                                <b>课程：</b>
                                                <span title="<?=$course_tile?>"><?=$course_tile?></span>
                                            </p>
<!--                                            <p>
                                                <b>实验：</b>
                                                <span title="密码学原理">密码学原理密码学原理密码学原理</span>
                                            </p>-->
                                             <p>
                                                <b>学习状态：</b>
                                                <span ><?=$c_sta?></span>
                                            </p>
                                        </a>
                                    </li> 
                                 <?php
                              }else  if($i==0  &&  $j==1  &&  $k!=$num){
                               ?>  
                              </ul>
                             <ul class="ps_cont ul-sec"> 
                            <li class="pro-li pro-right <?=$color?>"  onclick="javascript:chooseCourse('<?=$skill_courses[$key]['course_id']?>');">
                                <a class="expc" href="<?=($u_id?$href:$href1)?>" target="_blank">
                                    <p>
                                        <b>课程：</b>
                                        <span title="<?=$course_tile?>"><?=$course_tile?></span>
                                    </p>
<!--                                    <p>
                                        <b>实验：</b>
                                        <span title="密码学原理">密码学原理密码学原理密码学原理</span>
                                    </p>-->
                                     <p>
                                        <b>学习状态：</b>
                                        <span ><?=$c_sta?></span>
                                    </p>
                                </a>
                            </li>
                        </ul>      
                        <ul class="ps_cont ps_left"> 
                              <?php    
                              }else  if($i==0  &&  $j==1  &&  $k=$num){
                                ?>
                             </ul>
                             <ul class="ps_cont ul-sec"> 
                            <li class="pro-li pro-right <?=$color?>"  onclick="javascript:chooseCourse('<?=$skill_courses[$key]['course_id']?>');">
                                <a class="expc" href="<?=($u_id?$href:$href1)?>" target="_blank">
                                    <p>
                                        <b>课程：</b>
                                        <span title="<?=$course_tile?>"><?=$course_tile?></span>
                                    </p>
<!--                                    <p>
                                        <b>实验：</b>
                                        <span title="密码学原理">密码学原理密码学原理密码学原理</span>
                                    </p>-->
                                     <p>
                                        <b>学习状态：</b>
                                        <span ><?=$c_sta?></span>
                                    </p>
                                </a>
                            </li>    
                                <?php  
                              }else  if($k<7 || ($k>14 && $k<21)  ||  ($k>28 && $k<35)    ||  ($k>42 && $k<49)  ||  ($k>56 && $k<63) || ($k>70 && $k<77) || ($k>84 && $k<91)  ){
                              ?>  
                            <li class="pro-li <?=$color?>"  style="float: left;"  onclick="javascript:chooseCourse('<?=$skill_courses[$key]['course_id']?>');">
                                <a class="expc" href="<?=($u_id?$href:$href1)?>" target="_blank">
                                    <p>
                                        <b>课程：</b>
                                        <span title="<?=$course_tile?>"><?=$course_tile?></span>
                                    </p>
<!--                                    <p>
                                        <b>实验：</b>
                                        <span title="密码学原理">密码学原理密码学原理密码学原理</span>
                                    </p>-->
                                     <p>
                                        <b>学习状态：</b>
                                        <span ><?=$c_sta?></span>
                                    </p>
                                </a>
                            </li>      
                              <?php   
                              }else  if( ($k>7 && $k<14) || ($k>21 && $k<28)   ||  ($k>35 && $k<42)  ||  ($k>49 && $k<56) || ($k>63 && $k<70)  || ($k>77 && $k<84) || ($k>91 && $k<98)  ){
                              ?>
                              <li class="pro-li <?=$color?>"   style="float: right;"  onclick="javascript:chooseCourse('<?=$skill_courses[$key]['course_id']?>');">
                                <a class="expc" href="<?=($u_id?$href:$href1)?>" target="_blank">
                                    <p>
                                        <b>课程：</b>
                                        <span title="<?=$course_tile?>"><?=$course_tile?></span>
                                    </p>
<!--                                    <p>
                                        <b>实验：</b>
                                        <span title="密码学原理">密码学原理密码学原理密码学原理</span>
                                    </p>-->
                                     <p>
                                        <b>学习状态：</b>
                                        <span ><?=$c_sta?></span>
                                    </p>
                                </a>
                            </li>       
                              <?php
                              } 
                        }
                          ?>
                           </ul>
 <!--all_end-->
                      </div>
<!--                      <img src="images/profile-end.png" class="pro-end">-->
                  </div>
                 
           <?php  
                }else{  
            echo   "<div  style='color: #848584;font-weight: bold；'>该职业技能暂无课程！！</div>";
        }  ?>
              </div>
      </div>
    </div>
</div>
</div>
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