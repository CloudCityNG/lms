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
         <p >2015年全国职业院校技能大赛高职组&#8220;神州数码&#8221;杯&#8220;信息安全管理与评估&#8221;赛项由神州数码网络有限公司协办，神州数码网络有限公司为大赛提供网络设备器材、竞赛环境的搭建、现场技术支持、专家等多方面支持，为比赛的顺利进行保驾护航。 </p>
         <br/>
         <p class="fbb fblue" >竞赛目的 </p>
         <p >通过赛项检验参赛选手网络组建、安全架构和网络安全运维管控等方面的技术技能，检验参赛队组织和团队协作等综合职业素养，培养学生创新能力和实践动手能力，提升学生职业能力和就业竞争力。丰富完善学习领域课程建设，使人才培养更贴近岗位实际，实现以赛促教、以赛促学、以赛促改的产教结合格局，提升专业培养服务社会和行业发展的能力，为国家信息安全行业培养选拔技术技能型人才。 </p>
                  <br/>

         <p class="fbb fblue">竞赛内容 </p>
         <p >重点考核参赛选手网络组建、网络系统安全策略部署、信息保护、网络安全运维管理的综合实践能力。 </p>
                  <br/>

         <p class="fbb fblue">竞赛形式 </p>
         <p >本次攻防大赛为68组战队互相乱攻，每个小组有相应的攻击机和要防守的靶机，小组成员需要在最快的速度将自己的靶机中的相应漏洞弥补，并且要在对方在防守过程中尽快的攻击对方靶机成功，拿到相应的KEY或者是以其他的方式。每个小组的靶机一旦被攻击成功后，小组的靶机在后台的监控中就会出现了被攻下的标志，这个小组也就退出比赛。68个小组中能够活到最后并且得分最高的获胜。 </p>
                  <br/>

         <p >由于防守程度的不同以及限制性，比赛会制定相应的禁止行为，如禁止用户修改相应的靶机的IP服务器地址，禁止设置防火墙为决绝一切访问等。 </p>
                  <br/>

         <p >参赛现场每个座位有固定的登录名，密码为随机生成，然后打印出来分发给学员。 </p>
         <p >&nbsp;</p>
       </ul> 
</div>


</div>
</body>
</html>
