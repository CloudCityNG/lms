<?php
$language_file = 'admin';
$cidReset = true;

include_once ("../../inc/global.inc.php");

header("content-type:text/html;charset=utf-8");
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchRule', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchDesc', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchRewad', 'normal' );
$exam_type = Database::get_main_table (SAI_CONTEST);

$default_begin_date = date ( 'Y-m-d H:i' );
$default_finish_date = date ( 'Y-m-d H:i', strtotime ( "+ 7200 seconds" ) );

$ids=  intval(getgpc('id'));
if($ids!=''){ 
    $sql="select matchName,matchDesc,matchStime,matchEtime,matchSelt,matchAard,matchSite,matchRule,matchRewad,status from $exam_type where id = '".$ids."'";
    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    while($ss = Database::fetch_array ( $res )){
        $ss['matchName']=  htmlspecialchars_decode($ss['matchName']);
        $ss['matchDesc']=  htmlspecialchars_decode($ss['matchDesc']);
        $ss['matchSite']=  htmlspecialchars_decode($ss['matchSite']);
        $ss['matchRule']=  htmlspecialchars_decode($ss['matchRule']);
        $ss['matchRewad']=  htmlspecialchars_decode($ss['matchRewad']);
        $ss['matchStime']=$ss['matchStime'];
        $ss['matchEtime']=$ss['matchEtime'];
        $ss['matchSelt']=$ss['matchSelt'];
        $ss['matchAard']=$ss['matchAard'];
            $ss['status'] = htmlspecialchars_decode($ss['status']);
        $values = $ss;
    }
}                        
                        

$form = new FormValidator ( 'examtype_new','POST','sai_edit.php?id='.$ids.'');

