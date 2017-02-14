<?php
header("Content-type:text/html;charset=utf-8");
include ('../inc/global.inc.php');

$res=api_sql_query ( $sql, __FILE__, __LINE__ );

$m_table = Database::get_main_table ( 'tbl_match' );
$u_table = Database::get_main_table ( 'user' );
$e_table = Database::get_main_table ( 'tbl_exam' );
$ev_table = Database::get_main_table ( 'tbl_event' );
//获取题目的分类ID
$cid=$_GET['classId'];
//$page=$_GET['page'];//页码
$page=1;
$pageSize=10;

//获取用户id 先假设=1

$uid=$_SESSION['_user']['user_id'];

//if($uid){
//    echo $uid;
//}else{
//    echo '跳转登录页面';
//}

//根据用户id，查出战队id
function getTidByUid($userId){
    global $u_table;
        $sql ="SELECT teamId FROM $u_table WHERE user_id=".$userId;
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        $row = mysql_fetch_assoc($res);
        return $row['teamId']; 
}
$teamId=getTidByUid($uid);

//根据分类id获取 题目以及对应id,返回2维数组
function getExamByCid($classId){
     global $e_table;
     $sql = "SELECT id,exam_Name FROM $e_table WHERE classId = $classId";
     $res = api_sql_query ( $sql, __FILE__, __LINE__ );
     while($row = mysql_fetch_assoc($res)){
         $rows[]=$row;
     }
     return $rows;
}

function getEventIdByQid($qid){
    global $ev_table;
    $sql='SELECT id FROM '.$ev_table.' WHERE examId='.$qid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row['id']; 
}


/*
//查出状态,返回二位数组
function getExamStateByIds($examId,$teamId){
    global $m_table;
    $sql = 'SELECT state FROM '.$m_table.' WHERE gid='.$teamId.' AND event_id='.$examId;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    while($row = mysql_fetch_assoc($res)){
         $rows[]=$row;
     }
     return $rows;  
}
*/

//检查 用户是否打了这道题
function is_has_answer($uid,$qid){
    global $m_table;
    $sql= 'SELECT eTime FROM '.$m_table.' WHERE event_id='.$qid. ' AND user_id='.$uid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row['eTime'];  
}

//返回二位数组,返回int
function getExamStateByIds($examId,$teamId){
    global $m_table;
    $sql = 'SELECT state FROM '.$m_table.' WHERE gid='.$teamId.' AND event_id='.$examId;

    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row['state']; 
}

//获取所需二维数组
function get_need_arr($classId){
    global $teamId;//int
    $examInfo=getExamByCid($classId); //二维数组--根据分类id获取 题目以及对应id,返回2维数组
    foreach($examInfo as $k=>$v){
        $state=getExamStateByIds(getEventIdByQid($v['id']),$teamId);
        $v['state']=$state;
        $arr[]= $v;
    }
    return $arr;
}

$arr=get_need_arr($cid);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>CTF首页</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <link rel="stylesheet" type="text/css" href="css/base.css">
         <link rel="stylesheet" type="text/css" href="css/media-style.css">
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
                                <h3 class="b-title">题目</h3>
                                <div class="b-15"></div>
                               
                                
  <div class="b-course">
    <table cellspacing="0" cellpadding="0" widht="100%" class="tb_title">
     <tr>
        <th width="8%">题号</th>
        <th width="76%">题目</th>
        <th width="16%">操作</th>
    </tr>
    <?php
    $i = (($page-1)*$pageSize)+1;//第几题=（(当前页数-1)*显示条数）+1

    foreach($arr as $v){
        if($v['state']==1){
            $look = '<a href="look_answer.php?qid='.$v['id'].'">查看队友答案</a>|';
            if(is_has_answer($uid,getEventIdByQid($v['id']))){
                //该用户已经答了这道题;
                $answer = '您已答';
                //$action = 'update';
                $tdHtml = $look.$answer;
            }else{
                //没有答这道题;
                $answer = '答题1';
                //$action = 'add';
                $tdHtml =''.$look.'<a href="answer_question.php?qid='.$v['id'].'&action='.$action.'&classId='.$cid.'">'.$answer.'</a>';
            }
        }else{
            //战队无人答题
             $answer = '答题2';
             //$action = 'add';
             $look = '';
             $tdHtml=''.$look.'<a href="answer_question.php?qid='.$v['id'].'&action='.$action.'&classId='.$cid.'">'.$answer.'</a>';
        }
         
        $trHtml.='<tr>
            <td align="center">第'.$i++."题</td>
            <td class='table-text'>".$v['exam_Name'].'</td>
            <td align="center">'.$tdHtml.'</td>
        </tr>';
    }
echo $trHtml;
    ?>
    
</table>
       </div>                         
  <!--题目页面结束-->                              

     
       </div>
     </div>
    </div>
   </dv>
 </section>
</body>
</html>




























 
