<?php
header("Content-type:text/html;charset=utf-8");
include ('../inc/global.inc.php');
$res=api_sql_query ( $sql, __FILE__, __LINE__ );

$m_table = Database::get_main_table ( 'tbl_match' );
$u_table = Database::get_main_table ( 'user' );
$ev_table= Database::get_main_table ( 'tbl_event' );
//获取当前题目ID
$qid=$_GET['qid'];
//$page=$_GET['page'];//页码
$page=1;
$pageSize=10;

//获取用户id 先假设=1
$uid=$_SESSION['_user']['user_id'];

function getEventIdByQid($qid){
    global $ev_table;
    $sql='SELECT id FROM '.$ev_table.' WHERE examId='.$qid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row['id']; 
}
$eid= getEventIdByQid($qid);

//根据用户id，查出战队id
function getTidByUid($userId){
    global $u_table;
    $count=count($top_arr);
        $sql ="SELECT teamId FROM $u_table WHERE user_id=".$userId;
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        $row = mysql_fetch_assoc($res);
        return $row['teamId']; 
}

//根据战队id，和题目id 查看队友的答案，以及报告结果(返回二维数组)
function getAnswerByids($uId,$qid){
    global $m_table;
    $sql ="SELECT answer,report,fraction FROM $m_table WHERE user_id=".$uId." AND event_id=".$qid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row;
}

$tid=getTidByUid($uid);//战队id

function getLeaderId($tid){
   $sql='select teamAdmin from tbl_team where id='.$tid;
   $res = api_sql_query ( $sql, __FILE__, __LINE__ );
   $row = mysql_fetch_assoc($res);
   return $row['teamAdmin'];
}

//根据战队 id获取 成员名数组
function getTeamMateIdArr($tid){
    $sql ='select user_id,username from user where teamId ='.$tid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    while($row = mysql_fetch_assoc($res)){
        $rows[]=$row;
    }
    return $rows;
}

$fu=getTeamMateIdArr($tid);
//var_dump($fu);
//获取用户名
function getUsernameByUid($uid){
    $sql ='select username from user where user_id ='.$uid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row['username'];
}

//
function get_need_arr(){
    global $tid,$eid;
    $arr=getTeamMateIdArr($tid);
    foreach ($arr as $v){
        $answerArr=getAnswerByids($v['user_id'],$eid);
        if(!empty($answerArr)){
            $need_row=array_merge($answerArr,$v);
            $need_arr[]=$need_row;
        }else{
            $need_arr[]=$v;
        }
    }
  //  getUsernameByUid($arr[]);
    return $need_arr;
}
$needArr=get_need_arr();

$lid = getLeaderId($tid);


?>

<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title>CTF首页</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel="stylesheet" type="text/css" href="css/base.css">
         <link rel="stylesheet" type="text/css" href="css/media-style.css">
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    </head>
    <script type="text/javascript">
//        $(document).ready(function(){
//		$('#Nav .navition').bind('click',function(){
//			var thisIndex = $(this).index();
//			$(this).children('a').addClass('selected').siblings().removeClass('selected');
//			$('.g-mn1 .g-mn1c').eq(thisIndex).show().siblings().hide();	
//		})
//        })


$(function(){
    $("#Login").click(function(){
        $("#login-tip").css("display","block");
    })
    $("#login-tip .l-close").click(function(){
        $("#login-tip").css("display","none");
    })
})
    </script>
    <body>
        <!--登录-->
     <div class="l-login" id="login-tip">
         <span class="l-close">×</span>
         <section class="l-wrap">
             <h1 class="l-logo"></h1>
             <form> 
                 <input type="text" value="username">
                 <input type="password" value="Password">
                 <a class="forget-pass" href="#">忘记密码?</a>
                 <button class="l-blue">登录</button>
                 <a class="l-register" href="#">注册</a>
             </form>
         </section>
     </div>   
      
       <!--登录结束--> 
        <section id="M-moclist">
               <div class="g-flow">
                   <div class="g-container f-cb f-pr">
                   <!--侧边栏-->
                   <div class="g-sd1 f-pr">
                       <div class="b-200"></div>
                       <ul class="nav" id="Nav">
<!--                          <li class="u-login f-pr" id="Login">
                               <a href="#">登录</a>
                               <span class="u-people"></span>
                           </li>-->
                           <li class="navition selected"><a href="index.html">题目</a></li>
                           <li class="navition"><a href="score-table.html">积分榜</a></li>
                           <li class="navition"><a href="#">公告</a></li>
                           <li class="navition"><a href="#">决赛日程</a></li>
                           <li class="navition"><a href="#">大赛简介</a></li>
                           <li class="navition"><a href="#">大赛新闻</a></li>
                           <li class="navition"><a href="#">组织单位</a></li>
                           <li class="navition"><a href="#">大赛规则</a></li>
                           <li class="navition"><a href="#">决赛场地</a></li>
                           <li class="navition"><a href="#">FAQ</a></li>
                       </ul>
                   </div>
                   <!--左侧结束-->
                   <!--右侧-->
                   
                   <div class="g-mn1">
                       <div class="g-mn1c">
<!--                              <div class="login">
                                  <a class="l-submit register">注册</a>
                                  <input class="l-submit now" type="submit" value="登录">
                                  
                                  <input class="login-text" type="text" >
                                  <input class="login-text password" type="text">
                                  
                              </div>-->
                           <!--题目-->
                           <div class="j-list lists">
                                <h3 class="b-title">题目</h3>
                                <div class="b-15"></div>



<table border='1'>
    <tr>
        <td>用户名</td>
        <td>答案/报告</td>
        <td>分数</td>
        
    </tr>
     <?php 
     foreach($needArr as $v){
            echo '<tr>';
            if($v['user_id']==$uid){ 
                echo '<td>'.$v['username'].'(您):</td>';
            }elseif($v['user_id']==$lid){
                echo '<td>'.$v['username'].'(队长):</td>';
            }else{
                echo '<td>'.$v['username'].'：</td>';
            }    
       
            
            if($v['answer']){
                echo '<td>'.$v['answer'].'</td>';
            }elseif($v['report']){
                echo '<td><a href="'.$v["report"].'">报告的图标放1个</a></td>';
            }else{
                echo '<td>无</td>';
            }
            echo '<td>'.$v['fraction'].'</td>';
            echo '</tr>';
     }
     
    
     ?>
    
</table>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                 </div>
                           <!--题目页面结束-->
                        
                       </div>
                   </div>
                   <!--右侧结束-->
                   </div>
               </dv>
        </section>
    </body>
</html>
