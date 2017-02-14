<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chang
 * Date: 13-6-20
 * Time: 上午9:15
 * To change this template use File | Settings | File Templates.
 */
$language_file = 'admin';
$cidReset = true;

include_once ("../../inc/global.inc.php");
//api_protect_admin_script ();
//if (! isRoot ()) api_not_allowed ();
header("content-type:text/html;charset=utf-8");
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchRule', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'matchDesc', 'normal' );
$exam_type = Database::get_main_table (SAI_CONTEST);

$ids=  intval(getgpc('id'));
if($ids!=''){
    $sql="select matchName,matchDesc,matchStime,matchEtime,matchSelt,matchAard,matchSite,matchRule,matchRewad from $exam_type where id = '".$ids."'";
    $res = api_sql_query( $sql, __FILE__, __LINE__ );
    while($ss = Database::fetch_array ( $res )){
        $ss['matchStime']=date("Y-m-d",$ss['matchStime']);
        $ss['matchEtime']=date("Y-m-d",$ss['matchEtime']);
        $values = $ss;
    }
}

$form = new FormValidator ( 'examtype_new','POST','sai_edit.php?id='.$ids.'');


// matchName 赛事名称
$form->addElement ( 'text', 'matchName', get_lang ( "赛事名称" ), array ('maxlength' => 20, 'style' => "width:250px", 'class' => 'inputText' ) );

// matchStime   赛事开启时间
$form->addElement ( 'text', 'matchStime', get_lang ( '赛事开启时间' ), array ('style' => "width:250px", 'class' => 'inputText' ) );

// matchEtime 赛事开启时间
$form->addElement ( 'text', 'matchEtime', get_lang ( '赛事结束时间' ), array ('style' => "width:250px", 'class' => 'inputText' ) );

//matchSelt   大赛选平
$form->addElement ( 'text', 'matchSelt', get_lang ( '大赛选平' ), array ('style' => "width:250px", 'class' => 'inputText' ) );

//matchAard  大赛颁奖
$form->addElement ( 'text', 'matchAard', get_lang ( '大赛颁奖' ), array ('style' => "width:230px", 'class' => 'inputText', 'id' => 'lastname' ) );
//$form->addRule ( 'lastname', get_lang ( 'ThisFieldIsRequired' ), 'required' );

//matchSite  比赛场地
$form->addElement ( 'text', 'matchSite', get_lang ( '比赛场地' ), array ('style' => "width:230px", 'class' => 'inputText', 'id' => 'lastname' ) );


//matchRewad  比赛奖励
$form->addElement ( 'text', 'matchRewad', get_lang ( '比赛奖励' ), array ('style' => "width:230px", 'class' => 'inputText', 'id' => 'lastname' ) );

//matchRule  比赛规程
$form->addElement ( 'textarea', 'matchRule', get_lang ( '比赛规程' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );


// matchDesc 赛事描述
$form->addElement ( 'textarea', 'matchDesc', get_lang ( '赛事描述' ), array ('type'=>'textarea','rows'=>'15','cols'=>'80' ) );
$group = array ();
$group [] = $form->createElement ( 'style_submit_button', 'submit', get_lang ( 'Ok' ), 'class="add"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );


$form->setDefaults ( $values );
$form->add_progress_bar ();
Display::setTemplateBorder ( $form, '90%' );
if ($form->validate ()) {
    $exam_list  = $form->getSubmitValues ();

    $matchName           = $exam_list['matchName'];
    $data=$exam_list  ['matchStime'];
       $is_date=strtotime($data)?strtotime($data):false;
 
       if($is_date===false){
            exit('赛事开始时间日期格式非法');
       }else{
               $matchStime = strtotime(date('Y-m-d',$is_date));
      }
	$data=$exam_list  ['matchEtime'];
       $is_date=strtotime($data)?strtotime($data):false;
 
       if($is_date===false){
            exit('赛事结束时间日期格式非法');
       }else{
               $matchEtime = strtotime(date('Y-m-d',$is_date));
      }
	
    $matchAard    = $exam_list['matchAard'];
    $matchSite        = $exam_list['matchSite'];
    $matchRewad           = $exam_list['matchRewad'];
    $matchRule    = $exam_list['matchRule'];
    $matchDesc        = $exam_list['matchDesc'];
    
    $sql ="UPDATE  `vslab`.`tbl_contest` SET  `matchName` =  '".$matchName."',`matchStime` =  '".$matchStime."',`matchEtime`='".$matchEtime."',
                           `matchSelt` =  '".$matchSelt."',`matchAard` =  '".$matchAard."',`matchSite`='".$matchSite."',
                           `matchRewad` =  '".$matchRewad."',`matchRule` =  '".$matchRule."',`matchDesc`='".$matchDesc."'WHERE  `id` =".$ids;
    //echo  $sql;exit();
    api_sql_query ( $sql, __FILE__, __LINE__ );
   
           tb_close( 'sai_list.php' );
    
   

}
Display::display_header ( $tool_name, FALSE );
$form->display ();
Display::display_footer ();
