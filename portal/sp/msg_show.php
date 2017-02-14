<script>
function yesno(){
    //alert("请输入发送内容！");
    if(fr.text.value==""){
        alert("请输入发送内容！");
        fr.text.focus();
        return false;
    }
    
}
</script>

<?php
include_once ("../../main/inc/global.inc.php");
$created_user=getgpc ( "created_user" );
$recipient=getgpc ( "recipient" );
$text=getgpc ( "text" );
$usid=api_get_user_id ();

//$use_id=api_get_user_id();


if(isset($recipient) and $text!=""){
    $sql="INSERT INTO `message`(`content`, `created_user`, `status`, `recipient`) VALUES ('$text',$created_user,0,$recipient)";
    api_sql_query ( $sql, __FILE__, __LINE__ );
    tb_close();
    
}

$upsql="update  message set status =1 where  recipient =$usid and created_user=$created_user";

api_sql_query ( $upsql, __FILE__, __LINE__ );

$sql="select * from message where (created_user=$created_user and recipient=$usid) or (created_user=$usid and recipient=$created_user) order by date_start";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$msg=array();
$ress=array();
while ( $ress = Database::fetch_array ( $res ) ) {
    $msg[]=$ress;
    
}


?>
      <?php  if(api_get_setting ( 'lm_switch' ) == 'true'){ ?>
<style>
    .box{
        width: 98%;
        height: 75%;
        border: 1px solid #C5C5C5;
        box-shadow: 0 1px 6px #999;
        overflow: auto;
        background:#fff;
        margin-bottom:10px;
        border-radius:5px;
    }
    .msg{
        margin-bottom: 5px;
        height: 50px;
    }
    .msg:nth-child(odd){
        background:#fafafa;
        border-top:1px dotted #ccc;
        border-bottom:1px dotted #ccc;
    }
    .sp{
        margin-left:10px;
        color:#357cd2;
        font-weight:bold;
    }
    .msg pre{
        margin-left:25px;
    }
    .sp1{
         color:#357cd2;
         margin-left:10px;
         font-weight:bold;
    }
    .send{
        width:70px;
        height:30px;
        line-height:30px;
        color:#fff;
        background:#357cd2;
        border-radius:5px;
        outline:0;
        border:0;
    }
</style>
      <?php   }else{   ?> 
<style>
    .box{
        width: 98%;
        height: 75%;
        border: 1px solid #C5C5C5;
        box-shadow: 0 1px 6px #999;
        overflow: auto;
        background:#fff;
        margin-bottom:10px;
        border-radius:5px;
    }
    .msg{
        margin-bottom: 5px;
        height: 50px;
    }
    .msg:nth-child(odd){
        background:#fafafa;
        border-top:1px dotted #ccc;
        border-bottom:1px dotted #ccc;
    }
    .sp{
        margin-left:10px;
        color:#13a654;
        font-weight:bold;
    }
    .msg pre{
        margin-left:25px;
    }
    .sp1{
         color:#13a654;
         margin-left:10px;
         font-weight:bold;
    }
    .send{
        width:70px;
        height:30px;
        line-height:30px;
        color:#fff;
        background:#357cd2;
        border-radius:5px;
        outline:0;
        border:0;
    }
</style>
      <?php   }   ?> 
<div class="box">
    <?php foreach ($msg as $key => $value) {
      $firsname=Database::getval("select `firstname` from `user` where `user_id`=".$value['created_user']);  
    ?>
    <?php if($value['created_user']==$created_user){?>
    <div class="msg">
        <span class="sp"><?php echo $firsname;?>&nbsp;<?php echo $value['date_start'];?></span><br>
        <pre><?php echo $value['content'];?></pre>
    </div>
  
    <?php }elseif ($value['created_user']==$usid) {?>
    <div class="msg">
        <span class="sp1"><?php echo $firsname;?>&nbsp;<?php echo $value['date_start'];?></span><br>
        <pre><?php echo $value['content'];?></pre>
    </div>
    <?php }?>
    <?php }?>
    
    
</div>

<div>
   <form action="msg_show.php" method="post" onsubmit="return yesno()" name="fr">
       <input type="hidden" name="recipient" value="<?php echo $created_user;?>">
       <input type="hidden" name="created_user" value="<?php echo $usid;?>">
      <textarea name="text" cols="128" rows="7"></textarea> <br>
       <input type="submit" value="发送" class="send">
   </form>
    
</div>