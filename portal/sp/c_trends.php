<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");

//最新课程
$star_query=mysql_query('select t1.code as code,t1.title,t1.description9,t2.code as code2,t2.id as id,start_date from course as t1,course_category as t2 where t1.category_code=t2.id and t1.status=0 and t2.status=0 order by start_date desc limit 8');
while($star_row=mysql_fetch_assoc($star_query)){
    $star_rows[]=$star_row;
}
 
//最新赛事
$sql="select  id,matchName  from  tbl_contest  WHERE  `status`=1   order by matchStime  desc   limit  4";
$contests= api_sql_query_array_assoc($sql, __FILE__, __LINE__);
 
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
 .people {
background:url("../sp/images/people_lm.png") no-repeat;
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
                        <a class="f-thide f-f1" title="我的岗位" href="my_post.php">我的岗位</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="最新动态" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="最新动态" href="c_trends.php" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">最新动态</a>
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
           <div class="cw-trends">
               <div class="N-trends">
               <h3 class="Trends-pop">最新课程</h3>
               <div class="Tr-curr">
                   <?php 
                    foreach($star_rows as $star_k=>$star_v){
                        $wrap=($star_k == 3 || $star_k == 7) ? ' wrap-right' : ''; 
                    ?>     
                      <div class="subject-wrap wrap-left wrap-0 <?=$wrap?>">
                       <a onclick="kecheng('<?=$star_v['code']?>','<?=$star_v['title']?>');"   class="subj-img-link" style="height:118px;">
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
                          <a onclick="kecheng('<?=$star_v['code']?>','<?=$star_v['title']?>');" title="<?=$star_v['title']?>"><?=substr ($star_v['title'],11)?></a>
                       </p>
                       <?php
                        $sql11="SELECT DISTINCT `username` FROM   `view_course_user`  WHERE `code` =".$star_v['code'];
                        $count_use_data=  api_sql_query_array_assoc($sql11);
                        $count_users=count($count_use_data); 
                        ?>                                     
                        <span class="fl"></span>
                         <span class="people fl"><?=$count_users?>人在学</span>
                   </div>
               <?php   }   ?>
               </div>
           </div>
               <!--最新赛事-->
           <div class="Tr-event">
                  <h3 class="Trends-pop">最新赛事</h3>  
                  <ul class="Tr-page">
                      <?php  
                   foreach ($contests  as  $k=>$val){   
                       if($k==0){
                           $clas="fT-one";
                       }else if($k==1){
                           $clas="fT-two";
                       }else if($k==2){
                           $clas="fT-three";
                       }else if($k==3){
                           $clas="fT-four";
                       }
                      ?>
                      <li class="<?=$clas?>">
                          <a href="../../main/ctf/index.php?id=<?=$val['id']?>">
                              <div class="fT-list-info">
                                  <h4><?=$val['matchName']?></h4>
                              </div>
                          </a>
                      </li>
                      <?php } ?>
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
<?php 
include './inc/page_footer.php';
?>
<div id="kecheng">
            <div class="tag-title">
                <div id="kecheng-2"></div>
                <div id="chahao">×</div>
            </div>
            <div id="kecheng-3"></div>
</div>  
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
                
   function kecheng(code,title){
       $.ajax({
           url:'course_info.php',
          type:'GET',
          data:'code='+code,
      dataType:'html',
      success:function(er){
           if(er === 'err'){
             location.href='login.php';  
           }else{
            var bht=document.documentElement.clientHeight;
            var Hkecheng=$("#kecheng").height();
            var ter=/firefox/;
                   //alert(navigator.userAgent.toLowerCase());
                  if(ter.test(navigator.userAgent.toLowerCase())){
                      itop=document.documentElement.scrollTop;  
                      }     
                  if("ActiveXObject" in window){
                      $("#kecheng").css('left','20%');
                      itop=document.documentElement.scrollTop;
                   }  
               
                    var zht=bht/2-Hkecheng/2;
                    $("#kecheng").css('top',zht);
                    $("#kecheng").css('display','block');
                    $("#kecheng-2").html(title);
                    $("#kecheng-3").html(er);
           
    
                     if(settimes === 1){
                    $("#kecheng").css('-webkit-transform','translateX(-140%)');
                    $("#kecheng").css('-moz-transform','translateX(-140%)');
                    $("#kecheng").css('-o-transform','translateX(-140%)');
                   
                    setTimeout(function(){
                    $("#kecheng").css('-webkit-transform','translateX(-100%)');
                    $("#kecheng").css('-moz-transform','translateX(-100%)');
                    $("#kecheng").css('-o-transform','translateX(-100%)');
                     settimes++;
                    },200);
                   }else{
                    $("#kecheng").css('-webkit-transform','translateX(-100%)');
                    $("#kecheng").css('-moz-transform','translateX(-100%)');
                    $("#kecheng").css('-o-transform','translateX(-100%)');   
                   }
           }
      }       
       });
   }
 
</script>
</body>
</html>


