<?php
$language_file = array ('admin', 'registration' );
$cidReset = true;
include_once ('../../inc/global.inc.php');
api_protect_admin_script ();
if (! isRoot ()) api_not_allowed ();
$htmlHeadXtra [] = Display::display_thickbox ();
include_once ('../../inc/header.inc.php');
           
echo '<aside id="sidebar" class="column skill open">
       <div id="flexButton" class="closeButton close"></div>
      </aside>';
                
function get_sqlwhere() {
    $sql_where = "";
    if (isset ( $_GET ['keyword'] ) && ! empty ( $_GET ['keyword'] )) {
        $keyword = escape ( getgpc ( 'keyword', 'G' ), TRUE );
        if($keyword=='输入搜索关键词'){
            $keyword='';
        }
        $sql_where .= " AND and (skill_name LIKE '%" . $keyword . "%'  ) ";
    }
            
    $sql_where = trim ( $sql_where );
    if ($sql_where)
        return substr ( ltrim ( $sql_where ), 3 );
    else return "";
}
                
function get_number_of_occupations() {
	$user_table = Database::get_main_table ( VIEW_USER_DEPT );
	$sql = "SELECT COUNT(id) AS total_number_of_items FROM  skill_occupation  where  1" ;
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= $sql_where;
	return Database::getval ( $sql, __FILE__, __LINE__ );
}

function get_occupation_data($from, $number_of_items, $column, $direction) {
	$table = Database::get_main_table ( skill_occupation );
	$sql = "SELECT  id		AS col0,
                 	skill_name	AS col1,
                 	skill_description 	AS col2,
                 	position_description 	AS col3,
                 	postition_requirement   AS col4 ,
                    exam_desc   AS   col5,
                    id  AS  col6,
                     id  AS col7,
                     id  AS col8
	FROM  $table  where  1 ";
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
              
function active_filter($id) {
        $html = "";
        $html .= link_button('edit.gif', '编辑',  'occupation_edit.php?id=' . $id , "70%", "80%",FALSE); 
        $html .=  confirm_href ( 'delete.gif', '你确定执行此操作？', 'Delete', 'occupation_manage.php?action=del&id=' . $id );
        return $html;
}
            
function  vmdisk_filter($id){
        $html="";
        $html .= link_button ( 'learnpath_organize.gif', '选择虚拟模板', 'occupation_rel_vmdisk.php?occupation_id=' . $id, '80%', '80%', FALSE );
        return $html;
}
function  topo_filter($id){
        $html="";
        $html .= link_button ( 'conf.gif', '拓扑设计', 'design_topo.php?action=design&occupation_id=' . $id, '80%', '80%', FALSE );
        return $html;
}

if (isset ( $_GET ['action'] )) {
    $id=  intval(getgpc('id'));    
    switch ($_GET ['action']) {
            case 'del' :
                    if ($id) {
                       $sql="DELETE FROM `skill_occupation` WHERE  `id`=".$id;
                       api_sql_query($sql);
                       tb_close('occupation_manage.php');
                    }
                    break;
    }
}
if (isset ( $_POST ['action'] )) {
    switch ($_POST ['action']) {
            case 'deletes' :
                  $del_id= $_POST['id'];
                    foreach ($del_id as $index => $id ) {
                        $sql="DELETE FROM `skill_occupation` WHERE  `id`=".$id;
                        api_sql_query($sql);
//                        api_logging ( get_lang ( 'DelUsers' ) . $user_id, 'USER', 'DelUsers' );
                    } 
           break;
    }
}

$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{element}</span> ' );
$form->addElement ( 'text', 'keyword', get_lang ( 'keyword' ), array ('style' => "", 'class' => 'inputText', 'title' => '职业技能名称','value'=>'输入搜索关键词','id'=>'searchkey') );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );
                
echo '<section id="main" class="column">';
echo '<h4 class="page-mark" >当前位置：平台首页  &gt; 技能管理</h4>';
echo '<div class="managerSearch">';
$form->display ();  
echo '<span class="searchtxt right">';
echo link_button ( 'add_user_big.gif', '新增技能', "occupation_add.php", '70%', '80%' );
echo '</span>';
echo "</div>";

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ["keyword"] )) {
        $parameters ['keyword'] = getgpc ( 'keyword', 'G' );
}
$table = new SortableTable ( 'admin_occupations', 'get_number_of_occupations', 'get_occupation_data', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );
$idx = 0;
$table->set_header ( $idx ++, '',  false, null, null);
$table->set_header ( $idx ++, get_lang ( '职业技能名称' ), false, null, array ('style' => 'width:10%' ));
$table->set_header ( $idx ++, get_lang ( '技能描述' ), false, null, array ('style' => 'width:18%' ));
$table->set_header ( $idx ++, get_lang ( '职位描述' ), false, null, array ('style' => 'width:18%' ));
$table->set_header ( $idx ++, get_lang ( '职位需求' ), false, null, array ('style' => 'width:18%' ));
$table->set_header ( $idx ++, get_lang ( '职业技能考核描述' ), false, null, array ('style' => 'width:18%' ));
$table->set_header ( $idx ++, get_lang ( '选择模板' ), false, null, array ('style' => 'width:6%' ));
$table->set_header ( $idx ++, get_lang ( '设计拓扑' ), false, null, array ('style' => 'width:6%' )); 
$table->set_header ( $idx ++, get_lang ( '操作' ), false, null, array ('style' => 'width:6%' ));
$table->set_column_filter( 6, 'vmdisk_filter' );
$table->set_column_filter( 7, 'topo_filter' );
$table->set_column_filter ( 8, 'active_filter' ); 
$actions = array ('deletes' => get_lang ( 'BatchDelete' ));
$table->set_form_actions ( $actions );
                
?>
    <article class="module width_full">
        <table cellspacing="0" cellpadding="0" class="p-table">
	   <?php $table->display ();?> 
        </table>
    </article>
</section>
<?php

