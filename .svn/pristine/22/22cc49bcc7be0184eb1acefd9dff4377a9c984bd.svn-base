 <?php  
$cidReset = true;
include_once ("inc/app.inc.php");
$user_id = api_get_user_id (); 

$teacher_id=intval(getgpc('teacher_id'));
if($teacher_id){
    $_SESSION['report']['teacher_id']=$teacher_id;
}
$category_id= intval(getgpc('category_id'));
$sel=addslashes(htmlspecialchars($_POST['auto-id-rTOGAi3MiQOM7HrB']));
$point_out_status=$_POST['point_out_status1']; 
if($point_out_status==1){
     $_SESSION['point_out_status1']=1; 
      $sql = "select `vmid`,`addres`,`proxy_port`,`user_id`,`lesson_id`,`stime`  FROM  `vmtotal` where `user_id`= '{$user_id}' and  `manage`='0'"; 
                $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                $vm= array (); 
                while ($vm = Database::fetch_row ( $res)) { 
                    $vms [] = $vm; 
                }
                if($vms){ 
                    if($_SESSION['point_out_status1']==1){ 
                        foreach ( $vms as $k1 => $v1){  
                                $vmid = $v1[0];
                                $vmaddres = $v1[1];
                                $proxy_port=$v1[2];
                                $userId=$v1[3];
                                $lesson=$v1[4];
                                 $stime=$v1[5];
                                if($vmid && $vmaddres){
                                        $platforms=file_get_contents(URL_ROOT.'/www'.URL_APPEDND.'/storage/DATA/platform.conf');
                                        $platform_array=explode(':',$platforms);
                                        $platform=intval(trim($platform_array[1]));
                                        $output="sudo -u root /usr/bin/ssh root@".$vmaddres." /sbin/cloudvmstop.sh ".$vmid." ".$user_id;
                                        $output1="sudo -u root  /sbin/cloudvncstop.sh ".$vmid." ".$user_id;
                                        usleep(rand(0,1500));
                                        exec($output,$execinfo);
                                        $execinfo1=$execinfo[0];
                                        exec($output1);


                                       $sqla = "delete  FROM  `vmtotal` where `user_id`= ".$user_id." and `proxy_port`='".$proxy_port."' and `lesson_id`='".$lesson."' and `vmid`='".$vmid."'";
                                       $del_res=@api_sql_query ( $sqla, __FILE__, __LINE__ );
        
                                       $isport="sudo -u root   /sbin/cloudhub.sh del ".$proxy_port." ".$user_id;
                                       if($proxy_port){
                                            exec($isport); 
                                       }
                                       if($del_res){
                                           $endtime=  date("Y-m-d H:i:s",time());
//                                           $vm_log="UPDATE `vmdisk_log` SET `end_time`='".$endtime."'  where  `user_id`=".$user_id."  and `vmid`=".$vmid;
                                           $vm_log="UPDATE `vmdisk_log` SET `end_time`='".$endtime."'  where  `user_id`=".$user_id."  and  `vmid`=".$vmid." and `start_time`='".$stime."'";
                                           @api_sql_query ( $vm_log, __FILE__, __LINE__ );
                                       } 
                                    } 
                           }
                  $_SESSION['point_out_status1']=0;      
                  $_SESSION['point_out_status']=0;
                  $_SESSION['point_out_cancel']=0;
                   }
              } 
     header("location:http://".$_POST['page_url']);
}else if($point_out_status==2){
     $_SESSION['point_out_status1']=2;  
     $lessonid=DATABASE::getval("select `lesson_id` FROM  `vmtotal` where `user_id`= '{$user_id}' and  `manage`='0' limit 1");
     header("location:http://".$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT']."/lms/portal/sp/course_home.php?cidReq=".$lessonid."&action=introduction");
}else{
     $_SESSION['point_out_status1']=0;
}
include_once './inc/page_header.php'; 

$sql='select * from course_category where id='.$category_id;
$category_res=  api_sql_query_array_assoc($sql);
$category_data=$category_res[0]; 

//learning center
$objStat = new ScormTrackStat ();
api_session_unregister ( 'oLP' );
api_session_unregister ( 'lpobject' ); 

//$sql="SELECT `view_course_user`.*,`course`.`category_code`,`course`.`code`,`course_category`.`id`,`course_category`.`parent_id` ,`course`.`description` FROM (`course`INNER JOIN `view_course_user` ON `course`.`code` = `view_course_user`.`course_code`)INNER JOIN `course_category` ON `course`.`category_code` = `course_category`.`id` WHERE `view_course_user`.`user_id` = '".$user_id."' AND `view_course_user`.`visibility` != ''AND `view_course_user`.`is_valid_date` != '0'  ";
if($_SESSION['_user']['status']==1 || $_SESSION['_user']['status']==10){
    $sql="SELECT  DISTINCT `course_rel_user`.`course_code`,`view_course_user`.*, `user`.`user_id` FROM  `course_rel_user`,`user` , `view_course_user` WHERE  `course_rel_user`.`arrange_user_id` =".$teacher_id." AND `course_rel_user`.`user_id`=`user`.`user_id` and `course_rel_user`.`course_code`=`view_course_user`.`course_code` ";
}else{
$sql="SELECT  DISTINCT `course_rel_user`.`course_code`,`view_course_user`.*, `user`.`user_id` FROM  `course_rel_user`,`user` , `view_course_user` WHERE `course_rel_user`.`user_id` = '".$user_id."' AND `course_rel_user`.`arrange_user_id` =".$teacher_id." AND `course_rel_user`.`user_id`=`user`.`user_id` and `course_rel_user`.`course_code`=`view_course_user`.`course_code` ";
}
if( $category_id ){
    $category_id= intval(getgpc('category_id'));
    $sqlwhere=" and `course_rel_user` .`course_code` in (select code from course where category_code=$category_id)";
    $sql .= $sqlwhere; 
}
$sql.=" group BY `course_rel_user`.`course_code`";
$personal_course_list1 = api_sql_query_array_assoc ( $sql, __FILE__, __LINE__ );
$total_rows = intval(count($personal_course_list1));
 
$parms="teacher_id=".$teacher_id."&category_id=".(int)$_GET['category_id'];
$url = WEB_QH_PATH . "arranged_course_list.php?".$parms;
$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );

