<?php
$cidReset = true;
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']).':'.$_SERVER['SERVER_PORT'];
include_once ("inc/app.inc.php");
if(api_get_setting ( 'lnyd_switch' ) == 'true'){
        if(!api_get_user_id ()){
                echo '<script language="javascript"> document.location = "./login.php";</script>';
                exit();
        }
}
$user_id = api_get_user_id (); 
if(!api_get_user_id ()){
    $_SESSION['up_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
}
$category_id= intval(getgpc('category_id'));
$id=intval($_GET['id']);


$grade= intval(getgpc('grade','G'));
$pid=  getgpc('pid','G');
$select=  intval($_GET['select']);
$sel = htmlspecialchars($_POST['auto-id-rTOGAi3MiQOM7HrB']);
if(!empty($_GET['keyword']) && $category_id === 0){
    unset($category_id);
    unset($_GET['category_id']);
}
 if(api_get_setting ( 'enable_modules', 'course_center' ) == 'false'){
      echo '<script language="javascript"> document.location = "./learning_center.php";</script>';
      exit ();
 }
$my_courses_all = CourseManager::get_user_subscribe_courses_code ( $user_id );

if($category_id!==''){
    $parent_cateid=mysql_fetch_row(mysql_query('select parent_id from course_category where id='.$category_id));
    $sql_cc="SELECT `course`.`code` FROM `course` INNER JOIN `view_course_user` ON `course`.`code` = `view_course_user`.`course_code` WHERE `view_course_user`.`user_id` =".$user_id." AND `course`.`category_code` =".$category_id;
    $course_codess=api_sql_query_array_assoc($sql_cc,__FILE__,__LINE__); 
    $course_count=count($course_codess);
    $end_code=$course_count-1;
    if($course_count){ 
        $code_var='';
        foreach ($course_codess as $key => $value) {
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

$sql='select * from course_category where id='.$category_id;
$category_res=  api_sql_query_array_assoc($sql);
$category_data=$category_res[0]; 

$table_course = Database::get_main_table ( TABLE_MAIN_COURSE );
$tbl_course_category = Database::get_main_table ( TABLE_MAIN_CATEGORY );
$tbl_course_openscore = Database::get_main_table ( TABLE_MAIN_COURSE_USER );

//Recently Study
$sql="SELECT `code`,`title` FROM  `course` INNER JOIN `course_rel_user` ON `course_rel_user`.`course_code`=`course`.`code` where `user_id`=".$user_id." limit  0,5";
$Recently_Study=  api_sql_query_array_assoc($sql,__FILE__,__LINE__);
$Recently_Study_count=intval(count($Recently_Study));

$sql="SELECT * FROM `vslab`.`course` WHERE 1 ";
if(isset($_GET['category_id']) && $category_id!=='' && is_numeric($_GET['category_id'])){
        $sql.=" AND category_code=".$category_id;
}
$category_name = Database::get_scalar_value ( $sql );
 
$CourseDescription = "SELECT * FROM $tbl_course_category WHERE id='" . escape ( $category_id ) . "' limit 1";//"select * from course_catalog where  "

$ress = api_sql_query($CourseDescription, __FILE__, __LINE__ );

 $vms[0]= Database::fetch_row ( $ress); 
if (isset ( $_GET['keyword'] ) && is_not_blank ( $_GET['keyword'] )) { 
    $keyword = Database::escape_str ( urldecode ( $_GET['keyword'] ), TRUE );
    $sqlwhere .= " AND (title LIKE '%" . $keyword . "%' OR code LIKE '%" . $keyword . "%')";
}

if(is_not_blank($sel)){ 
    $sqlwhere.="  AND (`title` LIKE '%" . trim ( $sel ) . "%' OR `code` LIKE '%" . trim ( $sel ) . "%')"; 
}
if($grade==1||$grade==2){
    $sql1=" SELECT count(*) FROM  `course` WHERE 1  AND description = '". $grade ."' ";
    if(isset($_GET['category_id']) && $_GET['category_id']!=='' && is_numeric($_GET['category_id'])){
        $sql1.=" AND category_code=".$category_id;
    }
    if(isset($select) && $select!=''){
        if($category_id){
            $sql1.=' AND `course`.`code` NOT IN '.$code_var." ";
        }
    }
    $sql1.= $sqlwhere ;
    $total_rows = DATABASE::getval($sql1,__FILE__,__LINE__); 

}elseif($grade==3){
    $sql1=" SELECT count(*) FROM  `course` WHERE 1 AND description in ('0','') ";
    if(isset($_GET['category_id']) && $_GET['category_id']!=='' && is_numeric($_GET['category_id'])){
        $sql1.=" AND category_code=".$category_id;
    }
    if(isset($select) && $select!=''){
        if($category_id){
            $sql1.=' AND `course`.`code` NOT IN '.$code_var." ";
        }
    }
    $sql1.= $sqlwhere ;
    $total_rows = DATABASE::getval($sql1,__FILE__,__LINE__); 
}else{
    $sql1=" SELECT count(*) FROM  `course` WHERE 1 ";
    if(isset($_GET['category_id']) && $_GET['category_id']!=='' && is_numeric($_GET['category_id'])){
        $sql1.=" AND category_code=".$category_id;
    }

    if(isset($select) && $select!=='' && is_numeric($_GET['select'])){

        if($category_id){
            $sql1.=' AND `course`.`code` NOT IN '.$code_var." ";
        }
    }
    $sql1.= $sqlwhere ;
    $total_rows = DATABASE::getval($sql1,__FILE__,__LINE__);
}

$url = 'course_catalog.php?category_id=' .$category_id . '&keyword=' .( $sel?urlencode ( $sel ):urlencode ($_GET['keyword'])).'&grade=' .$grade;
if(isset($select) && $select=='1' && is_numeric($_GET['select'])){

    $url.="&select=1";
}

$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );

if (api_is_platform_admin () or api_get_setting ( 'course_center_open_scope' ) == 1) {
        if($grade==1||$grade==2){
            $sql = "SELECT * FROM " . $table_course . " WHERE 1 AND `description` = '". $grade ."' ";
        }elseif($grade==3){
            $sql = "SELECT * FROM " . $table_course . " WHERE 1 AND `description` in ('0','') ";
        }else{
            $sql = "SELECT * FROM " . $table_course . " WHERE  1 ";
        }
         if(!isset($_GET['category_id']) || $category_id==''){
            $sql.=" ".$sqlwhere;
         }else{
            $sql.=" AND `category_code` = '" .$category_id. "'". $sqlwhere;
         }
}else{
    if($grade==3||$grade==1||$grade==2){
            $sql = "SELECT * FROM " . $table_course . " WHERE `description` = '". $grade ."' AND code IN (SELECT course_code FROM " . $tbl_course_openscore . " WHERE user_id='" . $user_id . "') " . $sqlwhere;
        }else{
            $sql = "SELECT * FROM " . $table_course . " WHERE code IN (SELECT course_code FROM " . $tbl_course_openscore . " WHERE user_id='" . $user_id . "') " . $sqlwhere;
        }

        if(!isset($_GET['category_id']) || $category_id==''){
            $sql.=" ".$sqlwhere;
        }else{
            $sql.=" AND `category_code` = '" .$category_id. "'". $sqlwhere;
        }
}
if(isset($select) && $select!=''){
    if($category_id){
        $sql.=' AND `course`.`code` NOT IN '.$code_var." ";
    }
}

$sql .= " ORDER BY category_code,title";
$offset = (int)getgpc ( "offset", "G" );
if (empty ( $offset )) $offset = 0;
$sql .= " LIMIT " . $offset . "," . NUMBER_PAGE;

$course_list = api_sql_query_array_assoc ( $sql, __FILE__, __LINE__ );
if ($param {0} == "&") $param = substr ( $param, 1 );
$url = WEB_QH_PATH . "learning_center.php?" . $param; 
display_tab ( TAB_COURSE_CENTER );
Display::display_thickbox(false,true);

if(isset($_GET['category_id']) && $category_id!==''){
     $sql11="SELECT DISTINCT `username` FROM   `view_course_user`  WHERE `category_code` =".$category_id;
     $count_use_data=  api_sql_query_array_assoc($sql11);
     $count_users=count($count_use_data);  
}
$sql1="select  `id`, `title`, `description`, `subclass` from `setup`";
$setup_data=  api_sql_query_array_assoc($sql1);

?> 
     <div class="clear"></div> 
	<div class="m-moclist">
	    <div class="g-flow" id="j-find-main">
		     <div class="b-30">
                          <div class="j-nav nav f-cb" style="margin-top:4px;"> 
					        <div class="u-btn-group right" id="j-tab">
                                                    <?php
                                                    if($category_id==''){
                                                    ?>
                                                    <a class="u-btn u-btn-sm<?=$grade==''?' u-btn-left u-btn-active':''?>" title="全部课程" href="course_catalog.php">全部</a>
                                                    <a class="u-btn u-btn-sm<?=$grade=='3'?' u-btn-left u-btn-active':''?>" title="初级课程" href="course_catalog.php?grade=3">初级</a>
                                                    <a class="u-btn u-btn-sm<?=$grade=='1'?' u-btn-left u-btn-active':''?>" title="中级课程" href="course_catalog.php?grade=1">中级</a>
                                                    <a class="u-btn u-btn-sm<?=$grade=='2'?' u-btn-left u-btn-active':''?>" title="高级课程" href="course_catalog.php?grade=2">高级</a>
                                                    <?php
                                                    }else{
                                                    ?>
                                                    <a class="u-btn u-btn-sm<?=$grade==''?' u-btn-left u-btn-active':''?>" title="全部课程" href="course_catalog.php?category_id=<?= $category_id?>">全部</a>
                                                    <a class="u-btn u-btn-sm<?=$grade=='3'?' u-btn-left u-btn-active':''?>" title="初级课程" href="course_catalog.php?category_id=<?= $category_id?>&grade=3">初级</a>
                                                    <a class="u-btn u-btn-sm<?=$grade=='1'?' u-btn-left u-btn-active':''?>" title="中级课程" href="course_catalog.php?category_id=<?= $category_id?>&grade=1">中级</a>
                                                    <a class="u-btn u-btn-sm<?=$grade=='2'?' u-btn-left u-btn-active':''?>" title="高级课程" href="course_catalog.php?category_id=<?= $category_id?>&grade=2">高级</a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
					   </div>
                     </div>
	<div class="g-container f-cb">		 
            <div class="g-sd1 nav">
                <?php
                         if(isset($_GET['category_id']) && $category_id!==''){
                ?>
                    <div class="m-sidebr" id="j-cates">
                           <ul class="u-categ f-cb">
<!--                               <li class="navitm it f-f0 f-cb<?=$seelectd?>" data-id="-1" data-name="我的学习课程" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" title="我的学习课程">我的学习课程</a>
                               </li>-->
                               <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="全部课程" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" style="background-color:<?=( api_get_setting("lm_switch")=="true"?'#357CD2;':'#13a654;')?>;color:#FFF" title="全部课程">全部课程</a>
                               </li>
                               <?php  
                                $sql="SELECT `id`,`name` from `course_category` where `course_category`.`parent_id`=0 ";
                                $sql.=" order by `id`";
                                $res=api_sql_query_array($sql);
                                  $countS=count($res);
                                  foreach ($res as $value) {
                                       if(isset($_GET['pid']) && $pid=='1' && is_numeric($_GET['pid'])){
                                          if($category_id==$value['id']){
                                              $seelectd=" first cur";
                                          }else{
                                              $seelectd=" haschildren";
                                          }
                                       }else{
                                           $category_pid=  Database::getval("select  `parent_id` from `course_category` where `id`=".$category_id,__FILE__,__LINE__);
                                          if($category_pid==$value['id']){
                                              $seelectd=" first cur";
                                          }else{
                                              $seelectd=" haschildren";
                                          }
                                       }
                                       foreach($setup_data as $k1=>$v1){
                                            $subclass=  explode(',',$v1['subclass']);
                                            $subclass=  array_filter($subclass);
                                            if(in_array($value['id'],$subclass)){
                                                $setup_id=$v1['id'];
                                            }
                                       }
                                       
                                      ?>
                                      <li class="navitm it f-f0 f-cb<?=$seelectd?>" data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                                            <a class="f-thide f-f1" title="<?=$category_cate?>"  href="<?=URL_APPEND?>portal/sp/select_study.php?id=<?=$setup_id?>&category=<?=$value['id']?>"><?=$value['name']?></a>
                                                <div class="i-mc">
                                                    <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                                            <?php   
                                                            $sql1="select `id` ,`name` from `course_category` where `parent_id`=".$value['id'];
                                                            $sql1.=" order by `id`";
                                                            $rews1=  api_sql_query_array_assoc($sql1);
                                                            $counts=intval(count($rews1));
                                                            if($counts>0){
                                                                foreach ($rews1 as $v1) {
                                                                      if($v1['name']!=='' && $v1['id']!==''){
                                                                         echo "<a class='j-subit f-ib f-thide' href='".URL_APPEND."portal/sp/course_catalog.php?category_id=".$v1['id']."'>".$v1['name']."</a>";
                                                                      }
                                                                  } 
                                                            }else{ 
                                                              echo "<p align='center'>没有相关课程分类</p>";
                                                            }
                                                                  ?>

                                                        </div>
                                                </div>

                                        </li>
                               <?php  }  ?>
                               </ul>
                        </div>
                <?php
                         }else{
                ?>
                     <div class="m-sidebr" id="j-cates">
                           <ul class="u-categ f-cb">
                               <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="选课中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" style="background-color:#13a654;color:#FFF" title="选课中心">选课中心</a>
                               </li>
                               <?php  
                                $sql="select id,title from setup order by id";
                                  $res=  api_sql_query_array($sql);
                                  foreach ($res as $value) {
                                      ?>
                            <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                                <?php   if(api_get_setting ( 'lm_switch' ) == 'true'){   ?>
                            <a class="f-thide f-f1" <?=$value['id']==$id?'style="color:#357cd2;font-weight:bold"':''?> title="<?=$value['title']?>" href="<?=URL_APPEND."portal/sp/select_study.php?id=".$value['id']?>"><?=$value['title']?></a>
                            <?php   }else{   ?> 
                            <a class="f-thide f-f1" <?=$value['id']==$id?'style="color:green;font-weight:bold"':''?> title="<?=$value['title']?>" href="<?=URL_APPEND."portal/sp/select_study.php?id=".$value['id']?>"><?=$value['title']?></a>                    
                            <?php   }   ?> 
                            <div class="i-mc">
                                                    <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                                            <?php    
                                                            $sql1="select subclass from setup where id=".$value['id'];
                                                              $re1=  Database::getval($sql1);
                                                              $rews1=explode(',',$re1);
                                                                  $subclass1='';
                                                                  foreach ($rews1 as $v1) {
                                                                      if($v1!==''){
                                                                         $subclass1[]=$v1; 
                                                                      }
                                                                  }
                                                              $objCrsMng1=new CourseManager();//课程分类  对象。
                                                              $objCrsMng1->all_category_tree = array (); 
                                                              $category_tree1 = $objCrsMng1->get_all_categories_trees ( TRUE,$subclass1);
                                                              $i = 0;   $j = 0;   $o = array(); //标记循环变量， 数组 ;
                                                              foreach ( $category_tree1 as $category ) { ///父类循环
                                                                $url = "select_study.php?id=".$value['id']."&category=" . $category ['id'];
                                                                  $cate_name = $category ['name'] . (($category_cnt [$category ['id']]) ? "&nbsp;(" . $category_cnt [$category ['id']] . ")" : "");
                                                                  if($category['parent_id']==0) {
                                                                  ?>
                                                                <a class="j-subit f-ib f-thide" href="<?=$url?>"><?=$cate_name?></a>
                                                                  <?php  if($i==3){$i=0;}
                                                                    }  
                                                                 }
                                                                  if(!$category_tree1){    
                                                                      echo "<p align='center'>没有相关课程分类，请联系课程管理员</p>";
                                                                  }
                                                                  ?>

                                                        </div>
                                                </div>

                                        </li>
                               <?php  }  ?>
                               </ul>
                        </div>
                <?php
                         }
                ?>  
           </div>
 
           <!--  右侧 -->
		   <div class="g-mn1" >
		        <div class="g-mn1c m-cnt" style="display:block;">

                     <div class="j-list lists" id="j-list">
                         <?php
                         if(isset($_GET['category_id']) && $category_id!==''){
                         ?>
					    <div class="m-allwrap f-cb">

						   <div class="cnt f-cb" id="auto-id-k6s3rv2cJswp3exB">
						       <div class="course-top">
							        <div class="g-cell1 u-card j-href ie6-style no-common" data-href="#">
									   <div class="card">
									        <div class="u-img f-pr">
                                                                                        <a href="course_catalog_info.php?action=view&category_id=<?=$category_id?>">
                                                                                            <img  class="img" src="../../storage/category_pic/<?=$vms[0][4]?>" width="222" height="124" alt="<?=$category_data["name"]?>" title="<?=$category_data["name"]?>"> 
                                                                                        </a>
											</div>
											 <div class="f-pa over f-fcf">
												<span class="txt"><?=$total_rows?>个课程</span>
											</div>
											<div class="subject-study">
											  <span>学习:</span>
											  <span class="sub-img"></span>
                                              <span class="study-num"><?=$count_users?>人</span>
											  <span class="study-good"></span>
											</div>
											<p class="t2 f-thide">
											</p>
											
									   </div>
									</div>

									<div class="course-simple right1">
									    <h3 class="sub-simple">简介</h3>
										<p class="sub-con">
                                                                                <?php
                                                                                    if($category_data["CourseDescription"]!==''){
                                                                                        echo  api_trunc_str2($category_data["CourseDescription"],225);
                                                                                    }else{
                                                                                        echo "没有课程简介！！！";
                                                                                    }
                                                                                    ?>
                                                                                </p>
                                                                                <?php
                                                                                if($category_id!==''){
                                                                                    echo '<div class="f-pa over f-fcf u-intro">
												<span class="txt">
                                                                                                    <a href="course_catalog_info.php?action=view&category_id='.$category_id.'">更多</a>
                                                                                                </span>
										</div>';
                                                                                }
                                                                                ?>
										 

									</div> 
                      </div> 
	      </div> 
    </div>
                         <div class="b-20"></div>
                         <?php
                         }
                         ?>
     
                        <div class="u-content">
                                   <h3 class="sub-simple u-course-title">
                                        <span class="u-title-next">目录</span>
                                       </h3>
                                       <div class="u-content-bottom">
                                         <?php
                                           if (is_array ( $course_list ) && count ( $course_list ) > 0)
                                           {
                                               $i=1;

                                                 foreach ( $course_list as $key => $value )
                                                 {

                                                     $token_key = md5(rand(88,88888));
                                                     $_SESSION['_user']['code'][$value['code']] = $token_key;
                                                     $url = WEB_QH_PATH . "course_info.php?code=" . $value ["code"];
                                                     $course_code = $value['code'];
                                         ?>
                                                      <ul class="u-course-time">
                                                          <li class="title-time p-514 ">课程<?=$offset+$i?></li>
                                                          <li class="title-name">
                                                              <?php if(!api_get_user_id ())
                                                              {
                                                                  ?>
                                                               <a href="login.php" title="<?=$value ['title']?>"><?=api_trunc_str2($value ['title'],90)?></a>
                                                              <?php
                                                              }
                                                              else
                                                              {
                                                                  if(api_get_setting ( 'zgkd_switch' ) == 'true')
                                                                  {
                                                              ?>
                                                                     <a  href="course_home.php?cidReq=<?=$course_code?>&action=introduction" title="<?=$value ['title']?>"><?=api_trunc_str2($value ['title'],90)?></a>
                                                              <?php
                                                                  }  else {
                                                              ?>
                                                                     <a  onclick="kecheng('<?php echo $value['code']?>','<?php echo $value['title'];?>','<?=$token_key;?>');"  title="<?=$value ['title']?>"><?=api_trunc_str2($value ['title'],90)?></a>
                                                              <?php
                                                                  }
                                                              }
                                                              ?>

                                                          </li>

                                                           <li class="add-lab p-514 ">
                                                             <?php
                                                                             if(api_trunc_str2($value ['description'])=='0'){
                                                                                 echo '初级';
                                                                             }
                                                                             if(api_trunc_str2($value ['description'])=='1'){
                                                                                 echo '中级';
                                                                             }
                                                                             if(api_trunc_str2($value ['description'])=='2'){
                                                                                 echo '高级';
                                                                             }
                                                                             if(api_trunc_str2($value ['description'])!=='0' && api_trunc_str2($value ['description'])!=='1' && api_trunc_str2($value ['description'])!=='2'){
                                                                                 echo '初级';
                                                                             }
                                                                             ?>
                                                          </li>
                                                        <li class="lab-time f-13">
                                                              <?php
                                                                         if (in_array ( $value ["code"], $my_courses_all )) {
                                                                             ?><span  style="color:#FF0000">已选修</span><?php
                                                                         } else {
                                                                            echo "<span id='code".$value['code']."'>可选修</span>";
                                                                             }?>
                                                         </li>
                                                          <li class="lab-time f-13">
                                                              <?=$value ['credit_hours']."学时"?>
                                                         </li>
                                                         <li class="lab-time f-13">
                                                              <?=CourseManager::get_course_user_count($value ["code"])?>人
                                                         </li>
                                                         <li class="lab-time f-13">
                                                              <?=$value ['tutor_name']?>
                                                         </li>

                                                      </ul>
                                                     <?php
                                                      $i++;
                                                     }
                                           ?>
                                               <div class="page">
                                                   <ul class="page-list">
                                                       <li class="page-num">总计<?=$total_rows?>个课程</li>
                                                      <?=$pagination->create_links (); ?>
                                                   </ul>
                                               </div>
                                               <?php
                                                  }
                                                  else
                                                  {
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
        
        <div id="kecheng">
            <div class="tag-title">
                <div id="kecheng-2"></div>
                <div id="chahao">×</div>
            </div>
            <div id="kecheng-3"></div>
        </div>
 </body>
</html>
<style>
    body{
/*        padding:71px 0 120px 0;*/
        font-size:14px;
    }
    #kecheng{
        -webkit-transition:all .2s ease;
        width:900px;
        padding-bottom:10px;
        background:#fff;
        position:fixed;
        z-index:999;                              
        left:80%;
        border:1px solid #13A654;
        display:none;
        height:300px;
        
        
    } 
    .tag-title{
      height:50px;
      width:100%;
      background:#13A654;
      color:#fff;
    }
    #kecheng-2{
        float:left;
        line-height:50px;
        font-size:20px;
        margin-left:10px;
    }
    #chahao{
        float:right;
       width:20px;
       height:20px;
        cursor:pointer;
       font-size:20px;
       line-height:20px; 
    }
    #chahao:hover{
        color:#FF6600;
        cursor:pointer;
    }
</style>
  <script type="text/javascript">
               var settimes=1;
               $(function(){
                   $('#chahao').click(function(){
                       $("#kecheng").css('display','none');
                       $("#kecheng").css('-webkit-transform','translateX(0%)');
                    $("#kecheng").css('-moz-transform','translateX(0%)');
                    $("#kecheng").css('-o-transform','translateX(0%)');
                    settimes=1;
                    var gorecode=$("#goreferen").val();
                    if(gorecode){
                      $("#code"+gorecode).css('color','red');  
                      $("#code"+gorecode).html('已选修');
                    }
                   });
               });

               function kecheng(code,title,token_key){
                    var bht=document.documentElement.clientHeight;
                    var Hkecheng=$("#kecheng").height();
                   $.ajax({
                       url:'course_info.php',
                      type:'GET',
                      data:'code='+code+'&cate=<?=$parent_cateid[0]?>+&id=<?=$id?>&category_id=<?=$category_id?>&'+token_key+'=token',
                  dataType:'html',
                  success:function(er) {
                      if (er !== 'err') {
                          var ter = /firefox/;
                          if (ter.test(navigator.userAgent.toLowerCase())) {
                              itop = document.documentElement.scrollTop;
                          }
                          if ("ActiveXObject" in window) {
                              $("#kecheng").css('left', '20%');
                              itop = document.documentElement.scrollTop;
                          }

                          var zht = bht / 2 - Hkecheng / 2;
                          $("#kecheng").css('top', zht);
                          $("#kecheng").css('display', 'block');
                          $("#kecheng-2").html(title);
                          $("#kecheng-3").html(er);


                          if (settimes === 1) {
                              $("#kecheng").css('-webkit-transform', 'translateX(-140%)');
                              $("#kecheng").css('-moz-transform', 'translateX(-140%)');
                              $("#kecheng").css('-o-transform', 'translateX(-140%)');

                              setTimeout(function () {
                                  $("#kecheng").css('-webkit-transform', 'translateX(-100%)');
                                  $("#kecheng").css('-moz-transform', 'translateX(-100%)');
                                  $("#kecheng").css('-o-transform', 'translateX(-100%)');
                                  settimes++;
                              }, 200);
                          } else {
                              $("#kecheng").css('-webkit-transform', 'translateX(-100%)');
                              $("#kecheng").css('-moz-transform', 'translateX(-100%)');
                              $("#kecheng").css('-o-transform', 'translateX(-100%)');
                          }
                      }
                  }
                   });
               }
    </script>
