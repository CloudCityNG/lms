<?php
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
require_once (api_get_path ( LIBRARY_PATH ) . "course.lib.php");

$tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
$table_course_category = Database::get_main_table ( TABLE_MAIN_CATEGORY ); 
$tbl_courseware = Database::get_course_table ( TABLE_COURSEWARE );
$step_id=  getgpc('id');          
$objCrsMng = new CourseManager ();
function get_sqlwhere() {
	global $restrict_org_id, $objCrsMng;
	$sql_where = "";
	if (is_not_blank ( $_GET ['category_id'] )) {
		$sql_where .= "   AND     `course`.`category_code`=" . Database::escape (intval(getgpc ( 'category_id', 'G' )) );
	}
	$sql_where = trim ( $sql_where );
	if ($sql_where) return substr ( ltrim ( $sql_where ), 3 );
	else   return   "";
}
function get_number_of_courses() {
	$course_table = Database::get_main_table ( TABLE_MAIN_COURSE );
	$sql = "SELECT COUNT(`code`) AS total_number_of_items FROM $course_table    ";  
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .="WHERE  ". $sql_where;     
            
                    $number=Database::getval ( $sql, __FILE__, __LINE__ );  
            
	return  $number;
}    
function get_course_data($from, $number_of_items, $column, $direction) {
	$course_table = Database::get_main_table ( TABLE_MAIN_COURSE );   
                    $sql = "SELECT code ,title ,code    FROM $course_table   ";   
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .=  "WHERE  ". $sql_where;
	$sql .= " ORDER BY `course`.`title` asc"; 
	$sql .= " LIMIT $from,$number_of_items";    
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );    
                    $objCourse = new CourseManager (); 
                    while ( $course = Database::fetch_array ( $res, 'NUM' ) ) {
                         $objCourse->category_path = ''; 
                            $course [1] = '<span style="float:left"> '.Display::return_icon ( "course.gif" ) . '&nbsp;' . api_trunc_str2 ( $course [1],45 ).'</span>';
                             $courses [] = $course;
                     }
	return $courses;
}
function  get_occupation_course_data($from, $number_of_items, $column, $direction){ 
                    $sql = "SELECT  `course_id`,`sequentially` ,`id`    FROM  `skill_course_occupation`  where  `step_id`=".getgpc('id'); 
	$sql .= " ORDER BY  sequentially  asc"; 
	$sql .= " LIMIT $from,$number_of_items";
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$oocupa_courses = array (); 
	while ( $course = Database::fetch_array ( $res, 'NUM' ) ) {  
                             $oocupa_courses [] = $course;
	}
	return $oocupa_courses;
}
function  get_number_of_occupation_courses(){ 
	$sql = "SELECT COUNT(id) AS total_number_of_items FROM  `skill_course_occupation`   where  step_id=".getgpc('id'); 
	return Database::getval ( $sql, __FILE__, __LINE__ );
}
function active_filter($id){
       $id_step=  Database::getval("select `step_id`   from  `skill_course_occupation`  where  `id`=".$id);
        $html = "";
        $html .= link_button('acces_tool.gif', '技能课程顺序设置',  'occupat_sequent_set.php?action=set_sequentially&rel_id=' . $id , "50%", "50%",FALSE); 
        $html .=  "&nbsp;&nbsp;&nbsp;&nbsp;".confirm_href ( 'delete.gif', '你确定执行此操作？', 'Delete', 'occupation_rel_course.php?action=del_rel&rel_id=' . $id."&id_step=".$id_step ."&category_id=".$_GET ['category_id']);
        return $html;
}
function course_title($c_code){
        $html='';
        $sql="select  `title`  from  `course`  where  `code`='".$c_code."'";
        $title=Database::getval($sql);
        $html=  "<a  href='../course/course_list.php?keyword={$title}'  target='_blank'>".$title."</a>";
        return   $html;
}
//批量选择技能阶段课程
if (isset ( $_POST ['action'] )) {
    $act=explode("---", $_POST ['action']);
    $step_id= $act[1];
    $cate_id=$act[2]; 
    $skill_id=  Database::getval("select  `occupat_id`  from  `skill_rel_step`  where  `step_id`=".$step_id);
    switch ($act[0]) {   
            case 'add_occupation_courses' :   
                    $course_codes = $_POST['courses'];    
                    if (count( $course_codes ) > 0) {    
                        $sql="select  max(`sequentially`)   from  `skill_course_occupation`  where  `skill_id`=".$skill_id;
                        $sequent=  Database::getval($sql);
                        $sequentially=  intval($sequent)+1;
                            foreach ( $course_codes as $index => $course_code ) {
                                  $sql="select  count(`id`)  from  `skill_course_occupation`  where  `course_id`='{$course_code}'  and  `skill_id`={$skill_id} ";
                                  $exist=  Database::getval($sql);
                                  if($exist<1){ 
                                        $sql="INSERT INTO `skill_course_occupation`(`course_id`, `skill_id`, `sequentially`,`step_id`) VALUES ({$course_code},{$skill_id},{$sequentially},{$step_id})";
                                        api_sql_query($sql);
                                        $sequentially++;
                                  }
                            }
                    }
                    header( 'location:occupation_rel_course.php?id='.  $step_id.'&category_id='.  intval($cate_id)); 
          break;
    }
}
//删除技能课程
if($_GET['action']=='del_rel'){
    $id_step=  getgpc('id_step');
    $rel_id=  getgpc('rel_id');
    $sql="delete  from  `skill_course_occupation`  where  `id`=".intval($rel_id);
    api_sql_query($sql);
    header( 'location:occupation_rel_course.php?id='. intval($id_step).'&category_id='.  intval($_GET ['category_id']) ); 
}

