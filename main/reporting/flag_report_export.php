<?php
include_once ('exercise.class.php');
include_once ('question.class.php');
include_once ('answer.class.php');

$language_file = 'exercice';
include_once ('../inc/global.inc.php');
include_once ('exercise.lib.php');

define ( "QUESTION_OPTION_SPLIT_CHAR", "|" );
set_time_limit ( 0 );
include (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');
$alpha = array ('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
$types=  intval ( getgpc('type'));
$form = new FormValidator ( 'export_report_flag' );
$form->addElement ( 'hidden', 'type', getgpc ( 'type', 'G' ) );
$form->addElement ( 'html', '<div style="margin-top:2px;margin-left:30px;"><h2>您确定要导出报告吗？</h2></div>');
$form->addElement ( 'hidden', 'file_type', 'xls' );
$form->addElement ( 'hidden', 'type1', $types );
include (api_get_path ( INCLUDE_PATH ) . "conf/templates.php");

$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->setDefaults ( $defaults );

Display::setTemplateBorder ( $form, '98%' );
if ($form->validate ()) {
    $export = $form->exportValues ();
    $export = $form->getSubmitValues ();
    $file_type = $export ['file_type'];
    $type = $export ['type'];
    $type1 = $export ['type1'];
    $data = array ();
    $data [] = array ('编号', "报告名称", "用户名","用户真实名称", "用户部门", "用户组", "时间", "得分", "评语", "结果" );
    $filename = 'ExportReport_flag_' . date ( 'YmdHis' );
    $sql = "select reporting_info.id,reporting_info.report_name,reporting_info.user,user.firstname,user.dept_id,user.username,reporting_info.submit_date,reporting_info.score,reporting_info.comment,
    reporting_info.return from user right join reporting_info on user.username=reporting_info.user where reporting_info.marking_status=1 and reporting_info.type=".$type1;
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
    $questions = array ();
    $line = 2;
    while ( $row = Database::fetch_array ( $res) ) {
        $group_id=Database::getval("select group_id from user where username='".$row['username']."'",__FILE__,__LINE__);
        $group_name=Database::getval("select name from group_user where id='".$group_id."'",__FILE__,__LINE__);
        $dept_name=Database::getval("select dept_name from sys_dept where id='".$row['dept_id']."'",__FILE__,__LINE__);
        $row ['编号'] = $row['id'];
        $row ['报告名称'] = $row['report_name'];
        $row ["用户名"] = $row ["user"];
        $row ["用户真实名称"] = $row ["firstname"];
        $row ["用户部门"] = $dept_name;
        $row ["用户组"] = $group_name;
        $row ["时间"] = $row ["submit_date"];
        $row ["得分"] = $row ["score"];
        $row ['评语'] = $row ["comment"];
        if($row ['return']==1){
            $return='通过';
        }else{
            $return='未通过';
        }
        $row ['结果'] = $return;
        unset ( $row ["id"], $row ["id"] );
        unset ( $row ["report_name"], $row ["report_name"] );
        unset ( $row ["user"], $row ["user"] );
        unset ( $row ["submit_date"], $row ["submit_date"] );
        unset ( $row ["score"], $row ["score"] );
        unset ( $row ["comment"], $row ["comment"] );
        unset ( $row ["return"], $row ["return"] );
        unset ( $row ["firstname"], $row ["firstname"] );
        unset ( $row ["dept_id"], $row ["dept_id"] );
        unset ( $row ["username"], $row ["username"] );
        $questions [] = $row;
        $line ++;
    }

    foreach ( $questions as $question ) {
        $data [] = $question;
    }
//echo '<pre>';
//    var_dump($data);
//echo '</pre>';exit();
    switch ($file_type) {
        case 'xls' :
            Export::export_table_data ( $data, $filename, 'xls', false );
            break;
    }
    tb_close ( 'flag_report_export.php' );
}
Display::display_header ( null, FALSE );

$form->display ();

Display::display_footer ();