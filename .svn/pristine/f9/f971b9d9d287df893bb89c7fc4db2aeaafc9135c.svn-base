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
         }
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
<!DOCTYPE HTML>
<html>
<head>
<title>ISTS  NSFOCUS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/lm_login.css" rel="stylesheet" type="text/css"/>
<link href="css/lm_page.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/ui.js"></script>
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
    <script type="text/javascript"> 
function login() {
	//$('loginForm').submit();
	 UI.addClass(UI.G('filedset'),'error');
}
function keypress(e) {
	var keynum
	if(window.event) { //IE
		keynum = window.event.keyCode
	} else if(e.which) {// Netscape/Firefox/Opera 
		keynum = e.which
	}
	if (keynum == 13) {
		login();
		Event.stop(e); //Stop Chrome's event buubbl.
	}
}
</script>
<script>
var dialog;
(function(window,UI,$){
	if(!UI){return;}
	var langMenu = UI.GC('.ns-langmenu')
	UI.each(langMenu,function(o){
		UI.EA(o,'mouseover',function(){
			UI.addClass(o,'on');
		});
		
		UI.EA(o,'mouseout',function(){
			UI.removeClass(o,'on');
		});
	});
	dialog = new UI.Dialog({name:'dialog'});
	$("#username").placeholder({"killdefault":true,"color":"#6DADC3"});
	$("#password").placeholder({"killdefault":true,"color":"#6DADC3"});
	UI.gangedPlaceholder("username","password");
}(window,window.UI,window.jQuery))

</script>
</head>
<?php  
    $logo_set=  explode(";", api_get_setting ( 'login_logo_set' ) ); 
    $logo_width=$logo_set[0];
    $logo_height=$logo_set[1]; 
?>
<body class="nm-login">
    <img src="images/lm_login_bg.png" class="nm-login-bg" width="0" height="0" />
<div class="nm-login-header">
	<a href="http://www.nsfocus.com" target="_blank" tabIndex="-1">
		<img src="images/lm_nsfocus.png" class="nsfocus" alt="绿盟科技" />
	</a>
</div>
<div class="nm-login-body">
	<img id="logo_img"  src="images/lm_login_logo.png" class="logo"/>
	<form  action="lm_login.php" method="post"   onSubmit="return userlogincheck();" name="form1" id='login'>
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
		<fieldset class="nm-login-fieldset" id="filedset">
			<legend>用户登录</legend>
			<div class="nm-loginbox nm-loginbox-username"> 
                                <input type="text"   name="login" value="<?=$_configuration ['enable_ukey_login']?'':$_COOKIE['lms_login_name'] ?>"  onkeypress="keyPressInUser()"
                        <?php if($_configuration ['enable_ukey_login']) echo 'readonly';?>autocomplete="on"  placeholder="用户名"/>
			</div>
			<div class="nm-loginbox nm-loginbox-password"> 
                                 <input type="password"  value="" onkeypress="keyPressInPassword()" name="token"  placeholder="密码"/>
			</div>
			<div class="nm-loginbox nm-loginbox-button">
                                                                 <input type="submit" value="登录" class="submit" >
			</div>
		</fieldset>
	</form>
	
</div>
<div class="footer">
	<div class="copyright">
		<span>版权所有 &copy; 2012 绿盟科技</span>
		<a href="#" onClick="dialog.show({title:'用户许可协议',url:'article.html',width:540,height:480,resize:false});return false;" title="用户许可协议">
			<span class="about">用户许可协议</span>
		</a>
	</div>
	<div style="clear:both"></div>

</div>
</body>
</html>