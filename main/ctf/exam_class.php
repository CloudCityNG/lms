<?php
include_once ("../inc/global.inc.php");
$htmlHeadXtra [] =import_assets('commons.js');

$htmlHeadXtra [] = '<script type="text/javascript">
if(document.attachEvent)  window.attachEvent("onload",  iframeAutoFit);  
else  window.addEventListener("load",  iframeAutoFit,  false);
</script>';

$htmlHeadXtra [] = Display::display_thickbox ();
$htmlHeadXtra [] = '<script type="text/javascript">
	function refresh_tree(){ parent.CategoryTree.location.reload();/*parent.CategoryTree.d.openAll();*/ }
	</script>';
Display::display_header ();
/*
 ==============================================================================
 课程分类管理
 ==============================================================================
 */

    $language_file = 'admin';
    $cidReset = true;
    $this_section = SECTION_PLATFORM_ADMIN;

    require_once (api_get_path ( LIBRARY_PATH ) . 'course.lib.php');

    $org_id = (isset ( $_REQUEST ["org_id"] ) ? intval(getgpc ( 'org_id' )) : "-1");
    $category = (isset ( $_REQUEST ["category"] ) ? intval(getgpc ( 'category' )) : "0");
    $action = getgpc ( 'action' );

    $tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
    $tbl_category = Database::get_main_table ( TABLE_MAIN_CATEGORY );

    $sql = "SELECT parent_id FROM $tbl_category WHERE id=" . intval(Database::escape (getgpc("id","G") ));
    $parent_id = Database::get_scalar_value ( $sql );
    function deleteNode($id){
        $query=mysql_query('select id from tbl_class where fid='.$id);
            while($row=mysql_fetch_assoc($query)){
                  $frows[]=$row;
            }
            if(count($frows)){
                 foreach($frows as $fk=>$fv){
                     deleteNode($fv['id']);
                 } 
            }
            mysql_query("delete from tbl_class where id=".$id);
    }
    if (! empty ( $action )) {
        if ($action == 'delete') {
            $rtn=deleteNode (intval(getgpc('id')) );
             tb_close("exam_class.php");
            //api_redirect ( $_SERVER ['PHP_SELF'] . '?category=' . $parent_id . "&result=success" );
        }elseif ($action == 'moveUp') {
            moveNodeUp (intval(getgpc('id')), getgpc('tree_pos'), $category );
            api_redirect ( $_SERVER ['PHP_SELF'] . '?category=' . $parent_id . "&result=success" );
        }
    }


    if (empty ( $action )) {
        $sql = "select *from tbl_class where fid=0 order by id asc";
        $result = api_sql_query ( $sql, __FILE__, __LINE__ );
        while($row=mysql_fetch_assoc($result)){
            $Categories[]=$row;
        }
    }

    if (! empty ( $category ) && empty ( $action )) {
        $result = api_sql_query ( "SELECT parent_id,name FROM $tbl_category WHERE id='$category'", __FILE__, __LINE__ );
        list ( $parent_id, $categoryName ) = mysql_fetch_row ( $result );
    }

    $objCrsMng = new CourseManager ();
    $category_tree = $objCrsMng->get_all_categories_tree ( TRUE );

    function _get_course_count($parent_id) {
        $GLOBALS ['objCrsMng']->sub_category_ids = array ();
        $sub_category_ids = $GLOBALS ['objCrsMng']->get_sub_category_tree_ids ( $parent_id, TRUE );
        $tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
        $sql = "select count(*) from tbl_class where fid=".$parent_id;
        //echo $sql;
        return Database::get_scalar_value ( $sql );
    }

   function sonfun($id,$left=20){
       $query=mysql_query('select * from tbl_class where fid='.$id.' order by id asc');
       while($row=mysql_fetch_assoc($query)){
           $rows[]=$row;
       }
       $content='';
       if(count($rows)){
           $left=$left+10;
           foreach($rows as $rok=>$rov){
                $id=intval($rov['id']);
                $count =_get_course_count($id);
                $onclick='onclick="gradeli('.$id.');"';
                if($count){
                     $cour='style="cursor: pointer;"';
                }else{
                    $cour='';
                }
                $str=link_button ( 'edit.gif', '修改分类名称', 'exam_add_edit.php?action=edit&category=' . urlencode ($id), '20%', '25%', FALSE );
                $str.='&nbsp;&nbsp;';
                $str.=confirm_href ( 'delete.gif', '确定删除本分类及其子分类?', 'DeleteNode', $_SERVER ['PHP_SELF'] . "?category=" . urlencode ($id) . "&action=delete&id=" . urlencode ($id));
                $content.='<tr class="bline-m hide deli'.$rov['fid'].'" '.$cour.' id="tr'.$id.'">
                            <td colspan="4">
                                <table cellpadding="0" style="margin-left:'.$left.'px;" cellspacing="0" class="course-list-list">
                                    <tr>
                                        <td class="course-winss" '.$onclick.'>'.$rov['className'].'</td>
                                        <td class="course-win3" style="width:20%;" '.$onclick.'>分类总数:<span id="count'.$id.'">'.$count.'</span></td>
          <td style="width:22px !important;padding:0 !important;"><span style="float:right;"><img src="../../themes/img/create.gif" id="td'.$id.'" onclick="thisck('.$id.')" title="添加子分类" style="margin:4px 3px 0 0;"/><span id="span'.$id.'" style="display:none;">&nbsp;<input type="text" id="inpu'.$id.'" />&nbsp;<input type="button" value="添加" onclick="oneclick('.$id.');" /></span></span></td>                                  
                                        <td class="course-win4">
                                       &nbsp;/&nbsp;
                                       '.$str.'
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>';
                if($count){
                  $content.=sonfun($id,$left);  
                }
           }
       }
       return $content;
   }

    $table_header [] = array (get_lang ( 'CategoryName' ) );
    $table_header [] = array (get_lang ( 'CategoryCode' ) );
    $table_header [] = array (get_lang ( 'CourseCount' ), false, null, array ('style' => 'width:80px' ) );
    $table_header [] = array (get_lang ( 'Actions' ), false, null, array ('style' => 'width:80px' ) );
