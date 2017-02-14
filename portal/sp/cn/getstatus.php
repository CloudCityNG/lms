<?php
include_once ("../../../main/inc/global.inc.php");
$shengcun_sql=mysql_query("select * from cn_mation group by group_1");
while($shengcun_row=mysql_fetch_assoc($shengcun_sql)){
    $shengcun_rows[]=$shengcun_row;
}
$status_str='<tr></tr>
               <tr></tr>
               <tr></tr>
              <tr style="color:#ffffff;">
                <th>组号</th>
                <th>生存状态</th>
                <th>受保护服务器IP</th>
                <th>攻击机IP</th>
                <th>被攻击者组号</th>
                <th>被攻击者IP</th>
              </tr>';
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
       $status_str.='
              <tr>
                <td bgcolor="#FFFFFF"><b>'.$shengcun_v['group_1'].'</b></td>
                <td bgcolor="#FFFFFF"><div style="background-color:'.$stadtus_img.';width:120px;height:20px;display:inline-block;"></div></td>
                <td bgcolor="#FFFFFF"><b><p>'.$type_row['ip_2'].'</p></b></td>
                <td bgcolor="#FFFFFF"><b>'.$type_row['ip_1'].'</b></td>
                <td bgcolor="#FFFFFF"><b>'.$type_row['group_2'].'</b></td>
                <td bgcolor="#FFFFFF"><b>'.$type_row['ip_3'].'</b></td>
              </tr>';
   }
echo $status_str;exit;
