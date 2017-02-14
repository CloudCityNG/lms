$(function(){
    var url=window.location.pathname;
var netarr=url.split('/',2);
var netname=netarr[1];
	//网站头部	
	var $header = $("");
	$("#header").append($header);
/**
* LMS 后台菜单栏目JS start  =========================================================================
**/
	$index = $("<div class='navs'>" +
        "<dl class='nav-list'><dt>我的桌面</dt><dd class='two-nav-list hide'><ul>" +
            "<li><a href='/"+netname+"/user_portal.php' title='我的管理课程'>我的管理课程</a></li>" +
            "<!--li><a href='/"+netname+"/main/exam/exam_corrected_list.php' title='考卷批改'>考卷批改</a></li--></ul></dd></dl>" +
        "<dl class='nav-list'><dt>课程管理</dt><dd class='two-nav-list hide'><ul>" +
            "<li><a href='/"+netname+"/main/admin/course/course_list.php' title='课程管理'>课程管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/course/course_plan.php' title='课程调度'>课程调度</a ></li>" +
            "<li><a href='/"+netname+"/main/admin/course/setup.php' title='课程体系分类'>课程体系</a ></li>" +
            "<li><a href='/"+netname+"/main/admin/course/course_category_iframe.php' title='课程分类管理'>课程分类</a></li> " +
//            "<li><a href='/"+netname+"/main/admin/import_export/imex_list.php' title='课件资源库管理'>课件资源库</a></li>" +
            "<li><a href='/"+netname+"/main/admin/syllabus/syllabus_list.php' title='课程表管理'>课程表管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/course/course_user_manage.php' title='课程调度查看'>调度查看</a></li>" +
//            "<li><a href='/"+netname+"/main/reporting/learning_progress.php' title='学习情况查询'>学习情况</a></li>" +
            "<li><a href='/"+netname+"/main/survey/index.php' title='调查问卷'>调查问卷</a></li>" +

            "</ul></dd></dl>" +
        "<dl class='nav-list'><dt>实验报告管理</dt><dd class='two-nav-list hide'><ul>" +

            "<li><a href='/"+netname+"/main/admin/course/reports.php' title='实验报告管理'>实验报告</a ></li>"+

            "</ul></dd></dl>"+
        "<dl class='nav-list'><dt>云平台管理</dt><dd class='two-nav-list hide'><ul>" +

            "<li><a href='/"+netname+"/main/admin/vmmanage/vmmanage_iframe.php' title='虚拟化管理'>虚拟化管理</a></li> " +
            "<li><a href='/"+netname+"/main/admin/net/vm_list_iframe.php' title='网络拓扑设计'>网络拓扑设计</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmmanage/centralized.php' title='集中管理设置'>集中管理设置</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmdisk/vmdisk_list.php' title='虚拟模板管理'>虚拟模板管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmmanage/unified_shut.php' title='虚拟化统一关机'>虚拟化统一关机</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmmanage/ip_info.php' title='IP地址信息'>IP地址信息</a></li>" +
            "<li><a href='/"+netname+"/main/admin//cloud/cloud_plan.php' title='弹性云计算'>弹性云计算</a></li>" +


        "</ul></dd></dl>" +
        "<dl class='nav-list'><dt>云桌面管理</dt> <dd class='two-nav-list hide'><ul>" +

            "<li><a href='/"+netname+"/main/admin/cloud/clouddesktop.php' title='云桌面终端'>云桌面终端</a></li>" +
            "<li><a href='/"+netname+"/main/admin/cloud/clouddesktopdisk.php' title='云桌面存储空间'>云桌面存储空间</a></li>" +
            "<li><a href='/"+netname+"/main/admin/cloud/clouddesktopscan.php' title='云桌面扫描'>云桌面扫描</a></li></ul></dd></dl>" +

        "<dl class='nav-list'><dt>路由交换管理</dt><dd class='two-nav-list hide'><ul>" +

//            "<li><a href='/"+netname+"/main/admin/router/router_type.php' title='路由交换类型管理'>路由交换类型管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/router/labs_ios.php' title='路由交换ROM设置'>路由交换ROM设置</a></li>" +
//            "<li><a href='/"+netname+"/main/admin/router/labs_mod.php' title='路由交换模块管理'>路由交换模块管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/router/labs_category_iframe.php' title='路由交换课程分类'>路由交换课程分类</a></li>" +
            "<li><a href='/"+netname+"/main/admin/router/labs_topo.php' title='路由交换课程'>路由交换课程</a></li> " +
            "<li><a href='/"+netname+"/main/admin/router/labs_device.php' title='网络设备管理'>网络设备管理</a></li>" +
            "</ul></dd></dl>"+

        "<dl class='nav-list'><dt>用户管理</dt><dd class='two-nav-list hide'><ul>" +

            "<li><a href='/"+netname+"/main/admin/user/user_online.php'>在线用户</a></li>" +
            "<li><a href='/"+netname+"/main/admin/dept/dept_iframe.php' title='组织管理'>组织管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/user/user_list_audit.php' title='审核注册用户'>审核用户</a></li>" +
            "<li><a href='/"+netname+"/main/admin/user/user_list.php' title='用户管理'>用户管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/user/work_attendance.php' title='用户考勤'>用户考勤</a></li></ul></dd></dl>" +

        " <dl class='nav-list'><dt>系统管理</dt><dd class='two-nav-list hide'><ul>" +

            "<li><a href='/"+netname+"/main/admin/misc/settings.php' title='系统设置'>系统设置</a></li>" +
            "<li><a href='/"+netname+"/main/admin/log/logging_list.php' title='系统日志'>系统日志</a></li>" +
            "<li><a href='/"+netname+"/main/admin/misc/system_announcements.php' title='系统公告'>系统公告</a></li>" +
            "<li><a href='/"+netname+"/main/admin/misc/system_upgrade.php' title='系统升级'>系统升级</a></li> " +
            "<li><a href='/"+netname+"/main/admin/misc/system_management.php' title='系统管理'>系统管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/systeminfo.php' title='系统信息'>系统信息</a></li></ul></dd></dl>" +

        " <dl class='nav-list'><dt>license管理</dt><dd class='two-nav-list hide'><ul>" +

            "<li><a href='/"+netname+"/main/admin/course_license.php' title='课件license管理'>课件license管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/import_export/imex_list.php' title='课件资源库管理'>课件资源库</a></li>" +

        "</ul></dd></dl>"+
        " <dl class='nav-list'><dt>信息传递</dt><dd class='two-nav-list hide'><ul>" +

            "<li><a href='/"+netname+"/main/admin/message_list.php' title='信息传递'>信息传递</a></li>" +

        "</ul></dd></dl>"+
        "</div>");
	$(".index").append($index);
/**CTF管理**/
$ctf=$("<div class='navs'><dl class='nav-list'><dt>CTF管理</dt><dd class='two-nav-list'><ul>" +
         "<li><a href='/"+netname+"/main/ctf/sai/sai_list.php' title='赛事管理'>赛事管理</a></li>" +
         "<li><a href='/"+netname+"/main/ctf/team/team_list.php' title='战队管理'>战队管理</a></li>"+
         "<li><a href='/"+netname+"/main/ctf/exam_class.php' title='题库分类管理'>题库分类管理</a></li>" +
         "<li><a href='/"+netname+"/main/ctf/exam_list_1.php' title='题库管理'>题库管理</a></li>" +
         "<li><a href='/"+netname+"/main/ctf/exam_question.php' title='赛题管理'>赛题管理</a></li>" +
         "<li><a href='/"+netname+"/main/ctf/faq/faq_list.php' title='faq管理'>faq管理</a></li>" +
         "<li><a href='/"+netname+"/main/cn/contest_list.php' title='组织管理'>组织管理</a></li>" +
         "<li><a href='/"+netname+"/main/cn/organizations_list.php' title='大赛管理'>大赛管理</a></li>" +
         "<li><a href='/"+netname+"/main/cn/massage_list.php' title='规则管理'>规则管理</a></li>" +
         "<li><a href='/"+netname+"/main/ctf/match/match_list.php' title='成绩管理'>成绩管理</a></li>" +
         "</ul></dd></dl>" +
          "</div>");
        $(".ctfinex").append($ctf);          
/**我的桌面**/
	$mydesktop = $("<div class='navs'><dl class='nav-list'><dt>我管理课程</dt><dd class='two-nav-list'><ul>" +

          "<li><a href='/"+netname+"/user_portal.php' title='我的管理课程'>我的管理课程</a></li></ul></dd></dl>" +

//      "<dl class='nav-list'><dt>考卷批改</dt><dd class='two-nav-list'><ul>" +

//          "<li><a href='/"+netname+"/main/exam/exam_corrected_list.php' title='考卷批改'>考卷批改</a></li></ul></dd></dl>"+

          "</div>");
	$(".mydesktop").append($mydesktop);
/**云平台**/
	$cloud =$("<div class='navs'><dl class='nav-list'><dt>云平台管理</dt><dd class='two-nav-list'><ul>" +
          "<li><a href='/"+netname+"/main/admin/vmmanage/vmmanage_iframe.php' title='虚拟化管理'>虚拟化管理</a></li>" +
          "<li><a href='/"+netname+"/main/admin/net/vm_list_iframe.php' title='网络拓扑设计'>网络拓扑设计</a></li>" +
          "<li><a href='/"+netname+"/main/admin/vmmanage/centralized.php' title='集中管理设置'>集中管理设置</a></li>" +
          "<li><a href='/"+netname+"/main/admin/vmdisk/vmdisk_list.php' title='虚拟模板管理'>虚拟模板管理</a></li>" +
          "<li><a href='/"+netname+"/main/admin/vmmanage/unified_shut.php' title='虚拟化统一关机'>虚拟化统一关机</a></li>" +
          "<li><a href='/"+netname+"/main/admin/vmmanage/ip_info.php' title='IP地址信息'>IP地址信息</a></li>" +
          "<li><a href='/"+netname+"/main/admin/cloud/cloud_plan.php' title='弹性云计算'>弹性云计算</a></li>" +
//          "<li><a href='/"+netname+"/main/admin/vmmanage/vmdisk_log_list.php' title='虚拟机日志'>虚拟机日志</a></li>" +
//          "<li><a href='/"+netname+"/main/admin/vmmanage/vm_log_statistics.php' title='虚拟机日志统计'>虚拟机日志统计</a></li>" +
        "</ul></dd></dl>" +
        "<dl class='nav-list'><dt>云桌面管理</dt> <dd class='two-nav-list'><ul>" +

          "<li><a href='/"+netname+"/main/admin/cloud/clouddesktop.php' title='云桌面终端'>云桌面终端</a></li>" +
          "<li><a href='/"+netname+"/main/admin/cloud/clouddesktopdisk.php' title='云桌面存储空间'>云桌面存储空间</a></li>" +
          "<li><a href='/"+netname+"/main/admin/cloud/clouddesktopscan.php' title='云桌面扫描'>云桌面扫描</a></li></ul></dd></dl> </div>");

	$(".cloud").append($cloud);
/**系统管理 **/
	$systeminfo = $("<div class='navs'><dl class='nav-list'><dt>系统管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/admin/misc/settings.php' title='系统设置'>系统设置</a></li>" +
//        "<li><a href='/"+netname+"/main/admin/log/logging_list.php' title='系统日志'>系统日志</a></li>" +
        "<li><a href='/"+netname+"/main/admin/misc/system_announcements.php' title='系统公告'>系统公告</a></li>" +
        "<li><a href='/"+netname+"/main/admin/misc/system_upgrade.php' title='系统升级'>系统升级</a></li>" +
        "<li><a href='/"+netname+"/main/admin/systeminfo.php' title='系统信息'>系统信息</a></li>" +
        "<li><a href='/"+netname+"/main/admin/misc/system_management.php' title='系统管理'>系统管理</a></li>" +
//        "<li><a href='/"+netname+"/main/admin/misc/summary.php' title='大赛简介'>大赛简介</a></li>" +
//        "<li><a href='/"+netname+"/main/admin/misc/tools.php' title='导调工具'>导调工具</a></li>" +

        "</ul></dd></dl>"+
    "<dl class='nav-list'><dt>license管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/admin/course_license.php' title='课件license管理'>课件license管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/import_export/imex_list.php' title='课件资源库管理'>课件资源库管理</a></li>" +
        "</ul></dd></dl>"+ 
        "<dl class='nav-list'><dt>日志管理</dt><dd class='two-nav-list'><ul>" + 
            "<li><a href='/"+netname+"/main/admin/log/logging_list.php' title='系统日志'>系统日志</a></li>" +
             "<li><a href='/"+netname+"/main/admin/log/access_login_statistics.php' title='登录访问日志统计'>登录访问日志统计</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmmanage/vmdisk_log_list.php' title='虚拟机日志'>虚拟机日志</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmmanage/vm_log_statistics.php' title='虚拟机日志统计'>虚拟机日志统计</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmmanage/vm_topten_statistics.php' title='课程学习使用TOP10'>课程学习使用TOP10</a></li>" +
            "<li><a href='/"+netname+"/main/reporting/learning_progress.php' title='学习情况查询'>学习情况</a></li>" +
            "<li><a href='/"+netname+"/main/admin/log/ranking.php' title='学霸排名日志'>学霸排名日志</a></li>" +
            "<li><a href='/"+netname+"/main/admin/statistics/student_statistic.php' title='学生学习统计信息'>学生学习统计信息</a></li>" +
            "<li><a href='/"+netname+"/main/admin/statistics/user_number.php' title='用户活跃度统计'>用户活跃度统计</a></li>" +
            "<li><a href='/"+netname+"/main/admin/statistics/equipment_status.php' title='设备运行状态统计'>设备运行状态统计</a></li>" +
        "</ul></dd></dl></div>");
	$(".systeminfo").append($systeminfo);
/**用户管理**/
	$users = $("<div class='navs'><dl class='nav-list'><dt>用户管理</dt><dd class='two-nav-list'>" +
        "<ul><li><a href='/"+netname+"/main/admin/user/user_online.php'>在线用户</a></li>" +
        "<li><a href='/"+netname+"/main/admin/dept/dept_iframe.php' title='组织管理'>组织管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/user/user_list_audit.php' title='审核注册用户'>审核用户</a></li>" +
        "<li><a href='/"+netname+"/main/admin/user/user_list.php' title='用户管理'>用户管理</a></li>"+
        "<li><a href='/"+netname+"/main/admin/user/user_blacklist.php' title='用户黑名单'>用户黑名单</a></li>"+
        "<li><a href='/"+netname+"/main/admin/user/work_attendance.php' title='用户考勤'>用户考勤</a></li></ul></dd></dl></div>");
	$(".users").append($users);
///**考试管理**/
	$exercice = $("<div class='navs'><dl class='nav-list'><dt>考试管理</dt><dd class='two-nav-list'><ul>" +

            "<li><a href='/"+netname+"/main/exam/pool_iframe.php' title='题库管理'>题库管理</a></li>" +
            "<li><a href='/"+netname+"/main/exercice/question_base.php' title='所有考题管理'>所有考题管理</a></li>" +
            "<li><a href='/"+netname+"/main/exercice/exercice.php?type=1' title='综合考试管理'>综合考试管理</a></li>" +
            "<li><a href='/"+netname+"/main/exercice/exercice.php?type=2' title='课程考试管理'>课程考试管理</a></li>" +
            "<li><a href='/"+netname+"/main/exercice/exercice.php?type=3' title='自我测试管理'>自我测试管理</a></li>" +
//            "<li><a href='/"+netname+"/main/survey/index.php' title='调查问卷'>调查问卷</a></li>" +
            "<li><a href='/"+netname+"/main/reporting/query_quiz.php' title='考试成绩查询'>考试成绩查询</a></li></ul></dd></dl>" +

       		 "<dl class='nav-list'><dt>实验报告管理</dt><dd class='two-nav-list'><ul>" +

            "<li><a href='/"+netname+"/main/admin/course/report.php' title='实验报告管理'>实验报告管理</a></li></ul></dd></dl></div>");

	$(".exercice").append($exercice);

    /**路由交换管理**/
$router = $("<div class='navs'><dl class='nav-list'><dt>路由交换管理</dt><dd class='two-nav-list'><ul>" +
//        "<li><a href='/"+netname+"/main/admin/router/router_type.php' title='路由交换类型'>路由交换类型</a></li>" +
        "<li><a href='/"+netname+"/main/admin/router/labs_ios.php' title='路由交换ROM设置'>路由交换ROM设置</a></li>" +
//        "<li><a href='/"+netname+"/main/admin/router/labs_mod.php' title='路由模块管理'>路由模块管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/router/labs_device.php' title='网络设备管理'>网络设备管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/router/labs_category_iframe.php' title='路由交换课程分类'>路由交换课程分类</a></li>" +
        "<li><a href='/"+netname+"/main/admin/router/labs_topo.php' title='路由交换课程'>路由交换课程</a></li> " +
        "</ul></dd></dl>");
    $(".router").append($router);
/**课程管理**/
	$course = $("<div class='navs'><dl class='nav-list'><dt>课程管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/admin/course/course_plan.php' title='课程调度'>课程调度</a ></li>" +
        "<li><a href='/"+netname+"/main/admin/course/course_list.php' title='课程管理'>课程管理</a></li>" +
          "<li><a href='/"+netname+"/main/admin/course/setup.php' title='课程体系分类'>课程体系</a ></li>" +
        "<li><a href='/"+netname+"/main/admin/course/course_category_iframe.php' title='课程分类管理'>课程分类</a></li>" +
//        "<li><a href='/"+netname+"/main/admin/import_export/imex_list.php' title='课件资源库管理'>课件资源库</a></li>" +
        "<li><a href='/"+netname+"/main/admin/syllabus/syllabus_list.php' title='课程表管理'>课程表管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/course/course_user_manage.php' title='课程调度查看'>调度查看</a></li>" +
//        "<li><a href='/"+netname+"/main/reporting/learning_progress.php' title='学习情况查询'>学习情况</a></li>" +
        "<li><a href='/"+netname+"/main/survey/index.php' title='调查问卷'>调查问卷</a></li>" +
        "<li><a href='/"+netname+"/main/admin/course/comment_manage.php' title='课程评论管理'>课程评论管理</a></li>" +
        "</ul></dd></dl>"+
        "<dl class='nav-list'><dt>路由交换管理</dt><dd class='two-nav-list'><ul>" +
          "<li><a href='/"+netname+"/main/admin/router/labs_topo.php' title='路由交换课程'>路由交换课程</a></li> " +
          "<li><a href='/"+netname+"/main/admin/router/labs_category_iframe.php' title='路由交换课程分类'>路由交换课程分类</a></li>" +
//        "<li><a href='/"+netname+"/main/admin/router/router_type.php' title='路由交换类型'>路由交换类型</a></li>" +
        "<li><a href='/"+netname+"/main/admin/router/labs_ios.php' title='路由交换镜像管理'>路由交换镜像管理</a></li>" +
//        "<li><a href='/"+netname+"/main/admin/router/labs_mod.php' title='路由模块管理'>路由模块管理</a></li>" +
//        "<li><a href='/"+netname+"/main/admin/router/labs_device.php' title='网络设备管理'>网络设备管理</a></li>" +
     
      
        "</ul></dd></dl>"+ 
          "<dl class='nav-list'><dt>安全评估</dt> <dd class='two-nav-list'><ul>" +
            "<li><a href='/"+netname+"/main/evaluate/project.php' title='安全评估'>安全评估</a></li>" +
        "</ul></dd></dl></div>");
	$(".course").append($course);
/**考试实验报告管理**/
    $report = $("<div class='navs'><dl class='nav-list'><dt>实验报告管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/admin/course/report.php' title='实验报告管理'>实验报告管理</a ></li></ul></dd></dl></div>");

    $(".report").append($report);
    
/**课程实验报告管理**/
    $reports = $("<div class='navs'><dl class='nav-list'><dt>实验报告管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/admin/course/reports.php' title='实验报告管理'>实验报告管理</a ></li></ul></dd></dl></div>");

    $(".reports").append($reports);
/**
 * LMS 后台菜单栏目JS end =========================================================================
 **/

/**消息传递**/
    $cloud1 =$("<div class='navs'>"+
       " <dl class='nav-list'><dt>信息传递</dt><dd class='two-nav-list'><ul>" +
        "<li><a href='/"+netname+"/main/admin/message_list.php' title='信息传递'>信息传递</a></li>" +
        "</ul></dd></dl>" +" </div>");
        $(".cloud1").append($cloud1);
/**
*MONITOR 后台菜单栏目JS start =========================================================================
**/
/**monitor首页**/
$sidebars = $("<div class='navs'>" +
    "<dl class='nav-list'>" +
      "<dt>单兵作战</dt><dd class='two-nav-list hide'><ul> " +
        "<li><a href='/"+netname+"/main/exam/pool_iframe.php' title='题库管理'>题库管理</a></li>" +
        "<li><a href='/"+netname+"/main/exercice/question_base.php' title='考题管理'>考题管理</a></li>" +
        "<li><a href='/"+netname+"/main/exam/exam_list.php' title='考试管理'>考试管理</a></li>" +
        "<li><a href='/"+netname+"/main/exercice/exercices.php' title='所有考卷'>所有考卷</a></li>" +
        "<li><a href='/"+netname+"/main/exam/exam_corrected_list.php' title='考卷批改'>考卷批改</a></li>"+
        "<li><a href='/"+netname+"/main/reporting/query_quiz.php' title='考试成绩查询'>考试成绩查询</a></li>"+
        "<li><a href='/"+netname+"/main/reporting/quiz_user.php' title='考试汇总'>考试汇总</a></li></ul></dd>" +


    "<dl class='nav-list'><dt>夺旗管理</dt><dd class='two-nav-list hide'><ul>" +

        "<li><a href='/"+netname+"/main/admin/misc/flag.php' title='旗子位置'>旗子位置</a></li></ul></dd></dl>"+

    "<dl class='nav-list'><dt>分组对抗管理</dt><dd class='two-nav-list hide'><ul>" +
        "<li><a href='/"+netname+"/main/admin/control/control_user_group.php' title='用户分组'>用户分组</a></li>" +
        "<li><a href='/"+netname+"/main/admin/control/renwu.php' title='分组任务下发'>分组任务下发</a></li>" +
        "<li><a href='/"+netname+"/main/admin/control/control_list.php' title='分组对抗模板分配'>分组对抗模板分配</a></li>" +
        "<li><a href='/"+netname+"/main/admin/control/counterwork_info.php' title='分组对抗信息'>分组对抗信息</a></li>" +
        "<li><a href='/"+netname+"/main/reporting/counterwork_report.php' title='分组对抗报告'>分组对抗报告</a></li>" +

    "</ul></dd></dl>" +
    "<dl class='nav-list'><dt>用户管理</dt><dd class='two-nav-list hide'><ul>" +

        "<li><a href='/"+netname+"/main/admin/dept/dept_iframe.php' title='组织管理'>组织管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/user/user_list_audit.php' title='审核注册用户'>审核用户</a></li>" +
        "<li><a href='/"+netname+"/main/admin/user/user_list.php' title='用户管理'>用户管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/user/user_online.php' title='在线用户'>在线用户</a></li>"+

    "</ul></dd></dl>" +
    "<dl class='nav-list'><dt>安全评估</dt> <dd class='two-nav-list hide'><ul>" +

        "<li><a href='/"+netname+"/main/evaluate/project.php' title='安全评估'>安全评估</a></li></ul></dd></dl>" +

    "<dl class='nav-list'><dt>云管理</dt><dd class='two-nav-list hide'><ul>" +

        "<li><a href='/"+netname+"/main/admin/vmmanage/vmmanage_iframe.php' title='虚拟化管理'>虚拟化管理</a></li> " +
        "<li><a href='/"+netname+"/main/admin/net/vm_list_iframe.php' title='网络拓扑设计'>网络拓扑设计</a></li>" +
        "<li><a href='/"+netname+"/main/admin/vmmanage/centralized.php' title='集中管理设置'>集中管理设置</a></li>" +
        "<li><a href='/"+netname+"/main/admin/vmdisk/vmdisk_list.php' title='虚拟模板管理'>虚拟模板管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/token_bucket/token_bucket_list.php' title='令牌桶管理'>令牌桶管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/vmmanage/unified_shut.php' title='虚拟化统一关机'>虚拟化统一关机</a></li>" +
        "<li><a href='/"+netname+"/main/admin/vmmanage/ip_info.php' title='IP地址信息'>IP地址信息</a></li>" +

        "</ul></dd></dl>" +
    "<dl class='nav-list'><dt>云桌面管理</dt> <dd class='two-nav-list hide'><ul>" +

        "<li><a href='/"+netname+"/main/admin/cloud/clouddesktop.php' title='云桌面终端'>云桌面终端</a></li>" +
        "<li><a href='/"+netname+"/main/admin/cloud/clouddesktopdisk.php' title='云桌面存储空间'>云桌面存储空间</a></li>" +
        "<li><a href='/"+netname+"/main/admin/cloud/clouddesktopscan.php' title='云桌面扫描'>云桌面扫描</a></li>" +

        "</ul></dd></dl>" +
    "<dl class='nav-list'><dt>系统管理</dt><dd class='two-nav-list hide'><ul>"+

        "<li><a href='/"+netname+"/main/admin/misc/system_announcements.php' title='系统公告'>系统公告</a></li>" +
        "<li><a href='/"+netname+"/main/admin/misc/settings.php' title='系统设置'>系统设置</a></li>" +

        "</ul></dd></dl>"+
    "</div>");
    $(".sidebars").append($sidebars);

/**MONITOR调度管理**/
    $control =$("<div class='navs'>" +
        "<dl class='nav-list'><dt>分组对抗管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/admin/control/control_user_group.php' title='用户分组'>用户分组</a></li>" +
        "<li><a href='/"+netname+"/main/admin/control/renwu.php' title='分组任务下发'>分组任务下发</a></li>" +
        "<li><a href='/"+netname+"/main/admin/control/control_list.php' title='分组对抗模板分配'>分组对抗模板分配</a></li>" +
        "<li><a href='/"+netname+"/main/admin/control/counterwork_info.php' title='分组对抗信息'>分组对抗信息</a></li>" +
        "<li><a href='/"+netname+"/main/reporting/counterwork_report.php' title='分组对抗报告'>分组对抗报告</a></li>" +

        "</ul></dd></dl></div>");
    $(".control").append($control);

/**MONITOR态势展示**/
    $trend =$("<div class='navs'>" +
        "<dl class='nav-list'><dt>态势地图</dt><dd class='two-nav-list'><ul>" +

            "<li><a href='/"+netname+"/main/admin/trend/trend.php' title='态势展示一'>态势展示一</a></li>" +
            "<li><a href='/"+netname+"/main/admin/trend/trend2.php' title='态势展示二'>态势展示二</a></li>" +
        "</ul></dd></dl>"+
        "</div>");
    $(".trend").append($trend);


/**MONITOR用户管理**/
    $user = $("<div class='navs'>" +
        "<dl class='nav-list'><dt>用户管理</dt><dd class='two-nav-list'><ul> " +

            "<li><a href='/"+netname+"/main/admin/dept/dept_iframe.php' title='组织管理'>组织管理</a></li>" +
            "<li><a href='/"+netname+"/main/admin/user/user_list_audit.php' title='审核注册用户'>审核用户</a></li>" +
            "<li><a href='/"+netname+"/main/admin/user/user_list.php' title='用户管理'>用户管理</a></li>"+
            "<li><a href='/"+netname+"/main/admin/user/user_online.php' title='在线用户'>在线用户</a></li>"+

        "</ul></dd></dl></div>");

    $(".userlist").append($user);
/**MONITOR考试管理**/
    $exam= $("<div class='navs'>" +
        "<dl class='nav-list'><dt>考试管理</dt><dd class='two-nav-list'><ul> " +

            "<li><a href='/"+netname+"/main/exam/pool_iframe.php' title='题库管理'>题库管理</a></li>" +
            "<li><a href='/"+netname+"/main/exercice/question_base.php' title='考题管理'>考题管理</a></li>" +
            "<li><a href='/"+netname+"/main/exam/exam_list.php' title='考试管理'>考试管理</a></li>" +
            "<li><a href='/"+netname+"/main/exercice/exercices.php' title='所有考卷'>所有考卷</a></li>" +
            "<li><a href='/"+netname+"/main/exam/exam_corrected_list.php' title='考卷批改'>考卷批改</a></li>"+
            "<li><a href='/"+netname+"/main/reporting/query_quiz.php' title='考试成绩查询'>考试成绩查询</a></li>"+
            "<li><a href='/"+netname+"/main/reporting/quiz_user.php' title='考试汇总'>考试汇总</a></li>"+
            "<li><a href='/"+netname+"/main/admin/vmmanage/exam_vm.php' title='虚拟机开启情况'>虚拟机开启情况</a></li>"+
            
        "<li><a href='/"+netname+"/main/admin/course/report.php' title='实验报告管理'>实验报告管理</a ></li> "+

        "</ul></dd></dl>"+
        "</div>");
    $(".exercices").append($exam);
/**MONITOR报告管理**/
    $reporting= $("<div class='navs'>" +
        "<dl class='nav-list'><dt>报告管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/reporting/report.php' title='实验报告'>实验报告</a></li>" +

        "</ul></dd></dl>"+
        "</div>");
    $(".reporting").append($reporting);
/* MONITOR系统管理*/
    $system =$("<div class='navs'>" +
        "<dl class='nav-list'><dt>系统管理</dt><dd class='two-nav-list'><ul>" +

            "<li><a href='/"+netname+"/main/admin/misc/system_announcements.php' title='系统公告'>系统公告</a></li>" +
            "<li><a href='/"+netname+"/main/admin/misc/settings.php' title='系统设置'>系统设置</a></li>" +
            "<li><a href='/"+netname+"/main/admin/log/logging_list.php' title='系统日志'>系统日志</a></li>" +

        "</ul></dd></dl></div>");
    $(".system").append($system);
 /*日志管理*/
     $logs_manage =$("<div class='navs'>" +
        "<dl class='nav-list'><dt>日志管理</dt><dd class='two-nav-list'><ul>" + 
            "<li><a href='/"+netname+"/main/admin/log/logging_list.php' title='系统日志'>系统日志</a></li>" +
             "<li><a href='/"+netname+"/main/admin/log/access_login_statistics.php' title='登录访问日志统计'>登录访问日志统计</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmmanage/vmdisk_log_list.php' title='虚拟机日志'>虚拟机日志</a></li>" +
            "<li><a href='/"+netname+"/main/admin/vmmanage/vm_log_statistics.php' title='虚拟机日志统计'>虚拟机日志统计</a></li>" +
            "<li><a href='/"+netname+"/main/reporting/learning_progress.php' title='学习情况查询'>学习情况</a></li>" +
             "<li><a href='/"+netname+"/main/admin/log/ranking.php' title='学霸排名日志'>学霸排名日志</a></li>" +
        "</ul></dd></dl></div>");
    $(".logs_manage").append($logs_manage);
/* MONITOR安全评估*/
    $evaluate =$("<div class='navs'>" +
        "<dl class='nav-list'><dt>安全评估</dt> <dd class='two-nav-list'><ul>" +

            "<li><a href='/"+netname+"/main/evaluate/project.php' title='安全评估'>安全评估</a></li>" +

        "</ul></dd></dl></div>");
    $(".evaluate").append($evaluate);
/** monitor夺旗入口 **/
    var $flag =$("<div class='navs'>" +
        "<dl class='nav-list'><dt>夺旗管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/admin/misc/flag.php' title='夺旗竞赛管理'>夺旗竞赛管理</a></li>" +
         "<li><a href='/"+netname+"/main/reporting/flag_report.php' title='夺旗报告'>夺旗报告</a></li>" +

        "</ul></dd>"+
        "</dl></div>");
    $(".flag").append($flag);
    /**职业技能管理**/
    $skill= $("<div class='navs'>" +
        "<dl class='nav-list'><dt>技能管理</dt><dd class='two-nav-list'><ul>" +

        "<li><a href='/"+netname+"/main/admin/skill/occupation_manage.php' title='职业技能管理'>职业技能管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/skill/step_manage.php' title='技能阶段管理'>技能阶段管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/skill/skill_course_exam.php' title='技能课程自测管理'>技能课程自测管理</a></li>" +
        "<li><a href='/"+netname+"/main/admin/skill/skill_exam_correct.php' title='技能综合测试批改'>技能综合测试批改</a></li>" +
        "</ul></dd></dl>"+
        "</div>");
    $(".skill").append($skill);
/**
*MONITOR 后台菜单栏目JS end =========================================================================
**/
})
//  Andy Langton's show/hide/mini-accordion @ http://andylangton.co.uk/jquery-show-hide

