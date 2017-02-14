<?php
include_once ("../../main/inc/global.inc.php");
$pageid = intval($_POST['pageid']);
$id = intval($_POST['id']);
$cid = intval($_POST['cid']);
$offset = ($pageid-1)*5;
$query = mysql_query('select id,uid,text,comtime from Comment where cid='.$cid.' and fid='.$id.' and fid<>0 and state=1 order by id asc limit '.$offset.',5');
while ($row = mysql_fetch_assoc($query))
{
            $result = mysql_query('select username,picture_uri from user where user_id='.$row['uid']);
           $userarr = mysql_fetch_row($result);
          $userpath = api_get_path ( WEB_PATH ) . 'storage/users_picture/';
            $depath = api_get_path ( WEB_PATH ) . 'themes/img/home_default_logo.jpg';
           $imgpath = $userarr[1] ? $userpath.$userarr[1] : $depath;
   $row['username'] = $userarr[0];
    $row['imgpath'] = $imgpath;
              $text = stripslashes(htmlspecialchars_decode($row['text']));
              $time = date('Y-m-d H:i:s',strtotime($row['comtime']));
       $row['text'] = $text;
       $row['time'] = $time;
       $rows[]=$row;
}
if(count($rows))
{
    echo json_encode($rows);
}
?>
