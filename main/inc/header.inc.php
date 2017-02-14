<?php
header ( 'Content-Type: text/html; charset=' . SYSTEM_CHARSET );
if (isset ( $httpHeadXtra ) && $httpHeadXtra) {
	foreach ( $httpHeadXtra as $thisHttpHead ) {
		header ( $thisHttpHead );
	}
}
$document_language = 'en';
$my_code_path = api_get_path ( WEB_CODE_PATH );
$my_style = 'default';
/**platform type 20130329 changzf start**/
$platforms = file_get_contents(URL_ROOT.'/www'.URL_APPEDND.'/storage/DATA/platform.conf');
$platform_array = explode(':',$platforms);
$platform = intval(trim($platform_array[1]));
 
$platform_path = $default_home_page;
$platform_name = api_get_setting('siteName');

if (! api_is_admin () && $_SESSION['_user']['status']!='1') api_not_allowed ();
//设置后台设置系统时间的默认时间
$sys_date_time = date("Y-m-d H:i:s",  time());
$sql = "update `settings_current`  set  `selected_value`='".$sys_date_time."'  where  `variable`='system_date_set' ";
api_sql_query($sql);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>
            <?php 
if(api_get_setting ( 'lm_switch' ) == 'true'){
   echo' ISTS  NSFOCUS';
}else{
echo get_setting ( 'siteName' );
}?>
    </title>
    <?php  
    if(api_get_setting ( 'lm_switch' ) == 'true' ){   ?>
      <link rel="stylesheet" href="<?=api_get_path ( WEB_PATH )?>themes/css/lm_layout.css" type="text/css" media="screen" />
    <?php  }else{  ?>
    <link rel="stylesheet" href="<?=api_get_path ( WEB_PATH )?>themes/css/layout.css" type="text/css" media="screen" />
      <?php   }  ?> 
    <link rel="stylesheet" href="<?=api_get_path(WEB_PATH).PORTAL_LAYOUT?>js/jbox-v2.3/jBox/Skins/Blue/jbox.css" media="all"/>
    <link  rel="shutcut icon" href="<?=api_get_path ( WEB_PATH )?>themes/images/focion.ico">   
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="<?=api_get_path ( WEB_PATH )?>themes/css/ie.css" type="text/css" media="screen" />
  <?php  echo import_assets('themes/js/html5.js',api_get_path ( WEB_PATH ));?>
    <![endif]-->
    <?php
    echo import_assets('themes/js/html5.js',api_get_path ( WEB_PATH ));
    echo import_assets('themes/js/jquery-1.5.2.min.js',api_get_path ( WEB_PATH ));
    echo import_assets('themes/js/hideshow.js',api_get_path ( WEB_PATH ));
    echo import_assets('themes/js/jquery.tablesorter.min.js',api_get_path ( WEB_PATH ));
    echo import_assets('themes/js/jquery.equalHeight.js',api_get_path ( WEB_PATH ));
    ?>
	<script type="text/javascript" src="<?=WEB_QH_PATH?>js/jbox-v2.3/jBox/jquery.jBox-2.3.min.js"></script>
	<script type="text/javascript" src="<?=WEB_QH_PATH?>js/jbox-v2.3/jBox/i18n/jquery.jBox-zh-CN.js"></script>
    <script type="text/javascript">

        $(document).ready(function()
                {
                    $(".tablesorter").tablesorter();
                }
        );
        $(document).ready(function() {

            //When page loads...
            $(".tab_content").hide(); //Hide all content
            $("ul.tabs li:first").addClass("active").show(); //Activate first tab
            $(".tab_content:first").show(); //Show first tab content

            //On Click Event
            $("ul.tabs li").click(function() {

                $("ul.tabs li").removeClass("active"); //Remove any "active" class
                $(this).addClass("active"); //Add "active" class to selected tab
                $(".tab_content").hide(); //Hide all tab content

                var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
                $(activeTab).fadeIn(); //Fade in the active ID content
                return false;
            });

        });
    </script>
    <script type="text/javascript">
        $(function(){
            $('.column').equalHeight();
        });
    </script>
    <?php
    if (isset ( $htmlHeadXtra ) && $htmlHeadXtra) {
        foreach ( $htmlHeadXtra as $this_html_head ) {
            echo ($this_html_head);
        }
    }

    if (isset ( $htmlIncHeadXtra ) && $htmlIncHeadXtra) {
        foreach ( $htmlIncHeadXtra as $this_html_head ) {
            include ($this_html_head);
        }
    }
    $userName = $_SESSION ['_user'] ['firstName'];
    $userNo = $_SESSION ['_user'] ['username'];
    if (! is_not_blank ( $userNo )) {
        $user_info = api_get_user_info ( api_get_user_id () );
        $userNo = $user_info ['username'];
        $userName = $user_info ['firstName'] . " " . $user_info ['lastName'];
    }
