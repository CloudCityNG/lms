<?php
/**
 ==============================================================================

 ==============================================================================
 */

$language_file = array ('admin', 'registration' );
$cidReset = true;
include_once ('../../inc/global.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'usermanager.lib.php');
require_once (api_get_path ( LIBRARY_PATH ) . 'dept.lib.inc.php');
require_once (api_get_path ( LIBRARY_PATH ) . "course.lib.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
$main_user_table = Database::get_main_table ( VIEW_USER_DEPT );
$table_dept = Database::get_main_table ( TABLE_MAIN_DEPT );
$table_user = Database::get_main_table ( TABLE_MAIN_USER );

$action = getgpc ( 'action' );
$dept_id = isset ( $_GET ['keyword_deptid'] ) ? getgpc ( 'keyword_deptid', 'G' ) : '0';

$sql = "SELECT user_id FROM " . $table_user . " WHERE is_admin=1 AND " . Database::create_in ( $_configuration ['default_administrator_name'], 'username' );
$root_user_id = Database::get_into_array ( $sql, __FILE__, __LINE__ );
$redirect_url = 'main/admin/user/user_list_iframe.php';

//部门数据
$objDept = new DeptManager ();

$objCrsMng = new CourseManager ();
$arrange_user_id = api_get_user_id();

if (isset ( $_REQUEST ['action'] )) {
	switch (getgpc("action")) {
		case 'batch_unsubscribe' :
			$subid = $_POST['id'];
//                        var_dump($_POST);
			if ($subid && is_array ( $subid )) {
				foreach ( $subid as $user ) {
                        
                                                                               $table_course_user = Database::get_main_table ( TABLE_MAIN_COURSE_USER );
                                                                               $course_user_sql = "select course_code from $table_course_user where user_id=".$user;
                                                                               $res = api_sql_query($course_user_sql);
                                                                                while ( $course_user = Database::fetch_array ( $res, 'NUM' ) ) {                                                                                      
                                                                                                if (CourseManager::is_allowed_to_unsubscribe ( $course_user[0], $user )) {
                                                                                                                var_dump($user);
                                                                                                                var_dump($course_user[0]);
                                                                                                                var_dump($arrange_user_id);
                                                                                                        CourseManager::unsubscribe_user ( $user, $course_user[0], $arrange_user_id);
                                                                                                }
                                                                                     }
				}
			}
			break;
	}
	$redirect_url = "user_plan.php";
	api_redirect ( $redirect_url );
}


function get_sqlwhere() {
	global $objDept, $root_user_id;

	$sql_where = '';
	if (isset ( $_GET ['keyword'] ) && ! empty ( $_GET ['keyword'] )) {
		$keyword = escape ( $_GET ['keyword'], TRUE );
		$sql_where .= " AND (firstname LIKE '%" . $keyword . "%'  OR username LIKE '%" . $keyword . "%'  OR official_code LIKE '%" . $keyword . "%') ";
	}
	
	if (isset ( $_GET ['keyword_deptid'] ) and getgpc ( 'keyword_deptid' ) != "0") {
		$dept_id = intval ( escape ( getgpc ( 'keyword_deptid', 'G' ) ) );
		$dept_sn = $objDept->get_sub_dept_sn ( $dept_id );
		if ($dept_sn) $sql_where .= " AND dept_sn LIKE '%" . $dept_sn . "%'";
	}
	$sql_where = trim ( $sql_where );
	if ($sql_where)
		return ltrim ( $sql_where );
	else return "";
}

function get_number_of_users() {
	$user_table = Database::get_main_table ( VIEW_USER_DEPT );
	$sql = "SELECT COUNT(user_id) AS total_number_of_items FROM " . $user_table;
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " WHERE 1 " . $sql_where;
	return Database::getval ( $sql, __FILE__, __LINE__ );
}

function get_user_data($from, $number_of_items, $column, $direction) {
	$user_table = Database::get_main_table ( VIEW_USER_DEPT );
	$sql = "SELECT  
                 	                 user_id	                  AS col0,
                        	username 	AS col1,
                                  	firstname  	AS col2,
                           	official_code 	AS col3,
		dept_name	AS col4,
		user_id		AS col5
		FROM  $user_table ";
	$sql_where = get_sqlwhere ();
	if ($sql_where) $sql .= " WHERE 1 " . $sql_where;
	$sql .= " ORDER BY col$column $direction ";
	$sql .= " LIMIT $from,$number_of_items";
	$res = api_sql_query ( $sql, __FILE__, __LINE__ );
	$users = array ();
	$objDept = new DeptManager ();
	while ( $user = Database::fetch_row ( $res ) ) {
		$users [] = $user;
	}
	return $users;
}

function modify_filter($user_id, $url_params) {
	global $_configuration;
	$result .= link_button ( 'synthese_view.gif', 'Info', '../user/user_information.php?user_id=' . $user_id, '90%', '90%', FALSE );
	$result .= '&nbsp;' . link_button ( 'enroll.gif', 'CourseListSubAndArrange', '../user/user_subscribe_course_list.php?user_id=' . $user_id, '90%', '94%', FALSE );
	return $result;
}

$htmlHeadXtra [] = import_assets ( "yui/tabview/assets/skins/sam/tabview.css", api_get_path ( WEB_JS_PATH ) );
$htmlHeadXtra [] = '<script type="text/javascript">$(document).ready( function() {$("body").addClass("yui-skin-sam");});</script>';
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( NULL, TRUE );

$html = '<div id="demo" class="yui-navset boxPublic">';
$html .= '<ul class="yui-nav">';
$html .= '<li><a href="' . URL_APPEND . 'main/admin/course/course_plan.php"><em>' . get_lang ( 'CourseAuthByCrs' ) . '</em></a></li>';
$html .= '<li  class="selected"><a href="' . URL_APPEND . 'main/admin/course/user_plan.php"><em> ' . get_lang ( 'CourseAuthByUser' ) . '</em></a></li>';
$html .= '<li><a href="' . URL_APPEND . 'main/admin/course/course_user_open_plan.php"><em> ' . get_lang ( 'CourseAuthByOpenUser' ) . '</em></a></li>';
$html .= '</ul>';
$html .= '</div>';
//$html .= '<div class="yui-content"><div id="tab1">';
//echo $html;

$form = new FormValidator ( 'search_simple', 'get', '', '', '_self', false );
$renderer = $form->defaultRenderer ();
$renderer->setElementTemplate ( '<span>{label}{element}</span> ' );
$keyword_tip = get_lang ( 'LoginName' ) . "/" . get_lang ( "FirstName" ) . "/" . get_lang ( "OfficialCode" );
$form->addElement ( 'text', 'keyword', $keyword_tip, array ('style' => "width:25%", 'class' => 'inputText', 'title' => $keyword_tip ) );
$depts = $objDept->get_sub_dept_ddl2 ( 0, 'array' );
$form->addElement ( 'select', 'keyword_deptid', get_lang ( 'UserInDept' ), $depts, array ('id' => "dept_id", 'style' => 'height:27px;' ) );
$form->addElement ( 'submit', 'submit', get_lang ( 'Query' ), 'class="inputSubmit"' );

//echo '<div class="actions">';

//$form->display ();
//echo '</div>';

if (isset ( $_GET ['keyword'] ) && is_not_blank ( $_GET ["keyword"] )) {
	$parameters ['keyword'] = getgpc ( 'keyword', 'G' );
	$parameters = array ('keyword' => getgpc('keyword'), 'keyword_status' => getgpc('keyword_status'), 'keyword_org_id' => getgpc("keyword_org_id") );
}

if (is_not_blank ( $_GET ["keyword_org_id"] )) $parameters ['keyword_org_id'] = trim ( getgpc("keyword_org_id") );
if ($dept_id) $parameters ['keyword_deptid'] = $dept_id;

$table = new SortableTable ( 'admin_users', 'get_number_of_users', 'get_user_data', 0, NUMBER_PAGE, 'DESC' );
$table->set_additional_parameters ( $parameters );

$idx = 0;
$table->set_header ( $idx ++, '', false );
$table->set_header ( $idx ++, get_lang ( 'LoginName' ) );
$table->set_header ( $idx ++, get_lang ( 'FirstName' ) );
$table->set_header ( $idx ++, get_lang ( 'OfficialCode' ) );
//$table->set_header(4, get_lang('InOrg'));
$table->set_header ( $idx ++, get_lang ( 'InDept' ) );
//$table->set_header($idx++, get_lang('CreationOrRegistrationDate'));
//$table->set_header($idx++, get_lang('Email'));
$table->set_header ( $idx ++, get_lang ( 'Actions' ), false, null, array ('style' => 'width:60px' ) );
//$table->set_column_filter(6, 'email_filter');
$table->set_column_filter ( 5, 'modify_filter' );
$actions = array ('batch_unsubscribe' => get_lang ( 'BatchDeleteAuthorization' ) );
$table->set_form_actions ( $actions );
//$table->display ();
//echo '</div></div></div>';
//Display::display_footer ( 1 );
?>
<aside id="sidebar" class="column course open">

    <div id="flexButton" class="closeButton close">

    </div>
</aside>

<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; <a href="<?=URL_APPEDND;?>/main/admin/course/course_list.php">课程管理</a> &gt;课程调度</h4>
	  <?php echo $html; ?>
      <div class="managerSearch">
		<?php $form->display ();?>
      </div>
  	<!--数据模块-->
    <article class="module width_full hidden">
    	<?php $table->display ();?>
    </article>
  
</section>
</body>
</html>