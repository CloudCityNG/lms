<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
include_once './inc/page_header.php';
$user_id = api_get_user_id ();  

$hait_query=mysql_query('select code from view_course_user');
while($hait_row=mysql_fetch_row($hait_query)){
    $hait_rows[]=$hait_row[0];
}
$hait_arr=array_count_values($hait_rows);
$timenow=time();
$timenumb=0;
$star_query=mysql_query('select t1.code as code,t1.title,t1.description9,t2.code as code2,t2.id as id,start_date from course as t1,course_category as t2 where t1.category_code=t2.id and t1.status=0 and t2.status=0 order by start_date desc limit 8');
while($star_row=mysql_fetch_assoc($star_query)){
    if($timenow-strtotime($star_row['start_date']) < 1209600){
        $timenumb++;
    }
    $star_rows[]=$star_row;
}
   
?>
<link href="css/goods.css" type="text/css" rel="stylesheet">
<link href="css/safe-cons.css" type="text/css" rel="stylesheet">
<link href="css/media-style.css" type="text/css" rel="stylesheet">
<link href="css/a.css" type="text/css" rel="stylesheet">
<link href="css/b.css" type="text/css" rel="stylesheet">
<!--<script src="./js/jquery-1.7.2.min.js" type="text/javascript"></script>-->
<script src="./js/jquery.drag.js" type="text/javascript"></script>
<script src="./js/jquery.util.js" type="text/javascript"></script>
<script src="./js/all.js" type="text/javascript"></script>
<!--<script type="text/javascript" src="./js/jquery.fancybox-1.3.4.js"></script>-->
<!--鼠标控制滚动-->
<script type="text/javascript" src="./js/jquery.mousewheel-3.0.4.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox-1.3.4.css" media="screen">
<script type="text/javascript">
    $(function(){
        $(".various1 a").fancybox({
            'width':'70%',
            'height':'80%',
            'autoScale':false,
            'transitionIn':'none',
            'transitionOut':'none',
            'type':'iframe'
        });

        $("a[rel=example_group]").fancybox({
            'transitionIn':'none',
            'transitionOut':'none',
            'titlePosition':'over',
            'titleFormat':function(title, currentArray, currentIndex, currentOpts) {
                return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
            }
        });
    });
    function loopfun(){

        $.ajax({
            url:'loop.php',
            type:'get',
            dataType:'html',
            data:'cid=<?=$cidReq?>',
            success:function(er){
                if(er !== 'err'){
                    $("#id-id").html(er);
                    $(".various1 a").fancybox({
                        'width':'70%',
                        'height':'80%',
                        'autoScale':false,
                        'transitionIn':'none',
                        'transitionOut':'none',
                        'type':'iframe'
                    });

                    $("a[rel=example_group]").fancybox({
                        'transitionIn':'none',
                        'transitionOut':'none',
                        'titlePosition':'over',
                        'titleFormat':function(title, currentArray, currentIndex, currentOpts) {
                            return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
                        }
                    });
                }
            }
        });
    }
    $(function(){
        setInterval("loopfun()",5000);
    });
    function delfun(fname){
        $.get("delname.php",{fname:""+fname+""},
            function(prompt){
                if(prompt == 'ok'){
                    $("#fancybox-wrap").hide();
                    $("#fancybox-overlay").hide();
                }
            },"html");
    }
    window.onload = function(){
        var ovideo=document.getElementById('video-btn');
        var oshut=document.getElementById('video-shut');
        var oshadow=document.getElementById('shadow');
        var oatn=document.getElementById('video-area');
        oshut.onclick = function (e) {
            ovideo.style.display='none';
            oshadow.style.display='none';
            oatn.innerHTML='';
            e.stopPropagation();
        };
    };
</script>
<script>
            function videoShowHide(url){
                //var obtn=document.getElementById('video');
                var ovideo=document.getElementById('video-btn');
                var oatn=document.getElementById('video-area');
                var oshadow=document.getElementById('shadow');
                ovideo.style.display='block';
                oshadow.style.display='block';
                oatn.innerHTML='<video autoplay="" controls="" name="media"><source src="'+url+'" type="video/mp4"></video>';
            }     
        </script>
