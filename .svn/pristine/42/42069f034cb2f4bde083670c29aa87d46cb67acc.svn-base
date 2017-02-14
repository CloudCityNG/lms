<?php
/**
==============================================================================
 * @package zllms.admin
==============================================================================
 */
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

    $data = array ();
    $data [] = array ('试题类型','编号',"题目","内容","是否有key值", "上传文件","难度","是否有报告","分数","题目解析","科目场景","key1","key2","key3","key4","key5");
    $key_title= array("key1","key2","key3","key4","key5");
    $filename = 'ExportQuestions_' . api_get_course_code () . '_' . date ( 'YmdHis' ); //导出文件名
    $file_type = 'xls';
    $sql = 'select * from exam_question where type=10';
    $quety_res = mysql_query($sql);

    while($row = mysql_fetch_assoc($quety_res)){
        $rows[] = $row;
    }

    $rows_arr = array();
    foreach($rows as $rows_k => $row_v){
        $rows_arr['试题类型'] = '实战题';
        $rows_arr['编号'] = $row_v['question_code'];
        $rows_arr['题目'] = $row_v['question'];
        $rows_arr['内容'] = $row_v['contents'];
        if($row_v['is_k'] == 1) {
            $rows_arr['是否有key值'] = '是';
        }else{
            $rows_arr['是否有key值'] = '否';
        }
        $rows_arr['上传文件'] = $row_v['picture'];
        $rows_arr['难度'] = $row_v['level'];
        $rows_arr['是否有报告'] = $row_v['is_up'];
        $rows_arr['分数'] = $row_v['ponderation'];
        $rows_arr['题目解析'] = $row_v['comment'];
        $rows_arr['科目场景'] = $row_v['vm_name'];
        if($row_v['is_k'] == 1){
            $keyss_arr = array();
            $key_score = array();
            $key_con = '';
            $keyss_arr = unserialize($row_v['keyss']);
            $key_score = unserialize($row['key_score']);
            foreach($keyss_arr as $keyss_k => $key_score){
                $rows_arr[$key_title[$keyss_k]] = $key_score.'--->'.$key_score[$keyss_k];
            }
        }
        $questions[] = $rows_arr;
    }

    foreach ( $questions as $question ) {
        if ($file_type == 'csv') {
            $export_encoding = 'GBK';
            $question ['试题内容'] = mb_convert_encoding ( $question ['试题内容'], $export_encoding, SYSTEM_CHARSET );
            $question ['题目解析'] = mb_convert_encoding ( $question ['题目解析'], $export_encoding, SYSTEM_CHARSET );
        }
        $data [] = $question;
    }


    switch ($file_type) {
        case 'csv' :
            Export::export_table_data ( $data, $filename, 'csv' );
            break;
        case 'xls' :
            Export::export_table_data ( $data, $filename, 'xls', false );
            break;
    }
    header('Location:question_base.php');

