<?php
//题库
include_once ('../inc/global.inc.php');
$redirect_url = 'main/exam/pool_iframe.php';
$close_url ='main/exercice/question_base.php';
$TBL_QUESTIONS = Database::get_main_table ( 'tbl_exam' );
$htmlHeadXtra [] = Display::display_thickbox ();
$nameTools = get_lang ( 'QuestionPoolManagement' );
include_once ('../inc/header.inc.php');
$id = $_GET['id'];
//搜索->所属分类
function hasCildren($id){//判断分类是否有子分类
    $c_table = Database::get_main_table ( 'tbl_class' );
    $sql = "select count(*) from $c_table where fid= $id"; 
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );//查询子类 
    if($res){
        $rows=Database::fetch_row ($res);
    }
    return $rows[0];
}

function downLoadfilter($path){
    if($path){
        return '<a href="'.$path.'"><img src="../../themes/img/down.gif" alt="下载报告" title="下载报告" style="vertical-align: middle;"></a>';
    }else{
        return '<img src="../../themes/img/down_na.gif" alt="没有报告" title="没有报告" style="vertical-align: middle;">';
    }
}

$sel= '<select name="classId">
      <option value="all">--全部分类--</option>';

function get_cname($id=0,$tab=0,$str='--'){
    global $sel;
    $c_table = Database::get_main_table ( 'tbl_class' );
    $sql = "select id,className,fid from $c_table where fid= $id"; 
    $res = api_sql_query ( $sql, __FILE__, __LINE__ );//查询子类 
    $rows = array(); 
        while($rows=Database::fetch_row ($res)){ //循环记录集
            if(!hasCildren($rows[0])){
              
                $sel.= "<option value='".$rows['0']."'>".$pre.$rows['1']. "</option>";
             
            }else{
                get_cname($rows['0']);
            }
           
        }
     return $sel;
}

$cname=get_cname();
$cname.='</select>';

if (isset ( $_POST ['action'] )) {
	switch ($_POST ['action']) {
		case 'delete' ://批量删除
			$number_of_selected_exams = count ( $_POST ['id'] );
			$number_of_deleted_exams = 0;
			foreach ( $_POST ['id'] as $index => $exam_id ) {
                            $exam_table = Database::get_main_table ( 'tbl_exam' );
                            $sql = "SELECT uploadText FROM $exam_table WHERE id = $exam_id";
                            $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                            $rows=Database::fetch_row ($res);
                            $path=$rows[0];
                            if($path!=''){
                                if(unlink($path) || !file_exists($path)){
                                    $sql = "DELETE FROM $exam_table WHERE id = $exam_id";
                                    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                                }else{
                                    echo '删除原文件失败';
                                }
                            }else{
                                $sql = "DELETE FROM $exam_table WHERE id = $exam_id";
                                $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                            }
		}
			break;
        case 'batchMoveTo' ://批量移动题库
            $number_of_selected_exams = count ( $_POST ['id'] );
            if ($number_of_selected_exams > 0) {
                $id_str = implode ( ",", $_POST ['id'] );
                $form = new FormValidator ( 'batch_change_dept', 'post' );
                $form->addElement ( "hidden", "action", 'batchMoveToSave' );
                $form->addElement ( "hidden", "id_str", $id_str );
                $renderer = $form->defaultRenderer ();
                $renderer->setElementTemplate ( '<span>{element}</span> ' );
                $form->addElement ( 'static', 'text1', null, get_lang ( "PlsSelectThePoolMoveTo" ) );

                $cata=get_cname();
                $cata.='</select>';
                $form->addElement ('html',$cata);
                $all_pools = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
                $form->addElement ( 'select', 'to_pool_id', get_lang ( "QuestionPool" ), $all_pools, array ('id' => "pool_id", 'style' => 'min-width:150px;height:30px;border:1px solid #999' ) );
                $form->addElement ( 'submit', 'submit', get_lang ( 'Save' ), 'class="inputSubmit"' );
                $form->addElement ( 'button', 'btn', get_lang ( 'Cancel' ), 'class="cancel" onclick="javascript:location.href=\'question_base.php?pool_id=' . $pool_id . '\';"' );
                echo '  <aside id="sidebar" class="column exercice open">
                                        <div id="flexButton" class="closeButton close">
                                        </div>
                                        </aside>';
                echo '<div class="actions" style=" width:50%;float:left;margin-top:50px;margin-left:100px">';
                $form->display ();
                echo '已选择的题目总数:' . $number_of_selected_exams;
                echo '</div>';
            } else {
                Display::display_msgbox ( get_lang ( 'PlsSelectedQuestions' ), $redirect_url, 'warning' );
            }
            exit ();
            break;


        case 'batchMoveToSave' :
			$id_str = getgpc ( 'id_str', 'P' );
			$to_pool_id = intval ( getgpc ( 'to_pool_id', 'P') );
			if ($id_str && $to_pool_id) {
				$sql_data = array ('pool_id' => $to_pool_id );
				$sql = Database::sql_update ( $TBL_QUESTIONS, $sql_data, " id IN (" . $id_str . ")" );
				api_sql_query ( $sql, __FILE__, __LINE__ );
				Display::display_msgbox ( get_lang ( 'SelectedQuestionInPoolChanged' ), $redirect_url );
			} else {
				Display::display_msgbox ( '请选择一个目标题库', $redirect_url, 'warning' );
			}
			break;
	}
}

