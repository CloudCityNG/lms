 <?php
$cidReset = true;
$_SERVER['HTTP_HOST'] = gethostbyname($_SERVER['SERVER_NAME']);
include_once ("../portal/sp/inc/app.inc.php");

include_once ("router.class.php");
if (isset ( $_GET ['action'] )) {
	switch ($_GET ['action']) {
		case 'download' :
                    if(!$user_id){
                        $user_id=$_SESSION['_user']['user_id'];
                    }
                    $topoId =intval( getgpc ( 'id', 'G') );
                    if($topoId && $user_id){
                       $url_routercourse=glob(URL_ROOT."/www".URL_APPEDND."/storage/routecourses/");
                        $url_router=$url_routercourse[0];
                        tar_labs_conf($topoId,$user_id);
                        if($fileName==''){
                            $fileName=$topoId.'_'.$user_id.'.lib';
                        }
                        $f=$url_router.$fileName;
			
//                        if(file_exists($f)){  
//                        header('Content-Transfer-Encoding: binary' );    
//			Header("Content-type: application/force-download"); 
//			header("Content-Type: application/zip"); 
//			header("Content-Disposition: attachment; filename=".basename($f) ); 
//			header("Content-Length: ".filesize($f));
//			ob_clean(); 
//			flush(); 
//			readfile( $f ); 
//                        @unlink($f);
//                        }
			header("Location: ".URL_APPEDND."/storage/routecourses/".$fileName);
                    }
                    tb_close("labs.php");
                    exit();
                    break;
	}
}




include_once ("../portal/sp/inc/page_header.php");

$labs_category=(int)trim(getgpc("labs_category","G"));


$sql='select * from labs_category where id='.$labs_category;
$category_res=  api_sql_query_array_assoc($sql);
$category_data=$category_res[0]; 
$sel=$_POST['auto-id-rTOGAi3MiQOM7HrB'];
$topoId=  getgpc("id","G");
$user_id=  api_get_user_id();  
 
if(isset($_GET['labs_category']) && $_GET['labs_category']!=='' && (int)$_GET['labs_category']){
    $sql_where.="  AND  `labs_category`=".$labs_category;
    $param.='labs_category='.$labs_category;
}

if (is_not_blank ( $sel )) {
	$keyword = Database::escape_str ( urldecode ($sel ), TRUE );   
	$sql_where.= " AND ( `name`  LIKE '%" . trim($keyword) . "%') OR ( `description`  LIKE '%" . trim($keyword) . "%') ";
	$param .= "&keyword=" . urlencode ( $keyword );
}

//还原实验配置
if(isset($_GET['action']) && $_GET['action']=='return' && $topoId!==''){
    if($topoId && $user_id){
       stoping_labs($user_id,$topoId);
       $url='/tmp/mnt/iostmp/';
       $urlhome='/tmp/mnt/iostmp/'.$user_id;
       $url_routercourse=glob(URL_ROOT."/www".URL_APPEDND."/storage/routecourses/".$topoId."-*.lib");
       $url_router=$url_routercourse[0];
       $url_user=$url.$user_id; 
       if($topoId){
	  $exec_var1="cd ".$url_user."/; sudo -u root rm  -rf ".$topoId;    
	  exec($exec_var1);     
       }
       
	if(!file_exists($urlhome)){
		exec(" mkdir -p ".$urlhome);
		
	}
	if(!empty($url_router)){
			$exec_var0="cd ".$urlhome."/;sudo -u root  tar -zxf ".$url_router." ".$topoid;
			exec($exec_var0);
		}
       
    }
    tb_close("labs.php");
}
 


if ($param {0} == "&") $param = substr ($param,1);

$sql1 = "SELECT COUNT(*) FROM `labs_labs`";
if ($sql_where){
	$sql1 .=' where 1'.$sql_where;
}
 
$total_rows = Database::get_scalar_value ( $sql1 );

$sql="select `id`,`name` ,`description`,`id` FROM `labs_labs`";  
if ($sql_where){    
    $sql.=' where 1'.$sql_where;
}
 
$offset = intval(getgpc('offset','G'));
$sql .= " order by `id`";
if(!$sel){
$sql .= " LIMIT " . (empty ( $offset ) ? 0 : $offset) . ",10";     
}
//echo $sql;
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$arr= array ();

