<?php
$cidReset = true;
include_once ("inc/app.inc.php");
include_once ("../../main/inc/global.inc.php");
$r_id=$_GET['r_id'];

$sql="select  `report_name`,`code`,`score`,`comment`  from  `report`  where  `id`=".$r_id;    
$r_info=api_sql_query_array_assoc($sql);
?>
<html>
    <head><meta charset="utf-8"/></head>
    <style>
        .w-score{
            width:100%;
        }
        .score-tab{
            width:80%;
            margin:0 auto;
            background:#fff;
        }
        .score-tab tr td{
            border-left: 1px dotted #e1e1e1;
            border-right: 1px dotted #e1e1e1;
            border-bottom: 1px dotted #e1e1e1;
            border-right: none !important;
            font-weight: 500;
            line-height: 40px;
           
        }
        .tab-td1{
            text-align:right;
        }
        .tab-td2{
            padding-left:20px;
        }
    </style>
    <body>
        <div class="w-score">
           <table cellspacing="0"  cellpadding="0" border="0"  class="score-tab">
               <tbody>
                   <tr>
                       <td class="tab-td1" width="200px">实验报告名称：</td>
                       <td class="tab-td2"><?=$r_info[0]['report_name']?></td>
                   </tr>
                   <tr>
                       <td class="tab-td1" width="200px">课程名称：</td>
                       <td class="tab-td2"><?= $r_info[0]['code']?></td>
                   </tr>
                   <tr>
                       <td class="tab-td1" width="200px">得分：</td>
                       <td class="tab-td2"><?=($r_info[0]['score']?$r_info[0]['score']:"未批改")?></td>
                   </tr>
                   <tr>
                       <td class="tab-td1" width="200px">教师评语：</td>
                       <td class="tab-td2"><?=($r_info[0]['comment']?$r_info[0]['comment']:"暂无评语")?></td>
                   </tr>
               </tbody>
           </table> 
            
            
            
<!--        <div style="width: 100%;line-height: 40px;"><span style="background-color: #D2EFF7;border: 1px #ccc dashed;">实验报告名称：</span><span><?=$r_info[0]['report_name']?></span></div>
        <div style="width: 100%;line-height: 40px;"><span style="background-color: #D2EFF7;border: 1px #ccc dashed;">课程名称：</span><span><?= $r_info[0]['code']?></span></div>
        <div style="width: 100%;line-height: 40px;"><span style="background-color: #D2EFF7;border: 1px #ccc dashed;">得分：</span><span><?=($r_info[0]['score']?$r_info[0]['score']:"未批改")?></span></div>
        <div style="width: 100%;line-height: 40px;"><span style="background-color: #D2EFF7;border: 1px #ccc dashed;">教师评语：</span><span><?=($r_info[0]['comment']?$r_info[0]['comment']:"暂无评语")?></span></div>-->
        </div>
    </body>
</html>

   