function get_sqlwhere() {
	$sql = '';

        if(isset($_GET['classId']) && $_GET['classId']=='all'){
        	$_GET['classId']='';
    	}
       	elseif (is_not_blank ( $_GET ['classId'] )) {
                $sql .= " AND e.classId=" . Database::escape ( getgpc ( 'classId' ) );
	}
        
	if(isset($_GET['keyword']) && $_GET['keyword']=='输入搜索关键词'){
        	$_GET['keyword']='';
    	}
        else if (is_not_blank ( $_GET ['keyword'] )) {
            $keyword = Database::escape_str ( getgpc ( 'keyword', "G" ), TRUE );
            $sql .= " AND (e.exam_Name LIKE '%" . $keyword . "%' OR e.examBranch LIKE '%" . $keyword . "%')";
            }
return $sql;
}

function get_data($from, $number_of_exams, $column, $direction) {
        $c_table = Database::get_main_table ( 'tbl_class' );
	$exam_table = Database::get_main_table ( 'tbl_exam' );
	$sql = "SELECT  e.id		AS col0,
                 	e.exam_Name	AS col1,
                 	e.examBranch 	AS col2,
                 	c.className 	AS col3,
                 	e.examStime 	AS col4,
                        
                        e.id            AS col5
	FROM  $exam_table AS e,$c_table AS c where e.classId=c.id";
	$sql_where = get_sqlwhere ();
        
	if ($sql_where) $sql .= $sql_where;
	$sql .= " ORDER BY col$column $direction ";
	$sql .= " LIMIT $from,$number_of_exams";  
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();
	while ( $user = Database::fetch_row ( $res ) ) {
                $user[4]=date('Y-m-d H:i:s',$user[4]);//创建时间
                $user[1]=  htmlspecialchars_decode($user[1]);
		$users [] = $user;  
	}
	return $users;
}

function get_number_of_data() {
        $c_table = Database::get_main_table ( 'tbl_class' );
	$exam_table = Database::get_main_table ( 'tbl_exam' );
	$sql ="SELECT COUNT(*) AS total_number_of_exams FROM $exam_table AS e,$c_table AS c where e.classId=c.id";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
        $num=Database::getval ( $sql, __FILE__, __LINE__ );
	return $num;
}

//编辑 删除链接 
function active_filter($team_id) {
        $result = link_button ( 'edit.gif', '修改题目信息', 'exam_edit_1.php?id=' . $team_id, '75%', '55%', FALSE )."&nbsp;&nbsp;".confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'exam_list_1.php?action=delete_exam&id=' . $team_id );
	return $result;
}

