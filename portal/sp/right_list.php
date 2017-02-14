<?php
 $r_suffix='r';
     $right_bl=false;
    if($category_id && file_exists('/tmp/'.$category_id) && $category_sun === null){
          $right_list=file_get_contents('/tmp/'.$category_id);
          $right_bl=true;
    }else if($id && file_exists('/tmp/'.$id.$r_suffix) && $category_id === 0 && $category_sun === null){
          $right_list=file_get_contents('/tmp/'.$id.$r_suffix);
          $right_bl=true;
    }

if($right_bl === false){
     $subclass='';
    if($category_id){   //选中某个一级分类
        $subclass[]=$category_id;
    }else{  //某课程体系下的所有一级分类
    $sql1="select subclass from setup where id=$id";
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
       $right_list='<div class="g-mn1" >
                      <div class="g-mn1c m-cnt" style="display:block;">
                         <div class="j-list lists" id="j-list">
                           <div class="m-allwrap f-cb">
                                <div class="cnt f-cb" id="auto-id-k6s3rv2cJswp3exB">
                                       <input type="hidden" value="1" id="tol_input1">';
               foreach ( $category_tree as $category ) {
                   foreach ( $category_tree as $category2 ){
                         $category_tree_new[]=$category2;
                   }
               }
               $i = 0;   $j = 0;   $o = array();  $k=0; $ii=1;$jj=1; //标记循环变量， 数组 ;
               foreach ( $category_tree as $category ) {
                   if($category['parent_id']==0) {
                       $o[$j] = $category['id'];$j+=1;
                       foreach ( $category_tree as $key=>$category2 ) {//子类 循环  ；
                                    $url2 = "course_catalog.php?category_id=" . $category2 ['id'];
                                    $cate_name2 = $category2 ['name'] . (($category_cnt [$category2 ['id']]) ? "&nbsp;(" . $category_cnt [$category2 ['id']] . ")" : "");
                                    $sql="SELECT DISTINCT `username` FROM   `view_course_user`  WHERE `category_code` =".$category2['id'];
                                    $count_use_data=  api_sql_query_array_assoc($sql);
                                    $count_users=count($count_use_data);
                                    if($category_sun === true){
                                       $count_users++;
                                    }
                           if ($category2['parent_id'] == $o[$j-1]){
                               if(($k+12)%12==0){
                                   if($k==0){
       $right_list.='<div  class="to_ol_class"  id="to_ol_class'.$jj.'"  style="display:block;">';
                                   }else{
       $right_list.='<div  class="to_ol_class"  id="to_ol_class'.$jj.'"  style="display: none;">';
                                   }
                               }
                 $lesson_size=$category_cnt [$category2 ['id']] ? $category_cnt [$category2 ['id']]: "0";
       $right_list.='<div class="g-cell1 u-card j-href ie6-style" data-href="#">
                                    <div class="card">
                                         <div class="u-img f-pr">
                                              <a href="course_catalog.php?id='.$id.'&category_id='.$category2['id'].'">
                                                    <img class="img" src="../../storage/category_pic/'.$category2['code'].'" height="124" alt="'.$cate_name2.'" title="'.$cate_name2.'">
                                              </a>
                                         </div>
                                                  <div class="f-pa over f-fcf">
                                                    <span class="txt">'.$lesson_size.'个课程</span>
                                                 </div>
                                                 <div class="subject-study">
                                                   <span   style="font-size: 12px;"><b>学习:</b></span>
                                                   <span class="sub-img"></span>
                                                  <span class="study-num" style="color:#501E1E; font-size: 12px;"><b>'.$count_users.'人</b></span>
                                                <span class="study-good"></span>
                                              </div>
                                              <p class="t2 f-thide">
                                                    <a class="t21 f-fc6" href="#" target="_blank"><b>'.$category2 ['name'].'</b></a>
                                              </p>
                                              <div class="descd j-d">
                                                          <p class="dtit" ><b><a class="sun" style="color:#868D86" href="'.$url2.'" >'.$cate_name2.'</a></b></p>

                                                              <span class="dbtn"><a  href="'.$url2.'">查看课程</a></span>
                                              </div>
                                    </div>
                            </div>';
                               if(($ii)%12==0){
                                   $jj++;
       $right_list.='</div>';
                               }
                               $ii++;
                               $k++;
                           }
                       }
                   }
                           if($i==3){$i=0;}
               }
               if(!$category_tree){
       $right_list.="<p align='center'>没有相关课程分类，请联系课程管理员</p>";
               }
       $right_list.='</div>
               </div>';
                  if($k>12){
                      $allnum=ceil($k/12);
       $right_list.='<input type="hidden" value="'.$allnum.'" id="tol_input2">
                              <div class="ui-pager" id="auto_id123">
                                  <div class="auto-1404702552486">
                                     <a class="zbtn zprv" id="tool_left1">上一页</a>';

                           for($i=1;$i<=$allnum;$i++){
        $right_list.='<a onclick="yema('.$i.','.$allnum.')" class="zpgi zpg1" id="auto_id'.$i.'" >'.$i.'</a>';
                           }
       $right_list.='<a class="zbtn znxt" id="tool_right1">下一页</a>
                                  </div>
                               </div>';

                  }
       $right_list.='</div>
                 </div>
            </div>
       </div>';
    $id && $category_id ? file_put_contents('/tmp/'.$category_id,$right_list) : file_put_contents('/tmp/'.$id.$r_suffix,$right_list);
}