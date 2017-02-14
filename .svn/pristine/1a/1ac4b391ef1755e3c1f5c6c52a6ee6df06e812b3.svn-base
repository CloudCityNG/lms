<?php
$language_file = 'admin';
$cidReset = true;
include_once './inc/page_header.php';   
include_once ("inc/app.inc.php");
require_once (api_get_path ( LIBRARY_PATH ) . "course.lib.php");
$u_id=  api_get_user_id();
$step_id=  intval(getgpc('step_id'));
$occupat_id=  intval(getgpc('occupat_id'));

$sql="select  `course_id`,`sequentially`  from  `skill_course_occupation`   where  `step_id`={$step_id}      and    `skill_id`= {$occupat_id}  ORDER  BY  `sequentially` ";
$res=  api_sql_query_array_assoc($sql, __FILE__, __LINE__);

?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
        <?php   if(api_get_setting ( 'lm_switch' ) == 'true'){   ?>
  <style>
.lab-box a.startLab {
background: #357cd2;
} 
.tabs-space .lab-box {
background: #357cd2;
}
.tabs-space .lab-box .line {
background: #357cd2;
}
  </style>
      <?php   }   ?> 
 <div class="clear"></div> 
<div class="m-moclist">
    <div class="g-flow" id="j-find-main" style="width:1000px"> 
      <div class="b-30"></div>
      <div class="g-container f-cb">
          <div class="l-profile">
              <div class="profile-in">
                  <div class="tabs-space">
                      <div class="lab-box">
                          <span>开始</span>
                          <div class="line"></div>
                      </div>
  <!--start-->
        <?php  
        $step=1;
            foreach ($res  as  $value){
                $seq=$value['sequentially'];
                $href="course_home.php?cidReq=".$value['course_id']."&action=introduction&from=skill_line&skill_id=".$occupat_id;
                $href1="login.php";
                $sql="select  `line_content`  from  `skill_line`  where  `uid`={$u_id}   and  `skill_id`={$occupat_id}";
                $content=  Database::getval($sql);
                $line_content=unserialize($content);                
                $line_course_status=$line_content[$value['course_id']];
                if($line_course_status==1){
                    $c_sta="学习中";
                }else if($line_course_status==2){
                     $c_sta="通过 ";
                }else{
                    $c_sta="未开始";
                }
                
                if($seq%2==1){
                    ?>
                    <div class="lab-box <?=(($line_course_status==1  ||  $line_course_status==2)?'':'disabled')?>">
                          <span><?=$step?></span>
                          <div class="line <?=(($line_course_status==1  ||  $line_course_status==2)?'':'disabled')?>"></div>
                          <div class="popover p-left content-left">
                                        <div class="arrow"></div>
                                        <h5><?=  Database::getval("select  `title`  from  `course`  where  `code`='".$value['course_id']."'")?> </h5>
                                        <p>学习状态：<?=$c_sta?><a  href="testself.php?course_id=<?=$value['course_id']?>&occupat_id=<?=$occupat_id?>"  target="_blank"  style="padding-left:20px;font-weight: bold;color: <?=( api_get_setting("lm_switch")=="true"?'#357CD2;':'#76C2AF;')?>;">进入课程自测</a></p>
                                        <a class="startLab <?=(($line_course_status==1  ||  $line_course_status==2)?'':'disabled')?>" id="startlab291" href="<?=($u_id?$href:$href1)?>"  target="_blank"   onclick="javascript:chooseCourse('<?=$value['course_id']?>');">
                                                进入课程  
                                        </a>
                                    </div>
                      </div>
                        <?php
                }else  if($seq%2==0){
                       ?>
                    <div class="lab-box <?=(($line_course_status==1  ||  $line_course_status==2)?'':'disabled')?>">
                          <span><?=$step?></span>
                          <div class="line <?=(($line_course_status==1  ||  $line_course_status==2)?'':'disabled')?>"></div>
                          <div class="popover p-right content-right">
                                        <div class="arrow"></div>
                                        <h5><?=  Database::getval("select  `title`  from  `course`  where  `code`='".$value['course_id']."'")?>  </h5>
                                        <p>学习状态：<?=$c_sta?><a  href="testself.php?course_id=<?=$value['course_id']?>&occupat_id=<?=$occupat_id?>"  target="_blank"  style="padding-left:20px;font-weight: bold;color: <?=( api_get_setting("lm_switch")=="true"?'#357CD2;':'#76C2AF;')?>;">进入课程自测</a></p>
                                        <a class="startLab <?=(($line_course_status==1  ||  $line_course_status==2)?'':'disabled')?>" id="startlab291" href="<?=($u_id?$href:$href1)?>"  target="_blank"  onclick="javascript:chooseCourse('<?=$value['course_id']?>');">
                                                进入课程  
                                        </a>
                                    </div>
                      </div>
              <?php
                    } 
                $step++;
                }  
               ?> 
              
 <!--end-->
 <?php  
 $sql="select  `course_id`  from  `skill_course_occupation`  where  `step_id`={$step_id}     order  by    `sequentially`   desc   limit  1";
 $c_id=  Database::getval($sql);
 $sql="select  `line_content`  from  `skill_line`  where  `uid`={$u_id}   and   `skill_id`={$occupat_id}";
 $line_content= api_sql_query_array_assoc($sql, __FILE__,__LINE__);
 $line_content=  unserialize($line_content[0]['line_content']);
 ?>
                       <div class="lab-box <?=(empty($line_content[$c_id])?'disabled':'')?>">
                          <span>结束</span>
                      </div>
                  </div>
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