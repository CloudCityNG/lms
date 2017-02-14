<?php
$cidReset = true;
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
include_once ("inc/app.inc.php");

$user_id = api_get_user_id ();
if(!api_get_user_id ())
{
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
$category_id = intval(getgpc('category_id'));
$id = intval($_GET['id']);
if($_SESSION['_user']['status']==10)
{
   $teacher_arr = api_sql_query_array( 'select arrange_user_id from course_rel_user group by arrange_user_id' );
}
elseif($_SESSION['_user']['status']==5)
{
   $teacher_arr = api_sql_query_array( 'select arrange_user_id from course_rel_user where user_id='.$user_id.' group by arrange_user_id' );
}elseif($_SESSION['_user']['status']==1)
{
   $teacher_arr = api_sql_query_array( 'select arrange_user_id from course_rel_user where arrange_user_id='.$user_id.' group by arrange_user_id' );
}
else{
   $teacher_arr = api_sql_query_array( 'select distinct course.title,course.code,course.credit_hours  from course left join course_rel_user on course_rel_user.course_code = course.code where course_rel_user.arrange_user_id='.$user_id.'' );
}
$grade = intval(getgpc('grade','G'));
$pid =  getgpc('pid','G');
$select =  intval($_GET['select']);
$sel = htmlspecialchars($_POST['auto-id-rTOGAi3MiQOM7HrB']);
if(!empty($_GET['keyword']) && $category_id === 0)
{
    unset($category_id);
    unset($_GET['category_id']);
}
if(api_get_setting ( 'enable_modules', 'course_center' ) == 'false')
{
    echo '<script language="javascript"> document.location = "./learning_center.php";</script>';
    exit ();
}
$my_courses_all = CourseManager::get_user_subscribe_courses_code ( $user_id );

if($category_id!=='')
{
    $parent_cateid = mysql_fetch_row(mysql_query('select parent_id from course_category where id='.$category_id));
    $sql_cc = "SELECT `course`.`code` FROM `course` INNER JOIN `view_course_user` ON `course`.`code` = `view_course_user`.`course_code` WHERE `view_course_user`.`user_id` =".$user_id." AND `course`.`category_code` =".$category_id;
    $course_codess = api_sql_query_array_assoc($sql_cc,__FILE__,__LINE__);
    $course_count = count($course_codess);
    $end_code = $course_count-1;
    if($course_count)
    {
        $code_var='';
        foreach ($course_codess as $key => $value)
        {
            if($course_count<2){
                $code_var.="(".$value['code']." ) ";
            }else{
                if($key<1){
                    $code_var.="(".$value['code']." , ";
                }elseif($key == $end_code) {
                    $code_var.=$value['code'].")";
                }else{
                    $code_var.=$value['code']." , ";
                }
            }
        }

    }
}

include_once './inc/page_header.php';

$sql = 'select * from course_category where id='.$category_id;
$category_res =  api_sql_query_array_assoc($sql);
$category_data = $category_res[0];

$table_course = Database::get_main_table ( TABLE_MAIN_COURSE );
$tbl_course_category = Database::get_main_table ( TABLE_MAIN_CATEGORY );
$tbl_course_openscore = Database::get_main_table ( TABLE_MAIN_COURSE_OPENSCOPE );

//Recently Study
$sql = "SELECT `code`,`title` FROM  `course` INNER JOIN `course_rel_user` ON `course_rel_user`.`course_code`=`course`.`code` where `user_id`=".$user_id." limit  0,5";
$Recently_Study = api_sql_query_array_assoc($sql,__FILE__,__LINE__);

$Recently_Study_count = intval(count($Recently_Study));

$sql = "SELECT * FROM `vslab`.`course` WHERE 1 ";
if(isset($_GET['category_id']) && $category_id !=='' && is_numeric( $_GET['category_id'] ))
{
    $sql.=" AND category_code=".$category_id;
}
$category_name = Database::get_scalar_value ( $sql );

$CourseDescription = "SELECT * FROM $tbl_course_category WHERE id='" . escape ( $category_id ) . "' limit 1";//"select * from course_catalog where  "

$ress = api_sql_query($CourseDescription, __FILE__, __LINE__ );

$vms[0]= Database::fetch_row ( $ress);

if($_SESSION['_user']['status']==10){
    $sqls = 'SELECT distinct arrange_user_id FROM  course_rel_user';
    $total_user = api_sql_query_array( 'select arrange_user_id from course_rel_user group by arrange_user_id' );
    
}elseif ($_SESSION['_user']['status']==5) {
    $sqls = 'SELECT distinct arrange_user_id FROM  course_rel_user where user_id='.$user_id.' ';
    $total_user = api_sql_query_array( 'select arrange_user_id from course_rel_user where user_id='.$user_id.' group by arrange_user_id' ); 
}elseif ($_SESSION['_user']['status']==1) {
    $sqls = 'SELECT distinct arrange_user_id FROM  course_rel_user where arrange_user_id='.$user_id.' ';
    $total_user = api_sql_query_array( 'select arrange_user_id from course_rel_user where arrange_user_id='.$user_id.' group by arrange_user_id' ); 
}
 else {
    $sqls = 'select * from course,course_rel_user where course_rel_user.course_code = course.code and course_rel_user.arrange_user_id='.$user_id.' ';
   $total_user = api_sql_query_array( 'select distinct course.title,course.code,course.credit_hours from course,course_rel_user where course_rel_user.course_code = course.code and course_rel_user.arrange_user_id='.$user_id.'' );   
}
$total_rows = count($total_user);
$url = 'teacher_course.php';

$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );
$offset = (int)getgpc ( "offset", "G" );
if (empty ( $offset )) $offset = 0;
$sqls .= " LIMIT " . $offset . "," . NUMBER_PAGE;
$course_list = api_sql_query_array_assoc ( $sqls, __FILE__, __LINE__ );

