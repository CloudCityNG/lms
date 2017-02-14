<?php
$gid=$_SESSION['tbl_contest'];

$user_id=$_SESSION['_user']['user_id'];
if($user_id === null){
    header('Location:'.URL_APPEND.'portal/sp/login.php');
    exit;
}

if($_SESSION['tbl_contest'] === 0){
     header('Location:'.URL_APPEND.'main/ctf/index_2.php');
     exit;
}
$team_admin=mysql_fetch_row(mysql_query('SELECT `teamAdmin`,`tbl_team`.`id` FROM `user` INNER JOIN `tbl_team` WHERE `user`.`user_id`='.$_SESSION['_user']['user_id'].' AND `user`.`teamId`=`tbl_team`.`id`'));

if($team_admin[0] === $_SESSION['_user']['user_id']){
    $team_query=mysql_query('SELECT `tbl_cation`.`id`,`user`.`username` FROM `tbl_cation` INNER JOIN `user` WHERE `tbl_cation`.`teamId`='.$team_admin[1].' and tbl_cation.status=0 and user.user_id=tbl_cation.user_id');
    while($team_row=mysql_fetch_row($team_query)){
        $team_rows_2[]=$team_row;
    }
}

if(count($team_rows_2)){
?>
                <!--审批-->
     <div class="l-login" id="login-tip">
         <span class="l-close">×</span>
         <section class="l-wrap" style="width:500px;padding:20px 10px;background-color:#fff;">
             <form> 
                 <table style="text-align:center;" class="apply-table">
 <?php 
             foreach($team_rows_2 as $team_k => $team_v){
?>
                 <tr id="tr<?=$team_v[0]?>" class="team_class">
                     <td width="60%"><?=$team_v[1]?>&nbsp;审核战队成员&nbsp;&nbsp;</td>
                     <td><input class="apply-input welcome" type="button" onclick="agree(<?=$team_v[0]?>);" style="cursor:pointer;" value="欢迎加入"/></td>
                     <td><input class="apply-input reflect" type="button" onclick="refusal(<?=$team_v[0]?>);"  style="cursor:pointer;"  value="残忍拒绝"/></td>
                 </tr>
<?php                 
             }
 ?>           
                 </table>   
             </form>
         </section>
     </div>   
      
       <!--审批结束--> 
<?php }?>       
        <section id="M-moclist">
               <div class="g-flow">
                   <div class="g-container f-cb f-pr">
                   <!--侧边栏-->
                    <div class="g-sd1 f-pr" id="sidebar">
                        <div class="b-50"></div>
                        <div class="ctf-logo">
                           <img src="images/ctf-logo.png" alt="云资源教育平台-51CTF的LOGO图片" title="云资源教育平台-51CTF的LOGO图片">
                        </div>
                        <div class="b-50"></div>
                        <div class="viewport">
                            <div class="overview">
                                <ul class="nav navbar" id="Nav">
                    <?php if(count($team_rows_2)){?>        
                            <li class="u-login f-pr" id="Login">
                                 <a href="#" title="申请加入战队">
                                     <span class="application">审核战队成员</span>
                                     <span class="u-people"></span>           
                                 </a>
                                          
                            </li>
                    <?php }?>       
                            <li class="navition color_1 selected">
                                <a href="index.php?id=<?=$gid?>" title="题目">
                                    <img src="images/forms.png" alt="比赛题目" title="题目">
                                    <span>题目</span>
                                </a>
                            </li>
                            <li class="navition color_2">
                                <a href="score-table.php" title="积分榜">
                                    <img src="images/widgets.png" alt="积分榜" title="积分榜">
                                    <span>积分榜</span>
                                </a>
                            </li>
                            <li class="navition color_3">
                                <a href="match_desc.php?id=<?=$gid?>" title="赛事公告">
                                     <img src="images/grid.png" alt="赛事公告" title=="积分榜">
                                    <span>公告</span>
                                </a>
                            </li>
<!--                           <li class="navition color_4">
                               <a href="#">
                                   <img src="images/calendar.png">
                                    <span>决赛日程</span>
                               </a>
                           </li>-->
                            <li class="navition color_5">
                                <a href="match_rule.php?id=<?=$gid?>" title="大赛简介">
                                      <img src="images/maps.png" title="大赛简介" alt="大赛简介">
                                    <span>大赛简介</span>
                                </a>
                            </li>
                            <li class="navition color_8">
                                <a href="match_rewad.php?id=<?=$gid?>" title="大赛简介">
                                    <img src="images/others.png" title="大赛简介" alt="大赛简介">
                                    <span>大赛规则</span>
                                </a>
                            </li>
<!--                           <li class="navition color_7">
                               <a href="team_match.php">
                                   <img src="images/explorer.png">
                                    <span>战队成绩</span>
                               </a>
                           </li>-->
                           <li class="navition color_6">
                      
<?php
                           if($team_admin[0] === $_SESSION['_user']['user_id']){
                               $isadmin=true;
?>                                <a href="team_manage.php" title="战队管理" alt="战队管理">
                                    <img src="images/gallery.png" alt="战队管理">
                                    <span>战队管理</span>
<?php
                           }else{
?>
                                  <a href="team_match.php" title="战队信息" alt="战队信息">
                                    <img src="images/gallery.png" alt="战队信息">
                                    <span>战队信息</span>
<?php
                           }
?>                                    
                                </a>
                            </li>
<!--                            <li class="navition color_9"><a href="#">决赛场地</a></li>-->
                            <li class="navition color_9">
                                <a href="faq_list.php" title="大赛FAQ">
                                    <img src="images/statistics.png" title="大赛FAQ" alt="大赛FAQ">
                                    <span> FAQ</span>
                                   
                                </a>
                            </li>
                        </ul>
                            </div>
                        </div>
                        
                    </div>
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
$(function(){
    $("#Login").click(function(){
        $("#login-tip").css("display","block");
    })
    $("#login-tip .l-close").click(function(){
        $("#login-tip").css("display","none");
    })
});
<?php if(count($team_rows_2)){?>
    function agree(id){
       ajaxfun(id,'agree');
    }
    
    function refusal(id){
        ajaxfun(id,'refusal');
    }
    function ajaxfun(id,judge){
         $.ajax({
            url:'judge.php',
         type:'POST',   
  dataType:'html',
          data:'id='+id+'&judge='+judge,
     success:function(err){
          console.log($(".team_class:visible").length);
         if(err === 'agree' || err === 'refusal'){
             $("#tr"+id).hide();
             if($(".team_class:visible").length === 0){
                 location.reload();
             }
         }else if(err === 'err'){
             alert('操作失败请重试！');
         }
     }     
        });
    }
<?php }?>   
    </script>