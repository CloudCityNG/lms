<?php
include_once ('../inc/global.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
api_protect_admin_script ();
//获取赛题最底层分类
function showson($fid){
    $query=mysql_query('select count(*) from tbl_class where fid='.$fid);
    $countarr=mysql_fetch_row($query);
    if($countarr[0]){
        $fquery=mysql_query('select id from tbl_class where fid='.$fid);
    while($frow=mysql_fetch_assoc($fquery)){
        $strlang.=showson($frow['id']);
    }
    }else{
        $cquery=mysql_query('select className from tbl_class where id='.$fid);
        $carr=mysql_fetch_row($cquery);
        $strlang.=','.$fid.';'.$carr[0];
    }
    return $strlang;
}
$strlang=showson(0);
$strarr=explode(',', $strlang);
for($i=1;$i<count($strarr);$i++){
    $class_arr=explode(';', $strarr[$i]);
    $class_lang[$class_arr[0]]=$class_arr[1];
}
$form = new FormValidator ( 'tbl_event' );
$conquery=mysql_query('select id,matchName from tbl_contest');
$htmlContent='<tr class="containerBody"><td class="formLabel">所属赛事</td><td class="formTableTd" align="left"><select style="width:15%;" id="matchId" name="matchId">';
while($conrow=mysql_fetch_assoc($conquery)){
    $htmlContent.='<option value="'.$conrow['id'].'">'.$conrow['matchName'].'</option>';
}
$htmlContent.='</select></td></tr>';
$form->addElement('html',$htmlContent);

// answer 显示状态
$group = array ();
$group [] = $form->createElement ( 'radio', 'eventState', null, get_lang ( '开放' ), COURSE_VISIBILITY_REGISTERED );
$group [] = $form->createElement ( 'radio', 'eventState', null, get_lang ( '关闭' ), COURSE_VISIBILITY_CLOSED );
$form->addGroup ( $group, 'eventState', get_lang ( '显示状态' ), '&nbsp;&nbsp;&nbsp;&nbsp;', false );
$values ['eventState'] = COURSE_VISIBILITY_REGISTERED;

$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
    
	$isShow = intval($exam_list  ['isShow']);
	$eventState = $exam_list  ['eventState'];
                  $examId=$exam_list['examId'];
                  $matchId=$exam_list['matchId'];
                  $user=$_SESSION['_user']['user_id'];
                  $strTime=time();
    $sql ="INSERT INTO tbl_event (`id`,`examId` ,`eventState`,`isUser`,`isShow`,`sTime`,`matchId`) VALUES  ('',$examId,$eventState,$user,$isShow,$strTime,$matchId)";
    api_sql_query ( $sql, __FILE__, __LINE__ );

    tb_close( 'exam_question.php' );

}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>
<div id="TB_overlay" class="TB_overlayBG" style="display:none;"></div>
<div id="TB_window" style="display: none;">
    <div id="TB_title">
        <div id="TB_ajaxWindowTitle">从题库中选题</div>
        <div id="TB_closeAjaxWindow">
            <a id="TB_closeWindowButton" href="#" title="关闭" style="color: #FFF;">关闭</a>
        </div>
    </div>
    <div style="width:80%; margin:0 auto;">
        <div class="tip-list"   style="width:100%;">
<?php 
   $j=0;
    foreach($class_lang as $classk=>$classv){
        if($j===0){
            $class_id=$classk;
        }
?>            
            <div class="spanls" title="<?=$classv;?>" <?php echo $style=$j===0 ? 'style="background-color:#0b7933;"' : '';?> id="span<?=$classk;?>" onclick="showson(<?=$classk;?>);"><?=$classv;?></div>
     <?php 
     $j++;
    } 
    $class_query=mysql_query('select id,exam_Name from tbl_exam where classId='.$class_id);
    ?>                    
        </div>
        <form id="form1" style="margin-top:10px;">
        <div id="lsdiv" style="width:99%;margin:0 auto;max-height:300px;overflow:scroll;">
            
                <table id="table_2" style="width:100%;border:1px solid #ccc;text-align:center;" cellspacing="0">
                <tr>
                    <td style="width:50px;"><input  type="checkbox" id="allchid" onclick="allcheck();" /></td>
                    <td>题目</td>
                </tr>
