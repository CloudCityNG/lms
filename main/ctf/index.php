 <?php
header("Content-type:text/html;charset=utf-8");
include ('../inc/global.inc.php');
$gid=intval($_GET['id']);

if($_SESSION['_user']['user_id']){
    if($_SESSION['tbl_contest'] !== $gid){
        $_SESSION['tbl_contest']=$gid;
    }
}
$c_table = Database::get_main_table ( 'tbl_class' );

//查询顶级分类
   function get_top_array($id=0){ 
            global $c_table;
            $event_query=mysql_query('select a.classId from tbl_event as e,tbl_exam as a where e.matchId='.$id.' and e.examId=a.id');
            while($event_row=mysql_fetch_row($event_query)){
               $event_rows[]=$event_row; 
            }

            foreach($event_rows as $event_k=>$event_v){
                  $class_name_arr=mysql_fetch_row(mysql_query('select className,id from tbl_class where id='.$event_v[0]));
                  $class_arr[]=$class_name_arr;
            }
            return $class_arr; 
     }

    function unique_arr($array2D,$stkeep=false,$ndformat=true)
    {
            // 判断是否保留一级数组键 (一级数组键可以为非数字)
            if($stkeep) $stArr = array_keys($array2D);
            // 判断是否保留二级数组键 (所有二级数组键必须相同)
            if($ndformat) $ndArr = array_keys(end($array2D));
            //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            foreach ($array2D as $v){
                $v = join(",",$v);
                $temp[] = $v;
            }
            //去掉重复的字符串,也就是重复的一维数组
            $temp = array_unique($temp);
            //再将拆开的数组重新组装
            foreach ($temp as $k => $v)
            {
                if($stkeep) $k = $stArr[$k];
                if($ndformat)
                {
                    $tempArr = explode(",",$v);
                    foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
                }
                else $output[$k] = explode(",",$v);
            }
            return $output;
  }

$m_table = Database::get_main_table ( 'tbl_match' );
$u_table = Database::get_main_table ( 'user' );
$e_table = Database::get_main_table ( 'tbl_exam' );
$ev_table = Database::get_main_table ( 'tbl_event' );

$uid=$_SESSION['_user']['user_id'];
//战队id
$teamId=$_SESSION['_user']['teamId'];

//检查得分
function checkUserAnswer($uid,$eid){
    global $m_table;
    $sql = 'select fraction,eTime,state,Even_tion FROM'.$m_table.' WHERE user_id='.$uid.' AND event_id='.$eid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    
    return $row;
}

//返回二位数组,返回int
function getExamStateByIds($examId,$teamId){
    global $m_table;
    $sql = 'SELECT fraction,eTime FROM '.$m_table.' WHERE gid='.$teamId.' AND event_id='.$examId.' and Even_tion=1';

    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row; 
}
$top_arr=get_top_array($gid);
$top_arr=unique_arr($top_arr);

//根据分类id与赛事id查询题目
function getQuestionInfo($mid,$cid){
    $sql ='select a.exam_Name,a.examBranch,a.classId,a.id,e.id,a.isReport from tbl_exam as a,tbl_event as e where e.matchId='.$mid.' and e.examId=a.id and a.classId='.$cid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    while($row=Database::fetch_row($res)){
        $rows[]=$row;
    }
    return $rows;
}
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
                                    <ul class="b-course-title">
 <?php
                                         foreach($top_arr as $top_ak => $top_av){
                                            echo '<li><a href="#">'.$top_av[0].'</a></li>';
                                         }
?>
                                       
                                    </ul>
                                    <div class="b-10"></div>
                                    <ul class="title-list">
<?php       
            foreach($top_arr as $top_k => $top_v){
                                $childHtml.='<li>';
                                $qName = getQuestionInfo($gid,$top_v[1]);
                                $count=count($qName);
                      for($j=0;$j<$count;$j++){
                                               $eid= $qName[$j][4];
                                               $qName[$j][0]=  htmlspecialchars_decode($qName[$j][0]);
                                               if(api_get_setting ( 'lnyd_switch' ) !== 'true') {
                                                   $userAnswer = checkUserAnswer($uid, $eid);
                                               }

                                      //首先判断用户本赛式当前赛题有没有答
                                      if(!$userAnswer['eTime']){
                                                //判断战队有没有人答对
                                                 $state=getExamStateByIds($eid,$teamId);
                                       if($state['eTime']){                                            //有人答对
                                                $answer='战队有成员回答正确';
                                                $childHtml.= '<a style=background-color:#ff0088 href="answer_question.php?qid='.$qName[$j][3].'&gid='.$gid.'" title="'.$answer.'">
                                                                            '.$qName[$j][0].'
                                                                            <span class="title-num">'.$qName[$j][1].'</span>
                                                                        </a>'; 
                                             }else{                                                         //战队内无人答题或答对        
                                                 $answer = '回答';
                                                 $childHtml.= '
                                                                <a  href="answer_question.php?qid='.$qName[$j][3].'&gid='.$gid.'" title="'.$answer.'" >
                                                                    '.$qName[$j][0].'
                                                                    <span class="title-num">'.$qName[$j][1].'</span>
                                                                </a>';
                                            }
                                          }else{
                                                   if($userAnswer['Even_tion'] === '1' ||  $userAnswer['Even_tion'] === '2'){    //用户答对了#d93a49
                                                            $answer = '您已答对';
                                                            $childHtml.= '<a style="background-color:#1d953f" onclick="reAnswer()" title="'.$answer.'">'
                                                                                       .$qName[$j][0]
                                                                                       .'<span class="title-num">'.$qName[$j][1].'</span>
                                                                                  </a>';
                                                        }else if($userAnswer['Even_tion'] === '3' || $userAnswer['Even_tion'] === '0'){                               //答错
                                                                $answer = '您已答错';
                                                                $childHtml.= '<a style="background-color:#d93a49" onclick="reAnswer()" title="'.$answer.'">'
                                                                                           .$qName[$j][0]
                                                                                           .'<span class="title-num">'.$qName[$j][1].'</span>
                                                                                       </a>';
                                                            }else if($userAnswer['Even_tion'] === '4'){
                                                                $answer = '审核中';
                                                                $childHtml.= '<a style="background-color:#8552a1" onclick="reAnswer()" title="'.$answer.'">'
                                                                                         .$qName[$j][0]
                                                                                         .'<span class="title-num">'.$qName[$j][1].'</span>
                                                                                      </a>';

                                                        }
                                                }
                                               
                                            }
                                             $childHtml.='</li>';    
                                         }
                                         echo $childHtml;

?>
                                             
                                    </ul>
                                </div>
                                <div class="b-30"></div>
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
    function reAnswer(){
        alert("您已经回答过本题，请寻求队友帮助作答。");
        return false;
    }
</script>