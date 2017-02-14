<?php
session_start();
define ( "IN_QH", TRUE );
$cidReset = true;
$language_file = array ("registration", "admin", "index", 'customer_qihang' );
include_once ('../../main/inc/global.inc.php');
$orgsql = "SELECT *  FROM cn_org";
$org_list = api_sql_query ( $orgsql);
while($org_arr1=Database::fetch_row($org_list)){
    $org_arr[]=$org_arr1;
}

if (api_get_setting ( 'allow_registration' ) == 'false') api_redirect ( 'login.php' );

function remove_xss($val) {

    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;

        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

   if(count($_GET)){
      foreach($_GET as $rek=>$rev){
          $_GET[$rek]=remove_xss($rev);
      }
   }
   if(count($_POST)){
       foreach($_POST as $pk=>$pv){
           $_POST[$pk]=remove_xss($pv);
       }
   }

require_once (api_get_path ( LIBRARY_PATH ) . 'smsmanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( INCLUDE_PATH ) . 'sendmail/SMTP.php');
if (is_equal ( $_POST ["action"], "do_register" )) {
	$table_user = Database::get_main_table ( TABLE_MAIN_USER );//user
	$values = $_POST;
        if(strtolower($values['code_gg']) != strtolower($_SESSION['helloweba_gg'])){
            $tishi='tishi';
        }else{
	$tbl_card = Database::get_main_table ( 'bos_card' );//bos_card
	if (api_get_setting ( 'enabled_learning_card' ) == 'true') {
		$sql = "SELECT * FROM $tbl_card WHERE enabled=1 AND card_no='" . escape ( $values ['card_no'] ) . "' AND passwd='" . escape ( $values ['card_pwd'] ) . "'";
		if (Database::if_row_exists ( $sql ) == false) {
			api_redirect ( 'user_register.php?msg=invalid_card' );
		}
	}
	
	$table_user_register = Database::get_main_table ( TABLE_MAIN_USER_REGISTER );//user_register
	$username = getgpc ( "username","P" );
	$sql = "SELECT * FROM $table_user_register WHERE username = '" . escape ( $username ) . "'";
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	
	$sql2 = "SELECT * FROM $table_user WHERE username = '" . escape ( $username ) . "'";
	$res2 = api_sql_query ( $sql2, __FILE__, __LINE__ );
	if (Database::num_rows ( $res ) > 0 or Database::num_rows ( $res2 ) > 0) {
		api_redirect ( 'user_register.php?msg=invalid_username' );
	}
	
	$values ['status'] = STUDENT;
	$values ['language'] = api_get_setting ( 'platformLanguage' );
	
	$dept_ids=73;
                    $sex=$values ['sex'];
	$credential_type = $values ['credential_type'];
	$credential_no = ($credential_type == '0' ? '' : $values ['credential_no']);
	$credential_no = getgpc ( 'card_no', 'P' );
                  $school=$values ['school'];
		  $school = strip_tags($school);
		  $values['phone'] = strip_tags($values['phone']);
        $password=crypt(md5($values ['pass1']),md5($values ['username']));
	$user_id = UserManager::register_user ( $values ['firstname'], $values ['status'], $values ['email'], $values ['username'], $password,'',
        $values ['language'], $values ['phone'], $values ['mobile'], $values ['question'], $values ['answer'],
			 0, '',$values ['qq'], $values ['msn'], $dept_ids, $sex, $credential_type, $credential_no, $values ['zip_code'], $school, ip () );
	
	if ($user_id) {
		$smhostarr=mysql_fetch_row(mysql_query("select selected_value from settings_current where enabled =1 and category='MailServer' and variable='smtp_mail_host'"));
		$smportarr=mysql_fetch_row(mysql_query("select selected_value from settings_current where enabled =1 and category='MailServer' and variable='smtp_mail_port'"));
                $smuserarr=mysql_fetch_row(mysql_query("select selected_value from settings_current where enabled =1 and category='MailServer' and variable='smtp_mail_username'"));
                $smpassarr=mysql_fetch_row(mysql_query("select selected_value from settings_current where enabled =1 and category='MailServer' and variable='smtp_mail_password'"));
               //如果不要审核注册用户
		if (get_setting ( 'allow_registration' ) == 'true') {
			
			if (api_get_setting ( 'enabled_learning_card' ) == 'true') {
				$sql = "UPDATE $tbl_card SET username='" . escape ( $username ) . "' WHERE card_no='" . escape ( $values ['card_no'] ) . "'";
				api_sql_query ( $sql, __FILE__, __LINE__ );
			}
			
			//审核直接通过
			$result = UserManager::audit_reg_user_passed ( $user_id );
			
			//邮件提醒注册的用户
			if ($values ['email'] && is_email ( $values ['email'] )) {
				$emailToName = $values ['firstname'];
				$emailFrom = api_get_setting ( 'emailAdministrator' );
				$emailFromName = addslashes ( get_setting ( 'administratorSurname' ) . ' ' . get_setting ( 'administratorName' ) );
				$emailSubject = get_lang ( 'YourReg' );
				$emailBody = get_lang ( 'Dear' ) . ' ' . stripslashes ( $values ['firstname'] ) . "<p>" . get_lang ( 'YouAreReg' ) . ' ' . get_setting ( 'siteName' ) . ' ' . get_lang ( 'Settings' ) . "<br/>" . get_lang ( 'TheU' ) . ' : ' . $values ['username'] . "<br/>";
				$emailBody .= get_lang ( 'Pass' ) . ' : ' . stripslashes ( $values ['pass1'] ) . "<br/><br/>";
				email_body_txt_add ( $emailBody );
                                $mail = new MySendMail();
                                $mail->setServer($smhostarr[0],$smuserarr[0],$smpassarr[0],$smportarr[0], true); //到服务器的SSL连接 
                                $mail->setFrom($smuserarr[0]);
                                $mail->setReceiver($values ['email']);
                          
                                $headers=iconv('UTF-8','GB2312', $emailSubject);
                                $mail->setMail($headers, $emailBody);
                                $mail->sendMail(); 
			}
			
			unset ( $user_id );
			$message = urlencode ( get_lang ( 'YourAccountHasRegSuccess' ) );
			$redirect_url = "notice.php?message=" . $message . "&msg_title=" . urlencode ( get_lang ( "OperationSuccess" ) ) . "&url=" . urlencode ( "login.php" );
			
                        api_redirect ( $redirect_url );
		} 

		//需要审核,则发送管理员审核用户邮件,SMS提醒
		elseif (get_setting ( 'allow_registration' ) == 'approval') {

                        $mails=array(
                            0=>array(0=>$values ['email'],1=>"尊敬的".$values ['firstname']."用户，您好，您已成功注册为本系统的用户！！<br/>请您耐心等待系统管理员的审核，审核通过以后即可登陆本系统，谢谢合作！！！"),
                            1=>array(0=>"liuhui@51elab.com",1=>"尊敬的管理员，您好，用户".$values ['firstname']."已成功注册为本系统的新用户！！<br/>请您尽快审核，谢谢合作！！！")
                            );
                        foreach ($mails  as $val){
                            $mail = new MySendMail();
                            $mail->setServer($smhostarr[0],$smuserarr[0],$smpassarr[0],$smportarr[0], true); //到服务器的SSL连接 
                            $mail->setFrom($smuserarr[0]);
                            $mail->setReceiver($val[0]);
                            $hea="注册用户成功";
                            $headers=iconv('UTF-8','GB2312', $hea);
                            $mail->setMail($headers, $val[1]);
                            $mail->sendMail(); 
                        }
                        
			// 3. exit the page
			unset ( $user_id );
			$message = urlencode ( get_lang ( 'YourAccountHasToBeApproved2' ) ); //您已经成功注册为起航学员
			$redirect_url = "notice.php?message=" . $message . "&msg_title=" . urlencode ( get_lang ( "OperationSuccess" ) ) . "&url=" . urlencode ( "login.php" );
		 	api_redirect ( $redirect_url );
		} else {
		 	api_redirect ( api_get_path ( WEB_PATH ) );
		}
	}
  }    
}
?>
<!doctype>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= api_get_setting ( 'siteName' ) ?></title>
    <style type="text/css">
        *{margin:0;padding:0;}
        body{font:12px/1.8em "Microsoft Yahei",Tahoma, Helvetica, Arial, "SimSun", sans-serif;color:#000000;
             
        }
        .zhuce_bg{
            background:#dceaf5 url(images/zhuce_bg.png) top center no-repeat;
        }
        .zhuce_all{
            width:870px;
            margin:0 auto;
        }
        .logo{
            padding:30px 10px 0 10px;
            line-height:80px;
            margin-left:30px;
            font-size:24px;

        }
        .zhuce_blue{
            width:870px;
            height:3px;
            background:#1e92ff;
            
        }
        .zhuce_til{
            width:860px;
            height:53px;
            background:#edf2f5;
            color:#0084ff;
            line-height:53px;
            font-size:20px;
            padding-left:10px;
        }
        .zhuce_line{
            width:870px;
            height:1px;
            background:#dcdcdc;
            
        }
        ul,li,dd,dl,dt{list-style:none;}
        
/*        .logo{height:70px;line-height:60px;margin-bottom:10px;}*/
        .logo h1{margin:10px 0 0 20px;}
        .userRegister{
            background:#fafafa;  
            margin-bottom:20px;
        
        }
        
/*        .userRegister h3{display:block; width:100%; height:36px;
                         background:url(images/bgx.png) repeat;
                         background-image:linear-gradient(#4EA45B, #3C8440);
                        color:#FFFFFF;line-height:36px; text-indent:2em;font-weight:normal;
                        box-shadow:0 0px 6px #999;
        }*/
        .RegisterContent{overflow:hidden;}
        .content,.sidebar{float:left;}
        .content{
            width:75%;  
            margin-left:15%;
            margin-bottom:15px;
        }
       .content ul{margin:40px 0 20px 50px;}
        .content ul li{margin-bottom:20px; height:30px;line-height:30px; position:relative; clear:both;}
	 /**   .content ul li span{color:red;font-weight:bold;}**/
        .txt-impt{color:#FF0000;font-size:16px;font-weight:bold;}
        .content ul li label{ float:left;display:block; width:70px; text-align:right;}
        .content ul li input{border:1px solid #ABABAB;height:30px; vertical-align:bottom;margin-left:12px;padding:0 0 0 5px; width:220px;}
        .content ul li input[type='radio']{width:10px;}
        input#okgo{height:42px; width:193px;border:0 none;color:#FFF;font-weight:bold; 
                  background:url(images/zhuce_buton.png) no-repeat;
                   
                   margin:10px 0 0 130px; cursor:pointer;}
       
        .content ul li input:hover{border:1px solid #F00;}
        .sidebar{background:#F5F5F5;border-left:1px solid #E0E0E0;width:379px; height:530px;}
        .sidebar img{margin:10px 0px 0px 0px;}
        .footer{clear:both; text-align:center; padding:20px 0;color:#999999;}
        .register_li .notice{color: #F00}
        .jqicontainer{background-color: #F5F5F5; position:relative; left:620px; top:-70px; padding:10px 20px;border:1px solid #3C8440;}
        .jqiclose{text-align:right;}
        .jqiclose:hover{cursor:pointer;}
      
    </style>
     <link type="html/css" rel="stylesheet" href="js/fromValidator/style/validator.css"></link>


    <script type="text/javascript" src="../../themes/js/commons.js"></script>
    <script type="text/javascript" src="../../themes/js/jquery-latest.js"></script>
    <script type="text/javascript" src="../../themes/js/jquery-plugins/jquery-impromptu.2.7.min.js"></script>
    <script src="js/formValidator/formValidator.js" type="text/javascript" charset="UTF-8"></script> 
    <script src="js/formValidator/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script> 
    <script type="text/javascript">
    $(document).ready(function(){
        $.formValidator.initConfig({formid:"theForm",onerror:function(msg){$.prompt(msg)}});

    <?php
    if (api_get_setting ( 'enabled_learning_card' ) == 'true') {
        ?>
        $("#card_no").formValidator({onshow:"请输入学习卡号",onfocus:"学习卡号为8个数字",oncorrect:"学习卡号格式正确"})
                .inputValidator({min:8,max:8,onerror:"您输入的学习卡号非法,请确认"})
                .regexValidator({regexp:"num",datatype:"enum",onerror:"学习卡号格式不正确"});

        $("#card_pwd").formValidator({onshow:"请输入学习卡密码",onfocus:"学习卡密码为8位",oncorrect:"学习卡密码格式正确"})
                .inputValidator({min:8,max:8,onerror:"您输入的学习卡密码非法,请确认"})
                .regexValidator({regexp:"username",datatype:"enum",onerror:"学习卡密码格式不正确"});
        <?php
    }
    ?>

        $("#username").formValidator({onshow:"请输入登录帐号",onfocus:"登录帐号至少4个字符,最多20个字符",oncorrect:"登录帐号格式正确"})
                .inputValidator({min:4,max:20,onerror:"您输入的用户名非法,请确认"})
                .regexValidator({regexp:"username",datatype:"enum",onerror:"登录帐号格式不正确"});

        $("#pass1").formValidator({onshow:"请输入密码",onfocus:"密码不能为空,至少6个字符",oncorrect:"密码合法"})
                .inputValidator({min:6,empty:{leftempty:false,rightempty:false,emptyerror:"密码两边不能有空符号"},onerror:"密码长度不合要求,请确认"});

        $("#pass2").formValidator({onshow:"请输入重复密码",onfocus:"两次密码必须一致哦",oncorrect:"密码一致"})
                .inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"重复密码两边不能有空符号"},onerror:"重复密码不能为空,请确认"})
                .compareValidator({desid:"pass1",operateor:"=",onerror:"两次输入的密码不一致,请确认"});

        $("#email").formValidator({onshow:"请输入邮箱",onfocus:"邮箱6-100个字符",oncorrect:"输入正确"})
                .inputValidator({min:6,max:100,onerror:"您输入的邮箱长度非法,请确认"}).regexValidator({regexp:"^([\\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$",onerror:"您输入的邮箱格式不正确"});
//        $("#mobile").formValidator({empty:true,onshow:"请输入您的手机号码,方便我们与您联系",onfocus:"您要是输入了，必须输入正确",oncorrect:"谢谢您的合作",
//            onempty:"您真的不想留手机号码啊？"}).inputValidator({min:11,max:11,onerror:"手机号码必须是11位的,请确认"})
        $("#mobile").formValidator({onshow:"请输入您的手机号码,方便我们与您联系",onfocus:"您要是输入了，必须输入正确",oncorrect:"谢谢您的合作"})
                .inputValidator({min:11,max:11,onerror:"手机号码必须是11位的,请确认"})
                .regexValidator({regexp:"mobile",datatype:"enum",onerror:"您输入的手机号码格式不正确"});;

        $("#firstname").formValidator({onshow:"为了能够顺利审核通过，请输入您的真实姓名,",onfocus:"至少2个字符,最多20个字符",oncorrect:"格式正确"})
                .inputValidator({min:4,max:20,onerror:"输入非法,请确认"});
//        $("#school").formValidator({onshow:"为了能够顺利审核通过，请输入您的组织模式,",onfocus:"至少4个字符,最多60个字符",oncorrect:"谢谢您的合作"})
//                .inputValidator({min:2,max:60,onerror:"长度非法,请确认！"});
        $("#code_gg").formValidator({onshow:"请输入4位验证码 ！",onfocus:"请输入4位验证码 ！",oncorrect:"格式正确"})
                .inputValidator({min:1,max:4,onerror:"验证码不能为空 ！"});
        $("#username").blur(function(){
            $.get('ajax_actions.php?action=check_username',{username:$("#username").val()},
                    function(data){
                        if(data==1) $.prompt("平台已有此帐号，请更换另一个!");
                    },'text');
        });
        return false;

    <?php
    if (api_get_setting ( 'enabled_learning_card' ) == 'true') :
        ?>
        $("#card_no").blur(function(){
            $.get('ajax_actions.php?action=check_cardno',{card_no:$("#card_no").val()},
                    function(data){
                        if(data==0) $.prompt("系统中无此帐号，请更换另一个!");
                    },'text');
        });

        $("#card_pwd").blur(function(){
            $.get('ajax_actions.php?action=check_cardpwd',{card_no:$("#card_no").val(),card_pwd:$("#card_pwd").val()},
                    function(data){
                        if(data==0) $.prompt("您输入的卡号或密码有误,请确认!");
                    },'text');
        });

        <?php
   endif;
   $g_msg=  getgpc('msg');
    if (is_equal ( $g_msg, 'invalid_card' )) echo '$.prompt("您输入的卡号或密码有误,请确认!");';
    if (is_equal ( $g_msg, 'invalid_username' )) echo '$.prompt("您输入的帐号不可用，请更换另一个!");';
    ?>

    });
</script>
    <?php
    if (is_equal ( $g_msg, 'invalid_card' )) echo '<script>$.prompt("您输入的卡号或密码有误,请确认!");</script>';
    if (is_equal ( $g_msg, 'invalid_username' )) echo '<script>$.prompt("您输入的帐号不可用，请更换另一个!");</script>';
    $deptObj = new DeptManager ();
    $depts = $deptObj->get_sub_dept_ddl2 ( 0, 'array' );
    ?>
</head>
<body class="zhuce_bg">
<div class="zhuce_all">
   <h3 class="logo">   <?=api_get_setting ('siteName')?>  </h3>
    <div class="zhuce_blue"></div>
    <div class="zhuce_til">注册帐号</div>
    <div class="zhuce_line"></div>
    <div class="userRegister">
<!--   <h3><?=api_get_setting ( 'siteName' )?> </h3>-->
     <div class="RegisterContent">
        <div class="content">
        <form method="post" action="user_register.php" id="theForm" name="theForm"><input type="hidden" name="action" value="do_register" />
            <div class="register_form">
            <ul>
                <li>
                 <div class="register_hint dd2">温馨提示：如果您已是本平台学员，请直接使用学员帐号
                  <a href="login.php" class="dd6" style="font-size: 16px; font-weight: bold">登 录 </a>
                 </div>
                </li>
                <li>
                 <div class="register_li"> <span class="notice">*</span>登&nbsp;录&nbsp;名&nbsp;:
                  <input type="text" class="text dx1" name="username" id="username" />
                  <span id="usernameTip"></span>
                  <div class="clearall"></div>
                 </div>
                </li>
                <li>
                 <div class="register_li"> <span class="notice">*</span>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码:
                  <input type="password" class="text dx1" name="pass1" id="pass1" />
                  <span id="pass1Tip"></span>
                  <div class="clearall"></div>
                 </div>
                </li>
                <li>
                <div class="register_li"> <span class="notice">*</span>确认密码:
                  <input type="password" class="text dx1" name="pass2" id="pass2" />
                  <span id="pass2Tip"></span>
                  <div class="clearall"></div>
                 </div>
                </li>
                <li>
                 <div class="register_li"> <span class="notice">*</span>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱:
                  <input type="text" class="text dx1" name="email" id="email" />
                  <span id="emailTip"></span>
                  <div class="clearall"></div>
                 </div>
                </li>
                <li>
                 <div class="register_li"> <span class="notice">*</span>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名:
                  <input type="text" class="text dx1" name="firstname" id="firstname" />
                  <span id="firstnameTip"></span>
                  <div class="clearall"></div>
                 </div>
                </li>
                <li>
                 <div class="register_li"><span class="notice">*</span>手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机:
                  <input type="text" class="text dx1" name="mobile" id="mobile" />
                  <span id="mobileTip"></span>
                  <div class="clearall"></div>
                 </div>
                </li>
                
                <?php if(api_get_setting('enable_modules', 'clay_oven') == 'false'){?>
<!--                <li>
                 <div class="register_li"> <span class="notice">*</span> <?=  api_get_setting('Shool')?>:
                  <input type="text" class="text dx1" name="school" id="school" />
                  <span id="schoolTip"></span>
                  <div class="clearall"></div>
                 </div>
                </li>-->
                <?php }else{?>
                 <li>
                 <div class="register_li"> <span class="notice">*</span> <?=  api_get_setting('Shool')?>:
                  <select name="school" id="school">  
                    <option value ="0">请选择</option>
                    <?php foreach($org_arr as $k=>$v){?>
                    <option value="<?= $v[0]?>"><?=$v[1]?></option> 
                    <?php }?>
                  </select> 
                  <span id="schoolTip"></span>
                  <div class="clearall"></div>
                 </div>
                </li>
                <?php }?>
<!--		<li>
                 <div class="register_li">&nbsp;所属部门:&nbsp;&nbsp;&nbsp;
	          <select id="dept_id" style="height:30px;width:220px" name="dept_id">
                  <?php
//			$html_option = '';
//			foreach ($depts as $k=>$v ) {
//				if ($k == DEPT_TOP_ID) {
//					$html_option .= '<option style="height:15px" value="' . $k . '">' . $v . '</option>';
//				} else {
//					$html_option .= '<option style="height:15px" value="' . $k . '">' . str_repeat ( "&nbsp;&nbsp;", intval ( $item ['level'] / 2 ) ) . $v . '</option>';
//				}
//			}
//			echo $html_option;
		  ?>
		  </select>
                 </div>
                </li>-->
                <li>
                 <div class="register_li"><span class="notice">&nbsp;</span>&nbsp;性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别:
                   <input class="radio" type="radio" name="sex" value="1" checked="checked" />男&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <input class="radio" type="radio" name="sex" value="2"/>女
                  <div class="clearall"></div>
                 </div>
                </li>
                <li>
                    <span class="register_li"><span class="notice">*</span>验&nbsp;证&nbsp;码:</span>
                    <input type="text" id="code_gg" name="code_gg" maxlength="4"/>
                    &nbsp;&nbsp;<img src="./code_gg.php?" onclick="this.src=this.src+Math.random();" align="absmiddle" title="看不清，换一张" style="cursor:pointer;">
                    &nbsp;<span style='color:red;font-size:12px;display:none;' id='prompt'>验证码错误</span>
                    
                </li>
                <li>
                     <input class="btn_register" type="button" id="okgo" name="button"  onclick="$('#theForm').submit()">
		            <div class="register_li dd7">
		                <div class="register_label"></div> <div class="clearall"></div>
		            </div>
                </li>
            </ul>
             

<div class="register_form">
 
 <div class="register_li">
<!--<div class="register_label de1">备注说明:</div>
<input type="text" class="text dx1" name="org" id="org" />
<div class="clearall"></div>-->
</div>
<div class="register_li dd7">
  <div class="register_label"></div> 
  <div class="clearall"></div>
</div>

</form>

</div>
<div class="clearall"></div>
</div>

        </div>
<!--        <div class="sidebar">
            <img src="images/registerImage.gif">
        </div>-->
    </div>
</div>
<div class="footer"> 
    <?php if(api_get_setting ( 'Institution' )){ ?>
        <?=api_get_setting ( 'Institution' )?> 
	<?php } ?>
<br/>
<?php
        if(api_get_setting ( 'show_administrator_data' )=='true'){
                if(api_get_setting('administratorTelephone')){
                   echo  "联系我们:  ".api_get_setting('administratorName')."&nbsp";
                }
                if(api_get_setting('administratorTelephone')){
                   echo  "电话:  ".api_get_setting('administratorTelephone')."&nbsp";
                }
                if(api_get_setting('emailAdministrator')){
                   echo  "邮箱:  ".api_get_setting('emailAdministrator')."&nbsp";
                }
                if(api_get_setting ( 'site_beian_code' )){ 
                     echo api_get_setting ( 'site_beian_code' );
                }
         }
        ?>
  </div>
</div>
</body>
<script>
    //中文判断函数，允许生僻字用英文“*”代替
    //返回true表示是符合条件，返回false表示不符合
    function isChinese(str)
    {
        var badChar ="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        badChar += "abcdefghijklmnopqrstuvwxyz";
        badChar += "0123456789";
        badChar += " "+"　";//半角与全角空格
        badChar += "`~!@#$%^&amp;()-_=+]\\|:;\"\\'&lt;,&gt;?/";//不包含*或.的英文符号 <BR>
        if(""==str)  {
            return false;
        }
        for(var i=0;i<str.length;i++)  {
            var c = str.charAt(i);//字符串str中的字符
            if(badChar.indexOf(c) > -1)
            {
                return false;
            }
        }
        return true;
    }
</script>
</html>
<?php
     if($tishi == 'tishi'){
?>
<script type="text/javascript">
    window.document.body.scrollTop=window.document.body.scrollHeight;
    $("#username").val("<?php echo $values['username'];?>");
    $("#pass1").val("<?php echo $values['pass1'];?>");
    $("#pass2").val("<?php echo $values['pass2'];?>");
    $("#email").val("<?php echo $values['email'];?>");
    $("#school").val("<?php echo $values['school'];?>");
    $("#firstname").val("<?php echo $values['firstname'];?>");
    $("#mobile").val("<?php echo $values['mobile'];?>");
    <?php 
    if($values['sex']==1){
?>
    $("#sex1").attr('checked','true');
<?php    
    }else{ 
?>
     $("#sex2").attr('checked','true');  
<?php
    }
?>  
   $("#code_gg").val("<?php echo $values['code_gg'];?>");
   $("#prompt").css('display','inline');
</script>
<?php  
}else{
?>
<script type='text/javascript'>
   $("#prompt").css('display','none');
</script>
<?php
     }
?>
