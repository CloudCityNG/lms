<?php
include_once ("../../../main/inc/global.inc.php");
$shengcun_sql=mysql_query("select * from cn_mation");
while($shengcun_row=mysql_fetch_assoc($shengcun_sql)){
    $shengcun_rows[$shengcun_row['group_1']][]=$shengcun_row;
}

foreach($shengcun_rows as $shengcun_k => $shengcun_v){
      $count_k=0;$count_f=0;$sum_k=0;$sum_f=0;
      foreach($shengcun_v as $shengcun_v_k => $shengcun_v_v){
          if($shengcun_v_v['type'] == 1 && !empty($shengcun_v_v['Fraction'])){
              $count_k += $shengcun_v_v['Fraction'];
              $sum_k++;
          }else if($shengcun_v_v['type'] == 2 && !empty($shengcun_v_v['Fraction'])){
              $count_f += $shengcun_v_v['Fraction'];
              $sum_f++;
          }
      }
    if($count_k > 100){
        $count_k = 100;
    }
    if($count_f > 300){
        $count_f = 300;
    }
    $result_list[$shengcun_k]['count_k'] = $count_k;
    $result_list[$shengcun_k]['count_f'] = $count_f;
    $result_list[$shengcun_k]['sum_k'] = $sum_k;
    $result_list[$shengcun_k]['sum_f'] = $sum_f;

    $result_list[$shengcun_k]['count_sum'] = $count_k + $count_f;
}

function arr_sort($array,$key,$order="desc"){//asc是升序 desc是降序

         $arr_nums=$arr=array();

         foreach($array as $k=>$v){
            $arr_nums[$k]=$v[$key];
         }

        if($order=='asc'){
          asort($arr_nums);
        }else{
          arsort($arr_nums);
        }

        foreach($arr_nums as $k=>$v){
             $arr[$k]=$array[$k];
        }

        return $arr;
}
$count_list=arr_sort($result_list,'count_sum');
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
	<H1>大赛成绩墙</H1>
  <table  border="0" cellpadding="0" cellspacing="1" id="status_id" style="text-align: center;">
  <tr style="color:#ffffff;">
    <th>排名</th>
    <th>组号</th>
    <th>已获取KEY</th>
    <th>获取KEY数得分</th>
    <th>已获取FLAG</th>
    <th>获取FLAG数得分</th>
    <th>总得分</th>
  </tr>
<?php
    $i=1;
if(count($shengcun_rows)) {
    foreach ($count_list as $count_list_k => $count_list_v) {
        ?>
        <tr>
            <td bgcolor="#FFFFFF"><b><?= ($i++) ?></b></td>
            <td bgcolor="#FFFFFF"><b><?= $count_list_k ?></b></td>
            <td bgcolor="#FFFFFF"><b><?= $count_list_v['sum_k'] ?>个</b></td>
            <td bgcolor="#FFFFFF"><b><?= $count_list_v['count_k'] ?>分</b></td>
            <td bgcolor="#FFFFFF"><b><?= $count_list_v['sum_f'] ?>个</b></td>
            <td bgcolor="#FFFFFF"><b><?= $count_list_v['count_f'] ?>分</b></td>
            <td bgcolor="#FFFFFF"><b><?= $count_list_v['count_sum'] ?>分</b></td>
        </tr>
    <?php
    }
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
           url:'./get_results.php',
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
