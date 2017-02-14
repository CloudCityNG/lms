<?php
$cidReset = true;
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header ( "Location: ./login.php" );
}
include_once ("inc/page_header.php");
$user_id=api_get_user_id ();  

 $total_rows = DATABASE::getval("SELECT  count(`login_ip`)   FROM `track_e_login` where  `login_user_id`=".$user_id );
$url = 'login_print.php';
$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );

//登录足迹
$sql="SELECT  `login_date`,`login_ip`  FROM `track_e_login`  where  login_user_id=".$user_id."   order  by  `login_date`  desc  ";
$offset = (int)getgpc ( "offset", "G" );
if (empty ( $offset )) $offset = 0;
$sql .= " LIMIT " . $offset . "," . NUMBER_PAGE;
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
     color:#357CD2;
}
.page .page-list li a:hover{
	background:#357CD2;
}
.page .page-list li.la a{
	background:#357CD2;
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
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的岗位" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的岗位" href="my_post.php">我的岗位</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="最新动态" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="最新动态" href="c_trends.php">最新动态</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的消息" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的消息" href="my_voice.php">我的消息</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的赛场" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的赛场" href="my_contest.php">我的赛场</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的考勤" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的考勤" href="work_attendance.php">我的考勤</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="学习足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="学习足迹" href="footprint.php">学习足迹</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="登录足迹" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="登录足迹" href="login_print.php"  style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>color:#FFF">登录足迹</a>
                    </li>
                    <li class="navitm it f-f0 f-cb haschildren" data-id="-1" data-name="我的报告" id="auto-id-D1Xl5FNIN6cSHqo0">
                        <a class="f-thide f-f1" title="我的报告" href="labs_report.php">我的报告</a>
                    </li>
                    
                </ul>
                 
            </div>
            
       </div>
 
    <div class="g-mn1" > 
    <div class="g-mn1c m-cnt" style="display:block;">
    <div class="j-list lists" id="j-list" style="margin-bottom:40px;clear:both;"> 
        <div class="userContent">
            <div id="j-all-box">
                <div class="tabarea j-tabs">
                   <a class="a-tab">您经历过的地方</a>
               </div>
       
            <!--登录足迹-->
             <div class='j-all-box m-person-course' id="login-foot"  >
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
            <div class="page">
               <ul class="page-list">
                   <li class="page-num">总计<?=$total_rows?>个课程</li>
                   <?php
                   echo $pagination->create_links ();
                   ?>
               </ul>
           </div>
       </div>
     </div>
    </div>
    </div>
    </div>
    </div>
</div>
</div>
<?php 
include './inc/page_footer.php';
?>
</body>
</html>
