<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
$user_id=api_get_user_id ();
//学习足迹
$pa=  getgpc("page");
if($pa){
    $page=$pa;
}else{
    $page=1;
}
$pagesize=5;
$start=($page-1)*$pagesize;
$page_url="my_foot.php";

$sql="select  `lesson_id`,`vmid`,`start_time`,`end_time`,`close_status`  from  `vmdisk_log`  where    `user_id`=".$user_id."  order by  id  desc  limit  {$start} , {$pagesize}"; 
$course_vms=  api_sql_query_array_assoc($sql);
$count_sql= $sql="select  `lesson_id`,`vmid`,`start_time`,`end_time`,`close_status`  from  `vmdisk_log`  where    `user_id`=".$user_id."  order by  id  desc";
$crs_vm=  api_sql_query_array_assoc($sql);
$counts=count($crs_vm);   
$pages=  ceil($counts/$pagesize);   
//登录足迹
$sql="SELECT  `login_date`,`login_ip`  FROM `track_e_login`  where  login_user_id=".$user_id."   order  by  `login_date`  desc   limit  20";
$login_foots=  api_sql_query_array_assoc($sql);
//login-address
function convertip($ip) { 
  $ip1num = 0;
  $ip2num = 0;
  $ipAddr1 ="";
  $ipAddr2 ="";
  $dat_path = '../../main/admin/log/qqwry.dat';        
  if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) { 
    return $ip; 
  }  
  if(!$fd = @fopen($dat_path, 'rb')){ 
    return $ip; 
  }  
  $iparr = explode('.', $ip); 
  $ipNum = $iparr[0] * 16777216 + $iparr[1] * 65536 + $iparr[2] * 256 + $iparr[3];  
  $DataBegin = fread($fd, 4); 
  $DataEnd = fread($fd, 4); 
  $ipbegin = implode('', unpack('L', $DataBegin)); 
  if($ipbegin < 0) $ipbegin += pow(2, 32); 
    $ipend = implode('', unpack('L', $DataEnd)); 
  if($ipend < 0) $ipend += pow(2, 32); 
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1; 
  $BeginNum = 0; 
  $EndNum = $ipAllNum;  
  while($ip1num>$ipNum || $ip2num<$ipNum) { 
    $Middle= intval(($EndNum + $BeginNum) / 2); 
    fseek($fd, $ipbegin + 7 * $Middle); 
    $ipData1 = fread($fd, 4); 
    if(strlen($ipData1) < 4) { 
      fclose($fd); 
      return $ip; 
    }
    $ip1num = implode('', unpack('L', $ipData1)); 
    if($ip1num < 0) $ip1num += pow(2, 32); 

    if($ip1num > $ipNum) { 
      $EndNum = $Middle; 
      continue; 
    } 
    $DataSeek = fread($fd, 3); 
    if(strlen($DataSeek) < 3) { 
      fclose($fd); 
      return $ip; 
    } 
    $DataSeek = implode('', unpack('L', $DataSeek.chr(0))); 
    fseek($fd, $DataSeek); 
    $ipData2 = fread($fd, 4); 
    if(strlen($ipData2) < 4) { 
      fclose($fd); 
      return $ip; 
    } 
    $ip2num = implode('', unpack('L', $ipData2)); 
    if($ip2num < 0) $ip2num += pow(2, 32);  
      if($ip2num < $ipNum) { 
        if($Middle == $BeginNum) { 
          fclose($fd); 
          return $ip; 
        } 
        $BeginNum = $Middle; 
      } 
    }  
    $ipFlag = fread($fd, 1); 
    if($ipFlag == chr(1)) { 
      $ipSeek = fread($fd, 3); 
      if(strlen($ipSeek) < 3) { 
        fclose($fd); 
        return $ip; 
      } 
      $ipSeek = implode('', unpack('L', $ipSeek.chr(0))); 
      fseek($fd, $ipSeek); 
      $ipFlag = fread($fd, 1); 
    } 
    if($ipFlag == chr(2)) { 
      $AddrSeek = fread($fd, 3); 
      if(strlen($AddrSeek) < 3) { 
      fclose($fd); 
      return $ip; 
    } 
    $ipFlag = fread($fd, 1); 
    if($ipFlag == chr(2)) { 
      $AddrSeek2 = fread($fd, 3); 
      if(strlen($AddrSeek2) < 3) { 
        fclose($fd); 
        return $ip; 
      } 
      $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0))); 
      fseek($fd, $AddrSeek2); 
    } else { 
      fseek($fd, -1, SEEK_CUR); 
    } 
    while(($char = fread($fd, 1)) != chr(0)) 
    $ipAddr2 .= $char; 
    $AddrSeek = implode('', unpack('L', $AddrSeek.chr(0))); 
    fseek($fd, $AddrSeek); 
    while(($char = fread($fd, 1)) != chr(0)) 
    $ipAddr1 .= $char; 
  } else { 
    fseek($fd, -1, SEEK_CUR); 
    while(($char = fread($fd, 1)) != chr(0)) 
    $ipAddr1 .= $char; 
    $ipFlag = fread($fd, 1); 
    if($ipFlag == chr(2)) { 
      $AddrSeek2 = fread($fd, 3); 
      if(strlen($AddrSeek2) < 3) { 
        fclose($fd); 
        return $ip; 
      } 
      $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0))); 
      fseek($fd, $AddrSeek2); 
    } else { 
      fseek($fd, -1, SEEK_CUR); 
    } 
    while(($char = fread($fd, 1)) != chr(0)){ 
      $ipAddr2 .= $char; 
    } 
  } 
  fclose($fd);  
  if(preg_match('/http/i', $ipAddr2)) { 
    $ipAddr2 = ''; 
  } 
  $ipaddr = "$ipAddr1 $ipAddr2"; 
  $ipaddr = preg_replace('/CZ88.NET/is', '', $ipaddr); 
  $ipaddr = preg_replace('/^s*/is', '', $ipaddr); 
  $ipaddr = preg_replace('/s*$/is', '', $ipaddr); 
  if(preg_match('/http/i', $ipaddr) || $ipaddr == '') { 
    $ipaddr = 'Unknown'; 
  } 
  return $ipaddr; 
}
 