$Category_id = $Categories;
?>
<style type="text/css">
    .course-winss{
        width: 40%;
        padding: 0 10px 0 10px;
    }
</style>
<aside id="sidebar" class="column ctfinex open">
    <div id="flexButton" class="closeButton close">

    </div>
</aside>
<section id="main" class="column">
    <h4 class="page-mark">当前位置：<a href="<?=URL_APPEDND;?>/main/admin/index.php">平台首页</a> &gt; 
        ctf管理 &gt; CTF题库分类管理</h4>
    <article class="module width_full hidden ip">
        <table cellpadding="0" cellspacing="0" id="course-table">
            <tbody id="tob">
            <tr>
                <td class="course-dtd">
                    <div class="course-title"><strong>CTF题库分类管理</strong></div>
                    <div class="course-add">添加一级分类:&nbsp;<input type="text" id="inpu0" value="" />&nbsp;<input type="button" onclick="oneclick(0);" value="添加"/></div>
                </td>
            </tr>
            
            <tr><?php 
                if($Categories==null){
                    echo "<td class='course-info' style='text-align:center'> 没有相关的课程分类 </td>";
                }else{
                    echo '<td class="course-info"  style="color: #ff0000">'.'提示：鼠标点击父菜单，即可查看子菜单栏目。'.'</td>';
                }
            ?></tr>
            <?php
             foreach($Categories as $name){?>
                <?php if($name['fid']==0) {
                    $id = intval($name['id']);
                    ?>
            <tr id="tr<?=$id?>" >
                <td>
                    <table cellpadding="0" cellspacing="0" class="course-list">

                        <tr class="bline" title="查看子分类">

                            <td class="course-winss" onclick="gradeli(<?=$id?>);"><?php echo ($name['className']);?></td>
                            <td class="course-win3" style="width:20%;">分类总数:<span id="count<?=$id?>"><?php $count = _get_course_count($id); echo $count;?></span></td>
<?php 
                               echo '<td style="width:22px !important;padding:0 !important;"><span style="float:right;"><img src="../../themes/img/create.gif" id="td'.$id.'" onclick="thisck('.$id.')" title="添加子分类" style="margin:4px 3px 0 0;"/><span id="span'.$id.'" style="display:none;">&nbsp;<input type="text" id="inpu'.$id.'" />&nbsp;<input type="button" value="添加" onclick="oneclick('.$id.');" /></span></span></td>';
?>
                            <td class="course-win4">
                                <?php
                                 echo "&nbsp;/&nbsp;";
                                // echo '<img src="http://192.168.1.8/lms3/themes/img/edit.gif" alt="修改分类" title="修改分类" style="vertical-align: middle;"><input type="text"  />';
                                 echo link_button ( 'edit.gif', '修改分类名称', 'exam_add_edit.php?action=edit&category=' . urlencode ( intval($name['id']) ), '20%', '25%', FALSE );
                                 echo '&nbsp;/&nbsp;'.confirm_href ( 'delete.gif', '确定删除本分类及其子分类?', 'DeleteNode', $_SERVER ['PHP_SELF'] . "?category=" . urlencode ( intval($name['id']) ) . "&action=delete&id=" . urlencode ( intval($name['id']) ) );
                                
                            ?></td>
                        </tr>
                      <?php
                     echo sonfun($id);
                      ?>
                    </table>
                </td>
            </tr>
                      

                <?php }?>
            <?php }?>
           
            </tbody>
        </table>
    </article> 
