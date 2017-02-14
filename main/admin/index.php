<?php
/*
 * Created by JetBrains PhpStorm.
 * User: chang
 * Date: 12-12-15
 * Time: 下午4:59
 * To change this template use File | Settings | File Templates.
 */
header("Content-Type:text/html;charset=utf-8");
include ('../../main/inc/global.inc.php');
api_protect_admin_script ();
include ('../inc/header.inc.php');
?>   
     <section id="main-content" class="column">
     <div class="container-fluid">
      <h4 class="page-mark">当前位置：平台首页</h4>
      <link href="../../themes/css/exerice.css" type="text/css" rel="stylesheet"/>      
<!--顶部系统使用信息开始-->
     <div class="all">
      <div class="all-inner">
	   <div class="row-fluid">
<?php
        //清楚趋势图表数据
    $mintime = mysql_fetch_assoc(mysql_query("select time from run_chart order by id asc limit 1"));
      if( $mintime['time'] )
      {
           $diftime=time()-strtotime($mintime['time']);
           if($diftime > 86400)
           {
               $maxcpu = mysql_fetch_assoc( mysql_query("select *from run_chart order by cpu desc limit 1") );
               $result = mysql_query("insert run_history(cpu,memory,virtual_number,disc_1,disc_2,ip_location,online_number,time) values({$maxcpu['cpu']},{$maxcpu['memory']},{$maxcpu['virtual_number']},{$maxcpu['disc_1']},{$maxcpu['disc_2']},'{$maxcpu['ip_location']}',{$maxcpu['online_number']},'{$maxcpu['time']}');");
               if($result)
               {
                  //清空数据走势图表索引归1 
                  mysql_query("truncate table run_chart"); 
               }
           }
      }
    $filestr = file_get_contents('/tmp/statusinfo');
    $filearr = explode(' ',$filestr);
    $result5 = mysql_query('select count(*) as num from vmtotal WHERE `manage`=0');
    $numarr = mysql_fetch_assoc($result5);
    $view_user_dept = Database::get_main_table ( VIEW_USER_DEPT );
    $tbl_track_online = Database::get_main_table ( TABLE_STATISTIC_TRACK_E_ONLINE );
    $sql1 = "SELECT t2.username,t2.firstname, login_user_id,login_ip,login_date,email,t2.dept_name,user_id,t1.login_id,t1.login_date FROM $tbl_track_online AS t1,$view_user_dept AS t2 WHERE t1.login_user_id=t2.user_id  ";
    $user_data = api_sql_query_array_assoc($sql1,__FILE__,__LINE__);
    foreach($user_data as $user_data_k=>$user_data_v)
    {

        if( ( time() - strtotime($user_data_v['login_date']) ) > ONLINE_TIME)
        {
             $user_session = ini_get('session.save_path').'/sess_'.$user_data_v['login_id'];
             chmod($user_session, 0777);
             unlink($user_session);
             mysql_query('delete from '.$tbl_track_online.' where login_user_id='.$user_data_v['login_user_id']);
        }else{
            $user_data_now[] = $user_data_v;
        }
    }

    $total = count ( $user_data_now ); 
?>              
<div class="span12 box box-transparent">  
        <div class="span2 box-quick-link blue-background" style="margin-left:0px;">
            <div class="link-list">
                <div class="header">
                    <a class="icon-comments">
                        <?php if(empty($filearr[2])){echo "0%";}else{echo $filearr[2];}?>
                    </a>
                </div>
                <div class="content">CPU使用率</div>
            </div>
       </div>
      <div class="span2 box-quick-link green-background">
            <div class="link-list">
                <div class="header">
                    <a class="icon-comments">
                        <?php if(empty($filearr[3])){echo "0%";}else{echo $filearr[3];}?>
                    </a>
                </div>
                <div class="content">内存使用率</div>
            </div>
       </div>
    <div class="span2 box-quick-link orange-background">
            <div class="link-list">
                <div class="header">
                    <a class="icon-comments">
                        <?php echo empty($filearr[0]) ? "0%" : $filearr[0];?>
                    </a>
                </div>
                <div class="content">磁盘使用率1</div>
            </div>
       </div>
    <div class="span2 box-quick-link purple-background">
            <div class="link-list">
                <div class="header">
                    <a class="icon-comments">
                        <?php echo empty($filearr[1]) ? "0%" : $filearr[1];?>
                    </a>
                </div>
                <div class="content">磁盘使用率2</div>
            </div>
       </div>
     <div class="span2 box-quick-link red-background">
            <div class="link-list">
                <div class="header">
                    <a class="icon-comments" href="vmmanage/vmmanage_iframe.php">
                        <?php echo empty($numarr['num']) ? "0" : $numarr['num'];?>
                    </a>
                </div>
                <div class="content">在线虚拟机数量</div>
            </div>
       </div>
    <div class="span2 box-quick-link muted-background">
            <div class="link-list">
                <div class="header">
                    <a class="icon-comments" href="user/user_online.php">
                          <?php echo $total; ?>
                    </a>
                </div>
                <div class="content">在线用户数量</div>
            </div>
       </div>
    
    
    </div>
    </div>
   </div>
 </div>
