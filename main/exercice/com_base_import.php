<?php
include_once ('exercise.class.php');
include_once ('question.class.php');
include_once ('answer.class.php');

$language_file = 'exercice';
include_once ('../inc/global.inc.php');
include_once ('exercise.lib.php');
//api_protect_quiz_script ();

define ( "QUESTION_OPTION_SPLIT_CHAR", "|" );
set_time_limit ( 0 );
require_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'export.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'import.lib.php');

$form = new FormValidator ( 'export_questions' );
$form->addElement ( 'hidden', 'type', getgpc ( 'type', 'G' ) );

$sql = "SELECT id,pool_name FROM " . $tbl_exam_question_pool . "  ORDER BY display_order ASC";
$all_pools = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
$form->addElement ( 'select', 'pool_id', get_lang ( "QuestionPool" ), $all_pools, array ('style' => "min-width:50%" ) );
$defaults ['pool_id'] =intval (  getgpc ( 'pool_id' ));

$sql = "SELECT code,title FROM " . $tbl_course . "";
$all_courses = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
//$all_courses = array_insert_first ( $all_courses, array ('' => '---使用导入文件中的课程编号设置---' ) );
//$form->addElement ( 'select', 'cc', get_lang ( "Courses" ), $all_courses, array ('style' => "min-width:50%", 'id' => 'course_code' ) );

//选择文件
$form->addElement ( 'file', 'import_file', get_lang ( 'ImportFileLocation' ), array ('style' => "width:70%", 'class' => 'inputText' ) );
$form->addRule ( 'import_file', get_lang ( 'ThisFieldIsRequired' ), 'required' );
$allowed_file_types = array ('xls' );
$form->addRule ( 'import_file', get_lang ( 'InvalidExtension' ) . ' (' . implode ( ',', $allowed_file_types ) . ')', 'filetype', $allowed_file_types );

$group = array ();
$group [] = $form->createElement ( 'submit', 'submit', get_lang ( 'Ok' ), 'class="inputSubmit"' );
$group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
$form->addGroup ( $group, 'submit', '&nbsp;', null, false );

$form->setDefaults ( $defaults );

Display::setTemplateBorder ( $form, '98%' );
$form->add_progress_bar ();

if ($form->validate ()) {
    $post_data = $form->getSubmitValues ();
    $pool_id = trim ( $post_data ['pool_id'] );
    if ($_FILES ['import_file'] ['size'] !== 0) {
        $save_path = $_FILES ['import_file'] ['tmp_name'];
        set_time_limit ( 0 );
        $file_type = getFileExt ( $_FILES ['import_file'] ['name'] );
        $file_type = strtolower ( $file_type );
        if ($file_type == 'xls') {
            $data = Import::parse_to_array ( $save_path, 'xls' );
            $data_rows = $data ['data'];
            $question_data = parse_upload_data ( $data_rows );

            save_data ( $question_data, $pool_id );
        } else {

        }

        my_delete ( $_FILES ['import_file'] ['tmp_name'] );

        tb_close ( 'question_base.php?pool_id=' . $pool_id );
    }
}

function save_data($data,$pool_id) {
    if (empty ( $data )) return false;
    else {
        $in_sql = '';
        $count_data = count($data);
      foreach($data as $data_k => $data_v){
          if($data_k == ($count_data-1)){
              $fenhao = ';';
          }else{
              $fenhao = ',';
          }
          $in_sql.= "(null,0,10,null,$pool_id,'".$data_v['ponderation']."','".$data_v['question']."','',".$data_v['level'].",1,0,'".$data_v['comment']."','','".$data_v['question_code']."',1,1,null,1,null,1,null,'".$data_v['picture']."',null,".$data_v['is_up'].",'".$data_v['vm_name']."','".$data_v['contents']."',1,'','".$data_v['keyss']."','".$data_v['is_k']."','".$data_v['key_score']."')".$fenhao;
      }
      $in_sqlss = 'insert exam_question values '.$in_sql;
      mysql_query($in_sqlss);
    }
}

function parse_upload_data($data_rows) {

    $allQuestions = array ();

    if (is_array ( $data_rows ) && count ( $data_rows ) > 0) {
        foreach ( $data_rows as $key => $item ) {
            $questionItem = array ();
            $question_type = trim ( $item ['试题类型'] );

            if($question_type == '实战题'){
                //题型
                $questionItem ['type'] = COMBAT_QUESTION;
                $questionItem ['question_code'] = $item['编号'];
                $questionItem ['question'] = str_replace("'",'&#39;',$item['题目']);
                $questionItem ['contents'] = str_replace("'",'&#39;',$item['内容']);
                if($item['是否有key值'] == '是') {
                    $questionItem ['is_k'] = 1;
                }else{
                    $questionItem ['is_k'] = 0;
                }
                $questionItem ['picture'] = $item['上传文件'];
                $questionItem ['level'] = $item['难度'];
                $questionItem ['is_up'] = $item['是否有报告'];
                $questionItem ['ponderation'] = $item['分数'];
                $questionItem ['comment'] = htmlspecialchars($item['题目解析']);
                $questionItem ['vm_name'] = $item['科目场景'];
                $key_score = array();
                $key_score = array_values(array_slice($item,11));
                $keys_arr = array();
                $score_arr = array();
                foreach($key_score as $key_score_k=>$key_score_v){
                     $key_score_arr = array();
                     $key_score_arr = explode('--->',$key_score_v);
                     if(!empty($key_score_arr[0])){
                         $keys_arr[] = $key_score_arr[0];
                     }
                     if(!empty($key_score_arr[1])){
                         $score_arr[] = $key_score_arr[1];
                     }
                }
                if(count($keys_arr)){
                    $questionItem ['keyss'] = serialize($keys_arr);
                }
                if(count($score_arr)){
                    $questionItem ['key_score'] = serialize($score_arr);
                }
            }
               $allQuestions[] = $questionItem;
        }
        return $allQuestions;
    }
    return array ();
}

$htmlHeadXtra [] = '<script type="text/javascript">
	$(document).ready( function() {
		$("#course_code").parent().append("<div class=\'onShow\'><br/>请注意,导入文件中的课程编号必须设置正确,否则会以当前选择的课程代替!</div>");
	});</script>';

Display::display_header ( NULL, FALSE );

$form->display ();

Display::display_footer ();