//$sql .= " ORDER BY `course_rel_user`.`course_code`";
if( $_GET['offset']==''){
    $offset=0;
}else{
    $offset=getgpc ( "offset", "G" );
}

$sql .= " LIMIT " . $offset . "," . NUMBER_PAGE;
$personal_course_list = api_sql_query_array_assoc ( $sql, __FILE__, __LINE__ );

if($category_id=='' && !isset($_GET['category_id']) && !isset($_GET['pid'])){
    $seelectd=" first cur";
}else{
    $seelectd=" haschildren";
}

//课程分类
$sql="SELECT DISTINCT `username` FROM   `view_course_user`  WHERE `category_code`=".$category_id;
$count_use_data=  api_sql_query_array_assoc($sql);
$count_users=count($count_use_data);  
?>      
     <div class="clear"></div> 
                <div class="m-moclist">
                <div class="g-flow" id="j-find-main">
                <div class="b-30"></div>
                <div class="g-container f-cb" style="display:flex;">	 
                <div class="g-sd1 nav">
                    <div class="m-sidebr" id="j-cates">
                        <ul class="u-categ f-cb">
                            <li class="navitm it f-f0 f-cb<?=$seelectd?>" style="" data-id="-1" data-name="我的学习课程" id="auto-id-D1Xl5FNIN6cSHqo0">
                                <a class="f-thide f-f1"  style="background-color:<?=( api_get_setting("lm_switch")=="true"?'#357CD2;':'#13a654;')?>;color:#FFF" href="arranged_course_list.php?teacher_id=<?=$teacher_id?>" title="我的学习课程">我的学习课程</a>
                            </li>
                            <?php  
                             $sql="SELECT distinct `parent_id` FROM (`course` INNER JOIN `course_rel_user` ON `course`.`code`=`course_rel_user`.`course_code`) INNER JOIN `course_category` ON `course`.`category_code`=`course_category`.`id` where `course_category`.`parent_id`!=0 and `course_rel_user`.`user_id`='".$user_id."' and `course_rel_user`.`arrange_user_id`= ".$teacher_id;
                             $sql.=" order by `parent_id`";
                             $res=api_sql_query_array($sql);
                             $countS=count($res);
                             $seelectd="";
                             foreach ($res as $value) { 
                                    $category_cate=  Database::getval("select  `name` from `course_category` where `id`=".$value['parent_id'],__FILE__,__LINE__);
                                    if(isset($_GET['pid']) && $pid=='1'){
                                        if($category_id==$value['parent_id']){
                                            $seelectd=" first cur";
                                        }else{
                                            $seelectd=" haschildren";
                                        }
                                     }else{
                                         $category_pid=  Database::getval("select  `parent_id` from `course_category` where `id`=".$category_id,__FILE__,__LINE__);
                                        if($category_pid==$value['parent_id']){
                                            $seelectd=" first cur";
                                        }else{
                                            $seelectd=" haschildren";
                                        }
                                     }
                                    ?>
                                    <li class="navitm it f-f0 f-cb<?=$seelectd?>" data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                                        <a class="f-thide f-f1" title="<?=$category_cate?>"  href="#"><?=$category_cate?></a>
                                        <div class="i-mc">
                                            <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                                <?php   
                                                $sql1="SELECT  distinct `id`,`name` FROM (`course` INNER JOIN `course_rel_user` ON `course`.`code`=`course_rel_user`.`course_code`) INNER JOIN `course_category` ON `course`.`category_code`=`course_category`.`id` where `course_category`.`parent_id`=".$value['parent_id']." and `course_rel_user`.`user_id`='".$user_id."' and `course_rel_user`.`arrange_user_id`= ".$teacher_id;
                                                $sql1.=" order by `parent_id`";
                                                $rews1=  api_sql_query_array_assoc($sql1);
                                                $counts=intval(count($rews1));
                                                if($counts>0){
                                                    foreach ($rews1 as $v1) {
                                                          if($v1['name']!=='' && $v1['id']!==''){ 
                                                             echo "<a class='j-subit f-ib f-thide' href='".URL_APPEND."portal/sp/arranged_course_list.php?teacher_id=".$teacher_id."&category_id=".$v1['id']."'>".$v1['name']."</a>";
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
                   </div>



           <!--  右侧 -->
		   <div class="g-mn1" >
		        <div class="g-mn1c m-cnt" style="display:block;">
                            <div class="j-list lists" id="j-list"> 
                                <div class="u-content">
                                    <h3 class="sub-simple u-course-title">
                                        <span class="u-title-next">教师安排全部课程</span>
                                    </h3>
                                    <div class="u-content-bottom">
               <?php 
           $i=1;
        if ($total_rows>0) {
            foreach ( $personal_course_list as $course ) {
                $title = $state = '';
                $course_system_code = $course ['course_code'];
                $course_title = Database::getval("select title from course where code=".$course_system_code);
                $course_visibility = $course ['visibility'];
                $is_course_admin = CourseManager::is_course_admin ( $user_id, $course_system_code );
                 $title .= '<a  href="' . WEB_QH_PATH . 'course_home.php?cidReq=' . $course_system_code . '&action=introduction" title="' . $course_title . '">' . api_trunc_str2 ( $course_title ,45) . '</a>';
                $icon = Display::return_icon ( "course.gif", get_lang ( "Student" ),array ('style' => 'vertical-align: middle;') );
                ?>
                    <ul class="u-course-time">
                        <li class="title-time p-514 ">课程<?=$i?></li>
                        <li class="title-name"><?=$icon . "&nbsp;" . $title . " " . $state?></li>
                         <li class="add-lab p-514 ">
                                 <?php
                                 $description=Database::getval("select description from course where code=".$course_system_code);
                                   if($description=='0'){
                                       echo '初级';
                                   }
                                   if($description=='1'){
                                       echo '中级';
                                   }
                                   if($description=='2'){
                                       echo '高级';
                                   }
                                   if($description!=='0' && $description!=='1' && api_trunc_str2($description)!=='2'){
                                       echo '初级';
                                   }
                                   ?>  
                              </li>
                            <li class="lab-time f-13">
                                        <?php		   
                                    if ($course ["is_required_course"]=='1' ) {
                                        ?><span  style="color:#FF0000">必修课</span><?php
                                    } else {
                                       echo "选修课"; 
                                        }?>
                             </li> 
                              <li class="lab-time f-13">
                                  <?=$course ['credit_hours']."学时"?>
                             </li> 
                             <li class="lab-time f-13">
                                  <?=CourseManager::get_course_user_count($course ["code"])?>人
                             </li> 
                    </ul>
                             
                <?php
                $i++;
            } 
        ?>
    <div class="page">
        <ul class="page-list">
            <li class="page-num">总计<?=$total_rows?>个课程</li>
            <?php
            echo $pagination->create_links ();
            ?>
        </ul>
    </div>

        <?php }else{?>
        <div class="error">没有相关的课程</div>

        <?php }?>
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