if ($param {0} == "&") $param = substr ( $param, 1 );
$url = WEB_QH_PATH . "learning_center.php?" . $param;
display_tab ( TAB_COURSE_CENTER );
Display::display_thickbox(false,true);


?>
<div class="clear"></div>
<div class="m-moclist">
    <div class="g-flow" id="j-find-main">
        <div class="g-container f-cb">
            <!--  右侧 -->
            <div class="g-mn1" >
                <div class="g-mn1c m-cnt" style="margin-left: 0px;" style="display:block;">

                    <div class="j-list lists" id="j-list">

                        <div class="u-content">
                            <h3 class="sub-simple u-course-title">
                                <span class="u-title-next">教师实验</span>
                            </h3>
                            <div class="u-content-bottom">
<?php 
                                if (is_array ( $course_list ) && count ( $course_list ) > 0) {
                                    $i=1;
                                        
                                    foreach ( $teacher_arr as $teacher_arr_k => $teacher_arr_v )
                                    {
                                        $user_row = mysql_fetch_row( mysql_query( 'select username from user where user_id='.$teacher_arr_v['arrange_user_id'] ) );
                                        $course_code_row = mysql_fetch_row( mysql_query( 'select course_code from course_rel_user where arrange_user_id='.$teacher_arr_v['arrange_user_id'] ) );
                                        $course_count = mysql_fetch_row( mysql_query('SELECT  COUNT(t1.user_id) FROM course_rel_user AS t1 WHERE t1.course_code="'.$course_code_row [0].'" and arrange_user_id='.$teacher_arr_v['arrange_user_id']) );
                                       $user_count = mysql_fetch_row( mysql_query('SELECT  COUNT(t1.user_id) FROM course_rel_user AS t1 WHERE t1.course_code="'.$teacher_arr_v ['code'].'" and arrange_user_id='.$user_id));
                                        $course_hours = mysql_fetch_row( mysql_query('SELECT  COUNT(course.credit_hours),course_rel_user.arrange_user_id FROM course left join course_rel_user on course.code=course_rel_user.course_code where arrange_user_id='.$teacher_arr_v['arrange_user_id']));
                                        
                                       ?>                                      
                                 
                                       
                                <?php 
                                if($_SESSION['_user']['status']==1){
                                ?>
                                  <ul class="u-course-time">
                                            <li class="title-time p-514 "><?=$teacher_arr_k+1?></li>
                                            <a href="arranged_course_list.php?teacher_id=<?=$user_id?>"><li class="title-name">所有教师的实验</li></a>
                                            <li class="lab-time f-13">
                                                <?=$teacher_arr_v ['credit_hours']."学时"?>
                                            </li>
                                            <li class="lab-time f-13">
                                                <?=$user_count[0]?>人
                                            </li>
                                            <li class="lab-time f-13">
                                                <?=$value ['tutor_name']?>
                                            </li>

                                        </ul>
                                <?php 
                                }else{
                                ?>
                                        <ul class="u-course-time">
                                            <li class="title-time p-514 "><?=$teacher_arr_k+1?></li>
                                            <a href="arranged_course_list.php?teacher_id=<?=$teacher_arr_v['arrange_user_id']?>"><li class="title-name"><?=$user_row[0]?>-老师的实验</li></a>
                                            <li class="lab-time f-13">
                                                <?=$course_hours[0]."学时"?>
                                            </li>
                                            <li class="lab-time f-13">
                                                <?=$course_count[0]?>人
                                            </li>
                                            <li class="lab-time f-13">
                                                <?=$value ['tutor_name']?>
                                            </li>

                                        </ul>
                                <?php } ?>
                                        <?php
                                        $i++;
                                    }
                                    ?><div class="page">
                                    <ul class="page-list">
                                        <li class="page-num">总计<?=$total_rows?>个教师实验</li>
                                        <?php
                                        echo $pagination->create_links ();
                                        ?>
                                    </ul>
                                    </div>
                                <?php
                                } else {
                                    echo ' <div class="error">没有相关课程</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- 底部 -->
<?php
include_once './inc/page_footer.php';
?>
</body>
</html>