$tool_name = get_lang ( 'CourseList' );
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( $tool_name,FALSE );

$form = new FormValidator ( 'search_simple', 'get', '', '', null, false ); 
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '{element} ' ); 
 
$sql = "SELECT category_code,count(*) FROM $tbl_course GROUP BY category_code";
$category_cnt = Database::get_into_array2 ( $sql );
$category_tree = $objCrsMng->get_all_categories_tree ( TRUE );
$cate_options [""] = "---所有分类---";
foreach ( $category_tree as $item ) {
	$cate_name = $item ['name']. (($category_cnt [intval($item ['id'])]) ? "&nbsp;(" . $category_cnt [intval($item ['id'])] . ")" : "") ;  
	$cate_options [intval($item ['id'])] = str_repeat ( "&nbsp;&nbsp;&nbsp;&nbsp;", intval ( $item ['level'] ) + 1 ) . trim ( $cate_name );
}
$form->addElement ( 'select', 'category_id', get_lang ( 'CourseCategory' ), $cate_options, array ('style' => 'min-width:150px;height:27px;border: 1px solid #999;' ) );
$form->addElement("hidden","id",$step_id);
$form->addElement ( 'submit', 'submit', get_lang ( 'SearchFilter' ), 'class="inputSubmit"' );
?>
<div class="managerSearch">
    <div class="seart">
        <?php $form->display (); ?>
    </div>
</div>
 
<article class="module width_full hidden">
<?php
            
if (isset ( $_GET ['category_id'] ) && is_not_blank ( $_GET ['category_id'] )) $parameters ['category_id'] = intval(getgpc ( 'category_id' ));
if($step_id)$parameters['id']=  intval ($step_id);

$table = new SortableTable ( 'courses', 'get_number_of_courses', 'get_course_data', 2, NUMBER_PAGE );
$table->set_additional_parameters ( $parameters );

$table->set_header ( 0, '', false);
$table->set_header ( 1, get_lang ( 'CourseTitle' ), false, null ,array('width'=>'60%') );
$table->set_header ( 2, get_lang ( 'CourseCode' ), false, null ,array('width'=>'30%') );
 
$table->set_form_actions ( array ('add_occupation_courses---'.$step_id.'---'.$_GET ['category_id']=> get_lang ( '选为技能阶段课程' )), 'courses' );
$table->display ();

echo "<div style='clear:both;'></div>";   
//该技能阶段所选课程 
echo "<div>该技能阶段所选课程如下：</div>";
$table = new SortableTable ( 'occupation_courses', 'get_number_of_occupation_courses', 'get_occupation_course_data', 2, NUMBER_PAGE );
if($step_id)$params['id']=  intval ($step_id);
$table->set_additional_parameters ( $params );
            
$table->set_header ( 0, get_lang ( 'CourseTitle' ), false, null ,array('width'=>'60%') );
$table->set_header ( 1, get_lang ( '技能课程顺序' ), false, null ,array('width'=>'20%') );
$table->set_header ( 2, get_lang ( '操作' ), false, null ,array('width'=>'20%') );
$table->set_column_filter ( 0, 'course_title' ); 
$table->set_column_filter ( 2, 'active_filter' ); 
$table->display ();
Display::display_footer ();            
?>
 </article>
</section>
</body>
        </html>