?>
<style>
    body{
        color:#444;
    }
    .la{color:#444;}
  input{color:#444;}
  .sp{border-right: 1px #ccc solid;padding-right: 3px;}
</style>
      <?php      if(api_get_setting ( 'lm_switch' ) == 'true'){
                ?>
  <style>
.m-moclist .nav .u-categ .navitm.it a:hover{
	color:#357CD2;
	background:#fff;
} 
.m-moclist .nav .u-categ .navitm.it.course-mess:hover{
    border-right-color: #357CD2;
}
.m-moclist .nav .u-categ .navitm.it.cur:hover .f-f1:hover{
background:#357CD2;
color:#fff;
}
.m-moclist .nav .u-categ .navitm.it.cur:hover .i-mc a:hover{
    color:#357CD2;
}
.login-time{
    font-size:12px;
    color:#357cd2;
}
  </style>
      <?php   }   ?> 
<div class="clear"></div>
<div class="m-moclist">
    <div class="g-flow" id="j-find-main">
           <div class="b-30"></div>
          <!--左侧-->
   <div class="g-container f-cb">
        <div class="g-sd1 nav">
            <div class="m-sidebr" id="j-cates">
                <ul class="u-categ f-cb">
                    <li class="navitm it f-f0 f-cb first cur" data-id="-1" data-name="用户中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                         <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF" title="用户中心">用户中心</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的足迹" href="my_foot.php" style="color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;font-weight:bold">我的足迹</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="选课记录" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="选课记录" href="course_applied.php">选课记录</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="信息修改" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="信息修改" href="user_profile.php">信息修改</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="密码修改" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="密码修改" href="user_center.php">密码修改</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的考勤" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的考勤" href="work_attendance.php">我的考勤</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="站内信" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="站内信" href="msg_view.php">站内信</a>
                    </li>
                    
                </ul>
                 <ul class="u-categ f-cb" style="margin-top:15px;">
                               <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="学习中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF" title="学习中心">学习中心</a>
                               </li>
                               <?php  
                                $sql="select id,title from setup order by custom_number";
                                  $res=  api_sql_query_array($sql);
                                  foreach ($res as $value) {
                                      ?>
                            <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                            <a class="f-thide f-f1" <?=$value['id']==$id?' style="color:green;font-weight:bold"':''?> title="<?=$value['title']?>" href="<?=URL_APPEND."portal/sp/learning_before.php?id=".$value['id']?>"><?=$value['title']?></a>
                                <div class="i-mc">
                                                    <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                                            <?php    
                                                            $sql1="select subclass from setup where id=".$value['id'];
                                                              $re1=  Database::getval($sql1);
                                                              $rews1=explode(',',$re1);
                                                                  $subclass1='';
                                                                  foreach ($rews1 as $v1) {
                                                                      if($v1!==''){
                                                                         $subclass1[]=$v1; 
                                                                      }
                                                                  }
                                                              $objCrsMng1=new CourseManager();//课程分类  对象。
                                                              $objCrsMng1->all_category_tree = array (); 
                                                              $category_tree1 = $objCrsMng1->get_all_categories_trees ( TRUE,$subclass1);
                                                              $i = 0;   $j = 0;   $o = array(); //标记循环变量， 数组 ;
                                                              foreach ( $category_tree1 as $category ) { ///父类循环
                                                                $url = "learning_before.php?id=".$value['id']."&category=" . $category ['id'];
                                                                  $cate_name = $category ['name'] . (($category_cnt [$category ['id']]) ? "&nbsp;(" . $category_cnt [$category ['id']] . ")" : "");
                                                                  if($category['parent_id']==0) {
                                                                  ?>
                                                                <a class="j-subit f-ib f-thide" href="<?=$url?>"><?=$cate_name?></a>
                                                                  <?php  if($i==3){$i=0;}
                                                                    }  
                                                                 }
                                                                  if(!$category_tree1){    
                                                                      echo "<p align='center'>没有相关课程分类，请联系课程管理员</p>";
                                                                  }
                                                                  ?>

                                                        </div>
                                                </div>

                                        </li>
                                        
                                       
                               <?php  }  ?>
                                <li class="navitm it f-f0 f-cb haschildren course-mess"  data-id="-1" data-name="课程表">
                                     <a class="f-thide f-f1" title="课程表" href="./syllabus.php">课程表</a>
                                </li>
                               </ul>
            </div>
            <div class="m-university u-categ f-cb" id="j-university">
                <div style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF">
                   <div class="bar f-cb">
                   <h3 class="f-thide f-f1">报告管理</h3>
                </div>
                <ul class="u-categ f-cb">
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的实验报告" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的实验报告" href="labs_report.php" >我的实验报告</a>
                    </li> 
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的实验图片录像" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的实验图片录像" href="course_snapshot_list.php" >我的实验图片录像</a>
                    </li>
                     <li class="navitm it f-f0 f-cb haschildren couse-mess" data-id="-1" data-name="系统公告" id="auto-id-D1Xl5FNIN6cSHqo0">
                         <a class="f-thide f-f1" title="系统公告" href="announcement.php" >系统公告</a>
                    </li>
                </ul>
               </div>
           </div>       
       </div>
 
    <div class="g-mn1" > 
    <div class="g-mn1c m-cnt" style="display:block;">
    <div class="j-list lists" id="j-list" style="margin-bottom:40px;clear:both;"> 
        <div class="userContent">
            <div class="j-tabs courseTabs">
                <a class="f-ib tab1 cur" id="learn-foot">学习足迹</a>
                <a class="f-ib tab2" id="login-foot" >登录足迹</a>
            </div>
            <div id="j-all-box">
            <div class='j-all-box m-person-course' id="learn-foot">
                <div class='m-data-lists f-cb f-pr j-data-list'>
                    <?php  
                   foreach ($course_vms  as  $v){    
                         //在学人数
                       $sql="select  `user_id`  from  `vmdisk_log`  where  `lesson_id`='".$v['lesson_id']."'"; 
                       $us=  api_sql_query_array_assoc($sql);
                       $uss='';
                       foreach ($us  as  $val){
                           $uss[]=$val['user_id'];
                       }
                       $user_count=count(array_unique($uss));
                       //course-picture
                       $c_cate=  Database::getval("select  `category_code`  from  `course`  where  `code`='".$v['lesson_id']."'");
                       $img=  Database::getval("select  `code`  from  `course_category` where  `id`=".$c_cate);
                       $sql_pic="select  `description9`  from  `course`  where  `code`='". $v['lesson_id']."'";
                        $pic=DATABASE::getval($sql_pic);
                        if($pic){
                            $course_pic=api_get_path ( SYS_PATH ).'storage/courses/'. $v['lesson_id'].'/'.  $pic; 
                            $file_exists=file_exists($course_pic);
                        }
                        if($pic  &&   $file_exists){
                          $imgpath=$course_pic; 
                        }else if($img  && file_exists('../../storage/category_pic/'.$img) ){  
                          $imgpath='../../storage/category_pic/'.$img;  
                        }else{
                           $imgpath= "../../portal/sp/images/default.png";
                        }
                    ?>
                    <div class='u-centerCourse f-cb first'> 
                        <div class='courseImg  f-pr'>
                            <img src='<?=$imgpath?>' class="j-info img">
                        </div>
                        <div class="m-course">
                            <div class='tit f-cb'>
                                <h4 class='j-info courseTit  f-thide'><?php  echo Database::getval("select  `title`  from  `course`  where  `code`=".$v['lesson_id']); ?></h4>
                            </div>
                            <ul class='j-info1'>
                                <li class='li-1 f-ib'>实验场景:  <span><?php  echo Database::getval("select  `name`  from  `vmdisk`  where  `id`=".$v['vmid']); ?></span> </li>
                                <li class='j-enroll li-1  f-ib'>
                                     <span class="numImg f-ib"></span>
                                     <span class="c-num0"><?=$user_count?>人在学</span>
                                </li>
                            </ul>
                            <div>
                                <div>
                                    <span class="sp">登录场景时间：<?php    echo  $v['start_time']; ?></span>
                                    <span class="sp">销毁场景时间：<?php    echo  $v['end_time']; ?></span>
                                    <span>销毁场景状态：<?php  if($v['close_status']==0){ echo "用户关闭";}else if($v['close_status']==1){echo "系统关闭";}else{echo "超时关闭";}  ?></span>
                                </div>
                            </div>
                            <!--<div class="j-join joinTime">2014年8月9日加入学习</div>-->
                        </div>
                    </div>
                    <?php   }  ?>
                </div>
                  
                <?php  
                showpage($pages,$page_url,$page); 
                ?>
            </div>
            <!--登录足迹-->
             <div class='j-all-box m-person-course' id="login-foot"  style="display:none;">
                <div class='m-data-lists f-cb f-pr j-data-list'>
                    <?php  
                    foreach ($login_foots as $value){         
                        $dates=  explode(" ", $value['login_date']);
                        $day=  explode("-", $dates[0]);
                         $str=convertip($value['login_ip']);  
                         $login_address= iconv('GBK','UTF-8',$str);
                    ?>
                    <div class="login-log">
                        <span class="login-time">
                            <?=$day[0]?>年<?=$day[1]?>月<?=$day[2]?>日 <?=$dates[1]?>
                        </span>
                        <span class="login-location">亲，您在<?=$login_address ?>登录</span>
                    </div>
                    <?php   } ?>        
                </div>
                
            </div>
                 <!--登录足迹结束-->
       </div>
     </div>
    </div>
    </div>
    </div>
    </div>
</div>
</div>
<?php 
//fen-ye
function showpage($pages,$page_url,$page){    
    if($pages=="NULL"){
        //echo "没有相关记录";
    }else{
        echo  ' <div class="j-data-pager">
                    <div class="ju-pager">';
        if($pages>10){
            echo "<a href=$page_url?page=1  class='zbtn zprv'> << </a>  ";
            if($page-1>0){
              echo "<a href=$page_url?page=".($page-1)." class='zbtn zprv'> < </a>  ";  
            }  
            $start=$page-5;
            $end=$page+5;          
            if($start >0 &&  $end<=$pages){
                for($j=$start;$j<$end;$j++){
                  echo "<a href=$page_url?page=$j   class='zpgi zpg1 ".($j==$_GET['page']?'selected':'')."'>".$j."</a>  ";  
                }
            }else if($start <=0){
                for($j=1;$j<11;$j++){
                  echo "<a href=$page_url?page=$j   class='zpgi zpg1 ".($j==$_GET['page']?'selected':'')."'>".$j."</a>  ";  
                }
            }else  if($end>$pages){
                 for($j=$pages-9;$j<=$pages;$j++){
                  echo "<a href=$page_url?page=$j  class='zpgi zpg1 ".($j==$_GET['page']?'selected':'')."'>".$j."</a>  ";  
                }
            } 
            if($page+1<$pages){
              echo "<a href=$page_url?page=".($page+1)." class='zbtn zprv'>  > </a>  ";  
            } 
           echo "<a href=$page_url?page=".$pages."  class='zbtn zprv'>  >> </a>      ";
         }else{
             echo "<a href=$page_url?page=1  class='zbtn zprv'>  << </a>  ";
             if($page-1>0){
                echo "<a href=$page_url?page=".($page-1)."   class='zbtn zprv'> < </a>  ";  
             }
             for($j=1;$j<=$pages;$j++){
                echo "<a href=$page_url?page=$j  class='zpgi zpg1 ".($j==$_GET['page']?'selected':'')."'>".$j."</a>  ";  
             }
             if($page+1<=$pages){
                echo "<a href=$page_url?page=".($page+1)."  class='zbtn zprv'> > </a>  ";  
             } 
             echo "<a href=$page_url?page=".$pages."  class='zbtn zprv'>  >> </a>      ";
         }
         echo '</div></div>';
      }
}

include './inc/page_footer.php';
?>
</body>
</html>
