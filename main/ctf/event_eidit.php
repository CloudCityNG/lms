<?php
/*修改赛题
 *  */
include ('../inc/global.inc.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
api_protect_admin_script ();
$id=$_GET['id'];
$event_id=intval($id);
$evenque=mysql_query('select * from tbl_event where id='.$event_id);
$event_row=mysql_fetch_assoc($evenque);
$exam_query=mysql_query('select exam_Name from tbl_exam where id='.$event_row['examId']);
$exam_arr=mysql_fetch_row($exam_query);
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
//所属赛事
    $conquery=mysql_query('select id,matchName from tbl_contest');
    while($conrow=mysql_fetch_assoc($conquery)){
        if($conrow['id']==$event_row['matchId']){
            $conoption='<option value="'.$conrow['id'].'">'.$conrow['matchName'].'</option>';
        }else{
        $htmlContent.='<option value="'.$conrow['id'].'">'.$conrow['matchName'].'</option>';
        }
    }
    $htmlContent.='</select></td></tr>';
    $con='<tr class="containerBody"><td class="formLabel">所属赛事</td><td class="formTableTd" align="left"><select style="width:15%;" name="matchId">';
$form->addElement('html','<input type="hidden" value="'.$event_id.'" name="event_id" />');
$form->addElement('html',$con.$conoption.$htmlContent);
// answer 显示状态
$group = array ();
$group [] = $form->createElement ( 'radio', 'eventState', null, get_lang ( '开放' ), 1 );
$group [] = $form->createElement ( 'radio', 'eventState', null, get_lang ( '关闭' ), 0 );
$form->addGroup ( $group, 'eventState', get_lang ( '显示状态' ), '&nbsp;&nbsp;&nbsp;&nbsp;', false );
$values ['eventState'] = $event_row['eventState'];

// 显示顺序
$form->addElement ( 'text', 'isShow', get_lang ( '显示顺序' ), array ('style'=>'width:30px','class'=>'inputText' ) );
$values['isShow']=$event_row['isShow'];
//提交
$group = array ();
$group [] = & HTML_QuickForm::createElement ( 'style_submit_button', 'submit', get_lang ( 'Save' ), 'class="save"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.location.reload();self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
                  $event_id=$exam_list['event_id'];
	$isShow = $exam_list  ['isShow'];
	$eventState = $exam_list  ['eventState'];
                  $examId=$exam_list['examId'];
                  $matchId=$exam_list['matchId'];
                  $user=$_SESSION['_user']['user_id'];
                  $strTime=time();
    $sql ="update tbl_event set examId={$examId},eventState={$eventState},isShow={$isShow},matchId={$matchId} where id=".$event_id;
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
    <div style="width:100%;">
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
        <form id="form1">
        <div id="lsdiv" style="width:100%;max-height:300px;overflow:scroll;">
            
                <table id="table_2" style="width:100%;border:1px solid #ccc;text-align:center;" cellspacing="0">
                <tr>
                    <td style="width:30px;">&nbsp;</td>
                    <td>题目</td>
                </tr>
<?php  while($class_row=mysql_fetch_assoc($class_query)){  ?>             
                <tr>
                     <td><input  type="radio" name="radiovalue"  value="<?=$class_row['id']?>" /></td>
                    <td id="td<?=$class_row['id']?>"><?=$class_row['exam_Name']?></td>
                </tr>
<?php } ?>                
                </table>
        </div>
            <div align="center"><input type="submit" value="添加"/></div>
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
       height:30px;
       line-height: 30px;
       //display:inline-block;
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
   td{
       border:1px solid #ccc;
   }
</style>
<script src="<?=api_get_path ( WEB_JS_PATH )?>js_calendar.js"></script>
<script type="text/javascript">
 $(function(){
    var sspanstr="<span id='spanstrid' style='display:inline-block;height:30px;line-height:30px;border:1px solid red;margin-left:20px;padding:0 5px 0 5px;'><?=$exam_arr[0];?></span>"; 
    $("tr:eq(2)").after("<tr class='containerBody'><td class='formLabel'>&nbsp;</td><td class='formTableTd' align='left'><a style='cursor:pointer;' onclick='popup();'><img src='../../themes/img/database.gif' style='vertical-align: middle;'>从题库中选题</a></td></tr>");
    $("tr:eq(3)").after("<input type='hidden' name='examId' id='inputid' value='<?=$event_row['examId'];?>'/>");
    $("tr:eq(3)  td:eq(1) > a").after(sspanstr);
    $("#TB_closeWindowButton").click(function(){
       $("#TB_overlay").css('display','none');
       $("#TB_window").css('display','none');
   })
          $("#form1").submit(function(){
                var radio_names=document.getElementsByName("radiovalue");
                for(var i=0;i<radio_names.length;i++){
                             if(radio_names[i].checked){
                               var radio_id=radio_names[i].value;
                                $("#TB_overlay").css('display','none');
                                $("#TB_window").css('display','none');
                                if($("#inputid").val()){
                                   $("#inputid").val(radio_id); 
                                }else{
                                $("tr:eq(3)").after("<input type='hidden' name='examId' id='inputid' value='"+radio_id+"'/>");
                                }
                                var texthtml=$("#td"+radio_id).html();
                                var spanhtml=$("#spanstrid").html();
                                if(spanhtml){
                                    $("#spanstrid").html('');
                                    $("#spanstrid").html(texthtml);
                                    return false;
                                }else{
                                var spanstr="<span id='spanstrid' style='display:inline-block;height:30px;line-height:30px;border:1px solid red;margin-left:20px;padding:0 5px 0 5px;'>"+texthtml+"</span>";
                               $("tr:eq(3)  td:eq(1) > a").after(spanstr);
                                }
                               return false;
                             }
                         }
                         alert('请选择一道题目');
                         return false;
         });
        $("#tbl_event").submit(function(){
          var isshow=$("input[name='isShow']").val();
          if(isNaN(isshow)){
              alert('请确认显示顺序输入是否为数字!');
              return false;
          }
           var intval=$("#inputid").val();
           if(!intval){
              alert('请在题库中选择一道题！');
              return false;
           }
             return true;
        });
        
});
  function showson(id){
      $.ajax({
          url:'showlist.php',
dataType:'html',
       type:'POST',
       data:'id='+id+'&mark=radio',
 success:function(er){
        if(er!=='err'){
            var content='<tr><td style="width:30px;">&nbsp;</td><td>题目</td></tr>'+er;    
            $(".spanls").css('background-color','#5FC187');
            $("#span"+id).css('background-color','#0B7933');    
            $("#table_2").html(content);
         }
       }
      });
  }
  function popup(){
      $("#TB_overlay").css('display','block');
      $("#TB_window").css('display','block');
  }
</script>