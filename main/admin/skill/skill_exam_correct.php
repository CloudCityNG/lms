<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;
include_once ('../../inc/global.inc.php');
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
$htmlHeadXtra [] = Display::display_thickbox ();
include_once ('../../inc/header.inc.php');
              
function get_sqlwhere() {
    $sql_where = "";
    if (isset ( $_GET ['keyword'] ) && ! empty ( $_GET ['keyword'] )) {
        $keyword = escape ( getgpc ( 'keyword', 'G' ), TRUE );
        if($keyword=='输入搜索关键词'){
            $keyword='';
        }
        if(! empty ($keyword)){
        $keyword_u=  Database::getval("select  `user_id`   from  `user`  where `username`='{$keyword}' ");
        $keyword_o=  Database::getval("select  `id`   from  `skill_occupation`  where  `skill_name`   like   '%".$keyword."%' ");
        if(! empty ($keyword_u)){$sql_where .= " AND and (`uid`=" . $keyword_u . " ) ";}
        if(! empty ($keyword_o)){$sql_where.="  AND  and  ( `occupation_id`=".$keyword_o." )";}
        }
    }
            
    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}          
function get_number_of_occupations() {
	$user_table = Database::get_main_table ( VIEW_USER_DEPT );
	$sql = "SELECT COUNT(`id`) AS total_number_of_items FROM  skill_examine  where  `user_answer`='skill_exam'  " ;
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
	return Database::getval ( $sql, __FILE__, __LINE__ );
}
function get_occupation_data($from, $number_of_items, $column, $direction) {
	$table = Database::get_main_table ( skill_examine );
	$sql = "SELECT   `id`	 AS col0,
                 	`uid`   AS col1,
                 	`occupation_id`  AS col2,
                 	`user_file`   AS col3,
                     `id`  AS col4
	FROM  $table  where  `user_answer`='skill_exam' ";
	$sql_where = get_sqlwhere ();     
	if ($sql_where) $sql .=   $sql_where;       
	$sql .= " ORDER BY col$column $direction ";
	$sql .= " LIMIT $from,$number_of_items";
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$occupations = array (); 
	while ( $occupation= Database::fetch_row ( $res ) ) {
		$occupations [] = $occupation;
	}
	return $occupations;
}     
function user_filter($id){
    $sql="select  username  from  user  where  user_id=".$id;
    $uname=  Database::getval($sql);
    return  $uname;
}
function occupat_filter($id){
    $sql="select  skill_name  from  skill_occupation  where  id=".$id;
    $skillname=  Database::getval($sql);
    return $skillname;
}
function active_filter($id) {
        $html = "";
        $html .= link_button('plugin.gif', '技能报告批改',  'skill_exam_correcting.php?id=' . $id , "70%", "80%",FALSE); 
        $html .=  confirm_href ( 'delete.gif', '你确定执行此操作？', 'Delete', 'skill_exam_correct.php?action=del&id=' . $id );
        return $html;
}
if (isset ( $_GET ['action'] )) {
    $id=  intval(getgpc('id'));    
    switch ($_GET ['action']) {
            case 'del' :
                    if ($id) {
                       $sql="DELETE FROM `skill_examine` WHERE  `id`=".$id;
                       api_sql_query($sql);
                       tb_close('skill_exam_correct.php');
                    }
                    break;
    }
}
if (isset ( $_POST ['action'] )) {
    switch ($_POST ['action']) {
            case 'deletes' :
                  $del_id= $_POST['id'];
                    foreach ($del_id as $index => $id ) {
                        $sql="DELETE FROM `skill_examine` WHERE  `id`=".$id;
                        api_sql_query($sql); 
                    } 
           break;
    }
}
$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "", 'class' => 'inputText', 'title' => '职业技能名称','value'=>'输入搜索关键词','id'=>'searchkey') );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );
    
echo '<aside id="sidebar" class="column skill open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';
echo '<section id="main" class="column">';
echo '<h4 class="page-mark" >当前位置：平台首页  &gt; 技能综合测试批改</h4>';
echo '<div class="managerSearch">';
$form->display ();  
echo "</div>";

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ["keyword"] )) {
        $parameters ['keyword'] = getgpc ( 'keyword', 'G' );
}
$table = new SortableTable ( 'admin_occupations', 'get_number_of_occupations', 'get_occupation_data', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$idx = 0;
$table->set_header ( $idx ++, '',  false, null, null);
$table->set_header ( $idx ++, get_lang ( '用户名称' ), false, null, array ('style' => 'width:25%' ));
$table->set_header ( $idx ++, get_lang ( '职业技能名称' ), false, null, array ('style' => 'width:35%' ));
$table->set_header ( $idx ++, get_lang ( '技能报告' ), false, null, array ('style' => 'width:25%' )); 
$table->set_header ( $idx ++, get_lang ( '操作' ), false, null, array ('style' => 'width:10%' ));
$table->set_column_filter ( 1, 'user_filter' ); 
$table->set_column_filter ( 2, 'occupat_filter' ); 
$table->set_column_filter ( 4, 'active_filter' ); 
$actions = array ('deletes' => get_lang ( 'BatchDelete' ));
$table->set_form_actions ( $actions );
                
?>
    <article class="module width_full">
        <table cellspacing="0" cellpadding="0" class="p-table">
	   <?php $table->display ();?> 
        </table>
    </article>
</section>
