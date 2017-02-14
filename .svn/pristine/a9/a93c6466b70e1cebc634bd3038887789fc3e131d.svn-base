<?php
include_once ("../../main/inc/global.inc.php");
include_once ("../../main/inc/lib/main_functions.lib.php");
$contentc = htmlspecialchars($_POST['content']);


if($_POST['con']=== '发表' && $contentc!==''){
    $cids=intval(getgpc('cid','P'));
    $user_id=$_SESSION['_user']['user_id'];
    $timenow=date('YmdHis',time());
    mysql_query("insert Comment(cid,text,uid,comtime,state) values ($cids,'$contentc','$user_id','$timenow',1)");
}
//查找二级评论
function myCom($id,$cid){
   $myComq=mysql_query('select * from Comment where cid='.$cid.' and fid='.$id.' and fid<>0 and state=1 order by id asc limit 5');
       if($myComq){
         while($myrow=mysql_fetch_assoc($myComq)){
            $myrows[]=$myrow;
         }
         if(count($myrows)){
            return $myrows;               
         }else{
            return false;
         }
        }else{
            return false;
        } 
        
}
//显示二级分类
function Additional($arr,$id){
    foreach($arr as $arrk=>$arrv)
    {
       $result=mysql_query('select username,picture_uri from user where user_id='.$arrv['uid']);
       $userarr=mysql_fetch_row($result);
       $userpath=api_get_path ( WEB_PATH ) . 'storage/users_picture/';
       $depath = api_get_path ( WEB_PATH ) . 'themes/img/home_default_logo.jpg';
       $imgpath = $userarr[1] ? $userpath.$userarr[1] : $depath;
        $text = htmlspecialchars_decode($arrv['text']);
        $time = date('Y-m-d H:i:s',strtotime($arrv['comtime']));
      $content.="<div class='zpk'>
                  <div class='zpkimg'><img height='32' width='32' src='{$imgpath}'/></div>
                  <div class='kkhuifu'>
                    <div class='huifuax'>
                     <span style='color:#2D64B3;'>{$userarr[0]}</span>:
                     <span>{$text}</span>
                    </div>
                    <div>
                       <div class='hutime'>{$time}<span  class='zhhf' onclick="."zhhf({$id},{$arrv['id']},'{$userarr[0]}');".">回复</span></div>
                    </div>
                  </div>
                  <div class='clear'></div>
                 </div>";
    }
    return $content;
}
function listcon($conarr){
    $conhtml='';
    $pes=0;
   foreach($conarr as $conk => $conv){
       
       $result = mysql_query('select username,picture_uri from user where user_id='.$conv['uid']);
       $userarr = mysql_fetch_row($result);
       $userpath = api_get_path ( WEB_PATH ) . 'storage/users_picture/';
       $depath = api_get_path ( WEB_PATH ) . 'themes/img/home_default_logo.jpg';
       $imgpath = $userarr[1] ? $userpath.$userarr[1] : $depath;
       $username = $userarr[0];
       $time = date('Y-m-d H:i:s',strtotime($conv['comtime']));
       $text = htmlspecialchars_decode($conv['text']);
       $mycom = myCom($conv['id'],$conv['cid']);
       if($mycom){
           $display = 'block';
           $span = '<span class="huifu" id="huifu'.$conv['id'].'" style="display:none;" onclick="huifu('.$conv['id'].');">回复</span><span class="huifu" style="height:30px;background-color:#F7F8FA;display:inline-block;" id="shouqi'.$conv['id'].'" onclick="shouqi('.$conv['id'].');">收起回复</span>';
           $contque = mysql_query('select count(*) from Comment where cid='.$conv['cid'].' and fid='.$conv['id'].' and fid<>0 and state=1');
           $countrow = mysql_fetch_row($contque);
           $pagecount = ceil($countrow[0]/5);
         }else{
           $pagecount='';  
           $display='none';
           $span='<span class="huifu" id="huifu'.$conv['id'].'" style="display:inline-block;" onclick="huifu('.$conv['id'].');">回复</span><span class="huifu" style="height:30px;background-color:#F7F8FA;display:none;" id="shouqi'.$conv['id'].'" onclick="shouqi('.$conv['id'].');">收起回复</span>';
       }
       $conhtml.='<div style="min-height:130px;width:100%;">
          <div class="showuser">
             <img src="'.$imgpath.'"/>
              <div>'.$username.'
              </div>
          </div>
          <div style="float:left;width:72%;word-wrap: break-word;">'.$text.'</div>
          </div>
          <div class="date_div">
             <div style="float:right;height:25px;margin-right:5%;line-height:25px;font:13px/18px Arial,SimSun;">'.$time.'&nbsp;&nbsp;'.$span.'</div>
          </div>';
      $conhtml.="<div id='huiping{$conv['id']}' class='huiping' style='display:".$display.";' >";
      $conhtml.="<div id='fdiv{$conv['id']}'>";
      $conhtml.=Additional($mycom,$conv['id']);
      $conhtml.="</div>";
     if($pagecount > 1){ 
        $conhtml.="<div class='pagenumsize' id='pagenums".$conv['id']."'>";
                   $conhtml.="<a class='pagea' id='pagea1' style='color:black;' onclick='numpage(1,".$conv['id'].",".$conv['cid'].")'>1</a>";  
           for($i=2;$i <= $pagecount;$i++){
                   $conhtml.="<a class='pagea' id='pagea".$i."' onclick='numpage(".$i.",".$conv['id'].",".$conv['cid'].")'>".$i."</a>";
           }
        $conhtml.="</div>";
      }
      $conhtml.="<div class='plk'>
                     <form width='100%' id='form{$conv['id']}'>
                         <div id='text{$conv['id']}' class='divtext' contenteditable='true'>
                         </div>
                         <input type='hidden' name='fid' value='{$conv['id']}'/>
                         <input type='hidden' name='cid' value='{$conv['cid']}'/>
                         <input type='hidden' name='ffid' id='ffid{$conv['id']}' value='0' />   
                         <div id='iconbuon{$conv['id']}' onclick='holder({$conv['id']})' style='float:left;width:33px;height:32px;margin-top:5px;background:url(../../storage/qqimg_files/insertsmiley_icon_711ec2d.png) no-repeat;cursor:pointer;'></div>    
                         <input class='replay'  type='button' onclick='mincon({$conv['id']})' value='回复'/>
                         <div class='clear'></div>
                     </form>
                 </div>";
      $conhtml.="</div>";
      if($pes != (count($conarr)-1)){
           $conhtml.='<div class="conline"></div>';
        }
        $pes++;
     } 
      return $conhtml;
}
$cidc = getgpc('cidReq','G');

