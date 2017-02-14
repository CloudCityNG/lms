<?php
include_once ("../inc/global.inc.php");
$gid = $_SESSION['tbl_contest'];
$user_id = $_SESSION['_user']['user_id'];
$user_team_id = $_SESSION['_user']['teamId'];
$team_query = mysql_query('select teamAdmin from tbl_team where id=' . $user_team_id);
$team_admin = mysql_fetch_row($team_query);
$class_query = mysql_query('select class.id as cid,class.className as cname,event.id as eid,exam.exam_Name as ename from tbl_class as class,tbl_exam as exam,tbl_event as event where event.matchId=' . $gid . ' and class.id=exam.classId and exam.id=event.examId');
while ($class_row = mysql_fetch_assoc($class_query)) {
    $top_class[$class_row['cid']] = $class_row['cname'];
    $class_rows[$class_row['cid']][] = $class_row['eid'];
    $clunms_arr[$class_row['cid']][] = array($class_row['ename']);
}

$users_query = mysql_query('select user_id,username from user where teamId=' . $user_team_id);
while ($user_row = mysql_fetch_row($users_query)) {
    $user_rows[$user_row[0]] = $user_row[1];
}

foreach ($user_rows as $user_k => $user_v) {
    foreach ($class_rows as $class_k => $class_v) {
        foreach ($class_v as $class_v_k => $class_v_v) {
            $user_match_query = mysql_query('select fraction,state,Even_tion from tbl_match where gid='.$user_team_id.' and user_id=' . $user_k . ' and event_id=' . $class_v_v);
            $user_match_row = mysql_fetch_assoc($user_match_query);
            if ($user_match_row) {
                $list_arr[$class_k][$user_k][$class_v_k] = $user_match_row;
            } else {
                $list_arr[$class_k][$user_k][$class_v_k] = array();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
            <title>云教育资源平台_51CTF_战队信息</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="关于网络安全知识方面的在线考题竞赛平台实现以战队与个人形式进行在线答题比赛平台的战队信息页面，该平台依托于云资源教育平台">
        <meta name="keywords" content="云教育资源平台，网络安全教育学习，计算机教育学习，网络安全知识竞赛比赛">
        <link rel="stylesheet" type="text/css" href="css/base.css">
        <link rel="stylesheet" type="text/css" href="css/media-style.css">
    </head>
    <body>
<?php
        include 'left_header.php';
?>        
        <div class="g-mn1">
            <div class="g-mn1c">
                <!--题目-->
                <div class="j-list lists">
                    <div> 
                        <div>
                            <a noclick="return false;"><input type="submit" value="战队成绩" class="inputSubmit" style="border: 0 none;height: 25px;line-height: 22px;width: 70px;color: #FFFFFF;margin-left: 10px;vertical-align: middle;cursor: pointer;font-weight: bold;background-color: #0B7933;border-radius: 5px;"></a>
<!--                            <a noclick="return false;"><h3 class="b-title-match" style="color:#75B7E6;">战队成绩</h3></a>-->
                            <a href="team_manage.php">
                                <h3 class="b-title-match b-title-2">
<?php 
                             echo $isadmin===true ?
                                 '<input type="submit" value="战队管理" class="inputSubmit"style="border: 0 none;height: 25px;line-height: 22px;width: 70px;color: #FFFFFF;margin-left: 10px;vertical-align: middle;cursor: pointer;font-weight: bold;background-color: #0B7933;border-radius: 5px;">'
                                 :
                                 '<input type="submit" value="战队信息" class="inputSubmit"style="border: 0 none;height: 25px;line-height: 22px;width: 70px;color: #FFFFFF;margin-left: 10px;vertical-align: middle;cursor: pointer;font-weight: bold;background-color: #0B7933;border-radius: 5px;">'
                                ;
?>                                                                                                       
                                </h3>
                            </a>
                    <ul class="ul-tip"> 
                        <li>
                            <span class="li-r"></span>  
                            <font>正确</font>
                        </li>
                        <li>
                            <span class="li-w"></span>  
                            <font>错误</font>
                        </li>
                        <li>
                            <span class="li-n"></span>  
                            <font>未进入</font>
                        </li>
                        <li>
                            <span class="li-s"></span>  
                            <font>得分点</font>
                        </li>
                    </ul>
                        </div>
                        <div style="border-bottom: 2px solid #808080;width:100%;margin-top:3px;"></div>
                    <div class="b-15"></div>
                      <div class="b-course" id="b-scroll">
                          <table class="team-table" cellspacing="0" cellpadding="0" width="100%" style="text-align:center;">
                              <tr class="cate">
                                  <th width="8%"></th>
                                  <th width="20%"></th>
<?php
                           foreach($user_rows as $user_k => $user_v){
?>                                  
                                  <th class="ht">
<?php
                               echo $user_v;
                                if ($team_admin[0] == $user_k) {
                                    echo '&nbsp;(队长)';
                                }
?>
                                  </th>
<?php
                           }
?>                                  
                              </tr>
<?php
                  $i=0; 
                  foreach($top_class as $top_k => $top_v){ 
                      $style=$i===0 ? 'class="class-0"' : '';
?>                              
                              <tr <?=$style;?>>
                                  <td class="tdbig"><?=$top_v;?></td>                                
                                  <td class="t-every">
<?php
                           $ii=123;
                           foreach($clunms_arr[$top_k] as $clunms_k => $clunms_v){
                               if($ii !== 123){
                                    $bor_top='style="border-top:1px solid #000;"';
                               }else{
                                   $bor_top=null;
                               }
?>                                        
                                      <div <?=$bor_top;?>><?=htmlspecialchars_decode($clunms_v[0]);?></div>
<?php
                          unset($ii);   
                          }
?>                                        
                                  </td> 
<?php
                               foreach($list_arr[$top_k] as $list_arr_k => $list_arr_v){
?>                                  
                                    <td>
<?php
                       $i_three=1;
                      foreach($list_arr_v as $list_v_k => $list_v_v){
                          if(count($list_v_v) < 1){
                              $class='class="t-no" title="未进入"';
                          }else if($list_v_v['state'] == 1 && $list_v_v['Even_tion'] == 1){
                              $class='class="t-score" title="得分点"';
                          }else if($list_v_v['state'] == 1){
                              if($list_v_v['fraction']){
                                  $class='class="t-right" title="正确"';
                              }else{
                                  $class='class="t-wrong" title="错误"';
                              }
                          }
                          if($i_three !== 1){
                                   $bor_top='style="border-top:1px solid #000;"';
                               }else{
                                   $bor_top=null;
                               }
?>                                        
                                        <div <?=$bor_top;?>>
                                           <span  <?=$class;?>></span>
                                        </div>
<?php
                   unset($i_three);   }
?>                                        
                                        </div> 
                                    </td>
<?php
                               }
?>                                    
                              </tr>
<?php
            $i++;    
              }
?>                              
                          </table>
                      </div>
                    </div>   
                </div>
            </div>
        </div>   

    </dv>
</section>
</body>
</html>

