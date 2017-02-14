<?php
$language_file = array ('courses','admin');
$cidReset=true;
include_once ("../../inc/global.inc.php");
$this_section=SECTION_PLATFORM_ADMIN;
$countnum=mysql_fetch_row(mysql_query('select count(id) from vmdisk_log'));
if($countnum['0'] > 2000){
    $delnum=$countnum['0']-2000;
    if($delnim < 10000){
       mysql_query('delete from vmdisk_log order by id desc limit '.$delnum);
     }else{
       mysql_query('delete from vmdisk_log order by id desc limit 10000');  
     }
}
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
$display_admin_menushortcuts=(api_get_setting('display_admin_menushortcuts')=='true'?TRUE:FALSE);
include_once(api_get_path(SYS_CODE_PATH).'course/course.inc.php');
require_once (api_get_path(LIBRARY_PATH).'course.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
Display::display_header();
$track_e_login = Database::get_main_table ( track_e_login);  

 function get_sqlwhere() { 
    $sql_where = "";
    if (is_not_blank ( $_POST['the_week'] )) { 
        $oneweek=date("Y-m-d H:i:s",time()-3600*24*7);
        $sql_where .= "  where  login_date < '".date("Y-m-d H:i:s",time())."'  and  login_date >= '".$oneweek."'"; 
    }else if(is_not_blank ( $_POST['the_month'] )){
        $oneweek=date("Y-m-d H:i:s",time()-3600*24*30);
        $sql_where .= "  where  login_date < '".date("Y-m-d H:i:s",time())."'  and  login_date >= '".$oneweek."'"; 
    }
    
    if ($sql_where)  return  $sql_where ;
    else   return "";
} 
$sql_where=get_sqlwhere();
//data-first
$sql="select  count(login_user_id) as login_num,login_user_id  from {$track_e_login} ";
if($sql_where){
    $sql.=$sql_where;
}
$sql.=" group  by  login_user_id  order by  count(login_user_id) desc";
$ress= api_sql_query_array_assoc($sql); 
$results="";
foreach ($ress as $key=>$value){
    $ctitle=DATABASE::getval("select `username`  from `user`  where  `user_id`=".$value['login_user_id']);
    if(!$ctitle) $ctitle="匿名".$key;
     $results[$ctitle]= $value['login_num']; 
}
$php_json= json_encode($results);
?>
 
<section id="main" class="column" style="width:98%;">
    <h4 class="page-mark" style="width:98%;margin:20px 1% 0 2%;">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/log/logging_list.php">日志管理</a>&gt; 登录访问日志统计
            <span style="float: right;">
            <form action="" method="post" >
                <input type="hidden" name="the_week" value="theweek">
                <input type="submit"  value="本周">
            </form>
            </span>
            <span style="float: right;">
            <form action="" method="post" >
                <input type="hidden" name="the_month" value="themonth">
                <input type="submit"  value="本月">
            </form>
            </span>
    </h4>
    <div style="width:98%;height:580px;margin:20px 1% 0 2%;">
        <div id="container_first" style="border: 1px #008000 dashed;width:100%;height: 100%;margin: 0 auto ;">
            <?php  if(!$ress) echo "无相关数据！！";?>
        </div> 
    </div>
</section>
<script type="text/javascript" src="../../../themes/js/jquery-1.5.2.min.js"></script>
<script src="../../../themes/js/highcharts.js"></script>
<script src="../../../themes/js/highcharts-3d.js"></script> 
<script src="../../../themes/js/exporting.js"></script>
<script type="text/javascript">
$(function () { 
    var phpjson=<?=$php_json?>;
          var  login_num_arrs=new Array();
          var  users=new Array();
          var i=0;
          $.each(phpjson,function(k,val) {   
             users[i]=k;  
             login_num_arrs[i]=parseInt(val);;
             i++;  
          });
  $('#container_first').highcharts({
            chart: {
                type: 'column',
                margin: 75,
                options3d: {
                    enabled: true,
                    alpha: 10,
                    beta: 18,
                    depth: 70
                }
            },
            title: {
                text: '登录访问日志统计图'
            },
            subtitle: {
                text: ' '
            },
            plotOptions: {
                column: {
                    depth: 25
                }
            },
            xAxis: {
                categories:users
            },
            yAxis: {
                opposite: true
            },
            series: [{
                name: '访问次数',
                data: login_num_arrs
            }]
        });
});
</script>
</body>
</html>
