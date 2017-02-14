<?php
include_once ('../inc/global.inc.php');

$user_id=$_SESSION['_user']['user_id'];

if($user_id === null){
    header('Location:'.URL_APPEND.'portal/sp/login.php');
    exit;
}
$contest_query=mysql_query('select id,matchName from tbl_contest where status=1');
while($contest_row=mysql_fetch_row($contest_query)){
    $contest_rows[]=$contest_row;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CTF首页</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/base.css">
         <link rel="stylesheet" type="text/css" href="css/media-style.css">
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
        <style type="text/css">
            .font_class{
                text-shadow:1px 1px 2px #2c5103; color:#60ad0d;
            }
        </style>
    </head>
    <body>
          <div class="f-wrap">
             <div class="f-main">
                    <div class="c-contest">选择比赛</div>
                    <div class="sele-team contest-t">
                       <ul class="contest-ul">
                           <?php 
                                foreach($contest_rows as $contest_k=>$contest_v){
                            ?>
                           <li>
                               <a href="index.php?id=<?=$contest_v[0]?>"><?=$contest_v[1];?></a>
                           </li>
                           <?php        
}
?> 
                       </ul>
                         
                    </div>
             </div>
          </div>
    </body>
</html>