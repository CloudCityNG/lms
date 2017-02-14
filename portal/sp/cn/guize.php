<?php
include_once ("../../../login.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=api_get_setting ( 'siteName' )?>欢迎您</title> 
<link href="../css/cn_admin.css" rel="stylesheet" type="text/css" />
<script type='text/javascript'>
      function closebtn(){     
             if(confirm("你确定退出系统吗？")){
                 location.href="<?=URL_APPEND?>main/cloud/cloudvmstop.php?user_id=<?=$user_id?>";
             }           
  }
</script>
</head>

<body  bgcolor="#1c2d7d">
<div class="top_index">
   <ul><a  style="cursor:pointer"  onclick="closebtn();" class="f20">退出</a></ul>
</div>
<div class="mean">
	<div class="mean_left">
        <?php include 'left_link.php';?>
	</div>
    <div class="area">
       <ul class="jianjie">
        <p class="fbb fblue"> 提交说明：</p>
         <br />
        <p>1.裁判服务器地址为192.168.40.16</p>
        <p>2.KEY或者FLAG提交流程：</p> 
        <p><span>A.受保护服务器系统内置了flagsubmit程序用于向裁判服务器发送key或者flag，flagsubmit的命令格式为flagsubmit FLAGorKEY GROUPNAME PASSWORD。
     其中FLAGorKEY是你取得的FLAG或者KEY，GROUPNAME是你的工位号，PASSWORD为比赛现场提供的“提交密码”。工位号和提交密码是确定参赛组身份的唯一标识，请注意保护。</span></p>
    <p> <span>B.提交程序将提取运行flagsubmit程序的服务器的硬件及相关信息作为标识。因此，请注意如果在本机找到的KEY或者FLAG请在本机运行，如果在其他机器找到的KEY或者FLAG请在其他机器系统内运行。
</span></p>
        <p><span >  C.裁判服务器通过514端口接收KEY和FLAG数据，通过80端口向外发布成绩。</span> </p> 
         <br />
         <p class="fbb fblue t30"> 竞赛规则：</p>
           <br />
         <p>1、	学员不能停止受保护服务器的syslog服务，一旦发现则直接下场；<br />
         <p>2、	学员不能停止受保护服务器的WEB应用服务，一旦停止直接下场；<br />
         </p>
          <p>3、	所有受保护服务器的FLAG.TXT文件保存在/root目录下，不得删除或修改FLAG文件，一旦发现直接下场。<br />
          </p>
             <p>4、	取得别人受保护服务器的FLAG并在被攻陷服务器进行提交才能得分，一旦提交正确，那么被攻陷的小组必须立即离场。<br />
          </p>
            </p>
            <br />
           <p class="fbb fblue">评分规则</p>
           <br />
           <p>1.	所有参赛组的分数都将由系统自动打分，并且实时显示；<br />
           </p>
           <p>2.	受保护系统总共包含20个key，加固自身受保护服务器每KEY的分值为5分，加固总分值最高为100分；<br />
           </p>
           <p><span>3.	每台受保护服务器只有一个FLAG，存在于/root目录下FLAG.TXT中；取得一个FLAG的分值为20分，攻击总分值最高为总计300分（即取得15台服务器FLAG即可取得满分）； </span>
          </p>
          <p>4.	如果比赛结束时，自身受保护服务器没有被攻陷，则自动获得的加固分值为100分，即获得加固部分的所有分。<br />
          </p>
           <p>5.	所有KEY和FLAG的提交均通过flagsubmit程序提交，提交自身受保护服务器的KEY可以得分，提交自身FLAG不得分。 <br />
          </p>
            <br />
         <p >&nbsp;</p>
       </ul> 
    </div>


</div>
</body>
</html>
