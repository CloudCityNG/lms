<?php
$cidReset = true;
include_once ("../portal/sp/inc/app.inc.php");
include_once ("../portal/sp/inc/page_header.php");

$htmlHeadXtra [] = "<script type=\"text/javascript\">
function confirmation (name){
if (confirm(\"" . get_lang ( "AreYouSureToDelete" ) . " \" + name + \" ?\"))	{return true;}
else{return false;}
}</script>";
$htmlHeadXtra [] = Display::display_thickbox ();
Display::display_header ( $tool_name, FALSE );

//文件大小转换格式 chang
function sizecount($filesize) {
    if($filesize >= 1073741824) {
        $filesize = round($filesize / 1073741824 * 100) / 100 . ' gb';
    } elseif($filesize >= 1048576) {
        $filesize = round($filesize / 1048576 * 100) / 100 . ' mb';
    } elseif($filesize >= 1024) {
        $filesize = round($filesize / 1024 * 100) / 100 . ' kb';
    } else {
        $filesize = $filesize . ' bytes';
    }
    return $filesize;
}
$key = getgpc('keyword','G');
$labs_category = getgpc('labs_category','G');
$id = getgpc('id','G');
$offset = getgpc('offset','G');
$d_id = getgpc('d_id','G');
if (isset ( $_GET ["keyword"] ) && is_not_blank ( $_GET ["keyword"] )) {
    $keyword = Database::escape_str ( urldecode ( $key ), TRUE );
    $sql_where .= " AND (id LIKE '%" . $keyword . "%')";
    $param .= "&keyword=" . urlencode ( $keyword );
}
if(isset($_GET['labs_category']) && $_GET['labs_category']!==''){
    $categoryId_sql="select `id` from `labs_category` where `name`='".$labs_category."'";
    $labs_category=DATABASE::getval($categoryId_sql,__FILE__,__LINE__);
    $sql_where.=" `labs_category`=".$labs_category;
}
if ($param {0} == "&") $param = substr ( $param, 1 );

$sql1 = "SELECT COUNT(*) FROM `labs_document` where `labs_id`=".trim($id);
if ($sql_where){
    $sql1 .=' and '.$sql_where;
}
$total_rows = Database::get_scalar_value ( $sql1 );

$sql = "select `id`, `document_name`, `document_size`, `labs_id`,`id`,`id` FROM `labs_document`  WHERE `labs_id`=".$id;
if ($sql_where) $sql .= " and " . $sql_where;
$sql .= " order by `id`";
$sql .= " LIMIT " . (empty ( $offset ) ? 0 : $offset) . ",10";

$res = api_sql_query ( $sql, __FILE__, __LINE__ );
$arr= array ();
while ( $arr = Database::fetch_row ( $res) ) {
    $arrs [] = $arr;
}
$rtn_data=array ("data_list" => $arrs, "total_rows" => $total_rows );
$personal_course_list = $rtn_data ["data_list"];
$total_rows = $rtn_data ["total_rows"];
$url ="labs_document.php?" . $param;
$pagination_config = Pagination::get_defult_config ( $total_rows, $url, NUMBER_PAGE );
$pagination = new Pagination ( $pagination_config );

$sql = "SELECT `name` FROM `vslab`.`labs_category` WHERE parent_id=0 ORDER BY `tree_pos`";
$res = api_sql_query ( $sql, __FILE__, __LINE__ );

$vm= array ();
$j = 0;
while ( $vm = Database::fetch_row ( $res) ) {
    $j++;
    $category_tree[$j] = $vm[0];
}
 
$name_sql="select `name` from `labs_labs` where `id`=".$id;
$name=Database::getval($name_sql,__FILE__,__LINE__);
 
if(isset($_GET['action']) && $_GET['action']=='download'){

    $d_sql='select `document_name` from `labs_document` where `id`='.trim($d_id);
    $document_name=Database::getval($d_sql,__FILE__,__LINE__);

    $path='../storage/routerdoc';echo  $path."/".$document_name;
    downloads($document_name); 
}

?> 

  <div class="clear"></div> 
	<div class="m-moclist">
	    <div class="g-flow" id="j-find-main">
		     <div class="b-30"></div>
		<div class="g-container f-cb">	 
                <div class="g-sd1 nav">
                    <div class="m-sidebr" id="j-cates">
                        <ul class="u-categ f-cb">
                            <li class="navitm it f-f0 f-cb<?=$seelectd?>" style="" data-id="-1" data-name="路由课程" id="auto-id-D1Xl5FNIN6cSHqo0">
                                <a class="f-thide f-f1"  style="background-color:#13a654;color:#FFF"  title="路由课程">路由课程</a>
                            </li>
                            <?php
                            foreach ( $category_tree as $k1 => $v1){
                                $idx=DATABASE::getval("select `id` from `labs_category` where  `name`='".$category_tree[$k1]."'");
                                ?>
                            <li class="navitm it f-f0 f-cb<?=$seelectd?>">
                                <a href="labs.php?labs_category=<?=$idx?>" title="<?=$category_tree[$k1]?>"><?=$category_tree[$k1]?></a>
                            </li>
                            <?php
                                }
                            ?>
                            </ul>
                        </div>
                    </div>

           <!--  右侧 -->
		   <div class="g-mn1" >
		        <div class="g-mn1c m-cnt" style="display:block;">
                            <div class="top f-cb j-top">
                                <h3 class="left f-thide title">
                                    <span class="f-fc6 f-fs1" id="j-catTitle">
                                       <a href="" title="实验手册">实验手册</a> &gt;
                                        <a href="<?=URL_APPEDND ?>/topoDesign/dynamic_maps.php?action=show&id=<?=$id?>" title="<?=$name?>"><?=$name?></a> 
                                        
                                    </span>
                                </h3> 
                            </div>

                            <div class="j-list lists" id="j-list"> 
                                <div class="u-content"> 
                                    <div class="u-content-bottom">
                                        <div class="sub-simple u-course-title"><b>
                                            <ul class="u-course-time"> 
                                                <li style="width:20%;line-height: 50px;font-size:18px;text-indent: 0.5em;text-align:center">序号</li>
                                                <li style="width:30%;line-height: 50px;font-size:18px;text-indent: 0.5em;text-align:center">名称</li>
                                                <li style="width:30%;line-height: 50px;font-size:18px;text-indent: 0.5em;text-align:center">大小</li>
                                            </ul></b>
                                        </div>
               <?php 
           
        if (is_array ( $personal_course_list ) && $personal_course_list) {
            $j=1;
            foreach($personal_course_list as $perk=>$perv){
                $src = api_get_path ( WEB_CODE_PATH ) . 'courseware/link_goto.php?manuid='.$perv[4];               
                $url=WEB_QH_PATH . 'document_viewer.php?manu=abc&url='.urlencode($src);
                ?>
                    <ul class="u-course-time"> 
                        <li style="width:20%;text-align:center;line-height:30px;font-size:18px"><?=$j?></li>
                        <li style="width:30%;text-align:center;line-height:30px;font-size:18px">
                            <a href="<?=$url?>" title="<?=$perv[1]?>" target="_blank"><?=$perv[1]?></a>
                        </li>
                        <li style="width:30%;text-align:center;line-height:30px;font-size:18px">
                            <?=sizecount($perv[2])?>
                        </li>
                    </ul>
       <?php
       $j++;
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
        //include_once '../portal/sp/inc/page_footer.php';
?>
 </body>
</html>