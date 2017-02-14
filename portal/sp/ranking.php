<?php
$cidReset = true;
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
include_once './inc/page_header.php';
$user_id = api_get_user_id ();  
include_once './inc/page_header.php';
$hait_query = mysql_query('select code from view_course_user');
while($hait_row = mysql_fetch_row($hait_query))
{
    $hait_rows[] = $hait_row[0];
}
$hait_arr = array_count_values($hait_rows);
$timenow = time();
$timenumb = 0;
$star_query = mysql_query('select t1.code as code,t1.title,t1.description9,t2.code as code2,t2.id as id,start_date from course as t1,course_category as t2 where t1.category_code=t2.id and t1.status=0 and t2.status=0 order by start_date desc limit 8');
while($star_row = mysql_fetch_assoc( $star_query ))
{
    if($timenow-strtotime($star_row['start_date']) < 1209600){
        $timenumb++;
    }
    $star_rows[] = $star_row;
}
   
?>

    <!-- 导航结束 -->
     <div class="clear"></div> 
	<div class="m-moclist">
	    <div class="g-flow" id="j-find-main"> 
                <div class="b-30"></div>
                <div class="g-container f-cb">
                     <!--右侧-->
		  <div class="col-md-9">
                     <div class="course-content">
                         <h3 class="tab-hd">
                             <span id="new_course" class="ft_yahei ft14 mb20 current">热门课程</span>
                             <span id="hot_course" class="ft_yahei ft14 mb20" style="margin-left:20px;">最新课程
                               <?php  if($timenumb){ ?>
                                 <span class="top10-mark">
                                    <em class="font"><?=$timenumb?></em>
                                  </span>
                               <?php }?>
                             </span>
                              <span id="course_source" class="ft_yahei ft14 mb20" style="margin-left:20px;">课程资源</span>
                         </h3>
                         <div id="layout-t">
                             <!--热门课程-->
                             <div id="hotcourse" class="popular_courses">
<?php  
                     $hait_num = 0;
                     foreach($hait_arr as $hait_k=>$hait_v)
                     {
                          $cod_result = mysql_query("select t1.title as title,t1.description9,t2.code as code2 from course as t1,course_category as t2 where t1.code='{$hait_k}' and t1.category_code=t2.id");
                          $code_row = mysql_fetch_assoc($cod_result);
                            if($hait_num==8){break;}     
                            if($code_row['description9']  &&  file_exists('../../storage/courses/'.$hait_k.'/'.$code_row['description9'])){   
                                $despath='../../storage/courses/'.$hait_k.'/'.$code_row['description9'];
                            }else if(file_exists("../../storage/category_pic/".$code_row['code2'])){  
                                $despath="../../storage/category_pic/".$code_row['code2'];
                            }  else {   
                                $despath="../../portal/sp/images/default.png";
                            }
                            $wrap=($hait_num == 3 || $hait_num == 7) ? ' wrap-left wrap-right' : ' wrap-left';

                            if(empty($_SESSION['_user']['code'][$hait_k])){
                                $token_key = md5(rand(88,88888));
                                $_SESSION['_user']['code'][$hait_k] = $token_key;
                            }else{
                                $token_key = $_SESSION['_user']['code'][$hait_k];
                            }

?>                                 
                                 <div class="subject-wrap<?=$wrap?>">
                                     <a onclick="kecheng('<?=$hait_k?>','<?=$code_row['title']?>','<?=$token_key?>');" target="_blank" id="courDetailTo" class="subj-img-link"  style="height:118px;" title="查看课程详情">
                                         <img src="<?=$despath?>" width="210" height="118" style="margin:-1px 0 0 -1px;">
                                     </a>
                                     <p class="newSubname" align="left" style="margin-bottom:6px;">
                                         <a onclick="kecheng('<?=$hait_k?>','<?=$code_row['title']?>','<?=$token_key?>');" title="<?=$code_row['title']?>">
                                         <?php
					                        echo substr ($code_row['title'],11);
                                         ?>
                                         </a>
                                     </p>
                                    <span class="fl"></span>                                   
                                     <span class="people fl"><?=$hait_v?>人在学</span>
                                 </div>
<?php
                       $hait_num++;          
}
?>                                 
                             </div> 
                             <!--热门课程结束-->
                             <!--最新课程-->
                             <div id="newcourse" class="popular_courses" style="display:none;">
