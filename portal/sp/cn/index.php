<?php
	include_once ("../inc/app.inc.php");
	include_once '../../../main/inc/global.inc.php';
	$user_id=$_SESSION['_user']['user_id'];
	 
	$sql_user="select `address` from  `user`  where user_id=$user_id";
	$sql_user_org=  api_sql_query( $sql_user );
	$org_user_show=DATABASE::fetch_row($sql_user_org);
	$sql_cnorg =  "select * from `cn_org` where id='$org_user_show[0]'" ; 
	$sql_org=  api_sql_query( $sql_cnorg );
	while ($org_show=  mysql_fetch_assoc($sql_org)){
		$org_info[]=$org_show; 
	}
	$org_id=$org_info[0]["id"];
	$sql_manage=  "select * from `cn_vmmanage` where org=$org_id  and type=1";
	$sql_vmmanage=  api_sql_query( $sql_manage );
	while ($org_vmshow=  mysql_fetch_assoc($sql_vmmanage)){
		$org_vminfo[]=$org_vmshow; 
	}
	$sql_vm_man=  "select * from `cn_vmmanage` where org=$org_id  and type=0";
	$sql_vm_mage=  api_sql_query( $sql_vm_man );
	while ($org_vm_show=  mysql_fetch_assoc($sql_vm_mage)){
		$org_vm_info[]=$org_vm_show; 
	}
	$sql_mass="select * from `cn_massage`";
	$sql_massage1=  api_sql_query( $sql_mass );
	$org_mass_show=DATABASE::fetch_row($sql_massage1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=api_get_setting ( 'siteName' )?>欢迎您</title>
<link href="../css/cn_student.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/fight-wall.css" />
<script type='text/javascript'>
      function closebtn(){     
             if(confirm("你确定退出系统吗？")){
                 location.href="<?=URL_APPEND?>main/cloud/cloudvmstop.php?user_id=<?=$user_id?>";
             }           
  }
</script>
</head>

<body  bgcolor="#1c2d7d" >
<div class="top_index">
  <ul><a   onclick="closebtn();"  class="f20"  style="cursor:pointer">退出</a></ul>
</div>
<div class="mean">
    <div class="mean_left">
    <ul>
       <li> <a href="index.php">大赛信息</a></li>
        <li><a href="page_rules.php">大赛规则</a></li>
        <li><a  onclick="closebtn();"  style="cursor:pointer" >退出登录</a></li>
        
    </ul>	
	</div>
<div class="area" style='font-family:Arial; font-family:"微软雅黑";' >
	<!--<iframe src="page_content.php" scrolling="no" frameborder="0" width="100%"></iframe>-->
    <div  style="width:100%;min-height:500px;height:100%;opacity:0.8;margin:30px auto;color:#fff;padding:10px 100px;">
        <table>
            <tr>
                <td style="font-weight: bold;color: #DD720A;">工位号:<?php echo $org_info[0]['org']?></td>
                <td width='30%'></td>
                <td style="font-weight: bold;color: #DD720A;">提交密码:<?php echo $org_info[0]['passport']?></td>
            </tr>
            <tr height='50px'><td></td><td></td><td></td></tr>
            <tr>
            <td> 
                            <table>
                                    <tr>受保护的服务器列表:</tr>
                               <?php foreach ($org_vminfo as $k => $v) {
                                        $_SESSION[$user_id.$v['org'].'bo'] = $v['org'];
                               ?>
                                            <?php  
                                                              $url="/lms/main/cloud/cloudvmstart.php?system=" . $v['vmdisk'].'_'.$v['org']."&amp;nicnum=1&cid=201308975637&org=".$v['org']."";
                                                    ?>
                                                    <tr>  
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;服务器<?php echo $k+1?>:</td>
                                                                    <?php if($v["status"]==1){?>
                                                            <td> <a href="<?php echo $url?>"  target="_blank" style='color: #DD720A;'><?php echo $v['vmdisk']?></a></td>
                                                                              <?php }else{?>
                                                                             <td><a  style='color: #DD720A;'> <?php echo $v['vmdisk']?></a></td>
                                                                              <?php }?>
                                                    </tr>
                                                      <tr>
                                                               <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;系统登录名:</td>
                                                            <td><?php echo $v['luse']?></td>
                                                      </tr>

                                                    <tr>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;登录密码:</td>
                                                            <td><?php echo $v['lpasswd']?></td>
                                                    </tr>
                                                    <tr>
                                                            <?php if($v['ip']!=""){?>
                                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ip地址:</td>
                                                                    <td><?php echo $v['ip']?> </td>
                                                            <?php }?>
                                                    </tr>  
                                    <?php }?>
                            </table>
            </td>
                 <td   width='30%'></td>
            <td>
            <table>
            <tr>渗透的服务器列表:</tr>
                   <?php 
                 foreach ($org_vm_info as $k => $v) {
                   $_SESSION[$user_id.$v['org'].'sh'] = $v['org'];
                    $url="/lms/main/cloud/cloudvmstart.php?system=" . $v['vmdisk'].'_'.$v['org']."&amp;nicnum=1&cid=201308975637&org=".$v['org']."";
                    ?>
                    <tr>
                         <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;服务器<?php echo $k+1?>:</td>
                          <?php if($v["status"]==1){?>
                             <td> <a href="<?= $url?>"  target="_blank"  style='color: #DD720A;'> <?php echo $v['vmdisk']?></a></td>
                           <?php }else{?>
                                 <td> <a><?php echo $v['vmdisk']?></a></td>
                           <?php }?>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;系统登录名:</td>
                        <td><?php echo $v['luse']?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;登录密码:</td>
                        <td><?php echo $v['lpasswd']?></td>
                    </tr>
                    <tr>
                        <?php if($v['ip']!=""){?>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ip地址:</td>
                            <td><?php echo $v['ip']?></td>
                        <?php }
                }?>
                </tr>          
            </table>
        </td>
                </tr>
            </table>
            <div style="margin-top: 60px ; ">
                提交说明：<br> &nbsp; &nbsp; &nbsp; &nbsp;<?php // echo $org_mass_show[1]?> 
             
        <p>1.裁判服务器地址为192.168.40.16</p>
        <p>2.KEY或者FLAG提交流程：</p> 
        <p><span>A.受保护服务器系统内置了flagsubmit程序用于向裁判服务器发送key或者flag，flagsubmit的命令格式为flagsubmit FLAGorKEY GROUPNAME PASSWORD。
     其中FLAGorKEY是你取得的FLAG或者KEY，GROUPNAME是你的工位号，PASSWORD为比赛现场提供的“提交密码”。工位号和提交密码是确定参赛组身份的唯一标识，请注意保护。</span></p>
    <p> <span>B.提交程序将提取运行flagsubmit程序的服务器的硬件及相关信息作为标识。因此，请注意如果在本机找到的KEY或者FLAG请在本机运行，如果在其他机器找到的KEY或者FLAG请在其他机器系统内运行。
</span></p>
        <p><span >  C.裁判服务器通过514端口接收KEY和FLAG数据，通过80端口向外发布成绩。</span> </p> 
             <p>3.请仔细阅读大赛规则页面</p>
            </div>
            </div>
</div>


</div>
</body>
</html>
