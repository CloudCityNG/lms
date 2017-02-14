<?php
$cidReset = true;
include_once ("../../inc/global.inc.php");
api_protect_admin_script ();
header("content-type:text/html;charset=utf-8");
$vid=(int)filter_input(INPUT_GET,'vm');

$lessonarr=mysql_fetch_assoc(mysql_query('select system,addres,lesson_id,user_id,manage from vmtotal where id ='.$vid));

   /*
    * 数据展示函数
    */
   function listarr($arrname){
       $arrlist=null;
       $countarr=count($arrname)-1;
       foreach($arrname as $arrk => $arrv){
           if($countarr == $$arrk){
               $arrlist.=$arrv;
           }else{
               $arrlist.=$arrv.',';
           }
       }
        return $arrlist;
   }
Display::display_header ( $tool_name, FALSE );
    
    $result=mysql_query('select mem,cpu,diskread,diskwrite,mouse,netin,netout,nettime,times from vm_monitor where system="'.$lessonarr['system'].'" and user_id="'.$lessonarr['user_id'].'" and manage=0 and addres="'.$lessonarr['addres'].'"');
if($result)
{
     while($row=mysql_fetch_assoc($result)){
         $memarr[]=$row['mem'];
         $cpuarr[]=$row['cpu'];
         $readarr[]=$row['diskread'];
         $writearr[]=$row['diskwrite'];
         $mousearr[]=$row['mouse'];
         $netinarr[]=$row['netin'];
         $netoutarr[]=$row['netout'];
         $netarr[]=$row['nettime'];
         $timearr[]=  substr($row['times'],8);
     }
           
           /*
            * 时间数组
            */
           $timelist=null;
           $countime=count($timearr)-1;
   foreach($timearr as $timek => $timev){
       if($timek==$countime){
           $timelist.="'".$timev[0].$timev[1]."时".$timev[2].$timev[3]."分".$timev[4].$timev[5]."秒"."'";
       }else{
           $timelist.="'".$timev[0].$timev[1]."时".$timev[2].$timev[3]."分".$timev[4].$timev[5]."秒"."',";
       }
   }
    /*
     * cpu使用率
     */
          $cpulist =listarr($cpuarr);
    /*
     * 内存使用率
     */
          $memlist=listarr($memarr);
    /*
    * 宽带进
    */
          $netinlist=listarr($netinarr);
    /*
     * 宽带出
     */     
          $netoutlist=listarr($netoutarr);
    /*
     * 磁盘读
     */
          $readlist=listarr($readarr);
    /*
     * 磁盘写
     */         
          $writelist=listarr($writearr);
    /*
     * 网络通信延迟
     */ 
          $netlist=listarr($netarr);
    /*
     * 鼠标响应时间
     */
          $mouselist=listarr($mousearr);   
}          
            $lesstitle=DATABASE::getval('select `title` from `course` where `code`="'.$lessonarr['lesson_id'].'"');
            $result3=mysql_query('select username from user where user_id='.$lessonarr['user_id']);
        if($result3){
            $userArr=  mysql_fetch_assoc($result3);
        }
            
?>
   <script type="text/javascript" src="../../../themes/js/jquery-1.5.2.min.js"></script>
   <script type="text/javascript" src="../../../themes/js/highcharts.js"></script>
<!--   <script type="text/javascript" src="../../../themes/js/dark-green.js"></script>-->
   <script type="text/javascript" src="../../../themes/js/exporting.js"></script>
   <script type='text/javascript'>
      $(function(){
          $('#Coludlab-map-cpu').highcharts({
            chart: {
                type: 'line'
            },
            credits:{
     enabled: false
},
            title: {
                text: '<div style=\"font-size:16px;\">用户: <?php echo $userArr['username'];?>内存、CPU、宽带走势图</div>'
            },
            xAxis: {
                categories: [<?php echo $timelist;?>]
            },
            yAxis: {
                title: {
                    text: '百 分 比'
                }
                
            },
			tooltip: {
                crosshairs:true,
                shared:true
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled:false
                    },
                    enableMouseTracking:true
                }
            },
			
            series: [{
                name: 'C P U',
                data: [<?php echo $cpulist;?>]
                     },{
                name:'内 存',
                data:[<?php echo $memlist;?>]
                     },{
                name:'宽带接收',
                data:[<?php echo $netinlist;?>]
                     },{
                name:'宽带发送',
                data:[<?php echo $netoutlist;?> ]
                    }]
        });
        $('#Coludlab-map-online').highcharts({
            chart: {
                type: 'line'
             },
           credits:{
     enabled: false
},
            title: {
                text: '<div style="font-size:16px;">用户 :<?php echo $userArr['username'];?>  硬盘读写、网络延迟、鼠标响应时间走势图</div>'
            },
            xAxis: {
                categories: [<?php echo $timelist;?>]
                   },
            yAxis: {
                title:{ text: '数   值' },
                min:0
                },
		tooltip:{
                crosshairs:true,
                shared:true
                },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled:true
                    },
                    enableMouseTracking:true
                }
            },
			
            series: [{
                  name:'磁盘读取',
                  data:[<?php echo $readlist;?>]
            },{
                  name:'磁盘写入',
                  data:[<?php echo $writelist;?>]
            },{
                  name:'网络延迟',
                  data:[<?php echo $netlist;?>]
            },{
                  name:'鼠标响应',
                  data:[<?php echo $mouselist;?>]
            }]
        });
      });
   </script>
<section class="column">
    <div class="div1div">
     显示用户:&nbsp;<?php echo $userArr['username'];?>&nbsp;&nbsp;课程:&nbsp;<?php echo $lesstitle;?>&nbsp;&nbsp;虚拟模板名称:&nbsp;<?php echo $lessonarr['system'];?>&nbsp;&nbsp;虚拟机ip地址:&nbsp;<?php echo $lessonarr['addres'];?>
    </div>
    <article class="div2div">
        <h3 class="vmip"><img src="/lms/themes/default/images/base1.gif" align="absmiddle" width="30px" height="30px" >  图表</h3>
        <div class="d">
<?php if($result){?>
           
                 <div id="Coludlab-map-cpu"></div>
         
            <div id="Coludlab-map-online"></div>
<?php }else{
           echo "没有相关数据";
}?>        
        </div>
    </article> 
</section>
<style>
    html,body{
        margin:0;
        padding:0;
    }
    .column{
        margin:30px;
    }
.div1div{
    height:40px;
    line-height:40px;
    background:linear-gradient(to bottom,#9bc747 0%,#82bd42 100%);
    border: 1px solid #719e37;
    border-radius: 5px;
    color:#fff;
}
.div2div{
     margin-top:40px;
    border: 1px solid #9BA0AF;
    background: #ffffff;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}
.vmip{
    padding-left:10px;
    height:60px;
    line-height:60px;
    font-size:20px;
/*    background-color:url("../../../themes/images/table_sorter_header.png") repeat-x;*/
background:linear-gradient(to bottom,#FCFCFC 0%,#EDEDF0 100%);
border-bottom:1px solid #9A9A9A;
}

.d{
    margin:0;
}
#Coludlab-map-cpu{
    width:95%;
    margin:10px auto;
   
}


#Coludlab-map-online{
     width:95%;
    margin:10px auto;
}
.highcharts-container{
    margin:0 auto;
    margin-left:-8px;
}
</style>
 