<?php 
                              foreach($star_rows as $star_k=>$star_v){
                                  if(empty($_SESSION['_user']['code'][$star_v['code']])){
                                      $token_key = md5(rand(88,88888));
                                      $_SESSION['_user']['code'][$star_v['code']] = $token_key;
                                  }else{
                                      $token_key = $_SESSION['_user']['code'][$star_v['code']];
                                  }
                                  $wrap=($star_k == 3 || $star_k == 7) ? ' wrap-left wrap-right' : ' wrap-left'; 
?>                                 
                                 <div class="subject-wrap<?=$wrap?>">
                                     <a onclick="kecheng('<?=$star_v['code']?>','<?=$star_v['title']?>','<?=$token_key?>');" target="_blank" id="courDetailTo" class="subj-img-link"  style="height:118px;" title="查看课程详情">
<?php 
if($star_v['description9']  &&   file_exists('../../storage/courses/'.$star_v['code'].'/'.$star_v['description9'])){
  $imgpath='../../storage/courses/'.$star_v['code'].'/'.$star_v['description9'];      
}else if(file_exists('../../storage/category_pic/'.$star_v['code2'])){
  $imgpath='../../storage/category_pic/'.$star_v['code2'];  
}else{
   $imgpath= "../../portal/sp/images/default.png";
}
    
    ?>                                         
                                         <img src="<?=$imgpath;?>" width="210" height="118" style="margin:-1px 0 0 -1px;border:1px solid #f8f8f8;">
                                     </a>
                                     <p class="newSubname" align="left" style="margin-bottom:6px;">
                                         <a onclick="kecheng('<?=$star_v['code']?>','<?=$star_v['title']?>','<?=$token_key?>');" title="<?=$star_v['title']?>">
                                        <?php
					                         echo substr ($star_v['title'],11);
                                        ?></a>
                                     </p>
<?php
$sql11="SELECT DISTINCT `username` FROM   `view_course_user`  WHERE `code` =".$star_v['code'];
$count_use_data=  api_sql_query_array_assoc($sql11);
$count_users=count($count_use_data); 
?>                                     
                                    <span class="fl"></span>
                                     <span class="people fl"><?=$count_users?>人在学</span>
            <?php  if($timenow-strtotime($star_v['start_date']) < 1209600){ ?>
             <span style="display: inline-block;
                        color: white;
                        height: 20px;  
                        width:30px; 
                        font-size: x-small;
                        clear: both;
                        margin-top: -185px;
                        margin-left: 115px; 
                        background: url('./images/newcourse.png') repeat-x;
                       ">
                 <em>new</em>
             </span>
           <?php } ?>
                                 </div>
<?php
                              }
?>                                 
                             </div>
                             <!--最新课程结束-->
                              <!--课程资源--> 
                               <div id="course_source" class="popular_courses" style="display:none;">
                                   <div class="job-name">
<?php
      $sql="select id,title,subclass from setup order by custom_number";
      $res=  api_sql_query_array($sql);
      foreach($res as $resk=>$resv){
?>                                       
     <a class="active" onclick="divshow(<?=$resv[0]?>);" title="<?=$resv[1]?>"><?=$resv[1]?></a>
<?php
      }
?>                                       
                                   </div> 
                                   <h3 class="jobtree-title"> </h3>  

      <div class="j-list lists" id="j-list" style="padding:6px 0 20px 0;">
	<div class=" u-content f-cb">
	    <div class="cnt f-cb" id="auto-id-k6s3rv2cJswp3exB">
                <article> 
<?php
        $j=0;
        foreach($res as $resk=>$resv){
          $subclass=$resv[2];
          $subarr=explode(',',$subclass);
          $sus=($j===0) ? 'block' : 'none';
?>                    
                <div class="module_content" id="div<?=$resv[0]?>" style="display:<?=$sus?>;">
<?php
$color = array('blue','green', 'purple');
$i=0;
foreach($subarr as $subk=>$subv){
      if($subv){
      $subvre=mysql_query('select name from course_category where id='.$subv);
      $subarr=mysql_fetch_assoc($subvre);
      if($subarr){
?>                        
                  <div class="course-list <?=$color[$i]?>">                    
                      <div class="case-list blue">
                          <div class="case-title"> 
                              <a onclick="return false;"><?=$subarr['name']?></a>
                          </div>
                          <dl class="case-describe">
<?php
       $subquery=mysql_query("select id,name from course_category where parent_id='".$subv."'");
        while($subrow=mysql_fetch_assoc($subquery)){      
?>                              
                              <dd> <a class="sun" href="course_catalog.php?category_id=<?=$subrow['id']?>"><?=$subrow['name']?></a>  </dd>
        <?php }?>                              
                          </dl>
                      </div>         
                  </div>
<?php
$i++;if($i==3){$i=0;}
      }
     } 
         }
?>          
                </div>
<?php
   $j++;
   
}
?>                    
                </article>
            </div>
         
         </div>
 
     </div>
 
 </div>
                             <!--课程资源结束--> 
                             
                        </div>
                     </div> 
                  </div>
                    <!--右侧结束--> 
