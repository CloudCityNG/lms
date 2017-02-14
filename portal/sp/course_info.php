<?php
$cidReset = true;
include_once ("inc/app.inc.php");
require_once (api_get_path ( LIBRARY_PATH ) . 'course_class_manager.lib.php');
if(!$_SESSION['_user']['status']){
    echo 'err';exit;
}

$course_code = getgpc ( "code" );
$token_key_true = $_SESSION['_user']['code'][$course_code];
if($_GET[$token_key_true] !== 'token'){
    echo 'err';exit;
}

$upurl=$_SERVER['HTTP_REFERER'];
$u1= end(explode("/", $upurl));
$u2=  explode("?", $u1);
if((!$u2[0]=="course_catalog.php") || (!$u2[0]=="ranking.php")){
    echo 'err';exit;
}

$table_course = Database::get_main_table ( TABLE_MAIN_COURSE );
$tbl_course_category = Database::get_main_table ( TABLE_MAIN_CATEGORY );
$tbl_course_desc = Database::get_course_table ( TABLE_COURSE_DESCRIPTION );

$objCourse = new CourseManager ();
$course_info = $objCourse->get_course_information ( $course_code ); //var_dump($course_info);
$is_subscribe = $objCourse->is_user_subscribe ( $course_code, $user_id ); //echo $is_subscribe;
$subscribe_requisition_rs = $objCourse->is_user_subscribe_requisition ( $course_code, $user_id );
$isFreeCourse = ($course_info ['is_free'] == '1' or floatval ( $course_info ['fee'] ) == 0) ? 'true' : 'false';

$sql = "SELECT description FROM " . $table_course . " WHERE code=" . Database::escape ( getgpc ( "code" ) );
$description = Database::get_scalar_value ( $sql );

echo Display::display_thickbox ( false );
$category_id= (int)$_GET['cate'];
$cate=(int)$_GET['category_id'];
$id=(int)$_GET['id'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" href="css/FunctionStyle.css" type="text/css" media="all">
 
</head>
<body>
<table class="tablebox">
	<thead>
		<tr>
			<td id="tishi" colspan="4">
				<?php
					if ($is_subscribe) :
				?>
				你已选修该课程
				<?php endif;?>
			</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>课程名称：</td>
			<td><?=$course_info ["title"]?></td>
			<td>课程编号：</td>
			<td><?=$course_info ["code"]?></td>
		</tr>
		<tr>
			<td>课程类别：</td>
			<td>
				<?php
					$objCourse->category_path = '';
					echo $objCourse->get_category_path ( $course_info ['category_code'], TRUE );
				?>
			</td>
			<td>课时：</td>
			<td><?=$course_info ["credit_hours"]?></td>
		</tr>
		<tr>
			<td>课程讲师：</td>
			<td><?=$course_info ["tutor_name"]?></td>
			<td>学习人数：</td>
			<td id="countnumber"><?=CourseManager::get_course_user_count($course_code)?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">
				<?php
					if (! $is_subscribe && $course_info ['subscribe'])
					{
						if ($subscribe_requisition_rs < 0 && !empty( $_SESSION['_user']['user_id'] )) {//已登录
						if(api_get_setting('course_subscribe_with_class')=='true')
						{
					?>
							<a class="thickbox de1" href="course_info_chose_class.php?course_code=<?=$course_code?>&KeepThis=true&TB_iframe=true&modal=true&width=400&height=200">进入课程</a>
			    <?php
			            }else{
			    ?>
			               <span id="chooid" onclick="javascript:chooseCourse('<?=$course_code?>','<?=$token_key_true;?>');"><a  class="go-green" href="javascript: ;"  > 选修课程</a></span>
			    <?php
					    }
				    } else {
			    ?>
				           <span style="float: left; color: red" class="de5">你已提交该课程的选修申请</span> <?php
				    }
				   } else {
					if ($course_info ['visibility'] == 1 ||$course_info ['visibility'] == 0) { //已选修
				?>
					<a target="_top" href="course_home.php?cidReq=<?=$course_code?>&action=introduction" class="go">进入课程</a>
				<?php
				    }
			    }
			    ?>
			</td>
		</tr>
	</tfoot>
</table>
</body>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript">
function chooseCourse(code,token){
 $.ajax({
	 type:"get", 
         url:"ajax_test.php",
         data:"ajaxAction=subscribe"+"&code="+code+"&course_class_id=&id=<?=$id?>&cate=<?=$category_id?>&category_id=<?=$cate?>&referer=<?=$u2[0]?>"+"&"+token+"=token",
	 dataType:"html",
	 success:function(data){
		 if(data){
             if(data !== 'err') {
                 $('#chooid').html('<a target="_top" href="course_home.php?cidReq=' + code + '&action=introduction" class="go">进入课程</a><input type="hidden" id="goreferen" value="' + code + '" />');
                 var countnum = $('#countnumber').html();
                 var pepol = parseInt(countnum);
                 pepol++;
                 $('#countnumber').html(pepol);
                 $('#tishi').html('你已选修该课程');
             }else{
                 $('#tishi').html('<span style="color:red;">非法操作!</span>');
             }
         }
      }
 });
}
 
</script>
 
</html>
