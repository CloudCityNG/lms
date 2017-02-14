<?php
include_once ('../../main/inc/global.inc.php');
$msg_title = urldecode ( trim ( getgpc("msg_title") ) );
$message = urldecode ( trim ( getgpc("message") ) );
$url = urldecode ( trim ( getgpc("url") ) );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="utf-8">
    <title><?=api_get_setting ( 'siteName' )?></title>
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
        
        .logo h1{margin:10px 0 0 20px;}
        .userRegister{
            background:#fafafa;  
            margin-bottom:20px;
        
        }

        .RegisterContent{overflow:hidden;}
        .content,.sidebar{float:left;}
        .content{
            width:60%;  
            margin-left:20%;
            margin-bottom:15px;
        }
        .content .success_logo{
            margin-top:10px;
            width:100%;
            height:103px;
            background:url(images/zhuce_success.png) no-repeat;
            
        }
        .content .zhuce-message{
            padding:5px 0 0px 50px;
            font-size:14px;
        }
        
        
        .txt-impt{color:#FF0000;font-size:16px;font-weight:bold;}
        .content ul li label{ float:left;display:block; width:70px; text-align:right;}
        .content ul li input{border:1px solid #ABABAB;height:30px; vertical-align:bottom;margin-left:12px;padding:0 0 0 5px; width:220px;}
        .content ul li input[type='radio']{width:10px;}
        input#okgo{height:38px; width:120px;border:0 none;color:#FFF;font-weight:bold; background:url(images/glb.png) no-repeat;margin:10px 0 0 83px; cursor:pointer;}
        input#okgo:hover{background:url(images/glb.png) no-repeat -144px 0px;}
        .content ul li input:hover{border:1px solid #F00;}
        .sidebar{background:#F5F5F5;border-left:1px solid #E0E0E0;width:379px; height:530px;}
        .sidebar img{margin:10px 0 0 15px;}
        .footer{clear:both; text-align:center; padding:20px 0;color:#999999;}
        .register_li .notice{color: #F00}
        .header_title{width:980px;margin:0px auto;}
        .register_banner{float:right;color:#FFF}
        a:link,a:visited{text-decoration:none; color:#CCC}
        
    </style>
<!--link href="index.css" rel="stylesheet" type="text/css"></link-->

</head>

<body class="zhuce_bg">
    <div class="zhuce_all">
       <h3 class="logo"><?=api_get_setting ( 'siteName' )?> </h3>
    <div class="zhuce_blue"></div>
    <div class="zhuce_til">注册帐号</div>
    <div class="zhuce_line"></div>
    
     <div class="userRegister">
       <div class="RegisterContent">
          <div class="content">
            <div class="success_logo" > </div>
            <div class="zhuce-message"><?php echo $message; ?></div>
            <div class="zhuce-message">
                   <div id="leftTime" style="display: inline;"></div> 
                   秒后将自动跳转到目的页面
            </div> 
            <div class="zhuce-message">
                 如果页面没有跳转，请点击<a href="<?php echo $url; ?>" style="fontsize: 14px; color: #A0001B;">立即跳转</a>
            </div>
                   <div class="clearall"></div>
            </div>
      <div class="clearall"></div>
  </div>
  <div class="clearall"></div>
</div>
    </div>
<script language="JavaScript">
var  times=11;
var time=document.getElementById("leftTime");
time.innerHTML=times;
clock();
function  clock()
{
	window.setTimeout('clock() ',1000);
	times=times-1;
	if(times<0) times=0;
	time.innerHTML=times;
	if(times<=0){
		location.href="<?php
		echo $url;
		?>";
	}
}
</script>


