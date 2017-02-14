var rightMenu = $('<ul class="rightMenu"></ul>');
var getID = (function() {
    var i = 1;
    return function() {
        return i++;
    }
})();
function rgbaString(c, a) {
    return "rgba(" + c[0] + "," + c[1] + "," + c[2] + "," + a + ")";
};
function unique(target) {
    var result = [];
    loop: for (var i = 0, n = target.length; i < n; i++) {
        for (var x = i + 1; x < n; x++) {
            if (target[x] === target[i])
                continue loop;
        }
        result.push(target[i]);
    }
    return result;
}
function checkArrayObj(objs,name){
    var returnObj;
    $.each(objs,function(index, obj) {
        if(obj.name == name){
            returnObj = obj
            return false
        }
    });
    return returnObj
};
function by(name) {
    return function(o, p) {
        var a, b;
        if (typeof o === "object" && typeof p === "object" && o && p) {
            a = o[name];
            b = p[name];
            if (a === b) {
                return 0;
            }
            if (typeof a === typeof b) {
                return a < b ? 1 : -1;
            }
            return typeof a < typeof b ? 1 : -1;
        } else {
            throw ("error");
        }
    }
};

function init(options){
    $('body').on('click',function(){
        $('.rightMenu').remove()
    });
    $('body').on('contextmenu','.rightMenu',function(){
        return false
    })
    require.config({
        packages: [
            {
                name: 'zrender',
                location: 'javascripts/zrender/src',
                main: 'zrender'
            }
        ]
    });
    require(
        [
            "zrender",
            'zrender/Group',
            'zrender/tool/color',
            "zrender/animation/Animation",
            'zrender/shape/Line',
            'zrender/shape/Circle',
            'zrender/shape/Ring',
            'zrender/shape/Image'
        ], 
        function(zrender, Group, color, Animation, LineShape,CircleShape,RingShape,ImageShape){
            var zr = zrender.init( document.getElementById("Main") );
            var g = {};
            var id = null;
            var date = new Date();
            var lineW = 150;
            var c,nsx,nsy,nex,ney,w,h,fx,fy,side,sourceCx,sourceCy,sourceCw,sourceCh,targetCx,targetCy,targetCw,targetCh;
            var clipShape,line,source={},target={},sourceC={},targetC={},circleS={},ringS={},circleE={},ringE={};
            var sCacheObj = {},eCacheObj={};
            var trs = []; 
            var total = 0;
            var sourceDataObj = [],targetDataObj = [],typeDataObj = [];

            setInterval(ajaxser,5000);
            function ajaxser(){
                $.ajax({
                   url:'../ajax_server.php',
                dataType:'json',
                success:function(data){
                    for(var i in data){
                        fire(data[i]);
                    }
                }
                });
            }
            ajaxser();
            function eventsData(el){
                date.setTime(el.time);
                var tr = '<tr style="color:'+options.type[el.type].color+'"><td>'+date.toLocaleString()+'</td><td>'+el.source.name+'</td><td>'+el.source.ip+'</td><td>'+el.target.name+'</td><td>'+el.theway+'</td><td>'+el.port+'</td></tr>';
                if(trs.length>7){
                    trs.shift()
                }
                trs.push(tr)
                $('#events-data tbody').html(trs.join(''))
            };
            function dataRank(el,type){
                var name = null,
                    obj = null,
                    dataObj = null,
                    dataTrs = '',
                    dataTable = null,
                    theway = null;
                    port = null;
                port = el.port;
                theway = el.theway;
                if(type == 'source'){
                    name = el.source.name;
                    dataTable = $('#left-data tbody');
                    dataObj = sourceDataObj;
                }else if(type == 'target'){
                    name = el.target.name;
                    dataTable = $('#right-data tbody');
                    dataObj = targetDataObj;
                }else{
                    name = el.type;
                    dataTable = $('#bottom-right-data tbody');
                    dataObj = typeDataObj;
                }
                obj = checkArrayObj(dataObj,name);

                if(obj){
                    obj.count = obj.count +1;
                }else{
                    if(dataObj.length>10){
                        dataObj.pop()
                    }
                    dataObj.push({
                        name : name,
                        count : 1,
                        theway : theway,
                        port : port
                    })
                };

                dataObj.sort(by("count"));
                return dataObj
            };

            function onmouseoverFun(obj,x,y,color){
                obj.g.clearChildren();
                var targetCs = obj[x+","+y],
                    splitxy,x1,y1;
                targetCs.forEach(function(item){
                    splitxy = item.split(',');
                    var x1 = splitxy[0];
                    var y1 = splitxy[1];
                    var lines = new LineShape({
                        style: {
                            xStart: x,
                            yStart: y,
                            xEnd: x1,
                            yEnd: y1,
                            lineCape : 'round',
                            opacity : 0.7,
                            strokeColor: color,
                            lineWidth: 2
                        },
                        zlevel :1000
                    });
                    obj.g.addChild( lines );
                })
            };
            function onmouseoutFun(obj){
                obj.g.clearChildren();
            };
            function buildTargetC(el){
                if(el.target.type === 1){
                    targetCx = el.ex - 30;
                    targetCy = el.ey - 30;
                    targetCw = 60;
                    targetCh = 60;
                }else{
                    targetCx = el.ex - 50;
                    targetCy = el.ey - 50;
                    targetCw = 100;
                    targetCh = 100;
                };
                targetC[el.ex+","+el.ey] = new ImageShape({
                    style: {
                        image: 'images/c_'+el.target.type+'_'+el.target.color+'.png',
                        x: targetCx,
                        y: targetCy,
                        width:targetCw,
                        height:targetCh
                    },
                    clickable :true,
                    onmousedown :function(obj){
                        if(obj.event.which ===3){
                            if(el.target.rightLink && el.target.rightLink.length>0){
                                rightMenu.css({
                                    "left" : el.ex,
                                    "top":el.ey
                                });
                                var lis = '';
                                $.each(el.target.rightLink,function(index, el) {
                                    lis += '<li><a href="'+el.href+'" target="_blank">'+el.name+'</a></li>'
                                });
                                rightMenu.html(lis);
                                $('body .rightMenu').remove();
                                $('body').append(rightMenu.prop('outerHTML'))
                            }
                        }
                    },
                    onmouseover : function(){
                        onmouseoverFun(eCacheObj,el.ex,el.ey,options.mouseoverLineColor.target)
                    },
                    onmouseout : function(){
                        onmouseoutFun(eCacheObj)
                    }
                });
                circleE[el.ex+","+el.ey] = new CircleShape({
                    style: {
                        x: el.ex,
                        y: el.ey,
                        r: 1,
                        opacity :0.8,
                        color: '#fff',
                        shadowOffsetX : 0,
                        shadowOffsetY :0,
                        shadowColor :'#fff',
                        shadowBlur :6
                    }
                });
                ringE[el.ex+","+el.ey] = new RingShape({
                    style: {
                        x: el.ex,
                        y: el.ey,
                        r0: 4,
                        r: 7,
                        opacity :0.8,
                        color: '#fff',
                        shadowOffsetX : 0,
                        shadowOffsetY :0,
                        shadowColor :'#fff',
                        shadowBlur :16
                    }
                });
                zr.addShape(targetC[el.ex+","+el.ey]);
                zr.addShape(circleE[el.ex+","+el.ey]);
                zr.addShape(ringE[el.ex+","+el.ey]);
            }

            $('.toggle').on('click',function(){
                var _this = $(this),
                    box = _this.parents('.box'),
                    tableContainer = box.find('.table-container');
                if(!_this.data('flag')){
                    tableContainer.hide();
                    _this.data('flag',true)
                }else{
                    tableContainer.show()
                    _this.data('flag',false)
                }
            });
            function initTr(obj,type){
                if(type == 'type'){
                    return'<tr class="row" style="color: '+options.type[obj.name].color+'" count="'+obj.count+'">'+
                                '<td>'+
                                    '<div class="bar" style="width:'+obj.count/total*160+'px"></div>'+
                                '</td>'+
                                '<td>'+
                                    '<span class="numeric">'+obj.count+'</span>'+
                                '</td>'+
                                '<td>'+
                                    '<span class="port-circle" style="color:'+options.type[obj.name].color+'">●</span>'+
                                '</td>'+
                                '<td>'+obj.theway+'</td>'+
                                '<td>'+
                                    '<span class="numeric">'+obj.port+'</span>'+
                                '</td>'+
                            '</tr>'
                }else{
                    return '<tr class="row" style="color: red;" count="'+obj.count+'">'+
                            '<td>'+
                                '<div class="bar" style="width:'+obj.count/total*160+'px"></div>'+
                            '</td>'+
                            '<td>'+
                                '<span class="numeric">'+obj.count+'</span>'+
                            '</td>'+
                            '<td>'+obj.name+'</td>'+
                        '</tr>'
                }
            };
            function getTr(tbody,index){
                return tbody.find('tr').eq(index);
            };
            function buildTrs(obj,type,eleId){
                var tbody = $(eleId+' tbody');
                $.each(obj,function(index, el) {
                    var tr = getTr(tbody,index);
                    if(tr.length==0){
                        tbody.append(initTr(el,type));
                        (function(index){
                            setTimeout(function(){
                                getTr(tbody,index).css('color','white')
                            },100)
                        })(index)
                    }else{
                        if(tr.attr('count') != el.count){
                            getTr(tbody,index).prop('outerHTML',initTr(el,type));
                            (function(index){
                                setTimeout(function(){
                                    getTr(tbody,index).css('color','white')
                                },100)
                            })(index)
                        }
                    }
                });
            };

            eCacheObj.g = new Group();
            zr.addGroup(eCacheObj.g);
            sCacheObj.g = new Group();
            zr.addGroup(sCacheObj.g);

            function fire(data){
                data.forEach(function(el){
                    total++;
                    eventsData(el);
                    dataRank(el,'source');
                    dataRank(el,'target');
                    dataRank(el,'type');
                    id = getID();
                    g[id] = new Group();
                    zr.addGroup(g[id]);
                    
                    if(el.sx < el.ex){
                        nsx = el.sx
                        nex = el.ex
                        fx = 1
                    }else{
                        nsx = el.ex
                        nex = el.sx
                        fx = -1
                    }

                    if(el.sy < el.ey){
                        nsy = el.sy
                        ney = el.ey
                        fy = 1
                    }else{
                        nsy = el.ey
                        ney = el.sy
                        fy = -1
                    }
                    
                    w = ~~nex - ~~nsx;
                    h = ~~ney - ~~nsy;

                    side = Math.sqrt(w*w+h*h);

                    clipShape = new CircleShape({
                        style: {
                            x: nsx+w/2,
                            y: nsy+h/2,
                            r: side/2
                        }
                    });

                    g[id].clipShape = clipShape;

                    c = color.toArray(options.type[el.type].color);
                    
                    line = new LineShape({
                        position : [el.sx, el.sy],
                        scale : [1, 1],
                        style: {
                            xStart: 0,
                            yStart: 0,
                            xEnd: fx*(w/(side/lineW)),
                            yEnd: fy*(h/(side/lineW)),
                            lineCape : 'round',
                            opacity : 1,
                            strokeColor: color.getRadialGradient(0, 0, 0, 0, 50, 90, [[0,rgbaString(c,0)],[1,rgbaString(c,1)]]),
                            lineWidth: 3
                        }
                    });

                    g[id].addChild( line );

                    //攻击源图标
                    if(!sCacheObj.hasOwnProperty(el.sx+","+el.sy)){
                        sCacheObj[el.sx+","+el.sy] = [el.ex+","+el.ey];
                        if(el.source.type === 1){
                            sourceCx = el.sx - 30;
                            sourceCy = el.sy - 30;
                            sourceCw = 60;
                            sourceCh = 60;
                        }else{
                            sourceCx = el.sx - 50;
                            sourceCy = el.sy - 50;
                            sourceCw = 100;
                            sourceCh = 100;
                        };
                        sourceC[id] = new ImageShape({
                            style: {
                                image: 'images/c_'+el.source.type+'_'+el.source.color+'.png',
                                x: sourceCx,
                                y: sourceCy,
                                width:sourceCw,
                                height:sourceCh
                            },
                            clickable :true,
                            onmousedown :function(obj){
                                if(obj.event.which ===3){
                                    if(el.source.rightLink && el.source.rightLink.length>0){
                                        rightMenu.css({
                                            "left" : el.sx,
                                            "top":el.sy
                                        });
                                        var lis = '';
                                        $.each(el.source.rightLink,function(index, el) {
                                            lis += '<li><a href="'+el.href+'" target="_blank">'+el.name+'</a></li>'
                                        });
                                        rightMenu.html(lis);
                                        $('body .rightMenu').remove();
                                        $('body').append(rightMenu.prop('outerHTML'))
                                    }
                                }
                            },
                            onmouseover : function(){
                                onmouseoverFun(sCacheObj,el.sx,el.sy,options.mouseoverLineColor.source)
                            },
                            onmouseout : function(){
                                onmouseoutFun(sCacheObj)
                            }
                        });
                        circleS[id] = new CircleShape({
                            style: {
                                x: el.sx,
                                y: el.sy,
                                r: 1,
                                opacity :0.8,
                                color: '#fff',
                                shadowOffsetX : 0,
                                shadowOffsetY :0,
                                shadowColor :'#fff',
                                shadowBlur :6
                            }
                        });
                        ringS[id] = new RingShape({
                            style: {
                                x: el.sx,
                                y: el.sy,
                                r0: 4,
                                r: 7,
                                opacity :0.8,
                                color: '#fff',
                                shadowOffsetX : 0,
                                shadowOffsetY :0,
                                shadowColor :'#fff',
                                shadowBlur :16
                            }
                        });
                        zr.addShape(sourceC[id]);
                        zr.addShape(circleS[id]);
                        zr.addShape(ringS[id]);
                    }else{
                        sCacheObj[el.sx+","+el.sy].push(el.ex+","+el.ey)
                        sCacheObj[el.sx+","+el.sy] = unique(sCacheObj[el.sx+","+el.sy])
                    };

                    //遮罩层
                    source[id] = new RingShape({
                        style: {
                            x: el.sx,
                            y: el.sy,
                            r0: 0,
                            r: 3,
                            opacity : 1,
                            color : options.type[el.type].color
                        }
                    });
                    zr.addShape(source[id]);
                    zr.render();

                    (function (el,id){

                        zr.animate( line.id, "", false)
                        .when(options.animateTime.fire, {
                            position : [el.ex, el.ey]
                        })
                        .start().done(function(){
                            g[id].clearChildren();
                            zr.delGroup(g[id]);
                            delete g[id];

                            if(!eCacheObj.hasOwnProperty(el.ex+","+el.ey)){
                                eCacheObj[el.ex+","+el.ey] = [el.sx+","+el.sy];
                                buildTargetC(el)
                            }else{
                                if(targetC[el.ex+","+el.ey].style.image != 'images/c_'+el.target.type+'_'+el.target.color+'.png'){
                                    zr.delShape(targetC[el.ex+","+el.ey]);
                                    zr.delShape(circleE[el.ex+","+el.ey]);
                                    zr.delShape(ringE[el.ex+","+el.ey]);
                                    buildTargetC(el)
                                }
                                eCacheObj[el.ex+","+el.ey].push(el.sx+","+el.sy)
                                eCacheObj[el.ex+","+el.ey] = unique(eCacheObj[el.ex+","+el.ey])
                            }
                    
                            target[id] = new RingShape({
                                style: {
                                    x: el.ex,
                                    y: el.ey,
                                    r0: 0,
                                    r: 2,
                                    opacity : 1,
                                    color : options.type[el.type].color
                                },
                                zlevel :100
                            });
                            zr.addShape(target[id]);
                            (function(id){
                                zr.animate( target[id].id, "style", false)
                                .when(options.animateTime.target, {
                                    r0 : 30,
                                    r : 32,
                                    opacity : 0
                                })
                                .start().done(function(){
                                    zr.delShape(target[id]);
                                })
                            })(id)
                        });

                        zr.animate( source[id].id, "style", false)
                        .when(options.animateTime.source, {
                            r0 : 40,
                            r : 43,
                            opacity : 0
                        })
                        .start().done(function(){
                            zr.delShape(source[id]);
                            delete source[id];
                        })
                    })(el,id)
                });

                buildTrs(sourceDataObj,'source','#left-data');
                buildTrs(targetDataObj,'target','#right-data');
                buildTrs(typeDataObj,'type','#bottom-right-data');
            }
        }
    )
}