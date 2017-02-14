<?php
$language_file = 'admin';
$cidReset = true;
include_once ("../../inc/global.inc.php");
if (! isRoot () && $_SESSION['_user']['status']!='1') api_not_allowed ();
$report_id=  getgpc('id');
?>

<div class=" tab7 hide" style="height:100%;">
                       
                            <?php 
                            $sql="select * from `report` where `id` = '$report_id' ";
                            $result = api_sql_query ( $sql, __FILE__, __LINE__ );
                            while ( $data = Database::fetch_array ( $result, 'ASSOC' ) ) {   ?>

                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告名称：</h3>
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
                                     <h3 class="j-titleName name left f-thide">实验报告作者：</h3>
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
                                     <h3 class="j-titleName name left f-thide">实验报告课程：</h3>
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
                                     <h3 class="j-titleName name left f-thide">实验报告实验目的及要求：</h3>
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
                                     <h3 class="j-titleName name left f-thide">实验报告实验设备环境及要求：</h3>
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
                                     <h3 class="j-titleName name left f-thide">实验报告实验内容与步骤：</h3>
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
                                         <span class="no-step"><img src="../../../storage/snapshot/<?= $b['1']?>.jpg"></span>
                                        </h4>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="m-learnChapterNormal f-pr">
                                <div class="titleBox j-titleBox f-cb" id="auto-id-HIAtBpJJKXmR4sSU">
                                     <h3 class="j-titleName name left f-thide">实验报告实验结果：</h3>
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
                                     <h3 class="j-titleName name left f-thide">实验报告实验分析与讨论：</h3>
                                </div>
                                <div class="f-pa line j-line"></div>
                                <div class="lessonBox j-lessonBox">
                                    <div class="u-learnLesson normal f-cb f-pr last" id="auto-id-NERqH6rnJ2CidLD9">
                                        <h4 class="j-name name f-thide"><?= $data['analysis']?></h4>
                                    </div>
                                </div>
                            </div>
                            
                            <?php }  ?>
                        </div>


<style>
    h1, h2, h3, h4, h5, h6, div, dl, dt, dd, ul, ol, li, p, blockquote, pre, hr, figure, table, caption, th, td, form, fieldset, legend, input, button, textarea, menu {
    margin: 0px;
    padding: 0px;
    font-weight:normal;
}
    .m-learnChapterNormal{
        font-size:14px;
        font-weight:normal;
    }
.f-pr {
    position: relative;
}
.m-learnchapternormal .titlebox {
    background: rgb(232, 249, 255);
    padding:8px 15px;
}
.f-cb, .f-cbli li {
    zoom: 1;
}
.m-learnchapternormal .titlebox .name {
    font-size:14px;
    font-weight:normal;
    max-width: 80%;
}
.f-thide {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.u-learnlesson {
padding: 20px 15px 20px 40px;
}
.u-learnlesson.normal .name {
   max-width: 95%;
}
.no-step {
display: block;
padding: 20px;
}
</style>