</section>
<script type="text/javascript">
    //添加子分类
     function oneclick(id){
         var name=$("#inpu"+id).val();
        name=name.replace(/\ /g,'');
         if(!name){
             alert("分类名称不能为空");
         }else{
             $.ajax({
                 url:'addclass.php',
                type:'POST',
                data:'class='+id+'&name='+name,
            dataType:'html',
            success:function(er){
          if(er!=='err'){
               var erarr=er.split(',');
               var arrint=parseInt(erarr[1]);
               var constr=''; 
             if(arrint===0){
                constr='<tr id="tr'+erarr[0]+'">'+
                '<td>'+
                    '<table cellpadding="0" cellspacing="0" class="course-list">'+
                        '<tr class="bline" title="查看子分类">'+
                            '<td class="course-winss" onclick="gradeli('+erarr[0]+');">'+erarr[2]+'</td>'+
                            '<td class="course-win3" style="width:20%;">分类总数:<span id="count'+erarr[0]+'">0</san></td>'+
                               '<td style="width:22px !important;padding:0 !important;"><span style="float:right;"><img src="../../themes/img/create.gif" id="td'+erarr[0]+'" onclick="thisck('+erarr[0]+')" title="添加子分类" style="margin:4px 3px 0 0;"/><span id="span'+erarr[0]+'" style="display:none;">&nbsp;<input type="text" id="inpu'+erarr[0]+'" />&nbsp;<input type="button" value="添加" onclick="oneclick('+erarr[0]+');" /></span></span></td>'+
                            '<td class="course-win4">&nbsp;/&nbsp;'+
                           '<a class="thickbox" href="exam_add_edit.php?action=edit&amp;KeepThis=true&amp;TB_iframe=true&amp;modal=true&amp;width=25%&amp;height=20%" title="修改分类名称"><img src="<?=URL_APPEDND;?>/themes/img/edit.gif" alt="修改分类名称" title="修改分类名称" style="vertical-align: middle;"></a>&nbsp;/&nbsp;'+           
                           '<a href="exam_add_edit.php?action=delete&id='+erarr[0]+' title="删除该分类" target="_self" onclick="javascript:if(!confirm(\"您确定要执行该操作吗？\")) return false;"><img src="<?=URL_APPEDND;?>/themes/img/delete.gif" alt="删除该分类" title="删除该分类" style="vertical-align: middle;width:24px;height:24px"></a>'+ 
                           '</td>'+
                        '</tr>'+
                    '</table>'+
                '</td>'+
            '</tr>';
           $("#tob").append(constr);
              }else{
                  $.ajax({
                 url:'lookclass.php',
                type:'POST',
                data:'class='+erarr[1],
            dataType:'html',
            success:function(ser){
                var left=$('#tr'+erarr[1]+'  > td > table').css('marginLeft');
               var marleft=30;
               if(left!=='0px'){
                   left=left.replace('px','');
                   marleft=parseInt(left)+parseInt(10);
               }
              
                var counum=$('#count'+erarr[1]).html();
                     $('#count'+erarr[1]).html(parseInt(counum)+1);
                constr='<tr class="bline-m hide deli'+erarr[1]+'" id="tr'+erarr[0]+'" style="display:table-row;">'+
                '<td colspan="4">'+
                    '<table cellpadding="0" style="margin-left:'+marleft+'px;" cellspacing="0" class="course-list">'+
                        '<tr>'+
                            '<td class="course-winss" onclick="gradeli('+erarr[0]+');">'+erarr[2]+'</td>'+
                            '<td class="course-win3" style="width:20%;">分类总数:<span id="count'+erarr[0]+'">0</san></td>'+
                               '<td style="width:22px !important;padding:0 !important;"><span style="float:right;"><img src="../../themes/img/create.gif" id="td'+erarr[0]+'" onclick="thisck('+erarr[0]+')" title="添加子分类" style="margin:4px 3px 0 0;"/><span id="span'+erarr[0]+'" style="display:none;">&nbsp;<input type="text" id="inpu'+erarr[0]+'" />&nbsp;<input type="button" value="添加" onclick="oneclick('+erarr[0]+');" /></span></span></td>'+
                            '<td class="course-win4">&nbsp;/&nbsp;'+
                           '<a class="thickbox" href="exam_add_edit.php?action=edit&amp;KeepThis=true&amp;TB_iframe=true&amp;modal=true&amp;width=25%&amp;height=20%" title="修改分类名称"><img src="<?=URL_APPEDND;?>/themes/img/edit.gif" alt="修改分类名称" title="修改分类名称" style="vertical-align: middle;"></a>&nbsp;/&nbsp;'+           
                           '<a href="exam_add_edit.php?action=delete&id='+erarr[0]+' title="删除该分类" target="_self" onclick="javascript:if(!confirm(\"您确定要执行该操作吗？\")) return false;"><img src="<?=URL_APPEDND;?>/themes/img/delete.gif" alt="删除该分类" title="删除该分类" style="vertical-align: middle;width:24px;height:24px"></a>'+ 
                           '</td>'+
                        '</tr>'+
                    '</table>'+
                '</td>'+
            '</tr>';
               if(!$('.deli'+erarr[1]).is(':visible')){
                   $('.deli'+erarr[1]).show();
               }
               $("#tr"+ser).after(constr);
            }
             }); 
               }
         }   
          $('#inpu'+id).val('');
            $('#span'+id).css('display','none');
            $('#td'+id).show();
            }
             });
         }
     }
     function thisck(id){
        $("#td"+id).css('display','none');
        $("#span"+id).show(1000);
     }

     function idshow(obj){
         for(var i=0;i<obj.length;i++){
             var tid=obj[i].id.replace('tr','');
             if($(".deli"+tid).is(":visible")){
                 $(".deli"+tid).hide();
                 idshow($(".deli"+tid));
             }
         }
          obj.hide();
     }
     function gradeli(id){
         $str=$(".deli"+id);
         if($str.is(":visible"))
         {
             idshow($str);
         }else{
          $str.show();
         }
     }
</script>
</body>
</html>
