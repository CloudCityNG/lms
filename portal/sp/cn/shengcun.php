<?php
include_once ("../../../main/inc/global.inc.php");
$shengcun_sql=mysql_query("select * from cn_mation group by group_1");
while($shengcun_row=mysql_fetch_assoc($shengcun_sql)){
    $shengcun_rows[]=$shengcun_row;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../css/css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
</head>

<body  bgcolor="#1c2d7d">
<div class="top_index">
  <ul><a href="#" class="f20">退出</a></ul>
</div>
<div class="mean">
	<div class="mean_left">
        <?php include 'left_link.php';?>
	</div>
<div class="area">
	<H1>生存状态图</H1>
  <table  border="0" cellpadding="0" cellspacing="1" id="status_id" style="text-align: center;">
  <tr></tr>
  <tr></tr>
  <tr></tr>
  <tr style="color:#ffffff;">
    <th>组号</th>
    <th>生存状态</th>
    <th>受保护服务器IP</th>
    <th>攻击机IP</th>
    <th>被攻击者组号</th>
    <th>被攻击者IP</th>
  </tr>
<?php
   foreach($shengcun_rows as $shengcun_k => $shengcun_v){
       $type_row['id']=null;
       $type_query=mysql_query('select * from cn_mation where group_1="'.$shengcun_v['group_1'].'" and type=2 and  Fraction is not null limit 1');
       $type_row=mysql_fetch_assoc($type_query);
       if($type_row['id']){
           $stadtus_img = 'red';
       }else{
           $type_query2=mysql_query('select * from cn_mation where group_1="'.$shengcun_v['group_1'].'" and type=1 and  Fraction is not null order by id desc limit 1');
           $type_row=mysql_fetch_assoc($type_query2);
           $stadtus_img = 'green';
       }
?>
           <tr>
               <td bgcolor="#FFFFFF"><b><?= $shengcun_v['group_1'] ?></b></td>
               <td bgcolor="#FFFFFF"><div style="background-color:<?=$stadtus_img;?>;width:120px;height:20px;display:inline-block;"></div></td>
               <td bgcolor="#FFFFFF"><b><p><?= $type_row['ip_2'] ?></p></b></td>
               <td bgcolor="#FFFFFF"><b><?= $type_row['ip_1'] ?></b></td>
               <td bgcolor="#FFFFFF"><b><?= $type_row['group_2'] ?></b></td>
               <td bgcolor="#FFFFFF"><b><?= $type_row['ip_3'] ?></b></td>
           </tr>
<?php
       }
?>
  </table>
</div>
<script type="text/javascript">
    $(function(){
       setInterval(getStatus,5000);
    });
    function getStatus(){
        $.ajax({
           url:'./getstatus.php',
        dataType:'html',
        success:function(data){
         $("#status_id").html(data);
        }
        });
    }
</script>

</div>
</body>
</html>
