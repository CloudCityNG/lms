<?php
header("Content-type:text/html;charset=utf-8");
include ('../inc/global.inc.php');
$res=api_sql_query ( $sql, __FILE__, __LINE__ );

$m_table = Database::get_main_table ( 'tbl_match' );
$u_table = Database::get_main_table ( 'user' );
$e_table = Database::get_main_table ( 'tbl_exam' );
$ev_table = Database::get_main_table ( 'tbl_event' );

//$action=$_REQUEST['action'];
$action='add';//关闭更改功能
$cid=  intval($_REQUEST['classId']);
$id=intval($_GET['id']);
//获取当前题目ID
$qid=intval($_REQUEST['qid']);
//$page=$_GET['page'];//页码
$page=1;
$pageSize=10;

//获取用户id 
$uid = $_SESSION['_user']['user_id'];
$ggid=intval($_REQUEST['gid']);


//判断用户是否回答过这道题
function is_has_answer($uid,$qid){
  if(api_get_setting ( 'lnyd_switch' ) !== 'true') {
    global $m_table;
    $sql = 'SELECT id FROM tbl_event WHERE examId=' . $qid;
    $res = mysql_query($sql);
    $id = mysql_fetch_array($res);
    $sql = 'SELECT Even_tion FROM ' . $m_table . ' WHERE event_id=' . $id[0] . ' AND user_id=' . $uid;
    $res = api_sql_query($sql, __FILE__, __LINE__);
    $row = mysql_fetch_assoc($res);
    return $row['Even_tion'];
  }else{
      return false;
  }
}


//获取题目表id
function getEventIdByQid(){
    global $qid,$ev_table,$ggid;
    $sql='SELECT id FROM '.$ev_table.' WHERE examId='.$qid.' AND matchId='.$ggid;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $row = mysql_fetch_assoc($res);
    return $row['id']; 
}
$evid=getEventIdByQid();

function location($message,$ggid){
    //echo '<script>alert("   '.$message.'")</script>';
    echo "<script language='javascript' type='text/javascript'>
        window.location.href='index.php?id=".$ggid."';
    </script>";
}

function answer_success($uid,$qid,$ggid){
    if(is_has_answer($uid,$qid)){//防止作弊多次提交
        header('Location:index.php?id='.$ggid);exit;
    }
}


//根据用户id，查出战队id
function getTidByUid($userId){
    global $u_table;
    $count=count($top_arr);
        $sql ="SELECT teamId FROM $u_table WHERE user_id=".$userId;
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        $row = mysql_fetch_assoc($res);
        return $row['teamId']; 
}

$gid = getTidByUid($uid);

answer_success($uid,$qid,$ggid);
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

//判断答案是否正确（限单选）
function is_correct($answer,$correctKey){

  $answer = stripslashes(stripslashes($answer));

   if($correctKey['isKey'] && $correctKey['isReport']==0){
        if(strcasecmp($answer,stripslashes($correctKey['examKey']))==0){
            return $correctKey['examBranch'];
        }else{
            return false;
        }
   }
}

//无序比较两个字符串，abcd可以bcde 可以返回true
function strnsc($str1,$str2){
	$length1 = strlen($str1);
	$length2 = strlen($str2);
	if($length1 != $length2){
		return false;
	}else{
		$arr = str_split($str2);
		for($i=0;$i<$length1;$i++){					
			$a = substr($str1,$i,1);
			if(in_array($a,$arr)){			
				$trans = array_flip($arr);				
				$key = $trans[$a];
				unset($arr[$key]);
			}else{
				return false;
			}	
			if($i == $length1-1){
				return true;
			}		
		}
        }	
}

function first_correct(){
    global $qid,$m_table,$gid;
    $sql= 'SELECT id FROM tbl_event WHERE examId='.$qid;
    $res= mysql_query($sql);
    $id = mysql_fetch_array($res);
    $column = array('Even_tion');
    $where = 'gid='.$gid.' AND event_id='.$id[0].' AND Even_tion=1 limit 1';
    $markArr=selectTable($m_table,$column,$where);
    //如果没人答过题，则说明用户是第一个答对的；
      if($markArr[0]['Even_tion']){
          return false;
      }else{
          return true;
      }
}

