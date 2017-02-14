<?php
include_once ('exercise.class.php');
include_once ('question.class.php');
include_once ('answer.class.php');

$language_file = 'exercice';
include_once ("../inc/global.inc.php");

include_once ('exercise.lib.php');
//api_protect_quiz_script ();

require_once (api_get_path ( LIBRARY_PATH ) . 'fileManage.lib.php');
include_once (api_get_path ( LIBRARY_PATH ) . 'fileDisplay.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . "attachment.lib.php");
include_once (api_get_path ( LIBRARY_PATH ) . 'document.lib.php');

$action = (isset ( $_REQUEST ['action'] ) ? getgpc ( "action" ) : "");
$type = intval(getgpc ( 'answerType' ));
$qid = intval ( getgpc ( 'qid' ));
$actions = getgpc ( 'action' );
$combo_questionId = intval ( getgpc ( "pid" ));

if ($action && $action == 'add') {
    $objQuestion = Question::getInstance ( $type );
    $form_action = $_SERVER ['PHP_SELF'];
}
if ($action && $action == 'edit') {
    $objQuestion = Question::read ( $qid );
    $form_action = $_SERVER ['PHP_SELF'];
}

$htmlHeadXtra [] = Display::display_kindeditor ( 'description', 'normal' );
$htmlHeadXtra [] = Display::display_kindeditor ( 'description1', 'normal' );
Display::display_header ( null, FALSE );


if (is_object ( $objQuestion ) && $objQuestion) {
    $form = new FormValidator ( 'question_admin_form', 'post', $form_action );

    //题干(公共部分)
    $hide_question_name = empty ( $_REQUEST ['hideQN'] ) ? false : true;
    $form->addElement ( 'hidden', 'hideQN', $_REQUEST ['hideQN'] );
    $objQuestion->createForm ( $form );

    //答案、选项
    $objQuestion->createAnswersForm ( $form );

    $form->addElement ( 'hidden', 'pid', empty ( $combo_questionId ) ? '0' : $combo_questionId );
    $form->addElement ( 'hidden', 'action', $action );
    if ($action && $action == 'edit') $form->addElement ( 'hidden', 'qid', $qid );
    if($type === COMBAT_QUESTION) {
        //科目场景
        $title_sql = "select name FROM  `vslab`.`vmdisk` ";
        $title_res = api_sql_query($title_sql, __FILE__, __LINE__);
        $vm = array();
        $title['0'] = '无';
        while ($vm = Database::fetch_row($title_res)) {
            $vms [] = $vm;
        }
        foreach ($vms as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $title[$v2] = $v2;
            }
        }

        $form->addElement('select', 'vm_name', "请选择科目场景", $title, array('id' => "vm_name", 'class' => 'select'));
        $renderer = $form->defaultRenderer();
        $default_template = '<tr class="containerBody"><td class="formLabel">{label}</td><td class="formTableTd" align="left">{element}</td></tr>';
        $renderer->setElementTemplate($default_template, 'vm_name');
    }
    $sql_uid = "select created_user from exam_question where id= ".$qid." ";
    $data_res = Database::getval($sql_uid,__FILE__LINE__);
    
    //提交按钮
    $group = array ();
    if(isRoot() || $actions == 'add'){
    $group [] = $form->createElement ( 'submit', 'submitQuestion', get_lang ( 'Ok' ), 'class="inputSubmit" id="submitQuestion"' );    
    }else if($_SESSION['_user']['status']== '1' && $data_res == $_SESSION['_user']['user_id'] && $actions == 'edit'){
    $group [] = $form->createElement ( 'submit', 'submitQuestion', get_lang ( 'Ok' ), 'class="inputSubmit" id="submitQuestion"' );
    }
    
    $group [] = $form->createElement ( 'style_button', 'cancle', null, array ('type' => 'button', 'class' => "cancel", 'value' => get_lang ( 'Cancel' ), 'onclick' => 'javascript:self.parent.tb_remove();' ) );
    //$group[] =$form->createElement('style_button', 'submitQuestion',null,array('type'=>'button','class'=>"inputSubmit",	'value'=>get_lang('Ok'),'id'=>'submitQuestion'));
    //$goback_url=(empty($exerciseId)?"question_base.php":'admin.php?exerciseId='.$exerciseId);
    //$group[] =$form->createElement('style_button', 'back',null,array('type'=>'button','class'=>"back",'value'=>get_lang('Back'),'onclick'=>'javascript:location.href=\''.$goback_url.'\';'));
    $form->addGroup ( $group, 'submit', '&nbsp;', null, false );
    $default_template = '<tr class="containerBody"><td class="formLabel">{label}</td><td class="formTableTd" align="left">{element}</td></tr>';
    $renderer = $form->defaultRenderer ();
    $renderer->setElementTemplate ( $default_template, 'submit' );

    $form->addElement ( 'html', '</table>' );

    //Display::setTemplateBorder($form, '98%');
    $form_template = '<form {attributes}><table align="center" width="98%" cellpadding="4" cellspacing="0">{content}</table></form>';
    $renderer->setFormTemplate ( $form_template );

    if (isset ( $_POST ['submitQuestion'] ) && $form->validate ()) {

        // 问题题干的创建（题目及公共部分）
        $objQuestion->processCreation ( $form, $objExercise );

        // 选项及答案保存
        $objQuestion->processAnswersCreation ( $form );
        tb_close ();
    } else {
        echo '<style>div.row div.label{width: 10%;float:right;}div.row div.formw{width: 90%;float:right;}</style>	';
        $form->display ();
    }
}

Display::display_footer ();
?>
<script  type="text/javascript">
    if(document.getElementById("is_key1").checked) {
        $("#key_tab").parent().parent().hide();
        $("#add_sub").hide();}
    $("#is_key1").click(function(){
        $("#key_tab").parent().parent().hide();
        $("#add_sub").hide();
    })
    $("#is_key2").click(function(){
        $("#key_tab").parent().parent().show();
        $("#add_sub").show();
    })

    $('#vm_search').keyup(function(){
        var url="<?=api_get_path ( WEB_CODE_PATH ) . "admin/ajax_actions.php"?>";
        var keyword_val= $("#vm_search").val();
        $.ajax({type:"post", data:{action:"vms_search",keyword:keyword_val},
            url:url, dataType:"html",cache:false,
            success:function(data){
                $("#vm_name").html(data);
            },
            error:function() { alert("Server is Busy, Please Wait...");}
        });
    })

</script>