// this tells jquery to run the function below once the DOM is ready
$(document).ready(function() {

// choose text for the show/hide link - can contain HTML (e.g. an image)
var showText='展开';
var hideText='关闭';

// initialise the visibility check
var is_visible = false;

// append show/hide links to the element directly preceding the element with a class of "toggle"
$('.toggle').prev().append(' <a href="#" class="toggleLink">'+hideText+'</a>');

// hide all of the elements with a class of 'toggle'
$('.toggle').show();

// capture clicks on the toggle links
$('a.toggleLink').click(function() {

// switch visibility
is_visible = !is_visible;

// change the link text depending on whether the element is shown or hidden
if ($(this).text()==showText) {
$(this).text(hideText);
$(this).parent().next('.toggle').slideDown('slow');
}
else {
$(this).text(showText);
$(this).parent().next('.toggle').slideUp('slow');
}
// return false so any link destination is not followed
return false;

});
});

/*
 * 后台主页选项卡切换
 * Author:Yuhao
 * Date: 2012/12/25
 */
$(function(){
	$("#flexButton").click(function(){
		if($("#sidebar").hasClass("open"))
		{
			 $("#sidebar").animate({width:"1%"}).removeClass("open").attr("class","column close");
			 $(".toggle").hide();
			 $(".navs").hide();
			 $("#main").animate({width:"99%"});
			 $(this).attr("class","closeButton open");
		}else{
             $("#sidebar").animate({width:"15%"}).attr("class","column open");
			 $(".toggle").show();
			 $(".navs").show();
			 $("#main").animate({width:"85%"});
			 $(this).attr("class","closeButton close");
        }
	})
	$("#systeminfo tr:even").css("background","#FFC");
	$("#sidebar").css("height","100%");
	$(".labtable tr:even").css("background","#DEE4E5");
	$(".labtable tr:odd").css("background","#EDF2F5");
	var $div_li = $(".manage-tab li");
	$div_li.click(function(){
		$(this).addClass("selected").siblings().removeClass("selected");
		var index = $div_li.index(this);
		$(".manage-tab-content>div").eq(index).show().siblings().hide();
	}).hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");	
	})
	//lab实验选项卡
	var $nav_dt = $(".nav-list dt");
	$nav_dt.click(function(){
		var $url = $(this).siblings("dd");
		if($url.is(":visible"))
		{
			$url.hide();	
		}else{
			$url.show();		
		}
		return false;
	});
	//后台首页tab
	var $div_dt = $(".pagetab>ul>li");
	$div_dt.click(function(){
		$(this).addClass("selected").siblings().removeClass("selected");
		var index = $div_dt.index(this);
			if(index == 0)
			{
				  $(".f0").animate({left:"0px"},500); 	
			}else if(index == 1){
				  $(".f0").animate({left:"-1500px"},500);
				  $(".f1").animate({left:"0px"},500);		
			}else if(index == 2){
				  $(".f0").animate({left:"-1500px"},500);
				  $(".f1").animate({left:"-1500px"},500);
				  $(".f2").animate({left:"0px"},500);	
			}else if(index == 3){
				  $(".f0").animate({left:"-1500px"},500);
				  $(".f1").animate({left:"-1500px"},500);
				  $(".f2").animate({left:"-1500px"},500);
				  $(".f3").animate({left:"0px"},500);
				  $(".f4").animate({left:"-1500px"},500);	
			}else if(index ==4){
				  $(".f0").animate({left:"-1500px"},500);
				  $(".f1").animate({left:"-1500px"},500);
				  $(".f2").animate({left:"-1500px"},500);
				  $(".f3").animate({left:"-1500px"},500);
				  $(".f4").animate({left:"0px"},500);	
			}
		return false;	
	 });
	 $(".p-table tr:even").css("background-color","#F0F0F0");	
})
//分类管理table JS
$(function(){
		var $tabletr = $(".course-win2");
		$tabletr.click(function(){
			$tr = $(this).parent().siblings("tr");
			if($tr.is(":visible"))
			{
				$tr.hide();	
			}else{
				$tr.show();	
			}
		})
})

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