<!--顶部系统使用信息结束-->

        <div id="function-main" class="function-main"> 
        <div class="maintool f0">
<!--第二层网站信息开始            -->
            <div class="tool-list">
            <div class="Coludlab-map">
                <div class="Coludlab-map-icon map-desktop"></div>
                <dl class="cloudlab-map-list">
                    <dt>用户桌面</dt>
                    <dd><a href="<?=URL_APPEND?>user_portal.php">我管理的课程</a></dd>
                    <?php if(api_get_setting ( 'enable_modules', 'exam_center' ) == 'true'){?>
                    <dd><a href="<?=URL_APPEND?>main/exam/exam_corrected_list.php">我批改的考卷</a></dd>
                    <?php } ?> 
                </dl>
            </div>
            <div class="Coludlab-map">
                <div class="Coludlab-map-icon map-course"></div>
                <dl class="cloudlab-map-list">
                    <dt>课程管理</dt>
                    <dd><a href="<?=URL_APPEND?>main/admin/course/course_list.php">课程管理</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/course/course_plan.php">课程调度</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/course/course_user_manage.php">调度查看</a></dd>


                    <?php if(api_get_setting ( 'enable_modules', 'survey_center' ) == 'true' AND $_configuration ['enable_module_survey']){?>
                    <dd><a href="<?=URL_APPEND?>main/admin/course/course_category_iframe.php">课程分类</a></dd>
                    <?php } ?>
                </dl>
            </div>
            <?php if(api_get_setting ( 'enable_modules', 'exam_center' ) == 'true'){?>
             <div class="Coludlab-map">
                 <div class="Coludlab-map-icon map-exam"></div>
                 <dl class="cloudlab-map-list">
                    <dt>考试管理</dt>
                    <dd><a href="<?=URL_APPEND?>main/exam/pool_iframe.php">题库管理</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/exercice/question_base.php">所有考试</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/exercice/exercice.php">综合考试</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/exercice/exercice.php?type=2">课程考试</a></dd>

                </dl>
            </div>
                    <?php } ?> 
            <div class="Coludlab-map">
                <div class="Coludlab-map-icon map-statistics"></div>
                <dl class="cloudlab-map-list">
                    <dt>查询统计</dt>
                    <dd><a href="<?=URL_APPEND?>main/reporting/learning_progress.php">学习情况</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/survey/index.php">调查问卷</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/user/user_online.php">在线用户</a></dd>
                    <?php if(api_get_setting ( 'enable_modules', 'exam_center' ) == 'true'){?>
                    <dd><a href="<?=URL_APPEND?>main/reporting/query_quiz.php">成绩查询</a></dd>
                    <?php } ?>
                </dl>
            </div>
            <div class="Coludlab-map">
                <div class="Coludlab-map-icon map-user"></div>
                <dl class="cloudlab-map-list">
                    <dt>用户管理</dt>
                    <?php if(isRoot()){?>
                        <dd><a href="<?=URL_APPEND?>main/admin/user/user_list.php">用户管理</a></dd>
                    <?php } ?>
                    <dd><a href="<?=URL_APPEND?>main/admin/dept/dept_iframe.php">组织管理</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/user/user_list_audit.php">审核用户</a></dd>
                </dl>
            </div>
            <div class="Coludlab-map">
                <div class="Coludlab-map-icon map-cloud"></div>
                <dl class="cloudlab-map-list">
                    <dt>云管理</dt>
                    <dd><a href="<?=URL_APPEND?>main/admin/vmmanage/vmmanage_iframe.php">虚拟化管理</a></dd>
                    <?php if(isRoot()){?>
                        <dd><a href="<?=URL_APPEND?>main/admin/net/vm_list_iframe.php">网络拓扑设计</a></dd>
                        <dd><a href="<?=URL_APPEND?>main/admin/vmmanage/centralized.php">集中管理设置</a></dd>
                        <dd><a href="<?=URL_APPEND?>main/admin/cloud/clouddesktop.php">云桌面终端</a></dd>
                    <?php } ?>
                </dl>
            </div>
            <div class="Coludlab-map">
                <div class="Coludlab-map-icon map-log"></div>
                <dl class="cloudlab-map-list">
                    <dt>路由交换</dt>
                    <dd><a href="<?=URL_APPEND?>main/admin/router/labs_topo.php">网络拓扑设计</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/router/labs_device.php">网络设备管理</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/router/labs_mod.php">路由交换模块</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/router/labs_ios.php">路由交换管理</a></dd>
                </dl>
            </div>
            <?php if(isRoot()){?>
            <div class="Coludlab-map">
                <div class="Coludlab-map-icon map-setting"></div>
                <dl class="cloudlab-map-list">
                    <dt>系统管理</dt>
                    <dd><a href="<?=URL_APPEND?>main/admin/misc/settings.php">系统设置</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/misc/system_upgrade.php">系统升级</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/misc/system_management.php">系统管理</a></dd>
                    <dd><a href="<?=URL_APPEND?>main/admin/systeminfo.php">系统信息</a></dd>
                </dl>
            </div>
            </div>
<!--第二层网站信息结束-->
<?php   
    $is=file_exists("/etc/cloudschedule/cloudsystemmonitor");  
    if(!$is){ 
?>
<!--走势图开始-->
            <div id="table_line" style="height:930px;margin-top:10px;border:1px solid #999999;border-radius:6px 6px 6px;">

       <div style="width:98%;height:50px;margin:20px 1% 0 1%;">   
<?php 
        $allip=mysql_query('select id,ip_location from run_chart group by ip_location');
        $Usestr=1;
        while($ip_location=mysql_fetch_assoc($allip))
         {
            if($Usestr==1){
            $ip_location_1=$ip_location['ip_location'];
            $ip_location_id=$ip_location['id'];
                           }
             $all_id[]=$ip_location['id'];
?>                
             <div id="iplocation<?php echo $ip_location['id'];?>" style="width:20%;height:50px;text-align:center;line-height:50px;float:left;"><a href="javascript:void(0)" onclick="iplist('<?php echo $ip_location['ip_location'];?>',<?php echo $ip_location['id'];?>);"><?php echo $ip_location['ip_location'];?></a></div>
<?php
          $Usestr++;      
         }
         $js_allid=json_encode($all_id);
        $all_ip_line=ceil(($Usestr-1)/5);
        $table_line_h=880+50*$all_ip_line;
        $function_main_h=1200+50*$all_ip_line;
?>                      
       </div>  
   <script type="text/javascript" src="../../themes/js/jquery-1.5.2.min.js"></script>
   <script type="text/javascript" src="../../themes/js/highcharts.js"></script>
   <?php
   if( api_get_setting("lm_switch")=="false"){
?>
   <script type="text/javascript" src="../../themes/js/dark-green.js"></script>
   <?php  } ?>
   <script type="text/javascript" src="../../themes/js/exporting.js"></script>
   <script type="text/javascript">
        
        $(function(){ 
           $('#function-main').css('height','<?php echo $function_main_h;?>px');
           $('#table_line').css('height','<?php echo $table_line_h;?>px');
          $.ajax({
               type:'POST',
                url:'list.php',
               data:'ip_location=<?php echo $ip_location_1;?>',
           dateType:'html',
           success:function(er){
               $('#iplist').html(er);
               cycle(<?php echo $ip_location_id;?>);
               $('#iplocation<?php echo $ip_location_id;?>').css("background","#D8E9F1");
           }
            });
        });
        function iplist(ip_location,id){
            $.ajax({
               type:'POST',
                url:'list.php',
               data:'ip_location='+ip_location,
           dateType:'html',
           success:function(er){
               $('#iplist').html(er);
               cycle(id);
               $('#iplocation'+id).css('background','#D8E9F1');
           }
            });
        }
        function cycle(id)
        {   
            var js_arr=<?php echo $js_allid?>;
     
    for(var i=0;i<=js_arr.length;i++)
          {
              if(id!==js_arr[i]){
                $('#iplocation'+js_arr[i]).css('background','#F8F8F8');  
              }
          }
        }
   </script>   
<div id="iplist"> 
   </div>
                <div id="Coludlab-map-cpu" class="Coludlab-map-line" style="width:98%; height:400px;margin:0px 1% 0px 1%;"></div>
                <div id="Coludlab-map-online" class="Coludlab-map-line" style="width:98%; height:400px;margin:0px 1%;"></div>                
            </div>
<!--走势图结束-->
            <?php  } ?>
         </div>  
     </div>
</div>
 </section

<?php } ?> 
</body>
</hrml>