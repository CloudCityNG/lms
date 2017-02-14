<?php
/**topo展示，不可操作**/
header("content-type:text/html;charset=utf-8");
include_once ("../main/inc/global.inc.php");
include_once ("../main/inc/app.inc.php");
include_once ("router.class.php");
$language_file = 'admin';
$cidReset = true; 
$title=api_get_setting('siteName');

$action=getgpc("action","G");
$act=getgpc("act","G"); 
$topoid =intval( getgpc ( 'id', 'G') );
  
$USERID=$_SESSION['_user']['user_id'];

$sql1="select count(*) from `labs_run_devices` where `labs_name`='".$topoid."' and `USERID`=".$USERID;
$run_devices_count=DATABASE::getval($sql1,__FILE__,__LINE__);

$sql_run="select count(*) from `labs_run_devices` where `USERID`=".$USERID;
$sql_run_count=DATABASE::getval($sql_run,__FILE__,__LINE__);

$rep1=array('}','"','px',' ');
$rep2=array('','','','');

if( $topoid!==''){
    $netmap_data=DATABASE::fetch_one_row("select `id`,`name`,`netmap`,`diagram` from `labs_labs`  where `id`='".$topoid."'",__FILE__,__LINE__);
     
     $toponame=trim($netmap_data['name']);
     $netmap=$netmap_data['netmap']; 
     $diagram=$netmap_data['diagram'];
     $net_arr = explode('","', $netmap);
        foreach($net_arr as $net_value){
           $net_var1 = explode('","', $net_value);
           $net_var2 = explode(' ', $net_var1[0]);
           foreach($net_var2 as $ss){
               $net_var3 = explode(':', $ss);
               $nodeIdArr[]=str_replace($rep1, $rep2, $net_var3[0]);
           }
        }
        $diagram = explode(';', $diagram);
        $nodeIdArrs=array_merge($diagram,$nodeIdArr);
        $deviceArr1=array_unique($nodeIdArrs);
        $deviceArr=array_filter($deviceArr1);
}else{
     $html = "访问被拒绝：非法操作。";
     Display::display_error_message ( $html, false );
     exit();
}
if(isset($_GET['status']) && $_GET['status']=='loading' && $_GET['action']=='show' && $_GET['id']!==''){

        $sql_run_count="select count(*) from `vslab`.`labs_run_devices` where `labs_name`='".$topoid."' and `USERID`='".$USERID."'";
        $status=DATABASE::getval($sql_run_count,__FILE__,__LINE__);

        if($status==0){
            if($sql_run_count==0){
                loading_labs($USERID,$topoid);
                header("Location: ./topoLook.php?action=show&id=".$topoid);
                exit ();
            }else{
                header("Location: ./topoLooks.php?action=show&id=".$topoid);
                exit ();
            }
            
            
        }
}
if(isset($_GET['action']) && $action=='show' && $topoid!==''){
      
    if($deviceArr){
        $DD=array();
        foreach($deviceArr as $sda){
            $DD[]=$sda;
        } 
        $deviceArr_count=count($DD);
        if($deviceArr_count){
            $sqll='(';
            $deviceArr_count1=$deviceArr_count - 1;
            for($f=0;$f<$deviceArr_count;$f++){
                
                if($deviceArr_count=='1'){
                    $d1=str_replace($rep1, $rep2,$DD[$f]);
                    $sqll.=' `id`='.$d1;
                }else{
                    if($DD[$f]){
                        if($f==$deviceArr_count1){
                            $d2=str_replace($rep1, $rep2,$DD[$f]);
                            $sqll.=' `id`='.$d2;
                        }else{
                            $d3=str_replace($rep1, $rep2,$DD[$f]);
                            $sqll.=' `id`='.$d3.' OR ';
                        }
                    }
                }
            }
            $sqll.=')'; 
        }
        
        $sql_device="select * from `labs_devices` where `lab_id`='".$toponame."' ";
        if($DD[0]){
            $sql_device.=" AND ".$sqll;
        }
        $device_data=api_sql_query_array_assoc($sql_device,__FILE__,__LINE__);
        $device_str='{"nodes":[';
        $offset_top=='';
        $offset_left=='';
        $desc_left=='';
        $desc_top=='';
        $device_counts=count($device_data);
        $device_end_id=$device_counts-1;
         
        for($i=0;$i<$device_counts;$i++){
            $nodeId=$device_data[$i]['id'];
            $dname=$device_data[$i]['name'];
            $picture=$device_data[$i]['picture'];
            $device_model=$device_data[$i]['ios'];
            $offset_top=$device_data[$i]['top']."px";
            $offset_left=$device_data[$i]['left']."px";
            $sllot=$device_data[$i]['slot'];
            $desc_info=$device_data[$i]['desc'];
            $desc_left="-40";
            $desc_top="60";
            $slots=explode(';',$sllot);array_pop( $slots);
            $interface="";
            if(in_array($nodeId, $deviceArr)){
                if($default[$i]['id']==$device){
                       $key1=0;
                       foreach($slots as $kk=> $mod){
                           $sqls="select `size`,`interface_type` from `labs_mod` where `mod_name`='".$mod."'";
                           $mod_data = api_sql_query_array_assoc ( $sqls, __FILE__, __LINE__ );
                           $size=$mod_data[0]['size'];

                           $interface_type=$mod_data[0]['interface_type']; 
                           $interface_numbers=explode(',',$size);
                           $interface_number=$interface_numbers[1];
                           $interface_nums=$interface_nums+$interface_number+1;

                           if($interface_type=="串口"){
                              for($s=0;$s<=$interface_number;$s++){
                                  $interface.='"'.$key1.'":"S'.$kk.'/'.$s.'",';
                                  $key1=$key1+1;
                              }
                           }else{
                              for($e=0;$e<=$interface_number;$e++){
                                  $interface.='"'.$key1.'":"E'.$kk.'/'.$e.'",';
                                  $key1=$key1+1;
                              } 
                           }

                           $interface_type="";

                       }
                   }
               }
               $interfaces= rtrim($interface,",");
               if($device_model!==''){
                   $device_models="(".$device_model.")";
               }
            if($i==$device_end_id){
            //最后一个没有逗号
                $device_str.='{"nodeId":"'.$nodeId.'","nodeName":"'.$dname.$device_models.'","nodeType":"'.$device_model.'","model":"'.$picture.'","offset":{"left":"'.$offset_left.'","top":"'.$offset_top.'"},"nodeDesc":{"desc":"'.$desc_info.'","left":'.$desc_left.',"top":'.$desc_top.'},"portsnumber":"'.$interface_nums.'","interface":{'.$interfaces.'}}';
            }else{
                $device_str.= '{"nodeId":"'.$nodeId.'","nodeName":"'.$dname.$device_models.'","nodeType":"'.$device_model.'","model":"'.$picture.'","offset":{"left":"'.$offset_left.'","top":"'.$offset_top.'"},"nodeDesc":{"desc":"'.$desc_info.'","left":'.$desc_left.',"top":'.$desc_top.'},"portsnumber":"'.$interface_nums.'","interface":{'.$interfaces.'}},';
            }
            $interface_nums=0;
     }
     $device_str.='],"netMap":['.$netmap.']}';
        
    }else{
        $html = "访问被拒绝：您的拓扑错误，请联系管理员。";
        Display::display_error_message ( $html, false );
        exit(); 
    }
}else{
    tb_close();
}
//echo $device_str."<br>";exit;
if($run_devices_count){
    header("Location: ./topoLook.php?action=show&id=".$topoid);
    exit ();
}

