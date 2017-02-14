$.fn.Drag=function (options) {
		var defaults={
			limit : window,//是否限制拖放范围,默认限制当前窗口内
			drop:false,//是否drop
			handle:false,//拖动手柄
			finish:function () {}//回调函数
		};
		var options=$.extend(defaults,options);
		this.X=0;//初始位置
		this.Y=0;
		this.dx=0;//位置差值
		this.dy=0;
		var This=this;
		var ThisO=$(this);//被拖目标
		var thatO;
		if (options.drop) {
			var ThatO=$(options.drop);//可放下位置
			ThisO.find('div').css({cursor:'move'});
			var tempBox=$('<div id="tempBox" class="grid window"></div>');
		}else {
			options.handle ? ThisO.find(options.handle).css({cursor:'move','-moz-user-select':'none'}) : ThisO.css({cursor:'move','-moz-user-select':'none'});
		}
		//拖动开始
		this.dragStart=function (e) {
			var cX=e.pageX;
			var cY=e.pageY;
			if (options.drop) {
				ThisO=$(this);
				if (ThisO.find('div').length!=1) {return}//如果没有拖动对象就返回
				This.X=ThisO.find('div').offset().left;
				This.Y=ThisO.find('div').offset().top;
				tempBox.html(ThisO.html());
				//ThisO.html('');
				$('#graphContainer').append(tempBox);
				tempBox.css({left:This.X,top:This.Y});
			}else {
				This.X=ThisO.offset().left;
				This.Y=ThisO.offset().top;
				ThisO.css({margin:0})
			}
			This.dx=cX-This.X;
			This.dy=cY-This.Y;
			if (!options.drop) {ThisO.css({position:'absolute',left:This.X,top:This.Y})}
			$(document).mousemove(This.dragMove);
			$(document).mouseup(This.dragStop);
			if ($.browser.msie) {ThisO[0].setCapture();}//IE,鼠标移到窗口外面也能释放
		}
		//拖动中
		this.dragMove=function (e) {
			var cX=e.pageX;
			var cY=e.pageY;
			if (options.limit) {//限制拖动范围

				//容器的尺寸
				var L=$(options.limit)[0].offsetLeft ? $(options.limit).offset().left : 0;
				var T=$(options.limit)[0].offsetTop ? $(options.limit).offset().top : 0;
				var R=L+$(options.limit).width();
				var B=T+$(options.limit).height();
				//获取拖动范围
				var iLeft=cX-This.dx, iTop=cY-This.dy;
				//获取超出长度
				var iRight=iLeft+parseInt(ThisO.innerWidth())-R, iBottom=iTop+parseInt(ThisO.innerHeight())-B;
				//alert($(window).height())
				//先设置右下, 再设置左上
				if(iRight > 0) iLeft -= iRight;

				if(iBottom > 0) iTop -= iBottom;
				if(L > iLeft) iLeft = L;
				if(T > iTop) iTop = T;
				if (options.drop) {
					tempBox.css({left:iLeft,top:iTop})
				}else {
					ThisO.css({left : iLeft,top : iTop})
				}
			}else {
				//不限制范围
				if (options.drop) {
					tempBox.css({left:cX-This.dx,top:cY-This.dy})
				}else {
					ThisO.css({left:cX-This.dx,top:cY-This.dy});
				}
			}

		}
		//拖动结束
		this.dragStop=function (e) {
			if (options.drop) {
				var flag=false;
				var cX=e.pageX;
				var cY=e.pageY;
				var oLf=ThisO.offset().left;

				var oRt=oLf+ThisO.width();
				var oTp=ThisO.offset().top;
				var oBt=oTp+ThisO.height();
				if (!(cX>oLf && cX<oRt && cY>oTp && cY<oBt)) {//如果不是在原位
					for (var i=0; i<ThatO.length; i++) {
						var XL=$(ThatO[i]).offset().left;
						var XR=XL+$(ThatO[i]).width();
						var YL=$(ThatO[i]).offset().top;
						var YR=YL+$(ThatO[i]).height();
						if (XL<cX && cX<XR && YL<cY && cY<YR) {//找到拖放目标 交换位置
							var newElm=$(ThatO[i]).html();
							$(ThatO[i]).html(tempBox.html());
							ThisO.html(newElm);
							thatO=$(ThatO[i]);
							tempBox.remove();
							flag=true;
							break;//一旦找到 就终止循环
						}
					}
				}
				if (!flag) {//如果找不到拖放位置,归回原位
					tempBox.css({left:This.X,top:This.Y});
					ThisO.html(tempBox.html());
					tempBox.remove();
				}
			}
			$(document).unbind('mousemove');
			$(document).unbind('mouseup');
			options.finish(e,ThisO,thatO);
			if ($.browser.msie) {ThisO[0].releaseCapture();}
		}
		//绑定拖动
		options.handle ? ThisO.find(options.handle).mousedown(This.dragStart) : ThisO.mousedown(This.dragStart);

		//IE禁止选中文本
		//document.body.onselectstart=function(){return false;}
	}