<?php
        $time_query=mysql_query('select t1.user_id from vmdisk_log as t1,user as t2 where t1.close_status=0 and t1.user_id=t2.user_id and t2.status=5 group by user_id');
        while($time_row=mysql_fetch_assoc($time_query)){
            $query_time=mysql_query("select start_time,end_time from vmdisk_log where manage=0 and user_id={$time_row['user_id']}");
            while($user_row=mysql_fetch_assoc($query_time)){
                if(!empty($user_row['start_time']) && !empty($user_row['end_time'])){ 
                $start_time=strtotime($user_row['start_time']);
                 $end_time=  strtotime($user_row['end_time']);
                 if($end_time > $start_time){
                 $study_time=$end_time-$start_time;
                 $study_times=intval($study_time/60);
                 $user_study_time[$time_row['user_id']]+=$study_times;
                 }
               }  
            }          
        } 
        arsort($user_study_time);
?>                   
                 <!--左侧开始-->
                   <div class="col-md-3">
                       <div class="side-content">
                           <div class="users-top10">
                               <div class="users-top10-header">学霸排名
                               </div>
                               <div class="users-top10-content">
<?php
                           $function_num=0;
                           foreach($user_study_time as $user_study_k => $user_study_v){
                               $questr=intval($user_study_v);
                               if($questr > 60){
                                   if($questr > 1440){
                                     $danum=intval($questr/1440);
                                     $Remainder=$questr%1440;
                                     $hours=0;
                                     if($Remainder > 60){
                                       $hours=intval($Remainder/60);    
                                     }
                                     $hours=$hours ? $hours.'小时' : '';
                                     $timestr=$danum.'天'.$hours;
                                  }else{
                                      $hours=intval($questr/60);
                                      $timestr=$hours.'小时';
                                  }
                               }else{
                                   $timestr=$questr.'分钟';
                               }   
                            if($function_num === 0){
                                 $style='style="color: rgb(231, 121, 121); font-size: 18px;"';
                               }else if($function_num === 1){
                                 $style='style="color: rgb(239, 156, 38); font-size: 17px;"';  
                               }else if($function_num === 2){
                                 $style='style="color: rgb(200, 208, 19); font-size: 16px;"';
                               }else if($function_num === 10){
                                 break;  
                               }else{
                                 $style='';  
                               }
                               $user_query=mysql_query('select username,picture_uri from user where user_id='.$user_study_k);
                               $user_row=mysql_fetch_row($user_query);
                               $user_images=$user_row[1] ? api_get_path ( WEB_PATH ) . 'storage/users_picture/'.$user_row[1] : URL_APPEND.'portal/sp/images/people_small.gif';
?>                                   
                                  <div class="rank-row">
                                      <div class="col-md-5">
                                          <a onclick="return false;" target="_blank" style="color:#121212;font-size:12px;white-space: nowrap;">
                                              <img class="userimg" src="<?=$user_images?>" />
                                              <span class="user-level"></span> 
                                              <?=$user_row[0]?>
                                          </a>
                                      </div>
                                      <div class="col-md-7">
                                          <span class="pull-right sortMinute" <?=$style?>>
                                              <strong><?=$timestr?></strong>
                                          </span>
                                      </div>
                                  </div>

<?php
                             $function_num++;
                           }
?>                                   
                               </div>
                           </div>
                       </div>
                   </div> 
                 <!--左侧结束-->
                     
                     
                     
                     
		</div>
	    </div>
     </div>
	<!-- 底部 -->
<?php
        include_once './inc/page_footer.php';
?>
        
<div id="kecheng">
            <div class="tag-title">
                <div id="kecheng-2"></div>
                <div id="chahao">×</div>
            </div>
            <div id="kecheng-3"></div>