while ( $arr = Database::fetch_row ( $res) ) {
	$arrs [] = $arr;
}
$rtn_data=array ("data_list" => $arrs, "total_rows" => $total_rows );
$personal_course_list = $rtn_data ["data_list"];
$total_rows = $rtn_data ["total_rows"];
$url ="labs.php?" . $param;
$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );

//all router  course
$sql = "SELECT `name` FROM `vslab`.`labs_category` WHERE parent_id=0 ORDER BY `tree_pos`";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$vm= array ();
$j = 0;
while ( $vm = Database::fetch_row ( $res) ) {
    $j++;
    $category_tree[$j] = $vm[0];
}


//Recently Study
$sql="SELECT `code`,`title`  FROM  `course` INNER JOIN `course_rel_user` ON `course_rel_user`.`course_code`=`course`.`code` where `user_id`=".$user_id." ORDER BY  `course_rel_user`.`last_access_time` DESC  limit  0,8";
//echo $sql;
$Recently_Study=  api_sql_query_array_assoc($sql,__FILE__,__LINE__);
$Recently_Study_count=intval(count($Recently_Study));
  
?>
      <?php      if(api_get_setting ( 'lm_switch' ) == 'true'){
                ?>
  <style>
.m-moclist .nav .u-categ .navitm.it a:hover{
	color:#357CD2;
	background:#fff;
} 
.m-moclist .nav .u-categ .navitm.it.course-mess:hover{
    border-right-color: #357CD2;
}
.m-moclist .nav .u-categ .navitm.it.cur:hover .f-f1:hover{
background:#357CD2;
color:#fff;
}
.m-moclist .nav .u-categ .navitm.it.cur:hover .i-mc a:hover{
    color:#357CD2;
}
  </style>
      <?php   }   ?> 
     <div class="clear"></div> 
	<div class="m-moclist">
	  <div class="g-flow" id="j-find-main">
               <div class="b-30"></div>
               <div class="g-container f-cb">	 
          <div class="g-sd1 nav">
                        <div class="m-sidebr" id="j-cates">
                            <ul class="u-categ f-cb">
                               <li class="navitm it f-f0 f-cb first cur"  data-id="-1" data-name="选课中心" id="auto-id-D1Xl5FNIN6cSHqo0">
                                   <a class="f-thide f-f1" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?>color:#FFF" title="选课中心">选课中心</a>
                               </li>
                               <?php  
                                $sql="select id,title from setup order by custom_number";
                                  $res=  api_sql_query_array($sql);
                                  foreach ($res as $value) {
                                      ?>
                                        <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="课程分类" id="auto-id-D1Xl5FNIN6cSHqo0">
                                            <?php if(api_get_setting ( 'lm_switch' ) == 'true'){ ?>
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
                                                                $url = URL_APPEND."portal/sp/select_study.php?id=".$value['id']."&category=" . $category ['id'];
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
                         <?php   $SurveyCenter=api_get_setting( 'enable_modules', 'survey_center'); 
                  if($SurveyCenter == 'true'){  ?>
                        <div class="m-university" id="j-university" style="border:1px solid #ddd;">
                            <div class="bar f-cb">
                                   <a class="left f-fc3 safe-assess" href="../portal/sp/pro_index.php">安全评估</a>
                            </div>
                            </div>
                            <?php   if(api_get_setting ( 'enable_modules', 'router_center' ) == 'true'){  ?>
                           <div class="m-sidebr" id="j-cates" style="margin-top:15px;">
                            <ul class="u-categ f-cb">
                                <li class="navitm it f-f0 f-cb haschildren"  data-id="-1" data-name="路由交换" id="auto-id-D1Xl5FNIN6cSHqo0">
                                     <?php if(api_get_setting ( 'lm_switch' ) == 'true'){ ?>
                                    <a class="f-thide f-f1" <?=$labs_category?'style="color:#357cd2;font-weight:bold"':''?> title="路由交换" href="<?=URL_APPEND."topoDesign/labs.php"?>">路由交换</a>
                                    <?php   }else{   ?> 
                                    <a class="f-thide f-f1" <?=$labs_category?'style="color:green;font-weight:bold"':''?> title="路由交换" href="<?=URL_APPEND."topoDesign/labs.php"?>">路由交换</a>
                                    <?php   }   ?> 
                                    <div class="i-mc">
                                        <div class="subitem" clstag="homepage|keycount|home2013|0601b">
                                        <?php   
                                        $sql="select  `id`,`name` from  `labs_category` ";
                                        $lab_cate=  api_sql_query_array($sql);
                                        foreach ($lab_cate  as  $val){ 
                                           $url=URL_APPEND."topoDesign/labs.php?labs_category=".$val['id']; 
                                         ?>
                                            <?php if(api_get_setting ( 'lm_switch' ) == 'true'){ ?>
                                    <a class="j-subit f-ib f-thide" <?=$labs_category==$val['id']?'style="color:#357cd2;font-weight:bold"':''?>  href="<?=$url?>"><?=$val['name']?></a>
                                    <?php   }else{   ?> 
                                    <a class="j-subit f-ib f-thide" <?=$labs_category==$val['id']?'style="color:green;font-weight:bold"':''?>  href="<?=$url?>"><?=$val['name']?></a>
                                        <?php   } }  ?>                                             
                                        </div>
                                    </div> 
                                </li>
                            </ul>
                        </div>
                             <?php } ?>
                  <?php  } ?>
                        <!--下部-->

                        <div class="m-university" id="j-university">
                            <div>
                                   <div class="bar f-cb" style="background-color:<?=(api_get_setting ( 'lm_switch' ) == 'true' ?'#357CD2;':'#13a654;')?> ">
                                          <h3 class="left f-fc3 rece-h3"  style='color:#FFF;'>最近学习</h3>
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
<!--				    <div class="top f-cb j-top">
					   <h3 class="left f-thide j-cateTitle title">
					      <span class="f-fc6 f-fs1" id="j-catTitle">
                                                  <?php
                                                if(isset($_GET['labs_category']) && $labs_category!='' && (int)$_GET['labs_category']){
                                                    if($category_data["name"]!==''){
                                                        echo "<a href='labs.php' style='color: #666;'>路由实训</a> > ".$category_data["name"];
                                                    }else{
                                                        echo "<a href='labs.php' style='color: #666;'>路由实训</a>"; 
                                                    } 
                                                }else{
                                                    echo "<a href='labs.php' style='color: #666;'>路由实训</a> > 全部路由课程";
                                                }
                                              ?></span>
					   </h3>
					   <div class="j-nav nav f-cb"> 
					   </div>
					</div>-->

                     <div class="j-list lists" id="j-list"> 
                   <div class="u-content">
                                <table cellspacing="0" border="0" width="100%" class="tbl_course"> 
                                    <tr>
                                        <th width="27%">名称</th>
                                        <th width="49%">描述</th>
                                        <th width="6%" align="center">导出</th>
                                        <th width="6%" align="center" title="还原实验配置">还原</th>
                                        <th width="6%" align="center">进入</th>
					<th width="6%" align="center">手册</th>
                                    </tr>
                                   <tr>
                                        <td colspan="10"><h3  class="sub-simple u-course-title"></h3> </td>
                                    </tr>
                         <?php
                                    if (is_array ( $personal_course_list ) && $personal_course_list) {
                                ?>
                                <?php
                                for($i=0;$i<count($personal_course_list);$i++){
                                    $labsId=$personal_course_list[$i][3];
                                    $sql_d="select `name` from `labs_labs` where id=".$labsId;
                                    $names= DATABASE::getval($sql_d,__FILE__,__LINE__);
                                    $USERID=$_SESSION['_user']['user_id'];
                                    $sql1="select count(*) from `labs_run_devices` where `labs_name`='".$labsId."' and `USERID`=".$USERID;
                                    $run_devices_count=DATABASE::getval($sql1,__FILE__,__LINE__);
                                    if(!$USERID){
                                        $join_img='restore.png';
                                        $return_img='return_lab_gray.png';
                                        $down_img='export_no.png';
                                    }else{
                                        if($run_devices_count){
                                            $join_img='restore_yes.png';
                                            $down_img='export_no.png';
                                        }else{
                                            $join_img='restore.png';
                                            $down_img='export_yes.png';
                                        } 
                                    }   
                                ?>
                                <tr>
                                    <td width="27%" class="labs-table-name">
                                        <img src="images/lab.png" width="25" height="25" style="vertical-align: middle;">
                                        <?=api_trunc_str2($personal_course_list[$i][1],25)?>
                                    </td>
                                    <td width="49%"><?=$personal_course_list[$i][2]?></td>
                                    <td width="6%"align="center">
                                        <?php
                                        if($run_devices_count){
                                             echo Display::return_icon ( $down_img, "导出实验配置（请关闭实验再试）", array ('style' => 'vertical-align: middle;width:24px;height:24px' ) );
                                        }else{
                                            if(!$USERID){
                                               echo Display::return_icon ( $down_img, "导出实验配置（用户没有登陆）", array ('style' => 'vertical-align: middle;width:24px;height:24px' ) );
                                            }else{
                                       echo '<a href="labs.php?action=download&id='.$labsId.'">' . Display::return_icon ( $down_img, get_lang ( 'Download' ), array ('width' => '24px', 'height' => '24px' ) ) . '</a>';
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td width="6%" align="center">
                                        <?php
                                        $url_routercourse1=glob(URL_ROOT."/www".URL_APPEDND."/storage/routecourses/".$personal_course_list[$i][3]."-*.lib");
                                        if(file_exists($url_routercourse1[0])){
                                            if(!$USERID){
                                                echo  Display::return_icon ("return_lab_gray.png", "无法还原配置", array ('style' => 'vertical-align: middle;width:24px;height:24px' ) );
                                            }else{
                                                if(!$run_devices_count){
                                                    echo  confirm_href("return_lab.png",'你确定要还原实验环境配置吗？','还原实验配置','labs.php?action=return&id='. $personal_course_list[$i][3]);
                                                }else{
                                                    echo  Display::return_icon ("return_lab_gray.png", "无法还原配置(请关闭实验重试)", array ('style' => 'vertical-align: middle;width:24px;height:24px' ) );
                                                }
                                            }
                                        }else{
                                            echo  Display::return_icon ("return_lab_gray.png", "无法还原配置", array ('style' => 'vertical-align: middle;width:24px;height:24px' ) );
                                        }
                                       
                                        ?>
                                    </td>
                                    <td width="6%" align="center">
                                        <a href="dynamic_map.php?action=show&id=<?=$labsId?>">
                                            <img alt="进入路由实验" title="进入路由实验" src="images/<?=$join_img?>" width="24" height="24">
                                        </a>
                                    </td>
				    <td width="6%" align="center">
					    <?php $topoqu=mysql_query('select id from labs_document where labs_id='.$labsId.' order by id desc limit 1');  
              $topoarr=mysql_fetch_row($topoqu);
              $src = api_get_path ( WEB_CODE_PATH ) . 'courseware/link_goto.php?manuid='.$topoarr[0];               
              $document_url=WEB_QH_PATH . 'document_viewer.php?manu=abc&url='.urlencode($src);
	      ?>
                                        <a href="<?=$document_url?>" target="_blank">
                                            <?php
                                            echo Display::return_icon ("lp_dokeos_module.png", "实验手册", array ('style' => 'vertical-align: middle;width:22px;height:22px' ) )
                                            ?>
                                        </a>
                                    </td>
                                </tr>
                              <tr>
                                        <td colspan="10"><div  class="sub-simple u-course-title"></div> </td>
                                </tr>
                                <?php
                                }
                                ?>
                                    <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <span class="l">
                                                总计：<strong><?=$total_rows?></strong> 条记录
                                            </span>
                                            <span class="num">
                                                <ul class="pages">
                                                 <?php
                                                     echo $pagination->create_links ();
                                                 ?>
                                                </ul>
                                            </span>  
                                        </td>
                                    </tr>
                               </tfoot>
                            <?php
                                } else {
                            ?>
                                <tr>
                                    <td colspan="5" class="error">没有相关实验</td>
                                </tr>
                            <?php
                                    }
                            ?>
                            </table>
		      </div>            
		    </div>
                 </div>
	      </div>
	   </div> 
        </div>
    </div>
	<!-- 底部 -->
<?php
        include_once('../portal/sp/inc/page_footer.php');
?>
 </body>
</html>
