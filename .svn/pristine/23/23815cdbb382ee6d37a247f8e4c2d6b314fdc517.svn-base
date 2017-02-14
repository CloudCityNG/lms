<?php
include_once ("../../../main/inc/global.inc.php");
$shengcun_sql=mysql_query("select * from cn_mation where steal_key is not null and steal_flag is not null");
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
	<ul>
    	<li> <a href="index_admin.php">大赛简介</a></li>
        <li><a href="guize.php">大赛规则</a></li>
        <li><a href="tuopu.php">大赛拓扑</a></li>
        <li><a href="./public/index.html">态势展示</a></li>
        <li><a href="shengcun.php">生存状态</a></li>
        <li><a href="flags.php">FLAG状态</a></li>
        <li><a href="Results_list.php">实时成绩墙</a></li>
    </ul>	
	</div>
<div class="area">
	<H1>FLAG状态图</H1>
  <table  border="0" cellpadding="0" cellspacing="1" id="status_id">
  <tr>
    <th bgcolor="#FFFFFF">工位号</th>
    <th bgcolor="#FFFFFF">已获取FLAG</th>
    <th bgcolor="#CCCCCC">已获取KEY</th>
  </tr>
<?php
   foreach($shengcun_rows as $shengcun_k => $shengcun_v){
?>
  <tr>
    <td bgcolor="#FFFFFF"><?=$shengcun_v['job_id']?></td>
    <td bgcolor="#FFFFFF"><?=$shengcun_v['steal_key']?></td>
    <td bgcolor="#FFFFFF"><?=$shengcun_v['steal_flag']?></td>
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
           url:'./get_flag.php',
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
