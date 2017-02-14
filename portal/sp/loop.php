<?php
header("Content-type:text/html;charset=utf-8");
include_once ("../../main/inc/global.inc.php");
$cid=intval($_GET['cid']);
if($cid){
    $user_id=  api_get_user_id();
    $sql="SELECT id,filename,snapshotdesc,type FROM `snapshot` WHERE `lesson_id`='".$cid."' and `user_id`='".$user_id."' and `status`='0'";
    $result= api_sql_query_array_assoc($sql,__FILE__,__LINE__);
    $url="image_and_media.php?action=delete&cidReq=".$cid."&id=";
    if(count($result) > 0){
        $context='';
        foreach($result as $rek=>$rev){
            $context.='<div class="g-cell1_m u-card j-href ie6-style" data-href="#">
        <div class="card">';
            if($rev['type']==1){
               $context.='<div class="u-img f-pr">
                        <td><a rel="example_group" href="/lms/storage/snapshot/'.$rev['filename'].'.jpg" title="'.$rev['snapshotdesc'].'"><div>'.$rev['snapshotdesc'].'</div><div><img src="/lms/storage/snapshot/'.$rev['filename'].'_s.jpg" /></div></a></td>
                    </div>
                    <div class="descd j-d">
                        <span class="dbtn">
                            <a href="javascript:if(confirm(\'你确定删除吗?\'))window.location=\''.$url.$rev['id'].'\'">删除</a>&nbsp;
                        </span> 
                    </div>'; 
            }else if($rev['type'] == 2){
                $context.='<div class="u-img f-pr">
                        <td><a class="various1" href="'.$rev['filename'].'"><div>'.$rev['snapshotdesc'].'</div><div><img  alt="高清视频"  title="'.$rev['snapshotdesc'].'" src="/lms/themes/img/video1.png" /></div></a></td>
            </div>
            <div class="descd j-d">
                        <span class="dbtn"><a href="javascript:if(confirm(\'你确定删除吗?\'))window.location=\''.$url.$rev['id'].'\'">删除</a>&nbsp;</span> 
            </div>';
            }
            $context.='</div></div>';
         }
              echo $context;
    }else{
    echo 'err';    
    }
}else{
    echo 'err';
}
?>