<style>
    #fancybox-close{position:absolute;top:-15px;right:-15px;width:30px;height:30px;background:transparent url('images/fancybox.png') -40px 0px;cursor:pointer;z-index:1103;display:none;}
    #shadow{width: 100%;height: 100%;position: fixed;top: 70px;left: 0;bottom: 0;right: 0;background: rgba(0,0,0,0.8);opacity: 0.8;filter:alpha(opacity=80);display: none;z-index: 100;}
    .video-btn{position: fixed;width:900px;height:480px;top:80px;left: 50%;margin-left:-450px;display: none;z-index: 101;}
    .video-area{float:left;width:855px;height: 480px;}
    .video-shut{height:30px;line-height: 26px;width:30px;font-size:28px;color:pink;float:left;text-align: center;display: block;border: 1px solid #ADADAD;}
</style>
    <!-- 导航结束 -->
     <div class="clear"></div>
<a name="top"></a>

<section id="c-container">
    <div class="container-video">

        <div class="video-list various1">
            <div class="subject-wrap wrap-left">
                <a class="subj-img-link" href="javascript:;" height="160" onclick="videoShowHide('../../storage/elab/A01.mp4');">
                    <img src="./images/safety-1.jpg">
                    <span class="newSubname">来自竞争者的高级持续性威胁</span>
                </a>
            </div>
            <div class="subject-wrap wrap-left">
                <a class="subj-img-link" href="javascript:;" onclick="videoShowHide('../../storage/elab/A02.mp4');" height="160">
                    <img src="./images/safety-2.jpg">
                    <span class="newSubname">办公室环境安全</span>
                </a>
            </div>
            <div class="subject-wrap wrap-left">
                <a class="subj-img-link" href="javascript:;"onclick="videoShowHide('../../storage/elab/A03-A.mp4');" height="160">
                    <img src="./images/safety-3.jpg">
                    <span class="newSubname">网络钓鱼防范</span>
                </a>
            </div>
            <div class="subject-wrap wrap-left right-0">
                <a class="subj-img-link" href="javascript:;" onclick="videoShowHide('../../storage/elab/A03-E.mp4');" height="160">
                    <img src="./images/safety-4.jpg">
                    <span class="newSubname">邮件安全-点击钓鱼链接</span>
                </a>
            </div>
            <div class="subject-wrap wrap-left">
                <a class="subj-img-link" href="javascript:;" onclick="videoShowHide('../../storage/elab/A04-S.mp4');" height="160">
                    <img src="./images/safety-5.jpg">
                    <span class="newSubname">社交网络安全-旅行安全</span>
                </a>
            </div>
            <div class="subject-wrap wrap-left">
                <a class="subj-img-link" href="javascript:;" onclick="videoShowHide('../../storage/elab/A04-W.mp4')" height="160">
                    <img src="./images/safety-6.jpg">
                    <span class="newSubname">差旅安全之无线网络</span>
                </a>
            </div>
            <div class="subject-wrap wrap-left">
                <a class="subj-img-link" href="javascript:;" onclick="videoShowHide('../../storage/elab/A05.mp4')" height="160">
                    <img src="./images/safety-7.jpg">
                    <span class="newSubname">中间人攻击防范、保护通讯安全</span>
                </a>
            </div>
            <div class="subject-wrap wrap-left right-0">
                <a class="subj-img-link" href="javascript:;" onclick="videoShowHide('../../storage/elab/A06.mp4')" height="160">
                    <img src="./images/safety-8.jpg">
                    <span class="newSubname">移动僵尸网络、移动设备安全使用</span>
                </a>
            </div>

        </div>
    </div>
</section>
<section id="xwdt">
    <div class="main-content-wrap various1">
        <header class="special cb">
            <h1>更多视频</h1>
            <div>
                <img width="100%" height="6" src="./images/xt3.png">
            </div>
        </header>


        <article class="main-content row">
            <h3>办公环境安全</h3>
            <ul id="this_print">
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A01.mp4')">来自竞争者的高级持续性威胁</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A02.mp4')">办公室环境安全</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A15.mp4')">工卡使用</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A17.mp4')">尾随和访客陪护</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C04.mp4')">借用一下你的工卡</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C05.mp4')">遗留在复印机的机密文件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C06.mp4')">清洁桌面</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C11.mp4')">团队内共享密码</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C22.mp4')">逻辑炸弹造成服务器崩溃</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C34.mp4')">违法与公司的责任</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C35.mp4')">信息泄露或网络谣言</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C43.mp4')">雇佣黑客攻击以获利的想法</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C44.mp4')">随意放置笔记本电脑</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C45.mp4')">将公司的软件复制到家里</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C50.mp4')">失窃的笔记本电脑-制止偷窃</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C51.mp4')">让我用一下你的密码</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C53.mp4')">私自启用wifi路由器？</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C55.mp4')">使用安全的密码</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C60.mp4')">可接受的互联网使用规则</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C61.mp4')">公司有权进行网站过滤么</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C62.mp4')">删除全部邮件踪迹的企图</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C63.mp4')">互联网使用法规</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C64.mp4')">一个被非法篡改的网站</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C65.mp4')">访问色情网站的制裁</a></li>

                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D04.mp4')">搜索引擎专利大战与黑客入侵</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D05.mp4')">商业战略计划泄露</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D06.mp4')">临时雇员带来的麻烦</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D18.mp4')">不使用时关闭数据连接</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D19.mp4')">地理位置信息泄露</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D20.mp4')">泄露薪资信息的后果</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D21.mp4')">虚假的防病毒软件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D22.mp4')">浏览器插件安全</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D23.mp4')">远程管理特洛伊木马</a></li>
            </ul>
        </article>


        <article class="main-content row article-2">
            <h3>个人信息安全</h3>
            <ul id="this_print">
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A07.mp4')">个人信息保护</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A12.mp4')">下载软件、标准软件管理</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A13.mp4')">地铁机场的无线安全使用</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A20.mp4')">移动设备在机场失窃</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C02.mp4')">公务旅行等候区的安全注意事项</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C03.mp4')">电脑崩溃，备份的重要性</a></li>

                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C07.mp4')">密码安全——写在标签上的密码</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C16.mp4')">休息室偷窥</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C18.mp4')">客户账户被攻击</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C19.mp4')">电脑维修时保护残余数据</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C20.mp4')">公共场合电话交谈</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C21.mp4')">网络下载-尊重版权</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C25.mp4')">通过电话展现网络信息</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C26.mp4')"> 防范公共场所的窃听</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C32.mp4')"> 创建强口令</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C33.mp4')">在不同的地方用相同的密码</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C36.mp4')">互联网是免费的世界吗</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C37.mp4')">下载最新的电影版本</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C38.mp4')">点对点下载的管理责任</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C39.mp4')">我的手机在哪里</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C40.mp4')">软件安装</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C41.mp4')">电脑桌面主体定制软件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C42.mp4')">来自他人电脑的病毒</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C48.mp4')">pad上安装应用程序</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C69.mp4')">通过个人手机进行网络外联</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C70.mp4')">电话钓鱼企业银行帐户版</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D01.mp4')">个人电脑安全-禁用防火墙</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D02.mp4')">个人电脑安全-禁用杀毒软件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D03.mp4')">个人电脑安全-不安装更新补丁</a></li>

                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D24.mp4')">短地址的安全</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D25.mp4')">勒索软件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D26.mp4')">广告点击僵尸的受害者</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D27.mp4')">网络监管与可接受的使用</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D28.mp4')">数据加密与远程擦除</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D29.mp4')">军事间谍活动</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D30.mp4')">家用路由器dns安全</a></li>

            </ul>
        </article>




        <article class="main-content row society-safe">
            <h3>社交安全</h3>
            <ul id="this_print">
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A04-S.mp4')">社交网络安全-旅行安全</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A04-W.mp4')">差旅安全之无线网络</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A05.mp4')">中间人攻击防范、保护通讯安全</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A08.mp4')">社交网络安全基础</a></li>

                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A09.mp4')">社会工程学-电话诈骗防范</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A10.mp4')">安全事件报告与处理</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A11.mp4')">把握社交媒体信息泄露尺度</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A14.mp4')">电话社会工程学防范</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C09.mp4')">出借智能卡</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C10.mp4')">社会工程学套取用户名和密码</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C17.mp4')">防范社交网络泄密</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C23.mp4')">社会工程学-询问密码</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C24.mp4')">正确地向第三方提供信息</a></li>

                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C46.mp4')">社交网络-新朋友来询问信息</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C47.mp4')">新安装的wifi</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C52.mp4')">社会工程学-假装警察打电话</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C54.mp4')">计算机设备盗窃的讨论</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C56.mp4')">使用娱乐软件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C57.mp4')">物理访问-女士需要帮助</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C58.mp4')">让我用一下你的智能卡</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C59.mp4')">离开时锁定计算机</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D07.mp4')">电话欠费骗局洗钱</a></li>

                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D08.mp4')">移动应用下载</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D09.mp4')">来自在线金融服务的财产损失</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D10.mp4')">移动电话越狱和修复</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D11.mp4')">安全小贴士</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D12.mp4')">博客广告的讨论</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D13.mp4')">美国国家安全局斯诺登网络战争</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D14.mp4')">移动锁设置</a></li>

                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D15.mp4')">移动隐私和密码锁定</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D16.mp4')">电话会议的安全使用</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/D17.mp4')">二维码安全</a></li>

            </ul>
        </article>


        <article class="main-content row society-safe" style="margin-right:0;">
            <h3>邮件安全</h3>
            <ul id="this_print">
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A03-A.mp4')">网络钓鱼防范</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A03-E.mp4')">邮件安全-点击钓鱼链接</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A16.mp4')">黑客入侵邮箱请求更改支付账号</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A18.mp4')">网站被黑后的密码安全</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/A19.mp4')">钓鱼邮件防范</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C01.mp4')">通过电子邮件发送机密信息</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C08.mp4')">邮箱被黑，被用来冒发邮件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C13.mp4')">word中的危险图片文件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C14.mp4')">邮件发送——潜在的风险</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C15.mp4')">含有恶意代码的电子邮件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C27.mp4')">带可疑附件的电子邮件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C28.mp4')">网络钓鱼-旅行前的电子邮件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C29.mp4')">  带危险附件的电子邮件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C30.mp4')"> 带在线广告链接的钓鱼电子邮件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C31.mp4')"> 转发不明连锁邮件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C49.mp4')">用密码压缩敏感文档</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C66.mp4')">带jpeg格式附件的电子邮件</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C67.mp4')">恶意代码攻击</a></li>
                <li> <a href="javascript:;" onclick="videoShowHide('../../storage/elab/C68.mp4')">使用USBWIFI来扩充公司网络</a></li>

            </ul>
        </article>
    </div>

