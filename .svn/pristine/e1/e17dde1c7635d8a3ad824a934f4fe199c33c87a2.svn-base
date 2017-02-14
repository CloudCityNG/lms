<?php
include_once ("../../../main/inc/global.inc.php");
$shengcun_sql=mysql_query("select * from cn_mation where steal_key is not null and steal_flag is not null");
while($shengcun_row=mysql_fetch_assoc($shengcun_sql)){
    $shengcun_rows[]=$shengcun_row;
}
 $status_str='<tr>
                <th bgcolor="#FFFFFF">工位号</th>
                <th bgcolor="#FFFFFF">已获取FLAG</th>
                <th bgcolor="#CCCCCC">已获取KEY</th>
             </tr>';
 foreach($shengcun_rows as $shengcun_k => $shengcun_v){
       $status_str.='<tr>
                      <td bgcolor="#FFFFFF">'.$shengcun_v['job_id'].'</td>
                      <td bgcolor="#FFFFFF">'.$shengcun_v['steal_key'].'</td>
                      <td bgcolor="#FFFFFF">'.$shengcun_v['steal_flag'].'</td>
                     </tr>';
   }
echo $status_str;exit;