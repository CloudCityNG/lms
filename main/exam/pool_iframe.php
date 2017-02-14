<?php
$language_file = array ('exercice', 'admin' );
require_once ('../inc/global.inc.php');

api_block_anonymous_users ();
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
$this_section = SECTION_EXAM;

//include_once (api_get_path ( SYS_CODE_PATH ) . "exam/examination.inc.php");
//$restrict_org_id = protect_exam_script ();

$htmlHeadXtra[]='<script type="text/javascript">
if(document.attachEvent)  window.attachEvent("onload",  iframeAutoFit);  
else  window.addEventListener("load",  iframeAutoFit,  false);
function hidetree() {
	var tree = G("treeview");
	var hidebtn = G("hidebtn"); 
	if (tree.style.display!="none") {				
		G("treeview").style.display = "none";
		G("List").contentWindow.iframeAutoFit();
		G("frm").style.width = "99%";				
	}else {
		G("treeview").style.display = "";
		G("List").contentWindow.iframeAutoFit();
		G("frm").style.width = "80%";						
	}
	//alert(G("frm").style.width);
}
$(document).ready(function(){
	//G("treeview").style.display = "none";
	//G("frm").style.width = "99%";
});
</script>

<style type="text/css">
.framePage {border-top-style:none;	width:100%;	padding-top:0px;	text-align:left;}
#Resources {width:100%;}
#Resources #treeview {	float:left;	border:#999 solid 1px;	width:14%;	}
#Resources #frm {	float:left;	width:85%;}
#Resources #hidebtn {background:url("../../themes/img/hidebtn_off.gif") no-repeat scroll 0 0 transparent;
	color:#818DA5;cursor:pointer;float:left;font-size:12px;height:105px;margin-top:160px;width:6px;}
#Resources #hidebtn a {background:url("../../themes/img/hidebtn.gif") no-repeat scroll 0 0 transparent;
	display:block;height:105px;width:6px;}
#Resources #hidebtn a:hover{background:url(../../../themes/img/hidebtn_off.gif) no-repeat;}
</style>
';
$htmlHeadXtra [] = Display::display_thickbox ();
//$htmlHeadXtra [] = '<script type="text/javascript">
//	function refresh_tree(){ parent.Tree.location.reload();parent.Tree.d.openAll(); }
//	</script>';

include ('../inc/header.inc.php');
?>
<body>
<?php
 
require_once (api_get_path ( INCLUDE_PATH ) . 'lib/mail.lib.inc.php');
include_once ('cls.question_pool.php');
$objQuestionPool = new QuestionPool ();
$redirect_url = 'main/exam/pool_iframe.php';
$id = intval(getgpc ( "id"));

if (isset ( $_GET ["action"] )) {
    switch (getgpc ( "action", "G" )) {
        case "delete" : //删除分类
            $rtn = $objQuestionPool->del_info ( $id );
            if ($rtn == - 1) {
                Display::display_msgbox ( get_lang ( 'DeletePoolsetFailed' ), $redirect_url, 'warning' );
            } elseif ($rtn == 1) {
                Display::display_msgbox ( get_lang ( 'DeletePoolsetSuccess' ), $redirect_url );
            }

            break;
        case "remove" : //删除题库
            if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
           // $rtn = $objQuestionPool->del_info ( $id );

            $sql_pool='DELETE FROM `vslab`.`exam_question` WHERE pool_id='.$id;
            api_sql_query ( $sql_pool, __FILE__, __LINE__ );

            $sql='DELETE FROM `vslab`.`exam_question_pool` WHERE id='.$id;
            $rtn = api_sql_query ( $sql, __FILE__, __LINE__ );

//            if ($rtn == 101) {
//                $message = '删除题库失败! 原因: 题库下有题目,只允许删除空题库';
//                Display::display_msgbox ( $message, $redirect_url, 'warning' );
//            } elseif ($rtn == 1) {
//                Display::display_msgbox ( get_lang ( 'DeletePoolsetSuccess' ), $redirect_url );
//                tb_close($redirect_url);
//            }
            tb_close('pool_iframe.php');
            break;
    }
}

$total_question_cnt = $objQuestionPool->get_pool_question_count ();


//Display::display_header ( null, FALSE );


