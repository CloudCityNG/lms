<?php  
$cidReset = true;
include_once ("inc/app.inc.php");
$user_id = api_get_user_id ();  
include_once './inc/page_header.php';
 if(api_get_setting ( 'enable_modules', 'course_center' ) == 'false'){
      echo '<script language="javascript"> document.location = "./learning_center.php";</script>';
      exit ();
 }

$id= intval(getgpc('id'));

if(!isset($_GET['id']) && $id==''){
    $sql =  "select id from setup order by id LIMIT 0,1";
    $courseId= DATABASE::getval ( $sql, __FILE__, __LINE__ );
    if($courseId!==''){
        echo '<script language="javascript"> document.location = "./course_list.php?id='.$courseId.'";</script>';
    }
}

$category_id= intval(getgpc("category"));  

$subclass='';
if($category_id){   //选中某个一级分类
    $subclass[]=$category_id;
}else{  //某课程体系下的所有一级分类
    $sql1="SELECT subclass FROM `setup` WHERE id=$id";
    $re=  Database::getval($sql1);
    $rews=explode(',',$re);
    foreach ($rews as $v) {
        if($v!==''){
           $subclass[]=$v; 
        }
    }
}

$tbl_course  = Database::get_main_table(TABLE_MAIN_COURSE);
$tbl_course_openscore = Database::get_main_table ( TABLE_MAIN_COURSE_OPENSCOPE );

if (api_is_platform_admin () OR api_get_setting('course_center_open_scope')==1) {
    $sql = "SELECT category_code,count(*) FROM $tbl_course  GROUP BY category_code";
} else {
    $sql = "SELECT category_code,count(*) FROM $tbl_course WHERE code IN (SELECT course_code FROM " . $tbl_course_openscore . " WHERE user_id='" . api_get_user_id () . "')  GROUP BY category_code";
}
$category_cnt = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );
 
$objCrsMng=new CourseManager();//课程分类  对象。
$objCrsMng->all_category_tree = array ();
$category_tree = $objCrsMng->get_all_categories_trees ( TRUE,$subclass);

//Recently Study
$sql="SELECT `code`,`title`  FROM  `course` INNER JOIN `course_rel_user` ON `course_rel_user`.`course_code`=`course`.`code` where `user_id`=".$user_id." ORDER BY  `course_rel_user`.`last_access_time` DESC  limit  0,8";
//echo $sql;
$Recently_Study=  api_sql_query_array_assoc($sql,__FILE__,__LINE__);
$Recently_Study_count=intval(count($Recently_Study));
    