// matchName 赛事名称
$form->addElement ( 'text', 'matchName', get_lang ( "赛事名称" ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );
//赛事状态
$group = array ();
$group [] = HTML_QuickForm::createElement ( 'radio', 'status', null, get_lang ( '开启' ), 1, array () );
$group [] = HTML_QuickForm::createElement ( 'radio', 'status', null, get_lang ( '关闭' ), 0, array ("checked" => "checked") );
$form->addGroup ( $group, 'status', get_lang ( '赛事状态' ), '&nbsp;' );
// matchStime   赛事开启时间
$t=date ( "Y-m-d H:i" );
$t2=date( "Y-m-d H:i", strtotime ( "+ 30 days" ));

$htmlstr=
'<tr class="containerBody"><td class="formLabel">赛事开启时间</td><td class="formTableTd" align="left">
<div id="append_parent"></div>    
<input id="matchStime" 
   readonly="readonly" style="width:24%;height:20px;" class="inputTexts" name="matchStime" type="text"  value="'.date('Y-m-d H:i',$values['matchStime']).
        '" onclick="showcalendar(event,this,true,\'' .$t. '\', \''.$t2 .'\')" />&nbsp;&nbsp;&nbsp;<span 
        style="color:#999999"><i>(格式如下:2014-11-11 10:10:10)</i></span></td></tr>';

$form->addElement('html',$htmlstr);


// matchEtime 赛事结束时间
$form->addElement('html','<tr class="containerBody"><td class="formLabel">赛事结束时间</td><td class="formTableTd" align="left"><input
    style="width:24%;height:20px;" class="inputTex" , name="matchEtime" type="text" id="ent_time" readonly="readonly"  value="'.date('Y-m-d H:i',$values['matchEtime']).'" onclick="showcalendar(event,this,true,\'' .$t. '\', \''.$t2 .'\')">&nbsp;&nbsp;&nbsp;<span 
    style="color:#999999"><i>(格式如下:2014-11-11 10:10:10)</i></span></td></tr>');

//matchSelt   大赛选平
$str=date('Y-m-d H:i',$values['matchSelt']);

$form->addElement('html','<tr class="containerBody"><td class="formLabel">大赛选平</td><td class="formTableTd" align="left"><input
    style="width:24%;height:20px;" class="inputTest" , name="matchSelt" type="text"    value="'.$str.'" id="begin_time" readonly="readonly"  onclick="showcalendar(event,this,true,\'' .$t. '\', \''.$t2 .'\')"">&nbsp;&nbsp;&nbsp;<span 
    style="color:#999999"><i>(格式如下:2014-11-11 10:10:10)</i></span></td></tr>');
//matchAard  大赛颁奖
$form->addElement('html','<tr class="containerBody"><td class="formLabel">大赛颁奖</td><td class="formTableTd" align="left"><input
    style="width:24%;height:20px;" class="inputTet" , name="matchAard" type="text"     value="'.date('Y-m-d H:i',$values['matchAard']).'"  id="end_time" readonly="readonly"  onclick="showcalendar(event,this,true,\'' .$t. '\', \''.$t2 .'\')">&nbsp;&nbsp;&nbsp;<span 
    style="color:#999999"><i>(格式如下:2014-11-11 10:10:10)</i></span></td></tr>');

//matchSite  比赛场地
$form->addElement ( 'text', 'matchSite', get_lang ( '比赛场地' ), array ('style' => "width:250px", 'class' => 'inputText', 'id' => 'lastname' ) );


//matchRewad  大赛规则
$form->addElement ( 'textarea', 'matchRewad', get_lang ( '大赛规则' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );

//matchRule  大赛简介
$form->addElement ( 'textarea', 'matchRule', get_lang ( '大赛简介' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );


// matchDesc 大赛公告
$form->addElement ( 'textarea', 'matchDesc', get_lang ( '大赛公告' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );


$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();
    $matchName= htmlspecialchars($exam_list['matchName']);
    $data=$exam_list  ['matchStime'];
     $is_date=strtotime($data) ? strtotime($data.':00') : false;
       if($is_date){
          $matchStime = $is_date;
       }
       $data_matchEtime=$exam_list  ['matchEtime'];
       $is_data_matchEtime=strtotime($data_matchEtime) ? strtotime($data_matchEtime.':00') : false;
       if($is_data_matchEtime){
            $matchEtime=$is_data_matchEtime;
       }
      $data_matchSelt=$exam_list  ['matchSelt'];
       $is_matchSelt=strtotime($data_matchSelt)?strtotime($data_matchSelt.':00'):false;
       if($is_matchSelt){
           $matchSelt = $is_matchSelt;
       }
       $data_matchAard=$exam_list  ['matchAard'];
       $is_data_matchAard=strtotime($data_matchAard) ? strtotime($data_matchAard.':00') : false;
       if($is_data_matchAard){
           $matchAard=$is_data_matchAard;
      }

    $matchSite       = htmlspecialchars($exam_list['matchSite']);
    $matchRewad  = htmlspecialchars($exam_list['matchRewad']);
    $matchRule     = htmlspecialchars($exam_list['matchRule']);
    $matchDesc     = htmlspecialchars($exam_list['matchDesc']);
    $matchStatus  = $exam_list['status']['status'];

    $sql ="UPDATE `tbl_contest` SET  `matchName` =  '".$matchName."',`matchStime` =  '".$matchStime."',`matchEtime`='".$matchEtime."',
                           `matchSelt` =  '".$matchSelt."',`matchAard` =  '".$matchAard."',`matchSite`='".$matchSite."',
                           `matchRewad` =  '".$matchRewad."',`matchRule` =  '".$matchRule."',`matchDesc`='".$matchDesc."',`status`='".$matchStatus."' WHERE `id` =".$ids;
   
    $re=api_sql_query ( $sql, __FILE__, __LINE__ );
    if($re){
        tb_close( 'sai_list.php' );
    }
}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
?>
<script src="<?=api_get_path ( WEB_JS_PATH )?>js_calendar.js" type="text/javascript"></script>
<script type="text/javascript">
     $("#examtype_new").submit(function(){
        var sai=$(".inputTexts").val();
        var matchArray = sai.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/)
        if (matchArray == null) {
          alert("赛事开启时间的格式错误 " + sai);
          return false;
        }
        var saishi=$(".inputTex").val();
         var matchArray = saishi.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/)
         if (matchArray == null) {
           alert("赛事结束时间的格式错误: " + saishi);
           return false;
         }
          var aa=$(".inputTest").val();
        var matchArray = aa.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/)
        if (matchArray == null) {
          alert("大赛评选格式错误: " + aa);
          return false;
        }
        var aa=$(".inputTet").val();
          var matchArray = aa.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/)
        if (matchArray == null) {
          alert("大赛评选格式错误: " + aa);
          return false;
        }
        return true;
       
     });
</script>