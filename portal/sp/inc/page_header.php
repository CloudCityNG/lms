<?php
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
include_once '../../main/inc/global.inc.php';
if(api_is_admin()){
if(isset($_GET['action']) && htmlspecialchars( $_GET['action'] )=='shutdown' && $_SESSION['_user']['status'] == PLATFORM_ADMIN)
{
        //exec("sudo -u root /sbin/cloudconfigreboot.sh shutdown");
} 
}
$sys_date_time = date("Y-m-d H:i:s",  time());
$sql = "update `settings_current`  set  `selected_value`='".$sys_date_time."'  where  `variable`='system_date_set' ";
api_sql_query($sql);

$user_id = $_SESSION['_user']['user_id'];

$point_out_status = intval( $_POST['point_out_status'] );
if($point_out_status==1){
     $_SESSION['point_out_status'] = 1;
     header("location:http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
}else if($point_out_status==2){
     $_SESSION['point_out_status'] = 2;
}else{
     $_SESSION['point_out_status'] = 0;
}
$point_out_cancel = intval( $_POST['point_out_cancel'] );
if($point_out_cancel==1){
    $_SESSION['point_out_cancel']=1;
}
$page_self=  end(explode('/', $_SERVER['PHP_SELF']));
                        
 $userNo = $_SESSION ['_user'] ['username'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
    <?php 
if(api_get_setting ( 'lm_switch' ) == 'true'){
   echo' ISTS  NSFOCUS';
}else{
echo get_setting ( 'siteName' );
}?>
 </title>
<link rel="stylesheet" href="<?=WEB_QH_PATH?>css/layout.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?=WEB_QH_PATH?>js/jbox-v2.3/jBox/Skins/Blue/jbox.css" media="all"/>
<link type="text/css" rel="stylesheet" href="<?= WEB_QH_PATH?>css/base.css">
<link type="text/css" rel="stylesheet" href="<?= WEB_QH_PATH?>css/course-intr.css">
<link type="text/css" rel="stylesheet" href="<?= WEB_QH_PATH?>css/lab-need.css">
<link href="<?=URL_APPEDND?>/themes/js/jquery-plugins/Impromptu.css" rel="stylesheet" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="<?= WEB_QH_PATH?>css/learn-center.css">
<link type="text/css" rel="stylesheet" href="<?= WEB_QH_PATH?>css/course-ranking.css">
<link  rel="shutcut icon" href="<?= WEB_QH_PATH?>images/focion.ico">
<script type="text/javascript" src="<?=URL_APPEDND?>/themes/js/commons.js"></script>
<script type="text/javascript" src="<?=URL_APPEDND?>/themes/js/jquery-plugins/jquery-impromptu.2.7.min.js"></script>
<script src="<?= WEB_QH_PATH?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=WEB_QH_PATH?>js/jbox-v2.3/jBox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="<?=WEB_QH_PATH?>js/jbox-v2.3/jBox/i18n/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript">
        $(function(){
            $('.column').equalHeight();
        });
</script>

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
     $.focusblur("#searchkey");
 });

	
  function closebtn(){     
             if(confirm("你确定退出系统吗？")){
                 location.href="<?=URL_APPEND?>main/cloud/cloudvmstop.php?user_id=<?=$user_id?>";
             }           
  }
 
    function closedown(){
      if(confirm("你确定关机吗？")){
                 location.href="<?=$url?>?action=shutdown";
      }
  }
