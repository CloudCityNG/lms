<?php
include_once ('../inc/global.inc.php');
$gid=getgpc('id','G');
$match_query=mysql_query('select matchRewad from tbl_contest where id='.$gid);
$match_row=mysql_fetch_row($match_query);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CTF首页</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel="stylesheet" type="text/css" href="css/base.css">
         <link rel="stylesheet" type="text/css" href="css/media-style.css">
    </head>
    <body>        
<?php 
                   include_once 'left_header.php';
?>                  
                   <!--左侧结束-->
                   <!--右侧-->
                   
                   <div class="g-mn1">
                       <div class="g-mn1c">
                           <div class="j-list lists">
                                <h3 class="b-title">大赛规则</h3>
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
