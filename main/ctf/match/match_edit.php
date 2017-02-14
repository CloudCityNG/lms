<?php
     include_once ('../../inc/global.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>手动批改</title>
<link href="<?=api_get_path ( WEB_PATH )?>portal/sp/index.css"
	rel="stylesheet" type="text/css" />
<style type='text/css'>
    .aa{
        width:30px;
    }
    tr{
        text-align:center;
    }

</style>

</head>
<body>
<?PHP
$id=getgpc('id','G');
//提交答卷信息
function submit_values(){ 
    $id=$_POST['id'];
    $email=$_POST['fraction'];
    if($_POST['fraction']){
        $even_tion=2;
    }else{
        $even_tion=3;
    }
    $sql="UPDATE `tbl_match` SET `fraction`='".$email."',`state`=2,`Even_tion`=".$even_tion." WHERE `id`=".$id;
    mysql_query($sql);
    
        
    
}
if($_POST['submit']){
     submit_values(); 
     echo "<script>parent.location.href='./match_list.php';</script>";
}


$sql = "SELECT * FROM tbl_match WHERE id=".$id;
$exam_result = Database::fetch_one_row ( $sql, FALSE, __FILE__, __LINE__ );

$sql1 = "SELECT examId FROM tbl_event WHERE id=".$exam_result['event_id'];
$s = Database::fetch_one_row ( $sql1, FALSE, __FILE__, __LINE__ );

$sql1 = "SELECT exam_Name FROM tbl_exam WHERE id=".$s['examId'];
$exam = Database::fetch_one_row ( $sql1, FALSE, __FILE__, __LINE__ );


$sql2 = "SELECT teamName FROM tbl_team WHERE id=".$exam_result['gid'];
$team = Database::fetch_one_row ( $sql2, FALSE, __FILE__, __LINE__ );

$tbl_arr=mysql_fetch_row(mysql_query('select examId from tbl_match,tbl_event where tbl_match.event_id=tbl_event.id and tbl_match.id='.$id));
$sql3 =mysql_query( "SELECT examBranch,examDesc FROM tbl_exam WHERE id=".$tbl_arr[0]);
$examBranch =mysql_fetch_assoc( $sql3 );

$sql4 = "SELECT eventName FROM tbl_event WHERE id=".$id;
$eventName = Database::fetch_one_row ( $sql4, FALSE, __FILE__, __LINE__ );
$sql5 = "SELECT report FROM tbl_match WHERE id=".$id;
$eventAnswer = Database::fetch_one_row ( $sql5, FALSE, __FILE__, __LINE__ );

?>
<style>
    #markerr{
        margin-left:10px;
        padding-left:10px;
        width:200px;
        height:20px;
        border:1px solid red;
        display:none;
        line-height:21px;
        background-color: #FFC0CB;
        
    }
    .t-title{
        
        padding:5px;
        line-height:24px;
        border-bottom:2px solid #000;
    } 
    .t-mar{
        margin:10px;
    }
    .answer-mit{
        margin-left:45%;
        width:77px;
        height:30px;
        background:#0B7933;
        border-radius:5px;
        color:#fff;
        
    }
</style>
	
<form   action="match_edit.php" method="post"   id='abc' style="margin:40px;">

    <input type="Hidden" name="id" value="<?=$id;?>" />
<div class="register_title dc2" style="text-align: center;">
				<strong><?php
				echo $exam['exam_Name'];
				?></strong>
			</div>
			<div class="emax_title de1">
				<span style="margin-right: 30px;"></span><span
					style="margin-right: 30px;">战队名称：<?php
					echo $team ["teamName"]?></span><span 
					style="margin-right: 30px;">考题分数：<?php
					echo $examBranch ["examBranch"]?></span>
				</span>
			</div>
    
    
<script>

</script>
           <br> 
    
  

   <div class="register_hint dd2" style="width: auto; margin: 10px 0 15px;">考题如下:</div>
    <div class="test-score"  style="width:90%;margin:0 auto;">
        <div class="t-title t-mar"><?= $examBranch ["examDesc"]?>TTL，全称是Time To Live，中文名为生存时间，它是IP报头中一个非常重要的参数。通过TTL的值，我们可以判断出当前网络IP层的工作状况。
TTL告诉网络中的路由器数据包在网络中的时间是否太长而应被丢弃，TTL的最初设想是确定一个时间范围，超过此时间就把包丢弃。由于数据包每经过一个路由器时，TTL值都会至少被路由器减1，所以TTL值通常表示包在被丢弃前还能最多经过的路由器个数。当TTL值为0时，路由器丢弃该数据包，并发送一个ICMP报文给数据包的最初发送者。 

有很多原因会导致数据包在一定时间内不能被传递到目的地。例如，不正确的路由表配置可能导致数据包的无限循环，而解决方法就是在一段时间后丢弃这个数据包，然后给发送者发送一个报文，由发送者决定是否重发该数据包。当网络出现这种情况时，数据包就会在路由表中配置错误的路由器处重复发送，每发送一次，TTL值减1，直到TTL为0时路由器丢弃该数据包，造成网络中数据传输错误。
操作系统和传输协议不同，对应TTL的默认值也不同。</div>
        <div class="answer t-mar">
            <span style="margin-right:20px;"> 答案文档</span>
            <a href="<?= '../'.$eventAnswer['report']?>" ><?php $str=explode('/',$eventAnswer['report']);echo array_pop($str);
                                        ?>
            </a>
        </div>
        <div class="answer t-mar" style="padding-bottom:6px;border-bottom:1px solid #000;">
            <span style="margin-right:20px;"> 答案得分</span>
            <input  type="text" name="fraction" class='aa' id='int' style="width:50px;" onKeyUp='checkFraction(this.value)'/>
            <span id="markerr"></span>
        </div>
        <div class="answer t-mar">
         <input type="submit" name="submit" value="提交"  class="answer-mit" />
         </div>
    </div>
           
      
</form>
</body>
</html>
<script src='../js/jquery-1.7.2.min.js'></script>
<script type="text/javascript">
        var markerr=document.getElementById('markerr');
        function checkFraction($i){
            if($i><?php echo $examBranch ["examBranch"]?>){
               markerr.style.display='inline-block';         
               markerr.innerHTML='最大分数不能超过最过<?php echo $examBranch ["examBranch"]?>';
            }else{
               markerr.style.display='none';         
               markerr.innerHTML='';
            }

        }
     $("#abc").keyup(function(){
        var sai=$("#int").val();
        var matchArray = sai.match(/^[0-9]\d*$/);
        if (matchArray == null) {
           markerr.style.display='inline-block';         
           markerr.innerHTML='必须输入数字';
        }else{
           markerr.style.display='none';         
           markerr.innerHTML='';
        }
     });
</script>