//单个删除
if (isset ( $_GET ['action'])) {
	switch (getgpc('action','G')) {
            case 'delete_exam' :
                //$exam_id = getgpc('id','G');
                $exam_table = Database::get_main_table ( 'tbl_exam' );
                $sql = "SELECT uploadText FROM $exam_table WHERE id = $id";
                $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                $rows=Database::fetch_row ($res);
                $path=$rows[0];
               if($path!=''){
                  if(unlink($path)){
                  $sql = "DELETE FROM $exam_table WHERE id = $id";
                  $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                  }else{
                    echo '删除原文件失败';
                  }
               }else{
                   $sql = "DELETE FROM $exam_table WHERE id = $id";
                   $res = api_sql_query ( $sql, __FILE__, __LINE__ );
               }
               if($res){
                   tb_close('exam_list_1.php');
                   } else {
                   tb_close('exam_list_1.php');
		}
		break;
             }
}


//搜索
$form = new FormValidator ( 'question_pool_form', 'get' );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{label} {element}</span> ' );


$form->addElement ( 'text', 'keyword', get_lang ( '考题名称/分数' ), array ('class' => 'inputText', 'style' => 'width:150px','id'=>'searchkey','value'=>'输入搜索关键词' ) );
$form->addElement ( 'html', $cname);


$sql="select `id`, `pool_name` from `exam_question_pool`";
$result = api_sql_query ( $sql, __FILE__, __LINE__ );
$pools = array ();
while ($row = Database::fetch_array ( $result, 'ASSOC' )){
    $pools[$row['id']]=$row['pool_name'];
}


$form->addElement ( 'submit', 'submit', get_lang ( 'Search' ), 'class="inputSubmit"' );

$parameters = array ('question_type' => getgpc ( 'question_type', 'G' ), 'keyword' => getgpc ( 'keyword', 'G' ), 'pool_id' => $pool_id );
$table = new SortableTable ( 'question_base', 'get_number_of_data', 'get_data', 1, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$idx = 0;
$table->set_header ( $idx ++, '',  false, null, null);
$table->set_header ( $idx ++, get_lang ( '考题名称' ), false, null, array ('style' => 'width:30%' ));
$table->set_header ( $idx ++, get_lang ( '分数' ), false, null, array ('style' => 'width:15%' ));
$table->set_header ( $idx ++, get_lang ( '所属分类' ), false, null, array ('style' => 'width:20%' ));
$table->set_header ( $idx ++, get_lang ( '创建时间' ), false, null, array ('style' => 'width:20%' ));
//$table->set_header ( $idx ++, get_lang ( '下载报告' ), false, null, array ('style' => 'width:5%' ));
$table->set_header ( $idx ++, get_lang ( '操作' ), false, null, array ('style' => 'width:10%' ));

//$table->set_column_filter ( 5, 'downLoadfilter' );
$table->set_column_filter ( 5, 'active_filter' );

$actions = array ('delete' => get_lang ( 'BatchDelete' ),
    'batchMoveTo'=>'批量移动分类',
   );
$table->set_form_actions ( $actions );

?>
<aside id="sidebar" class="column ctfinex open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>
<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/ctf/index.php">CTF管理</a> &gt; 题库管理</h4>
    <div class="managerSearch">
        <?php
        echo "<span class='seachtxt right'>";
      
        echo "</span>";
	echo str_repeat ( "&nbsp;", 2 ) ./* Display::return_icon ( 'save.png', get_lang ( 'NewQuestion' ), array ('align' => 'absbottom' ) ) .*/ '<b>' . get_lang ( "" ) . '</b>    ';
        echo str_repeat ( "&nbsp;", 4 ) . link_button ( 'question_add.gif', 添加考题, 'exam_add_1.php?action=add&answerType=' . 1 . '&pool_id=' . $pool_id, '90%', '80%' );
    
        if($platform==3){
            if ($_configuration ['enable_question_freeanswer']) echo str_repeat ( "&nbsp;", 2 ) . link_button ( 'question_add.gif', "实战题", 'question_update.php?action=add&answerType=' . COMBAT_QUESTION . '&pool_id=' . $pool_id, '90%', '80%' );
        }
       ?>
    </div>
    <div class="managerSearch">
        <?php $form->display ();?>
    </div>
       <article class="module width_full hidden">
          <form name="form1" method="post" action="">
              <?php $table->display ();?>
          </form>
       </article>
        </div>
    </div>
</section>
</body>
</html>
