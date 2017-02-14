<?php
$language_file = array ('courses','admin');
$cidReset=true;
include_once ("../../inc/global.inc.php");
$this_section=SECTION_PLATFORM_ADMIN;
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();

$htmlHeadXtra[]='<script type="text/javascript">
if(document.attachEvent)  window.attachEvent("onload",  iframeAutoFit);
else  window.addEventListener("load",  iframeAutoFit,  false);
</script>
<style type="text/css">
.framePage {border-top-style:none;	width:100%;	padding-top:0px;	text-align:left;}
#Resources {width:100%;}
#Resources #treeview {	float:left;	border:#999 solid 1px;	width:20%;	}
#Resources #frm {	float:left;	width:79%;}
</style>';

$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header();
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');


function get_vm_data() {
    $user_study_time = array();
    $time_query=mysql_query('select t1.user_id from vmdisk_log as t1,user as t2 where t1.close_status=0 and t1.user_id=t2.user_id and t2.status=5 group by user_id');
    while($time_row=mysql_fetch_assoc($time_query)){
        $query_time=mysql_query("select start_time, end_time from vmdisk_log  where manage=0 and user_id=".$time_row['user_id']);
        while($user_row=mysql_fetch_assoc($query_time)){
            if(!empty($user_row['start_time']) && !empty($user_row['end_time'])){
                $start_time=strtotime($user_row['start_time']);
                $end_time=  strtotime($user_row['end_time']);
                if($end_time > $start_time){
                    $study_time=$end_time-$start_time;
                    $study_times=intval($study_time/60);
                    $user_study_time[$time_row['user_id']]+=$study_times;
                }
            }
        }
    }
    arsort($user_study_time);
    $row_list = array();
    $row_list_s = array();
    $a = 1;
    foreach($user_study_time as $user_study_k => $user_study_v){
        $questr=intval($user_study_v);
        if($questr > 60){
            if($questr > 1440){
                $danum=intval($questr/1440);
                $Remainder=$questr%1440;
                $hours=0;
                if($Remainder > 60){
                    $hours=intval($Remainder/60);
                }
                $hours=$hours ? $hours.'小时' : '';
                $timestr=$danum.'天'.$hours;
            }else{
                $hours=intval($questr/60);
                $timestr=$hours.'小时';
            }
        }else{
                $timestr=$questr.'分钟';
        }

        $user_query=mysql_query('select username,last_login_ip from user where user_id='.$user_study_k);
        $user_row=mysql_fetch_row($user_query);
        $row_list[0] = $a;
        $row_list[1] = $user_row[0];
        $row_list[2] = $user_row[1];
        $row_list[3] = $timestr;
        $row_list_s[] = $row_list;
        $a++;
    }

    return $row_list_s;
    
}

$sorting_options = array ('column' => 'tablename_direction', 'default_order_direction' => 'ASC' );
$table_header [] = array ('序号',true, array ('style' => 'width:10%' ));
$table_header [] = array ('姓名',false, array ('style' => 'width:30%' ));
$table_header [] = array ('用户IP',false, array ('style' => 'width:30%' ));
$table_header [] = array ('学习总时间',false, array ('style' => 'width:30%' ));
$get_vm_data = get_vm_data()
?>
<aside id="sidebar" class="column systeminfo  open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark" style="width:98%;margin:20px 1% 0 2%;">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/log/ranking.php">日志管理</a> &gt; 学霸排名日志</h4>
   <ul class="manage-tab boxPublic">

        <?php
        $html .= '<li ><a href="logging_list.php"><em>系统操作日志</em></a></li>';
        $html .= '<li ><a href="access_login_logs.php"><em>' . get_lang ( "AccessLoginLog" ) . '</em></a></li>';
         $html .= '<li class="selected"><a href="ranking.php"><em>' . get_lang ( "学霸排名日志" ) . '</em></a></li>';
        echo $html;
        ?>
    </ul>
 
    <article class="module width_full hidden" style="width:98%;margin:20px 1% 0 2%;">
        <form name="form1" method="post" action="">
            <table cellspacing="0" cellpadding="0" class="p-table">
<?php
Display::display_sortable_table ( $table_header, $get_vm_data,$sorting_options, $paging_options = array (), $query_vars = null, $form_actions = array(), $disp_nav_bar_style = NAV_BAR_BOTTOM);
?>
            </table>
        </form>
    </article>
</section>

<style>
    .one-delete {
        float:right;
        display:block;
/*        margin-right:8px;*/
        padding:0 7px;
        position:relative;
    }
    .one-import{
/*       height:30px;*/
    }
    .one-import a{
        vertical-align:top;
    }
    .one-import a img{
        margin-top:-4px;
    }
    .one-delete  span{
        margin:0;
    }
    .one-delete  .img-span{
        display:inline-block;
        position:absolute;
        top:11px;
        left:0px;
        height:25px;
        width:25px;
        margin-right:5px;
        background:url("<?=api_get_path ( WEB_PATH )?>themes/images/one_delete.png")  no-repeat;
        
    }   
    .one-delete  .span-text{
       display:inline-block;
       padding:0 0 0 15px;
    }
    .img-span1{
        display:inline-block;
        position:absolute;
        top:11px;
        left:0px;
        height:25px;
        width:25px;
        margin-right:5px;
        background:url("<?=api_get_path ( WEB_PATH )?>themes/img/export.png")  no-repeat;
        
    }   
</style>
</body>
</html>