//echo $userName;
    ?>
<script type="text/javascript">
function openWindow(width,height,url,title){
var w = width;
var h = height;
var u = url;
var t = title;
$('.column').equalHeight();
$.jBox(u, {
	title: t,
	width: w,
	height: h,
	buttons: { '关闭': true }
});
}     
</script>
<style type ="text/css">#searchkey{color:gray;}</style>

<script type="text/javascript">

//input失去焦点和获得焦点
 $(document).ready(function(){
 //focusblur
     jQuery.focusblur = function(focusid) {
 var focusblurid = $(focusid);
 var defval = focusblurid.val();
         focusblurid.focus(function(){
 var thisval = $(this).val();
 if(thisval==defval){
                 $(this).val("");
             }
         });
         focusblurid.blur(function(){
 var thisval = $(this).val();
 if(thisval==""){
                 $(this).val(defval);
             }
         });
         
     };
 /*下面是调用方法*/
     $.focusblur("#searchkey");
 });
  function closebtn(){     
             if(confirm("你确定退出系统吗？")){
                 location.href="<?=URL_APPEND?>main/cloud/cloudvmstop.php?user_id=<?=$user_id?>";
             }
  }
    </script> 
<SCRIPT language=JavaScript>
window.onload=function (){
    stime();
}
var c=0;
var Y=<?php echo date('Y')?>,M=<?php echo date('n')?>,D=<?php echo date('j')?>;
function stime() {
		c++
		sec=<?php echo time()-strtotime(date("Y-m-d"))?>+c;
		H=Math.floor(sec/3600)%24
		I=Math.floor(sec/60)%60
		S=sec%60
		if(S<10) S='0'+S;
		if(I<10) I='0'+I;
		if(H<10) H='0'+H;
		if (H=='00' & I=='00' & S=='00') D=D+1;  
		if (M==2) {  
		if (Y%4==0 && !Y%100==0 || Y%400==0) {  
		if (D==30){M+=1;D=1;}  
		}
		else {  
		if (D==29){M+=1;D=1;}  
		}
		}
		else {  
		if (M==4 || M==6 || M==9 || M==11) {  
		if (D==31) {M+=1;D=1;}  
		}
		else {  
		if (D==32){M+=1;D=1;}  
		}
		}
		if (M==13) {Y+=1;M=1;}  
		setTimeout("stime()", 1000);
		document.getElementById("liveclock").innerHTML = Y+'-'+M+'-'+D+' '+H+':'+I+':'+S
}
</SCRIPT>
<?php  
if( api_get_setting("lm_switch")=="true"){
?>
<style>
    #TB_title{
        background-color: #357CD2;
    } 
</style>
<?php  }  ?>
</head>
<body>
<?php  
    $logo_set = explode(";", api_get_setting ( 'header_logo_set' ) );
    $logo_width = $logo_set[0];
    $logo_height = $logo_set[1];
?>
    <header id="header" class="<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'lm-header':'lms-header')?>">
    
