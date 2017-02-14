<?php
$cidReset = true;
include_once ("inc/app.inc.php");
include_once ('../../main/exercice/exercise.class.php');

include_once ("inc/page_header.php");
$enabled_exam = api_get_setting ( 'enable_modules', 'exam_center' ) == 'true' ? TRUE : FALSE;
if (! $enabled_exam) api_redirect ( 'learning_center.php' );
$type = (isset ( $_GET ['type'] ) ? getgpc ( 'type', 'G' ) : 3);
$type=(int)$type;
$url = "exam_result.php";
if($type!=null){
       $ty=Database::getval ("select name from exam_type where id=".$type, __FILE__, __LINE__ );
         $nameTools=$ty;
        }
$user_id = api_get_user_id ();  
//$sqlwhere = "(t1.score<>'' OR t1.score IS NULL) AND t2.active=1 AND t2.type=" . Database::escape ( $type );
//$rtn_data = Exercise::get_user_exam_pagelist ( $user_id, $sqlwhere, NUMBER_PAGE, getgpc ( "offset", "G" ) );
//$datalist = $rtn_data ["data_list"];
//$total_rows = $rtn_data ["total_rows"];

$total_rows =DATABASE::getval("select count(exe_id) from  `exam_track` where `exe_user_id`=".$user_id);
$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );

$sql="select  `exe_exo_id`,`start_date`,`exe_date`,`score`,`status`,`exe_result`,m.`results_disabled` from  `exam_track` as t inner join `exam_main` as m where t.exe_exo_id=m.id and t.exe_user_id=".$user_id;
if( $_GET['offset']==''){
    $offset=0;
}else{
    $offset=(int)getgpc ( "offset", "G" );
}
$sql .= " LIMIT " . $offset . "," . NUMBER_PAGE;

$re=api_sql_query ( $sql, __FILE__, __LINE__ );
$datalist='';
while ( $row = Database::fetch_array ( $re, 'ASSOC' ) ) {
      $datalist[] = $row;
}
 
display_tab ( TAB_LEARN_PROGRESS );
//导航判断
if($platform==3){
    $nav='exam';
}else{
    $nav='exam-Centre';
}
?>
<div class="clear"></div> 
    <div class="m-moclist">
        <div class="g-flow" id="j-find-main">
          <div class="b-30"></div>
	<div class="g-container f-cb">	
            <div class="g-sd1 nav">
              <div class="m-sidebr" id="j-cates">
                  <ul class="u-categ f-cb">
                      <li class="navitm it f-f0 f-cb haschildren cur" data-id="-1" data-name="考试中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                           <a class="f-thide f-f1" style="background-color:#13a654;color:#FFF" title="考试中心">考试中心</a>
                      </li>
                      <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的考试" id="auto-id-D1Xl5FNIN6cSHqo0">
                          <a class="f-thide f-f1" title="我的考试" href="exam_list.php" >我的考试</a>
                      </li>
                      <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="考试成绩查询" id="auto-id-D1Xl5FNIN6cSHqo0">
                          <a class="f-thide f-f1" title="考试成绩查询" href="exam_result.php"  style="color:green;font-weight:bold">考试成绩查询</a>
                      </li> 
                  </ul>
              </div>
            </div>
              
              
               <!--  右侧 -->
            <div class="g-mn1" >
                 <div class="g-mn1c m-cnt" style="display:block;">

                    <div class="j-list lists" id="j-list"> 
                        <div class="u-content">
                        <h3 class="sub-simple u-course-title"></h3>      
                        <?php
                        if ($datalist && is_array ( $datalist )) {
                            ?>
                            <table cellspacing="0" border="0" width="100%" class="tbl_course"> 
                                <tr>
                                    <th class="case-table-title">考试名称</th>
                                    <th>考试时间</th>
                                    <th style="text-align:center;">状态</th>
                                    <th style="text-align:center;">成绩</th>
                                    <th style="text-align:center;">是否及格</th>
                                </tr>
                                <tr>
                                <td colspan="10"><h3  class="sub-simple u-course-title"></h3> </td>
                                </tr>

                                <?php
                                $exerciseId = escape ( getgpc ( 'exerciseId' ));
                                foreach ( $datalist as $v ) {
                                    $attempt_times = Exercise::get_exam_user_attempts ( $v ['id'], $user_id );
                                    $sql="select `title`  from  `exam_main` where  `id`=".$v ['exe_exo_id'].' and title!=""';
                                    $exam_title=DATABASE::getval($sql);
                                   if($exam_title){
                                    ?>
                                 <tr>
                                        <td class="line-title">
                                        <?php
                                            echo  $exam_title;
                                        ?>

                                        </td>
                                        <td><?=substr ( $v ['start_date'], 0, 16 )?>
                                            至 <?=substr ( $v ['exe_date'], 0, 16 )?></td>
                                        <td class="dd2" style="text-align: center;">
                                                <?php
                                                if($v['status']=='completed'){
                                                    echo '已完成';
                                                }else if($v['status']=='incomplete'){
                                                    echo '未完成';
                                                }
                                                ?>
                                        </td>
                                     <?php if($v['results_disabled'] !== '1'){?>
                                        <td style="text-align:center;">
                                            <?php  echo $v ['exe_result'];  ?>
                                        </td>
                                     <?php }?>
                                        <td style="text-align:center;">
                                     <?php if($v['score'] < 60){
                                         echo "不及格";
                                     }else{
                                         echo "及格";
                                     }
                                      ?>            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"><h3  class="sub-simple u-course-title"></h3> </td>
                                    </tr>

                                            <?php
                                    }}
                                            ?>

                                </table>
                                <div class="page">
                                    <ul class="page-list">
                                        <li class="page-num">总计<?=$total_rows?> 条记录</li>
                                        <?php
                                        echo $pagination->create_links ();
                                        ?>
                                    </ul>
                                </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="error b">没有相关记录</div>
                                    <?php
                                }
                                    ?>
                                </div>
                        </div>
                </div>
            </div>
    </div>
 </div>
</div>
<?php
    include_once('./inc/page_footer.php');
?>
</body>
</html>
