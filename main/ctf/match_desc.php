<?php
include_once ('../inc/global.inc.php');
$gid=intval(getgpc('id','G'));
$match_query=mysql_query('select matchDesc from tbl_contest where id='.$gid);
$match_row=mysql_fetch_row($match_query);

?>
<!DOCTYPE html>
<html>
    <head>
          <title>云教育资源平台_51CTF_大赛简介</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="关于网络安全知识方面的在线考题竞赛平台实现以战队与个人形式进行在线答题比赛平台的大赛简介页面，该平台依托于云资源教育平台">
        <meta name="keywords" content="云教育资源平台，网络安全教育学习，计算机教育学习，网络安全知识竞赛比赛">
         <link rel="stylesheet" type="text/css" href="css/base.css">
         <link rel="stylesheet" type="text/css" href="css/media-style.css">
    </head>
    <body>
                   <!--侧边栏-->
<?php 
                   include_once 'left_header.php';
?>
                   <!--左侧结束-->
                   <!--右侧-->
                   
                   <div class="g-mn1">
                       <div class="g-mn1c">
                           <div class="j-list lists">
                                <h3 class="b-title">公告</h3>
                                <div class="b-15"></div>
                                <div class="match-con">
                                    <?php echo htmlspecialchars_decode($match_row[0]);?>
                                </div>
                           </div>                       
                       </div>
                   </div>
                 <!--右侧结束-->  
                   </div>
               </dv>
        </section>
    </body>
</html>
