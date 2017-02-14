<?php
include_once ('../inc/global.inc.php');

$user_id=$_SESSION['_user']['user_id'];

if($user_id === null){
    header('Location:'.URL_APPEND.'portal/sp/login.php');
    exit;
}
$user_team=mysql_fetch_row(mysql_query('select teamId from user where user_id='.$user_id));
if($user_team[0]){
        $_SESSION['_user']['teamId']=$user_team[0];
}
if(!$_SESSION['_user']['teamId']){
/*
创建战队验证
 *  */
        $team_name=getgpc('teamName','P');
        $team_description=getgpc('description','P');
        $team_sub=getgpc('team_sub','P');
         $teamId=getgpc('teamId','P');
        if($team_sub === '创建战队' && !empty($team_name) && !empty($team_description)){
            mysql_query("insert tbl_team (id,teamNode,teamName,teamAdmin,description)values(null,'0','{$team_name}',{$user_id},'{$team_description}')");
            $team_id=mysql_insert_id();
            if($team_id){
                mysql_query('update user set teamId='.$team_id.' where user_id='.$user_id);
                $_SESSION['_user']['teamId']=$team_id;
            }
        }else if($teamId){
                     /*
                      申请战队验证
                       *  */
                    $status_query=mysql_query("select id from tbl_cation where user_id={$user_id} and status=0");
                    $status_row=mysql_fetch_row($status_query);
                    if($status_row[0]){
                        $status=1;
                    }
                    if($status !== 1){
                            if(!empty($teamId)){
                                mysql_query("insert tbl_cation(id,teamId,user_id,status)values(null,$teamId,$user_id,0)");
                                $status=1;
                            }
                    }
        }else{
            
 /*
登陆验证是否有所属战队
 *  */
    $user_te_query=mysql_query('select teamId from user where user_id='.$user_id);
    $user_team=mysql_fetch_row($user_te_query);
    if($user_team[0]){
        $_SESSION['_user']['teamId']=$user_team[0];
    }
    
}    
}
if($status !== 1){
     $team_query=mysql_query('select id,teamName from tbl_team');
                            while($team_row=  mysql_fetch_row($team_query)){
                                $count_query=mysql_query('select count* from user where teamId='.$team_row[0]);
                                $count_row=mysql_fetch_row($count_query);
                                if($count_row[0] < 5){
                                    $count_rows[]=$team_row;
                                }
                            }
}

if($_SESSION['_user']['teamId']){
    $contest_query=mysql_query('select id,matchName from tbl_contest where status=1');
    while($contest_row=mysql_fetch_row($contest_query)){
        $contest_rows[]=$contest_row;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CTF首页</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel="stylesheet" type="text/css" href="css/base.css">
         <link rel="stylesheet" type="text/css" href="css/media-style.css">
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript">
$(function(){
<?php
   if($_SESSION['_user']['teamId']) {
       echo ' $("#TB-window").css("display","block");'; 
   }
?>    
    $("#user").focus(function(){
        var txt_value=$(this).val();
        if(txt_value==="战队名称"){
            $(this).val("");
        }
    });
    $("#user").blur(function(){
        var txt_value=$(this).val();
        if(txt_value===""){
           $(this).val("战队名称");
        }
    })
    $("#password").focus(function(){
        var txt_value=$(this).val();
        if(txt_value==="战队口号"){
            $(this).val("");
        }
    })
    $("#password").blur(function(){
        var txt_value=$(this).val();
        if(txt_value===""){
           $(this).val('战队口号');
        }
    })
    
    $("#c1").click(function(){
        $(".creat-team").hide();
        $(".sele-team").show();
        $("#c2").show();
        $(this).hide();
    })
     $("#c2").click(function(){
        $(".creat-team").show();
        $(".sele-team").hide();
        $("#c1").show();
        $(this).hide();
    })
})
</script>
    </head>
    <body id="index_2">
<?php
           if($_SESSION['_user']['teamId']){
?>        
        <div class="f-window" id="TB-window">
             <div class="f-main">
                    <div class="c-contest">选择比赛</div>
                    <div class="sele-team contest-t">
                       <ul class="contest-ul">
<?php 
                           foreach($contest_rows as $contest_k=>$contest_v){
?>
                           <li>
                               <a href="index.php?id=<?=$contest_v[0]?>"><?=$contest_v[1];?></a>
                           </li>
<?php        
                            }
?> 
                       </ul>
                        
                    </div>
             </div>
          </div>
<?php
              }
?>        
     <div class="f-wrap" id="CTF">
<?php
    if($status !== 1){
        if(count($count_rows)){
?>         
            <span class="choice-team c-con" id="c1">选择战队</span>

<?php
                }
?>     
            
             <span class="right-team c-con" id="c2">创建战队</span>
<?php }?>             
            <div class="f-main">
                <div class="c-logo"></div>
<?php
    if($status === 1){
?>
                <div class="sucess">已申请战队，请等待战队队长同意!</div>
<?php
    }else{
?>                
                <div class="creat-team" >
                        <form action="index_2.php" method="post">
                                <input type="text" id="user" class="i-name" name="teamName" value="战队名称"/>
                                <input type="text" id="password" class="i-name"  name="description" value="战队口号"/>
                                <input type="submit" id="submit" class="creat-btn"  value="创建战队" name="team_sub"/>
                        </form>    
                </div>
                <form action="index_2.php" method="post">                  
                        <div class="sele-team" style="display:none;">       
                            <div class="team-inner">
<?php 
if(count($count_rows)){
foreach($count_rows as $count_k=>$count_v){?>                                    
                                <div class="team-name">
                                    <input  type="radio" class="c-single" value="<?=$count_v[0];?>"  name="teamId" /><?=$count_v[1]?>
                                </div>
<?php }
}  
?>                                  
                            </div>
                             <input type="submit" id="submit" class="creat-btn c-btn"  value="选择战队"/>
                        </div>
              </form>    
<?php }?>                
            </div>
        </div>
    </body>
</html>