</script>
<?php
   
      echo import_assets ( "js/html5.js",WEB_QH_PATH);
      echo import_assets ( "js/jquery-1.5.2.min.js" ,WEB_QH_PATH);
      echo import_assets ( "js/hideshow.js" ,WEB_QH_PATH);
      echo import_assets ( "js/jquery.tablesorter.min.js", WEB_QH_PATH );
      echo import_assets ( "js/jquery.equalHeight.js" ,WEB_QH_PATH);
      //echo import_assets ( "commons.js" );
      //echo import_assets ( "jquery-plugins/Impromptu.css", api_get_path ( WEB_JS_PATH ) );
      echo import_assets ( "jquery-plugins/jquery-impromptu.2.7.min.js" );
      echo import_assets ( "jquery-plugins/jquery.wtooltip.js" , api_get_path ( WEB_JS_PATH ));
      echo import_assets ( "js/portal.js", WEB_QH_PATH );
      
     //top10                
        $vm_log = Database::get_main_table ( vmdisk_log); 
        $sql="select  count(lesson_id) as lesson_num,lesson_id  from {$vm_log} ";
        $sql.=" group  by  lesson_id  order by  count(lesson_id) desc  limit 10";
        $ress= api_sql_query_array_assoc($sql);        
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
        
       //$("#j-fixed-head").width=$(window).width();


 }) 


 $(document).ready(function(){
		$('.u-categ li').bind('click',function(){
			var thisIndex = $(this).index();
//			$(this).addClass('cur').siblings().removeClass('cur');
			$('.g-mn1 .g-mn1c').eq(thisIndex)
			.show().siblings().hide();	
		});	

  $('.u-card').mouseover(function(){
      $(this).children('.card').children('.descd').css('bottom','0');
      $(this).children('.card').children('.descd').css('display','block');
  });
  $('.u-card').mouseout(function(){$(window).height()
      $(this).children('.card').children('.descd').css('bottom','-136');
      $(this).children('.card').children('.descd').css('display','none');
  })


    $('.vm-img').mouseover(function(){
        $(this).children('.vm-tips').addClass('vm-up'); 
    });
    $('.vm-img').mouseout(function(){
        $(this).children('.vm-tips').removeClass('vm-up'); 
    });
    
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
<script type="text/javascript">
  $(function(){
     var footerHeight=0;
     var footerTop=0;
     var footer=$("#footer");

     function positionFooter(){
         
         footerHeight=footer.height();
         footerTop=($(window).height()-footerHeight-2)+'px';
       
         //如果页面内容高度小于屏幕高度，div#footer将绝对定位到屏幕底部，否则div#footer保留它的正常静态定位。
         
         if(document.documentElement.clientHeight-67 > document.documentElement.offsetHeight-4){
            footer.css('position','absolute');
            footer.css('left','0');
            footer.css('top',footerTop);
         } 
         
     }  
     positionFooter();
     //$(window).scroll(positionFooter).resize(positionFooter);
     
     
    //头部导航在手机浏览器中正常显示
    var s=$(document).width();
     $("#j-fixed-head").width(s);
     $("#footer").width(s);
       
 //头部导航固定    
var speed = 100;
var scrollTop = null;
var hold = 0;
var float_banner;
var pos = null;
var timer = null;
var moveHeight = null;
float_banner = document.getElementById("j-fixed-head");
window.onscroll=scroll_ad;
function scroll_ad(){
scrollTop = document.documentElement.scrollTop+document.body.scrollTop;
pos = scrollTop - float_banner.offsetTop;
//pos = pos/10;
moveHeight = pos>0?Math.ceil(pos):Math.floor(pos);
if(moveHeight!=0){
float_banner.style.top = float_banner.offsetTop+moveHeight+"px";
setTimeout(scroll_ad,speed);
}
//alert(scrollTop);
}

//头部效果
$("#nav-info-box").mouseover(function(){
    $(".set").css("display","block");
})
$("#nav-info-box").mouseout(function(){
    $(".set").css("display","none");
})


    
   /*学习中心侧边栏样式*/ 
    
  $("#j-courseTabList>li").click(function(){
      $(this).addClass("u-curtab").siblings().removeClass("u-curtab");
  });
  
  $("#bdshare").mouseover(function(){
      $(this).css("width","140px").find("div").css("display","block");
  });
  $("#bdshare").mouseout(function(){
      $(this).css("width","24px").find("div").css("display","none");
  })
  
  
  $(".vm-all").mouseover(function(){
    $(".vm-count").css("display","block");
})
$(".vm-all").mouseout(function(){
    $(".vm-count").css("display","none");
})
       
  }) 
  
</script>

<script type="text/javascript">
    
//    课程排行页面效果
     $(document).ready(function(){
    $(".tab-hd span").mouseover(function(){
        $(".tab-hd span:first").addClass("current");
        $(this).addClass("current").siblings("span").removeClass("current");
       $("#layout-t .popular_courses:eq("+$(this).index()+")").show().siblings(".popular_courses").hide();
        if(document.documentElement.clientHeight-67 < document.documentElement.offsetHeight-4){
            $("#footer").css('position',' absolute');
            $("#footer").css('top','');
        }else{
            var footerHeight=$("#footer").height();
            var footerTop=($(window).height()-footerHeight-2)+'px';
            $("#footer").css('position',' absolute');
            $("#footer").css('top',footerTop);
        }

    })
    })

 </script>
  <!--我的足迹-->
<script type="text/javascript">
    
//   我的足迹
  $(document).ready(function(){
    $(".j-tabs a").click(function(){
        $(".j-tabs a:first").addClass("cur");
        $(this).addClass("cur").siblings("a").removeClass("cur");
       $("#j-all-box .m-person-course:eq("+$(this).index()+")").show().siblings(".m-person-course").hide();
    })
   
    
    })

 </script>
<style>
   
    #sub1{
      width:50px; 
      height:27px;
      background:#5C5F68;
      color:#bbb;
      font-size: 14px;
     margin: -29px 0 0 205px;
      border: 0;
      box-shadow:0 0 0;
    }
    .vm-all{
        width:40px;
        padding:10px 0;
        position:fixed;
        right:0px;
        top:45%;
        z-index:19999;
    }
     .vm-all  .vm-title{
         display:block;
        cursor: pointer;
        width:40px;
        height:40px;
        background: url("<?= URL_APPEND?>portal/sp/images/rt-ico.png") 0 -410px no-repeat;
        border-bottom:1px solid #fff;
    }
    .vm-count{
        width:40px;
        padding:5px 0;
        background:#5d5c5c;
    }
   
    .vm-count .vm-img{
        margin:3px 2.5px;
    }
    .vm-count .vm-img a{
        display:block;
    }
    
    .vm-tips{
        position:absolute;
        top:50%;
        right:50px;
        display:none;
/*        padding:10px 5px;*/
        text-align: center;
        color: #fff;
        background: #333;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius:10px 4px 4px 10px;
        opacity:0.8;
    }
    
   
    .vm-tips:after {
   content: "";
    position: absolute;
    top:6px;
    right:-10px;
    border-width: 20px 0px 0px 20px;
    border-style: solid;
    border-color: #333 transparent;
    display: block;
    width: 0;
    -webkit-transform:rotate(45deg);
    -moz-transform:rotate(45deg);
    -o-transform:rotate(45deg);
    -ms-transform:rotate(45deg);
    transform:rotate(45deg);
    
}
    .vm-up{
        display:block;
    }
    .vm-tips .tips-text{
        display:block;
        padding:0 5px;
        height:32px;
        line-height:32px;
       min-width:230px;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    #point_out_id{
        width: 600px;
        margin: 30px auto;
        position:absolute;
        left:35%;
        top:7%;
        z-index:20001;       
        background-color: #fff;
        border: 1px solid #999;
        border: 1px solid rgba(0,0,0,0.2);
        border-radius: 6px;
        outline: 0;
        -webkit-box-shadow: 0 3px 9px rgba(0,0,0,0.5);
        box-shadow: 0 3px 9px rgba(0,0,0,0.5);
        background-clip: padding-box;
        font-family:'宋体';
    }
    .modal-header {
        min-height: 16.428571429px;
        padding: 15px;
        border-bottom: 1px solid #e5e5e5;
}
.modal-header .close {
       margin-top: -2px;
}
button.close {
        padding: 0;
        cursor: pointer;
        background: transparent;
        border: 0;
        -webkit-appearance: none;
}
.close {
    float: right;
    font-size: 21px;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .2;
    filter: alpha(opacity=20);
}
.modal-header h3{
    font-family:'宋体';
    font-size:24px;
    font-weight:normal;
    margin-top: 20px;
    margin-bottom: 10px;
}
   
  .modal-body {
      padding: 15px 30px 30px 30px;
}    
   .modal-body   p {
     margin: 0 0 10px;
} 

.modal-footer {
    padding: 19px 20px 20px;
    margin-top: 15px;
    text-align: right;
    border-top: 1px solid #e5e5e5;
}
.modal-footer:after{
    display:block;
    clear:both;
    content:"";
}
.modal-footer  span{
    display:inline-block;
    padding:5px;
}

.modal-footer  .butn {
    display:inline-block;
/*    padding: 10px 12px;*/
height:33px;
width:77px;
    font-size: 14px;
    font-weight: normal;
    line-height: 25px;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background:#ccc;
    border: 1px solid transparent;
    border-radius: 4px;
    color:#333;
}

.modal-footer .btn-primary {
color: #fff;
background-color: #3C8440;
border-color: #3C8440;
}

</style>
       <?php 
            if(api_get_setting ( 'lm_switch' ) == 'true'){
                ?>
  <style>
      .m-nav a:hover{ 
   background:url("images/headbg.gif") 0px -70px; 
   color:#000;
     }
 .m-moclist .nav .u-categ .navitm.it.cur:hover .f-f1:hover{
background:#357CD2 !important;
color:#fff;
}
.m-moclist .u-btn-group .u-btn-active {
background: #357CD2;
border-color: #357CD2;
}
.tag-title {
    background: #357CD2 !important;
}
.go {
background: #357CD2 !important;
}
.go-green {
    background: #357CD2 !important;
}
.u-tabul .u-curtab a, .u-tabul .u-curtab a:hover {
background-color: #357CD2 !important;
}
  </style>
      <?php   }   ?>
</head>
    
<body> 
   <!--
    <div id="top-opacity" class="top-down">
        <span class="top-close">×</span>
      <div id="top-out">
            <h3 class="top-img"></h3>
            <div class="form" id="form">
	       <div class="corner" id="corner"></div>
               <h3 class="ranking-title">排行榜</h3>
	        <ul class="button">
                    <?php foreach ($ress as $key=>$value){
                        $cate=DATABASE::getval("select `category_code`  from  `course` where  `code`=".$value['lesson_id']);
                        $cate_code=DATABASE::getval("select  `code`  from  `course_category`  where id=".$cate); 
                    ?>
                    <li>
                        <?php if($key<3){ ?>
                        <span class="top-num num1"><?=$key+1?></span>
                        <?php }else{  ?>
                         <span class="top-num"><?=$key+1?></span>
                        <?php } ?>
                        <a href="#">
                            <span class="top-course-img">
                                <img src='<?php  echo  URL_APPEDND."/storage/category_pic/".$cate_code; ?>' height='50px' width='50px'>
                            </span>
                            <span class="top-name">
                                <?php  
                                $title=DATABASE::getval("select `title`  from  `course`  where  `code`=".$value['lesson_id']);
                                $title=explode("-", $title);
                                $nu=count($title);
                                if($nu==3){
                                    echo $title[1]."-".$title['2'];
                                }else if($nu==2){
                                    echo $title[1];
                                }else{
                                    echo $title[0];
                                }
                                ?> 
                            </span>
                        </a>
                        <span class="people">学习人数：<?=$value['lesson_num']?></span>
                    </li>
                    <?php } ?>
                    
	        </ul>
           </div>	
       </div>
    </div>
 -->  
<?php  

//关闭VM提醒
    $sql = "select `vmid`,`addres`,`proxy_port`,`user_id`,`lesson_id` FROM  `vmtotal` where `user_id`= '{$user_id}' and  `manage`='0'"; 
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $vm= array (); 
    while ($vm = Database::fetch_row ( $res)) { 
        $vms [] = $vm; 
    }
    if($vms){
 if($page_self!="course_home.php" && $_SESSION['point_out_status']!=1 && $_SESSION['point_out_status']!=2 && $_SESSION['point_out_cancel']!=1){ 
        ?>
 <div id='loading-mask' style=" position:fixed;
       top: 0%;  left: 0%;width:100%;height:100%;z-index:20000;
        background-color:#000; -moz-opacity: 0.6;  opacity:.60;  filter: alpha(opacity=60);"></div>
   <div id="point_out_id">
         <div class="modal-header">
             <h3>开始新实验</h3>
         </div>
        <div  class="modal-body">
            <p> 是否关闭当前打开的实验环境？</p>
        </div>
        <div class="modal-footer">
         <span style='float: right;margin-left:10px; '>
        <form action="#" method="post" name="form_point_yes" onsubmit="remove_point_div()">
            <input type="hidden" name="point_out_status" value="1"> 
            <input class="butn btn-primary" type="submit" value="确定" >
        </form>
          </span>
            
       <span  style='float: right;'>
        <form action="#" method="post" name="form_point_no" onsubmit="remove_point_div()">
            <input type="hidden" name="point_out_status" value="2"> 
            <input type="hidden" name="point_out_cancel" value="1"> 
            <input class="butn" type="submit" value="暂不关闭" >
        </form>
       </span>
        </div>
    </div> 
        <?php
        }
        
        if($page_self=="course_home.php"){
            
            $n_cidReq = htmlspecialchars($_GET ["cidReq"]);
            if($vms[0][4]!=$n_cidReq){
                ?>
  <div id='loading-mask' style=" position:absolute;
       top: 0%;  left: 0%;width:100%;height:100%;z-index:20000;
        background-color:gray; -moz-opacity: 0.6;  opacity:.60;  filter: alpha(opacity=60);"></div>
   <div id="point_out_id">
      <div class="modal-header">
             <h3>开始新实验</h3>
         </div>
      <div  class="modal-body"><p>您已经打开一个实验环境，选择确定关闭此实验环境，返回进入该实验课程？</p></div>
        <div class="modal-footer">
         <span style='float:right;margin-left:10px;'>
        <form action="learning_center.php" method="post" name="form_point_yes1" onsubmit="remove_point_div()">
            <input type="hidden" name="point_out_status1" value="1">  
                <input type="hidden" name="page_url" value="<?php echo  $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
           <input  class="butn btn-primary" type="submit" value="确定" >
        </form>
          </span>
            <span  style='float: right;'>
        <form action="learning_center.php" method="post" name="form_point_no1" onsubmit="remove_point_div()">
            <input type="hidden" name="point_out_status1" value="2"> 
            <input type="hidden" name="point_out_cancel1" value="1"> 
            <input  class="butn" type="submit" value="返回" >
        </form></span>
        </div>
    </div> 
        <?php
        }
        }
        
     }


    $logo_set=  explode(";", api_get_setting ( 'header_logo_set' ) ); 
    $logo_width=$logo_set[0];
    $logo_height=$logo_set[1]; 
?>
   <div class="vm-all">
       <?php  
       $local_addres  = $_SERVER['HTTP_HOST'];
       $local_addresx = explode(':',$local_addres);
            $local_addresd = $local_addresx[0];
        $sql = "select `addres`,`proxy_port`,`system`,`lesson_id` FROM  `vmtotal` where `user_id`= '{$user_id}' and  `manage`='0'"; 
        $ress = api_sql_query ( $sql, __FILE__, __LINE__ );
        $vm= array (); 
        while ($vm = Database::fetch_row ( $ress)) { 
            $vmss [] = $vm; 
        }
        if($vmss){  ?>
            <span class="vm-title"></span>
       <?php }
       foreach ($vmss as  $val){ 
       ?>
            
         <div class="vm-count " style="display:none;">  
             <div class="vm-img">
                 <a href="<?php  echo 'http://'.$local_addres.'/lms/main/html5/auto.php?lessonId='.$val[3].'&host='.$local_addresd.'&port='.$val[1].'&system='.$val[2].'&sign=startvm';?>" target="_blank">
                     <img src="<?=URL_APPEDND?>/portal/sp/images/computer.png" width="35px;height:35px;">
                 </a>
                 <div class="vm-tips">
                     <span class="tips-text">
                        <?=DATABASE::getval("select `title` from  `course`  where  `code`=".$val[3])?>
                     </span>
                 </div>
             </div>  
         </div>   
       <?php } ?>
 </div>   

 <div id="j-fixed-head" class="g-hd   <?=(api_get_setting ( 'lm_switch' ) == 'true' ?'lm-f-bg1':'f-bg1')?>" >
         <div class="g-flow">
             <div class="m-header f-pr f-cb"> 
                 <div class="m-logo logo-img left" <?=(api_get_setting ( 'lm_switch' ) == 'true'?'style="margin-top: 18px;"':'')?> >
                    <?php 
                        if(api_get_setting ( 'lm_switch' ) == 'true'){
                            ?>
                     <h1 style="color: #fff;margin-top:6px;font-weight: bold;width: 400px;"><img src="<?= URL_APPEND?>portal/sp/images/lm_logo_dcst.png" title="信息安全实训平台"   /></h1>
                       <?php
                        }else{
                            ?>
                     <?php
                        if(api_get_setting ( 'enable_modules', 'exam_center' ) == 'true' && api_get_setting ( 'lnyd_switch' ) == 'true' ){
                        ?>
                  <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/exam_list.php">
                       <img src="<?=URL_APPEDND."/panel/default/assets/images/logo4.gif"?>">
                    </a>
                     <?php }else if(api_get_setting ( 'lnyd_switch' ) == 'true' ){?>
                     <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/new-index.php">
                       <img src="<?=URL_APPEDND."/panel/default/assets/images/logo4.gif"?>">
                    </a>
                     <?php  } else{   ?>
                     <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/select_study.php">
                       <img src="<?=URL_APPEDND."/panel/default/assets/images/logo4.gif"?>">
                    </a>
                     <?php  }    ?>
                     <?php  }    ?>
                  
                 </div>
                     <?php 
                        if(api_get_setting ( 'lm_switch' ) == 'flase'){
                            ?>
                <div class="n-logo logo-img left">
                    <a hidefocus="true" href="#">
                        <img src="<?= WEB_QH_PATH?>images/home5.png">
                    </a>
                </div>
             <?php  }    ?>
                 <div class="m-nav f-cb left" id="j-navFind"   <?=(api_get_setting ( 'lm_switch' ) == 'true' ?'style="margin-left: 60px;"':'')?> >
<?php
                        if(api_get_setting ( 'enable_modules', 'exam_center' ) == 'true' && api_get_setting ( 'lnyd_switch' ) == 'true' ){
                           if(page_name() !== 'quiz_paper.php'){
                               $url_name = URL_APPEND.'portal/sp/exam_list.php';
                           }else{
                               $url_name = '#';
                           }
?>
                     <a hidefocus="true" href="<?=$url_name?>" title='考试中心'>考试中心</a>
<?php
                        }else{
                      $sql_setup =  "select id from setup order by custom_number LIMIT 0,1";
                      $courseId= DATABASE::getval ( $sql_setup, __FILE__, __LINE__ );
                     if(api_get_setting ( 'enable_modules', 'course_center' ) == 'true'){
                        if($courseId){
                            echo '<a hidefocus="true" href="'.URL_APPEND.'portal/sp/select_study.php?id='.$courseId.'"  title="选课中心">选课中心</a>';
                        }else{
                            echo '<a hidefocus="true" href="'.URL_APPEND.'portal/sp/select_study.php"  title="选课中心">选课中心</a>';
                        }
                     }
                     ?>
                        
                        <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/learning_before.php<?=($courseId?'?id='.$courseId:'')?>" title='学习中心'>学习中心</a>
                         <?php if(api_get_setting ( 'jfjlg_switch' ) == 'true' ){  ?>
                        <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/teacher_course.php" title='实验课程'>实验课程</a>
<?php                          } ?> 
<?php                    if(api_get_setting ( 'enable_modules', 'exam_center' ) == 'true'){?>
                        <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/exam_list.php" title='考试中心'>考试中心</a>
<?php                    }   if(api_get_setting ( 'enable_modules', 'router_center' ) == 'true'){   ?>
<!--                   <a hidefocus="true" href="<?= URL_APPEND?>topoDesign/index.php" title='路由交换'>路由交换</a>-->
<?php               }                       
                        $occupatID=  Database::getval("select `id`  from  `skill_occupation`   order  by  `id`  limit  1");    ?>
<!--                   <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/courseware_tree.php?id=<?=$courseId ?>" title="资源树">资源树</a>-->
<!--                   <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/msg_view.php" title='站内信'>站内信</a>-->
                        <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/new-trends.php?id=<?=$courseId ?>" title="最新动态">最新动态</a>
                         <a id="top10_export" hidefocus="true" href="<?= URL_APPEND?>portal/sp/ranking.php" title="TOP10">排行榜 </a>			
 <?php                if(api_get_setting ( '51ctf' ) == 'true' ){  ?>
                        <a  href="<?= URL_APPEND?>main/ctf/index.php" target="_blank" title="51CTF">51CTF<span class="top10-mark"></span></a>
<?php                }   if(api_get_setting ( 'lnyd_switch' ) != 'true' ){  ?>
                        <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/profile_line.php?id=<?=$occupatID?>" title="职业技能">职业技能 <span class="top10-mark"></span></a>
<?php                 }
                               if(api_get_setting ( 'zgkd_switch' ) == 'true' || api_get_setting ( 'lnyd_switch' ) == 'true'){ ?>
                       <a hidefocus="true" href="<?= URL_APPEND?>portal/sp/video-more.php" title="安全意识">安全意识 <span class="top10-mark"></span> </a>
<?php                         } ?>
<?php                } ?>
                 </div>
                <div class="m-links right" id="j-topnav">
                    <div class="unlogin">
<?php
                      $user_img=$_SESSION['_user']['picture_uri'];
                      $userpath=api_get_path ( WEB_PATH ) . 'storage/users_picture/';
                      $user_image=$user_img ? $userpath.$user_img : URL_APPEND."portal/sp/images/user-small.jpg";
                      if($_SESSION['_user']['status']){  
?>                        
                        <div class="f-thide login-name">
                            <a class="f-fc9" href="javascript::void(0);"><?=$userNo?></a>
                        </div>
                        <div class="user-info" id="nav-info-box">
                        <div class="face">                                
                            <img id="my-img" src="<?=$user_image?>" width="28px" height="28px" alt="用户头像">
                        </div>
                            <div class="set j-nav-set x-hide">
                                <ul class="u-navbg u-navbg2">                                                                     
<?php                if(api_get_setting ( 'lnyd_switch' ) === 'true' && api_get_setting ( 'enable_modules', 'exam_center' ) !== 'true'){?>
                                 <li class="text">
                                      <a class="s-fc2" href="<?= URL_APPEND?>portal/sp/my_foot.php" title="用户中心">用户中心</a>
                                 </li>
<?php                }elseif(api_get_setting ( 'lnyd_switch' ) !== 'true' ){?>
                                 <li class="text"> <a class="s-fc2" href="<?= URL_APPEND?>portal/sp/my_foot.php" title="用户中心">用户中心</a> </li>
<?php                }   if ($_SESSION['_user']['status'] == PLATFORM_ADMIN)    {?>
                                <li> <a href="<?=URL_APPEDND?>/main/admin/index.php" target="_blank" title="后台" class="self j-uhref">后台</a></li>
                                <li> <a target="_top" onclick="closebtn();" title="退出" class="exit">退出</a></li>
<?php                }else if($_SESSION['_user']['status']=='1')  {?>
                                    <li> <a href="<?=URL_APPEDND?>/user_portal.php" target="_blank" class="self j-uhref" title="后台">后台</a>  </li>
                                    <li>  <a target="_top" onclick="closebtn();" title="退出" class="exit">退出</a> </li>
<?php                }else if(api_get_user_id()) {
                               if(api_get_setting ( 'lnyd_switch' ) !== 'true'){
                                    $student_pdf = 'manual.pdf';
                               }else{
                                    $student_pdf = 'lnyd_manual.pdf';
                               }?>
                                    <li class="text"> <a class="s-fc2" href="<?= URL_APPEND?>storage/<?=$student_pdf?>" title="用户帮助手册" target="_blank">用户帮助手册</a></li>
                                    <li> <a target="_top" onclick="closebtn();" title="退出" class="exit">退出</a> </li>
<?php                }?>                                 
                              </ul>
                            </div>
                        </div>
<?php
                      }else if(!$_SESSION['_user']['status']){
                          if(api_get_setting ( 'lm_switch' ) == 'true' && api_get_setting ( 'lm_switch_new' ) == 'true'){
?>                        
                           <a href="<?= URL_APPEND?>portal/sp/lm_login.php" title="登录">登录</a>
<?php                          } else{   ?> 
                           <a href="<?= URL_APPEND?>portal/sp/login.php" title="登录">登录</a>
<?php                                   }
                             } ?>
                   </div>
                </div>
                 <?php  
                        $page_name=  page_name();
                        if($page_name=="select_study.php" || $page_name === 'new-index.php'){
                            echo '<form action="course_catalog.php" method="post" class="search-form"   name="form_sel">';
                        }else{
                            echo '<form action="#" method="post"  class="search-form"  name="form_sel">';
                        }
                 ?>
                 
                <div class="nav-search u-searchUI" id="j-searchP">
                 <?php
                        if($page_name=="select_study.php" || $page_name=="course_catalog.php" || $page_name=="learning_center.php" || $page_name=="labs_report.php"  || $page_name=="new-index.php"|| $page_name=="exam_list.php"|| $page_name=="course_snapshot_list.php" ){
                    ?>
                    <div class="box j-search f-cb off" id="j-search2" onclick="enterclick()">
                           <input type="text" name='auto-id-rTOGAi3MiQOM7HrB' placeholder="搜索课程" class="j-input left" id="auto-id-rTOGAi3MiQOM7HrB" onclick="enterclick()">
                           <input class="topSearchBtn" type="submit" value="">
                    </div>
                        <?php } ?>
<!--                    <input type="submit" id='sub1' value="提交" class='sub1' style="display:none;"/>-->
                </div>
                </form>
                <script>
                                                    function lxfEndtime(){
                                                            $('.lxftime').each(function(){
                                                                  var lxfday=$(this).attr('lxfday');//用来判断是否显示天数的变量
                                                                  var endtime = new Date($(this).attr('endtime')).getTime();//取结束日期(毫秒值)
                                                                  var nowtime = new Date().getTime();        //今天的日期(毫秒值)
                                                                  var youtime = endtime-nowtime;//还有多久(毫秒值)
                                                                  var seconds = youtime/1000;
                                                                  var minutes = Math.floor(seconds/60);
                                                                  var hours = Math.floor(minutes/60);
                                                                  var days = Math.floor(hours/24);
                                                                  var CDay= days ;
                                                                  var CHour= hours % 24;
                                                                  var CMinute= minutes % 60;
                                                                  if(CMinute<10){CMinute='0'+CMinute;}
                                                                  var CSecond= Math.floor(seconds%60);//"%"是取余运算，可以理解为60进一后取余数，然后只要余数。
                                                                  if(CSecond<10){CSecond='0'+CSecond;}
                                                                  if(endtime<=nowtime){
                                                                      $(this).html('')//如果结束日期小于当前日期就提示过期啦
                                                                  }else if(endtime-nowtime<=1800000){
                                                                      $(this).html('00:<span>'+CMinute+'</span>:<span>'+CSecond+'</span>'); 
                                                                  }
                                                            });
                                                     setTimeout('lxfEndtime()',1000);
                                                    };
                                                  $(function(){
                                                        lxfEndtime();
                                                     });
                                                  </script>
                <!--考试时间提醒-->
                
                <?php
                //辽宁移动开关lnyd_switch
                    if(api_get_setting('lnyd_switch') == 'true' && api_get_setting ( 'enable_modules', 'exam_center' ) == 'true'){//开启辽宁移动开关和开启在线考试的情况下
                        if($page_name == "exam_center.php"){
                                    $times = date("Y-m-d H:i:s",time());
                                    $sql_papers = "select exam_id, user_id, available_start_date, available_end_date from exam_rel_user left join exam_main on exam_rel_user.exam_id = exam_main.id where user_id = '".$user_id."' and available_start_date > '".$times."' and available_start_date != '".$times."' order by available_start_date asc limit 1";
                                    $pa_da = api_sql_query_array_assoc($sql_papers,__FILE__,__LINE__);//获取最近一次考试试卷id,考试时间,结束时间
                          if(!empty($pa_da)){
                                    $exam__start_times = strtotime($pa_da[0]['available_start_date']);//考试开始时间
                                    $minute = $exam__start_times-60*30;//获取分钟
                                    $vs = date('m-d-Y H:i:s',$exam__start_times);
                            ?>
                <div class="lxftime" endtime="<?=$vs?>" lxfday="no"></div>
                              <?php
                               }    
                           }   
                    }
                ?>
             </div>
         </div>
         
       
     </div>
<div class="center-div"></div>
<?php  
                $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
                $name = $_SESSION['_user']['firstName'];
                $dept_id = $_SESSION['_user']['dept_id'];
                $sign_date =date('Y-m-d H:i:s',time());
                $username = $_SESSION['_user']['username'];
                $sql="select count(*) from work_attendance where username='".$username."' and mode=1";
                $mode=Database::getval($sql,__FILE__,__LINE__);  
                if(!api_is_admin() && api_get_user_id()){ 
                   if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'work_attendance'"))===1){ 
                            if($mode==0 OR $mode==''){
                               $qiandao='<span id="mydate"></span> <a class="qian" href="'.$url.'?action=sign"   target="_top" >签到</a>';
                           }else{
                               $qiandao='<span id="mydate"></span> <a class="qian" href="'.$url.'?action=sign_return" target="_top" >签退</a>';
                           }
                       
                   }
               }
               
                   if (isset ( $_GET ['action'] )) {   
                    switch (htmlspecialchars( $_GET ['action'] )) {
                        case 'sign' :
                            $sql= "INSERT INTO `work_attendance` (`id`, `username`, `name`, `dept_name`, `sign_date`, `sign_return_date`, `mode`) VALUES (NULL, '".$username ."', '".$name."', '".$dept_id."', '".$sign_date."', '0000-00-00 00:00:00', '1');";
                            $result = api_sql_query ( $sql, __FILE__, __LINE__ );   
                            if($result){
                                echo "<script language='javascript' type='text/javascript'>";
                                echo "window.location.href='$url'";
                                echo "</script>";
                            }
                            break;
                        case 'sign_return' :
                            $sql="select sign_date from work_attendance where  `work_attendance`.`username` ='".$username."' and `mode` ='1'";
                            $res=api_sql_query($sql,__FILE__,__LINE__);
                            $dates=Database::fetch_row($res);
                            $startdate= $dates[0];
                            $range=floor((strtotime($sign_date)-strtotime($startdate))%86400/60);
                            $sql= "UPDATE  `work_attendance` SET  `sign_return_date` =  '".$sign_date."',`mode` =  '2',`range`='".$range."' WHERE  `work_attendance`.`username` ='".$username."' and `mode` ='1'";
                            $result = api_sql_query ( $sql, __FILE__, __LINE__ );
                            if($result){
                                echo "<script language='javascript' type='text/javascript'>";
                                echo "window.location.href='$url'";
                                echo "</script>";
                            }
                            break;
                    }
                }
