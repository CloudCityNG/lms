<?php
include_once ('../inc/global.inc.php');

$team_query=mysql_query('SELECT `id`,`teamName` FROM `tbl_team`,`user` WHERE `tbl_team`.`teamAdmin`=`user`.`user_id`');
while($team_row=mysql_fetch_assoc($team_query)){
    $fra_query=mysql_query('SELECT `fraction` FROM `tbl_match`,`tbl_event` WHERE `tbl_match`.`event_id`=`tbl_event`.`id` AND `tbl_event`.`matchId`='.$_SESSION['tbl_contest'].' AND `gid`='.$team_row['id'].' AND `Even_tion`=1 GROUP BY `tbl_match`.`event_id`');
    while($fra_row=mysql_fetch_row($fra_query)){
        $fra_rows[]=$fra_row;
    }

    $fra_count=null;
    foreach($fra_rows as $fra_k=>$fra_v){
        $fraction=intval($fra_v[0]);
        $fra_count+=$fraction;
        //array_multisort($fra_count);
    }
    $team_row['score'] =$fra_count?$fra_count:0;
    $team_rows[]=$team_row;

}
foreach ($team_rows as $key=>$value){
    $score[$key]=$value['score'];
}
array_multisort($score,SORT_DESC,$team_rows);
$event_query=mysql_query('SELECT `tbl_event`.`id`,`tbl_exam`.`exam_Name` FROM `tbl_event`,`tbl_exam` WHERE `tbl_event`.`matchId`='.$_SESSION['tbl_contest'].' AND `eventState`=1 AND `tbl_event`.`examId`=`tbl_exam`.`id`');
while($event_row=mysql_fetch_row($event_query)){
    $event_list[]=$event_row;
}

function event_fun($id){
     $fra_query=mysql_query('select fraction from tbl_match,tbl_event where tbl_match.event_id=tbl_event.id and tbl_event.matchId='.$_SESSION['tbl_contest'].' and gid='.$id.' and Even_tion=1 group by tbl_match.event_id');
     while($fra_row=mysql_fetch_row($fra_query)){
           $fra_rows[]=$fra_row;
     }
     foreach($fra_rows as $fra_k=>$fra_v){
         $fraction=intval($fra_v[0]);
         $fra_count+=$fraction;
     }

     if($fra_count){
          return $fra_count;
     }else{
          return 0;
     }

}

function fra_list($event_id,$gid){
    $query=mysql_query('select id from tbl_match where event_id='.$event_id.' and gid='.$gid.' and Even_tion=1');
    $event=mysql_fetch_row($query);
    if($event[0]){
        return true;
    }else{
        return false;
    }
}
?>
 <tr>
     <th style="width:150px;">名称</th>
     <th style="width:60px;">人数</th>
     <th style="width:100px;">得分</th>
    <th>解题情况</th>
</tr>
<?php
   foreach($team_rows as $team_k => $team_v){
?>
                                            <tr>
                                                <td class="t-score"><?=$team_v['teamName'];?></td>
                                                <td>
<?php
           $user_count_arr=mysql_fetch_row(mysql_query('select count(user_id) from user where teamId='.$team_v['id']));
           echo $user_count_arr[0];
?>
                                                </td>

                                                <td class="t-score"><?=event_fun($team_v['id']);?></td>
                                                <td>
                           <?php
                                foreach($event_list as $event_k=>$event_v){
                                    $true_or_false=fra_list($event_v[0],$team_v['id']);
                                    if($true_or_false){
                           ?>
                                                    <span id="<?php echo $event_v[0],$team_v['id'];?>span" title="<?=$event_v[1]?>" class="t-green"></span>
                                    <?php }else{?>
                                                    <span id="<?php echo $event_v[0],$team_v['id'];?>span" title="<?=$event_v[1]?>" class="t-gray"></span>
                           <?php
                                   }
                                }
                           ?>
                                                </td>
                                            </tr>
<?php
    }
?>