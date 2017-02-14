<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;

include_once ('../../inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');

api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
$match_table = Database::get_main_table ( 'tbl_match' );
$htmlHeadXtra [] = Display::display_thickbox ();

$action = getgpc ( 'action', 'G' );

include_once ('../../inc/header.inc.php');

echo '<aside id="sidebar" class="column ctfinex open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';

//搜索功能
function get_sqlwhere() {
    $sql_where = "";
      if (isset ( $_POST['state'] ) && ! empty ( $_POST ['state'] )){
        $state=intval(getgpc('state','P'));

        if($state){
            $sql_where.=' and e.matchId='.$state;
        }
    }
    if (isset ( $_POST ['keyword'] ) && ! empty ( $_POST ['keyword'] )) {
        $keyword =  getgpc ( 'keyword', 'P' );
        if($keyword!=='输入搜索关键词'){
            $sql_where .= " AND(t.teamName LIKE '%" . $keyword . "%'  OR a.exam_Name LIKE '%" . $keyword . "%' OR u.username LIKE '%".$keyword."%')";}
        }
    
        if ($sql_where){
            return $sql_where;
        }else{ 
            return "";
        }

}
//总条数             
function get_number_of_match() {
                $match_table = Database::get_main_table ( 'tbl_match' );
                $team_table = Database::get_main_table ( 'tbl_team' );
                $user_table = Database::get_main_table ( 'user' );
                $event_table =Database::get_main_table ( 'tbl_event' );
                $sql = "SELECT COUNT(*) AS total_number_of_items FROM $team_table AS t,$user_table AS u,$event_table AS e,$match_table AS m,tbl_exam as a where m.user_id=u.user_id AND m.gid=t.id AND m.event_id=e.id and e.examId=a.id";
                $sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
        $num=Database::getval ( $sql, __FILE__, __LINE__ );
	return $num;
}

//获取要显示的数据
function get_data($from, $number_of_items, $column, $direction) {
                $match_table = Database::get_main_table ( 'tbl_match' );
                $team_table = Database::get_main_table ( 'tbl_team' );
                $user_table = Database::get_main_table ( 'user' );
                $event_table =Database::get_main_table ( 'tbl_event' );
	$sql = "SELECT  m.id AS col0,
                 	u.username AS col1,
                 	t.teamName AS col2,
                 	m.fraction AS col3,
                 	m.answer AS col4,
                        a.exam_Name     AS col5,
                        e.matchId       AS col6,
                        m.stime         AS col7,
                        m.etime         AS col8,
                        m.id AS col9
	FROM $team_table AS t,$user_table AS u,$event_table AS e,$match_table AS m,tbl_exam as a where m.user_id=u.user_id AND m.gid=t.id AND m.event_id=e.id AND e.examId=a.id";

	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
	$sql .= " ORDER BY col$column $direction ";
	$sql .= " LIMIT $from,$number_of_items"; 

	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();
      
	while ( $user = Database::fetch_row ( $res ) ) {
                $sql="select matchName from tbl_contest where id=$user[6]";
                $re = api_sql_query ( $sql, __FILE__, __LINE__ );
                $matchName = Database::fetch_row ( $re );
                $user[6]=$matchName[0];
                $user[7]=date('Y-m-d H:i:s',$user[7]);//进入时间
                $user[8]=date('Y-m-d H:i:s',$user[8]);//结束时间
		$users [] = $user;  
	}
	return $users;
}


function state_filter($state){
    if($state=='0'){
        return '进行中';
    }elseif($state=='1'){
        return '结束';
    }
}