if(!api_is_admin() && api_get_user_id()){
?>
<div id="bdshare">
    <img src="<?= URL_APPEND?>portal/sp/images/r2.png">
     <div class="dayq" style="display:none; left:24px;">
         <?=$qiandao?>
     </div>   
</div>    
<?php
}    
    //获取当前url中php页面名
    function page_name(){
        $page_name=$_SERVER['PHP_SELF'];
        $page_name=  explode('/', $page_name);
        $page_name=$page_name[count($page_name)-1];
        return $page_name;
    }
if ($htmlHeadXtra && is_array ( $htmlHeadXtra )) {
	foreach ( $htmlHeadXtra as $head_html ) {
		echo $head_html;
	}
}
?>
<script type="text/javascript">
    $(function(){
        var dtime = new Date();
        var week = dtime.getDay();   
        switch(week){
            case 0:
                week = "星期日";
                break;
            case 1:
                week = "星期一";
                break;
            case 2:
                week = "星期二";
                break;
            case 3:
                week = "星期三";
                break;
            case 4:
                week = "星期四";
                break;
            case 5:
                week = "星期五";
                break;
            case 6:
                week = "星期六";
                break;
        }
 
        $("#mydate").html(week);
    })
    
    function remove_point_div(){
        $("#point_out_id").remove();
        $("#loading-mask").remove();
    }
</script>
