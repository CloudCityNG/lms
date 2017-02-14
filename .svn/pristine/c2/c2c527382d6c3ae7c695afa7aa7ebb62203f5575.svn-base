<?php
include_once ('../inc/global.inc.php');

$team_query=mysql_query('SELECT `id`,`teamName` FROM `tbl_team`,`user` WHERE `tbl_team`.`teamAdmin`=`user`.`user_id`');
while($team_row=mysql_fetch_assoc($team_query)){
   $fra_query=mysql_query('SELECT `fraction` FROM `tbl_match`,`tbl_event` WHERE `tbl_match`.`event_id`=`tbl_event`.`id` AND `tbl_event`.`matchId`='.$_SESSION['tbl_contest'].' AND `gid`='.$team_row['id'].' AND `Even_tion`=1 GROUP BY `tbl_match`.`event_id`');
    $fra_rows=array();
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
//function event_fun($id){
//    $fra_query=mysql_query('SELECT `fraction` FROM `tbl_match`,`tbl_event` WHERE `tbl_match`.`event_id`=`tbl_event`.`id` AND `tbl_event`.`matchId`='.$_SESSION['tbl_contest'].' AND `gid`='.$id.' AND `Even_tion`=1 GROUP BY `tbl_match`.`event_id`');
//    while($fra_row=mysql_fetch_row($fra_query)){
//        $fra_rows[]=$fra_row;
//    }
//
//    $fra_count=null;
//    foreach($fra_rows as $fra_k=>$fra_v){
//        $fraction=intval($fra_v[0]);
//        $fra_count+=$fraction;
//    }
//
//    if($fra_count){
//        return $fra_count;
//    }else{
//        return 0;
//    }
//
//}

function fra_list($event_id,$gid){
    $query=mysql_query('SELECT `id` FROM `tbl_match` WHERE `event_id`='.$event_id.' AND `gid`='.$gid.' AND `Even_tion`=1');
    $event=mysql_fetch_row($query);
    if($event[0]){
        return true;
    }else{
        return false;
    }
}
?>
<!DOCTYPE html>
<html>
<head>

    <title>云教育资源平台_51CTF_积分榜</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="description" content="关于网络安全知识方面的在线考题竞赛平台实现以战队与个人形式进行在线答题比赛平台的积分榜，该平台依托于云资源教育平台">
    <meta name="keywords" content="云教育资源平台，网络安全教育学习，计算机教育学习，网络安全知识竞赛比赛">
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/media-style.css">
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
</head>
<body>
<?php
include_once 'left_header.php';
?>
<!--左侧结束-->
<!--右侧-->
<div class="g-mn1">
    <div class="g-mn1c">
        <!--题目-->
        <div class="j-list lists">
            <h3 class="b-title">积分榜
                <!--                                   <span class="b-tips"></span>-->
            </h3>
            <div class="b-15"></div>
            <div class="b-course b-width">
                <table cellspacing="0" border="0" width="100%" id="tab"  class="tbl_score" style="min-width:980px;">
                    <tbody id="tbody">
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
                                $user_count_arr=mysql_fetch_row(mysql_query('SELECT count(user_id) FROM `user` WHERE `teamId`='.$team_v['id']));
                                echo $user_count_arr[0];
                                ?>
                            </td>
                            <td class="t-score"><?=$team_v['score'];?></td>
                            <td class="t-circle">
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
                    </tbody>
                </table>
            </div>
        </div>
        <!--题目页面结束-->
    </div>
</div>
<!--右侧结束-->
</div>
</dv>
</section>
</body>
</html>
<script type="text/javascript">
    $(function(){
        setInterval('loop_fun()',600000);
    });
    function loop_fun(){
        $.ajax({
            url:'score_list.php',
            dataType:'html',
            type:'POST',
            success:function(er){
                $("#tbody").html(er);
            }
        });
    }
    $(function(){
        Tab();
    })
    function Tab(){
        var tr=$("#tab").find("tr");
        var tr_l=tr.length;
        var lastTD=$("#tab").find(".t-circle");
        var Span=$("#tab").find(".t-circle").find("span");
        var every=(Span.length)/(tr_l-1);
        var count=every*25;
        var Allw=count+310;
        $("#tab").width(Allw);
    }
</script>