</div>       
 </body>
</html>
<style>
    body{
        font-size:14px;
    }
    #kecheng{
        -webkit-transition:all .2s ease;
        width:60%;
        padding-bottom:10px;
        background:#fff;
        position:fixed;
        z-index:999;                              
        left:80%;
        border:1px solid #13A654;
        display:none;
        height:300px;
    } 
    .tag-title{
      height:50px;
      width:100%;
      background:#13A654;
      color:#fff;
    }
    #kecheng-2{
        float:left;
        line-height:50px;
        font-size:20px;
        margin-left:10px;
    }
    #chahao{
        float:right;
       width:20px;
       height:20px;
        cursor:pointer;
       font-size:20px;
       line-height:20px; 
    }
    #chahao:hover{
        color:#FF6600;
        cursor:pointer;
    }
</style>

 <?php if(api_get_setting ( 'lm_switch' ) == 'true'){?>
  <style>
     .col-md-9 .tab-hd .current {
         border-bottom: 3px solid #357CD2;
     }   
     .job-name a {
         background: #357CD2;
     }
     .users-top10 .users-top10-header {
         border-left: 5px solid #357CD2;
     }
     .users-top10 .users-top10-content {
         color: #357CD2;
     }
     .people {
background:url("../sp/images/people_lm.png") no-repeat;
}
.col-md-9 .newSubname a:hover {
    color:#357cd2;
}
  </style>
      <?php   }   ?>
<script type="text/javascript">
    var settimes=1;
               $(function(){
                   $('#chahao').click(function(){
                       $("#kecheng").css('display','none');
                       $("#kecheng").css('-webkit-transform','translateX(0%)');
                    $("#kecheng").css('-moz-transform','translateX(0%)');
                    $("#kecheng").css('-o-transform','translateX(0%)');
                    settimes=1;
                    var gorecode=$("#goreferen").val();
                    if(gorecode){
                      $("#code"+gorecode).css('color','red');  
                      $("#code"+gorecode).html('已选修');
                    }
                   });
               });

    function kecheng(code,title,token_key){
        var bht=document.documentElement.clientHeight;
        var Hkecheng=$("#kecheng").height();
        $.ajax({
            url:'course_info.php',
            type:'GET',
            data:'code='+code+'&cate=<?=$parent_cateid[0]?>+&id=<?=$id?>&category_id=<?=$category_id?>&'+token_key+'=token',
            dataType:'html',
            success:function(er) {
                if (er !== 'err') {
                    var ter = /firefox/;
                    if (ter.test(navigator.userAgent.toLowerCase())) {
                        itop = document.documentElement.scrollTop;
                    }
                    if ("ActiveXObject" in window) {
                        $("#kecheng").css('left', '20%');
                        itop = document.documentElement.scrollTop;
                    }

                    var zht = bht / 2 - Hkecheng / 2;
                    $("#kecheng").css('top', zht);
                    $("#kecheng").css('display', 'block');
                    $("#kecheng-2").html(title);
                    $("#kecheng-3").html(er);


                    if (settimes === 1) {
                        $("#kecheng").css('-webkit-transform', 'translateX(-140%)');
                        $("#kecheng").css('-moz-transform', 'translateX(-140%)');
                        $("#kecheng").css('-o-transform', 'translateX(-140%)');

                        setTimeout(function () {
                            $("#kecheng").css('-webkit-transform', 'translateX(-100%)');
                            $("#kecheng").css('-moz-transform', 'translateX(-100%)');
                            $("#kecheng").css('-o-transform', 'translateX(-100%)');
                            settimes++;
                        }, 200);
                    } else {
                        $("#kecheng").css('-webkit-transform', 'translateX(-100%)');
                        $("#kecheng").css('-moz-transform', 'translateX(-100%)');
                        $("#kecheng").css('-o-transform', 'translateX(-100%)');
                    }
                }
            }
        });
    }
   function divshow(id){
       $(".module_content").css('display','none');
       $("#div"+id).css('display','block');

       if(document.documentElement.clientHeight-67 < document.documentElement.offsetHeight-4) {
               $("#footer").css('position',' absolute');
               $("#footer").css('top', '');
       }else{
           var footerHeight=$("#footer").height();
           var footerTop=($(window).height()-footerHeight-2)+'px';

           $("#footer").css('position',' absolute');
           $("#footer").css('top',footerTop);
       }
   }
</script>