if(!empty($_POST)){
    if($_POST['answer']!=''){
        $data['answer'] = addslashes($_POST['answer']);
    }
    $data['stime'] =  ($_POST['stime']);
    $mark = is_correct($data['answer'], $correctKey);//判断对错
    if($mark){
        $data['fraction'] = $mark;
        if(first_correct()){//判断是否 是第一个答题答对的人
           $data['Even_tion']=1;
       }else{
           $data['Even_tion']=2;//答对了，但不是第一个人
       }
    }else{
        $data['fraction'] = 0;
        $data['Even_tion']=3;//答错了
    }
    $data['user_id'] = intval($uid);
    $data['gid'] = intval($gid);
    $data['event_id'] = intval($evid);  
    $data['etime'] = time();
    if(!empty($_FILES['report']['name'])){//有上传报告
        $fileInfo = $_FILES['report'];
        $type = substr($fileInfo['name'],(strrpos($fileInfo['name'],'.')+1));
        $tmp_name = $fileInfo['tmp_name'];
        $filename = substr(md5(uniqid(rand())),0,16).'-'.$qid.'.'.$type;
        $size = $fileInfo['size'];
        $error = $fileInfo['error'];
        $m=100;
        $Maxsize = 1024*1024*$m;
        ini_set('upload_max_filesize',$m.'M');
        $ext=pathinfo($filename,PATHINFO_EXTENSION);
        $path='../../storage/matchReport/'.$gid.'/'.$uid.'/';
        $destination = $path.$filename;
        $allowext=array('doc','docx','png','jpg','gif','pdf','jpeg');
        $data['report'] = $destination;
        $data['Even_tion']=4;
        if ($error == 0) {
            if($size>$Maxsize){
                $err='上传文件的文件大小不能超过'.$m.'MB';
            }
            if(!in_array($ext,$allowext)){
                $err='不允许上传该类型文件';
            }
            if(!file_exists($path)){
                mkdir($path,0777,TRUE);
                chmod($path,0777);
            }
            if(@move_uploaded_file($tmp_name,$destination)){//如果上传成功之后，则根据不同的$action添加对应的数据库记录
                
                if($action == 'add'){
                    if(is_has_answer($uid,$qid)){
                        header('Location:index.php?id='.$ggid);
                    }else{
                        $res=insertData($m_table,$data);
                        header('Location:index.php?id='.$ggid);
                    }
                    
                    
                }elseif($action == 'update'){
                    $where='event_id='.$qid.' AND user_id='.$uid;
                    $sql=updateData($m_table,$data,$where,true);
                }
                
                $res = api_sql_query ($sql, __FILE__, __LINE__ );
                if($res){
                     header('Location:index.php?id='.$ggid);
                }
                if($action == 'add'){
                    if(!$res){
                        $err='插入失败';
                    }
                }elseif($action == 'update'){//更新-有上传的时候，根据是否有report记录，有就删除原来的文件
                    if(!$res){
                        $err='更新失败';
                    }else{
                        if(file_exists($report)){//是否有原来的上传文件
                            if(!unlink($report)){
                                $err='删除原文件失败';
                            }
                        }
                    }
                 }
            }else{
                 exit('上传失败');
            }
            
        } else {
            switch ($error) {
                case 1:
                    $err='上传文件超过了PHP配置文件中upload_max_filesize选项的值';
                    break;
                case 2:
                    $err= '超过了表单MAX_FILE_SIZE限制的大小';
                    break;
                case 3:
                    $err='文件部分被上传';
                    break;
                case 4:
                    $err='没有选择上传文件';
                    break;
                case 6:
                    $err='没有找到临时目录';
                    break;
                case 7:
                case 8:
                    $err='系统错误';
                    break;
            }
          }   
       } else {//没有上传报告的情况
                $data['state'] = 1;
                if($action == 'add'){
                    if(is_has_answer($uid, $qid)){
                         header('Location:index.php?id='.$ggid);
                    }else{
                        $res=insertData($m_table,$data);
                        if($res){
                             header('Location:index.php?id='.$ggid);
                        }
                    }
                }elseif($action == 'update'){
                    $where='event_id='.$qid.' AND user_id='.$uid;
                    $res=updateData($m_table,$data,$where);
                }
        }
}


//查询题目信息
$questionInfo=selectOne($e_table,array('exam_Name','examDesc','examBranch','classId'),"id=$qid");

if($err){
    echo '<script>alert('.$err.')</script>';
}


?>


<!DOCTYPE html>
<html>
    <head>
        <title>云教育资源平台_51CTF_答题页面</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="关于网络安全知识方面的在线考题竞赛平台实现以战队与个人形式进行在线答题比赛平台的答题页面，该平台依托于云资源教育平台">
        <meta name="keywords" content="云教育资源平台，网络安全教育学习，计算机教育学习，网络安全知识竞赛比赛">
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
          <div class='Faq-all answer-c' style="padding:0">
                               
<div class="a-text">
    <?php 
echo '<h1>'.htmlspecialchars_decode($questionInfo['exam_Name']).'</h1></br>';
echo htmlspecialchars_decode($questionInfo['examDesc']);

$sh=showOrHide();

?>
</div>

              
<form action="answer_question.php" enctype="multipart/form-data" method="post">
   <?php  
   if($sh[0]['isKey']){
        echo '<input class="answer-input" type="text" name="answer">';
   }
   ?>
     </br>
     <input type="hidden" name='gid' value="<?php echo $ggid ?>">
    <!-- <input type="hidden" name='classId' value="<?php echo $cid ?>">-->
     <input type="hidden" name='qid' value="<?php echo $qid ?>">
     <input type="hidden" name="stime" value="<?php echo time() ?>">
     <input type="hidden" name="action" value="<?php echo $action ?>">

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
    </br></br><input type="submit" class="an-btn" >　　　　
    <a href="index.php?id=<?=$ggid?>"><button style="padding: 0 15px;background: #218813;border:1px solid #218813;margin:10px 10px 0 0;height: 30px;text-align:center;color: #fff; font-weight: bold;cursor:pointer;border-radius: 5px;"type="button" class="cancel" onclick="javascript:self.parent.tb_remove();" name="cancle">返回</button></a>
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