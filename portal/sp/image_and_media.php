<?php
$cidReset = true;
include_once ("inc/app.inc.php");
include_once ("../../main/inc/global.inc.php");
include_once ('../../main/assignment/assignment.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileDisplay.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'document.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'tablesort.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . "export.lib.inc.php");
include_once (api_get_path ( SYS_CODE_PATH ) . 'document/document.inc.php');
include_once ("inc/page_header_report.php");
$cidReq=$_GET['cidReq'];
$user_id=  api_get_user_id();
$sql="SELECT * FROM `snapshot` WHERE `lesson_id`='".$cidReq."' and `user_id`='".$user_id."' and `status`='0'";
$result= api_sql_query_array_assoc($sql,__FILE__,__LINE__);
$num=count($result);
$url="image_and_media.php?action=delete&cidReq=".$cidReq."&id=";

if($_GET['action']==delete){
    $id=$_GET['id'];
    $url_1="image_and_media.php?cidReq=".$cidReq;
    $sql="SELECT  `type`,`filename` FROM `snapshot` WHERE `id` = ".$id;
    $result= api_sql_query_array_assoc($sql,__FILE__,__LINE__);
    $result=$result['0'];
    $filename=$result['filename'];
//    echo '<pre>';var_dump($result);echo '<hr>';
    if($result['type']==1){
        exec("sudo rm -rf ".URL_ROOT."/www".URL_APPEDND."/storage/snapshot/".$filename."*.jpg");
    }
    if($result['type']==2){
        exec("sudo rm -rf ".URL_ROOT."/www".URL_APPEDND."/storage/snapshot/".$filename.".fbs");
    }
    $desc = 'delete from snapshot where id='.$id;
    $res= api_sql_query ( $desc, __FILE__, __LINE__ );
    echo '<script type="text/javascript">';
    echo 'location.href = "'.$url_1.'"';
    echo '</script>';
    }

    
?>
<script type="text/javascript" src="../../themes/js/jquery-fanxybox/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../themes/js/jquery-fanxybox/js/jquery.fancybox-1.3.4.js"></script>
<!--鼠标控制滚动-->
<script type="text/javascript" src="../../themes/js/jquery-fanxybox/js/jquery.mousewheel-3.0.4.js"></script>
<link rel="stylesheet" type="text/css" href="../../themes/js/jquery-fanxybox/css/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript">
               $(function(){
			$(".various1").fancybox({
			'width':'70%',
			'height':'90%',
			'autoScale':false,
			'transitionIn':'none',
			'transitionOut':'none',
			'type':'iframe'
		});
                
		$("a[rel=example_group]").fancybox({
			'transitionIn':'none',
			'transitionOut':'none',
			'titlePosition':'over',
			'titleFormat':function(title, currentArray, currentIndex, currentOpts) {
				return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
			}
		});              
	});       
           function loopfun(){
            
                $.ajax({
                    url:'loop.php',
                    type:'get',
               dataType:'html',
                   data:'cid=<?=$cidReq?>',
               success:function(er){
                   if(er !== 'err'){ 
                       $("#id-id").html(er);
                       	$(".various1").fancybox({
			'width':'70%',
			'height':'90%',
			'autoScale':false,
			'transitionIn':'none',
			'transitionOut':'none',
			'type':'iframe'
		});
                
		$("a[rel=example_group]").fancybox({
			'transitionIn':'none',
			'transitionOut':'none',
			'titlePosition':'over',
			'titleFormat':function(title, currentArray, currentIndex, currentOpts) {
				return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
			}
		});
               }
                  }
                });
             }  
        $(function(){
        setInterval("loopfun()",5000);
        });
        function delfun(fname){
            $.get("delname.php",{fname:""+fname+""},
                function(prompt){
                    if(prompt == 'ok'){
                        $("#fancybox-wrap").hide();
                        $("#fancybox-overlay").hide();
                    }
                },"html");
        }

</script>
<style type="text/css">
*{margin:0;padding:0;list-style-type:none;}
body{font:normal 12px/18px Verdana, sans-serif;}
#content{width:410px;margin:40px auto 0 auto;padding:0 60px 30px 60px;border:solid 1px #cbcbcb;background:#fafafa;-moz-box-shadow:0px 0px 10px #cbcbcb;-webkit-box-shadow:0px 0px 10px #cbcbcb;}
hr{border:none;height:1px;line-height:1px;background:#E5E5E5;margin-bottom:20px;padding:0;}
#content p{margin:0;padding:7px 0;}
#content a img{border:1px solid #BBB;padding:2px;margin:10px 20px 10px 0;vertical-align:top;}
#content a img.last{margin-right:0;}
#content ul{margin-bottom:24px;padding-left:30px;}
#fancybox-close{position:absolute;top:-15px;right:-15px;width:30px;height:30px;background:transparent url('../../themes/js/jquery-fanxybox/images/fancybox.png') -40px 0px;cursor:pointer;z-index:1103;display:none;}
#fancybox-content .video-put{
    width:100%;
    margin-bottom:5px;
}
#fancybox-content .video-put:after{
    display:block;
    clear:both;
    content:"";
}
#fancybox-content .video-put input{
    float:right;
    height:30px;
    width:77px;
    background:#13a654;
    border-radius:5px;
    border:0;
    color:#fff;
    outline:0;
}
</style>
<div id="id-id">
<?php
for($i=0;$i<$num;$i++){
    ?>

    <div class="g-cell1_m u-card j-href ie6-style" data-href="#">
        <div class="card">    
            <?php
            $var=$result[$i];
            $filename=$var['filename'];
            $snapshotdesc=$var['snapshotdesc'];
            if($var['type']==1){  
                //图片
?>
                <div class="u-img f-pr">
                    <td><a rel="example_group" href="/lms/storage/snapshot/<?= $filename?>.jpg" title="<?= $snapshotdesc?>"><div><?= $snapshotdesc?></div><div><img alt="" src="/lms/storage/snapshot/<?= $filename?>_s.jpg" /></div></a></td>
                </div>
                <div class="descd j-d">
                    <span class="dbtn">
                        <a href="javascript:if(confirm('你确定删除吗?'))window.location='<?= $url.$var['id']?>'">删除</a>&nbsp;
                    </span> 
                </div>
            <?php  
            }else if($var['type']==2){
                               //录屏   
?>
        <div class="u-img f-pr">
                    <td>
                        <a class="various1" href="<?=$filename?>">
                            <div><?=$snapshotdesc?></div>
                            <div>
                                <img  alt="高清视频"  title="<?=$snapshotdesc?>" src="/lms/themes/img/video1.png" />
                            </div>
                        </a>
                    </td>
        </div>
        <div class="descd j-d">
                    <span class="dbtn"><a href="javascript:if(confirm('你确定删除吗?'))window.location='<?= $url.$var['id']?>'">删除</a>&nbsp;</span> 
        </div>
<?php                
            }
            ?>
        </div>
    </div>
                <?php
}
?>
</div>

