<?php
include_once ('../../../main/announcements/announcements.inc.php');
include_once ('../../../main/inc/global.inc.php');

if (! defined ( 'IN_QH' )) exit ( 'Access Denied !' );
$tbl_course = Database::get_main_table ( TABLE_MAIN_COURSE );
$cidReq=$_GET['cidReq'];
$sql = "SELECT description, description1, description2,description3,description4,description5,description6,description7,description8,description9,description10,description11,description12,description13 FROM " . $tbl_course . " WHERE code=" . Database::escape ( api_get_course_code () );
list ($description, $description1, $description2,$description3,$description4,$description5,$description6,$description7,$description8,$description9,$description10,$description11,$description12,$description13 ) = api_sql_query_one_row ( $sql, __FILE__, __LINE__ );
$url_go="course_home.php?cidReq=".$cidReq."&action=introduction";
$go_id=  Database::getval("SELECT `category_code` FROM `course` WHERE `code`=".$cidReq, __FILE__, __LINE__);

$sql_pic="select  `description9`  from  `course`  where  `code`='". getgpc("cidReq")."'";
$pic=DATABASE::getval($sql_pic);
if($pic){
    $course_pic='../../storage/courses/'.  getgpc("cidReq").'/'.  $pic; 
    $file_exists=file_exists($course_pic);
}
if($pic  &&   $file_exists){
  $imgpath=$course_pic; 
}else if( file_exists('../../storage/category_pic/'.$img) ){  
  $imgpath='../../storage/category_pic/'.$img;  
}else{
   $imgpath= "../../portal/sp/images/default.png";
}

//查看该用户提交的报告 
    $sql_course="select `title` from `course` where `code` = '$cidReq' ";
    $c_title=  Database::getval($sql_course,__FILE__, __LINE__ );
    $sql="select `id` from `report` where `user`='".api_get_user_name ()."' and  `code`='".$c_title."'";
    $report_id=Database::getval($sql,__FILE__,__LINE__);
    //报告提交状态
    $sql="select `status` from `report` where `user`='".api_get_user_name ()."' and  `code`='".$c_title."'";
    $report_status=Database::getval($sql,__FILE__,__LINE__);