//还原实验配置
if(isset($_GET['action']) && $_GET['status']=='return' && $_GET['action']=='show' && $topoid!==''){
    if(!$USERID){
        $user_id=$_SESSION['_user']['user_id'];
    }else{
        $user_id=$USERID;
    }
    if($topoid && $user_id){
       stoping_labs($user_id,$topoid);
       $url='/tmp/mnt/iostmp/';
       $urlhome='/tmp/mnt/iostmp/'.$user_id;
       $url_routercourse=glob(URL_ROOT."/www".URL_APPEDND."/storage/routecourses/".$topoid."-*.lib");
       $url_router=$url_routercourse[0];
       $url_user=$url.$user_id; 
       if($topoid){
	  $exec_var1="cd ".$url_user."/; sudo -u root rm  -rf ".$topoid;    
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
  //  tb_close("topoLooks.php?action=show&id=".$topoid);
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>拓扑展示,不可操作</title>
    <meta name="keywords" content="network map, Web Interface, linux"/>
    <link href="css/template_netmap.css" rel="stylesheet" type="text/css"/>
    <link href="css/jquery.contextMenu.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="all" type="text/css" href="css/jquery-impromptu.css"/>
    <style type="text/css">
        #main {width:99%; border: 2px solid graytext;background-color:rgb(218, 212, 213);   }
        #sidebarContainer { height: 50px; }
        #sidebarContainer .tt{height:50px;width:100%;}
        #sidebarContainer .title{height:10px;}
        #sidebarContainer .report{ text-align: right;float: left;height:20px;width:15%;font-size: 18px;font-weight: bold;  color:gray;}
        #sidebarContainer a.action_botton{ height: 20px;margin-top: 4px; float:right;margin-right:15px;font-size: 16px;  /*border: 1px solid green;*/  }
        #sidebarContainer div {margin-top: 5px;  }
        #graphContainer {   /*float: left;*/  width:100%;  height: 700px; /* margin-top: 100px;*/   }
        #sidebarContainer ul { list-style: none;padding: 0;}
        #sidebarContainer ul li { padding: 0 0 0 8px;  }
    </style>
    <script type='text/javascript' src='js/jquery-1.8.2.min.js'></script>
    <script type='text/javascript' src='js/jquery-ui-1.8.23.custom.min.js'></script>
    <script type='text/javascript' src='js/jquery.jsPlumb-1.3.14-all-min.js'></script>
    <script type="text/javascript" src="js/jquery.contextMenu.js"></script>
    <script type="text/javascript" src="js/HashMap.js"></script>
    <script type="text/javascript" src="js/jquery.jsIOUWebNetMap.js"></script>
    <script type="text/javascript">
    (function() {
    "use strict";
    var nodes = [ ],
        endpoint = {
            Anchor : "Continuous",
            //Connector : [ "Bezier", { curviness: 50 } ],
            Connector : [ "Straight" ],
            Endpoint : "Blank",
            //PaintStyle : { lineWidth : 10, strokeStyle : "#000000" },
            cssClass: "link"
        },
        netMap = new ArrayList(),
        devMapIndex = new HashMap(),
        currentNodeIndex = 0,
        sourcePort = "",
        targetPort = "",
        sourceNode = "",
        targetNode = "",
        usedPorts = new HashMap(),
        endpointOptions = { isSource: true, isTarget: true },
        prepare = function(elId) {
            //jsPlumbDemo.initHover(elId);
            return jsPlumb.addEndpoint(elId, endpoint);
        };

    function getDevIndex(type) {
        var index = devMapIndex.get(type);
        index = index || 0;
        index = parseInt(index, 0);
       // alert(type + " index: " + index);

        index = index + 1;
        devMapIndex.put(type, index);
        return index;
    }

    /**
     * 打开控制台
     */
//     function openConsole(nodesId) {
//       open("<?=$url?>?nodesId="+nodesId+"&topo=<?=$topoid?>");
//    }

    /**
     *  <li> Mouse up listener
     * @param d
     */
    function addLiMouseUpListener(d){
        var deviceObj = $(d);
        deviceObj.find("div a").bind("dblclick", function(e) {
            var aObj = $(this);
            var consoleId = aObj.attr("id");
           // openConsole(consoleId);
        }
    ); 
        deviceObj.find("ul li").bind("mouseup", function() {
            targetNode = $(this).parent().parent().attr("id");
            targetPort = $(this).find("a").html();
            // Verify this node
            if (jsIOUWebNetMap.isUsedPort(targetNode + ":" + targetPort)) {
                sourceNode = "";
                sourcePort = "";
                alert("对不起，这个目标端口已经使用！");
                return;
            }

            console.log("starting build connection: " + targetPort + sourcePort);

            jsPlumb.connect({
                source: sourceNode,
                target: targetNode,
                overlays: [
                    [ "Label", {label: sourcePort, location: 0.15, cssClass: "label"}],
                    [ "Label", {label: targetPort, location: 0.85, cssClass: "label"}]
                ]
            });

            jsIOUWebNetMap.hidePopWindows();
            jsIOUWebNetMap.resetCurrentPort();
        });

        deviceObj.bind("dblclick", function(e) {
  
           // e.preventDefault();
	    var aObj = $(this);
            var consoleId = aObj.attr("id");
            openConsole(consoleId);
        });
    }

    /**
     * <image> event listener
     * @param imgDom
     */
    function imageListener(imgDom) {
        var currentNode =  $(imgDom).parent().attr("id"),
            ulDom =   $(imgDom).parent().find("ul");
            ulDom.show(); 
            
        $.each(ulDom.find("li"), function(index, obj) {
            var aDom =    $(obj).find("a"),
                currentPort = aDom.html(),
                nodePort = currentNode + ":" +  currentPort;

            console.log("Port: " + nodePort + "  isUsed: " + jsIOUWebNetMap.isUsedPort(nodePort));

            if (jsIOUWebNetMap.isUsedPort(nodePort)) {
                console.log("used port add color");
                aDom.css("color", "green");
            } else {
                aDom.css("color", "black");
            }
        });   // end each
    }

    /**
     * <li> mouse down listener
     */
    function liMouseDownListener(li) {
        sourceNode = $(li).parent().parent().attr("id");
        sourcePort = $(li).find("a").html();
        console.log(sourceNode + ":" + sourcePort);
        if (jsIOUWebNetMap.isUsedPort(sourceNode + ":" + sourcePort)) {
            sourceNode = "";
            sourcePort = "";
            alert("对不起，这个接口已经使用！");
        }
    }

    function buildNodeHtml(device) {
        var deviceId = device.nodeId,
            devHtml = '<img  src="<?=URL_APPEDND?>/storage/images/devices/' + device.model + '_red.png" width="" height=""  title="' + device.nodeName + '">' +
                '<div class="name">'+ device.nodeName + '</div> <br />' +
                '<div id="nodeDescDiv_' + deviceId + '" class="nodeDesc"> ' + device.nodeDesc.desc + '</div>';
        devHtml += '<input id="nodeType_' + deviceId +  '" type="hidden" value="' + device.type + '">';
        devHtml += '<input id="model_' + deviceId +  '" type="hidden" value="' + device.model + '">';
        devHtml += '<input id="nodeName_' + deviceId + '" type="hidden" value="' + device.nodeName + '">';
        devHtml += '<input id="portsnumber' + deviceId + '" type="hidden" value="' + device.portsnumber + '">'; 
 
        return devHtml;
    }

    /**
     * device bind action
     * @param obj
     */
    function deviceBindAction(obj) {
        // li mark Source link
        obj.find("ul li.eq").each(function(i, e) {
            //  alert($(e).html());
            jsPlumb.makeSource($(e), {
                parent: $(e).parent().parent(),
                anchor: "Continuous",
                connector: [ "Straight" ],
                connectorStyle: { strokeStyle: 'rgb(243,230,18)', lineWidth: 3} 
            });
        });

        obj.find("ul li").bind("mousedown", function() {
            liMouseDownListener(this);
        });
 

        obj.find("img").bind("mouseover", function() {
            if (sourcePort !== "") {
                $(this).parent().find("ul").show();
                imageListener(this);
            }
        });
    }

    window.jsIOUWebNetMap = {
        version: 0.3, 
        addRouter : function(id, event) {
            //alert(' add device... ');
            jsIOUWebNetMap.createRouter(id, event);
           // var e = prepare(info.id);
            /*jsPlumb.draggable(info.id, {
                containment:"parent"
            });
            nodes.push(info.id);*/
        },
        hidePopWindows : function() { $("div.window ul").hide(); },
        resetCurrentPort : function() {
            sourceNode = "";
            sourcePort = "";
            targetNode = "";
            targetPort = "";
        },
        getNodes : function () { return nodes; },
        getNetMap : function() { return netMap; },
        addNetMap : function(map) {
            console.log(" ### adding map: " + map);
            netMap.add(map);
        },
        removeNetMap : function(map) {
    //拓扑连接线的删除
           // console.log(" ###removing map: " + map);
          //  netMap.removeValue(map);
        },
        logUsePort : function (port) {console.log(port + " add log..."); usedPorts.put(port, ""); },

        isUsedPort : function(port) {
            return usedPorts.containsKey(port);
        },

        loadNetMap: function(jsonStr) {
//            var jsonStr = '{"nodes":[{"nodeId":"cloud1","nodeName":"云1","nodeType":"cloud","offset":{"left":"209px","top":"76px"},"nodeDesc":{"desc":"324234","left":161,"top":138},"ports":{"s":"3","e":"2"}}],"netMap":[]} ';

            var json = $.parseJSON(jsonStr);

            if (json !== null) {
                $.each(json.nodes, function(i, device) {

                    var devObjIndex = getDevIndex(device.nodeType);
                    var devObjId = device.nodeId;

                    var id = devObjId;  // getDevIndex(device.type);

                    var d = document.createElement("div");
                    d.className = "window";

                    device.type = device.nodeType;
                    // device.nodeDesc

                    var devHtml = buildNodeHtml(device);
                    $(d).html(devHtml);
                    $(d).css({left: device.offset.left, top: device.offset.top});
                    $("#graphContainer").append(d);

                    $("#nodeDescDiv_" + id).css({left: device.nodeDesc.left, top: device.nodeDesc.top  });

                    deviceBindAction($(d));

                    addLiMouseUpListener(d);

                    // id = device.type + id;

                    var elementObj = jsPlumb.CurrentLibrary.getElementObject(d);
                    jsPlumb.CurrentLibrary.setAttribute(elementObj, "id", id);
                    // alert("id 2: " + id);

                    jsPlumb.draggable(id, {
                        containment: "parent"
                    });

                    jsPlumb.draggable("nodeDescDiv_" + id, {
                        //  containment:"parent"
                    });

                    nodes.push(id);
                });

                $.each(json.netMap, function(i, conn) {
                    var nodesLabel = conn.split(" "),
                        nodeLabel_1 = nodesLabel[0].split(":"),
                        nodeLabel_2 = nodesLabel[1].split(":"),
                        node1 = nodeLabel_1[0],
                        label1 = nodeLabel_1[1],
                        node2 = nodeLabel_2[0],
                        label2 = nodeLabel_2[1];

                    jsPlumb.connect({
                        source: node1,
                        target: node2,
                        //     paintStyle:{lineWidth:3,strokeStyle:'rgb(0,0,0)'},
                        overlays: [
                            [ "Label", {label: label1, location: 0.15, cssClass: "label"}],
                            [ "Label", {label: label2, location: 0.85, cssClass: "label"}]
                        ]
                    });

                    jsIOUWebNetMap.logUsePort(node1 + ":" + label1);
                    jsIOUWebNetMap.logUsePort(node2 + ":" + label2);
                    jsIOUWebNetMap.addNetMap(conn);
                });
            }
        } };
})();</script>
    <script type="text/javascript" src="js/jquery-impromptu.js"></script>
    <script type="text/javascript">
        /*global window, document, jQuery, $, alert, console, jsPlumb, HashMap, ArrayList:true, jsIOUWebNetMap:true*/
        document.onselectstart = function () {
            return false;
        };

        jsPlumb.bind("ready", function () {
            jsPlumb.importDefaults({
                Anchor: "Continuous",
                Connector: [ "Straight" ],
                Endpoint: "Blank",
                PaintStyle: { lineWidth: 2, strokeStyle: "#000000" },
                cssClass: "link"//,
            });
        });
        var createAble = false;
        $(document).ready(function () {
            var jsonStr ='<?=$device_str?>';
//            var jsonStr = '{"nodes":[{"nodeId":"1","nodeName":"DCR-1702","nodeType":"DCN防火墙","model":"DCFW-1800E-8G","portsnumber":"1","offset":{"left":"221px","top":"352px"},"nodeDesc":{"desc":"DCN防火墙 DCFW-1800E-8G","left":9,"top":66},"interface":{"0":"col"}},{"nodeId":"2","nodeName":"DCS-3950-26C-10101","nodeType":"DCN交换机","model":"DCRS-5950-24","portsnumber":"1","offset":{"left":"575px","top":"336px"},"nodeDesc":{"desc":"DCN交换机 DCRS-5950-24","left":4,"top":69},"interface":{"0":"col"}},{"nodeId":"3","nodeName":"router1","nodeType":"DCN流量整形网关","model":"DCFS-8000","portsnumber":"1","offset":{"left":"596px","top":"80px"},"nodeDesc":{"desc":"DCN流量整形网关 DCFS-8000","left":21,"top":75},"interface":{"0":"5555"}}],"netMap":["1:col 3:5555"]}';
             jsIOUWebNetMap.loadNetMap(jsonStr);
            var sidebarContainer = $("#sidebarContainer");
            var sidebarHtml = "",  i;
            <?php
            if(!$run_devices_count){ 
                if(!$sql_run_count){
                ?>
            sidebarHtml += '<a  class="action_botton" href="topoLooks.php?action=show&status=loading&id=<?=$topoid?>" title="加载试验" class="openColor"><img title="加载试验" width="15" height="15" src="images/play.png"><strong>加载试验</strong><\/a>&nbsp;&nbsp;&nbsp;';
            <?php  }} 
              $topoqu=mysql_query('select id from labs_document where labs_id='.$topoid.' order by id desc limit 1');  
              $topoarr=mysql_fetch_row($topoqu);
              $src = api_get_path ( WEB_CODE_PATH ) . 'courseware/link_goto.php?manuid='.$topoarr[0];               
              $document_url=WEB_QH_PATH . 'document_viewer.php?manu=abc&url='.urlencode($src);
              $run_labs_id=DATABASE::getval("select  `labs_name`  from  `labs_run_devices`  where `USERID`=".$USERID);
            ?>
            <?php if($sql_run_count){ ?>
            sidebarHtml += '<a  class="action_botton"  href="dynamic_map.php?action=show&id=<?=$run_labs_id?>"  title="返回已打开实验" target="_blank">返回已打开实验</a></b>&nbsp;';
            <?php } ?>
            sidebarHtml += '<a  class="action_botton" href="topoLooks.php?action=show&status=return&id=<?=$topoid?>" title="还原试验" class="openColor"><img title="还原试验" width="15" height="15" src="../themes/img/return_lab.png"><strong>还原实验</strong><\/a>&nbsp;&nbsp;&nbsp;';
            sidebarHtml += '<a  class="action_botton"  href="<?=$document_url?>"  title="打开实验手册" target="_blank">打开实验手册</a></b>&nbsp;&nbsp;&nbsp;<hr>';
            sidebarHtml += '<span class="title" style="color:red"><b>&nbsp;&nbsp;&nbsp;温馨提示：加载实验后，双击设备图标打开控制台!</b></span>';
            
            sidebarContainer.html(sidebarHtml);

            $("div#graphContainer").droppable({
                drop: function (event, ui) {
                    // alert("ui: " + ui.draggable.attr('type'));
                    if (createAble) {
                        jsIOUWebNetMap.addRouter(devices[ui.draggable.attr('type')], event);
                    }
                }
            }).bind("mousedown", function () {
                createAble = false;
            }).bind("mouseup", function () {
                        // alert("container clear");
                $("div.window ul").hide();
                jsIOUWebNetMap.resetCurrentPort();
            });

            // var curNodeIndex = 1 ,

            jsPlumb.bind("endpointHover", function (connection) {
                alert('hover');
            });
            jsPlumb.bind("beforeDrop", function (connection) {
                alert('beforeDrop');
            });

            jsPlumb.bind("mouseenter", function (conn) {
                alert("mouseenter : " + conn);
            });

            jsPlumb.bind("jsPlumbConnection", function (conn) {
                // alert("jsPlumbConnection");
                $("div.window ul").hide();
                var connection = conn.connection;
                var sourceId = connection.sourceId;
                var targetId = connection.targetId;
                console.log("build connection: source: " + sourceId + " targetId: " + targetId);
                if (sourceId === targetId) {  // it connect itself, return
                    jsPlumb.detach(conn);
                    return;
                }

                var overlays = connection.overlays;

                var map = sourceId + ":" + overlays[0].getLabel() + " " + targetId + ":" + overlays[1].getLabel();
                console.log(overlays[0].getLabel());
                jsIOUWebNetMap.logUsePort(sourceId + ":" + overlays[0].getLabel());
                jsIOUWebNetMap.logUsePort(targetId + ":" + overlays[1].getLabel());
                jsIOUWebNetMap.addNetMap(map);

                jsIOUWebNetMap.resetCurrentPort();
            });


            $("img#rountAddBtn").bind("click", function () {
                jsIOUWebNetMap.addRouter(devices[0]);
            });

            jsPlumb.bind("dblclick", function (c) {
                console.log(c);
                var overlays = c.overlays;
                var map = c.sourceId + ":" + overlays[0].getLabel() + " " + c.targetId + ":" + overlays[1].getLabel();
                console.log(map);

                jsIOUWebNetMap.removeUsePort(c.sourceId + ":" + overlays[0].getLabel());
                jsIOUWebNetMap.removeUsePort(c.targetId + ":" + overlays[1].getLabel());

                jsIOUWebNetMap.removeNetMap(map);
                jsPlumb.detach(c);
            });
        });
    </script>
</head>
<body>
    <div id="main">
        <span class="f-fc6 f-fs1" id="j-catTitle">
             <?php
             $router_category_name=  Database::getval("select `name` from `labs_labs` where `id` =".$topoid,__FILE__,__LINE__);
               if($router_category_name){
                   echo "课程名称：".$router_category_name;
               }
         ?></span>
        <div id="sidebarContainer"></div>
        <div id="graphContainer"></div>   
    </div>
</body>
</html>