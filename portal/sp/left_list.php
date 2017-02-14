<?php
$l_suffix='l';
$l_sun='s';
$left_bl=false;
if($category_id && file_exists('/tmp/'.$category_id.$l_sun) && $category_sun === null){
          $left_list=file_get_contents('/tmp/'.$category_id.$l_sun);
          $left_bl=true;
    }else if($id && file_exists('/tmp/'.$id.$r_suffix) && $category_id === 0 && $category_sun === null){
          $left_list=file_get_contents('/tmp/'.$id.$r_suffix);
          $left_bl=true;
    }

if($left_bl === false){
    $tbl_course  = Database::get_main_table(TABLE_MAIN_COURSE);
    $tbl_course_openscore = Database::get_main_table ( TABLE_MAIN_COURSE_OPENSCOPE );

    if (api_is_platform_admin () OR api_get_setting('course_center_open_scope')==1) {
        $sql = "SELECT category_code,count(*) FROM $tbl_course  GROUP BY category_code";
    } else {
        $sql = "SELECT category_code,count(*) FROM $tbl_course WHERE code IN (SELECT course_code FROM " . $tbl_course_openscore . " WHERE user_id='" . api_get_user_id () . "')  GROUP BY category_code";
    }
    $category_cnt = Database::get_into_array2 ( $sql, __FILE__, __LINE__ );


        $left_list='<div class="m-sidebr" id="j-cates">
                   <ul class="u-categ f-cb">
                                   <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="选课中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                                       <a class="f-thide f-f1" style="background-color:'.(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;').'color:#FFF" title="选课中心">选课中心</a>
                                   </li>';

           $sql="select id,title from setup order by custom_number";
           $res=  api_sql_query_array($sql);
     foreach ($res as $value) {
        $style_thide=$value['id']==$id?'style="color:'.(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;').';font-weight:bold"':'';
        $left_list.=' <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                            <a class="f-thide f-f1" '.$style_thide.' title="'.$value['title'].'" href="'.URL_APPEND.'portal/sp/select_study.php?id='.$value['id'].'">'.$value['title'].'</a>
                            <div class="i-mc">
                                 <div class="subitem" clstag="homepage|keycount|home2013|0601b">';

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
                            if(is_array($category_tree1)){
                                      foreach ( $category_tree1 as $category ) { ///父类循环
                                          $url = "select_study.php?id=".$value['id']."&category=" . $category ['id'];
                                          $cate_name = $category ['name'];//. (($category_cnt [$category ['id']]) ? "&nbsp;(" . $category_cnt [$category ['id']] . ")" : "");
                                          if($category['parent_id']==0) {
                                            $a_style= $category_id == $category['id'] ? 'style="color:'.(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;').';font-weight:bold"' : '';
        $left_list.='<a class="j-subit f-ib f-thide" href="'.$url.'" '.$a_style.'>'.$cate_name.'</a>';
                                          }
                                      }

                            }else{
        $left_list.="<p align='center'>没有相关课程分类，请联系课程管理员</p>";
                            }
        $left_list.='            </div>
                            </div>
                      </li>';
     }

        $left_list.=' </ul>
                </div>';
                      $SurveyCenter=api_get_setting( 'enable_modules', 'survey_center');
		     if(api_get_setting ( 'lnyd_switch' ) == 'false'){
                      if($SurveyCenter == 'true'){
        			$left_list.='<div class="m-university" id="j-university" style="border:1px solid #ddd;">
                                <div class="bar f-cb">
                                       <a class="left f-fc3 safe-assess" href="./pro_index.php">安全评估</a>
                                </div>
                                </div>';
                      }
			}
                      if(api_get_setting ( 'enable_modules', 'router_center' ) == 'true'){
        $left_list.='<div class="m-sidebr" id="j-cates" style="margin-top:15px;">
                                    <ul class="u-categ f-cb">
                                      <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="路由交换" id="auto-id-D1Xl5FNIN6cSHqo0">
                                          <a class="f-thide f-f1"   title="路由交换" href="'.URL_APPEND.'topoDesign/labs.php">路由交换</a>
                                            <div class="i-mc">
                                              <div class="subitem" clstag="homepage|keycount|home2013|0601b">';
                                                  $sql="select  `id`,`name` from  `labs_category` ";
                                                  $lab_cate=  api_sql_query_array($sql);
                                                     foreach ($lab_cate  as  $val){
                                                       $url=URL_APPEND."topoDesign/labs.php?labs_category=".$val['id'];
        $left_list.='<a class="j-subit f-ib f-thide" href="'.$url.'">'.$val['name'].'</a>';
                                                     }
        $left_list.='           </div>
                                            </div>
                                      </li>
                                    </ul>
                                   </div>';
                      }
    if($category_id){
        $l_suffix='s';
        $cate_name=$category_id;
    }else{
        $cate_name=$id;
    }

    file_put_contents('/tmp/'.$cate_name.$l_suffix,$left_list);
}
 
            if(api_get_setting ( 'lm_switch' ) == 'true'){
                ?>
  <style>
  .m-moclist .nav .u-categ .navitm.it a:hover{
	color:#357cd2;
	background:#fff;
} 
.m-moclist .nav .m-university .us .Recently_Study a:hover{
     color:#357cd2;
}
  </style>
      <?php   }   ?>