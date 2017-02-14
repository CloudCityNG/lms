<?php
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
include_once '../../main/inc/global.inc.php';
if($_SESSION['up_url']){
    echo '<script language="javascript"> document.location = "'.$_SESSION['up_url'].'";</script>';
}else{
   $sql =  "select `id` from `setup` order by `custom_number` LIMIT 0,1";
    $courseId= DATABASE::getval ( $sql, __FILE__, __LINE__ );
    if($courseId){
         if(api_get_setting ( 'lnyd_switch' ) == 'true'){
             if(api_get_setting ( 'enable_modules', 'exam_center' ) == 'true'){
                 echo '<script language="javascript"> document.location = "./exam_list.php";</script>';
             }else{
                 echo '<script language="javascript"> document.location = "./new-index.php";</script>';}
        }else  if(api_get_setting('enable_modules', 'clay_oven') == 'false'){
                echo '<script language="javascript"> document.location = "./select_study.php?id='.$courseId.'";</script>';
        }else{
            if($_SESSION['_user']['user_id']){
                if($_SESSION['_user']['user_id']==1){
                    echo '<script language="javascript"> document.location = "./cn/index_admin.php";</script>';
                }else{
                     echo '<script language="javascript"> document.location = "./cn/index.php";</script>';
                }
            }else{
                 echo '<script language="javascript"> document.location = "./cn/login.php";</script>';
            }     
        }
    }else{
       echo '<script language="javascript"> document.location = "./my_foot.php";</script>';
    }
}
exit();
