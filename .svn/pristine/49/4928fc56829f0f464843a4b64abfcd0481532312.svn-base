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
include_once './inc/page_header.php';
$user_id = api_get_user_id ();  
if(api_get_setting('enable_modules', 'clay_oven') == 'true'){
    echo '<script language="javascript"> document.location = "./cn/login.php";</script>';
    exit();
}
if ($user_id !== '1'){
	if(api_get_setting('enable_modules', 'clay_oven') == 'true'){
		echo '<script language="javascript"> document.location = "./cn/page_content.php";</script>';
	}
}
if(api_get_setting ( 'enable_modules', 'course_center' ) == 'false'){
      echo '<script language="javascript"> document.location = "./learning_center.php";</script>';
      exit ();
}

$id=(int)getgpc('id');
if(!isset($_GET['id']) && $id==''){
    $sql =  "select id from setup order by id LIMIT 0,1";
    $courseId= DATABASE::getval ( $sql, __FILE__, __LINE__ );
    if($courseId!==''){
        echo '<script language="javascript"> document.location = "./select_study.php?id='.$courseId.'";</script>';exit;
    };
}
$category_id=(int)getgpc("category");
include './left_list.php';
?>
    <!-- 导航结束 -->
     <div class="clear"></div> 
	<div class="m-moclist">
	    <div class="g-flow" id="j-find-main"> 
                <div class="b-30"></div>
                <div class="g-container f-cb">
                    <div class="g-sd1 nav">
                        <!--左上部分--!>
                        <?=$left_list?>
                        <!--左下部-->

                        <div class="m-university" id="j-university">
                            <div>
                                   <div class="bar f-cb" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>">
                                          <h3 class="left f-fc3 rece-h3"  style='color:#FFF;'>最近学习</h3>
                                   </div>
                                 <div class="us">
<?php
$sql="SELECT `code`,`title`  FROM  `course` INNER JOIN `course_rel_user` ON `course_rel_user`.`course_code`=`course`.`code` where `user_id`=".$user_id." ORDER BY  `course_rel_user`.`last_access_time` DESC  limit  0,8";
$Recently_Study=  api_sql_query_array_assoc($sql,__FILE__,__LINE__);
$Recently_Study_count=intval(count($Recently_Study));
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
<?php
include './right_list.php';
echo $right_list;
?>
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
      $("#auto_id1").css('background-color','#13A654');
      $("#auto_id1").css('color','#FFFFFF'); 
     if(val===1){
         jQuery(".cnt f-cb").children("div").hide();
         jQuery("#to_ol_class1").show();
     }
     jQuery("#tool_right1").click(function(){
         
          var   val1=jQuery("#tol_input1").val();
          if(val1<val2){
        var addval=val1*1+1;
      $("#auto_id"+addval).css('background-color','#13A654');
      $("#auto_id"+addval).css('color','#FFFFFF');
        for(var i=1;i<=val2;i++){
          if(i!==addval){
          $("#auto_id"+i).css('background-color','#EEEEEE');
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
        $("#auto_id"+addval).css('background-color','#13A654');
      $("#auto_id"+addval).css('color','#FFFFFF');
        for(var i=1;i<=val2;i++){
          if(i!==addval){
          $("#auto_id"+i).css('background-color','#EEEEEE');
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
        
    });
    function yema(id,all){
      $("#tol_input1").val(id);
      $("#auto_id"+id).css('background-color','#13A654');
      $("#auto_id"+id).css('color','#FFFFFF');
      for(var i=1;i<=all;i++){
          if(i!==id){
          $("#auto_id"+i).css('background-color','#EEEEEE');
          $("#auto_id"+i).css('color','#999');  
          }
      }
      $("#to_ol_class"+id).fadeOut(500,function(){
                  var  show="#to_ol_class"+id;
                  $(".cnt").children("div").hide();
                  $("#tol_input2").css('display','block');
                  $("#auto_id123").css('display','block');
                 $(show).show();
             });  
    }
</script>
        
 </body>
</html>
