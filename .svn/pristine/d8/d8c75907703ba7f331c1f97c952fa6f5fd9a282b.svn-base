<?php
header("content-type:text/html;charset=utf-8");
require_once ('../inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'database.lib.php');
$userId = $_SESSION['_user']['user_id'];
if(!isset($userId)){
	$html = '<p style="color:red;font-weight:bold">对不起，用户会话已经过期，或者您的访问非法！</p>';
    Display::display_error_message ( $html, false );
    exit();
}

$port   = intval($_GET['port']);
$system = trim(getgpc('system','G'));
$str='';
$str2='';
if( isset($_GET['host'] ) && $_GET['host'] !=='' )
{
     $str.="?host=".$_GET['host'];
     if( isset($_GET['port'] ) && $_GET['port'] !=='' )
     {
        $str.="&port=".$_GET['port'];
     }
}
if(isset($_GET['host']) && $_GET['host']!=='')
{
     $str2.="host=".$_GET['host'];
     if(isset($_GET['port']) && $_GET['port']!=='')
     {
         $str2.="&port=".$_GET['port'];
     }
     if(isset($_GET['lessonId']) && $_GET['lessonId']!=='')
     {
         $str2.="&lessonId=".trim($_GET['lessonId']);
     }
     if(isset($_GET['system']) && $_GET['system']!=='')
     {
         $str2.="&system=".trim($_GET['system']);
     }
}

//URL访问非法
$urls = explode("?",$_SERVER['HTTP_REFERER']);
$filename = substr( $urls[0],strrpos($urls[0],'/')+1 );
//参数访问非法
$user_system = $_SESSION['user_system'];
$system_arr = array();
foreach($user_system as  $key => $value)
{
       $system_arr[]=$value['system'];
}
if(api_get_setting('enable_modules', 'clay_oven') == 'false')
{
     if( !api_is_admin() )
     {
       if( !in_array($system, $system_arr) )
       {
             $html = '<p style="color:red;font-weight:bold">对不起，您的访问非法，请检查您的参数！</p>';
             Display::display_error_message ( $html, false );
             exit();
       }
     }
}
$sql = "select user_id,port,vmid from vmtotal where proxy_port=$port";
$ress = api_sql_query ( $sql, __FILE__, __LINE__ );
$vm = Database::fetch_row ( $ress);
$sql_snap = "select id from `snapshot` where `user_id`='".$vm[0]."' and  `type`=2 and `port`='".$vm[1]."' and `vmid`='".$vm[2]."' and `status`='1' ";
$dada = Database::getval( $sql_snap,__FILE__,__LINE__);

$course_code = htmlspecialchars( addslashes( $_GET['lessonId'] ) );
$tbl_courseware = Database::get_course_table ( TABLE_COURSEWARE );
$sql = "SELECT * FROM $tbl_courseware WHERE cc='" . $course_code . "' and cw_type!='media'" ;
$item = Database::fetch_one_row ( $sql, false, __FILE__, __LINE__ );
$cw_type = $item['cw_type'];
switch ($cw_type)
{
    case 'link' :
         $link_url = Security::remove_XSS ( $item ['path'] );
         event_link ( $cw_id );
         break;
    case 'html' :
         $http_www = api_get_path ( WEB_COURSE_PATH ) . api_get_course_path () . '/html';
         $path = (substr($item['path'],-1)!='/'?$item['path'].'/':$item['path']);
         $link_url = $http_www.$path.$item['attribute'];
         break;
}

//倒计时关闭虚拟机功能
date_default_timezone_set("Asia/Hong_Kong");//地区
//配置每天的活动时间段
$now_time =  date("Y-m-d H:i:s",  time());
$lesson_id = getgpc("lessonId");
$uid =  api_get_user_id();
$s_time =  Database::getval("select  `stime`  from  `vmtotal`  where  `user_id`=".$uid."  and   `lesson_id`=".$lesson_id);
$total_hours = api_get_setting("clouse_vm_witch");

?>
<!DOCTYPE html>
<html>
<head>

    <title><?=$system?></title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    <link rel="apple-touch-startup-image" href="images/screen_320x460.png" />

    <link rel="apple-touch-icon" href="images/screen_57x57.png">

    <link rel="stylesheet" href="include/base.css" title="plain">

    <script src="include/util.js"></script>
    <script src="include/jquery-1.7.2.min.js"></script>

</head>

<body style="margin: 0px;">
        <?php
        if(api_get_setting('enable_modules', 'clay_oven') == 'false')
        {
        ?>
            <header id="header">
                <ul>
                    <li><a href="#" onclick="openwin1()"  class="content11">截屏</a></li>
                    <li><a href="#" <?=$dada!=null ? ' onclick="openwin3()"   class="content2"  title="停止录屏">停止</a>' : ' onclick="openwin2()"  class="content21">录屏</a>';?></li>
                    <li><a href="<?=URL_APPEDND?>/main/cloud/cloudvmstatus.php?status=suspend&<?=$str2?>"  class="content31" onclick="desktop.siderLin1k()">暂停</a></li>
                    <li><a href="<?=URL_APPEDND?>/main/cloud/cloudvmstatus.php?status=resume&<?=$str2?>" class="content41" onclick="desktop.siderLin2k()">恢复</a></li>
                    <li><a href="<?=URL_APPEDND?>/main/cloud/cloudvmstatus.php?status=stop&<?=$str2?>" class="content51" onclick="desktop.siderLin3k()">关闭</a></li>
                    <li><a href="<?=URL_APPEDND?>/main/cloud/cloudvmstatus.php?status=reset&<?=$str2?>" class="content61" onclick="desktop.siderLin4k()">重启</a></li>
                    <li><a href="javascript:void(0)" onclick="desktop.fullScreen()">全屏</a></li>
                    <li><a href="javascript:void(0)" onclick="desktop.sliderMenu(true)">隐藏</a></li>
                </ul>
            </header>

            <div style="width: 300px;float:right;position:absolute;top:5px;right:10px;">
                <h2><span style="font-size:14px;">实验机销毁倒计时&nbsp;</span><strong id="RemainH">XX</strong>:<strong id="RemainM">XX</strong>:<strong id="RemainS">XX</strong></h2>
            </div>
        <?php
        }
        ?>

        <div class="tabbable tabbable-custom">

            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1_1" data-toggle="tab">虚拟机</a></li>
                <li><?=api_get_setting('enable_modules', 'clay_oven') == 'false' ? '<a href="#tab_1_2" data-toggle="tab">手册</a>' : null;?></li>
                <li><a id="showMenu" href="javascript:void(0)" onclick="desktop.sliderMenu(false)">显示菜单</a> </li>
                <li><div id="noVNC_buttons">
                        <input type=button value="Ctrl+Alt+Del" id="sendCtrlAltDelButton" class="noVNC_status_button">
                                            <span id="noVNC_xvp_buttons">
                                              <input type=button value="Shutdown" id="xvpShutdownButton">
                                              <input type=button value="Reboot" id="xvpRebootButton">
                                              <input type=button value="Reset" id="xvpResetButton">
                                            </span>
                    </div>
                </li>
                <div id="noVNC_status_bar" class="noVNC_status_bar" style="margin-top: 0px;float:right;">
                    <div id="noVNC_status" style="position: inherit; height: auto;">
                        Loading
                    </div>
                </div>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1_1">
                    <div id="noVNC_screen">
                        <canvas id="noVNC_canvas" width="640px" height="20px">
                            Canvas not supported.
                        </canvas>
                    </div>
                </div>

                <div class="tab-pane" id="tab_1_2">
                    <iframe src="<?=$link_url?>" width="100%" height="100%"></iframe>
                </div>
            </div>
        </div>

        <div id="bootStrap">
            <a href="javascript:desktop.showGallery(false);">关闭</a>
            <div class="Style">
                <link id="bs-css" href="include/bootstrap-cerulean.css" rel="stylesheet">
            </div>
            <div class="Script">
                <script src="include/bootstrap-tab.js"></script>
                <script src="include/bootstrap-tooltip.js"></script>
                <script src="include/bootstrap-popover.js"></script>
            </div>
        </div>

        <div id="time_last_ten" style="display: none;">
            <div id='loading-mask' style=" position:fixed;top: 0%;  left: 0%;width:100%;height:100%;z-index:20000;background-color:#000; -moz-opacity: 0.6;  opacity:.60;  filter: alpha(opacity=60);">
            </div>
            <div id="point_out_id">
                <div class="modal-header">
                    <h3>距离实验结束时间还有最后十分钟！</h3>
                </div>
                <div class="modal-footer">
             <span style='float: right;margin-left:10px; '>
                 <div class="butn btn-primary"  onclick="have_ten()">确定</div>
              </span>
                </div>
            </div>
        </div>

        <script>
        /*jslint white: false */
        /*global window, $, Util, RFB, */
        "use strict";

        var num=0;
        var runtimes = 0;
        function GetRTime(){
            var EndTime=<?=(strtotime($s_time)+(intval($total_hours)))*1000?>;
            var nowTime=<?=time()*1000?>;
            var nMS = EndTime -nowTime-(runtimes*1000);
            var nH=Math.floor(nMS/(1000*60*60)) % 24;
            var nM=Math.floor(nMS/(1000*60)) % 60;
            var nS=Math.floor(nMS/1000) % 60;
            document.getElementById("RemainH").innerHTML=nH;
            document.getElementById("RemainM").innerHTML=nM;
            document.getElementById("RemainS").innerHTML=nS;
            if(nMS>10*59*1000&&nMS<=10*60*1000)
            {
                if(num<1){
                    document.getElementById("time_last_ten").style.display="block";
                    num++;
                }
            }else if(nMS <= 0){
                delcloud(<?=$lesson_id?>);
            }
            runtimes++;
            if(nMS>=1000){
                setTimeout("GetRTime()",1000);
            }
        }
        window.onload=GetRTime;

        function delcloud(lession){
            $.ajax({
                type:'GET',
                url:'delcloud.php',
                data:'lession='+lession,
            });
        }

        function have_ten(){
            document.getElementById("time_last_ten").style.display="none";
        }

        function openwin1() {
            var url = location.search;
            url = "<?=URL_APPEDND?>/main/cloud/snapshot_form.php"+url;
            window.open (url, "newwindow", "height=200, width=400, top=100px,left=0,toolbar=no, menubar=no, scrollbars=no, resizable=no,alwaysRaised=yes,dependent=yes,location=no, status=no,directories=no");
        }
        function openwin2() {
            var url = location.search;
            url = "<?=URL_APPEDND?>/main/cloud/rec_form.php"+url;
            window.open (url, "newwindow", "height=200, width=400, top=100px,left=0,toolbar=no, menubar=no, scrollbars=no, resizable=no,alwaysRaised=yes,dependent=yes,location=no, status=no,directories=no");
        }
        function openwin3() {
            var url = location.search;
            url = "<?=URL_APPEDND?>/main/cloud/cloudvmrec.php<?=$str?>"+url;
            window.open (url, "newwindow", "height=200, width=400, top=100px,left=0,toolbar=no, menubar=no, scrollbars=no, resizable=no,alwaysRaised=yes,dependent=yes,location=no, status=no,directories=no");
        }

        // Load supporting scripts
        Util.load_scripts(["webutil.js", "base64.js", "websock.js", "des.js",
                           "keysymdef.js", "keyboard.js", "input.js", "display.js",
                           "inflator.js", "rfb.js", "keysym.js"]);

        var rfb;
        var resizeTimeout;


        function UIresize() {
            if (WebUtil.getConfigVar('resize', false)) {
                var innerW = window.innerWidth;
                var innerH = window.innerHeight;
                var controlbarH = $D('noVNC_status_bar').offsetHeight;
                var padding = 5;
                if (innerW !== undefined && innerH !== undefined)
                    rfb.setDesktopSize(innerW, innerH - controlbarH - padding);
            }
        }
        function FBUComplete(rfb, fbu) {
            UIresize();
            rfb.set_onFBUComplete(function() { });
        }
        function passwordRequired(rfb) {
            var msg;
            msg = '<form onsubmit="return setPassword();"';
            msg += '  style="margin-bottom: 0px">';
            msg += 'Password Required: ';
            msg += '<input type=password size=10 id="password_input" class="noVNC_status">';
            msg += '<\/form>';
            $D('noVNC_status_bar').setAttribute("class", "noVNC_status_warn");
            $D('noVNC_status').innerHTML = msg;
        }
        function setPassword() {
            rfb.sendPassword($D('password_input').value);
            return false;
        }
        function sendCtrlAltDel() {
            rfb.sendCtrlAltDel();
            return false;
        }
        function xvpShutdown() {
            rfb.xvpShutdown();
            return false;
        }
        function xvpReboot() {
            rfb.xvpReboot();
            return false;
        }
        function xvpReset() {
            rfb.xvpReset();
            return false;
        }
        function updateState(rfb, state, oldstate, msg) {
            var s, sb, cad, level;
            s = $D('noVNC_status');
            sb = $D('noVNC_status_bar');
            cad = $D('sendCtrlAltDelButton');
            switch (state) {
                case 'failed':       level = "error";  break;
                case 'fatal':        level = "error";  break;
                case 'normal':       level = "normal"; break;
                case 'disconnected': level = "normal"; break;
                case 'loaded':       level = "normal"; break;
                default:             level = "warn";   break;
            }

            if (state === "normal") {
                cad.disabled = false;
            } else {
                cad.disabled = true;
                xvpInit(0);
            }

            if (typeof(msg) !== 'undefined') {
                sb.setAttribute("class", "noVNC_status_" + level);
                s.innerHTML = msg;
            }
        }

        window.onresize = function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function(){
                UIresize();
            }, 500);
        };

        function xvpInit(ver) {
            var xvpbuttons;
            xvpbuttons = $D('noVNC_xvp_buttons');
            if (ver >= 1) {
                xvpbuttons.style.display = 'inline';
            } else {
                xvpbuttons.style.display = 'none';
            }
        }

        window.onscriptsload = function () {
            var host, port, password, path, token;

            $D('sendCtrlAltDelButton').style.display = "inline";
            $D('sendCtrlAltDelButton').onclick = sendCtrlAltDel;
            $D('xvpShutdownButton').onclick = xvpShutdown;
            $D('xvpRebootButton').onclick = xvpReboot;
            $D('xvpResetButton').onclick = xvpReset;

            WebUtil.init_logging(WebUtil.getConfigVar('logging', 'warn'));
            //document.title = unescape(WebUtil.getConfigVar('title', 'noVNC'));
            // By default, use the host and port of server that served this file
            host = WebUtil.getConfigVar('host', window.location.hostname);
            port = WebUtil.getConfigVar('port', window.location.port);

            // if port == 80 (or 443) then it won't be present and should be
            // set manually
            if (!port) {
                if (window.location.protocol.substring(0,5) == 'https') {
                    port = 443;
                }
                else if (window.location.protocol.substring(0,4) == 'http') {
                    port = 80;
                }
            }

            password = WebUtil.getConfigVar('password', '');
            path = WebUtil.getConfigVar('path', 'websockify');

            // If a token variable is passed in, set the parameter in a cookie.
            // This is used by nova-novncproxy.
            token = WebUtil.getConfigVar('token', null);
            if (token) {

                // if token is already present in the path we should use it
                path = WebUtil.injectParamIfMissing(path, "token", token);

                WebUtil.createCookie('token', token, 1)
            }

            if ((!host) || (!port)) {
                updateState(null, 'fatal', null, 'Must specify host and port in URL');
                return;
            }

            try {
                rfb = new RFB({'target':       $D('noVNC_canvas'),
                               'encrypt':      WebUtil.getConfigVar('encrypt',
                                        (window.location.protocol === "https:")),
                               'repeaterID':   WebUtil.getConfigVar('repeaterID', ''),
                               'true_color':   WebUtil.getConfigVar('true_color', true),
                               'local_cursor': WebUtil.getConfigVar('cursor', true),
                               'shared':       WebUtil.getConfigVar('shared', true),
                               'view_only':    WebUtil.getConfigVar('view_only', false),
                               'onUpdateState':  updateState,
                               'onXvpInit':    xvpInit,
                               'onPasswordRequired':  passwordRequired,
                               'onFBUComplete': FBUComplete});
            } catch (exc) {
                updateState(null, 'fatal', null, 'Unable to create RFB client -- ' + exc);
                return; // don't continue trying to connect
            }

            rfb.connect(host, port, password, path);
        };

        var Desktop = function () {

            var $$ = Desktop.prototype;

            $$.siderLin1k = function () {
                window.location.href = "<?=URL_APPEDND?>/main/cloud/cloudvmstatus.php?status=suspend&<?=$str2?>";
            }
            $$.siderLin2k = function () {
                window.location.href = "<?=URL_APPEDND?>/main/cloud/cloudvmstatus.php?status=resume&<?=$str2?>";
            }
            $$.siderLin3k = function () {
                window.location.href  = "<?=URL_APPEDND?>/main/cloud/cloudvmstatus.php?status=stop&<?=$str2?>";
            }
            $$.siderLin4k = function () {
                window.location.href = "<?=URL_APPEDND?>/main/cloud/cloudvmstatus.php?status=reset&<?=$str2?>";
            }

            $$.fullScreen = function () {
                var el = document.documentElement;
                var rfs = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullScreen;

                if (typeof rfs != "undefined" && rfs) {
                    rfs.call(el);
                } else if (typeof window.ActiveXObject != "undefined") {
                    // for Internet Explorer
                    var wscript = new ActiveXObject("WScript.Shell");
                    if (wscript != null) {
                        wscript.SendKeys("{F11}");
                    }
                }
            }

            $$.sliderMenu = function (v) {
                if (v) {
                    $("#header").slideUp(); $("#showMenu").show();
                    //document.getElementById("header").style.display = "none"; document.getElementById("showMenu").style.display = "block";
                }
                else {
                    $("#header").slideDown(); $("#showMenu").hide();
                    //document.getElementById("header").style.display = "block"; document.getElementById("showMenu").style.display = "none";
                }
            }

            $$.showGallery = function (v) {
                if (v)
                    $("#bootStrap").animate({ height: document.body.offsetHeight - 50 + "px" }, 500);
                else
                    $("#bootStrap").animate({ height: '0' }, 500);
            }

        }
        var desktop = new Desktop();

        </script>

        <style>
            a{   color:black;    font-size:12px;     text-decoration: none;  padding-left:30px;display:block;height:18px;line-height:18px;float:left}
            a:hover{   color:black;  font-size:14px;  text-decoration: none; }
            .content1{  background:url(clound.png) no-repeat 0px -16px; }
            .content2{ background:url(clound.png) no-repeat 0px -33px;}
            .content3{background:url(clound.png) no-repeat 0px -49px; }
            .content4{ background:url(clound.png) no-repeat 0px -69px; }
            .content5{background:url(clound.png) no-repeat 0px -88px;}
            .content6{  background:url(clound.png) no-repeat 0px -107px;}
            .content21{  background:url(clound2.png) no-repeat 0px -34px;}
            .content31{ background:url(clound2.png) no-repeat 0px -51px;}
            .content41{background:url(clound2.png) no-repeat 0px -70px;}
            .content51{background:url(clound2.png) no-repeat 0px -90px;}
            .content61{ background:url(clound2.png) no-repeat 0px -109px;}
            header{background-color: #fff;height: 25px;margin: 0px;font-family: 'Microsoft YaHei';}
            header a{color: #000;text-decoration: none;}
            header a:hover{color: #f00;text-decoration: underline;}
            header ul, header li{margin: 0px;padding: 0px;}
            header ul li{list-style: none;float: left;width: 70px;height: 20px;line-height: 20px;text-align: center;margin: 0px 5px;}
            #showMenu{position: absolute;left: 30%;display: none;}
            #bootStrap{width: 100%;height: 0;position: absolute;left: 0;top: 50px;z-index: 999;background-color: #e6e6e6;overflow: hidden;}
            .tabbable-custom{height:85%;}
            .tab-content,.tab-pane {height:100%;}

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
            }

            .modal-header h3{
                font-family:'宋体';
                font-size:24px;
                font-weight:normal;
                margin-top: 20px;
                margin-bottom: 10px;
            }
            .modal-footer {
                padding: 19px 20px 20px;
                margin-top: 15px;
                text-align: right;
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

    </body>
</html>
