<?php
$language_file = 'admin';
$cidReset = true;
include_once './inc/page_header.php';   
include_once ("inc/app.inc.php");
require_once (api_get_path ( LIBRARY_PATH ) . "course.lib.php");
$u_id=  api_get_user_id();
$course_id=  getgpc('course_id');
$occupat_id=  intval(getgpc('occupat_id'));
if($_GET['lim']){
    $lim= intval(getgpc('lim'));
}else{
    $lim=0;
}
$sql="SELECT * FROM `skill_question` WHERE  `contcat_id`='".$course_id."'  limit  ".$lim.",1";
$res=  api_sql_query_array_assoc($sql, __FILE__, __LINE__);
 
//save 
$datas=$_POST; 
if($datas){
    $sql="SELECT  count(`id`)  FROM `skill_examine` WHERE `uid`={$u_id}  and  `question_id`={$datas['qid']}   and   `course_id`='{$datas['hide']}'";
    $is_exis=  Database::getval($sql);
  
    $status='';
    $right_answer=  Database::getval("select  `answer`  from  `skill_question`   where  `id`=".$datas['qid']);
    if($datas['answer']==$right_answer){
        $status=1;
    }else{
        $status=0;
    }
    if($is_exis>0){
        $sql="UPDATE `skill_examine` SET  `user_answer`='{$datas['answer']}',`status`={$status}   WHERE `uid`={$u_id}  and  `question_id`={$datas['qid']}   and   `course_id`='{$datas['hide']}'";
         api_sql_query($sql);
    }else{
        $sql="INSERT INTO `skill_examine`(`uid`, `question_id`, `course_id`, `user_answer`, `status`) VALUES ({$u_id},{$datas['qid']},'{$datas['hide']}','{$datas['answer']}',{$status})";
        api_sql_query($sql);
    }
 
//技能课程自测审核批改 
$stu1_q=  Database::getval("SELECT  count(`question_id`)  FROM `skill_examine` WHERE   `status`=1  and `uid`={$u_id}   and  `course_id`='{$datas['hide']}' ");
$all_q=  Database::getval("SELECT  count(`id`)  FROM `skill_question` WHERE  `contcat_id`='{$datas['hide']}' ");
 if(float_format($stu1_q/$all_q)>=0.6){ 
     $c_status="select  `line_content`  from  `skill_line`   where  `uid`=".$u_id."   and  `skill_id`=".$datas['occupat_id'];    
     $content=  Database::getval($c_status);
    if($content!=''){
            $content_old=  unserialize($content);     
            $is_in_arr=   isset($content_old[$datas['hide']]);     
            if( $is_in_arr){
                $content_old[$datas['hide']]=2;
            } 
            $line_content1=serialize($content_old);
            $sql="update  `skill_line`  set  `line_content`='{$line_content1}'   where  `uid`=".$u_id."   and  `skill_id`=".$datas['occupat_id'];  
            api_sql_query($sql);
        }
 }
//end
    
    $count=  Database::getval("SELECT count(`id`)  FROM `skill_question` WHERE  `contcat_id`='".$datas['hide']."' ");
    if($_POST['submit']=='上一题'){       
        $lim_new=(($datas['lim']-1)<0?0:($datas['lim']-1));  
        tb_close("testself.php?course_id=".$datas['hide']."&occupat_id=".$occupat_id."&lim=".$lim_new);
    }else  if($_POST['submit']=='下一题'){       
        $lim_new=(($datas['lim']+1)>=$count?($count-1):($datas['lim']+1));   
        tb_close("testself.php?course_id=".$datas['hide']."&occupat_id=".$occupat_id."&lim=".$lim_new);
    }
}
//default
$sql="SELECT  `user_answer`  FROM `skill_examine` WHERE `uid`={$u_id}  and  `question_id`={$res[0]['id']}   and   `course_id`='{$course_id}'";
$old_answer=  Database::getval($sql); 
$sta=  Database::getval("SELECT  `status`  FROM `skill_examine` WHERE `uid`={$u_id}  and  `question_id`={$res[0]['id']}   and   `course_id`='{$course_id}'");
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
<div class="clear"></div> 
<div class="m-moclist">
    <div class="g-flow" id="j-find-main" style="width:1000px"> 
      <div class="b-30"></div>
      <div class="g-container f-cb">
          <div class="l-profile">
              <div class="profile-in">
                  <div class="tabs-space" style="padding:20px 0;">
                      <?php    if($res){ ?>
                    <div class="exam-title">
                        <form   action="testself.php?course_id=<?=$course_id?>&occupat_id=<?=$occupat_id?>&lim=<?=$lim?>"   method="post">
                        <div class="exam-t">
                            <span style="color: #1abc9c;">题目<?=$lim+1?>（<?=$res[0]['score']?>分）：</span>
                            <span><?=$res[0]['topic']?></span>
                        </div> 
                            <div style="margin-top: 20px;">答案   <input  type="text" name="answer"  value="<?=$old_answer?>"   style="color: black;border: 1px #ccc solid;" /> <span style="color: <?=($sta==1?"green":"red")?>;"><?php   if(!empty($old_answer)){ echo ($sta==1?"(正确)":"(错误)");}?></span>
                                <input  type="hidden"  name="hide"  value="<?=$course_id?>"/>
                                <input  type="hidden"  name="qid"  value="<?=$res[0]['id']?>"/>
                                <input  type="hidden"  name="lim"  value="<?=$lim?>"/>
                                <input  type="hidden"  name="occupat_id"  value="<?=$occupat_id?>"/>
                            </div>
                            <?php  
                                $count=  Database::getval("SELECT count(`id`)  FROM `skill_question` WHERE  `contcat_id`='".$course_id."' ");
                                if($lim>0){ 
                            ?>
                            <input   type="submit" name="submit" value="上一题"   class="pre-btn exam-btn" style=" background:#1abc9c;border: 0;" />
                            <?php   
                            } 
                            ?>
                            <input   type="submit" name="submit" value="<?= ($lim==($count-1))?'保存':'下一题'?>"   class="next-btn exam-btn"  style=" background:#1abc9c;border: 0;" />
                        </form>
                    </div>
                     <?php }else{  echo  "<div  style='color:red;font-weight:bold;'>抱歉！暂无该课程的自测题目！！</div>";  } ?>
                  </div>
              </div>
      </div>
    </div>
</div>
</div>
</body>
</html>