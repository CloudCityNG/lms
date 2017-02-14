<?php
$cidReset = true;
include_once ("inc/app.inc.php");
include_once ('../../main/exercice/exercise.class.php');

include_once ("inc/page_header.php");
$tbl_exam_rel_user = Database::get_main_table ( TABLE_MAIN_EXAM_REL_USER ); //考试用户关联表
$tbl_exam_main = Database::get_main_table ( TABLE_QUIZ_TEST ); //crs_quiz

$sql="SELECT * 
FROM  `project` 
WHERE  `release` =1";
$result = api_sql_query ( $sql, __FILE__, __LINE__ );
while ( $row = Database::fetch_array ( $result, 'ASSOC' ) ) {
    $arr[]=$row;
    
}
 

//Recently Study
$sql="SELECT `code`,`title`  FROM  `course` INNER JOIN `course_rel_user` ON `course_rel_user`.`course_code`=`course`.`code` where `user_id`=".$user_id." ORDER BY  `course_rel_user`.`last_access_time` DESC  limit  0,8";
//echo $sql;
$Recently_Study=  api_sql_query_array_assoc($sql,__FILE__,__LINE__);
$Recently_Study_count=intval(count($Recently_Study));
    
?>
<style>
    ul,li{
        padding:0;
        margin:0;
    }
    li{
        list-style:none;
    }
      m-moclist  .j-list:after{
        display:block;
        clear:both;
        content:"";
    } 
    m-moclist  .u-content:after{
        display:block;
        clear:both;
        content:"";
    }  
    .safe-lists{
        padding:0;
        margin:0;
        width:100%;
    }
    .safe-lists li{
       border: 1px rgb(97, 97, 102);
        width: 29.5%;
        height: 100px;
        margin-left:15px;
        margin-right:15px;
        margin-bottom: 30px;
        display:inline-block;
    }
    .safe-lists li .img{
         width: 60px;
        height: 60px;
        margin-top: 20px;
        margin-left: 10px;
        margin-right:10px;
        float: left;
        display: inline;
    }
    .safe-lists li .biao{
        width: 190px;
        height: 25px;
        text-align: left;
        color: #7CB1D2;
        font-size: 13px;
        margin-top: 14px;
        float: left;
    }
    .safe-lists li .anniu{
        width: 90px;
        height: 40px;
        margin-top:5px;
        overflow: hidden;
        float: left;
        border: 0px solid black;
    }
     .safe-lists li .number{
        float: left;
        color: #939393;
        font-style: italic;
        font-size: 12px;
        height: 35px;
        line-height: 55px;
    }

  
</style>
<div class="clear"></div> 
	<div class="m-moclist">
	    <div class="g-flow" id="j-find-main">
		<div class="b-30"></div>
		<div class="g-container f-cb">	 
                    <div class="g-sd1 nav">
                        <div class="m-sidebr" id="j-cates">
                            <ul class="u-categ f-cb">
                                <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="选课中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;color:#FFF" title="选课中心">选课中心</a>
                                </li>
                               <?php  
                                $sql="select id,title from setup order by id";
                                  $res=  api_sql_query_array($sql);
                                  foreach ($res as $value) {
                                      ?>
                                <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                                    <a class="f-thide f-f1"  title="<?=$value['title']?>" href="<?=URL_APPEND."portal/sp/select_study.php?id=".$value['id']?>"><?=$value['title']?></a>
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
                
                        <div class="m-university" id="j-university" style="border:1px solid #ddd;">
                            <div class="bar f-cb">
                                   <a class="left f-fc3 safe-assess" style="color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>;font-weight:bold" href="./pro_index.php">安全评估</a>
                            </div>
                        </div>
                        <!--下部-->
                        <?php   if(api_get_setting ( 'enable_modules', 'router_center' ) == 'true'){  ?>
                        <div class="m-sidebr" id="j-cates" style="margin-top:15px;">
                            <ul class="u-categ f-cb">
                                <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="路由交换" id="auto-id-D1Xl5FNIN6cSHqo0">
                                    <a class="f-thide f-f1"   title="路由交换" href="<?=URL_APPEND."topoDesign/labs.php"?>">路由交换</a>
                                    <div class="i-mc">
                                        <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                        <?php   
                                        $sql="select  `id`,`name` from  `labs_category` ";
                                        $lab_cate=  api_sql_query_array($sql);
                                        foreach ($lab_cate  as  $val){ 
                                           $url=URL_APPEND."topoDesign/labs.php?labs_category=".$val['id']; 
                                         ?>
                                            <a class="j-subit f-ib f-thide" href="<?=$url?>"><?=$val['name']?></a>
                                         <?php
                                           }
                                          ?> 
                                        </div>
                                    </div> 
                                </li>
                            </ul>
                        </div>

                             <?php } ?>
                        <div class="m-university" id="j-university">
                            <div>
                                <div class="bar f-cb" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?> ">
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
		   <div class="g-mn1" >
		        <div class="g-mn1c m-cnt" style="display:block;">
<!--                            <div class="top f-cb j-top">
                                <h3 class="left f-thide j-cateTitle title">
                                    <span class="f-fc6 f-fs1" id="j-catTitle">
                                         安全评估
                                    </span>
                                </h3>
                            </div>-->
                            <div class="j-list lists" id="j-list"> 
                                <!--<article class="lab-content study-list">-->
                                <div class="u-content">
                                    <ul id="list" class="safe-lists">
                                        <?php foreach ($arr as $val){  ?>   
                                        <li class="li"> 
                                            <div class="img"><img src="<?php echo $val['upfile'] ?>" width="55" height="55"></div>
                                            <h2 class="biao"><?php echo $val['name']?></h2>
                                            <div class="anniu"><a href="assess.php?pro_id=<?php echo $val['id'];?>">开始评估</a></div>
                                        </li> 
                                        <?php }?>
                                    </ul>
                                  <!--</article>-->
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