<hgroup>
    <h1 class='site_title'  <?=(api_get_setting ( 'lm_switch' ) == 'true' ?'style="color: #fff;font-weight: bold;"':'')?> title="<?=$platform_name?>">
    <?php
    if(api_get_setting ( 'lm_switch' ) == 'true'){   
                    echo  '<img src="'.URL_APPEND.'/portal/sp/images/lm_logo_dcst.png" title="信息安全实训平台"   />'; 
    }else{
            echo '<img src="'.URL_APPEND.'/panel/default/assets/images/logo4.gif" title="'.$platform_name.'"/>';
    } 
        ?> 
</h1>
   <h2 class="section_title">
       
       <span class="welcome">
            <?php 
            echo '您好:<a class="helpex dd2" >'.$userName."(".$userNo.")".'</a>,欢迎使用'.(api_get_setting ( 'lm_switch' ) == 'true' ?'信息安全实训平台':$platform_name);
            ?>
       </span>
       <div class="header-clock">
           <span id="liveclock"></span>
       </div>
    </h2>
<!--<div class="btn_view_site">-->
    <div class="btn">
        <a class="helpex dd2"  target="_top" onclick="closebtn()">退出</a>
        <?php 
        if (api_is_admin ()){
        ?>
         <a href='<?=URL_APPEND . PORTAL_LAYOUT?>' title='前台首页'>前台首页</a>
        <?php } ?>
  </div>
<!--</div>-->
</hgroup>
           
   </header>
<?php
$language_file = array ('index' );
include_once ('../../main/inc/global.inc.php');
api_block_anonymous_users ();
if (api_is_platform_admin () || $_SESSION['_user']['status'] == '1') {
    ?>
<section id="secondary_bar" class="f-bg1">
    <ul class='nav'>
<?php if(isRoot()){?>        
            <li><a  href='<?=URL_APPEND?>main/admin/index.php' title='后台首页' style="margin-left:20px;">后台首页</a></li>
<?php }?>            
            <li><a href='<?=URL_APPEND?>user_portal.php'>我的桌面</a></li>
            <li><a href='<?=URL_APPEND?>main/admin/course/course_list.php' title='课程管理'>课程管理</a></li>
 <?php //if(api_get_setting ( 'enable_modules', 'exam_center' ) == 'true'){ ?>
            <li><a href='<?=URL_APPEND?>main/exam/exam_list.php' title='考试管理'>考试管理</a></li> 
 <?php // } ?>
<?php if(isRoot()){?>
            <li><a href='<?=URL_APPEND?>main/admin/cloud_menu.php' title='云平台'>云平台</a></li>
<?php }?>
            <!--<li><a href='<?=URL_APPEND?>main/admin/router/labs_ios.php' target='_self'>路由交换管理</a></li>-->
            <?php //  $SurveyCenter=api_get_setting( 'enable_modules', 'survey_center'); 
//                  if($SurveyCenter == 'true'){ ?>
            <!--<li><a href='<?=URL_APPEND?>main/evaluate/project.php' title='安全评估'>安全评估</a></li>-->
            <?php //  } ?>
            <?php  if($_SESSION['_user']['status']!='1'){ ?>
            <li><a href='<?=URL_APPEND?>main/admin/user/user_list.php' title='用户管理'>用户管理</a></li>
            <?php } ?>
            <li><a href='<?=URL_APPEND?>main/admin/message_list.php' title='信息传递'>信息传递<span id='vmm' style='color :red'></span></a></li>
<?php if(isRoot()){?>
     <?php    if(api_get_setting ( 'lm_switch' ) != 'true' ){  ?>       <li><a href='<?=URL_APPEND?>main/ctf/exam_class.php' title='ctf管理'>ctf管理</a></li>  <?php  }?>
            <li><a href='<?=URL_APPEND?>main/admin/misc/settings.php' title='系统管理'>系统管理</a></li>
            <li><a href='<?=URL_APPEND?>main/admin/skill/occupation_manage.php' title='系统管理'>技能管理</a></li>
<?php } ?>
    </ul>
   
</section>
<?php
}
?>
