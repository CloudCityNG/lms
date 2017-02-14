<?php
$cidReset = true;
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
include_once ("inc/app.inc.php");
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
include_once './inc/page_header.php';
$user_id = api_get_user_id ();   
//最新动态
 $sql="select  `course_code`,`user_id`,`creation_time`  from `course_rel_user`  order by creation_time  desc  limit  20";  
 $news=  api_sql_query_array_assoc($sql);
 //学员分布
 $sql="SELECT   `login_ip`  FROM `track_e_login`  ";
$stu_add=  api_sql_query_array_assoc($sql);
$stu_address='';
foreach ($stu_add as $va){
    $stu_address[]=$va['login_ip'];
}
$ads=array_unique($stu_address); 
$student_add='';
foreach ($ads as $valu){
     $str=convertip($valu);  
     $login_address= iconv('GBK','UTF-8',$str);
    $student_add[]=$login_address;
} 
$addss=array_unique($student_add); 
$student_address='';
foreach ($addss as $valuee){
    $student_address[]=$valuee;
}

//address
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

    <!-- 导航结束 -->
              <?php   if(api_get_setting ( 'lm_switch' ) == 'true'){   ?>
  <style>
.news #news-title{
    border-bottom:3px solid #357cd2; 
}
#stu_map{
    color: #333333;
    border-bottom: 3px solid #357cd2;
   }
  </style>
      <?php   }   ?> 
     <div class="clear"></div> 
	<div class="m-moclist">
	    <div class="g-flow" id="j-find-main"> 
                <div class="b-30"></div>
                <div class="g-container f-cb" style="background:#fff;">
             <!--左侧开始-->
                    <div class="news">
                        <div class="w-new">
                        <h3 id="news-title">最新动态</h3>
                        <ul class="trends-list">
                            <?php 
                            foreach ($news as $value){
                                $uname=Database::getval("select  username  from  user  where user_id='".$value['user_id']."'");
                                $course_name=Database::getval("select  title  from  course where code='".$value['course_code']."'");
                                $img_attributes = get_user_picture ( $value['user_id'] );
                            ?>
                            <li>
                                <div class="Dynamic">
                                    <div class="dyna_left"> 
                                        <img <?=$img_attributes?>  class="head"/>
                                    </div>
                                    <div class="dyna_right">
                                        <p class="user-course">
                                            <a class="u-name"><?=$uname ?></a> 开始《<?=substr($course_name,11) ?>》实验。
                                        </p>
                                        <span><font><?=$value['creation_time']?></font></span>
                                    </div>
                                </div>
                            </li>
                            <?php  } ?>
                        </ul>
                    </div>  
		</div>
                  <!--左侧结束-->
             <!--右侧开始-->
               <div class='case'>
                     <div class="w-new">
                          <h3 class="tab-hd">
                              <span id="stu_map">学员分布</span>
                           <?php  if(count($student_address)>20){ ?>   <a href='case-more.php' class='case-more'>更多</a><?php } ?>
                          </h3>
                         <ul class='layout-t'>
                             <?php  
                                foreach ($student_address as $key=>$v){   
                                    if($key<20){
                             ?>
                         <li><?=$v?></li>
                       <?php             } 
                                     } 
                       ?>
                         </ul>
                     </div>
               </div>
              <!--右侧结束-->  
	    </div>
     </div>
	<!-- 底部 -->
<?php
        include_once './inc/page_footer.php';
?>
        
<div id="kecheng">
            <div class="tag-title">
                <div id="kecheng-2"></div>
                <div id="chahao">×</div>
            </div>
            <div id="kecheng-3"></div>
</div>       
 </body>
</html>
<style>
    body{
        font-size:14px;
    }
    #kecheng{
        -webkit-transition:all .2s ease;
        width:60%;
        padding-bottom:10px;
        background:#fff;
        position:fixed;
        z-index:999;                              
        left:80%;
        border:1px solid #13A654;
        display:none;
        height:300px;
    } 
    .tag-title{
      height:50px;
      width:100%;
      background:#13A654;
      color:#fff;
    }
    #kecheng-2{
        float:left;
        line-height:50px;
        font-size:20px;
        margin-left:10px;
    }
    #chahao{
        float:right;
       width:20px;
       height:20px;
        cursor:pointer;
       font-size:20px;
       line-height:20px; 
    }
    #chahao:hover{
        color:#FF6600;
        cursor:pointer;
    }
</style>
<script type="text/javascript">
    var settimes=1;
               $(function(){
                   $('#chahao').click(function(){
                       $("#kecheng").css('display','none');
                       $("#kecheng").css('-webkit-transform','translateX(0%)');
                    $("#kecheng").css('-moz-transform','translateX(0%)');
                    $("#kecheng").css('-o-transform','translateX(0%)');
                    settimes=1;
                    var gorecode=$("#goreferen").val();
                    if(gorecode){
                      $("#code"+gorecode).css('color','red');  
                      $("#code"+gorecode).html('已选修');
                    }
                   });
               });
                
   function kecheng(code,title){
       $.ajax({
           url:'course_info.php',
          type:'GET',
          data:'code='+code,
      dataType:'html',
      success:function(er){
           if(er === 'err'){
             location.href='login.php';  
           }else{
            var bht=document.documentElement.clientHeight;
            var Hkecheng=$("#kecheng").height();
            var ter=/firefox/;
                   //alert(navigator.userAgent.toLowerCase());
                  if(ter.test(navigator.userAgent.toLowerCase())){
                      itop=document.documentElement.scrollTop;  
                      }     
                  if("ActiveXObject" in window){
                      $("#kecheng").css('left','20%');
                      itop=document.documentElement.scrollTop;
                   }  
               
                    var zht=bht/2-Hkecheng/2;
                    $("#kecheng").css('top',zht);
                    $("#kecheng").css('display','block');
                    $("#kecheng-2").html(title);
                    $("#kecheng-3").html(er);
           
    
                     if(settimes === 1){
                    $("#kecheng").css('-webkit-transform','translateX(-140%)');
                    $("#kecheng").css('-moz-transform','translateX(-140%)');
                    $("#kecheng").css('-o-transform','translateX(-140%)');
                   
                    setTimeout(function(){
                    $("#kecheng").css('-webkit-transform','translateX(-100%)');
                    $("#kecheng").css('-moz-transform','translateX(-100%)');
                    $("#kecheng").css('-o-transform','translateX(-100%)');
                     settimes++;
                    },200);
                   }else{
                    $("#kecheng").css('-webkit-transform','translateX(-100%)');
                    $("#kecheng").css('-moz-transform','translateX(-100%)');
                    $("#kecheng").css('-o-transform','translateX(-100%)');   
                   }
           }
      }       
       });
   }
   function divshow(id){
       $(".module_content").css('display','none');
       $("#div"+id).css('display','block');
   }
</script>

