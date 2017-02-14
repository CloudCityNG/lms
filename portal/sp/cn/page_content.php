<?php
	include_once ("../inc/app.inc.php");
	include_once '../../../main/inc/global.inc.php';
	$user_id=$_SESSION['_user']['user_id'];
	$sql =  "select `id` from `setup` order by `custom_number` LIMIT 0,1";
	$courseId= DATABASE::getval ( $sql, __FILE__, __LINE__ );
	if($user_id==''){
		echo '<script language="javascript"> document.location = "../login.php";</script>';
	}elseif($user_id=='1'){
		 echo '<script language="javascript"> document.location = "../select_study.php?id='.$courseId.'";</script>';
	}
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

<!DOCTYPE html>
<html>
	<head>
		<title>赛场规则页</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" type="text/css" href="../css/fight-wall.css" />
		<style>
			html{
				height:100%;
				}
				body{
                    height:100%;
                    width:100%;
                    margin:0;
                    padding:0;
                }
        </style>
    </head>
<body>
<div id="common-section" class="contest-section">
	<div class="contest-rule common-rule" >
		<div style=" margin-left: -100px;margin-top: 50px; float: left;">
			<a href="page_content.php">赛场信息</a></br></br>
			<a href="page_rules.php">赛场规则</a></br></br>
			<a href="page_result.php">成绩信息</a></br></br>
			<a href="logout.php?action=logout">退出赛事</a>
		</div>
		<div class="contest-content">
			<div style="margin-top: 50px;margin-bottom: 50px;">
						<div style="margin-left:100px;float:left;">组织内容:<?php echo $org_info[0]['org']?></div>
						<div style="margin-right:150px;float: right;">提交密码:<?php echo $org_info[0]['passport']?></div>
					</div>
				<div>
				<div style="margin-left:-120px;margin-top: 150px;float: left;"> 
						<table>
							<tr>受保护的服务器列表:</tr>
						   <?php foreach ($org_vminfo as $k => $v) {?>
								<?php  
										  $url="/lms/main/cloud/cloudvmstart.php?system=" . $v['vmdisk'].'_'.$v['org']."&amp;nicnum=1&cid=201308975637&org=".$v['org']."";
									?>
									<tr>  
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;服务器<?php echo $k+1?>:</td>
											<?php if($v["status"]==1){?>
										<td> <a href="<?php echo $url?>"  target="_blank">  <?php echo $v['vmdisk']?></a></td>
												  <?php }else{?>
												 <td> <?php echo $v['vmdisk']?></td>
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
				</div>
				<div style="margin-right:-190px;float: right;margin-top: 150px;">
				<table>
				<tr>渗透的服务器列表:</tr>
                   <?php 
						foreach ($org_vm_info as $k => $v) {
                            $url="/lms/main/cloud/cloudvmstart.php?system=" . $v['vmdisk'].'_'.$v['org']."&amp;nicnum=1&cid=201308975637&org=".$v['org']."";
                            ?>
                            <tr>
                                 <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;服务器<?php echo $k+1?>:</td>
                                  <?php if($v["status"]==1){?>
                                     <td> <a href="<?= $url?>"  target="_blank"><?php echo $v['vmdisk']?></a></td>
                                   <?php }else{?>
                                         <td> <?php echo $v['vmdisk']?></td>
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
						}
					?>
                </tr>          
            </table>
        </div>
                </div>
            <div style="margin-top: 550px ; ">
                提交说明：<br> &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $org_mass_show[1]?>
            </div>
            </div>
           </div>
      </div>
    </body>
</html>
