<?php
include_once ("../../main/inc/global.inc.php");
$val = htmlspecialchars( $_POST['val'] );
$valarr = explode('&', $val);
$fidarr = explode('=',$valarr[0]);
$fid = intval( $fidarr[1] );
$cidarr= explode("=",$valarr[1]);
$cid = intval( $cidarr[1] );
$ffidarr = explode("=",$valarr[2]);
$ffid = intval( $ffidarr[1] );
$text = $_POST['text'];
$text = str_replace("&ltimg","<img",$text);
$text = str_replace("&gt",">", $text);
$text = htmlspecialchars($text);
if(!$text){
    $errarr=array('err' => $text);
    echo json_encode($errarr);exit;
}else{
   $user_id = $_SESSION['_user']['user_id'];
   $timenow = date('YmdHis',time());
   $ffid = $ffid ? $ffid : 0;
   $state = 1;
   $query = mysql_query("insert Comment(cid,fid,ffid,text,uid,comtime,state)values($cid,$fid,$ffid,'$text',$user_id,$timenow,$state)");
   if($query){
       $myid = mysql_insert_id();
       $cotext = stripslashes(htmlspecialchars_decode($text));
       $upath = $_SESSION['_user']['picture_uri'];
       $upathig = $upath ? api_get_path ( WEB_PATH ) . 'storage/users_picture/'.$upath : api_get_path ( WEB_PATH ) . 'themes/img/home_default_logo.jpg';
       $time = date('Y-m-d H:i:s',strtotime($timenow));

       $arr = array(
           'id' => $myid,
           'username' => $_SESSION['_user']['username'],
           'url' => $upathig,
           'text' => $cotext,
           'time' => $time,
           'fid' => $fid,
           'err' => 'ok'
           );

       echo json_encode($arr);
   }
}
?>