?>  
    <!-- 导航结束 -->
     <div class="clear"></div> 
	<div class="m-moclist">
	    <div class="g-flow" id="j-find-main"> 
               <div class="b-30"></div>
 <div class="g-container f-cb">

            <div class="g-sd1 nav"> 
                
                
                    <div class="m-sidebr" id="j-cates">
                           <ul class="u-categ f-cb">
                               <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="学习中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" style="background-color:#13a654;color:#FFF" title="学习中心">学习中心</a>
                               </li>
                               <?php  
                                $sql="SELECT id,title FROM `setup` ORDER BY custom_number";
                                  $res=  api_sql_query_array($sql);
                                  foreach ($res as $value) {
                                      ?>
                            <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                            <a class="f-thide f-f1" <?=$value['id']==$id?' style="color:green;font-weight:bold"':''?> title="<?=$value['title']?>" href="<?=URL_APPEND."portal/sp/learning_before.php?id=".$value['id']?>"><?=$value['title']?></a>
                                <div class="i-mc">
                                                    <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                                            <?php    
                                                            $sql1="SELECT `subclass` FROM setup WHERE id=".$value['id'];
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
                                                                $url = "learning_before.php?id=".$value['id']."&category=" . $category ['id'];
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
                                <li class="navitm it f-f0 f-cb haschildren course-mess"  data-id="-1" data-name="选课记录">
                                     <a class="f-thide f-f1" title="选课记录" href="./course_applied.php" >选课记录</a>
                                </li>
                                <li class="navitm it f-f0 f-cb haschildren course-mess"  data-id="-1" data-name="课程表">
                                     <a class="f-thide f-f1" title="课程表" href="./syllabus.php">课程表</a>
                                </li>
                               </ul>
                        </div>
                
                 <!--下部--> 

                        <div class="m-university" id="j-university">
                            <div>
                                   <div class="bar f-cb" style="background-color:#13a654;">
                                          <h3 class="left f-fc3 rece-h3" style='color:#FFF;'>最近学习</h3>
                                   </div>
                                 <div class="us">
                                 <?php
                                    if($Recently_Study_count>0){
                                        foreach ($Recently_Study as $values1) { ?>
                                            <div class="Recently_Study">
                                               <a class="recently1" href="<?=URL_APPEND?>portal/sp/course_home.php?cidReq=<?=$values1['code']?>&action=introduction" class="logo" >
                                                 <?=api_trunc_str2($values1['title'],18)?> 
                                               </a>
                                           </div>
                                    <?php
                                        }
                                    }else{?>
                                        <div class="Recently_Study">
                                                 没有最近学习
                                           </div>
                                   <?php
                                   }
                                    ?>
                                </div> 
                        </div>
                </div>
           

                  
</div>

           <!--  右侧 -->
 <div class="g-mn1">
     <div class="g-mn1c m-cnt" style="display:block;background:#fff;border:1px solid #fff;">
      <div class="j-list lists" id="j-list">
	<div class="f-cb">
             <div class="cnt f-cb" id="auto-id-k6s3rv2cJswp3exB">
                 <div class="course-tags">
                     <table cellspacing="0" cellpadding="0" width="100%" class="tbl_course" style="margin-top:0;">
                         <tbody>
                             <tr>
                                <th width="60%">课程名称</th>
                                <th width="15%">已选修课程</th>
                                <th width="15%">未选修课程数</th>
                                <th width="10%">总课程数</th>
                             </tr>
                             <tr>
                                 <td colspan="10">
                                     <h3 class="sub-simple u-course-title"></h3>
                                 </td>
                             </tr>
                             <tr>
                                 <td>L001001001-口令破解-01HASH基础</td>
                                 <td class="t-center">
                                     <input class="td-btn" type="button" value="进入">
                                 </td>
                                 <td class="t-center">
                                     <input class="td-btn" type="button" value="进入">
                                 </td>
                                  <td class="t-center">800</td>
                             </tr>
                             <tr>
                                 <td colspan="10">
                                     <h3 class="sub-simple u-course-title"></h3>
                                 </td>
                             </tr>
                              <tr>
                                 <td>L001001001-口令破解-01HASH基础</td>
                                 <td class="t-center">
                                     <input class="td-btn" type="button" value="进入">
                                 </td>
                                 <td class="t-center">
                                     <input class="td-btn" type="button" value="进入">
                                 </td>
                                  <td class="t-center"> 800</td>
                             </tr>
                              <tr>
                                 <td colspan="10">
                                     <h3 class="sub-simple u-course-title"></h3>
                                 </td>
                             </tr>
                              <tr>
                                 <td>L001001001-口令破解-01HASH基础</td>
                                 <td class="t-center">
                                     <input class="td-btn" type="button" value="进入">
                                 </td>
                                 <td class="t-center">
                                     <input class="td-btn" type="button" value="进入">
                                 </td>
                                  <td class="t-center"> 800</td>
                             </tr>
                         </tbody>
                     </table>
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
        
<script  type="text/javascript">

     jQuery(function(){
 
         var   val=jQuery("#tol_input1").val();
         var   val2=jQuery("#tol_input2").val();
      $("#auto_id1").css('background','#13A654');
      $("#auto_id1").css('color','#FFFFFF'); 
     if(val===1){
         jQuery(".cnt f-cb").children("div").hide();
         jQuery("#to_ol_class1").show();
     }
     jQuery("#tool_right1").click(function(){
         
          var   val1=jQuery("#tol_input1").val();
          if(val1<val2){
        var addval=val1*1+1;
      $("#auto_id"+addval).css('background','#13A654');
      $("#auto_id"+addval).css('color','#FFFFFF');
        for(var i=1;i<=val2;i++){
          if(i!==addval){
          $("#auto_id"+i).css('background','#EEEEEE');
          $("#auto_id"+i).css('color','#999');  
          }
      }
        jQuery("#tol_input1").val(addval);
         var hide="#to_ol_class"+val1;
 
            jQuery(hide).fadeOut(500,function(){
                  var  show="#to_ol_class"+addval;
                  jQuery(".cnt f-cb").children("div").hide();
                 jQuery(show).show();
             });
         }
    });
    
      jQuery("#tool_left1").click(function(){
         
          var   val1=jQuery("#tol_input1").val();
          if(val1>1){
        var addval=val1*1-1;
        $("#auto_id"+addval).css('background','#13A654');
      $("#auto_id"+addval).css('color','#FFFFFF');
        for(var i=1;i<=val2;i++){
          if(i!==addval){
          $("#auto_id"+i).css('background','#EEEEEE');
          $("#auto_id"+i).css('color','#999');  
          }
      }
        jQuery("#tol_input1").val(addval);
         var hide="#to_ol_class"+val1;
 
            jQuery(hide).fadeOut(500,function(){
                  var  show="#to_ol_class"+addval;
                  jQuery(".cnt f-cb").children("div").hide();
                 jQuery(show).fadeIn(500);
             });
         }
    });
        
    });
    function yema(id,all){
      $("#tol_input1").val(id);
      $("#auto_id"+id).css('background','#13A654');
      $("#auto_id"+id).css('color','#FFFFFF');
      for(var i=1;i<=all;i++){
          if(i!==id){
          $("#auto_id"+i).css('background','#EEEEEE');
          $("#auto_id"+i).css('color','#999');  
          }
      }
      $("#to_ol_class"+id).fadeOut(500,function(){
                  var  show="#to_ol_class"+id;
                  $(".cnt").children("div").hide();
                  $("#tol_input2").css('display','block');
                  $("#auto_id123").css('display','block');
                 $(show).fadeIn(500);
             });  
    }
</script>
        
 </body>
</html>