$cid = $cidc ? $cidc : intval(getgpc('cid','P'));
$countarr = mysql_fetch_row(mysql_query('select count(*) from Comment where cid='.$cid.' and fid=0 and state=1'));
$size = 10;
$countpage = $countarr[0];
$pagesize = ceil($countpage/$size);
$pages=intval(getgpc('page','G'));
    if($pages <= 0){
        $page = 1;
    }else if($pages > $pagesize){
        $page = $pagesize;
    }else{
        $page = $pages;
    }
    if($_POST['con'] === '发表' && $contentc !== '' && $pagesize != 1){
         $page=intval(getgpc('lastpage','P'));
    }
$offset = ($page-1)*$size;

$sql = "select * from Comment where cid={$cid} and fid=0  and state=1 order by id asc limit {$offset},{$size}";
$query = api_sql_query($sql);
while ($row =  mysql_fetch_assoc($query)){
    $rows[]=$row;
}
if(count($rows)){
   echo listcon($rows);
}else{
   echo "<div style='text-align:center;width:100%;height:100px;line-height:100px;font-size:30px;'>本课程还没有任何评论！</div>";
}
$content = Display::display_kindeditor ( 'content', 'basic' );
if($pagesize > 1){
?>
<div id="page">
    <div class="page_inner" style="float:right;">
<?php
     for($i=1;$i <= $pagesize;$i++){
         if($i == $page){$color="style='color:#000;'";}else{$color="";};
?>
         <a href="Comment.php?page=<?=$i?>&cidReq=<?=$cid?>"><span class="pagenum" <?php echo $color;?>><?=$i;?></span></a>
<?php        
     }
?>        
    </div>    
</div>
<?php
}
?>
<div style="height:1px;width:100%;background-color:#EEEEEE;margin-top:10px;"></div>
<div style="margin:10px 0 10px 30px;">发表评论</div>
<form action="Comment.php" method="post">
    <div style="width:90%;margin-left:5%;padding:10px;">
        <textarea id="content" name="content" style="width:100%;height:200px;"></textarea>
    </div>
    <input type="hidden" name="lastpage" value="<?=$pagesize?>"/>
    <input type="hidden" name="cid" value="<?=$cid?>"/>
    <input type="submit" name="con" value="发表"/>
