<?php
include_once ("../inc/app.inc.php");
include_once '../../../main/inc/global.inc.php';
$user_id=$_SESSION['_user']['user_id'];
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
					 <a href="result.php">成绩信息</a></br></br>
					 <a href="logout.php">退出赛事</a>
				</div>
                <div class="contest-content">
                    <div style="margin-top: 40px;">
					<?php
					session_start();
					session_start();
						if($_GET['action'] == "logout"){
							unset($_SESSION['userid']);
							unset($_SESSION['username']);
							echo '注销登录成功！点击此处 <a href="../login.php">登录</a>';
							exit;
						}
					?>
                 </div>
                </div>
           </div>
      </div>
    </body>
</html>