?>

    <div class="m-cbg"></div>
    <div class="m-learnhead">
        <div class="f-cb">
            <span class="schoolImg left"><a href="<?=URL_APPEDND;?>/portal/sp/course_catalog.php?category_id=<?=$go_id?>">学习中心</a></span>
            <div class="info1 left">
                <a hidefocus="true" target="" href="<?= $url_go?>">
                    <h4 class="f-fc3 courseTxt">课程名称：<?= $class?></h4>
                 </a>
                 <h5 class="f-fc6">作者:<?= $create_user?></h5>
            </div> 
            <span class="left" style="border:1px #ccc solid;display: block;float:right;margin-right: 50px;">
                <a style=" background: #e8e8e8;width:70px;height:25px;font-size:14px;line-height:25px;border-right: 1px solid #ddd;text-align: center;background-image: linear-gradient(white, #e8e8e8);" href="<?=$_SERVER['HTTP_REFERER']?>">返回</a>
            </span>
        </div>
    </div>
    <div class="g-wrap f-cb"></div>
    <div class="g-container f-cb">     
    <div class="g-sd1">
        <div class="m-learnleft f-cb">
            <div class="top f-cb">
                <a href="course_catalog.php?category_id=<?= $go_id?>" title="返回课程分类"><img src="<?=$imgpath?>" width="230" height="130" alt="C语言程序设计"></a>
            </div>
            <ul id="j-courseTabList" class="tab u-tabul">
                <li class="u-greentab j-tabitem f-f0 first u-curtab" data-name="课程信息" style="border-top:1px solid #ccc;">
                        <a class="f-thide f-fc3">课程信息</a>
                </li>
                <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                        <a class="f-thide f-fc3">实验指导书</a>
                </li>
                <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                        <a class="f-thide f-fc3">实验场景</a>
                </li>
                <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                        <a class="f-thide f-fc3">课程教材</a>
                </li>
                <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                        <a class="f-thide f-fc3">实验图片与录屏</a>
                </li>
                <?php if($report_id!=NULL){ ?>
                    <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                         <a class="f-thide f-fc3">查看实验报告</a>
                    </li>
               <?php }else{ ?>
                      <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                          <a class="f-thide f-fc3">添加实验报告</a>
                      </li>

               <?php }?>
                <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                        <a class="f-thide f-fc3">课程作业</a>
                </li>
                <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                        <a class="f-thide f-fc3">课程公告</a>
                </li>
                <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                        <a class="f-thide f-fc3">课程评论</a>
                </li>
                <li class="u-greentab j-tabitem f-f0" data-name="课程信息">
                        <a class="f-thide f-fc3">课程拓扑</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="g-mn1">         

        <div class="g-mn1c m-learnbox" id="courseLearn-inner-box">
            <div>
                <div class="m-learnChapterList p-fr">    

                    <div class="lab-cont"> 
                        <div class=" tab1">
                            <div class="u-learn-moduletitle f-cb">
                                <h2 class="left j-moduleName"> 课程信息</h2>
                            </div>
                            <?php $description=str_replace("\n","",$description); ?>
                            <?php $description1=str_replace("\n","",$description1); ?>
                            <?php $description2=str_replace("\n","",$description2); ?>
                            <?php $description3=str_replace("\n","",$description3); ?>
                            <?php $description4=str_replace("\n","",$description4); ?>
                            <?php $description5=str_replace("\n","",$description5); ?>
                            <?php $description6=str_replace("\n","",$description6); ?>
        
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验等级</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?php if($description==0){echo '初级';} if($description==1){echo '中级';} if($description==2){echo '高级';} ?></h4>
                                    </div>
                                </div>
                            </div>
        
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                    <h3 class="j-titleName name left f-thide">实验目的</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                       <h4 class="j-name name f-thide"><?=$description1?></h4>
                                    </div>
                                </div>
                            </div>
        
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                    <h3 class="j-titleName name left f-thide">预备知识</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                   <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name u-line30"><?=$description2?></h4>
                                    </div>
                                </div>
                            </div>
        
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                    <h3 class="j-titleName name left f-thide">实验内容</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                   <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?=$description4?></h4>
                                    </div>
                                </div>
                            </div>
        
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                    <h3 class="j-titleName name left f-thide">实验原理</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                   <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?=$description5?></h4>
                                    </div>
                                </div>
                            </div>
        
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                    <h3 class="j-titleName name left f-thide">实验环境描述</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                   <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                      <h4 class="j-name name u-line30" >
                                            <?=$description6?>
                                    </h4>
                                    </div>
                                </div>
                            </div>   
                        </div>
    
                        <div class=" tab2 hide ">
                            <div class="u-learn-moduletitle f-cb">
                                <h2 class="left j-moduleName"> 实验指导书</h2>
                            </div>
 
                            <dl class="book">		
                            <?php
                            $sql = "SELECT * FROM $table_courseware WHERE visibility=1 AND cc=" . Database::escape ( getgpc ( "cidReq", "G" ) ) . " ORDER BY display_order ASC,id DESC";
                            $all_courseware = api_sql_query_array ( $sql, __FILE__, __LINE__ );
                            $row_index = 0;
                            foreach ( $all_courseware as $cw_info ) {
                                    if ($cw_info ['cw_type'] == 'scorm') { //SCORM标准课件
                                        $progress = $objStat->get_courseware_progress ( $user_id, $course_code, $cw_info ['id'] );
                                        $lp_url = api_get_path ( WEB_SCORM_PATH ) . 'lp_controller.php?cidReq=' . $course_code . '&action=read&lp_id=' . $cw_info ['attribute'] . '&cw_id=' . $cw_info ["id"];
                                    ?>
                                    <tr>
                                        <td class="jie">
                                        <?=Display::return_icon ( 'scorm.gif', 'SCORM标准课件', array ('style' => 'vertical-align: middle;' ) ) . '&nbsp;&nbsp;'?>
                                        <a target="_blank" href="<?=$lp_url?>" title="<?=$cw_info ['title']?>"><?=api_trunc_str2 ( $cw_info ['title'] )?></a>
                                        </td>
                                        <?php
                                                if ($_configuration ['enable_display_courseware_track_info']) {
                                        ?>
                                        <td class="percent">
                                        <div class="tiao">
                                                <div style="background: rgb(0,49,92); height: 13px; width: <?=$progress?>%;"></div>
                                        </div>
                                        <div style="float: left; width: 30px;" class="de1"><?=$progress?>%</div>
                                        </td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                        <?php
                                        unset ( $progress );
                                    } elseif ($cw_info ['cw_type'] == 'html') {
                                        
                                    $course_code = api_get_course_code ();
                                    $tbl_courseware = Database::get_course_table ( TABLE_COURSEWARE );
                                    $tbl_track_cw = Database::get_statistic_table ( TABLE_STATISTIC_TRACK_E_CW );
                                    $tbl_course_user = Database::get_main_table ( TABLE_MAIN_COURSE_USER );

                                        $http_www = api_get_path ( WEB_COURSE_PATH ) . api_get_course_path () . '/document';  
                                        $progress = $objStat->get_courseware_progress ( $user_id, $course_code, $cw_info ["id"] );
                                        $document_name = $cw_info ['title'];
                                        $path = $cw_info ['path'];
                                        $default_page = $cw_info ['default_page'];
                                        
                                    $src =api_get_path ( WEB_CODE_PATH ) . 'courseware/link_goto.php?' . api_get_cidreq () . '&cw_id=' . $cw_info ["id"];   
                                    $cw_type = $cw_info ['cw_type'];
                                    $cw_id = $cw_info ["id"];
                                    
                                    $sql = "SELECT * FROM $tbl_courseware WHERE id=" . Database::escape ( $cw_id );
                                    $file_info = Database::fetch_one_row ( $sql, __FILE__, __LINE__ );
                                    if (empty ( $file_info )) exit ( "非法访问!" );

                                    evnet_courseware ( $course_code, $user_id, $cw_id, 0, 'add' );
                                    event_cw_access_times ( $course_code, $user_id, $cw_id );

                                    $sql = "UPDATE " . $tbl_course_user . " SET is_pass=" . LEARNING_STATE_IMCOMPLETED . " WHERE course_code='" . escape ( $course_code ) . "' AND user_id='" . escape ( $user_id ) . "' AND is_pass=" . LEARNING_STATE_NOTATTEMPT;
                                    api_sql_query ( $sql, __FILE__, __LINE__ );

                                    $sql = "UPDATE " . $tbl_course_user . " SET learning_status='" . LESSON_STATUS_INCOMPLETE . "' WHERE course_code='" . escape ( $course_code ) . "' AND user_id='" . escape ( $user_id ) . "' AND (learning_status IS NULL OR learning_status NOT IN ('" . LESSON_STATUS_NOTATTEMPT."','".LESSON_STATUS_COMPLETED."'))";
                                    $res = api_sql_query ( $sql, __FILE__, __LINE__ );
                                    $view_data ['src'] = $src;

                                    $ext_js = import_assets ( "jquery-plugins/jquery.timers-1.2.js" );
                                    $ext_js .= '<script>
                                            var web_path="' . api_get_path ( WEB_QH_PATH ) . '";
                                            var code="' . $course_code . '";
                                            var cw_id=' . $cw_id . ';
                                    </script>';
                                    $ext_js .= '<script type="text/javascript">
                                            $(document).ready( function() {
                                    //		if(!is_ie){
                                    //			$.prompt("本功能强烈建议在Chrome浏览器中使用, 其它浏览器可能无法跟踪学习时间记录,请注意!");
                                    //		}
                                            });</script>';
                                    $view_data ['ext_js'] = $ext_js;

                                    extract ( $view_data );
                                    ?>
                                    <?php
                                    echo import_assets ( "commons.js" );
                                    echo import_assets ( "jquery-latest.js" );
                                    echo import_assets ( "jquery-plugins/Impromptu.css", api_get_path ( WEB_JS_PATH ) );
                                    echo import_assets ( "jquery-plugins/jquery-impromptu.2.7.min.js" );
                                    echo $ext_js;
                                    ?>
                                    <script type="text/javascript">
                                    var updateContentAreaHeight=function(){
                                             var winHeight = $(window).height(); 
                                             $("#content_id").height(winHeight-60);
                                    }

                                    $(window).load(function() {
                                            updateContentAreaHeight();
                                    });

                                    $(window).resize(function() {
                                            updateContentAreaHeight();
                                    });

                                    var m_time=0;p_time=0;
                                     var tt;
                                     function onInit()  {
                                            tt=window.setInterval("startClock()",1000);//计时开始
                                     }

                                     function startClock() {	   
                                            m_time=m_time+1;//开始计时	 
                                            if(document.getElementById("LeftTime")) document.getElementById("LeftTime").value=parseInt(m_time / 60) + "分" + (m_time % 60) +"秒";
                                            if(document.getElementById("lblTimeAll")) document.getElementById("lblTimeAll").value=m_time;
                                     }

                                     var learn_time=0;
                                     function onFinish(){
                                             learn_time=$("#lblTimeAll").val();
                                             //alert($("#lblTimeAll").val());
                                                    $.ajax({type:"POST", url:"ajax_actions.php",data:{action:"track_cw_learning_time",cw_id:cw_id,learn_time:learn_time},
                                                            success:function(data){

                                                            }
                                                    });
                                      }
                                    </script>
                                    <SCRIPT FOR=window EVENT=onload LANGUAGE="JScript">

                                    document.all("content_name").height=content_name.document.body.scrollHeight;

                                   </SCRIPT>
                                    <body onload="onInit()" onbeforeunload="onFinish();">

                                    <div class="Study-content">
                                            <iframe id="content_id" name="content_name" src="<?=$src?>" style="width:100%"  border="0" frameborder="0"scrolling="yes"></iframe>
                                    </div>
                                    <?php ?>
                                    

                                        
                                        <?php
                                        if ($_configuration ['enable_display_courseware_track_info']) {
                                        ?>
                                            <td class="percent">
                                                <div class="tiao">
                                                <div style="background: rgb(0,49,92); height: 13px; width: <?=$progress?>%;"></div>
                                                </div>
                                                <div style="float: left; width: 30px;" class="de1"><?=$progress?>%</div>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                        </tr>
                                        <?php
                                    } elseif ($cw_info ['cw_type'] == 'mediaa') { //视频教程
                                        $progress = $objStat->get_courseware_progress ( $user_id, $course_code, $cw_info ["id"] );
                                        $document_name = $cw_info ['title'];
                                        $url = api_get_path ( REL_PATH ) . "main/courseware/flv_player.php?cw_id=" . $cw_info ["id"] . "&target=_blank";
                                        $url = 'document_viewer.php?cw_id=' . $cw_info ["id"] . '&url=' . urlencode ( $url );
                                        ?>
                                        <tr>
                                                <td class="jie"><?php
                                        echo Display::return_icon ( 'videos.gif', '视频点播课件', array ('style' => 'vertical-align: middle;' ) );
                                        echo '&nbsp;&nbsp;<a href="' . $url . '" target="_blank" title="' . $document_name . '">' . api_trunc_str2 ( $document_name ) . '</a>';
                                        ?></td>
                                    <?php
                                        if ($_configuration ['enable_display_courseware_track_info']) {
                                                ?>
                                                        <td class="percent">
                                                <div class="tiao">
                                                <div style="background: rgb(0,49,92); height: 13px; width: <?=$progress?>%;"></div>
                                                </div>
                                                <div style="float: left; width: 30px;" class="de1"><?=$progress?>%</div>
                                                </td><?php
                                        }
                                        ?>
                                                </tr>
                                        <?php
                                    } elseif ($cw_info ['cw_type'] == 'link') { //网页链接
                                        $progress = $objStat->get_courseware_progress ( $user_id, $course_code, $cw_info ["id"] );
                                        $url = api_get_path ( WEB_CODE_PATH ) . "courseware/link_goto.php?" . api_get_cidreq () . "&cw_id=" . $cw_info ["id"];
                                        $url = 'document_viewer.php?cw_id=' . $cw_info ["id"] . '&url=' . urlencode ( $url );
                                        ?>
                                        <tr>
                                                <td class="jie"><?php
                                        echo "<a href=\"" . $url . "\" target=\"_blank\">", Display::return_icon ( 'links.gif', '网页链接课件', array ('style' => 'vertical-align: middle;' ) ), "</a>";
                                        echo "&nbsp;&nbsp;<a href=\"" . $url . "\" target=\"_blank\" title=\"" . $cw_info ['title'] . "\">", api_trunc_str2 ( $cw_info ['title'] ), "</a>\n";
                                        ?></td>
                                        <?php
                                        if ($_configuration ['enable_display_courseware_track_info']) {
                                                ?>
                                                        <td class="percent">
                                                <div class="tiao">
                                                <div style="background: rgb(0,49,92); height: 13px; width: <?=$progress?>%;"></div>
                                                </div>
                                                <div style="float: left; width: 30px;" class="de1"><?=$progress?>%</div>
                                                </td><?php
                                        }
                                        ?>
                                                </tr>
                                        <?php
                                    }elseif ($cw_info ['cw_type'] == 'swf') { //PDF文件阅读
                                        $progress = $objStat->get_courseware_progress ( $user_id, $course_code, $cw_info ["id"] );
                                        $document_name = $cw_info ['title'];
                                        $url = api_get_path ( REL_PATH ) . "main/courseware/flv_player.php?cw_id=" . $cw_info ["id"] . "&target=_blank";
                                        $sql = "select path from crs_courseware where id = ".$cw_info['id'];
                                        $paths = Database::getval ( $sql);
                                        $url = 'flex_paper/index.php?cw_id=' . $cw_info ["id"] . '&url='.$paths.'&cc='.$course_code;
                                        ?>
                                                        <dd>
                                            <?php

                                                echo Display::return_icon ( 'pdf.png', '课件', array ('style' => 'vertical-align: middle;' ) );
                                                echo '<p><a href="' . $url . '" target="_blank" title="' . $document_name . '">' . api_trunc_str2 ( $document_name ) . '</a></p>';
                                            ?>
                                                        </dd>

                                            <?php
                                            if ($_configuration ['enable_display_courseware_track_info']) {
                                                ?>
                                                <td class="percent">
                                                    <div class="tiao">
                                                        <div style="background: rgb(0,49,92); height: 13px; width: <?=$progress?>%;"></div>
                                                    </div>
                                                    <div style="float: left; width: 30px;" class="de1"><?=$progress?>%</div>
                                                </td><?php
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </dl>
                        </div>


                        <div class="tab4 hide">
                            <div class="u-learn-moduletitle f-cb">
                                   <h2 class="left j-moduleName"> 实验场景</h2>
                            </div>
                            <?php
                            $lessonid = getgpc('cidReq');
                            echo "<iframe src='lessontop3.php?cidReq=$lessonid'  width='100%' style='min-height:640px' frameborder='0'> </iframe>";
                            ?>
                        </div>
                        
                        <div class=" tab5 hide">
                            <?php 
                            
                                //begin 课程教材
                                $sql = "SELECT `filetype` FROM  " . $tbl_document . "  AS docs	 WHERE docs.path LIKE '/%' AND docs.path NOT LIKE '/%/%'
                                        AND path!='/learnpath'	AND docs.cc='" . $cidReq . "' ORDER BY docs.display_order ASC,docs.filetype DESC";
                                $res_type=Database::getval($sql,__FILE__, __LINE__ );
                                if($res_type!=''&&$res_type!='folder'){ ?>
                                    <div class="u-learn-moduletitle f-cb">
                                        <h2 class="left j-moduleName">课程教材</h2>
                                    </div>
                                    
                                    <?php   
                                    include ("course_modules/documents.php"); 
                                    echo '<br><br>';
                                }   
                            //end 课程教材
                            //begin 课程视频   
                            $course_code=$_GET['cidReq'];
                            $user_id=api_get_user_id();
                            $courseDir = api_get_course_path () . "/document/flv";
                            $sys_course_path = api_get_path ( SYS_COURSE_PATH );
                            $base_work_dir = $sys_course_path . $courseDir;
                            $table_courseware = Database::get_course_table ( TABLE_COURSEWARE );
                            $sql="select * from  $table_courseware  where cc= $course_code AND cw_type= 'media' AND visibility=1";

                            $result=api_sql_query_array_assoc($sql,__FILE__, __LINE__); 
                            $num=count($result);
                            
                            $sql="select * from  $table_courseware  where cc= $course_code AND cw_type= 'link' AND visibility=1";
                            $result_link=api_sql_query_array_assoc($sql,__FILE__, __LINE__); 
                            $num_link=count($result_link);
                            if($num){?>
                                   <div class="u-learn-moduletitle f-cb">
                                        <h2 class="left j-moduleName">课程视频</h2>
                                        <div class="j-nav nav f-cb"> 
                                            <div id="j-tab">
                                                <input type=button width="80px" height="30px" style="color:#13a654;" name=go value="&nbsp;播放所有&nbsp;" onclick="Javascript:window.open('../../main/cloud/cloudplay.php?momi=qiant&user=<?=$user_id?>&lesson=<?=$course_code?>&type=2')">
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                for($i=0;$i<$num;$i++){
                                    if($result[$i]['cw_type']=='media'){ ?>
                                    
                                    <?php  
                                    $progress = $objStat->get_courseware_progress ( $user_id, $course_code, $result[$i]["id"] );
                                    $document_name = $result[$i]['title'];
                                    $document_name = explode('.', $document_name);
                                    $document_name = $document_name['0'];
                                    
                                    $path2=explode('/',$result[$i]['path']);
                                    $paths=explode('.',$path2[2]);
                                    $url = api_get_path ( REL_PATH ) . "main/courseware/flv_player.php?cw_id=" . $result[$i]["id"] . "&target=_blank";
                                    $url = 'document_viewer.php?cw_id=' . $result[$i]["id"] . '&url=' . urlencode ( $url );//old
                                    $url1 = '../../main/cloud/cloudplay.php?momi=qians&lesson='.$course_code.'&w=1024&h=768&f='.$paths[0];
                                    ?>
                                    <tr>
                                            <td class="jie"><?php
                                    $imga= Display::return_icon ( 'videos.gif', '视频点播课件', array ('style' => 'vertical-align: middle;' ) );
                                    echo '&nbsp;&nbsp;<a href="' . $url1 . '" target="_blank" title="' . $document_name . '">' .$imga. api_trunc_str2 ( $document_name ) . '</a>';
                                    ?></td><br/><br/><hr/><br/>
                                    <?php
                                    if ($_configuration ['enable_display_courseware_track_info']) {
                                            ?>
                                                    <td class="percent">
                                            <div class="tiao">
                                            <div style="background: rgb(0,49,92); height: 13px; width: <?=$progress?>%;"></div>
                                            </div>
                                            <div style="float: left; width: 30px;" class="de1"><?=$progress?>%</div>
                                            </td><?php
                                    }
                                    ?>
                                            </tr>
                                    <?php
                                    } 
                                } 
                            }
                            
                            
                            if($num_link){?>
                                   <div class="u-learn-moduletitle f-cb">
                                        <h2 class="left j-moduleName">链接资源</h2>
                                   </div>
                            <?php
                                for($i=0;$i<$num_link;$i++){
                                    if($result_link[$i]['cw_type']=='link'){ 
                                    $progress = $objStat->get_courseware_progress ( $user_id, $course_code, $result_link[$i]['id'] );
                                    $url = api_get_path ( WEB_CODE_PATH ) . "courseware/link_goto.php?" . api_get_cidreq () . "&cw_id=" . $result_link[$i]['id'];
                                    $url = 'document_viewer.php?cw_id=' . $result_link[$i]['id'] . '&url=' . urlencode ( $url );
                                    ?>
                                    <tr>
                                            <td class="jie">
                                    <?php
                                                echo "<a href=\"" . $url . "\" target=\"_blank\">", Display::return_icon ( 'links.gif', '网页链接课件', array ('style' => 'vertical-align: middle;' ) ), "</a>";
                                                echo "&nbsp;&nbsp;<a href=\"" . $url . "\" target=\"_blank\" title=\"" . $result_link[$i]['title'] . "\">", api_trunc_str2 ( $result_link[$i]['title'] ), "</a>\n";
                                    ?>
                                            </td>
                                    <?php
                                    if ($_configuration ['enable_display_courseware_track_info']) {
                                            ?>
                                                    <td class="percent">
                                                        <div class="tiao">
                                                        <div style="background: rgb(0,49,92); height: 13px; width: <?=$progress?>%;"></div>
                                                        </div>
                                                        <div style="float: left; width: 30px;" class="de1"><?=$progress?>%</div>
                                                    </td>
                                    <?php
                                    }
                                    ?>
                                    </tr>
                                    <?php
                                    } 
                                } 
                            }
                            ?>
                            
                              
                        </div>

                        <div class=" tab6 hide">
                            <div class="u-learn-moduletitle f-cb">
                                <h2 class="f-fl j-moduleName">实验图片与录屏</h2>
                            </div>
                            <?php
                                echo "<iframe src='image_and_media.php?cidReq=$n_cidReq'  width='100%' style='min-height:640px' frameborder='0'> </iframe>";
                            ?>
                        </div>


                        <div class=" tab7 hide" style="padding-bottom:40px;">
                            <?php 
                            if($report_id!=NULL){
                            ?>
                            <div class="u-learn-moduletitle f-cb">
                                <h2 class="f-fl j-moduleName" style="float:left;">查看实验报告</h2>
                                <span class="see-score"><?php  echo   link_button('', '查看得分', 'user_report_result.php?r_id='.$report_id, '60%','70%', true);  ?></span>
                            </div>
                            <?php 
                            $sql="select * from `report` where `id` = '$report_id' ";
                            $result = api_sql_query ( $sql, __FILE__, __LINE__ );
                            while ( $data = Database::fetch_array ( $result, 'ASSOC' ) ) {   ?>

                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告名称</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?= $data['report_name']?></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告作者</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?= $data['user']?></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告课程</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?= $data['code']?></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告实验目的及要求</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?= $data['purpose']?></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告实验设备环境及要求</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?= $data['equipment']?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告实验内容与步骤</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <?php 
                                            $a=explode("^^",$data['content']);
                                            foreach($a as $key => $value){  
                                                $b=explode("^",$value);
                                                $c=$b['0'];
                                        ?>
                                        <h4 class="j-name name f-thide"><?= $c?>
                                         <span class="no-step"><img src="../../storage/snapshot/<?= $b['1']?>.jpg"></span>
                                        </h4>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告实验结果</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-i d-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?= $data['result']?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告实验分析与讨论</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?= $data['analysis']?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                    <h3 class="j-titleName name left f-thide">
                                        <?php  if($report_status==1){
                                            echo  "<a  href='javascript:;'  style= 'color:green;font-weight: bold;'>报告已经提交，不能再次修改！！</a>";
                                        }else{
                                            ?>
                                       <a href="report_test.php?cidReq=<?= $n_cidReq?>" title='编辑实验报告论'  style="color:green;font-weight: bold;">编辑实验报告</a>
                                        <?php
                                        } ?> 
                                    </h3>
                                </div>
                                <div class="f-pa line j-line"></div>

                            </div>
                            <?php }
                            
                            }else{
                                echo "<iframe src='report_test.php?cidReq=$n_cidReq&b=3'  width='100%' style='min-height:640px' frameborder='0'> </iframe>";
                            } ?>
                        </div>

                          <div class=" tab8 hide">
                            <div class="u-learn-moduletitle f-cb">
                                <h2 class="f-fl j-moduleName">课程作业</h2>
                            </div>
                            
                        <?php
                          include ("course_modules/assignment.php");
                          ?>
                        </div>
                        
                        <div class=" tab9 hide"> 
                            <div class="u-learn-moduletitle f-cb">
                                     <h2 class="left j-moduleName"> 课程公告</h2>
                            </div>
                            <?php 
                                $htmlHeadXtra [] = Display::display_thickbox ();
                                Display::display_header ( NULL, FALSE );

                                $objAnnouncement = new CourseAnnouncementManager ();
                                $sql_num = $objAnnouncement->get_announcemet_list_sql ( api_get_user_id () );
                                $res = api_sql_query ( $sql_num, __FILE__, __LINE__ );
                                $announcement_number = Database::num_rows ( $res );

                                $tbl_announcement = Database::get_course_table ( TABLE_ANNOUNCEMENT );
                                $sql = CourseAnnouncementManager::get_announcemet_list_sql ( api_get_user_id (), api_get_course_code (), '0000-00-00 00:00:00' );
                                $sql .= " ORDER BY t1.end_date DESC";
                                $sorting_options = array ();
                                $sorting_options ['column'] = 1;
                                $sorting_options ['default_order_direction'] = 'DESC';
                                $result = api_sql_query ( $sql, __FILE__, __LINE__ );

                                $table_data = array ();
                                $index = 1;
                                while ( $data = Database::fetch_array ( $result, 'ASSOC' ) ) {
                                      $row = array (); 
                                      $row [] = $data ['title'];
                                      $row [] = $data ['lastedit_date'];
                                      $row [] = $data ['content'];
                                      $row [] = $html_action;
                                      $table_data [] = $row;
                                }
                                
                                $path=api_get_path ( WEB_PATH )."storage/courses/".$cidReq."/attachments";
                                $file_new=  Database::getval("SELECT  `new_name`  FROM `crs_attachment` WHERE `cc`=".$cidReq." ORDER BY `creation_time` DESC ", __FILE__, __LINE__);
                                $path_file=$path.'/'.$file_new;
                                Display::display_footer ();
                                if($row['1']!=NULL){
                            ?>
                                <div class="m-learnChapterNormal f-pr">
                                    <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                         <h3 class="j-titleName name left f-thide"><?= get_lang ( 'Title' )?></h3>
                                    </div>
                                    <div class="f-pa line j-line"></div>
                                    <div class="lessonBox j-lessonBox">
                                        <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                            <h4 class="j-name name f-thide"><?= $row['0']?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-learnChapterNormal f-pr">
                                    <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                         <h3 class="j-titleName name left f-thide">发布时间</h3>
                                    </div>
                                    <div class="f-pa line j-line"></div>
                                    <div class="lessonBox j-lessonBox">
                                        <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                            <h4 class="j-name name f-thide"><?= $row['1']?></h4>
                                        </div>
                                    </div>
                                </div>
                            <div class="m-learnChapterNormal f-pr">
                                    <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                         <h3 class="j-titleName name left f-thide">内容</h3>
                                    </div>
                                    <div class="f-pa line j-line"></div>
                                    <div class="lessonBox j-lessonBox">
                                        <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                            <h4 class="j-name name f-thide">
                                                <?= $row['2']?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                    <div class="m-learnChapterNormal f-pr">
                                    <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                         <h3 class="j-titleName name left f-thide">附件</h3>
                                    </div>
                                    <div class="f-pa line j-line"></div>
                                    <div class="lessonBox j-lessonBox">
                                        <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                            <h4 class="j-name name f-thide">
                                                <a href="<?= $path_file?>" title="下载附件"><?= $file_new?></a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <?PHP }else{
                                     echo "<center><tr><td colspan='10'>没有相关课程公告</td></tr></center>";
                                } ?>
                        </div>
                        <div class=" tab10 hide" style="height:100%;">
                            <div class="u-learn-moduletitle f-cb" style="margin-bottom:0px;">
                                <h2 class="f-fl j-moduleName">课程评论</h2>
                            </div>
                            <?php
                                echo "<iframe src='Comment.php?cidReq=$n_cidReq'  width='100%' style='min-height:640px' frameborder='0'> </iframe>";
                            ?>
                        </div>
                         <div class=" tab11 hide" style="height:100%;">
                            <div class="u-learn-moduletitle f-cb" style="margin-bottom:0px;">
                                <h2 class="f-fl j-moduleName">课程拓扑</h2>
                            </div>
                            <?php
                                echo "<iframe src='../../netMap/topoShow.php?action=show&cidReq=".getgpc("cidReq")."'  width='100%' style='min-height:640px' frameborder='0'> </iframe>";
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
   </div>
<script type="text/javascript">


$(document).ready(function(){
		$('.tab li').bind('click',function(){
//			var thisIndex = $(this).index();
			$(this).addClass('u-curtab').siblings().removeClass('u-curtab');
			$('.g-mn1 .g-mn1c').eq(thisIndex).show().siblings().hide();	
		})
})
</script>
<style>
    hr{
        border:1px dotted #ccc;
    }
</style>