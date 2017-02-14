<?php
header("Content-type:text/html;charset=utf-8");
include ('../inc/global.inc.php');


$m_table = Database::get_main_table ( 'tbl_match' );
$u_table = Database::get_main_table ( 'user' );
$e_table = Database::get_main_table ( 'tbl_exam' );
$ev_table = Database::get_main_table ( 'tbl_event' );

//$action=$_REQUEST['action'];
$action='add';//关闭更改功能
$cid=  intval($_REQUEST['classId']);

//获取当前题目ID
$qid=intval($_REQUEST['qid']);

/*@$table:表名 string
 *@$column:字段 array
 * 返回二维数组
*/
function selectTable($table,$column,$where=''){
    foreach($column as $v){
        $colSql.=','.$v;
    }
    $colSql=substr($colSql,1);
    if(!empty($where)){
        $where = 'where '.$where;
    }
    $sql="SELECT $colSql FROM ".$table." ".$where;

    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    while($row = mysql_fetch_assoc($res)){
        $rows[]=$row;
    }
    return $rows;
}    

/*@$table:表名 string
 *@$column:字段 array
 * 返回一维数组，查询一条记录
*/
function selectOne($table,$column,$where=''){
    foreach($column as $v){
        $colSql.=','.$v;
    }
    $colSql=substr($colSql,1);
    if(!empty($where)){
        $where='where '.$where;
    }
    $sql = "SELECT $colSql FROM ".$table." ".$where;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row;
}

/*@$table:表名 string
 *@$data:字段 array (字段=>插入的值，)
 *@$where:条件 string （不用写前面的where）
*/
function insertData($table,$data,$returnSql=false){
    foreach ($data as $k=>$v){
        $column.= ','.$k;     
        $values.= ",'".$v."'";
    }
    $column = substr($column,1);
    $values = substr($values,1);
    $sql="INSERT INTO ".$table."(".$column.") VALUES(".$values.")";
    if($returnSql){
        return $sql;
    }else{
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        return $res;
    }
}

/*@$table:表名 string
 *@$data:字段 array (字段=>插入的值，)
*/
function updateData($table,$data,$where,$returnSql=false){
    foreach ($data as $k=>$v){
        $set.= ",".$k."='".$v."'";     
    }
    if(!empty($where)){
        $where= 'where '.$where;
    }
    $set = substr($set,1);
    $sql="UPDATE ".$table." SET ".$set." ".$where;
    if($returnSql){
        return $sql;
    }else{
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        return $res;
    }
}

function showOrHide(){
    global $qid,$e_table;
    $column=array('isReport','isKey');
    $where='id ='.$qid;
    $row=  selectTable($e_table, $column, $where);
    return $row;
}

  $column = array('examKey','isKey','isReport','uploadText','examBranch');
  $where = 'id='.$qid;
  $correctKey = selectOne($e_table,$column,$where);





//查询题目信息
$questionInfo=selectOne($e_table,array('exam_Name','examDesc','examBranch','classId'),"id=$qid");




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
<!-- 左侧-->
               <div class="g-sd1 f-pr" id="sidebar">
                        <div class="b-50"></div>
                        <div class="ctf-logo">
                            <img src="images/ctf-logo.png">
                        </div>
                        <div class="b-50"></div>
                        <div class="viewport">
                            <div class="overview">
                                <ul class="nav navbar" id="Nav">
                           
                            <li class="navition color_1 selected">
                                <a href="#">
                                    <img src="images/forms.png">
                                    <span>题目</span>
                                </a>
                            </li>
                            <li class="navition color_2">
                                <a href="#">
                                    <img src="images/widgets.png">
                                    <span>积分榜</span>
                                </a>
                            </li>
                            <li class="navition color_3">
                                <a href="#">
                                     <img src="images/grid.png">
                                    <span>公告</span>
                                </a>
                            </li>
<!--                           <li class="navition color_4">
                               <a href="#">
                                   <img src="images/calendar.png">
                                    <span>决赛日程</span>
                               </a>
                           </li>-->
                            <li class="navition color_5">
                                <a href="#">
                                      <img src="images/maps.png">
                                    <span>大赛简介</span>
                                </a>
                            </li>
                            <li class="navition color_8">
                                <a href="#">
                                    <img src="images/others.png">
                                    <span>大赛规则</span>
                                </a>
                            </li>
<!--                           <li class="navition color_7">
                               <a href="team_match.php">
                                   <img src="images/explorer.png">
                                    <span>战队成绩</span>
                               </a>
                           </li>-->
                           <li class="navition color_6">
                                <a href="#">
                                    <img src="images/gallery.png">
                                    
                                    <span>战队管理</span>
                                    
                                </a>
                            </li>
<!--                            <li class="navition color_9"><a href="#">决赛场地</a></li>-->
                            <li class="navition color_9">
                                <a href="#">
                                    <img src="images/statistics.png">
                                    <span> FAQ</span>
                                   
                                </a>
                            </li>
                        </ul>
                            </div>
                        </div>
                        
                    </div>
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
//$(function(){
//    $("#Login").click(function(){
//        $("#login-tip").css("display","block");
//    })
//    $("#login-tip .l-close").click(function(){
//        $("#login-tip").css("display","none");
//    })
//});
</script>
                   <!--左侧结束-->
                   <!--右侧-->
                   
  <div class="g-mn1">
     <div class="g-mn1c">
   <!--题目-->
        <div class="j-list lists">
          <h3 class="b-title">题目</h3>
          <div class="b-15"></div>
          <div class='Faq-all answer-c' style="padding:0">
                               
<div class="a-text">
    <?php 
echo htmlspecialchars_decode($questionInfo['exam_Name']).'</br>';
echo htmlspecialchars_decode($questionInfo['examDesc']);

$sh=showOrHide();

?>
</div>

              
<form action="" enctype="" method="">
   <?php  
   if($sh[0]['isKey']){
        echo '<input class="answer-input" type="text" name="answer" disabled="disabled">';
   }
   ?>
     </br>


     <?php
     if($sh[0]['isReport']){
         echo '<input type="file" name="report">　　';
     }
     
     ?>
     <?php
if($correctKey['uploadText']){
     echo "</br></br></br><a href=".$correctKey['uploadText']." title='下载附件'><img src='../../themes/img/arrow_down_0.png' alt='下载附件' title='下载附件'>下载附件</a>";
     }
     ?> 
    </br></br> <input type="submit" class="an-btn" >
</form>
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