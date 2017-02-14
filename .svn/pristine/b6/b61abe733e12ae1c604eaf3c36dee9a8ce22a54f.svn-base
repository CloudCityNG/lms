<?php
include_once ('../inc/global.inc.php');
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
<!--     <div class="l-login" id="login-tip">
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
     </div>   -->
      
       <!--登录结束--> 

                   <!--侧边栏-->
<?php 
                   include_once 'left_header.php';
?>
                   <!--左侧结束-->
                   <!--右侧-->
                   
                   <div class="g-mn1">
                       <div class="g-mn1c">
                           <div class="j-list lists">
                                <h3 class="b-title">FAQ</h3>
                               <?php
	$sql = "SELECT question,answer FROM    tbl_faq "; 
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );

	$users = array ();
	while ( $user = Database::fetch_row ( $res ) ) {
               $user[0]=  htmlspecialchars_decode ($user[0]);
               $user[1]=  htmlspecialchars_decode($user[1]);
               $users [] = $user;               
        }
        foreach ($users  as  $value){
              
        }
                               ?>
          <div class="Faq-all">
               <?php  
                                                 foreach($users as $value){
                                            ?>  
              <div class='f-question'>
                  <h3 class='faq-title'>问题：<?php  echo  strip_tags($value[0]);?></h3>
                  <div class='faq-answer'><span class='a-font'>答案：</span><?php echo strip_tags($value[1]);?></div>
              </div>
               <?php
                                                 }
                                              ?>
          </div>
     </div>
     <div class="b-30"></div>
                                
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
