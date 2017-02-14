 <?php
include_once '../../main/inc/global.inc.php';

if(isset($_GET['action']) && $_GET['action']=='shutdown'){
       exec("sudo -u root /sbin/cloudconfigreboot.sh shutdown"); // echo "关闭系统！";
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=api_get_setting ( 'siteName' )?></title>
<link rel="stylesheet" href="<?=WEB_QH_PATH?>css/layout.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?=WEB_QH_PATH?>js/jbox-v2.3/jBox/Skins/Blue/jbox.css" media="all"/>
<link type="text/css" rel="stylesheet" href="<?= WEB_QH_PATH?>css/base.css">
<link type="text/css" rel="stylesheet" href="<?= WEB_QH_PATH?>css/course-intr.css">
<link type="text/css" rel="stylesheet" href="<?= WEB_QH_PATH?>css/lab-need.css">
<link href="<?=URL_APPEDND?>/themes/js/jquery-plugins/Impromptu.css" rel="stylesheet" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/learn-center.css">
<script type="text/javascript" src="<?=URL_APPEDND?>/themes/js/commons.js"></script>
<script type="text/javascript" src="<?=URL_APPEDND?>/themes/js/jquery-plugins/jquery-impromptu.2.7.min.js"></script>
<!-- <script src="js/core.js" type="text/javascript"></script>  -->
<script src="<?= WEB_QH_PATH?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=WEB_QH_PATH?>js/jbox-v2.3/jBox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="<?=WEB_QH_PATH?>js/jbox-v2.3/jBox/i18n/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript">
        $(function(){
            $('.column').equalHeight();
        });
    </script>


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


//上面的退出中jBox和$(function()在页面中都存在冲突
  function closebtn(){     
             if(confirm("你确定退出系统吗？")){
                 location.href="<?=URL_APPEND?>main/cloud/cloudvmstop.php?user_id=<?=$user_id?>";
             }
  }
 
</script>
<?php
   
      echo import_assets ( "js/html5.js",WEB_QH_PATH);
      echo import_assets ( "js/jquery-1.5.2.min.js" ,WEB_QH_PATH);
      echo import_assets ( "js/hideshow.js" ,WEB_QH_PATH);
      echo import_assets ( "js/jquery.tablesorter.min.js", WEB_QH_PATH );
      echo import_assets ( "js/jquery.equalHeight.js" ,WEB_QH_PATH);
      echo import_assets ( "commons.js" );
      echo import_assets ( "jquery-plugins/Impromptu.css", api_get_path ( WEB_JS_PATH ) );
      echo import_assets ( "jquery-plugins/jquery-impromptu.2.7.min.js" );
      echo import_assets ( "jquery-plugins/jquery.wtooltip.js" , api_get_path ( WEB_JS_PATH ));
      echo import_assets ( "js/portal.js", WEB_QH_PATH );
    ?>
 
  <script type="text/javascript">
 $(document).ready(function(){
 	$(".navitm").mouseover(function(){
 		$(this).children(".i-mc").css("display","block");
 		 $(this).addClass("selected"); 
 		$(".navitm").mouseout(function(){
 			$(this).children(".i-mc").css("display","none");
 			 $(this).removeClass("selected"); 
 		});
 	});
 }) 


 $(document).ready(function(){
		$('.u-categ li').bind('click',function(){
			var thisIndex = $(this).index();
			$(this).addClass('cur').siblings().removeClass('cur');
			$('.g-mn1 .g-mn1c').eq(thisIndex)
			.show().siblings().hide();	
		});	

  $('.u-card').mouseover(function(){
      $(this).children('.card').children('.descd').css('bottom','0');
      $(this).children('.card').children('.descd').css('display','block');
  });
  $('.u-card').mouseout(function(){
      $(this).children('.card').children('.descd').css('bottom','-136');
      $(this).children('.card').children('.descd').css('display','none');
  })

	})

 function enterclick(){
    $("#j-search2").css("background","#fff");
	$("#auto-id-rTOGAi3MiQOM7HrB").css("background","#fff");
    $("#auto-id-24pyTEn5cDBJ6Hon").css("display","none");
 }
 function onmouseout(){
    $("#j-search2").css("background","#fff");
	$("#auto-id-rTOGAi3MiQOM7HrB").css("background","#fff");
    $("#auto-id-24pyTEn5cDBJ6Hon").css("display","none");
 }
 </script> 
</head>

    <?php

if ($htmlHeadXtra && is_array ( $htmlHeadXtra )) {
	foreach ( $htmlHeadXtra as $head_html ) {
		echo $head_html;
	}
}
?>
    
    