$table_header [] = array (get_lang ( "PoolName" ) );
$table_header [] = array (get_lang ( "PoolQuestionCount" ) );
$table_header [] = array (get_lang ( "UniqueSelect" ) );
$table_header [] = array (get_lang ( "MultipleSelect" ) );
$table_header [] = array (get_lang ( "TrueFalseAnswer" ) );
$table_header [] = array (get_lang ( "FreeAnswer" ) );
$table_header [] = array (get_lang ( "实战题" ) );
$table_header [] = array (get_lang ( "DisplayOrder" ), false, null, array ('width' => '80' ) );
//$table_header[] = array(get_lang("Remark"));
//$table_header[] = array(get_lang("LastUpdatedDate"),null,array('width'=>'150'));
$table_header [] = array (get_lang ( "Actions" ), false, null, array ('width' => '120' ) );

$poolset = $objQuestionPool->get_list ();
$cnt = count ( $poolset );
foreach ( $poolset as $k => $pool_set ) {
    $row = array ();
    $row [] = $pool_set ['pool_name'];
    $row [] = $objQuestionPool->get_pool_question_count ( $pool_set ['id'] );
    $row [] = $objQuestionPool->get_pool_question_count ( $pool_set ['id'], 1 );
    $row [] = $objQuestionPool->get_pool_question_count ( $pool_set ['id'], 2 );
    $row [] = $objQuestionPool->get_pool_question_count ( $pool_set ['id'], 3 );
    $row [] = $objQuestionPool->get_pool_question_count ( $pool_set ['id'], 6 );
    $row [] = $objQuestionPool->get_pool_question_count ( $pool_set ['id'], 10 );
//    $row [] = $pool_set ['pool_desc'];
    $row [] = $pool_set ['display_order'];
 
    $action = '&nbsp;&nbsp;<a href="../exercice/question_base.php?pool_id=' . $pool_set ["id"] . '">' . Display::return_icon ( 'questionsdb.gif', get_lang ( 'QuestionList' ), array ('style' => 'vertical-align: middle;', 'width' => 24, 'height' => 24 ) ) . '</a>&nbsp;';
    if (isRoot ()) {
        $action .= '&nbsp;&nbsp;' . link_button ( 'edit.gif', 'Edit', 'pool_update.php?action=edit_pool&id=' . $pool_set ['id'], 220, 600, FALSE );
        $href = 'pool_iframe.php?action=remove&amp;id=' . $pool_set ["id"] . '&amp;pid=' . $pool_set ['pid'];
        $action .= '&nbsp;&nbsp;' . confirm_href ( 'delete.gif', 'DeletePoolsetConfirm', 'Delete', $href );
    }
    if($_SESSION['_user']['status']=='1' && $pool_set['user_id'] == $_SESSION['_user']['user_id']){
         $action .= '&nbsp;&nbsp;' . link_button ( 'edit.gif', 'Edit', 'pool_update.php?action=edit_pool&id=' . $pool_set ['id'], 220, 600, FALSE );
        $href = 'pool_iframe.php?action=remove&amp;id=' . $pool_set ["id"] . '&amp;pid=' . $pool_set ['pid'];
        $action .= '&nbsp;&nbsp;' . confirm_href ( 'delete.gif', 'DeletePoolsetConfirm', 'Delete', $href );
    }
    $row [] = $action;
    $table_data [] = $row;
}


if (getgpc("refresh","G")) echo '<script>refresh_tree();</script>';

//Display::display_footer ();
if($platform==3){
    $nav='exercices';
}else{
    $nav='exercice';
}
?>
    <aside id="sidebar" class="column exercices open">
        <div id="flexButton" class="closeButton close">
        </div>
    </aside>
<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/exercice/exercice.php">考试管理</a> &gt; 题库管理</h4>

    <div class="managerSearch">
      <span class="seachtxt right"> 
 <?php
         echo str_repeat ( '&nbsp;', 2 ) . link_button ( 'stats_access.gif', 'AddPool', 'pool_update.php?action=add_pool', '40%', '60%' );
        if($total_question_cnt>0)
            echo str_repeat ( '&nbsp;', 2 ) . link_button ( 'surveyadd.gif', 'AllQuestions', '../exercice/question_base.php', NULL, NULL );?>
    </span> 
    </div>
    <article class="module width_full hidden">
        <table cellspacing="0" cellpadding="0" class="p-table">
           <?php
            echo Display::display_table ( $table_header, $table_data );
            ?>
        </table>
    </article>
</section>
</body>
</html>


