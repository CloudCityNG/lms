<?php
include_once ("../../../login.inc.php");
$platform_path='portal/sp/index.php';
    $err=$_GET["error"]; 
   if($err=="user_password_incorrect"){
       $error_msg='密码错误！' ; 
   }elseif($err=="account_inactive"){ 
       $error_msg='用户被锁定！' ;
   }elseif($err=="auth_user_expiration"){ 
       $error_msg='帐号过期！' ;
   }elseif($err=="auth_user_not_exsist"){ 
           echo "<script ype='text/javascript'>";
      $error_msg='用户不存在！' ;
   }elseif($err=="auth_failed"){  
       $error_msg='认证失败！' ;
    }elseif($err=="all_failed"){ 
    $error_msg='登录失败 - 用户名/密码错误！' ;
    }
    if($err=='seccode_error'){
      $error_msg='验证码错误';  
    }
    $loginres=mysql_query('select selected_value from settings_current where variable="login_switch"');
    $loginarr=mysql_fetch_row($loginres);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=api_get_setting ( 'siteName' )?>欢迎您</title>
<link href="../css/cn_student.css" rel="stylesheet" type="text/css" />
 <script src="../js/jquery-1.7.2.min.js" type="text/javascript"></script>
 <script type="text/javascript">
        function formatText(index, panel) {
            return index + "";
        }
      
        $(function () {
         
          var footerHeight=0;
     var footerTop=0;
     var footer=$("#footer");
     function positionFooter(){
         
         footerHeight=footer.height();
         footerTop=($(window).height()-footerHeight-2)+'px';
       
         //如果页面内容高度小于屏幕高度，div#footer将绝对定位到屏幕底部，否则div#footer保留它的正常静态定位。
         
         if($(document.body).height()< $(window).height()){
            footer.css('position','absolute');
            footer.css('left','0');
            footer.css('top',footerTop);
         } 
         
     }  
     positionFooter();
     $(window).scroll(positionFooter).resize(positionFooter);
     
    //头部导航在手机浏览器中正常显示
    var s=$(document).width();
     $("#login-head").width(s);     
     $("#footer").width(s);  
     
     
        $('.anythingSlider').anythingSlider({
                easing: "easeInOutExpo",        // Anything other than "linear" or "swing" requires the easing plugin
                autoPlay: true,                 // This turns off the entire FUNCTIONALY, not just if it starts running or not.
                delay: 3000,                    // How long between slide transitions in AutoPlay mode
                startStopped: false,            // If autoPlay is on, this can force it to start stopped
                animationTime: 600,             // How long the slide transition takes
                hashTags: true,                 // Should links change the hashtag in the URL?
                buildNavigation: false,          // If true, builds and list of anchor links to link to each slide
                pauseOnHover: true,             // If true, and autoPlay is enabled, the show will pause on hover
                startText: "Go",             // Start text
                stopText: "Stop",               // Stop text
                navigationFormatter: formatText       // Details at the top of the file on this use (advanced use)
            });
            $("#slide-jump").click(function(){
                $('.anythingSlider').anythingSlider(6);
            });
            
   
        });
            
        function LoadPage(){
            if(document.getElementById("j_username").value.length>=6) {
                document.getElementById("j_password").focus();
            } else {
                document.getElementById("j_username").focus();
            }
        }

        function keyPressInUser() {
            var keyValue;
            keyValue=window.event.keyCode;
            if(keyValue==13) document.all.j_password.focus();
        }

        function keyPressInPassword() {
            var keyValue;
            keyValue=window.event.keyCode;
            if(keyValue==13) document.all.btnLogin.click();
        }

        function userlogincheck() {
            var frm=document.form1;
            var error=document.getElementById("error");
                error.innerHTML="";
            if(frm.login.value==""){
                error.innerHTML="<?=get_lang("userNameRequired")?>";
                frm.login.focus();
                return false;
            }
            if(frm.token.value==""){
                error.innerHTML="<?=get_lang("userPweRequired")?>";
                frm.token.focus();
                return false;
            }
<?php if($loginarr[0] == 'true'){?>      
            if(frm.code_gg.value=="" || frm.code_gg.value.length < 4){
                error.innerHTML="验证码最少4位";
                frm.code_gg.focus();
                return false;
            }
<?php 
          $height=420;
       }else{
          $height=390;
}?>            
            return true;
        }        
    </script>
</head>
<?php  
    $logo_set=  explode(";", api_get_setting ( 'login_logo_set' ) ); 
    $logo_width=$logo_set[0];
    $logo_height=$logo_set[1]; 
?>
<body bgcolor="#1d2979">
<div class="top">
 <img src="../images/gfdks_01.jpg"  />
 <div class="top_login">
 	 <ul >   
 	   <form  action="login.php" method="post"   onSubmit="return userlogincheck();" name="form1" id='login' >
                        <input type="hidden" name="testcookie" value="1" />
                         <?php
                        $sysidfile="/etc/lessonuser";
                        $num=file_get_contents($sysidfile);
                        if(!$num){
                            $num=10;
                        }
                        $user_list = WhoIsOnline ( api_get_user_id (), null, api_get_setting ( 'time_limit_whosonline' ) );
                        $count = count ( $user_list );
                        $sql = "select count(login_id) from track_e_online ;";
                        $count = Database::getval ( $sql, __FILE__, __LINE__ );
                        $count+=0;
                        $num+=0;
                        if($count<$num){
                            echo '<input type="hidden" name="indexPage" value="'.$platform_path.'" />';
                        }else{
                            echo '<script>
                            $.prompt("用户超限");
                        </script>';
                        }
                        ?> 
                        <table>
                            <tr>
                                <td>用户名：</td><td><input type="text"  class="gry" name="login" value="<?=$_configuration ['enable_ukey_login']?'':$_COOKIE['lms_login_name'] ?>"  onkeypress="keyPressInUser()"
                        <?php if($_configuration ['enable_ukey_login']) echo 'readonly';?>autocomplete="on"/> </td>
                            </tr>
                            <tr>
                                <td >密&nbsp;&nbsp;&nbsp;&nbsp;码：</td><td><input type="password"  class="gry"  value="" onkeypress="keyPressInPassword()" name="token"/></td>
                            </tr>
                            <tr><td></td><td><input type="submit" value="登   陆"  class="btn" border="0" /></td></tr>
                        </table>
                 
             
              
       </form>
 	   <p>&nbsp; </p>
    </ul>
 </div>
</div>
</body>
</html>