<?php  while($class_row=mysql_fetch_assoc($class_query)){  ?>             
                <tr>
                    <td><input  type="checkbox" name="radiovalue"  value="<?=$class_row['id']?>" /></td>
                    <td id="td<?=$class_row['id']?>"><?=  htmlspecialchars_decode($class_row['exam_Name'])?></td>
                </tr>
<?php } ?>                
                </table>
        </div>
            <div align="center"><input type="button" id="form-bun" value="添加"/></div>
      </form>
    </div>
</div>
<style>
   #TB_overlay{ 
    position: fixed;
    z-index: 100;
    top: 0px;
    left: 0px;
    height: 100%;
    width: 100%;
   }
   .TB_overlayBG{
       background-color: #000;
       opacity: 0.80;
   }
   #TB_window{
       width:99%;
       height:90%;
       overflow: scroll;
       position: fixed;
      background: #eeeeee;
      z-index: 999;
     color: #000000;
    border-radius: 10px;
    border: 8px solid #525252;
    text-align: left;
    top:0px;
    left: 0px;
   }
   #TB_title{
       background-color: #292929;
       border-top: 1px solid #515151;
       border-bottom: 1px solid #222;
       height: 35px;
       line-height: 30px;
   }
   #TB_ajaxWindowTitle{
       float: left;
        margin-bottom: 1px;
        color: #FFF;
        font-weight: bold;
        font-size: 13px;
   }
   #TB_closeAjaxWindow{
        margin-bottom: 1px;
        text-align: right;
        float: right;
   }
   .spanls{
       width:19%;
       height:45px;
       line-height: 45px;
      display:inline-block;
       text-align: center;
       cursor:pointer;
       background-color:#5FC187;
       padding:0;
       margin: 0px 0.5% 2px 0.5%;
       color:#FFFFFF;
       float:left;
   }
   .spanls:hover{
       background-color:#0B7933;
   }
   .tip-list:after{
       display:block;
       clear:both;
       content:"";
   }
   .tip-list{
       padding:0;
       margin-top:10px;
   }
   #table_2 tr td{
       line-height:30px;
   }
   td{
       border:1px solid #ccc;
   }
   .add{
       height:30px;
       width:70px;
   }
</style>
<script src="<?=api_get_path ( WEB_JS_PATH )?>jquery.js"></script>
<script type="text/javascript">
    function insert_event(mark){
       var matchId=$("#matchId").val();
       var eventState=$("input[type='radio']:checked").val();
    
       var str='';
        $("input[name='radiovalue']").each(function(){
            if($(this).attr('checked')){
              str+=','+$(this).val();        
            } 
        });
      if(str){
                    $.ajax({
                     url:'insert_exam.php',
           dataType:'html',
                 type:'GET',
                 data:'str='+str+'&matchId='+matchId+'&eventState='+eventState,
            success:function(er){
                 if(mark==='location'){
                             window.parent.location.href='./exam_question.php';
                  }
                 }
                 }); 
      }
}
 $(function(){
    $("tr:eq(1)").after("<tr class='containerBody'><td class='formLabel'>&nbsp;</td><td class='formTableTd' align='left'><a style='cursor:pointer;' onclick='popup();'><img src='../../themes/img/database.gif' style='vertical-align: middle;'>从题库中选题</a></td></tr>");
   $("#TB_closeWindowButton").click(function(){
       $("#TB_overlay").css('display','none');
       $("#TB_window").css('display','none');
   });
          $("#form-bun").click(function(){
              insert_event('location');
         });
});

 function allcheck(){
          var allid=document.getElementById("allchid");
            if(allid.checked){ 
                    $("input[name='radiovalue']").each(function(){this.checked=true;});
            }else{ 
                    $("input[name='radiovalue']").each(function(){this.checked=false;}); 
            } 
        }

  function showson(id){
            $.ajax({
                url:'showlist.php',
      dataType:'html',
             type:'POST',
             data:'id='+id+'&mark=checkbox',
       success:function(er){
           if(er!=='err' ){
                var content='<tr><td style="width:30px;"><input  type="checkbox" id="allchid" onclick="allcheck();"  /></td><td>题目</td></tr>'+er; 
                $(".spanls").css('background-color','#5FC187');
                $("#span"+id).css('background-color','#0B7933'); 
                if(!er){
                    content='<tr style="color:red;"><td>此分类下没有题目</td></tr>';
                }
                $("#table_2").html(content);
             }
               }
            });

          insert_event('mm');
  }
  function popup(){
      $("#TB_overlay").css('display','block');
      $("#TB_window").css('display','block');
  }
</script>