</section>


<div id="fancybox-tmp"></div>
<div id="fancybox-loading"><div></div></div>
<div id="fancybox-overlay"></div>
<div id="fancybox-wrap">
    <div id="fancybox-outer">
        <div class="fancybox-bg" id="fancybox-bg-n"></div>
        <div class="fancybox-bg" id="fancybox-bg-ne"></div>
        <div class="fancybox-bg" id="fancybox-bg-e"></div>
        <div class="fancybox-bg" id="fancybox-bg-se"></div>
        <div class="fancybox-bg" id="fancybox-bg-s"></div>
        <div class="fancybox-bg" id="fancybox-bg-sw"></div>
        <div class="fancybox-bg" id="fancybox-bg-w"></div>
        <div class="fancybox-bg" id="fancybox-bg-nw"></div>
        <div id="fancybox-content"></div>
        <a id="fancybox-close"></a>
        <div id="fancybox-title"></div>
        <a href="javascript:;" id="fancybox-left">
            <span class="fancy-ico" id="fancybox-left-ico"></span>
        </a>
        <a href="javascript:;" id="fancybox-right">
            <span class="fancy-ico" id="fancybox-right-ico"></span>
        </a>
    </div>
</div>
<embed id="xunlei_com_thunder_helper_plugin_d462f475-c18e-46be-bd10-327458d045bd" type="application/thunder_download_plugin" height="0" width="0">
	<!-- 底部 -->
        <div class="video-btn" id="video-btn">
            <div class="video-area" id="video-area"></div><!--视频-->
            <a class="video-shut" id="video-shut">×</a><!--关闭-->
        </div>
        <div id="shadow"></div><!--遮罩层-->
<?php
        include_once './inc/page_footer.php';
?>

 </body>
</html>