</form>    
<?php
echo $content;
?>
<style type="text/css">
    .conline{
        margin:5px 0 10px 0;
        height:1px;
        width:100%;
        background-color:#EEEEEE;
    }
    .showuser{
        float:left;
        text-align:center;
        width:20%;
        padding:5px;
        min-height:110px;
        margin-right:10px;
    }
    .showuser > img{
        width:100px;
        height:100px;
    }
    #page{
       width:100%; 
       margin:12px 0 6px 0;
       height:25px;
       padding:5px;
    }
    #page>.page_inner>a{
       display:block;
       float:left;
    }
    .pagenum{
       display: block;
        float: left;
        background: #9fd7b2;
        color: #000;
        font-weight: bold;
        margin: 5px;
        padding: 5px;
    }
    .date_div{

        width:100%;
        padding:2px 0 5px 0;
    }
    .date_div:after{
        display:block;
        clear:both;
        content:"";
    }
    .huifu:hover{
        cursor:pointer;
    }
    .huifu{
        padding-right:15px;
        padding-left:15px;
        font-size:8px;
        color:#2d64b3;
    }
    .huiping{
        min-height:55px;
        margin:0 5% 0 25%;
        width:70%;
        background-color:#F7F8FA;
    }
    .zpk{
        margin-left:2%;
        margin-right:2%;
        width:96%;
        min-height:40px;
        padding-top:15px;
        padding-bottom:10px;
        border-bottom:1px #d7d7d7 dotted;
    }
    .zpkimg{
        width:7%;
        float:left;
        display:inline-block;
    }
    .zhhf{
       margin-left:5px;
       cursor:pointer;
    }
    .clear{
        clear: both;
        height: 0;
        line-height: 0;
        font-size: 0;
        visibility: hidden;
        overflow: hidden;
    }
    .plk{
        padding-top:8px;
        padding-left:3%;
        padding-right:3%;
        width:94%;
    }
    .kkhuifu{
        float:left;
        position:relative;
        width:90%;
    }
    .huifuax{
        min-height:20px;
        width:98%;
        word-wrap:break-word;
        line-height:20px;
    }
    .hutime{
        float:right;
        font:13px/18px Arial,SimSun;
    }
    .pagenumsize{
        margin:0px 3%;
    }
    .pagea{
        text-decoration:none;
        white-space:normal;
        padding:4px;
        font-size:15px;
        cursor:pointer;
    }
    .divtext{
        width:100%;
        height:30px;
        border:1px solid #D6DFFA;
        padding-left:2px;
        font-size:16px;
        overflow:auto;
        background-color:#FFFFFF;
    }
    .replay{
        margin-top:5px;
    }
    
</style>
<script type="text/javascript" src="../../themes/js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="../../themes/js/qqimg/jquery.sinaEmotion.js"></script>
<link rel="stylesheet" type="text/css" href="../../themes/js/qqimg/jquery.sinaEmotion.css">
<script type="text/javascript">
    $(function(){
       $(".divtext").html(''); 
    });
  function huifu(id){
      $("#huifu"+id).css('display','none');
      $("#shouqi"+id).css('display','inline-block');
      $("#huiping"+id).slideDown("normal","linear");
  }
  function shouqi(id){
      $("#huiping"+id).slideUp('normal','linear',function(){
          $("#shouqi"+id).hide(0,function(){
              $("#huifu"+id).css('display','inline-block');
          });
      });
  }
  function mincon(id){
       var ssss = document.getElementById('text'+id).innerHTML;
        var val=$("#form"+id).serialize();
       ssss = ssss.replace(/(^s*)|(s*$)/g,"");
       ssss = ssss.replace("<img","&ltimg");
       ssss = ssss.replace(/<\/?[^>]*>/g,'');
       ssss = ssss.replace(">","&gt");
       ssss = ssss.replace(/&nbsp;/ig,'');
       if(ssss == ''){
        document.getElementById('text'+id).innerHTML="";  
        alert('输入格式错误');   
       }else{
        $.ajax({  
                    type: "post",
                    async:false,
                    dataType:'json',
                    url: "coms.php",  
                    data:{'val':val,"text":""+ssss+""},  
                    success: function (result) {
                  if(result.err === 'ok'){  
                     var str="<div class='zpk'>"+
                                "<div class='zpkimg'><img height='32' width='32' src='"+result.url+"'/></div>"+
                                  "<div class='kkhuifu'>"+
                                    "<div class='huifuax'>"+
                                      "<span style='color:#2D64B3;'>"+result.username+"</span>:"+
                                      "<span>"+result.text+"</span>"+
                                    "</div>"+
                                    "<div>"+
                                       "<div class='hutime'>"+result.time+"<span class='zhhf' onclick=\"zhhf("+result.fid+","+result.id+",'"+result.username+"')\";>回复</span></div>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='clear'></div>"+
                            "</div>";
                     $("#text"+result.fid).html('');
                     $("#fdiv"+result.fid).append(str);
                   }else{
                     alert('输入格式错误'); 
                   }  
                    }  
                });
                }
  } 
function holder(id){
   $("#iconbuon"+id).SinaEmotion($("#text"+id));
}
function zhhf(fid,id,name){
    $("#ffid"+fid).val(id);
    $("#text"+fid).html('回复 '+name+' :');
}
function numpage(pageid,id,cid){
    $.ajax({
        url:'pagenum.php',
       data:'pageid='+pageid+'&id='+id+'&cid='+cid,
   dataType:'json',
       type:'post',
       success:function(result){
          var constr = '';
          for(var i in result){
             constr+="<div class='zpk'>"+
                                "<div class='zpkimg'><img height='32' width='32' src='"+result[i].imgpath+"'/></div>"+
                                  "<div class='kkhuifu'>"+
                                    "<div class='huifuax'>"+
                                      "<span style='color:#2D64B3;'>"+result[i].username+"</span>:"+
                                      "<span>"+result[i].text+"</span>"+
                                    "</div>"+
                                    "<div>"+
                                       "<div class='hutime'>"+result[i].time+"<span class='zhhf' onclick=\"zhhf("+id+","+result[i].id+",'"+result[i].username+"')\";>回复</span></div>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='clear'></div>"+
                            "</div>"; 
          }
                     $("#fdiv"+id).html(constr);
                     $("#pagenums"+id+" > a").css('color','#0000EE');
                     $("#pagea"+pageid).css('color','black');
       }
    });
}
</script>