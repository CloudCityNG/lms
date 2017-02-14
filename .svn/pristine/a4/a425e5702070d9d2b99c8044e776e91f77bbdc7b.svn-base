<?php
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
session_start ();
//验证验证码
if(isset($_POST['code_gg'])){
       if(strtolower($_POST['code_gg']) != strtolower($_SESSION['helloweba_gg'])){
                    $code_ggis=true;
                    session_destroy();
                    header("Location:login.php?loginFailed=1&error=seccode_error");
                    exit;
        }
   }
   session_destroy();
    
include_once ("../../login.inc.php");
    if(api_get_setting('enable_modules', 'clay_oven') == 'true'){
                header("Location:./cn/login.php");
         }else  if(api_get_setting ( 'lm_switch' ) == 'true' ){
               header("Location:./lm_login.php");
         }
$platform_path='portal/sp/index.php';
    $err=$_GET["error"]; 
   if($err=="user_password_incorrect"){
//       $error_msg='密码错误！' ; 
       $error_msg='登录失败 - 用户名/密码错误！' ;
   }elseif($err=="account_inactive"){ 
       $error_msg='用户被锁定！' ;
   }elseif($err=="auth_user_expiration"){ 
       $error_msg='帐号过期！' ;
   }elseif($err=="auth_user_not_exsist"){ 
           echo "<script ype='text/javascript'>";
//      $error_msg='用户不存在！' ;
       $error_msg='登录失败 - 用户名/密码错误！' ;
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
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?=api_get_setting ( 'siteName' )?>欢迎您</title>
    <link rel="stylesheet" media="all" type="text/css" href="./css/base.css"/> 
    <link type="text/css" rel="stylesheet" href="./css/login.css">
    <script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
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
<body id="login">
    <div id="login-head">
        <div class="g-flow">
            <div class="first-logo">
                <img src="../../panel/default/assets/images/logo3.gif">
            </div>
            <div class="learn-login"></div>
        </div>
        
    </div>
  <div class="b-20"></div>
     

<div class="g-doc" >
      <div class="b-10"></div>
     
    <div class="m-logform f-cb f-pr">
          <div class="form_content right" id="form_parent">
            <div class="m-loginbox f-cb" style="height:<?=$height?>px;">
                <h3 class="user-login">登录账号</h3>

                <div class="login-content-input">
                    <form action="login.php" method="post"   onSubmit="return userlogincheck();" name="form1" id='login'>
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
                        <b class="buname">用户名：</b>
                         <div class="b-10">
                        </div>
                        
                        <input type="text"  class="l-input uname" name="login" value="<?=$_configuration ['enable_ukey_login']?'':$_COOKIE['lms_login_name'] ?>"  onkeypress="keyPressInUser()"
                        <?php if($_configuration ['enable_ukey_login']) echo 'readonly';?>autocomplete="on">
                        <div class="b-10">
                        </div>
                        <b class="buname">密&nbsp;&nbsp;&nbsp;码：</b>
                         <div class="b-10">  </div>
                        <input type="password" class="l-input passwd" value="" onkeypress="keyPressInPassword()" name="token">
                         <div class="b-10">  </div>
<?php if($loginarr[0] == 'true'){?>                         
                         <input type="text" name="code_gg" class="l-input" style="width:45%;" maxlength="4">
                         &nbsp;&nbsp;<img src="./code_gg.php?" onclick="this.src=this.src+Math.random();" align="absmiddle" title="看不清，换一张" style="cursor:pointer;">
                         <div class="b-10">  </div>
<?php }?>                         
                        <span class="itm itm-1 f-vama">
                        <label class="lb">
                            <span class="atlg">
                                <input type="checkbox" name="checkbox" class="f-check autologin j-autologin">自动登录
                            </span>
                        </label>
                            <?php
                        if (api_get_setting('allow_lostpassword') == 'true') { ?>
                            <a href="<?=api_get_path(WEB_PATH)?>lostPassword.php?KeepThis=true&TB_iframe=true&height=250&width=600&modal=true" >忘记密码</a>
                        <?php } ?>
                        </span>
                        <div class="b-10">
                        </div>
                        <input type="submit" value="登录" class="u-btn u-btn-primary j-submit" >
                        <div class="b-10">
                        </div> 
                        <div class="inputstyle forget" style="height: 10px;line-height: 8px;">
                            <span style="margin-top:8px;height: 10px;line-height: 10px;color:red;">
                                <div id="error" style="height: 10px;line-height: 10px;"  ><?=$error_msg?></div>
                                <?php if (api_get_setting('allow_registration') != 'false') { ?>
                                    <div class="reg" style="float:right">
                                    <a  title="注册"   onclick="location.href='user_register.php';">立即注册>></a>
                                    </div>
                               <?php }?>
                            </span>
                            
                        </div>
                        
                    </form>
                </div>
            </div>
    </div>
    </div>
</div>
<div class="clear"></div>
	 <div class="g-ft" id="footer">
 	    <div class="m-foot">
 		   <div class="g-flow ftwrapper f-cb">
 		      <div class="m-ft2 f-cb">
 			      <?php  echo api_get_setting ( 'EditionShowInBottom' ) ; ?>
                               <div class="notice"> 
                                 <p>推荐使用Chrome浏览器(请<a href="/lms/storage/c.tar"><b style="color:blue;">下载</b></a>安装) 来访问本平台！</p>
                             </div>
 		      </div>
 		   </div>
 		</div>
 	</div>  
<?php if($_configuration ['enable_ukey_login']){?>
<script type="text/javascript">
    var obj=document.getElementById("FtRockey2");
    if(obj){
        //obj.uid=715400947;
        obj.OpenMode=0;
        var r2_num = obj.Ry2find();
        //alert("找到的加密锁个数：" + r2_num);
        var r2_handle = obj.Ry2open();
        if(r2_handle >= 0){
            obj.BlockIndex = 4;
            obj.Buffer = ""
            err = obj.Ry2Read();
            if(err == 0){
                if(document.getElementById("j_username")) document.getElementById("j_username").value=(obj.Buffer);
                obj.close();
            }else{
                alert("对不起,读取硬件信息失败,你不能登录使用本系统!");
            }
        }else{
            alert("不能打开加密锁,请插入硬件U棒后刷新当前页面!");
        }
    }else{
        alert('无法初始化硬件, 你不能使用本系统!');
    }
</script>


    <?php } ?>

</body>
</html>