//编辑 删除链接 
function active_filter($match_id) {
        $match_table = Database::get_main_table ( 'tbl_match' );
        $sql = "SELECT report AS col8 FROM $match_table where id=$match_id";
        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
        $report = Database::fetch_row ( $res );
        if($report[0]){
             $result= link_button ( 'edit.gif', '批改', 'match_edit.php?id=' . $match_id, '70%', '75%', FALSE );
        }else{
             $result='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $result.="&nbsp;&nbsp;".confirm_href ( 'delete.gif', 'ConfirmYourChoice', 'Delete', 'match_list.php?action=delete_match&id=' . $match_id );
	return $result;
}

//单个删除
if (isset ( $_GET ['action'])) {
	switch (getgpc('action','G')) {
            case 'delete_match' :
                $match_id = getgpc('id','G');
                $match_table = Database::get_main_table ( 'tbl_match' );
                $sql = "DELETE FROM $match_table WHERE id = $match_id";
                $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                        if($res){
                               tb_close('match_list.php');
                               Display::display_msgbox ( get_lang ( '删除成功' ), $redirect_url );
			} else {
                               tb_close('match_list.php');
                               Display::display_msgbox ( get_lang ( '删除失败' ), $redirect_url, 'warning' );
			}
			break;
        }
}

//批量删除
if (isset ( $_POST ['action'] )) {
	switch ($_POST ['action']) {
		case 'delete' :
			$number_of_selected_users = count ( $_POST ['id'] );
			$number_of_deleted_users = 0;
                                                     $del_id= $_POST['id'];
			foreach ($del_id as $index => $match_id ) {
                                                        $match_table = Database::get_main_table ( 'tbl_match' );
                                                        $sql = "DELETE FROM $match_table WHERE id = $match_id";
                                                        $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                                                        $number_of_deleted_users ++;
			}
			}
                        
                if ($number_of_selected_users == $number_of_deleted_users) {
                                                               tb_close('match_list.php');
                        Display::display_msgbox ( get_lang ( '删除成功' ), $redirect_url );
                } else {
                                                               tb_close('match_list.php');
                        Display::display_msgbox ( get_lang ( '删除失败' ), $redirect_url, 'warning' );
                }

}



//搜索下拉菜单
$form = new FormValidator ( 'search_simple', 'post', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text','keyword', get_lang ( 'keyword' ), array ('style' => "", 'class' => 'inputText', 'title' => '战队名称/用户名/题目名称','value'=>'输入搜索关键词','id'=>'searchkey') );

$sql1 = "SELECT `id`,`matchName` FROM `vslab`.`tbl_contest`";
$result1 = api_sql_query ( $sql1, __FILE__, __LINE__ );
$arr= array ();
$arrs[0]='--所有赛事--';
while ( $arr = Database::fetch_row ( $result1) ) {
    $arrs [$arr[0]] = $arr[1];
}


$form->addElement ( 'select', 'state', get_lang ( 'UserInDept' ), $arrs, array ('style' => 'min-width:150px;height:30px;border: 1px solid #999;' ) );

$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );
$form->setDefaults ( $values );

//面包屑导航
echo '<section id="main" class="column">';
echo '<h4 class="page-mark" >当前位置：平台首页  &gt; 成绩管理</h4>';
echo '<div class="managerSearch">';
$form->display (); //searc form
echo '<span class="searchtxt right">';
echo '</span>';
echo "</div>";

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ["keyword"] )) {
	$parameters ['keyword'] = getgpc ( 'keyword', 'G' );
	$parameters = array ('keyword' => $_GET ['keyword'], 'keyword_status' => $_GET ['keyword_status'], 'keyword_org_id' => intval($_GET ["keyword_org_id"]) );
}

if (is_not_blank ( $_GET ["keyword_org_id"] )) $parameters ['keyword_org_id'] = intval( getgpc("keyword_org_id",'G') );
if ($_GET ['dept_name']) $parameters ['dept_name'] = $_GET ['dept_name'];

$table = new SortableTable ( 'admin_users', 'get_number_of_match', 'get_data', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$idx = 0;
$table->set_header ( $idx ++, '',  false, null, null);
$table->set_header ( $idx ++, get_lang ( '用户名' ), false, null, array ('style' => 'width:12%' ));
$table->set_header ( $idx ++, get_lang ( '战队名称' ), false, null, array ('style' => 'width:12%' ));
$table->set_header ( $idx ++, get_lang ( '比赛得分' ), false, null, array ('style' => 'width:8%' ));
$table->set_header ( $idx ++, get_lang ( '用户答案' ), false, null, array ('style' => 'width:8%' ));
$table->set_header ( $idx ++, get_lang ( '比赛题目' ), false, null, array ('style' => 'width:25%' ));
$table->set_header ( $idx ++, get_lang ( '赛事名称' ), false, null, array ('style' => 'width:12%' ));
$table->set_header ( $idx ++, get_lang ( '进入时间' ), false, null, array ('style' => 'width:12%' ));
$table->set_header ( $idx ++, get_lang ( '提交时间' ), false, null, array ('style' => 'width:12%' ));
$table->set_header ( $idx ++, get_lang ( '操作' ), false, null, array ('style' => 'width:8%' ));

//$table->set_column_filter ( 4, 'state_filter' );
$table->set_column_filter ( 9, 'active_filter' );
$actions = array ('delete' => get_lang ( 'BatchDelete' ),
   );
$table->set_form_actions ( $actions );
?>

    <article class="module width_full">
        <table cellspacing="0" cellpadding="0" class="p-table">
	   <?php $table->display ();?>
        </table>
    </article>
</section>
