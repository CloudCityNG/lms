/**
 * ui.js v1.0
 * Copyright 2014, nsfocus
 * http://www.nsfocus.com
 */
String.prototype.hasString = function(o) { //If Has String
	if(typeof o == 'undefined'){
		return false;
	}
	else if (typeof o == 'object') {
		for (var i=0,n = o.length;i < n;i++) {
			if (!this.hasString(o[i])) return false;
		}
		return true;
	}
	else if (this.indexOf(o) != -1) return true;
}
String.prototype.breakWord = function(n,s) {
	if (!s) s = '<wbr/>';
	if(typeof this == 'string'){
		return this.replace(RegExp('(\\w{' + (n ? n : 0) + '})(\\w)','g'),function(all,str,char){
			return str + s + char;
		});
	}
}

if(!UI)
var UI = {
	tip : function() {
	UI.each(UI.GC("*"),function(o){
		if(UI.A(o,"tip"))UI.Tip.build(o)
		else return;
		});
	},
	tipBox : function(o,n,w,h) {
		if (UI.isString(o)) {
			n = o;
			o = document.documentElement;
		}
		if(!o) o = document.documentElement;
		var n = '.' + (n ? n : 'tipBox');
		var width = w ? w : '';
		var height =  h ? h : '';
		var name = '__tipBox';
		var tag = 'tipbox';
		var tip_w = 'w_tip';
		var tip_h = 'h_tip';
		var delay;

		UI.each(UI.GC(o,n),function(o){
			o.style.cursor="pointer"
			UI.A(o,tag)==null?UI.A(o,tag,o.title):'';
			UI.A(o,tip_w,width);
			UI.A(o,tip_h,height);
			o.title = '';
			if(UI.parent(o).className=="tools"||UI.parent(UI.parent(o)).className=="tools"){
				if(!top.UI["tools"]){
					top.UI["tools"]= top.UI.TipBox.init({name:'UI.tools'});
				}
			}
			var open = function(e){
				var t = UI.E(e).target,html = UI.A(t,tag),w_tip = UI.A(t,tip_w),h_tip = UI.A(t,tip_h);
				if (!html) {
					var parents = UI.parents(t,n.slice(1));
					if (parents.length > 0) {
						t = parents[0];
						html = UI.A(t,tag);
					}
					else return false;
				}
				delay = setTimeout(function(){
					var p = {html:html,target:t,width:w_tip,height:h_tip};//.breakWord(5)
					if (html.length > 700){p.large = true;}
					else {p.large=false;}
					if(UI.parent(o).className=="tools"||UI.parent(UI.parent(o)).className=="tools"){
						p.resize=true;

						if(top.UI["tools"]){
							top.UI["tools"].show(p);
						}
							
					}
					else if(top.UI[name]){
						top.UI[name].show(p);
					}
				},200);
				//alert(delay + 'add');//连续被触发？
				//if(UI.parent(o).className=="tools"){UI[name] = new UI.TipBox({name:'UI.' + name});}
			}
			UI.EA(o,'mouseover',open);
			UI.EA(o,'focus',open);
			UI.EA(o,'mouseout',function(e){
				clearTimeout(delay);
				//alert(delay);
			});
		});
		
		if(!top.UI[name]){
			top.UI[name]= top.UI.TipBox.init({name:'UI.' + name});
		}
	},
	select : function(n) {
		this.Select.build(n);
	},
	selectMulti : function(o,n,option) {
		var multiCtrl;
		if(UI.isObject(o) && !UI.isElement(o)){
			option=o;
			n= o.selector ;
			o= o.container;
		}
		else if (UI.isString(o)) {
			multiCtrl=UI.G(o);
		}
		
		if(!option){option={};}
	
		if(!o) o = document.documentElement;

		var n = '.' + (n ? n : 'selectMulti');
		if(!multiCtrl){
			multiCtrl = UI.GC(o,n);
		}
		else{
			multiCtrl=[multiCtrl];
		}
		
		UI.each(multiCtrl,function(o,i){
			var name = 'selectMulti_' + new Date().getTime() + i + '_' + parseInt(Math.random()*100+1);
			o.name = name;
			window[name] = new UI.SelectMulti(o,option);
		});
	},
	resize : function(n,config) {
		var arr = UI.isObject(n) ? [n] : UI.GC(n);
		UI.each(arr,function(o){
			//if(!UI.Browser.ie && 'TEXTAREA'.hasString(o.nodeName)) return;
			if('TEXTAREA,SELECT,INPUT,BUTTON,IMG'.hasString(o.nodeName)) {
				var tipBox = '',title = ''; //Hack tipBox
				if (UI.hasClass(o,'tipBox')) {
					UI.removeClass(o,'tipBox');
					tipBox = ' tipBox';
					title = ' title="' + o.title + '"';
					o.title = '';
					UI.EA(o,'click',function(e){
						UI.E(e).stop();
					})
				}
				UI.wrap('<span class="resize_box' + tipBox + '"' + title + '><b class="ico"></b><span></span></span>',o);
			}
			else {
				var B = UI.html('<b class="ico"></b>')[0];
				o.appendChild(B);
			}
			new UI.Resize(o,config);
		});
	},
	ajax : function(o) { // UI.ajax({type:'',url:'json.html',data:'',success:''})
	},
	get : function(url,o,f) { // UI.get('json.html',{name:''},function(data){ alert(data); })
		if (window.ActiveXObject){
			var xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else if (window.XMLHttpRequest){
			var xmlHttp = new XMLHttpRequest();
		}
		xmlHttp.onreadystatechange = function(){
			if (xmlHttp.readyState == 4){// && xmlHttp.status == 200
				f(xmlHttp.responseText);
				xmlHttp = null;
			}else{
				return false;
			}
		}
		if (o != undefined) {
			url += '?' + o;
		}
		xmlHttp.open('GET',url,true)
		xmlHttp.send(null);
	},
	getScript : function(s,call){ //Cache?
		var el = UI.DC('script');
		if (call) {
			el.onload =el.onreadystatechange=call;//
		}
		UI.A(el,'type','text/javascript');
		UI.A(el,'src',s);
		UI.GT(document,'head')[0].appendChild(el);
	},
	getCss : function(s,call){
		var el = UI.DC('link');
		if (call) {
			el.onload = call;
		}
		UI.A(el,'rel','stylesheet');
		UI.A(el,'type','text/css');
		UI.A(el,'href',s);
		UI.GT(document,'head')[0].appendChild(el);
	},
	evalScript : function(s){
		var r = this.regExp.script;
		var s = s.match(new RegExp(r,'img'));
		UI.each(s,function(e){
			eval(e.match(new RegExp(r,'im'))[1]);
		})
	},
	regExp : {
		script : '<script[^>]*>([\\S\\s]*?)<\/script>'
	},
	url : {
		encode : function (s) {
			return escape(this._utf8_encode(s));
		},
		decode : function (s) {
			return this._utf8_decode(unescape(s));
		},
		_utf8_encode : function (s) {
			s = s.replace(/\r\n/g,'\n');
			var utftext = '';
			for (var n = 0; n < s.length; n++) {
				var c = s.charCodeAt(n);
				if (c < 128) {
					utftext += String.fromCharCode(c);
				}
				else if((c > 127) && (c < 2048)) {
					utftext += String.fromCharCode((c >> 6) | 192);
					utftext += String.fromCharCode((c & 63) | 128);
				}
				else {
					utftext += String.fromCharCode((c >> 12) | 224);
					utftext += String.fromCharCode(((c >> 6) & 63) | 128);
					utftext += String.fromCharCode((c & 63) | 128);
				}
	 
			}
			return utftext;
		},
		_utf8_decode : function (utftext) {
			var string = '';
			var i = 0;
			var c = c1 = c2 = 0;
			while ( i < utftext.length ) {
				c = utftext.charCodeAt(i);
				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				}
				else if((c > 191) && (c < 224)) {
					c2 = utftext.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				}
				else {
					c2 = utftext.charCodeAt(i+1);
					c3 = utftext.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}
			}
			return string;
		}
	},
	parseUrl : function() {
		var url = document.location.href,v = {};
		if (!url.hasString('?')) return v;
		var str = url.split('?')[1].split('&');
		for (var i=0;i<str.length;i++) {
			var value = str[i].split('=');
			v[value[0]] = UI.Browser.ie ? value[1] : UI.url.decode(value[1]);
		}
		return v;
	},
	cookie : function(n,v,d) { //Cookie
		if (v == undefined) {
			var N = n + '=',C = document.cookie.split(';');
			for(var i=0;i<C.length;i++) {
				var c = C[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(N) == 0) return decodeURIComponent(c.substring(N.length,c.length));
			}
			return null;
		}
		else {
			var k = '';
			if (d) {
				var D = new Date();
				D.setTime(D.getTime() + d * 24 * 60 * 60 * 1000);
				k = '; expires=' + D.toGMTString();
			}
			document.cookie = n + '=' + v + k + '; path=/';
		}
	},
	drag : function(o,f,captrue,drag_pos) {
		var D = document,captrue = captrue != undefined ? captrue : true;
		if(drag_pos) 
		{
			D = window.top.document;
			var arr =  new Array();
			var frames_in = function(arr,on){
				arr.push(on);
				for(var i=0;i<on.frames.length;i++)	{
					if(typeof on.frames[i] != 'undefined'){
						frames_in(arr,on.frames[i]);
					}
				}
			}
			
			frames_in(arr,top);
		}
		UI.EA(o,'mousedown',function(e){
			if (f.start) f.start(e);//start

			if (captrue) {
				if(o.setCapture) o.setCapture();
				else if(window.captureEvents) window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);
			}
			if (f.drag) D.onmousemove = f.drag; //drag
			if(drag_pos){
				
				//document.onmouseup = D.onmouseup =
				UI.each(arr,function(nn){
 					UI.EA(nn.document.body,'mouseup',function(){
					if (captrue) {
						if(o.releaseCapture) o.releaseCapture();
						else if(window.captureEvents) window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);
					}
	
					if (f.stop) f.stop(e); //stop
					D.onmousemove = null;
					nn.onmouseup = null;
					if (f.call) f.call(e); //call
					})
				})
			}
			else{
				 D.onmouseup = function(){
					if (captrue) {
						if(o.releaseCapture) o.releaseCapture();
						else if(window.captureEvents) window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);
					}
	
					if (f.stop) f.stop(e); //stop
					D.onmousemove = null;
					D.onmouseup = null;
					if (f.call) f.call(e); //call
				}
			}
		})

	},
	animate : function(o,name,num,call) { // UI.animate(UI.G('news_bar'),'width',100)
		var delay = setInterval(function(){
			var cur = UI.C(o,name);
			if (name == 'opacity') {
				cur = cur*100;
				num *= 100;
			}
			else cur = ( cur=='auto' ? 0 : Number(cur.slice(0,-2)) );
			if (Math.abs(num - cur) < 3) {
				cur = num;
				clearInterval(delay);
				eval(call);
			}
			UI.C(o,name,(name != 'opacity' ? (cur + (num-cur)*0.4 ) + 'px' : (cur + (num-cur)*0.4 )/100 + ''));
		},40);
		return delay;
	},
	getX : function(o) {
		return o.offsetParent ? o.offsetLeft + UI.getX(o.offsetParent) : o.offsetLeft;
	},
	getY : function(o) {
		return o.offsetParent ? o.offsetTop + UI.getY(o.offsetParent) : o.offsetTop;
	},
	getStyle:function(o,cssprop){
		if (document.defaultView && document.defaultView.getComputedStyle) //Firefox
			return document.defaultView.getComputedStyle(o, "")[cssprop]
		else if (o.currentStyle) //IE
			return o.currentStyle[cssprop]
		else //try and get inline style
			return o.style[cssprop]
	},
	frameX : function(o) {
		return o.frameElement ? UI.getX(o.frameElement) + UI.frameX(o.parent) : 0;
	},
	frameY : function(o) {
		return o.frameElement ? UI.getY(o.frameElement) + UI.frameY(o.parent) : 0;
	},
	width : function(o) {
		return parseInt(o.offsetWidth);
	},
	rectWidth:function(o){
		if(o.getBoundingClientRect){
			var rect=o.getBoundingClientRect();
			return parseInt(rect.right-rect.left);
		}
		else{
			return parseInt(o.offsetWidth);
		}
	},
	height : function(o) {
		if(o.getBoundingClientRect){
			var rect=o.getBoundingClientRect();
			return parseInt(rect.bottom-rect.top);
		}
		return parseInt(o.offsetHeight);
	},
	offset : function(o){
		var docElem, win,
		box = { top: 0, left: 0 },
		elem = o,
		doc = elem && elem.ownerDocument;

		if ( !doc ) {
			return;
		}

		docElem = doc.documentElement;

		// Make sure it's not a disconnected DOM node
		if ( !jQuery.contains( docElem, elem ) ) {
			return box;
		}

		// If we don't have gBCR, just use 0,0 rather than error
		// BlackBerry 5, iOS 3 (original iPhone)
		if ( typeof elem.getBoundingClientRect !== typeof undefined ) {
			box = elem.getBoundingClientRect();
		}
		
		win = UI.isWindow( doc ) ?
		doc :
		doc.nodeType === 9 ?
			doc.defaultView || doc.parentWindow :
			false;

		return {
			top: box.top  + ( win.pageYOffset || docElem.scrollTop )  - ( docElem.clientTop  || 0 ),
			left: box.left + ( win.pageXOffset || docElem.scrollLeft ) - ( docElem.clientLeft || 0 )
		};
	},
	pageWidth : function() {
		return document.body.scrollWidth || document.documentElement.scrollWidth;
	},
	pageHeight : function() {
		return document.body.scrollHeight || document.documentElement.scrollHeight;
	},
	windowWidth : function() {
		var E = document.documentElement;
		return self.innerWidth || (E && E.clientWidth) || document.body.clientWidth;
	},
	windowHeight : function() {
		var E = document.documentElement;
		return self.innerHeight || (E && E.clientHeight) || document.body.clientHeight;
	},
	scrollX : function(o) {
		var E = document.documentElement;
		if (o) {
			var P = o.parentNode,X = o.scrollLeft || 0;
			if (o == E) X = UI.scrollX();
			return P ? X + UI.scrollX(P) : X;
		}
		return self.pageXOffset || (E && E.scrollLeft) || document.body.scrollLeft;
	},
	scrollY : function(o) {
		var E = document.documentElement;
		if (o) {
			var P = o.parentNode,Y = o.scrollTop || 0;
			if (o == E) Y = UI.scrollY();
			return P ? Y + UI.scrollY(P) : Y;
		}
		return self.pageYOffset || (E && E.scrollTop) || document.body.scrollTop;
	},
	scrollYFrame : function(o) {
		var E = document.documentElement;
		if (o) {

			var P = o.parentNode,Y = o.scrollTop || 0;
			if(!P) {
				var win = o.defaultView || o.parentWindow;
				P= win.frameElement;
			}
			if (o == E) Y = UI.scrollYFrame();
			return P ? Y + UI.scrollYFrame(P) : Y;
		}
		return self.pageYOffset || (E && E.scrollTop) || document.body.scrollTop;
	},
	scrollTo : function(o,x,y) {
		if (o == document.documentElement || o == document.body) {
			return window.scrollTo(x,y);
		}

	},
	hide : function(o) {
		if (UI.isString(o)) o = this.G(o);
		var curDisplay = this.C(o,'display');
		if (curDisplay != 'none') {
			o.__curDisplay = curDisplay;
		}
		o.style.display = 'none';
	},
	show : function(o) {
		if (UI.isString(o)) o = this.G(o);
		o.style.display = o.__curDisplay || '';
	},
	toggle : function(o) {
		if (UI.isString(o)) o = this.G(o);
		if (this.C(o,'display') == 'none') {
			this.show(o);
		}
		else this.hide(o);
	},
	hasClass : function(o,n){
		if(typeof o == 'undefined'){
			return false;
		}
		return o.className != o.className.replace(new RegExp('\\b' + n + '\\b'),'');
	},
	addClass : function(o,n){
		if(typeof o == 'undefined'){
			return false;
		}
		else if (!o.className) {
			o.className = n;
		}
		else if (this.hasClass(o,n)) {
			return false;
		}
		else o.className += ' ' + n;
	},
	removeClass : function(o,n){
		if(typeof o == 'undefined'){
			return false;
		}
		o.className = o.className.replace(new RegExp('\\b' + n + '\\b'),'');
	},
	toggleClass : function(o,n){
		if (this.hasClass(o,n)) this.removeClass(o,n);
		else this.addClass(o,n);
	},
	node : {
		ELEMENT : 1,
		ATTRIBUTE : 2,
		TEXT : 3,
		CDATA_SECTION : 4,
		ENTITY : 6,
		COMMENT : 8,
		DOCUMENT : 9,
		DOCUMENT_TYPE : 10
	},
	next : function(o) {
		var n = o.nextSibling;
		if (n == null) return false;
		return UI.isElement(n) ? n : this.next(n);
	},
	prev : function(o) {
		var n = o.previousSibling;
		if (n == null) return false;
		return UI.isElement(n) ? n : this.prev(n);
	},
	append : function(o,t) {
		t.appendChild(o);
	},
	prepend : function(o,t) {
		var first = t.firstChild;
		if (first) UI.before(o,first);
		else UI.append(o,t);
	},
	after : function(o,t) {
		var P = t.parentNode;
		if(P.lastChild == o) P.appendChild(o);
		else P.insertBefore(o,t.nextSibling);
	},
	before : function(o,t) {
		t.parentNode.insertBefore(o,t);
	},
	replace : function(o,t) {
		var P = t.parentNode;
		/*UI.before(o,t);
		P.removeChild(t);*/
		P.replaceChild(o,t);
	},
	swap : function(o,t) {
		
	},
	wrap : function(o,t) {
		if (UI.isString(o)) {
			var reg = o.match(/(<[^\/][^<]*>)/g),name = 'wrapObject___';
			var last = RegExp.lastMatch;
			o = o.replace(last,last + '<pre class="' + name + '"></pre>');
			var tmp = UI.html(o)[0];
			UI.before(tmp,t);
			UI.replace(t,UI.GC(tmp,'pre.' + name)[0]);
		}
		else {
			UI.before(o,t);
			t.appendChild(t);
		}
	},
	html : function(s) {
		var wrap = UI.DC('div'),tmp = [];
		wrap.innerHTML = s;
		UI.each(wrap.childNodes,function(o){
			tmp.push(o);
		});
		return tmp;
	},
	text : function text(el) {//待完善
		var str = [],e = el.childNodes;
		for (var i = 0,num = e.length;i < num;i++) {
			str.push(e[i].nodeType != 1 ? e[i].nodeValue : text(e[i]));
		}
		return str.join('');
	},
	parent : function(o,n) {
		if (UI.isArray(o)) {
			var tmp = [];
			UI.each(o,function(o){
				if ((n && UI.hasClass(o.parentNode,n)) || !n) tmp.push(o.parentNode);
			});
			return tmp;
		}
		return o.parentNode;
	},
	parents : function(o,n) {
		if (n) {
			var tmp = [],arr = UI.parents(o);
			UI.each(arr,function(o){
				if (UI.hasClass(o,n)) {
					tmp.push(o);
				}
			});
			return tmp;
		}
		var P = o.parentNode;
		return P.nodeName == 'HTML' ? [P] : [P].concat(UI.parents(P));
	},
	children : function(o,n) {
		var tmp = [];
		UI.each(o.childNodes,function(o){
			if (UI.isElement(o) && (!n || UI.hasClass(o,n))) tmp.push(o);
		});
		return tmp;
	},
	A : function(o,n,v) {
		if (v==undefined) {
			return o.getAttribute(n);
		}
		else o.setAttribute(n,v);
	},
	C : function(o,n,v) { //CSS
		if (v==undefined) { //Get Style
			if (o.currentStyle) {
				if (n=='opacity') {
					return o.style.filter.indexOf('opacity=') >= 0 ? (parseFloat( o.style.filter.match(/opacity=([^)]*)/)[1] )/100):'1';
				}
				return o.currentStyle[n];
			}
			else if (window.getComputedStyle) {
				n = n.replace (/([A-Z])/g, '-$1');
				n = n.toLowerCase ();
				return window.getComputedStyle (o, null).getPropertyValue(n);
			}
		}
		else {
			if (n=='opacity' && UI.Browser.ie) {
				o.style.filter = (o.filter || '').replace( /alpha\([^)]*\)/, '') + 'alpha(opacity=' + v * 100 + ')';
			}
			else o.style[n] = v;
		}
	},
	DC : function(n) { //Dom Create Element
		return document.createElement(n);
	},
	E : function(e) {
		if (e && e.clone) return e;
		e = window.event || e;
		return {
			clone : true,
			stop : function() {
				if (e && e.stopPropagation) e.stopPropagation();
				else e.cancelBubble = true;
			},
			prevent : function(){
				if (e && e.preventDefault) e.preventDefault();
				else e.returnValue = false;
			},
			target : e.target || e.srcElement,
			x : e.clientX || e.pageX,
			y : e.clientY || e.pageY,
			button : e.button,
			key : e.keyCode || e.which,
			shift : e.shiftKey,
			alt : e.altKey,
			ctrl : e.ctrlKey,
			type : e.type
		};
	},
	ieGetUniqueID: function(_elem){
      if (_elem === window) { return 'theWindow'; }
      else if (_elem === document) { return 'theDocument'; }
      else { return _elem.uniqueID; }
    },
	EA : function (o,n,f,capture) {
		if (UI.isString(o)) {
			var tmp = f;
			f = function(e) {
				eval(tmp);
			}
		}
		if(o.addEventListener) {
			o.addEventListener(n,f,false);
			return true;
		}
		else if(o.attachEvent) {
			var typeRef = "_" + n;
			if (!o[typeRef]) {
				o[typeRef] = [];
			}
			var key = '{FNKEY::obj_' + UI.ieGetUniqueID(o) + '::evt_' + n + '::fn_' + f + '}';
			var fn = o[typeRef][key];

			fn = function () {
				f.apply(o, arguments);
			};

			o[typeRef][key] = fn;
			o.attachEvent('on' + n, fn);

			// attach unload event to the window to clean up possibly IE memory leaks
			window.attachEvent('onunload', function () {
				//ie8 has no privilege
				try{
					o.detachEvent('on' + n, fn);
				}
				catch(e){}
			});

			key = null;
			/*
			var r = o.attachEvent('on'+n,function(){
			f.apply(o,arguments);
			});
			 */
			return true;
		}
		else return false;
	},
	ER : function (o,n,f) {
		if(o.removeEventListener) {
			o.removeEventListener(n,f,false);
			return true;
		}
		else if(o.detachEvent) {
			var typeRef = "_" + n;
			var key = '{FNKEY::obj_' + UI.ieGetUniqueID(o) + '::evt_' + n + '::fn_' + f + '}';
			if(!o[typeRef]){
				return true;
			}
			var fn = o[typeRef][key];
			if (typeof fn != 'undefined'){
				o.detachEvent('on' + n, fn);
				delete o[typeRef][key];
			}

			key = null;
			return true;
		}
		else return false;
	},
	ET : function(e) { //Event Target
		return e.target||e.srcElement;
	},
	G : function(n) {
		return document.getElementById(n);
	},
	GT : function(o,n) {
		if(!o) return false;
		
		return o.getElementsByTagName(n);
	},
	GC : function (o,n) { //getElementByClassName -> UI.GC('a.hide.red')
		if(!o) return false;
		
		var arr,t,l,el = [];
		if (arguments.length == 1) {
			arr = o.split('.');
			o = document;
		}
		else arr = n.split('.');
		t = arr[0] == '' ? '*' : arr[0];
		arr.shift();
		l = this.GT(o,t);
		for (var i=0 in arr) {
			arr[i] = '&' + arr[i] + '&';
		}
		for(var i = 0,n = l.length;i < n;i++) {
			if(UI.isString(l[i].className))
			{
			  var c = '&' + l[i].className.replace(/ /g,'& &') + '&';
			  if(c.hasString(arr)) el.push(l[i]);
			}
		}
		/* //Another Method (Spend More Time)
		for(var i = 0,n = l.length;i < n;i++) {
			var m = l[i].className.match(new RegExp('\\b' + arr.join('\\b|\\b') + '\\b','g'));
			if(m && m.length == arr.length) el.push(l[i]);
		}
		*/
		return el.length > 0 ? el : false;
	},
	isDate : function(o) {
		if (!o) return o;
		if (o.getTime && o.getFullYear && o.getTimezoneOffset && o != 'NaN' && o != 'Invalid Date') return true;
	},
	cloneDate : function(v) {
		if (!v) return v;
		d = new Date();
		d.setTime(v.getTime());
		return d;
	},
	formatDate : function(v,f) {
		var F = f.replace(/\W/g,',').split(','),format = ['yyyy','MM','dd','hh','mm','ss','ww'];
		var date = {
			y : v.getFullYear(),
			M : v.getMonth() + 1,
			d : v.getDate(),
			h : v.getHours(),
			m : v.getMinutes(),
			s : v.getSeconds(),
			w : v.getDay()
		};
		for (var i = 0,num = F.length;i < num;i++) {
			var o = F[i];
			for (var j = 0;j < 7;j++) {
				var S = format[j].slice(-1);
				if (o.hasString(S)) {
					if (S == 'w' && date[S] == 0) date[S] = 7; //Sunday
					if (o.hasString(format[j])) {
						f = f.replace(RegExp(format[j],'g'),this.addZero(date[S]));
					}
					else f = f.replace(RegExp(format[j].slice(format[j].length/2),'g'),date[S]);
				}
			}
		}
		return f;
	},
	parseDate : function(v,f) {
		if (!f) f = 'yyyy-MM-dd';
		f = f.replace(/\W/g,',').split(',');
		v = v.replace(/\D/g,',').split(',');
		var y = 2000,M = 0,d = 1,h = 0,m = 0,s = 0,D = true;
		UI.each(f,function(o,i){
			if (v[i] == '' || isNaN(v[i])) D = false;
			if (o.hasString('y')) y = Number(v[i]);
			if (o.hasString('M')) M = Number(v[i]) - 1;
			if (o.hasString('d')) d = Number(v[i]);
			if (o.hasString('h')) h = Number(v[i]);
			if (o.hasString('m')) m = Number(v[i]);
			if (o.hasString('s')) s = Number(v[i]);
			if (o.hasString('w')) s = Number(v[i]);
		});
		if (!D) return false;
		return new Date(y,M,d,h,m,s);
	},
	isWindow: function( obj ) {
		return obj != null && obj == obj.window;
	},
	isArray : function(o) {
		return o !== null && UI.isObject(o) && 'splice' in o && 'join' in o;
	},
	isElement : function(o) {
		return o && o.nodeType == 1;
	},
	isFunction : function(o) {
		return typeof o == 'function';
	},
	isNumber : function(o) {
		return typeof o == 'number';
	},
	isObject : function(o) {
		return typeof o == 'object';
	},
	isString : function(o) {
		return typeof o == 'string';
	},
	isUndefined : function(o) {
		return typeof o == 'undefined';
	},
	addZero : function(o,n) {
		var tmp = [],l = String(o).length;
		if (!n) n = 2;
		if (l < n) {
			for (var i = 0;i < n - l;i++) {
				tmp.push(0);
			}
		}
		tmp.push(o);
		return tmp.join('');
	},
	trim : function(o) {
		return o.replace(/^\s+|\s+$/g,'');
	},
	random : function(a,b) {
		if (a == undefined) a = 0;
		if (b == undefined) b = 9;
		return Math.floor(Math.random() * (b - a + 1) + a);
	},
	has : function(o,v) {
		for (var i = 0,n = o.length;i < n;i++) {
			if (o[i] == v) return true;
		}
		return false;
	},
	each : function(o,f) {
		if(UI.isUndefined(o[0])){
			for (var key in o){
				if(!UI.isFunction(o[key])) f(key,o[key]);
			}
		}
		else{
			for(var i = 0,n = o.length;i < n;i++){
				if(!UI.isFunction(o[i])) f(o[i],i);
			}
		}
	},
	map : function(o,f) {
		if (UI.isString(f)) f = eval('(function(a,i) { return ' + f + '})');
		var tmp = [];
		UI.each(o,function(o,i){
			var v = f(o,i);
			if (UI.isArray(v)) {
				tmp = tmp.concat(v);
			}
			else tmp.push(v);
		});
		return tmp;
	},
	grep : function(o,f) {
		if (UI.isString(f)) f = eval('(function(a,i) { return ' + f + '})');
		var tmp = [];
		UI.each(o,function(o,i){
			if (f(o,i)) tmp.push(o);
		});
		return tmp;
	},
	merge : function(A,B) {
		var tmp = [];
		if (B) { //Merge A + B
			UI.each(B,function(o,i){
				if (!UI.has(A,o)) tmp.push(o);
			});
			return A.concat(tmp);
		}
		else { //Merge Same Value For A
			UI.each(A,function(o,i){
				if (!UI.has(tmp,o)) tmp.push(o);
			});
			return tmp;
		}
	},
	apart : function(A,B) {
		var tmp = [];
		UI.each(A,function(o,i){
			if (!UI.has(B,o)) tmp.push(o);
		});
		return tmp;
	},
	sort : {
		number : function(a,b) {
			return a - b;
		},
		numberDesc : function(a,b) {
			return b - a;
		},
		string : function(a,b) {
			return a.localeCompare(b);
		},
		stringDesc : function(a,b) {
			return b.localeCompare(a);
		}
	},
	ready : function(f) {
		if (UI.ready.done) return f();
		if (UI.ready.timer) {
			UI.ready.ready.push(f);
		}
		else {
			//UI.EA(window,'load',UI.isReady);
			UI.ready.ready = [f];
			UI.ready.timer = setInterval(UI.isReady,13);
		}
	},
	isReady : function() {
		if (UI.ready.done) return false;
		if (document && document.getElementsByTagName && document.getElementById && document.body) {
			clearInterval(UI.ready.timer);
			UI.ready.timer = null;
			for (var i = 0;i < UI.ready.ready.length;i++)
				UI.ready.ready[i]();
			UI.ready.ready = null;
			UI.ready.done = true;
		}
	},
	param:function( o, pre ){
		var undef, buf = [], key, e = encodeURIComponent;
		for(key in o){
			undef = o[key]== 'undefined';
			var val = undef ? key : o[key];
			buf.push("&", e(key), "=", (val != key || !undef) ? e(val) : "");
		}
		if(!pre){
			buf.shift();
			pre = "";
		}
		return pre + buf.join('');
	},
	Browser : (function(){
		var b = {},i = navigator.userAgent;
		b.ie6 = i.hasString('MSIE 6') && !i.hasString('MSIE 7') && !i.hasString('MSIE 8');
		b.ie8 = !i.hasString('MSIE 6') && !i.hasString('MSIE 7') && i.hasString('MSIE 8');
		b.ie7 = !i.hasString('MSIE 6') && i.hasString('MSIE 7') && !i.hasString('MSIE 8');
		b.ie = i.hasString('MSIE');
		b.tt = i.hasString('TencentTraveler');
		b.opera = i.hasString('Opera');
		b.safari = i.hasString('WebKit');
		return b;
	})(),
	GetRoot:function(js){
		var root,reg;
		js = UI.isArray(js)?js:[js];
		UI.each(js,function(o){
			if(reg){
				reg+="|";
			}
			if(!reg){
				reg ='(.*)'+o+'$';
			}
			else{
				reg += '(.*)'+o+'$';
			}
		})

		reg=new RegExp(reg);
		UI.each(UI.GT(document,"script"),function(o){
			var _script = o.src.match(reg);

			if (_script !== null) {
				root = _script[0];
			}
			return root;
		});
		
		return root;
	},
	hashCode:function(str){
		if (Array.prototype.reduce){
			return str.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);              
		} 
		var hash = 0;
		if (str.length === 0) return hash;
		for (var i = 0; i < str.length; i++) {
			var character  = str.charCodeAt(i);
			hash  = ((hash<<5)-hash)+character;
			hash = hash & hash; // Convert to 32bit integer
		}
		return hash;
	},
	Objequals : function(x,y) { 
		var p; 
		for(p in y) { 
			if(typeof(x[p])=='undefined') {return false;} 
		} 
		for(p in y) { 
			if (y[p]) { 
				switch(typeof(y[p])) { 
					case 'object': 
						if (!UI.Objequals(x[p],y[p])) { return false; } break; 
					case 'function': 
					if (typeof(x[p])=='undefined' || 
						(p != 'equals' && y[p].toString() != x[p].toString())) 
						return false; 
						break; 
					default: 
						if (y[p] != x[p]) { return false; } 
				} 
			} else { 
				if (x[p]) 
					return false; 
				} 
			} 
			for(p in x) { 
				if(typeof(y[p])=='undefined') {return false;} 
			} 
			return true; 
	} 
};


UI.Base64 = {
    /**
     * 此变量为编码的key，每个字符的下标相对应于它所代表的编码。
     */
    enKey: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/',
    /**
     * 此变量为解码的key，是一个数组，BASE64的字符的ASCII值做下标，所对应的就是该字符所代表的编码值。
     */
    deKey: new Array(
        -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
        -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
        -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
        52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
        -1,  0,  1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14,
        15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
        -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
        41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1
    ),
    /**
     * 编码
     */
    encode: function(src){
        //用一个数组来存放编码后的字符，效率比用字符串相加高很多。
        var str=new Array();
        var ch1, ch2, ch3;
        var pos=0;
       //每三个字符进行编码。
        while(pos+3<=src.length){
            ch1=src.charCodeAt(pos++);
            ch2=src.charCodeAt(pos++);
            ch3=src.charCodeAt(pos++);
            str.push(this.enKey.charAt(ch1>>2), this.enKey.charAt(((ch1<<4)+(ch2>>4))&0x3f));
            str.push(this.enKey.charAt(((ch2<<2)+(ch3>>6))&0x3f), this.enKey.charAt(ch3&0x3f));
        }
        //给剩下的字符进行编码。
        if(pos<src.length){
            ch1=src.charCodeAt(pos++);
            str.push(this.enKey.charAt(ch1>>2));
            if(pos<src.length){
                ch2=src.charCodeAt(pos);
                str.push(this.enKey.charAt(((ch1<<4)+(ch2>>4))&0x3f));
                str.push(this.enKey.charAt(ch2<<2&0x3f), '=');
            }else{
                str.push(this.enKey.charAt(ch1<<4&0x3f), '==');
            }
        }
       //组合各编码后的字符，连成一个字符串。
        return str.join('');
    },
    /**
     * 解码。
     */
    decode: function(src){
        //用一个数组来存放解码后的字符。
        var str=new Array();
        var ch1, ch2, ch3, ch4;
        var pos=0;
       //过滤非法字符，并去掉'='。
        src=src.replace(/[^A-Za-z0-9\+\/]/g, '');
        //decode the source string in partition of per four characters.
        while(pos+4<=src.length){
            ch1=this.deKey[src.charCodeAt(pos++)];
            ch2=this.deKey[src.charCodeAt(pos++)];
            ch3=this.deKey[src.charCodeAt(pos++)];
            ch4=this.deKey[src.charCodeAt(pos++)];
            str.push(String.fromCharCode(
                (ch1<<2&0xff)+(ch2>>4), (ch2<<4&0xff)+(ch3>>2), (ch3<<6&0xff)+ch4));
        }
        //给剩下的字符进行解码。
        if(pos+1<src.length){
            ch1=this.deKey[src.charCodeAt(pos++)];
            ch2=this.deKey[src.charCodeAt(pos++)];
            if(pos<src.length){
                ch3=this.deKey[src.charCodeAt(pos)];
                str.push(String.fromCharCode((ch1<<2&0xff)+(ch2>>4), (ch2<<4&0xff)+(ch3>>2)));
            }else{
                str.push(String.fromCharCode((ch1<<2&0xff)+(ch2>>4)));
            }
        }
       //组合各解码后的字符，连成一个字符串。
        return str.join('');
    }
};

UI.Encoder = {
	utf16to8: function(str) {
	    var out, i, len, c;

	    out = "";
	    len = str.length;
	    for(i = 0; i < len; i++) {
	        c = str.charCodeAt(i);
	        if ((c >= 0x0001) && (c <= 0x007F)) {
	            out += str.charAt(i);
	        } else if (c > 0x07FF) {
	            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
	            out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));
	            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
	        } else {
	            out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));
	            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
	        }
	    }
	    return out;
	},
	utf8to16: function(str) {
	    var out, i, len, c;
	    var char2, char3;

	    out = "";
	    len = str.length;
	    i = 0;
	    while(i < len) {
	        c = str.charCodeAt(i++);
	        switch(c >> 4){
	          case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
	            // 0xxxxxxx
	            out += str.charAt(i-1);
	            break;
	          case 12: case 13:
	            // 110x xxxx   10xx xxxx
	            char2 = str.charCodeAt(i++);
	            out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
	            break;
	          case 14:
	            // 1110 xxxx  10xx xxxx  10xx xxxx
	            char2 = str.charCodeAt(i++);
	            char3 = str.charCodeAt(i++);
	            out += String.fromCharCode(((c & 0x0F) << 12) |
	                                           ((char2 & 0x3F) << 6) |
	                                           ((char3 & 0x3F) << 0));
	            break;
	        }
	    }
	
	    return out;
	}
};
function exchange(ID) {
	obj=document.getElementById(ID);
	if (obj.style.display == "none") {
		obj.style.display = "block";
	}
	else {
		obj.style.display = "none";
	}
}
function show_search(ID,e) {
	obj=document.getElementById(ID);
	if (obj.style.display == "none") {
		obj.style.display = "block";
		e.getElementsByTagName('IMG')[1].className="ico dot_up";
	}
	else {
		obj.style.display = "none";
		e.getElementsByTagName('IMG')[1].className="ico dot_down";
	}
}
function switchImg(img) {
	var tmp = img.src;
	img.src = img.getAttribute('d-src');
	img.setAttribute('d-src',tmp);
}
function show_level(ID,e) {
	obj=document.getElementById(ID);
	if (obj.className== "hide") {
		obj.className= "show";
		e.innerHTML=e.innerHTML.replace(/&gt;&gt;/,"&lt;&lt;");
	}
	else {
		obj.className= "hide";
		e.innerHTML=e.innerHTML.replace(/&lt;&lt;/,"&gt;&gt;");
	}
}
function no_toggle(oid,id) {
	if (jQuery('#'+oid).hasClass('ico')){
		if (jQuery('#'+oid).hasClass('plus')) {
			jQuery('#'+oid).removeClass('plus');
			jQuery('#'+oid).addClass('minus');
		}
		else {
			jQuery('#'+oid).removeClass('minus');
			jQuery('#'+oid).addClass('plus');	
		}
	}
		jQuery('#'+id).toggle();
};
function set_frame_height(o) {
	setInterval(function() {
		var iframeid = o;
		var dataheight;
		var _height = 1800;
		if (o) {
			var doc= o.contentDocument || (o.contentWindow?o.contentWindow.document:null);
			if(!doc || !doc.body || !doc.documentElement){return;}
			
			html = doc.documentElement;

			var height = Math.max( doc.body.scrollHeight, doc.body.offsetHeight, 
							   html.clientHeight, html.scrollHeight, html.offsetHeight );
			
			dataheight = o.getAttribute('data-height');			   
			if(height){
				_height =dataheight ? height: height + 50;
			}

			if(!dataheight || dataheight !==_height)
			{
				o.height = _height;
				o.setAttribute('data-height',_height);
			}
		}
	},300);
}



;(function(jQuery,window){

if(typeof jQuery=='undefined'){
	window['setFrameHeight'] = function(){};
	return;
}


//注册ajax全局事件，ajax结束时自动设置iframe高度
jQuery( document ).ajaxStop(function() {
	if(typeof document !== 'undefined') {
 		var win = document.defaultView || document.parentWindow;
 		if (win.frameElement) {
			win.frameElement.width = "100%";
			getOnloadOption(win.frameElement,"ajax");
 		}
	}
});


function getOnloadOption(o,caller){
	var loadFunction = jQuery(o).attr('onload');
	if(loadFunction && typeof loadFunction == "function"){
		loadFunction = loadFunction.toString();
		var regLoadFn = /setFrameHeight\D*\)/;
		var resLoadFn = loadFunction.match(regLoadFn);
		if(resLoadFn){
			loadFunction = resLoadFn[0];
		}else{
			loadFunction = null;
		}
	}
	if (loadFunction && loadFunction.toString().indexOf('setFrameHeight')>=0) {
		var regMain = /^(setFrameHeight\s*\(\w+\,)([\w+\,\{?\D+\}?\:?]{1,})(\))$/;
		var aResultMain = loadFunction.match(regMain);
		if(aResultMain && aResultMain[2]){
			var tempArr = aResultMain[2].split(',');
			var regSub = /([^\{\}]*,)*(\{{1}[^\{]*:?[^\}]*\}{1}$)*/;
			var aResultSub = aResultMain[2].match(regSub);//
			if(!!aResultSub[2]){
				var iframeOption = eval("("+aResultSub[2]+")");		
			}
			if(!aResultSub[2]){		
				
				if(tempArr.length>1){//(this,true,true)
					setFrameHeight(o,tempArr[0],tempArr[1],{},caller);
				}	
					
			}else if(typeof iframeOption == "object"){
				if(tempArr.length>1){//(this,true,true,{})
					setFrameHeight(o,tempArr[0],tempArr[1],iframeOption,caller);
				}else{//(this,{})
					setFrameHeight(o,iframeOption,caller);
				}	
			}
		}else{//(this)
			setFrameHeight(o,undefined,undefined,{},caller);	
		}
	}	
}

function setParentFrameSize(o,option){
	try{
		var contentWindow = o.contentWindow || o.contentDocument.ownerWindow;
	}
	catch(e){return;}
	
	if(contentWindow.parent && contentWindow.parent.frameElement &&
	 contentWindow.parent.parent && contentWindow.parent.parent.frameElement){
		getOnloadOption(contentWindow.parent.frameElement,"child");
		//setFrameHeight(contentWindow.parent.frameElement,option.setHeight,option.setWidth,option,'child');
	}
}

/*
	@param type :whether from resize event
 */
function setFrameSize(o,option,type){
	var win = (o.contentWindow || o.contentDocument.ownerWindow),doc;

	if(win.document) doc=win.document;
	if(!doc) return;
	
	if(!doc || !doc.body || !doc.documentElement) return {width:null,height:null};
	html = doc.documentElement;

	if(!window.top) return;
	if(window.top.sizeTime){
		clearTimeout(window.top.sizeTime);
	}
	window.top.sizeTime = setTimeout(function(){
		try{
			if(typeof option.scrollContainer == 'string'){
				option.scrollContainer = win[option.scrollContainer];
			}
			
			var scrollTop = jQuery(option.scrollContainer).scrollTop();
			if(!scrollTop && option.scrollContainer && option.scrollContainer.document){
				jQuery('body',option.scrollContainer.document).scrollTop();
			}
			
			o.height = 0;

			var height = Math.max( doc.body.scrollHeight, doc.body.offsetHeight, 
					   html.clientHeight, html.scrollHeight, html.offsetHeight);

			if(height && option.setHeight){
				o.height = height + 'px';
				o.style['scroll']='auto';
				o.setAttribute('data-height',height);

				if(option.scrollContainer && scrollTop){
					jQuery(option.scrollContainer).scrollTop(scrollTop);				
				}
			}
		}catch(e){
			return;
		}
		
		
		try{
			if(option.setWidth){
				o.width = "100%";
			};

			setTimeout(function(){
				var width = Math.max(/*o.clientWidth, doc.body.scrollWidth,*/ doc.body.offsetWidth, 
				   html.clientWidth, html.scrollWidth, html.offsetWidth );

				/*if(width && option.setWidth && !(type=='resize' && !+"\v1" && !document.querySelector)){*/
				if(width && option.setWidth){
					o.width = width+"px";
					o.setAttribute('data-width',width);
				}
			},(!+"\v1" && !document.querySelector)?800:0);
			
		}catch(e){
			return;
		}	
				
		if(!option.onlySelf){
			setParentFrameSize(o,option);	
		}
	},400);
}

function initOption(setHeight,setWidth,options){
	var option={};
	
	if(!options || !options.setWidth){
		if(typeof setWidth ==='undefined' || !setWidth || setWidth === 'undefined' || setWidth =='false'){
			option.setWidth = false;
		}
		else{
			option.setWidth = true;
		}
	}
	
	if(!options || !options.setHeight){
		if(typeof setHeight !== 'undefined' && (setHeight === false || setHeight === 'false')){
			option.setHeight = false;
		}
		else{
			option.setHeight = true;
		}
	}
	
	if(!options || !options.resetbody){
		if(typeof resetbody !== 'undefined' && (resetbody === false || resetbody === 'false')){
			option.resetbody = false;
		}
		else{
			option.resetbody = true;
		}
	}
	
	return jQuery.extend(option,options);
}

//ie6 7设置主内容区iframe所属div的高度
//用于修复iframe设置height=100%导致高度过高的问题
function setWrapSize(o){
	jQuery(o).parent().css({height:jQuery(o.contentWindow.parent.document).height() - 85});
}


//TODO:增加对宽度的调整
/*
options={
	trigger:['mouseup','click'],
	onlySelf:true
}

*/
function setFrameHeight(o,setHeight,setWidth,options,caller) {
	o=o.frameElement || o;

	if (o) {
		if(jQuery.isPlainObject(setHeight)){
			options=setHeight;
			caller = setWidth;
			setWidth = null;
			setHeight=null;
		}
		var option=initOption(setHeight,setWidth,options);
		try{
			var win = (o.contentWindow || o.contentDocument.ownerWindow),doc;
			if (win.document) doc=win.document;
		}
		catch(e){return;}

		if(!o.width) o.width='100%';	
		if(!o.height) o.height='100%';
		
		if(doc){
			var noResetBody = jQuery(o).attr("notresetbody");
			//重置body的padding,margin
			if(noResetBody != "true"){
				if(doc.body && option.resetbody){
					jQuery(doc.body).addClass('is-resetbody');
				}else{
					jQuery(doc.body).removeClass('is-resetbody');	
				}
			}
			
			//当前页面中的子iframe已经设置了setFrameHeight则onload时不进行高度调整
			//TODO:后续验证多个iframe情况
			var isChildSetSize=false;
			jQuery('iframe',doc).each(function(){
				var onloadFunc = jQuery(this).attr('onload');
				if(onloadFunc && onloadFunc.toString().indexOf('setFrameHeight')>=0){
					isChildSetSize=true;
					return false;
				}
			});

			if(isChildSetSize && (!caller || (caller && caller!=='child' && caller!=='ajax'))){
				return;
			}
			
			if(!option.setHeight && !option.setWidth){
				return;
			}
			
			setFrameSize(o,option);
			
			var triggerEvents='';
			if(options && options.trigger){
				jQuery.each(options.trigger,function(){
					triggerEvents+=this +' ';
				})
			}
			if(triggerEvents.length>0){
				jQuery(doc).live(triggerEvents,function(e){
					if(!jQuery(e.target).attr('js-notrigger')){
						setFrameSize(o,option);
					}
				});
			}
			//TODO：resize事件会导致死循环
			if(options && options.resize){
				top.document.body.onresize = function(){
					setFrameSize(o,option,'resize');
				}
			}
		}
	}
};

window['setFrameHeight'] = setFrameHeight;

}(window.jQuery,window))


//set top-left framework .iframe_pos height in ie6
;(function($){
	if(!$) return;
	$(function(){
		var iev = $.browser.version;
		var isIELower = $.browser.msie&&(iev=='6.0' || iev=='7.0');
		if(isIELower && $('.iframe_pos')[0]){
			var headerHeight = $('.header').height();
			var topMenuHeight = $('.top_menu_div').hasClass('hide')?0:$('.top_menu_div').height();
			var hHeight = parseInt(headerHeight)+parseInt(topMenuHeight);

			$('.iframe_pos').css('height',$(window).height()-hHeight);
			$(window).resize(function(){
				$('.iframe_pos').css('height',$(window).height()-hHeight);
			})
		}
	})
}(window.jQuery));

/**
 * author:chenping@intra.nsfocus.com 
 * date:2013-04-26 
 * depends 
 * 		- jquery 1.5+
 * 
 */

(function($, window) {
	"use strict";
	if(typeof $ =='undefined'){
		return;
	}
	
	$.fn.pane = function(con) {
		if (typeof con === 'string') {
			var fn = $.pane.getMethod(con);
			if (!fn) {
				throw ("pane - 未找到方法：" + con);
			}

			var args = $.makeArray(arguments).slice(1);
			
			return fn.apply(this, args);
		}

		return this.each(function() {
			this.options = $.extend({}, $.fn.pane.defaults, con || {});
			this.status = this.options.status;
			this.container = $(this).find($(this.options.container)).first();
			this.refresh = $(this).find('.refresh').first();
			this.resize = $(this).find($(this.options.resize)).first();

			_initActions.call(this);

			//设置肤色
			if($(this).attr('class','pane '+this.options.skin));
			
			if (this.options.content) {
				var content = this.options.content;
				if (typeof this.options.content == 'string') {
					// 判断是否是html片段
					if (_isHtmlFrag(this.options.content)) {
						content = $(this.options.content);
					} else {
						content = $(this).find($(this.options.content)).first();
						if (content[0].id == this.container[0].id) {
							content = content.html();
						}
					}
				}

				this.container.empty();
				this.container.append(content);
			}

			if (this.options.maxWidth) {
				var maxWidth = this.options.maxWidth, body = this;

				_checkWidth(body, maxWidth);
				$(window).on('resize', function() {
							_checkWidth(body, maxWidth);
						});
			}

			$.fn.pane.Methods.load.call(this);
			_setStatus.call(this);

		});
	};
	
	$.fn.pane.defaults = {
		url : null,
		refresh : false,
		resize : '.title',
		status : 'max',
		content : null,
		container : '.content',
		skin : 'blue',
		icon : null,
		width : '100%',
		maxWidth : null,
		loadtext: '数据加载中...'
	};
	

	// 私有方法
	function _checkWidth(body, maxWidth) {
		var w_window = $(window).width(), hack = parseInt($(body).parent()
				.css('padding-right'))
				* 2;

		if (/^((-|\+)?\d{1,2}(\.\d+)?|100)%$/.test(maxWidth)) {
			maxWidth = parseFloat(maxWidth) / 100 * w_window;
		}
		$(body)
				.width((w_window < maxWidth ? w_window : maxWidth) - hack
						+ 'px');
	}

	function _initActions() {
		var _self = this;
		this.resize.on('click', function(e) {
					$.fn.pane.Methods.onResize.call(_self, e);
				});
		if (this.options.refresh) {
			this.refresh.on('click', function() {
						$.fn.pane.Methods.onReload.call(_self);
					});
		}
	};
	function _setStatus() {
		if (this.status == 'min') {
			this.resize.addClass('min');
		} else {
			this.resize.removeClass('min');
		}
	};

	// 判断是否是html片段
	function _isHtmlFrag(str) {	
		// var htmlReg = /^(?:(<[\w\W]+>)[^>]*|#([\w-]*))$/;
		var htmlReg = /^(?:(<[\w\W]+>)[^>]*)$/;
		return htmlReg.test(str);
	}

	function _initForms(el) {
		var form_nodes = $(el).find('form');
		if (form_nodes == null) {
			return;
		}
		$(form_nodes).each(function(index, form_node) {
			if (form_node.onsubmit != null) {
				return true;
			}

			$(form_node).on("submit", function(event) {
				event.preventDefault();
				_processResult(el,$(this).attr('action'),$(this).serialize());
			});
		});
	};

	function _processResult(container,url,data) {
		$(container).load(url,data,function(response,status,xhr){
			//如果新加载的页面绑定了onload事件，则执行
			if(typeof window.onload == 'function'){
				window.onload();
			}
			
			_initForms(container);
		});
	}

	// 公共方法
	$.fn.pane.Methods = {
		load : function() {
			if (this.options.url) {
				var container = this.container;
				container.html(this.options.loadtext);
				_processResult(container,this.options.url);
			}

			if (this.options.refresh) {
				this.refresh.removeClass('hide');
			} else {
				this.refresh.addClass('hide');
			}

			if (this.status == 'min') {
				$.fn.pane.Methods.minimize.call(this);
			} else {
				$.fn.pane.Methods.maximize.call(this);
			}
		},
		onReload : function(event) {
			this.status = 'max';
			$.fn.pane.Methods.load.call(this);
		},
		onResize : function(event) {
			if (!event || event.target != this.refresh[0]) {
				if (this.status == 'min') {
					$.fn.pane.Methods.maximize.call(this);
				} else {
					$.fn.pane.Methods.minimize.call(this);
				}
			}
		},
		maximize : function() {
			this.container.show();
			this.status = 'max';
			_setStatus.call(this);
		},
		minimize : function() {
			this.container.hide();
			this.status = 'min';
			_setStatus.call(this);
		}
	};

	$.pane = $.pane || {};
	$.extend($.pane, {
				getAccessor : function(obj, expr) {
					var ret, p, prm = [], i;
					if (typeof expr === 'function') {
						return expr(obj);
					}
					ret = obj[expr];
					if (ret === undefined) {
						try {
							if (typeof expr === 'string') {
								prm = expr.split('.');
							}
							i = prm.length;
							if (i) {
								ret = obj;
								while (ret && i--) {
									p = prm.shift();
									ret = ret[p];
								}
							}
						} catch (e) {
						}
					}
					return ret;
				},
				getMethod : function(name) {
					return this.getAccessor($.fn.pane.Methods, name);
				},
				extend : function(methods) {
					$.extend($.fn.pane, methods);
					if (!this.no_legacy_api) {
						$.fn.extend(methods);
					}
				}
			});

	// 兼容cavy.js中的接口
	if (!window.Cavy) {
		window.Cavy = {};
	}

	if (!window.Cavy.Pane) {
		window.Cavy.Pane = function(url, buttons, container, options) {
			var op = {};
			op.url = url;
			var refresh = $(buttons.refresh);
			if (refresh.hasClass('hide')) {
				op.refresh = false;
			} else {
				op.refresh = true;
			}
			op.resize = buttons.resize;
			op.container = container;
			if (options.content) {
				if (typeof options.content == 'string') {
					if (_isHtmlFrag(options.content)) {
						options.content = $(options.content);
					} else {
						options.content = '#' + options.content;
					}
				} else {
					options.content = '#' + $(options.content).selector;
				}
			}

			op = $.extend({}, op, options);

			return $(container).parent().pane(op);
		};
	}

	if (!$.fn.getElementsByClassName) {
		$.fn.getElementsByClassName = function(name) {
			var selector = $(this).selector;
			var eles = [];
			$('#' + selector).find('.' + name).each(function() {
						eles.push($(this));
					});
			return eles;
		};
	}
}(window.jQuery, window));
UI.colorSel=function(id,sel,text){
	var _obj = document.getElementById(id)||null;
	if(!_obj)return;
	UI.addClass(_obj,'color_sel');
	if(UI.trim(_obj.innerHTML)==""){
	_obj.innerHTML='<input type="'+(text?'text':'hidden')+'" class="text" value="255,121,121"/> '+(sel?'<a href="##" class="color_focus"></a> ':'')+'<div class="color_cont"><a href="##" class="input_color" rel="255,121,121"></a><a href="##" class="input_color" rel="165,215,112"></a><a href="##" class="input_color" rel="61,170,237"></a><a href="##" class="input_color" rel="255,159,63"></a><a href="##" class="input_color" rel="75,202,126"></a<a href="##" class="input_color" rel="80,137,231"></a><a href="##" class="input_color" rel="255,206,58"></a><a href="##" class="input_color" rel="116,208,240"></a><a href="##" class="input_color" rel="174,136,202"></a><a href="##" class="input_color" rel="167,84,208"></a></div>';		
	};
	var _cont=UI.GC(_obj,"div.color_cont")[0],_input=_obj.firstChild,_color=UI.GC(_cont,'a.input_color'),_focus=UI.GC(_obj,".color_focus")[0];
	while(_input.nodeType==3){
	_input = _input.nextSibling
	};
	var _val=_input.value;
	if(_focus){
		var __sp=UI.DC("span");
		__sp.style.cssText="background-color:rgb("+_val+")";
		UI.append(__sp,_focus);
		_focus.sp=__sp;
		_focus.onclick=function(e){
			UI.E(e).stop(e);
			if(_cont.style.display=="none"){_cont.style.display="block"}
			else{_cont.style.display="none"}
		};
		_focus.onfocus=function(){
			this.blur();
		};
		UI.addClass(_cont,"color_toggle");
		_cont.style.display="none";
		if(UI.Browser.ie6){
			var ifram=document.createElement("Iframe");
			ifram.style.cssText="width:100%;height:100%;position:absolute;z-index:-1;left:0;top:0;filter:alpha(opacity=0);";
			_cont.appendChild(ifram);
		}
	};
	if(_input.type=="text"){
		_cont.style.left="83px";
		if(_focus){
			if(UI.Browser.ie)
			_input.onpropertychange=function(){
				var _vl=this.value;
				_focus.sp.style.cssText="background-color:rgb("+_vl+")";
			}
			else
			UI.EA(_input,'input',function(e){
				var _vl=this.value;
				_focus.sp.style.cssText="background-color:rgb("+_vl+")";
			});
		}
	};
	UI.each(_color,function(o){
		var _cl=UI.A(o,"rel"),_sp=UI.DC("span");
		_sp.style.cssText="background-color:rgb("+_cl+")";
		UI.append(_sp,o);
		if(_val && _cl==UI.trim(_val)){UI.addClass(o,"selected");_obj.cur=o;};
		UI.EA(o,'click',function(e){
		UI.E(e).stop(e);
		if(_obj.cur==o) return;
		var clv=UI.A(o,"rel");
		_input.value=clv;
		if(_obj.cur) UI.removeClass(_obj.cur,"selected");
		UI.addClass(o,"selected");_obj.cur=o;
		if(_focus && _focus.sp){_focus.sp.style.cssText="background-color:rgb("+clv+")";_cont.style.display="none";};
		return false;	
		});
		o.onfocus=function(){
			this.blur();
		};
	});
};
/*
* 	for ie8 bug:if create a UI.Dialog in iframe,and refresh the iframe, UI.Dialog.getCurrent().close() will trigger "can't execute code from a freed script" error.
	we solve the problem as:
	if o.widget != null, don't create a new DialogWidget, instead, use the o.widget as the instance of DialogWidget.
* 
*/
if(UI && !UI.Dialog){
	UI.Dialog = function(o) {
		//兼容Cavy.Dialog调用方式
		if (arguments.length == 3) {
			o = jQuery.extend({
				title: arguments[0]
			}, arguments[2]);
		}
		o = o || {};

		var _name = 'dialogUI_' + new Date().getTime() + (UI.Dialog.__num++);
		o.name = o.name ? o.name + '_' + _name : _name;
		o.dialogCaller = this;
		o.framePath = getFramePath(window);

		this.name = o.name;

		if (top.window && !top.window[this.name] && !o.widget) {
			top.window[this.name] = new top.UI.DialogWidget(o);
		}

		if (o.widget) {
			this.name = o.widget.__option.name;
			top.window[this.name].__option.dialogCaller = this;
		}

		this.show = function(o) {
			this._getDialog().show(o);
		}

		this.close = function() {
			this._getDialog().hide();
		};

		this.hide = function() {
			this._getDialog().hide();
		};

		this.fillByAjax = function(url, mtd) {
			this._getDialog().fillByAjax(url);
		}

		this.fillByForm = function(url, mtd, frm) {
			if (UI.isString(frm)) {
				frm = '#' + frm;
			}
			var para = jQuery(frm).serializeObject();
			this._getDialog().fillByAjax(url, para);
		}

		this.fillByUrl = function(url) {
			this._getDialog().__option.url = url;
		};

		this.submitInnerForm = function(frm) {
			var url = frm.action;
			if (UI.isString(frm)) {
				frm = '#' + frm;
			}
			var para = jQuery(frm).serializeObject();

			this._getDialog().submitInnerForm(url, para);
		};

		this.getDialogWidget = this._getDialog = function() {
			return top.window[this.name];
		}

		this._iframe = this._getDialog()._iframe;

		this.callBack = function(fn) {
			this._getDialog().emitter(fn);
		};

		//get current frameElement path
		function getFramePath(o, path) {
			if (!path) path = [];
			if (o.frameElement) {
				for (var i = 0; i < o.parent.frames.length; i++) {
					if (o.parent.frames[i].frameElement == o.frameElement) {
						path.push(i);
						break;
					}
				}
				return getFramePath(o.parent, path);
			} else {
				return path;
			}
		}
	}

	UI.Dialog.__num = 0;
	UI.Dialog._current = [];
	UI.Dialog.getCurrent = function() {
		var dialog = null;
		if (top.UI.Dialog._current.length > 0) {
			var cur = top.UI.Dialog._current[top.UI.Dialog._current.length - 1];
			try {
				cur.dialog._getDialog();
				dialog = cur.dialog;
			} catch (e) {
				dialog = new UI.Dialog({
					widget: cur.widget
				});
			}
		}
		return dialog;
	};
}

if(!UI.Dialog.Text){
	 UI.Dialog.Text = {
		loading:'数据加载中...'
	};
}

UI.DialogWidget = function(o) {
	var _reloadUrlHandler;
	var _self = this;
	this.__option = {};
	for(var p in o){
		if(p=='width' || p=='height'){
			o[p] = parseInt(o[p]);	
		}
		this.__option[p] = o[p];
	}

	this.__initOption = o;
	//Default Size
	size.call(this,o);
	
	//Dom
	this._body = UI.DC('div');
	this._body.className = 'dialog2';
	this._body.innerHTML = (UI.Browser.ie6 ? '<iframe src="javascript:false;" class="cover_select"></iframe>' : '') + '<div class="bg"></div><div style="width:' + o.width + 'px;height:' + o.height + 'px;" class="wrap"><div class="title">' + o.title + '</div><a class="close ' + (o.close!=false ? '' : 'hide') + '" href="javascript:void(0)" onfocus="this.blur();" title="Close" tabindex="-1"></a><a class="help tipBox ' + (o.help != undefined ? '' : 'hide') + '" href="javascript:void(0)" onfocus="this.blur();" title="'+o.help+'" tabindex="-1"></a><a class="maxwin ' + (o.maxoff==true ? '' : 'hide') + '" href="javascript:void(0)" onfocus="this.blur();" tabindex="-1"></a><div class="cont"><div class="loading"><span>'+UI.Dialog.Text.loading+'</span></div><iframe allowtransparency="true" src="' + (o.url != undefined ? o.url : '')+ '" style="height:' + o.height + 'px;display:none;" scrolling="auto" frameborder="no" onload="if (UI.A(this,\'src\') != \'\') { this.style.display=\'block\';this.previousSibling.style.display=\'none\';UI.EA(' + o.name + '._iframe.contentWindow.document,\'keyup\',top.' + o.name + '.key); };" class="iframe"></iframe><div class="data"></div></div><b class="cor_1"></b><b class="cor_2"></b><b class="cor_3"></b><b class="cor_4"></b><div class="resize"></div></div><div class="border"></div>';
	
	this._bg = UI.GC(this._body,'div.bg')[0];
	this._wrap = UI.GC(this._body,'div.wrap')[0];
	this._title = UI.GC(this._body,'div.title')[0];
	this._close = UI.GC(this._body,'a.close')[0];
	this._help = UI.GC(this._body,'a.help')[0];
	this._maxwin = UI.GC(this._body,'a.maxwin')[0];
	this._cont = UI.GC(this._body,'div.cont')[0];
	this._iframe = UI.GC(this._body,'iframe.iframe')[0];
	this._data = UI.GC(this._body,'div.data')[0];
	this._resize = UI.GC(this._body,'div.resize')[0];
	this._border = UI.GC(this._body,'div.border')[0];
	this._loading = UI.GC(this._body,'div.loading')[0];	
	
	this.__name = o.name;
	this.__append = false;
	this.autoHeightMax = 420;
	this.__close = this.__option.close == undefined ? true : this.__option.close;
	
	var wrap = this._wrap,border = this._border,name = o.name;
	
	//Status
	this.checkStaus = function(o) {
		var bgWidth = Number(top.UI.pageWidth()) +
			parseFloat(UI.getStyle(top.document.body, 'marginLeft')) +
			parseFloat(UI.getStyle(top.document.body, 'marginRight'));

		this._bg.style.cssText += ';width:' + bgWidth + 'px;height:' +
			Math.max(Number(top.UI.windowHeight()), Number(top.UI.pageHeight())) + 'px;';
		if (!this._titleHeight) {
			this._titleHeight = this._title.offsetHeight;
		}
		try {
			var ch = o.height - this._titleHeight;
			this._cont.style.height = ch + 'px';
			var iframeHeight = parseFloat(this._wrap.style.height) - this._titleHeight;
			if (isNaN(iframeHeight) || !iframeHeight) {
				iframeHeight = ch;
			}
			this._iframe.style.height = iframeHeight + 'px';

			var btnDataDiv = UI.GC(this._data, 'div.button');
			var conDataDiv = UI.GC(this._data, 'div.data_cont');
			if (btnDataDiv && conDataDiv) {
				//16 = div.data_cont's padding-top + padding-bottom
				conDataDiv[0].style.height = (ch - UI.height(btnDataDiv[0]) - 16) + 'px';
			}

			this._cont.style.height = this._data.style.height = ch + 'px';

			if (this._data.innerHTML == "") {
				this._data.style.height = "0px";
			}

			var btnDiv = UI.GC(this._iframe.contentWindow.document.body, 'div.button');
			var conDiv = UI.GC(this._iframe.contentWindow.document.body, 'div.content');
			if (conDiv && btnDiv) {
				setTimeout(function() {
					conDiv[0].style.height = (ch - UI.height(btnDiv[0])) + 'px';
				}, 50);
			}
		} catch (e) {};

		if (o.move == false) {
			this.__move = false;
			this._title.style.cursor = 'default';
		} else if (this.__move == undefined || o.move) {
			this.__move = true;
			this._title.style.cursor = '';
		}
		if (o.resize == false) {
			this.__resize = false;
			this._resize.style.display = 'none';
		} else if (this.__resize == undefined || o.resize) {
			this.__resize = true;
			this._resize.style.display = '';
		}
		if (o.html) {
			UI.hide(this._loading);
		}
	};
	
	//定义对话框时，如果有参数url则自动打开对话框
	if (_self.__option.url) {
		UI.EA(window,'load',function(){
			document.body.appendChild(_self._body);
			_self.__append = true;
			_self.__display = true;

			_self.checkStaus.call(_self,_self.__option);
			_setDialogPosition.call(_self,o);

			cacheCurrentDialog(_self);
		});
	};
	//Event
	this.key = function(e) {
		e= window.event || e;
		if(!e) return;
		switch(UI.E(e).key) {
			case 27:
				var cacheLen = top.UI.Dialog._current.length;
				if(cacheLen>0){
					top.UI.Dialog._current[cacheLen-1].dialog._getDialog().hide();
				}
				else if (window[name].__display) window[name].hide();
				break;
		}
	};
	this.resizeBg = function(){
		var body = document.body,
		html = document.documentElement;
		var cacheLen = top.UI.Dialog._current.length;
		var _curDialog = _self;
		
		if(cacheLen>0){
			_curDialog = top.UI.Dialog._current[cacheLen-1].dialog._getDialog();
		}
		
		var height = Math.max( body.scrollHeight, body.offsetHeight, 
						   html.clientHeight, html.scrollHeight, html.offsetHeight );

		if (_curDialog.__display) {
			_curDialog._bg.style.cssText += ';width:' + Number(UI.pageWidth()-5) + 
						'px;height:' + Number(height-5)+ 'px;';
			_curDialog._body.style.height = height + 'px';

			if(!_curDialog.__option.fixSize){
				_curDialog.reset({},true);
			}
			else{
				_setDialogPosition.call(_curDialog, _curDialog.__option);
			}
		};
		if(_curDialog.maxWin){
			wrap.style.cssText = 'margin:0;top:2px;left:2px;width:' + 
						Number(UI.pageWidth()-5) + 'px;height:' + 
						Number(UI.pageHeight()-5)+ 'px;';
			window[name].checkStaus({height:wrap.offsetHeight});
		};
	};
	
	//close dialog
	this._close.onclick=function(e){
		//fix espc bug50957 todo:delete next version
		if(UI.Dialog.fixespc){
			_self._fixESPCCloseBug();
		}

		_self.hide();	
	}
	
	this._fixESPCCloseBug = function(){
		if(_self.__name.indexOf('dialog1')>=0||
		   _self.__name.indexOf('dialog2')>=0||
		   _self.__name.indexOf('dialog3')>=0||
		   _self.__name.indexOf('dialog4')>=0||
		   _self.__name.indexOf('dialog5')>=0){
			if(top.dialog && UI.isFunction(top.dialog.hide)){
				flag=true;
				top.dialog.hide();
			}
		}
	}
	
	this._title.onmousedown = function(e) { //Move
		if (window[name].__move) {
			var event = window.event || e;
			var _x = event.clientX - parseInt(wrap.style.left);
			var _y = event.clientY - parseInt(wrap.style.top);
			var w = UI.windowWidth(),h = UI.windowHeight(); //Kill Bug
			if(event.preventDefault){
				event.preventDefault();
			};
			
			UI.addClass(wrap,'move');
			document.onmousemove = function(e) {
				var ev = window.event || e;
				var E = UI.E(e);
				if (!UI.Browser.ie && (E.x < 0 || E.y < 0 || E.x > w || E.y > h)) {
					return false;
				}

				wrap.style.top = ev.clientY - _y + 'px';
				wrap.style.left = ev.clientX - _x + 'px';
				
				return false;
			};
		
			document.onmouseup = function() {
				UI.removeClass(wrap,'move');
				this.onmousemove = null;
				document.onmouseup = null;
				
				return false;
			};
			
			return false;
		}
	};
	
	this._maxwin.onclick=function(e){
		if(!_self.maxWin){
			_self.maxWin=wrap.style.cssText;
			wrap.style.cssText = 'margin:0;top:2px;left:2px;width:' + 
				Number(UI.pageWidth()-5) + 'px;height:' + 
				Number(UI.pageHeight()-5)+ 'px;';
			window[name].checkStaus({height:wrap.offsetHeight});
			if(_self.__resize){
				_self._resize.style.display = 'none';
			};
			if(_self.__move){
				_self.__move=false;
			};
			UI.addClass(this,"midwin");
		}
		else{
			wrap.style.cssText=_self.maxWin;
			_self.maxWin="";
			if(_self.__resize){
				_self._resize.style.display = '';
			};
			UI.removeClass(this,"midwin");
			_self.__move=true;
		}
		window[name].checkStaus({height:wrap.offsetHeight});
	}
	this._title.ondblclick = function(e) { //Restore
		var o = _self.__option;
		if(o && o.dblClick === false){return false;}
		window[name].reset(o,true);
	}
	this._resize.onmousedown = function(e) { //Resize
		if (window[name].__resize) {
			var width = parseInt(UI.C(wrap,'width')),
				height = parseInt(UI.C(wrap,'height')),
				top = parseInt(UI.getY(wrap)),
				left = parseInt(UI.getX(wrap));
			if (!UI.Browser.ie || document.compatMode == 'CSS1Compat') {
				width -= 2;
				height -= 2;
			}
			border.style.cssText = 'top:' + top + 'px;left:' + left + 'px;width:' + 
					width + 'px;height:' + height + 'px;display:block;';
			window[name]._body.style.cursor = 'se-resize';
			var event = window.event || e;
			var _x = event.clientX;
			var _y = event.clientY;
			if(event.preventDefault){
				event.preventDefault();
			}
			UI.addClass(wrap,'move');
			document.onmousemove = function(e) {
				var event = window.event || e,_Y = event.clientY - _y,_X = event.clientX - _x;
				var min_X = (150 - width)/2,min_Y = (100 - height)/2;
				if (_Y < min_Y) _Y = min_Y;
				if (_X < min_X) _X = min_X;
				if((top-_Y)<5||(left-_X)<5)return;
				var css = 'height:' + (_Y*2 + height) + 'px;width:' + 
						(_X*2 + width) + 'px;top:' + (top-_Y) + 'px;left:' + 
						(left-_X) + 'px;display:block;';
				if (UI.Browser.ie6 && document.compatMode == 'BackCompat') {
					setTimeout(function(){
						border.style.cssText = css;
					},10);
				}
				else border.style.cssText = css;
				return false;
			};
			document.onmouseup = function() {
				window[name]._wrap.style.cssText = 'margin:0;left:' + 
						border.style.left + ';top:' + 
						border.style.top + ';width:' + 
						border.offsetWidth + 'px;height:' + 
						border.offsetHeight + 'px;';
				window[name].checkStaus({height:border.offsetHeight});
				//50ms  in order to have enough time to calculate border.offsetHeight
				setTimeout(function(){
					border.style.display = 'none';
				},50);
				window[name]._body.style.cursor = '';
				this.onmousemove = null;
				document.onmouseup = null;
				UI.removeClass(wrap,'move');
			};
			return false;
		}
	};
	this._cont.onclick = function(e){
		if (_self.autoHeight) {
			clearTimeout(_self.delay);
			_self.delay = setTimeout(function(){
				if (!_self.__display) return false;
				_self.height();
			},100);
		}
	};

	var newdailog;
	//many dialog
	this.addDialog = function(op) {
		if (newdailog) return false;
		if (!op.size) op.size = 'small';
		size(op);
		var parent = this._wrap;
		var top = parseInt(jQuery(this._wrap).css('marginTop')); 
		var left = parseInt(jQuery(this._wrap).css('marginLeft')) +
			parseInt(jQuery(this._wrap).css('width')); 

		var dialogHtml = '<div class="wrap"  style="';
		//若设置center为true，则在中间位置进行显示.默认情况在右侧
		!op.center ? dialogHtml += ' margin:' + top + 'px 0 0 ' + left + 'px;' : 
			dialogHtml += ' margin:' + (-op.height / 2 + UI.scrollY()) + 'px 0 0 ' + 
			(-op.width / 2 + UI.scrollX()) + 'px;';
		dialogHtml = dialogHtml + ' width: ' + op.width + 'px; height: ' + 
			op.height + 'px;  box-shadow: 0px 0px 10px #666666;"><div class="title"><span>' + 
			op.title + '</span></div><a tabindex="-1" title="Close" onfocus="this.blur();" href="javascript:void(0);" class="close"></a><a tabindex="-1" onfocus="this.blur();" href="javascript:void(0)" class="maxwin hide"></a><div class="cont" style="overflow: hidden; height: ' + 
			(op.height - 30) + 'px;"><div class="loading" style="display: none;"><span>loading...</span></div><iframe scrolling="auto" frameborder="no" class="iframe"  style="display: block; height: ' + 
			(op.height - 30) + 'px;" src="' + op.url + '" allowtransparency="true"></iframe><div class="data" style="height: 0px;"></div></div><b class="cor_1"></b><b class="cor_2"></b><b class="cor_3"></b><b class="cor_4"></b></div>';
		newdailog = jQuery(dialogHtml);

		jQuery(jQuery('.dialog2')[0]).append(newdailog);

		var TimerFly = window.setInterval(function() {
			var iframe_dialog = jQuery(newdailog.find('.cont').find('.iframe')[0].contentWindow.document.body);
			if ((iframe_dialog.children('.content').length > 0) && (iframe_dialog.children('.button').length > 0)) {
				iframe_dialog.children('.content').height(op.height - newdailog.children('.title')[0].offsetHeight - 35);
				window.clearInterval(TimerFly);
			}
		}, 1000);

		if (!op.editable) {
			jQuery(_self._cont).prepend("<div class='bg' id='add_bg' style='opacity:0; filter:alpha(opacity=0)'></div>");
		}

		if (op.fadein) {
			var w = newdailog[0].style;
			var j = 1;
			var _w = parseInt(op.width);
			var _h = parseInt(op.height);
			w.width = '0px';
			t = setInterval(function() {
				parseInt(w.width) < _w ? w.width = (++j) * 5 + "px" : clearInterval(t);
			}, 1);
		}
		var mouse = {
			x: 0,
			y: 0
		};

		newdailog.find('.title').mousedown(function(e) {
			var event = window.event || e;

			var _x = event.clientX - parseInt(jQuery(newdailog).css('marginLeft'));
			var _y = event.clientY - parseInt(jQuery(newdailog).css('marginTop'));
			var w = UI.windowWidth(),
				h = UI.windowHeight(); //Kill Bug
			if (event.preventDefault) {
				event.preventDefault();
			};
			UI.addClass(newdailog, 'move');
			document.onmousemove = function(e) {
				var event = window.event || e;
				var E = UI.E(e);
				if (!UI.Browser.ie && (E.x < 0 || E.y < 0 || E.x > w || E.y > h)) return false;
				newdailog[0].style.marginTop = event.clientY - _y + 'px';
				newdailog[0].style.marginLeft = event.clientX - _x + 'px';
				return false;
			};
			document.onmouseup = function() {
				this.onmousemove = null;
				document.onmouseup = null;
				UI.removeClass(newdailog, 'move');
			};
			return false;
		});

		newdailog.find('.close').click(function(event) {
			newdailog.remove();
			if (document.getElementById('add_bg')) jQuery('#add_bg').remove();
			newdailog = null;
		});

	}

	this.addDialogClose=function(){
		newdailog.remove();
		if(document.getElementById('add_bg')) jQuery('#add_bg').remove();
		newdailog=null;
	}	
	
	this.show = function(o) {
		o = extendOption(o);

		//从缓存中显示对话框时，不在重复缓存
		if(o.cache!=false && o.caller!=='cache'){
			cacheCurrentDialog(_self);
		}
		
		if(o.caller =='cache'){
			o.caller = null;
		}
	
		//消除对话框敲击enter键盘时造成的bug;
		try{
			var getEvent=function(){  
				if (window.event) return window.event;  
				var c = getEvent.caller;  
				while (c.caller) c = c.caller;  
				return c.arguments[0];  
			}
			var e=getEvent();
			var target=e.target||e.srcElement;
			target.blur();
		}
		catch(error){
			document.body.focus();
		}
		
		if (!this.__append) {
			document.body.appendChild(this._body);
			this.__append = true;
		}
		if (!this.__display) {
			UI.show(this._body);
			this.__display = true;
		}   

		this.autoHeight = o.autoHeight || false;
		if (o.autoHeightMax) this.autoHeightMax = o.autoHeightMax;

		this.reset(o);
	};

	this.autofitDialogSize = function(obj,option){
		option = option.split(',');
		option = {autofit:option[0],width:option[1]};
		if(typeof jQuery == 'undefined' || 
			!option || !option.autofit || 
			option.autofit == 'undefined' || option.autofit == 'false') return;

		jQuery(obj).scrollLeft(10);
		jQuery(obj).scrollTop(10);
		var width = option.width;
		var index = 0;
		var maxWidth = top.UI.windowWidth();
		var initWidth = this._wrap.style.width;
		while((jQuery(obj).scrollLeft()>0 || jQuery(obj).scrollTop()>0) && index<15){
			width = parseInt(this._wrap.style.width) + 50;

			if(width>=maxWidth){
				width = maxWidth-100;
				index = 100;//break the loop
			}
			this._wrap.style.width = width + 'px';

			index++;
		}
		
		if(index>=15){
			this._wrap.style.width=initWidth;	
			return;
		}

		this._wrap.style.left = '50%';
		this._wrap.style.marginLeft = 0 - width/2 + UI.scrollX() + 'px';
	}
	function iframeLoadHandler(on){	
		var _docBody = UI.ET(on).contentWindow.document.body;
		var _dlgContent = UI.GC(_docBody,'div.content');
		var _dlgBtn = UI.GC(_docBody,'div.button');
		if(_dlgContent && _dlgBtn){
			var _height = UI.height(UI.ET(on)) - UI.height(_dlgBtn[0]);
			if(_height && !isNaN(_height) && _height>0){
				_dlgContent[0].style.height = _height + 'px';
			}
			_self.autofitDialogSize.call(_self,_dlgContent[0],UI.ET(on).getAttribute('option'));
		}
    };

	this.reset = function(o, tag) {
		if (o.top != undefined) {
			var x = o.top;
		} else var x = 0;
		if (o.left != undefined) {
			var y = o.left;
		} else var y = 0;

		if (o.title) this.title();

		if (!o.size && !(o.width || o.height)) {
			o.size = "medium";
		}
		if (o.size && !(o.width || o.height)) {
			size.call(this, o);
		}
		var autoHeight = !!(o.html && !UI.isString(o.html) && !o.height);

		//Check Postion
		this._wrap.style.top = '50%';
		this._wrap.style.left = '50%';
		this._body.style.height = UI.windowHeight();

		this._close.className = 'close' + (o.close !== false ? '' : 'hide');
		this._help.className = 'help tipBox ' + (o.help != undefined ? '' : 'hide');
		this._help.title = o.help != undefined ? o.help : '';

		_setDialogPosition.call(_self, o);

		if (o.maxoff) {
			this._maxwin.className = 'maxwin';
			this.maxWin = "";
		} else {
			this._maxwin.className = 'maxwin hide';
		};

		if (this.__resize) {
			this._resize.style.display = '';
		};

		if (o.html) {
			if (!tag) {
				UI.hide(this._iframe);
				this._data.innerHTML = ""; //reset data
				UI.hide(this._loading);
				if (UI.isString(o.html)) {
					this._data.innerHTML = o.html;
				} else {
					this._data.appendChild(o.html);
				}
			}
		} else if (o.filltype == 'ajax') {
			jQuery(this._iframe).remove();
			this._data.innerHTML = ""; //reset data
			UI.hide(this._loading);
			jQuery.ajax({
				url: o.url,
				type: 'get',
				dataType: 'html',
				async: false,
				success: function(data) {
					if (data == '{"timeout": 1}') {
						window.location.reload();
					} else {
						o.html = '<div class="data_cont"> ' + data + '</div>';
					}
				}
			});
			jQuery(this._data).html(o.html);
		} else if (o.ajax) {
			_self.fillByAjax(_self.__option.url, _self.__option.ajaxpara, true);
		} else if (o.url) {
			this._data.innerHTML = "";
			UI.hide(this._iframe);
			UI.show(this._loading);


			if (_reloadUrlHandler) {
				clearTimeout(_reloadUrlHandler);
			}

			_reloadUrlHandler = setTimeout(function() {
				_self._iframe.setAttribute('src', o.url);
				_self._iframe.setAttribute('option', o.autofit + ',' + o.width);

				UI.ER(_self._iframe, 'load', iframeLoadHandler);
				UI.EA(_self._iframe, 'load', iframeLoadHandler);
			}, 100);
		}

		if (o.fadein) {
			var w = this._wrap.style;
			var j = 1;
			var _w = parseInt(w.width);
			var _h = parseInt(w.height);
			w.width = '0px';
			t = setInterval(function() {
				parseInt(w.width) < _w ? w.width = (++j) * 15 + "px" : clearInterval(t);
			}, 1);
		}


		this.checkStaus.call(this, o);
		if (autoHeight) { //Auto Heihgt For Dom
			this.height();
		}

		if (this._help.title != '') {
			UI.tipBox('', '', o.w_tip != undefined ? o.w_tip : '', o.h_tip != undefined ? o.h_tip : '');
		}

	};
	
	//Method
	this.hide = function(option) {
		if(newdailog)
		{ 	newdailog.remove();
			if(document.getElementById('add_bg')) jQuery('#add_bg').remove();
			newdailog=null;
		}
		
		UI.hide(this._body);
		this.__display = false;
		
		closeCallBack();
		
		if(!option || (option.cache!==false && option.caller!='cache')){
			cacheCurrentDialog();
		}
	};
	
	this.title = function() {
		this._title.innerHTML = this.__option.title;
	};
	this.height = function() {
		var H = UI.height(this._data) + this._titleHeight;
		this.show({autoHeight:this.autoHeight,height:H > this.autoHeightMax ? this.autoHeightMax : H});
	};
	
	this.fillByAjax= function(url,para,rqt) {
		_self.__option.url=url;
		_self.__option.ajaxpara=para;
		_self.__option.ajax=true;
		
		var mtype=para?'POST':'GET';
		if(!rqt) {
			return;
		}
		jQuery.ajax({
			url:url,
			type:mtype,
			data:para,
			beforeSend:function(){
				jQuery(_self._data).html('');
				_self._iframe.src='about:blank';
				UI.show(_self._loading);
			},
			success:function(data){
				var htmlTag = data.replace(/<\s*html[^>]*>/i,'');
				var iframeTag = data.replace(/<\s*iframe[^>]*>/i,'');
				
				var option =  _self.__option;
				option.ajax = true;
				option.html = null;
				
				if(htmlTag.length<data.length && iframeTag.length==data.length){
					writeIframeHtml(data);
				}
				else if(iframeTag.length < data.length){
					UI.hide(_self._iframe);
					_self._data.innerHTML=data;
					UI.hide(_self._loading);
				}
				else{
					UI.hide(_self._iframe);
					jQuery(_self._data).html(data);
					
				}
				_self.checkStaus.call(_self,option);
				
			}
		})
	};
	
	this.submitInnerForm = function(url,pars) {
		jQuery(this._data).load(url,pars);
	};
	
	this.getFramePath = function(){
		var framePath='';
		for(var i=_self.__option.framePath.length-1;i>=0;i--){
			if(framePath.length>0){
				framePath+='.';
			}
			
			framePath+='frames['+_self.__option.framePath[i]+']';
		}
		if(framePath.length<=0){
			framePath='top';
		}
		else{
			framePath='top.'+framePath;
		}
		return framePath;
	}
	
	this.emitter = function(callFn){
		var framePath = _self.getFramePath();
		if(UI.isFunction(callFn)){
			callFn.apply(_self);
		}
		else if(UI.isString(callFn)){
			eval(framePath+'["'+callFn+'"].call()');
		}
	}

	/**
	 * 获取执行回调函数的上下文
	 * @return {} 
	 */
	this.getHandlerContext = function(){
		var context;
		var contextArg;
		if(this.__option && this.__option.handlerContext){
			contextArg = this.__option.handlerContext.toLowerCase();
		} 

		if( contextArg == 'dialog.iframe'){
			context = _self._iframe;
		}
		else if(contextArg == 'dialog'){
			context = top;
		}

		return context;
	}
	/**
	 * 执行回调函数上下文
	 * @param  {String} handler callback function name
	 * @return {}         
	 */
	this.excuteHandler = function(handler){
		var context = this.getHandlerContext.call(this);
		if(context && context.tagName && context.tagName == 'IFRAME'){
			function excHandler(){
				if(typeof this.contentWindow[handler]=='function'){
					this.contentWindow[handler]();
				}
			}
			if(context && typeof context.contentWindow=='undefined'){
				UI.EA(context,'load',excHandler);
			}
			else if(typeof context.contentWindow[handler]=='function'){
				context.contentWindow[handler]();
			}
		}
		else if(context == top){
			if(typeof top[handler] == 'function'){
				top[handler]();
			}
		}
	}
	
	//TODO:多个对话框同时存在时esc键、调整大小的对象应该是最外层显示的对话框
	UI.EA(document,'keyup',this.key);
	UI.EA(window,'resize',function(){
		setTimeout(_self.resizeBg,0)
	});
	
	function _setDialogPosition(o){
		if (o.width) {
			var tempWidth = o.width >= top.UI.windowWidth() ? top.UI.windowWidth() - 100 : o.width;
			if (o.fixSize) {
				tempWidth = o.width;
			}
			this._wrap.style.width = tempWidth + 'px';
			this._wrap.style.left = (top.UI.windowWidth() - tempWidth) / 2 + 'px';
			o.width = tempWidth;
		}
		if (o.height) {
			var tempHeight = o.height >= top.UI.windowHeight() ? top.UI.windowHeight() - 100 : o.height;
			if (o.fixSize) {
				tempHeight = o.height;
			}
			this._wrap.style.height = tempHeight + 'px';
			var offsetTop = top.document.documentElement.scrollTop || top.pageYOffset || top.document.body.scrollTop;
			var wrapTop = (top.UI.windowHeight() - tempHeight) / 2 + offsetTop;
			wrapTop = wrapTop < 0 ? 10 : wrapTop;
			this._wrap.style.top = wrapTop + 'px';
			o.height = tempHeight;
		}
	}

	function size(o){
		switch(o.size) {
			case 'small':
				_self.__option.width = o.width = 380;
				_self.__option.height = o.height = 220;
				break;
			case 'medium':
				_self.__option.width = o.width = 530;
				_self.__option.height = o.height = 420;
				break;
			case 'big':
				_self.__option.width = o.width = 760;
				_self.__option.height = o.height = 540;
				break;
		}
	};
	
	
	function writeIframeHtml(html){
		UI.show(_self._iframe);
		
		var editor = _self._iframe.contentWindow;
		if(!editor) return;
		// 针对IE浏览器, make it editable
		editor.document.designMode = 'On';
		editor.document.contentEditable = true;
		// For compatible with FireFox, it should open and write something to make it work
		editor.document.open();
		editor.document.write(html);
		editor.document.close();
		editor.document.designMode = 'Off';
		editor.document.contentEditable = false;
		UI.hide(_self._loading);
	}
	
	function isOwnEmpty(obj) {
		for (var name in obj) {
			if (obj.hasOwnProperty(name)) {
				return false;
			}
		}
		return true;
	}

	function extendOption(option){
		option = option || {};
		if(!isOwnEmpty(option)){
			for(var p in _self.__option){
				_self.__option[p]=_self.__initOption[p];
			}
		}
		
		for(var p in _self.__initOption){
			if(!_self.__option[p]){
				_self.__option[p]=_self.__initOption[p];
			}
		}

		for(var p in option){
			if(p=='width' || p=='height'){
				option[p] = parseInt(option[p]);	
			}
			_self.__option[p]=option[p];
		}
		
		return _self.__option;
	}
	
	//缓存打开的对话框
	function cacheCurrentDialog(obj){
		if(obj){
			var op = {};
			for(var i in obj.__option){
				if(obj.__option.hasOwnProperty(i)){
					op[i] = obj.__option[i];
				}
			}
			if(!op.size && !(op.width || op.height)){ 
				op.size="medium";
			}

			obj._body.style['zIndex'] = 2003;
			var loop=0;
			for(var i=top.UI.Dialog._current.length-1; i>=0 ;i--){
				var lastDialog = top.UI.Dialog._current[i];
				var lastDialogWidget = lastDialog.dialog._getDialog();

				if(i == top.UI.Dialog._current.length-1){
					if(obj.__option.name != lastDialogWidget.__option.name){
						UI.hide(lastDialogWidget._bg);
					}
				}

				lastDialogWidget._body.style['zIndex'] = 2002-loop;
				loop++;
			}

			top.UI.Dialog._current.push({dialog:obj.__option.dialogCaller,widget:obj,option:op});
		}
		else{
			var lastDialog;
			if(top.UI.Dialog._current.length>0){
				lastDialog = top.UI.Dialog._current[top.UI.Dialog._current.length-1];
				top.UI.Dialog._current.pop();
			}
			if(top.UI.Dialog._current.length>0){
				var curDialog = top.UI.Dialog._current[top.UI.Dialog._current.length-1];
				var curDialogWidget = curDialog.dialog._getDialog();
				var isShow = curDialogWidget._body.style.display;

				curDialog.option = curDialog.option || {};
				
				if(lastDialog && (curDialog.option.name == lastDialog.option.name || isShow!='none')) {
					curDialog.option.caller='cache';
				}
				
				UI.show(curDialogWidget._bg);
				if(curDialog.option.refresh===false && isShow!='none'){
					UI.show(curDialogWidget._body);
				}
				else{
					curDialog.dialog._getDialog().show(curDialog.option);
				}
			}
			//产品重构代码在对话框之间进行数据传递时，关闭对话框时同时清空在首页的对话框计数器
			//否则打开对话框个数累加到5时，就不能再打开对话框了
			else{
				if(top && typeof top.currentPos != 'undefined'){
					top.currentPos = 0;
				}
			}
		}
	}
	
	//执行关闭对话框回调函数
	function closeCallBack(){
		if(_self.__option.call ){
			if(UI.isFunction(_self.__option.call)){
				_self.__option.call.apply(_self);
			}
			else if(UI.isString(_self.__option.call)){
				var framePath = _self.getFramePath();
				eval(framePath+'["'+_self.__option.call+'"].call()');
			}
		}
	}
};


(function(jQuery,window){
	if(typeof jQuery == 'undefined') return;
	jQuery.fn.serializeObject = function(){
	    var o = {};
	    var a = this.serializeArray();
	    jQuery.each(a, function() {
	        if (o[this.name]) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    return o;
	};
}(window.jQuery))

UI.DropMenu = function(o) { //UI.DropMenu({id:'menu',menu:'menu_list',multi:true,max:15,type:['click','mouseover']});
	o.open = false; //Menu Open Status To Reduce Memory
	if (UI.isString(o.id)) o.id = UI.G(o.id);
	if (o.max == undefined) o.max = 10; //Max Height
	if (o.type == undefined) o.type = ['click','click']; //o.type = [open,close]
	else if (o.type.length == 1) o.type = [o.type,o.type];
	if (o.menu != undefined) {
		if (UI.isString(o.menu)) o.menu = UI.G(o.menu);
		if (!o.id || !o.menu) return false;
		if (o.multi) { //Multi Select
			o.parent = o.id.parentNode;
			var a = UI.GT(o.menu,'a');
			if (a.length > o.max) {
				var height = UI.C(a[0],'height');
				height = height == 'auto' ? 20 : height.slice(0,-2);
				o.menu.style.height = o.max * height + 'px';
				o.menu.style.overflow = 'auto';
			}
		}
		UI.EA(document,o.type[1],function(e){ //Document
			if (o.open) {
				if (UI.E(e).target != o.id && UI.E(e).target.parentNode != o.id) {
					if(o.type[1] == 'mouseover')
					{
						if(UI.E(e).target != o.id.parentNode){
							setTimeout(function(){
								UI.hide(o.menu);
								if (o.multi) UI.removeClass(o.parent,'drop_menu_on');
								o.open = false;
								UI.parent(o.id).style.zIndex=1000;
							},200);
						}
					}
					else
					{
						UI.hide(o.menu);
						if (o.multi) UI.removeClass(o.parent,'drop_menu_on');
					    o.open = false;
						
						UI.parent(o.id).style.zIndex=1000;
					}
				}
			}
		
		});
		/*
		if(o.id.children.length>0)
		{
			UI.EA(o.id.children[0],o.type[0],function(e){ //span
				var e = UI.E(e);
				if(o.open){
					UI.parent(o.id).style.zIndex=1000;
				}
				else{
					UI.parent(o.id).style.zIndex=1001;
				}
				
				if (o.type == 'mouseover') {
					e.stop();
					UI.show(o.menu);
				}
				else UI.toggle(o.menu);
				if (o.multi) UI.toggleClass(o.parent,'on');
				o.open = o.menu.style.display == 'block'||o.menu.style.display == '' ? true : false;
			
				if (e.stopPropagation) e.stopPropagation();
 				else e.cancelBubble = true;
			});
		}
		*/
		UI.EA(o.id,o.type[0],function(e){ //Menu
			//z-index
			if(o.open){
				UI.parent(o.id).style.zIndex=1000;
			}
			else{
				UI.parent(o.id).style.zIndex=1001;
			}
			
			if (o.type == 'mouseover') {
				UI.E(e).stop();
				UI.show(o.menu);
			}
			else {
				UI.toggle(o.menu);
			}

			if (o.multi) UI.toggleClass(o.parent,'drop_menu_on');
			o.open = o.menu.style.display == 'block'||o.menu.style.display == '' ? true : false;
			
		},false);

		if (o.hover) {
			UI.EA(o.id,'mouseover',function(e){
				UI.addClass(this,o.hover);
			});
			UI.EA(o.id,'mouseout',function(e){
				UI.removeClass(this,o.hover);
			});
			
		}
		
		UI.EA(o.menu,o.type[1],function(e){ //Menu list
			UI.E(e).stop();
		});
		
		
	}
	else {
		
	}
}
UI.Flash = function(o,src,width,height) {
	o.innerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+width+'" height="'+height+'" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="'+src+'" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><embed src="'+src+'" width="'+width+'" height="'+height+'" quality="high" wmode="transparent" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"/></object>';
}


;(function($){
	if(!$) return;
	$.gotop = function(options) {
 		var defaults = {
    			text: '',
    			min: 200,
    			inDelay:600,
    			outDelay:400,
      			containerID: 'toTop',
    			containerHoverID: 'toTopHover',
    			scrollSpeed: 800,
    			easingType: 'linear'
 		    },
            settings = $.extend(defaults, options),
            containerIDhash = '#' + settings.containerID,
            containerHoverIDHash = '#'+settings.containerHoverID;
		
		$('body').append('<a href="#" class="ns-gotop" id="'+settings.containerID+'">'+settings.text+'</a>').css({'position':'relative'});
		$(containerIDhash).hide().on('click.gotop',function(){
			$('html, body').animate({scrollTop:0}, settings.scrollSpeed, settings.easingType);
			$('#'+settings.containerHoverID, this).stop().animate({'opacity': 0 }, settings.inDelay, settings.easingType);
			return false;
		})
		.prepend('<span class="ns-gotop-hover" id="'+settings.containerHoverID+'"></span>')
		.hover(function() {
				$(containerHoverIDHash, this).stop().animate({
					'opacity': 1
				}, 600, 'linear');
			}, function() { 
				$(containerHoverIDHash, this).stop().animate({
					'opacity': 0
				}, 700, 'linear');
			});
					
		$(window).scroll(function() {
			var sd = $(window).scrollTop();
			if(typeof document.body.style.maxHeight === "undefined") {
				$(containerIDhash).css({
					'position': 'absolute',
					'top': sd + $(window).height() - 50
				});
			}
			if ( sd > settings.min ) 
				$(containerIDhash).fadeIn(settings.inDelay);
			else 
				$(containerIDhash).fadeOut(settings.Outdelay);
		});
};
})(window.jQuery);
UI.loading=function(o,s,b){
	o=o||top;
	var dom=o.document,load=dom.getElementById('ui_loading');
	if(load){
		UI.show(load);
	}
	else{
		load=dom.createElement("div");
		load.id="ui_loading";
		if(b==undefined)b=true;

		var bgWidth = Number(o.UI.pageWidth()) + parseFloat(o.UI.getStyle(o.document.body,'marginLeft'))+ parseFloat(o.UI.getStyle(o.document.body,'marginRight'));
		load.style.cssText += ';width:' + bgWidth + 'px;height:' + Math.max(Number(o.UI.windowHeight()),Number(o.UI.pageHeight()))+ 'px;';
		load.className="load"+(!b?' nobg': '');

		var offsetTop = o.document.documentElement.scrollTop || o.pageYOffset || o.document.body.scrollTop;
		var wrapTop = (o.UI.windowHeight()  - 33)/2 + offsetTop;
		load.innerHTML=(UI.Browser.ie6 ? '<iframe src="javascript:false;" class="cover_select"></iframe>' : '')+"<div class='load_bg'></div><div class='load_con' style='top:"+wrapTop+"px'>\
						<span>"+(s||'loading...')+"</span></div>";
		dom.body.appendChild(load);
	}
};
UI.loading.hide=function(o){
	var o=o||top,dom=o.document,load=dom.getElementById('ui_loading');
	if(load){
		UI.parent(load).removeChild(load)
	}
};
UI.loading.text=function(s,o){
	var o=o||top,dom=o.document,load=dom.getElementById('ui_loading');
	if(load){
		load.innerHTML="<div class='load_bg'></div>\
						<div class='load_con'><span>"+(s||'loading...')+"</span></div>";
	}
};

UI.Menu = function(o) {
	this.name = o.name;
	this.id = o.id;
	this.sub_id = o.sub_id;
	this.location_id = o.location_id;
	this.main = UI.G(o.id);
	this.body = UI.G(o.sub_id);
	this.wrap = UI.GC(this.body,'.sub_menu_wrap')[0];
	this.bar = UI.GC(this.body,'a.bar')[0];
	this.target = o.target; //Target Iframe
	this.cache = (o.cache == undefined ? true : o.cache); //Cache Menu Status
	this.large = o.large; //Large Icon For Menu Title
	this.extend = []; //Extend Menu
	this.data = o.data;
	//Show Location Information
	this.location = {
		data : [],
		rel : null,
		build : function() {
			for (var n=0;n<this.data.length;n++) {
				var h = UI.G(this.id);
				if (!n) h.innerHTML = '';
				if (n!=this.data.length-1) h.innerHTML += (n ? '<b class="dot"></b>' : '') + '<a href="' + this.data[n].url + '" target="' + this.target + '" ' + (!n && this.cache ? 'class="unlink" onclick="this.blur();return false;' : 'onclick="') + this.name + (this.data[n].fake ? '.location.show(' + n +');' : '.show(\'' + this.rel.slice(0,n+1) + '\');') + '" title="' + this.data[n].name + '">' + this.data[n].name + '</a>';
				else h.innerHTML += (n ? '<b class="dot"></b>' : '') + this.data[n].name;
			}
		},
		rebuild : function(o) {
			var o = eval('[' + o + ']');
			this.rel = o;
			this.data = [];
			for (var i=0;i<o.length;i++) {
				if (!i) this.data.push({name:this.tmp[o[i]].name,url:this.tmp[o[i]].url});
				try{
					if (i==1) this.data.push({name:this.tmp[o[i-1]].data[o[i]].name,url:(this.tmp[o[i-1]].data[o[i]].url ? this.tmp[o[i-1]].data[o[i]].url : this.tmp[o[i-1]].data[o[i]].data[0][1])});
					if (i==2) this.data.push({name:this.tmp[o[i-2]].data[o[i-1]].data[o[i]][0],url:this.tmp[o[i-2]].data[o[i-1]].data[o[i]][1]});
				}catch(e){}
			}
			this.build();
		},
		edit : function(n,u) {
			this.data.pop();
			this.data.push({name:n,url:u,fake:true});
			this.build();
		},
		add : function(n,u) {
			if (n != this.data[this.data.length - 1].name) {
				this.data.push({name:n,url:u,fake:true});
				this.build();
			}
		},
		remove : function() {
			this.data.pop();
			this.build();
		},
		show : function(n) {
			this.data.splice(n +1,50);
			this.build();
		}
	}
	this.location.name = this.name;
	this.location.id = this.location_id;
	this.location.target = this.target;
	this.location.cache = this.cache;
	this.location.tmp = o.data;

	this.show = function(o,load) { //Show Menu
		var url;
		if (UI.isArray(o)) o = this.index(o); //Search By Menu Name
		else var o = o.split(',');
		if (o.length <= 2) {
			o.push(0);
			if (o.length == 2 && this.location.tmp[o[0]].data.length) o.push(0);
		}
		if (load) {
			url = this.data[o[0]].data[o[1]].url || this.data[o[0]].url;
			try {
				url = this.data[o[0]].data[o[1]].data[o[2]][1];
			}catch(e){};
		}

		//List & Main Menu
		this.menu_list[this.cur_list].className = 'wrap hide';
		this.menu_list[o[0]].className = 'wrap show';
		UI.removeClass(this.main_menu[this.cur_list],'on');
		UI.addClass(this.main_menu[o[0]],'on');
		if (this.cache) this.main_menu[o[0]].setAttribute('rel',o)
		this.cur_list = o[0];

		try{
			UI.removeClass(UI.GC(this.menu_list[o[0]],'.on')[0],'on');
		}catch(e){};
		for (var i=1;i<o.length;i++) {
			if (i==1 && !this.location.tmp[o[0]].data[o[1]].data.length) {
				UI.addClass(UI.GC(UI.GC(this.body,'.wrap.show')[0],'.title')[o[1]],'on');
				this.main_menu[o[0]].href = this.location.tmp[o[0]].data[o[1]].url;
			}
			if (i==2 && !this.location.tmp[o[0]].data[o[1]].extend) {
				var menu_title = this.menu_title[o[0]][o[1]],menu_content = menu_title.nextSibling;
				UI.removeClass(menu_title,'off');
				UI.removeClass(menu_content,'hide');
				if (this.location.tmp[o[0]].data[o[1]].data.length) {
					UI.addClass(menu_content.getElementsByTagName('a')[o[2]],'on');
					if (this.cache) this.main_menu[o[0]].href = this.location.tmp[o[0]].data[o[1]].data[o[2]][1];
				}
				else if (this.cache) this.main_menu[o[0]].href = this.location.tmp[o[0]].data[o[1]].url;
			}
		}

		this.location.rebuild(o);
		if (url) window[this.target].location.href = url;

	}
	this.go = function(o) {
		this.show(o,true);
	}
	this.index = function(o) { //Get Menu Index Number With Menu Name
		var tmp = [];
		for (var i=0;i<o.length;i++) {
			if (i == 0) {
				for (var j=0;j<this.data.length;j++) {
					if (o[i] == this.data[j].name) {
						tmp.push(j);
						break;
					}
				}
			}
			if (i == 1) {
				for (var j=0;j<this.data[tmp[0]].data.length;j++) {
					if (o[i] == this.data[tmp[0]].data[j].name) {
						tmp.push(j);
						break;
					}
				}
			}
			if (i == 2) {
				for (var j=0;j<this.data[tmp[0]].data[tmp[1]].data.length;j++) {
					if (o[i] == this.data[tmp[0]].data[tmp[1]].data[j][0]) {
						tmp.push(j);
						break;
					}
				}
			}
		}
		return tmp;
	}

	this.refresh = function(o) {
		if (UI.isArray(o)) { //Search By Menu Name
			o = this.index(o);
			if (o.length == 1) tmp.push(0); //Auto To Find Second Menu
		}
		else if (o.split(',').length == 1) { //Auto To Find Second Menu
			o += ',0';
		}

		for (var i=0;i<this.extend.length;i++) {
			if (this.extend[i].rel == o) {
				var o = eval('[' + o + ']');
				var _extend = this.menu_title[o[0]][o[1]].nextSibling,_call = this.extend[i].call;
				_extend.innerHTML = '<div class="extend"><span class="content">loading...</span></div>';
				UI.get(this.extend[i].url,{},function(data){
					setTimeout(function(){
						_extend.innerHTML = '<div class="extend">' + data + '</div>';//.replace( /(?:\r\n|\n|\r)/g, '' )
						eval(_call);
					},200);
				})
			}
		}
	}
	var name = this.name;
	this.tree = function(n,m) {
		var o = UI.isString(n) ? UI.G(n) : n;
		var a = UI.GT(o,'a');
		var b = UI.GC(o,'b.arrow');
		//判断是否有默认选中的节点
		var selectedNode;
		UI.each(a,function(item){
			if(UI.hasClass(item,'on')){
				selectedNode=item;
				return false;
			}
		})
		
		if(selectedNode){
			//去除所有节点选中状态
			window[name].menu_links = UI.GT(window[name].wrap,'a');
			UI.each(window[name].menu_links,function(item){
				if(item!=selectedNode){
					UI.removeClass(item,'on');
				}
			});
			
			var url = UI.A(selectedNode,'href');
			if(!url.hasString('void(0)') && url != '#'){
				window[window[name]['target']].location.href = url;
			}
		}
		
		for (var i=0;i<a.length;i++) {
			UI.A(a[i],'target',window[name].target);
			a[i].onfocus = function(){
				this.blur();
			}
			a[i].onclick = function() {
				try{
					UI.each(UI.GC(window[name].menu_list[m],'.on'),function(on){UI.removeClass(on,'on');});
					//UI.removeClass(UI.GC(window[name].menu_list[m],'.on')[0],'on');
				}catch(e){};
				UI.addClass(this,'on');
				var href = UI.A(this,'href');
				if (UI.next(this) && (href.hasString('void(0)') || href == '#')) {
					UI.toggleClass(this,'unfold');
					UI.toggleClass(UI.next(this),'hide');
					if (UI.hasClass(this,'extend')) {
						var o = this,next = UI.next(o);
						next.innerHTML = '<div class="extend"><span class="content">loading...</span></div>';
						UI.get(this.getAttribute('rel'),'',function(data){
							setTimeout(function(){
								next.innerHTML = data;
								window[name].tree(next,m);
							},100);
							UI.removeClass(o,'extend');
						});
					}
				}
			}
		}
		UI.each(b,function(o,i){
			b[i].onclick = function(e) {
				var parent = this.parentNode.parentNode;
				var next = UI.next(parent);
				UI.toggleClass(parent,'unfold');
				UI.toggleClass(next,'hide');
				
				if (UI.hasClass(parent,'extend')) {
					next.innerHTML = '<div class="extend"><span class="content">loading...</span></div>';
					UI.get(parent.getAttribute('rel'),'',function(data){
						setTimeout(function(){
							next.innerHTML = data;
							window[name].tree(next,m);
						},100);
						UI.removeClass(parent,'extend');
					});
				}
				UI.E(e).stop();
				return false;
			}
		});
	}

	/* Sub Menu & Main Menu */
	var html = [],html_main = [],rel = [];
	for(var i=0;i < o.data.length;i++) {
		//Location
		if (!i) rel = 0;

		html.push('<div class="wrap' + (!i ? ' show' : ' hide') + '">');
		for(var j=0;j < o.data[i].data.length;j++) {
			var off = title_large = arrow_empty = title_on = hide = cont_hide = '',title_url = '#';
			if (o.data[i].data[j].close) {
				off = ' off';
				hide = ' hide';
			}
			if (!o.data[i].data[j].data.length && !o.data[i].data[j].extend) {
				cont_hide = ' style="display:none"';
				arrow_empty = ' empty';
				if (this.large) title_large = ' large';
				if (!j) title_on = ' on';
			}
			if (!o.data[i].data[j].extend) {
				title_url = o.data[i].data[j].url ? o.data[i].data[j].url : o.data[i].data[j].data[0][1];
			}
			html.push('<div class="title' + title_large  + off + title_on + ' " onmouseover="UI.addClass(this,\'hover\')" onmouseout="UI.removeClass(this,\'hover\')"><a href="javascript:void(0)" onfocus="this.blur()" class="arrow' + arrow_empty + '" onclick="UI.toggleClass(this.parentNode,\'off\');UI.toggleClass(this.parentNode.nextSibling,\'hide\')"></a><a href="' + title_url + '" target="' + this.target + '" onfocus="this.blur()" onclick="' + (o.data[i].data[j].extend ? this.name + '.refresh(this.getAttribute(\'rel\'));UI.removeClass(this.parentNode,\'off\');UI.removeClass(this.parentNode.nextSibling,\'hide\');return false;': '') + this.name + '.show(this.getAttribute(\'rel\'));UI.removeClass(this.parentNode,\'hover\');" rel="' + i + ',' + j + (o.data[i].data[j].data.length ? ',0' : '') +'" title="' + o.data[i].data[j].name + '"><span>' + (o.data[i].data[j].ico ? '<b class="ico ' + o.data[i].data[j].ico + '"></b>' : '') + '<em>' + o.data[i].data[j].name + '</em></span></a></div><div class="content' + hide + '"' + cont_hide + '><span>');
			if (o.data[i].data[j].extend) this.extend.push({rel:i + ',' + j,url:o.data[i].data[j].extend.url,call:o.data[i].data[j].extend.call});
			else {
				for (var m=0;m<o.data[i].data[j].data.length;m++) {
					html.push('<a href="' + o.data[i].data[j].data[m][1] + '" target="' + this.target + '" onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));"' + ((!title_on && !j && !m) ? ' class="on"' : '') + ' rel="' + i + ',' + j + ',' + m + '" title="' + o.data[i].data[j].data[m][0] + '"><span><b class="icon dot"></b>' + o.data[i].data[j].data[m][0] + '</span></a>');
				}
			}
			html.push('</span></div>');

			//Location
			if (!i && !j) {
				rel = '0,0' + (o.data[0].data[0].data.length ? ',0' : '');
			}
		}
		html.push('</div>');

		html_main.push('<a href="' + o.data[i].url + '" target="' + this.target + '" class="' + (i == 0 ? 'first on' :'') + (i == o.data.length - 1 ? 'last' : '') + '" title="' + o.data[i].name + '" onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));' + (o.data[i].call ? o.data[i].call : '') + '" rel="' + i + ',0' + (o.data[i].data[0].data.length ? ',0' : '') + '" title="' + o.data[i].name + '"><span>' + o.data[i].name + '</span></a>');
	}

	this.wrap.innerHTML = html.join('');
	this.main.innerHTML = html_main.join('');
	if (o.data.length == 1) { //Hide Main Menu
		UI.addClass(this.main,'hide');
	}
	else UI.addClass(document.body,'HasMainMenu');

	//Menu list
	this.main_menu = UI.GT(this.main,'a');
	this.menu_list = UI.GC(this.body,'.wrap');
	this.menu_links = UI.GT(this.wrap,'a');
	this.menu_title = [];
	for (var i=0;i<this.menu_list.length;i++) {
		this.menu_title.push(UI.GC(this.menu_list[i],'.title'));
	}
	this.cur_list = 0;
	if (this.extend.length) { //Load Extend Menu
		for (var i=0;i<this.extend.length;i++) {
			this.refresh(this.extend[i].rel);
		}
	}

	//Hide Bar
	this.bar.onclick = function() {
		UI.toggleClass(this.parentNode,'close');
		UI.removeClass(this.parentNode,'open');
	}
	this.bar.onfocus = function() {
		this.blur();
	}
	var _name = this.name,_delay;
	this.body.onmouseover = function() {
		clearTimeout(_delay);
		_delay = setTimeout(function() {
			if (UI.hasClass(window[_name].body,'close')) {
				UI.addClass(window[_name].body,'open');
			}
		},250);
	}
	this.body.onmouseout = function() {
		clearTimeout(_delay);
		_delay = setTimeout(function() {
			if (UI.hasClass(window[_name].body,'close')) {
				UI.removeClass(window[_name].body,'open');
			}
		},250);
	}
	if (UI.Browser.ie6) { //IE6 Hack
		this.bg_iframe = UI.html('<iframe src="javascript:false;" class="bg"></iframe>')[0];
		this.bg_div = UI.html('<div class="bg"></div>')[0];
		this.wrap.appendChild(this.bg_iframe);
		this.wrap.appendChild(this.bg_div);
	}

	//List Auto Height
	document.documentElement.style.overflow = 'hidden';
	var _menu_height = UI.GC('.header')[0].scrollHeight + UI.GC('a.bar')[0].scrollHeight;
	var _footer = UI.GC('td.footer');
	if (_footer) _menu_height += _footer[0].scrollHeight;
	this.autoHeight = function() {
		this.wrap.style.height = (UI.Browser.ie ? document.documentElement.scrollHeight - _menu_height - 4 : window.innerHeight - _menu_height) + 'px';
	};
	this.autoHeight();
	(function(n){
		UI.EA(window,'resize',function(){
			window[n].autoHeight();
		});
	})(this.name);

	//Default Show
	this.show(rel);
	window[this.target].document.location.href = o.data[0].url;
}
//left-frame menu use
UI.Menu_left = function (o) {
	this.name = o.name;
	this.id = o.id;
	this.target = o.target; //Target Iframe
	this.data = o.data;
	this._cookie = "left_menustatue_" + UI.GT(document, 'title')[0].innerHTML.replace(/\W/g, "");
	var html = [];
	var imagePath = UI.GetRoot(['ui.min.js', 'ui.js']);
	if (imagePath) {
		var path = imagePath.substr(0, imagePath.lastIndexOf("/"));
		imagePath = path.substr(0, path.lastIndexOf("/")) + "/stylesheet/images/";
	}
	//html渲染
	html.push('<div class="leftmenu_body">');
	for (var i = 0; i < o.data.length; i++) {
		if (i == 0 && o.data[0].name != '') {
			html.push('<div style=height:6px;></div>');
		}
		html.push('<div class="' + (o.data[i].name != '' ? ' menutitle' : 'menutitle hide') + '" id="menutitle' + i + '" onclick="' + this.name + '.selectmenu(this)"> <img  class="ico_right" src="' + imagePath + 'blank.gif"><span>' + o.data[i].name + '</span></div>');

		html.push('<div class="' + (o.data[i].name != '' ? ' submenu' : 'submenu nochild') + '" style="' + (o.data[i].name != '' ? '' : 'display:block') + '" id="sub' + i + '"> ');

		if (o.data[i].data.length > 0) {
			html.push('<img id="topimg" class="topimg" src="' + imagePath + 'blank.gif">');

			for (var j = 0; j < o.data[i].data.length; j++) {
				var lineClass = '';
				if(o.data[i].data[j].length>=3){
					lineClass = o.data[i].data[j][2];
				}
				if(j<=o.data[i].data.length-1 && j>=1 && o.data[i].data[j-1].length>=3){
					lineClass = o.data[i].data[j-1][2]+'-end';
				}
				html.push('<a id="two' + i + j + '" class="aline '+lineClass+'" target="' + 
					o.target + '" href="' + o.data[i].data[j][1] + '" onclick="' + 
					(o.data[i].name != '' ? '' : '' + this.name + '.closeall(\'all\');') + '' + 
					this.name + '.selectmenu(this);" title="'+ o.data[i].data[j][0] + '" rel="'+i+','+j+'">' + 
					o.data[i].data[j][0] + '</a>');
			}
			html.push('<img id="endimg" class="endimg" src="' + imagePath + 'blank.gif">');
		}

		html.push('</div>');
		var menuLineClass = '';
		if(o.data[i] && o.data[i].styleName){
			menuLineClass = o.data[i].styleName;
		}
		if (o.data[i].name == '' && (i + 1) < o.data.length && o.data[i + 1].name == '') {

		}
		else {
			html.push('<div class="menuline '+menuLineClass+'" ></div>');
		}
	}
	html.push('</div><a class="bar" title="收起菜单"></a>');
	html = html.join('');
	document.getElementById(o.id).innerHTML = html;
	//关闭所有菜单的选中
	//1 正常二级菜单点击使用
	//all正常一级菜单点击使用
	//nochange_all nochange菜单点击使用
	this.closeall = function (index) {
		switch (index) {
		case 1:
			UI.each(UI.GC('.menuselect'), function (on) {
				UI.removeClass(on, "menuselect");
			})
			UI.each(UI.GC('.topimg'), function (on) {
				UI.removeClass(on, 'active_top');
			})
			UI.each(UI.GC('.endimg'), function (on) {
				UI.removeClass(on, 'active_end');
			})
			break;
		case 'all':
			UI.each(UI.GC('.menutitle'), function (on) {
				UI.removeClass(UI.GT(on, 'img')[0], 'ico_down');
				UI.addClass(UI.GT(on, 'img')[0], 'ico_right');
			})
			UI.each(UI.GC('.menuselect'), function (on) {
				UI.removeClass(on, "menuselect");
			})
			UI.each(UI.GC(UI.G(this.id), '.submenu'), function (on) {
				if (!UI.hasClass(on, 'nochild'))
					on.style.display = "none";
			})
			UI.each(UI.GC('.topimg'), function (on) {
				UI.removeClass(on, 'active_top');
			})
			UI.each(UI.GC('.endimg'), function (on) {
				UI.removeClass(on, 'active_end');
			})
			break;
		default:
			break;
		}
	},
	this.selectmenu = function (obj,option) {
		var menu_cookie = "";
		var option = option || {};
		if(!obj && !UI.isElement(obj)) return;
		if (UI.hasClass(obj, 'menutitle')) {
			if (UI.next(obj).style.display == "block") {
				this.closeall("all");
			} else {
				var child1,child2;
				child1 = UI.children(UI.next(obj))[0];
				child2 = UI.children(UI.next(obj))[1];
				this.closeall("all");
				UI.removeClass(UI.GT(obj, 'img')[0], 'ico_right');
				UI.addClass(UI.GT(obj, 'img')[0], 'ico_down');
				UI.next(obj).style.display = "block";
				UI.addClass(child1, 'active');

				if(child2){
					UI.addClass(child2, 'menuselect');
					this.selectmenu(child2,option);
					if(!option.keepPage)
					UI.G(this.target).src = child2.href;
				}
			}
		} else {
			this.closeall(1);
			UI.addClass(obj, "menuselect");
			if (UI.next(obj).tagName == "IMG") {
				UI.addClass(UI.next(obj), 'active_end');
			}
			if (UI.prev(obj).tagName == "IMG") {
				UI.addClass(UI.prev(obj), 'active_top');
			}
			
			var parentMenu = UI.parent(obj);
			var parentPrevMenu = UI.prev(parentMenu);
			if (UI.hasClass(parentMenu, 'submenu') && UI.hasClass(parentPrevMenu, 'menutitle')) {
				UI.removeClass(UI.GT(parentPrevMenu, 'img')[0], 'ico_right');
				UI.addClass(UI.GT(parentPrevMenu, 'img')[0], 'ico_down');
				UI.next(parentPrevMenu).style.display = "block";
			}
			//cookie
			var first = 0,
			second = 0,
			i = 0;
			UI.each(UI.GC('.menutitle'),
				function (on) {
				if (on == UI.prev(UI.parent(obj))) {
					first = i;
				}
				i++;
			})
			i = 0;
			UI.each(UI.GC(UI.parent(obj), '.aline'),
				function (on) {
				if (on == obj) {
					second = i;
				}
				i++;
			})
			menu_cookie = first + "|" + second + "|" + obj.href;
		}
		if (menu_cookie) {
			UI.cookie(this._cookie, menu_cookie, 1);
		}
	},
	/**
	 * 根据路径直接设置选中项
	 * @param path 路径,最后一个为url 例如['My Portal','报表','url']
	 * @returns
	 */
	this.select_by_path = function (path,option) {
		var self = this;
		var h_menu_one;
		var h_menu_two;
		var option = option || {};
		if (path.length == 3) {
			this.closeall("all");
			var ones = UI.GC('.menutitle');
			for (var i = 0; i < ones.length; i++) //循环一级菜单
			{
				if ((UI.Browser.msie ? UI.children(ones[i])[1].innerText : UI.children(ones[i])[1].innerHTML) == path[0]) {
					h_menu_one = ones[i];
					self.selectmenu(h_menu_one,option);
					var twos = UI.GC(UI.next(h_menu_one), '.aline');
					UI.each(twos, function (on) { //循环二级菜单
						if (UI.trim(on.text ? on.text : on.innerText) == UI.trim(path[1])) { //找到目标菜单
							h_menu_two = on;
							self.selectmenu(h_menu_two,option);
							if(!option.keepPage)
							UI.G(self.target).src = path[2];
						}
					});
					if (h_menu_two != undefined) {
						break;
					}
				}
			}
		}

	},
	/**
	 * 根据菜单排序index直接设置选中项
	 * @param f一级菜单选中index,s二级菜单选中index，最后一个为url 例如menu.show(1,2,'http://www.baidu.com');
	 * @returns
	 */
	this.show = function (f, s, url) {
		var self = this;
		var h_menu_one;
		var h_menu_two;
		var _url = "";

		if(UI.isString(f)){
			var activeMenu = f.split(',');
			if(activeMenu.length>=2){
				f = activeMenu[0];
				s = activeMenu[1];
			}
		}
		
		//设置一级菜单的选中
		try {
			if (f != undefined) {
				self.selectmenu(UI.GC('.menutitle')[f]);
			}
			//设置二级菜单的选中
			if (s != undefined) {
				self.selectmenu(UI.GC(UI.next(UI.GC('.menutitle')[f]), '.aline')[s]);
				_url = UI.GC(UI.next(UI.GC('.menutitle')[f]), '.aline')[s].href;
			}
			if (url != undefined) {
				if (UI.G(this.target).src != url) {
					UI.G(this.target).src = url;
				}
			} else {
				if (UI.G(this.target).src != _url) {
					UI.G(this.target).src = _url;
				}
			}
		} catch (e) {
			this.show(0, 0)
		}
	},
	//cookie设置菜单选中项
	this.reload_menu_statue = function () {
		var c = UI.cookie(this._cookie);
		if (c == null) {
			this.show(0, 0)
		} else {
			var S = c.split('|');
			if (S.length == 3) {
				this.show(S[0], S[1], S[2]);
			}
		}
	},
	this.menu_reset = function () {
		UI.cookie(this._cookie, "", 10);
		this.show(0, 0);
	},
	//收缩
	this._leftmenu = UI.GC('div.leftmenu')[0];
	if (UI.Browser.safari) {
		UI.EA(this._leftmenu, 'mouseover', function (e) {
			if (UI.hasClass(UI.GC('a.bar')[0], 'on') && UI.ET(e) != UI.GC('a.bar')[0] && !this.contains(e.relatedTarget)) {
				setTimeout(function () {
					UI.removeClass(UI.GC('div.menuleft')[0], 'on');
					UI.show(UI.GC('div.leftmenu_body')[0]);
					UI.addClass(UI.GC('div.menuleft')[0], 'pos');
				}, 200)
			}
		});
		UI.EA(this._leftmenu, 'mouseout', function (e) {
			if (UI.hasClass(UI.GC('a.bar')[0], 'on') && !this.contains(e.relatedTarget)) {
				setTimeout(function(){
					UI.addClass(UI.GC('div.menuleft')[0], 'on');
					UI.hide(UI.GC('div.leftmenu_body')[0]);
					UI.removeClass(UI.GC('div.menuleft')[0], 'pos');
				},200)
			}
		});
	} else {
		UI.EA(this._leftmenu, 'mouseenter', function (e) {
			if (UI.hasClass(UI.GC('a.bar')[0], 'on') && UI.ET(e) != UI.GC('a.bar')[0]) {
				setTimeout(function () {
					UI.removeClass(UI.GC('div.menuleft')[0], 'on');
					UI.show(UI.GC('div.leftmenu_body')[0]);
					UI.addClass(UI.GC('div.menuleft')[0], 'pos');
				}, 200)
			}
		});
		UI.EA(this._leftmenu, 'mouseleave', function (e) {
			if (UI.hasClass(UI.GC('a.bar')[0], 'on')) {
				setTimeout(function(){
					UI.addClass(UI.GC('div.menuleft')[0], 'on');
					UI.hide(UI.GC('div.leftmenu_body')[0]);
					UI.removeClass(UI.GC('div.menuleft')[0], 'pos');
					UI.addClass(UI.GC('div.main')[0], 'on');
				},200)
			}
		});
	}

	this._bar = UI.GC('a.bar')[0];
	var _self = this;
	UI.EA(this._bar, 'click', function () {
		_bar = UI.GC('a.bar')[0];
		if (UI.hasClass(_bar, 'on')) {
			UI.removeClass(_bar, 'on');
			UI.removeClass(UI.GC('div.menuleft')[0], 'on');
			UI.removeClass(UI.GC('div.menuleft')[0], 'pos');
			UI.show(UI.GC('div.leftmenu_body')[0]);
			UI.removeClass(UI.GC('div.main')[0], 'on');
			UI.A(_bar, 'title', '收起菜单');
		} else {
			UI.addClass(_bar, 'on');
			UI.addClass(UI.GC('div.menuleft')[0], 'on');
			UI.hide(UI.GC('div.leftmenu_body')[0]);
			UI.addClass(UI.GC('div.main')[0], 'on');
			UI.A(_bar, 'title', '展开菜单');
		}
		UI.G(_self.target).style.width = UI.windowWidth() - UI.width(UI.G(_self.id)) + 'px';
	})
	this._menuleft = UI.GC('div.leftmenu')[0];
	UI.EA(this._menuleft, 'scroll', function (e) {
		UI.GC('a.bar')[0].style.bottom =  - UI.scrollY(UI.GC('div.leftmenu')[0]) + 'px';
	})
	
	/**
	* 菜单每次初始化时，是否清空cookie，如果不清空的话，进行用户切换时，可能会发生没有权限访问的问题
	* 原因：admin登录进去缓存的当前菜单是“首页-状态”，test用户进去后从缓存中读取的当前菜单其实已经不存在，
	* 出现页面访问错误的问题 
	*/
	if(o.resetCookie){
		this.menu_reset();
	}
	//List Auto Height
	this.reload_menu_statue();
	var menuid = this.id;
	var targetid = this.target;
	this.autoHeight = function () {
		document.documentElement.style.overflow = 'hidden';
		//fixbug:body margin-bottom不显示问题 by zgf
		//var _menu_height = 38;
		var _menu_height = jQuery(UI.GC('div.header')[0]).outerHeight(true);
		var _footer = UI.GC('.footer');
		if (_footer)
			_menu_height += 23;
		UI.G(menuid).style.height = UI.windowHeight() - _menu_height + 'px';
		UI.G(targetid).style.height = UI.windowHeight() - _menu_height + 'px';
		//UI.G(targetid).style.width = UI.windowWidth() - UI.width(UI.G(menuid)) + 'px';
		UI.G(targetid).style.width = UI.windowWidth() -  jQuery(UI.GC('div.menuleft')[0]).outerWidth(true)+ 'px';
		UI.GC('div.leftmenu')[0].scrollTop = 0;
	};
	this.autoHeight();
	(function (n) {
		UI.EA(window, 'resize', function () {
			window[n].autoHeight();
		});
	})(this.name);
};

(function ($, window) {
	if (!$) {
		return;
	}
	var defaults = {
		responsive : true,
		tipBox : '.foot_list_box',
		screen : 'small'
	};
	$.responsiveFooter = function (options) {
		var option = $.extend(defaults, options);

		var containers = option.container || [];

		//底部响应式布局
		function footerResponsive() {
			if (document.documentElement.scrollWidth > 1024) {
				$('.screen_1024').css('display', 'none');
				$('.screen_big').css('display', '');
			} else {
				showSmallScreen();
			}
		}

		function showSmallScreen() {
			$('.screen_1024').css('display', '');
			$('.screen_big').css('display', 'none');
			$('.foot_list').removeClass('on');
			if ($.browser.msie && $.browser.version == '6.0') {
				$('.foot_list').css({
					'background' : 'none',
					'height' : '23px'
				});
			}
			$(option.tipBox).hide();
		}

		//递归给所有iframe注册click事件，隐藏底部提示框
		function bindIframeClick(o) {
			var doc;
			if (o == top) {
				doc = o.document;

				var frames = doc.getElementsByTagName('iframe');
				for (var i = 0; i < frames.length; i++) {
					bindIframeClick(frames[i]);
				}
			} else {
				doc = o.contentDocument || (o.contentWindow ? o.contentWindow.document : null) || o.ownerDocument;
				bindDocClick(doc);

				$(o).bind('load', function () {
					var doc = this.contentDocument || (this.contentWindow ? this.contentWindow.document : null) || o.ownerDocument;
					bindDocClick(doc);

					var frames = doc.getElementsByTagName('iframe');
					for (var i = 0; i < frames.length; i++) {
						bindIframeClick(frames[i]);
					}
				});
			}

		}

		function bindDocClick(doc) {
			if (!doc)
				return;
			$(doc).live('click', function (e) {
				$('.footer', top.document).trigger('click');
			});
		}

		//点击"其他信息"
		function bindContainerClick() {
			$.each(containers, function (i, item) {
				$(item).css({
					'cursor' : 'pointer'
				});
				$(item).live('click', function (e) {
					var _self = this;
					$.each(containers, function (i, sitem) {
						if ($(sitem)[0] !== $(_self)[0]) {
							if ($.browser.msie && $.browser.version == '6.0') {
								$(sitem).css({
									'background' : 'none',
									'height' : '23px'
								});
							}
							$(option.tipBox, $(sitem)).hide();
							$(sitem).removeClass('on');
						}
					});

					$(option.tipBox, $(this)).toggle();
					if ($.browser.msie && $.browser.version == '6.0') {
						if ($(option.tipBox, $(this)).is(":visible") == true) {
							$(this).css({
								'background' : '#185caf',
								'height' : '16px'
							});
						} else {
							$(this).css({
								'background' : 'none',
								'height' : '23px'
							});
						}
					} else {
						$(this).toggleClass('on');
					}

					e.stopPropagation();
				})
			})
		}

		$(function () {
			if (!option.responsive && option.screen == "small") {
				showSmallScreen();
			}

			if (option.responsive) {
				footerResponsive();
				UI.EA(window, 'resize', function () {
					footerResponsive();
				});
			}

			bindIframeClick(window);
			bindContainerClick();

			$(document).live('click', function () {
				$.each(containers, function (i, item) {
					if ($.browser.msie && $.browser.version == '6.0') {
						$(item).css({
							'background' : 'none',
							'height' : '23px'
						});
					} else if ($(item).hasClass('on')) {
						$(item).removeClass('on');
					}
					$(option.tipBox, $(item)).hide();
				});
			});
		})
	}
}
	(window.jQuery, window));
//top框架menu
UI.Menu_top = function(o) {
	this.name = o.name;
	this.id = o.id;
	this.sub_id = o.sub_id;
	this.contant = UI.G(o.contant_id);
	this.main = UI.G(o.id);
	this.body = UI.G(o.sub_id);
	this.target = o.target; //Target Iframe
	this.cache = (o.cache == undefined ? true : o.cache); //Cache Menu Status
	this.data = o.data;
	this._cookie = "top_menustatue_"+UI.GT(document,'title')[0].innerHTML.replace(/\W/g,"");
	this.breadcrumb = [];
	this.callback = o.callback;
	
	//Show Location Information
	this.location = {
		data : [],
		rel : null,
		rebuild : function(o) {
			var o = eval('[' + o + ']');
			this.rel = o;
			this.data = [];
			for (var i=0;i<o.length;i++) {
				if (!i) this.data.push({name:this.tmp[o[i]].name,url:this.tmp[o[i]].url});
				try{
					if (i==1) this.data.push({name:this.tmp[o[i-1]].data[o[i]].name,url:(this.tmp[o[i-1]].data[o[i]].url ? this.tmp[o[i-1]].data[o[i]].url : this.tmp[o[i-1]].data[o[i]].data[0][1])});
					if (i==2) this.data.push({name:this.tmp[o[i-2]].data[o[i-1]].data[o[i]][0],url:this.tmp[o[i-2]].data[o[i-1]].data[o[i]][1]});
				}catch(e){}
			}
		},
		edit : function(n,u) {
			this.data.pop();
			this.data.push({name:n,url:u,fake:true});
		},
		add : function(n,u) {
			if (n != this.data[this.data.length - 1].name) {
				this.data.push({name:n,url:u,fake:true});
			}
		},
		remove : function() {
			this.data.pop();
		},
		show : function(n) {
			this.data.splice(n +1,50);
		}
	}
	this.location.cache = this.cache;
	this.location.tmp = o.data;
	this.show = function(o,load) { //Show Menu
	try{
		var url;
		var old_o=this.cur_list;
		if (UI.isArray(o)) o = this.index(o); //Search By Menu Name
		else var o = o.split(',');
		if (o.length <= 2) {
			o.push(0);
			if (o.length == 2 && this.location.tmp[o[0]].data.length) o.push(0);
		}
		
		//fiter the o
		var tmp = this.location.tmp;
		for(var i=0;i<o.length;i++){
			if(o[i]<tmp.length){
				tmp=tmp[o[i]].data;
			}else{
				o[i]=0;
				i!=2?tmp=tmp[o[i]].data:null;
			}
		}
		
		var _delay,self=this.body,contant=this.contant,main=this.main;
		if(this.location.tmp[o[0]].data.length==1 && this.location.tmp[o[0]].data[0].data.length==0 )
		{	
			clearTimeout(_delay);
			_delay = setTimeout(function() {
			UI.addClass(self,'hide');
			},250);
		}
		else{
			clearTimeout(_delay);
			_delay = setTimeout(function() {
			UI.removeClass(self,'hide');
			},250);
		}
		
		if (true) {
			url = this.data[o[0]].data[o[1]].url || this.data[o[0]].url;
			try {
				url = this.data[o[0]].data[o[1]].data[o[2]][1];
			}catch(e){};
		}
         //cookie menu statue
		 UI.cookie(this._cookie,"",10);
		 UI.cookie(this._cookie,o,1);
		//List & Main Menu
		this.menu_list[this.cur_list].className = 'second_menu hide';
		this.menu_list[o[0]].className = 'second_menu show';
		UI.removeClass(this.main_menu[this.cur_list],'selected');
		UI.addClass(this.main_menu[o[0]],'selected');
		if(parseInt(o[1])<jQuery('.second_menu.show').children('.second_menu_li').not('.hide').length){
			jQuery('.second_menu.show .menu_more').addClass('hide');
		}

		if (this.cache) 
		{var new_rel =o[0]+','+o[1]+','+o[2];
		 this.main_menu[o[0]].setAttribute('rel',new_rel);
		 UI.children(this.main_menu[o[0]])[0].setAttribute('href',url);
		 UI.children(UI.GC(this.menu_list[o[0]],'.second_menu_li')[o[1]])[0].setAttribute('rel',new_rel);
		 UI.children(UI.GC(this.menu_list[o[0]],'.second_menu_li')[o[1]])[0].setAttribute('href',url);
		 
		 }
		this.cur_list = o[0];

		try{
			UI.removeClass(UI.GC(this.menu_list[o[0]],'.selected')[0],'selected');
			//UI.removeClass(UI.GC(this.menu_list[o[0]],'.selecting')[0],'selecting');
			UI.each(UI.GC(this.menu_list[this.cur_list],'.third_menu'),function(on){UI.addClass(on,'hide');})
			
		}catch(e){};
		var show_li_len=jQuery('.second_menu.show').children('.second_menu_li').not('.hide').length
		for (var i=1;i<o.length;i++) {
			if (i==1 && this.location.tmp[o[0]].data.length) {
				UI.each(UI.GC(UI.GC(this.body,'.second_menu.show')[0],'li.selected'),function(on){
					{
						UI.removeClass(on,'selected');	
					}
				});
				if(show_li_len!=0&&parseInt(o[1])>show_li_len-1){
						jQuery('.second_menu.show').find(' .menu_more').parent().addClass('selected');
					}
				UI.addClass(UI.GC(UI.GC(this.body,'.second_menu.show')[0],'li.second_menu_li')[o[1]],'selected');
				//this.main_menu[o[0]].href = this.location.tmp[o[0]].data[o[1]].url;
				//UI.each(UI.GT(UI.GC(this.body,'.second_menu.show')[0],'ul'),function(on){
				//	if(typeof(on)!="string")
				//	{UI.removeClass(on,'show');UI.addClass(on,'hide');}
				//});
			}
			if (i==2 && this.location.tmp[o[0]].data[o[1]].data.length) {
				var menu_title = this.menu_title[o[0]][o[1]][0];
				//UI.removeClass(menu_title,'hide');
				if (this.location.tmp[o[0]].data[o[1]].data.length) {
					UI.addClass(menu_title.getElementsByTagName('li')[o[2]],'selected');
					//if (this.cache) this.main_menu[o[0]].href = this.location.tmp[o[0]].data[o[1]].data[o[2]].url;
				}

			    //else if (this.cache) this.main_menu[o[0]].href = this.location.tmp[o[0]].data[o[1]].url;
			}
		}
		this.location.rebuild(o);
		if (url) window[this.target].location.href = url;
		this.breadcrumb = [];
		if(this.data.length>=parseInt(o[0])+1){this.breadcrumb.push([this.data[o[0]].name,this.data[o[0]].url]);}
		if(this.data[o[0]].data.length>=parseInt(o[1])+1)
		{
			this.breadcrumb.push([this.data[o[0]].data[o[1]].name,this.data[o[0]].data[o[1]].url]);
			if(this.data[o[0]].data[o[1]].data.length>=parseInt(o[2])+1)
			{
				
				this.breadcrumb.push([this.data[o[0]].data[o[1]].data[o[2]][0],this.data[o[0]].data[o[1]].data[o[2]][1]]);
			}
		}
		this.auto_second_menu();
		if(old_o!=o[0]){
				this.bind_li_more();
				this.auto_second_menu_event();
		}
		this.callback?this.callback(this.breadcrumb):'';
		
	}catch(e){}
	}
	this.go = function(o) {
		this.show(o,true);
	}
	this.index = function(o) { //Get Menu Index Number With Menu Name
		var tmp = [];
		for (var i=0;i<o.length;i++) {
			if (i == 0) {
				for (var j=0;j<this.data.length;j++) {
					if (o[i] == this.data[j].name) {
						tmp.push(j);
						break;
					}
				}
			}
			if (i == 1&&tmp[0] != undefined) {
				for (var j=0;j<this.data[tmp[0]].data.length;j++) {
					if (o[i] == this.data[tmp[0]].data[j].name) {
						tmp.push(j);
						break;
					}
				}
			}
			if (i == 2&&tmp[1] != undefined) {
				for (var j=0;j<this.data[tmp[0]].data[tmp[1]].data.length;j++) {
					if (o[i] == this.data[tmp[0]].data[tmp[1]].data[j][0]) {
						tmp.push(j);
						break;
					}
				}
			}
		}
		return tmp;
	}

	/* Sub Menu & Main Menu */
	var html = [],html_main = [],li_more=[];
	html_main.push('<ul class="first_menu">');
	for (var i = 0; i < o.data.length; i++) {
		if(o.data[i] && o.data[i].data && o.data[i].data.length >0)	{
			html.push('<ul class="second_menu' + (!i ? ' show': ' hide') + '">');
			li_more=[];
			for (var j = 0; j < o.data[i].data.length; j++) {
				html.push('<li class="' + (!j ? ' selected': '') +' second_menu_li" ><a  id="two'+i + j +'" href="' + o.data[i].data[j].url + '" target="' + this.target + '" onfocus="this.blur()" onclick="'+ this.name + '.show(this.getAttribute(\'rel\'));'+ (o.data[i].data[j].data.length ? ' UI.toggleClass(UI.next(this),'+"'hide'"+')': '')+'" rel="' + i + ',' + j  + (o.data[i].data[0].data.length ? ',0': '') +'">' + o.data[i].data[j].name +(o.data[i].data[j].data.length ? ' <span class="more"></span>': '')+'</a>');
				
				if(o.data[i].data[j].data.length == 0){
					li_more.push('<li class="' + (!j ? ' selected': '') +' second_menu_li hide" ><a  id="two'+i + j +'" href="' + o.data[i].data[j].url + '" target="' + this.target + '" onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));'+ (o.data[i].data[j].data.length ? ' UI.toggleClass(UI.next(this),'+"'hide'"+')': '')+'" rel="' + i + ',' + j  + (o.data[i].data[0].data.length ? ',0': '') +'">' + o.data[i].data[j].name +(o.data[i].data[j].data.length ? ' <span class="more"></span>': '')+'</a>');
				}
				if(o.data[i].data[j].data.length >0){
				html.push('<ul  class="' +  'third_menu hide'+ '" onmouseout=UI.addClass(this,"hide") onmouseover=UI.removeClass(this,"hide")>');
				li_more.push('<li class="has_ul ' + (!j ? ' selected': '') +' second_menu_li hide" ><a  id="two'+i + j +'" href="' + o.data[i].data[j].url + '" target="' + this.target + '" onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));'+ (o.data[i].data[j].data.length ? ' UI.toggleClass(UI.next(this),'+"'hide'"+')': '')+'" rel="' + i + ',' + j  + (o.data[i].data[0].data.length ? ',0': '') +'">' + o.data[i].data[j].name +(o.data[i].data[j].data.length ? ' <span class="more"></span>': '')+'</a>');
				li_more.push('<ul  class="' +  'third_menu hide">');
				for (var m = 0; m < o.data[i].data[j].data.length; m++) {
					html.push('<li  class="' + (m == 0 ? 'selected': '') + '"><a id="three'+i + j + m +'" href="' + o.data[i].data[j].data[m][1] + '" target="' + this.target + '" onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));"' + ' rel="' + i + ',' + j + ',' + m + '" title="' + o.data[i].data[j].data[m][0] + '">' + o.data[i].data[j].data[m][0] + '</a></li>');
					li_more.push('<li  class="' + (m == 0 ? 'selected': '') + '"><a id="three'+i + j + m +'" href="' + o.data[i].data[j].data[m][1] + '" target="' + this.target + '" onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));"' + ' rel="' + i + ',' + j + ',' + m + '" title="' + o.data[i].data[j].data[m][0] + '">' + o.data[i].data[j].data[m][0] + '</a></li>');

				}
				html.push('</ul>');
				li_more.push('</ul>');
				}
				html.push('</li>');
				li_more.push('</li>');
			}
			//html.push('</ul>');
			html.push('<li class="li_more" style="cursor:pointer;"  ><a  onfocus="this.blur()" rel="" target="'+this.target+'" >more <span class="more"></span></a><ul class="menu_more hide">'+li_more.join('')+'</ul></li></ul>');
		}

		html_main.push('<li  class="' + (i == 0 ? 'selected': '') + '" onclick="' + this.name + 
			'.show(this.getAttribute(\'rel\'));' + (o.data[i].call ? o.data[i].call: '') + 
			'" rel="' + i + ',0' + (o.data[i].data && o.data[i].data[0].data.length ? ',0': '') + '"><a id="one'+i+
			'" href="' + o.data[i].url + '" target="' + this.target + '" title="' + o.data[i].name + 
			'" onfocus="this.blur()"  title="' + o.data[i].name + '">' + o.data[i].name + '</a></li>');
	}
	html_main.push('</ul>');
	this.body.innerHTML = html.join('');
	this.main.innerHTML = html_main.join('');
	this.main.style.left = UI.width(UI.GC(document,'.logo')[0])+50+'px'
    //Menu list
	this.main_menu = UI.GT(this.main,'li');
	this.menu_list = UI.GC(this.body,'ul.second_menu');
	this.menu_title = [];
	var menu_title_left = [];
	for (var i=0;i<this.menu_list.length;i++) {
		menu_title_left = [];
		UI.each(UI.GC(this.menu_list[i],'.second_menu_li'),function(on){
			menu_title_left.push(UI.GT(on,'ul'));
		});
		this.menu_title.push(menu_title_left);
	}
	this.cur_list = 0;
	
	var  self=this;
	this.bind_li_more=function(){
	jQuery(jQuery('.second_menu.show .li_more').children()[0]).bind('click',function(){
		self.show(this.getAttribute('rel')); 
		jQuery(this).parent().addClass('selected'); 
		UI.removeClass(UI.next(this),'hide');
		var s_li=jQuery(this).next().find('.selected')[0];
		if(s_li.children.length>1){
			jQuery(s_li.children[1]).removeClass('hide');
			var s_li_rel=s_li.children[0].getAttribute('rel').split(',')[2]||0;
			jQuery(s_li.children[1].children[s_li_rel]).addClass('selected');
		}
	})
	}
	//cookie设置菜单选中项
	this.reload_menu_statue = function(){
		//var c = getCookie("left_menustatue");
		var c = UI.cookie(this._cookie);
		if(c==null||c==''){this.show('0,0,0')}
		else{
			var S = c.split(',');
			if(S.length==3)
			{
			   this.show(S[0]+','+S[1]+','+S[2]);	
			}
		}
	}
	//List Auto Height
	this.reload_menu_statue();
	//List Auto Height
	document.body.style.overflow = 'hidden';
	var _menu_height = UI.GC('.header')[0].scrollHeight + UI.GC('.second_menu_div')[0].scrollHeight ;
	if(UI.GC('.footer').length>0){_menu_height+= UI.GC('.footer')[0].scrollHeight;}
	this.autoHeight = function() {
		this.contant.style.height = (UI.Browser.ie ? UI.windowHeight() - _menu_height - 4 : window.innerHeight - _menu_height) + 'px';
	};
	this.autoHeight();
	this.autoTools= function() {
		var tool_list = UI.children(UI.children(UI.G('tool'))[0]);
		if(tool_list.length==9)
		{
			if(document.documentElement.scrollWidth>1024)
			{
				for(var i=2;i<8;i++)
				{tool_list[i].style.display = "block";}
				 tool_list[8].style.display = "none";
			}
			else
			{
				for(var i=2;i<8;i++)
				{tool_list[i].style.display = "none";}
				 tool_list[8].style.display = "block";
			}
		}
	};
	this.autoTools();
	

	//suit top-menu
	this.auto_second_menu=function(){
		var current_ul=UI.GC('.second_menu.show')[0];
		if(!current_ul) return;
		var current_lis=current_ul.children;

		jQuery(current_lis).addClass('hide');
		jQuery('.second_menu.show .menu_more').children().removeClass('hide');
		var ul_width=0;
		var c_li = null;
		for(var i=0;i<current_lis.length;i++){
			jQuery(current_lis[i]).removeClass('hide');
			c_li=jQuery(jQuery('.second_menu.show .menu_more')[0].children[i]);
			if(c_li.hasClass('selected')){
				jQuery(current_lis[i]).addClass('selected');	
				jQuery('.li_more').removeClass('selected');
				//jQuery('.li_more')[0].children[0].setAttribute('rel',jQuery(jQuery('.second_menu.show .menu_more')[0].children[i+1])[0].children[0].getAttribute('rel'));
			}
			c_li.addClass('hide').removeClass('selected');
						
			if(jQuery(current_lis[i]).hasClass('selected')){
				c_li.addClass('selected');
				//jQuery('.li_more').addClass('selected');
			}
			
			ul_width += current_lis[i].clientWidth;
			var offset_w = jQuery('.tab_apply').length>0 ? jQuery('.tab_apply')[0].offsetLeft : jQuery('#sub_menu').width()-32;
			if(ul_width+16>offset_w)
			{
				if(jQuery(current_lis[i-1]).hasClass('selected')){
					jQuery('.second_menu.show').find(' .li_more').addClass('selected');
					jQuery('.second_menu.show').find(' .li_more')[0].children[0].setAttribute('rel',jQuery(current_lis[i-1])[0].children[0].getAttribute('rel'));
					jQuery('.second_menu.show').find(' .li_more')[0].children[0].setAttribute('href',jQuery(current_lis[i-1])[0].children[0].getAttribute('href'));
				}
				jQuery(current_lis[i]).addClass('hide');
				c_li.removeClass('hide');

				

				jQuery(current_lis[i-1]).addClass('hide');
				jQuery(jQuery('.second_menu.show').find(' .menu_more')[0].children[i-1]).removeClass('hide');
				jQuery('.second_menu.show').find(' .menu_more').parent().removeClass('hide');
				
				//break;
			}
			else{
				if(i==current_lis.length-1){
					jQuery('.second_menu.show').find(' .menu_more').parent().addClass('hide');	
				}
				//jQuery(jQuery('.second_menu.show .menu_more')[0].children[i]).addClass('hide');
			}
		}
		if(jQuery('.second_menu.show .menu_more').find('.second_menu_li').not('.hide').length==0){
			jQuery('.second_menu.show').find(' .menu_more').parent().addClass('hide');
		}
		else{
			if(jQuery('.second_menu.show').find(' .menu_more').find('.hide.selected').length){
				jQuery('.second_menu.show').find(' .li_more')[0].children[0].setAttribute('rel',jQuery('.second_menu.show').find('  .menu_more').find('.second_menu_li').not('.hide')[0].children[0].getAttribute('rel'));
			}
		}
	};
	this.auto_second_menu_event=function(){
		var self=this;
		
		function contains(parentNode, childNode) {
			if (parentNode.contains) {
				return parentNode != childNode && parentNode.contains(childNode);
			} else {
				return !!(parentNode.compareDocumentPosition(childNode) & 16);
			}
		}
		function checkHover(e,target){
			if (getEvent(e).type=="mouseover")  {
				return !contains(target,getEvent(e).relatedTarget||getEvent(e).fromElement) && !((getEvent(e).relatedTarget||getEvent(e).fromElement)===target);
			} else {
				return !contains(target,getEvent(e).relatedTarget||getEvent(e).toElement) && !((getEvent(e).relatedTarget||getEvent(e).toElement)===target);
			}
		}function getEvent(e){
			return e||window.event;
		}
		
		jQuery('.second_menu.show .menu_more').bind('mouseout',function(e){
			if(checkHover(e,this)){
				//setTimeout(function(){
					jQuery('.second_menu.show').find('.menu_more').addClass('hide');
				//},200)
			}
		})
		
		//jQuery('.second_menu.show').find('.menu_more').unbind('mouseout')
		
		jQuery('.second_menu.show').find(' .menu_more').find('a').bind('click',function(){
			
			jQuery('.second_menu.show').find('.menu_more').unbind('mouseout')
			//jQuery('.second_menu.show').find('.menu_more').height(jQuery('.second_menu.show').find('.menu_more').height()+100+'px');
			
			var new_rel=this.getAttribute('rel');
			var url=this.getAttribute('href');
			var o=new_rel.split(',');
			if(o.length<=2){
				new_rel+=',0';
				o.push('0');
			}
			if (self.cache) 
			{
			 self.main_menu[o[0]].setAttribute('rel',new_rel);
			 UI.children(self.main_menu[o[0]])[0].setAttribute('href',url);
			 UI.children(UI.GC(self.menu_list[o[0]],'.second_menu_li')[o[1]])[0].setAttribute('rel',new_rel);
			 UI.children(UI.GC(self.menu_list[o[0]],'.second_menu_li')[o[1]])[0].setAttribute('href',url);
			 jQuery(this).parent().parent().prev()[0].setAttribute('rel',new_rel);
			  jQuery(this).parent().parent().prev()[0].setAttribute('href',url);
			 jQuery('.second_menu.show').find(' .li_more').find('a')[0].setAttribute('rel',new_rel);
			 jQuery('.second_menu.show').find(' .li_more').find('a')[0].setAttribute('href',url);
			}
			
			jQuery(this).parent().parent().children().removeClass('selected');
			jQuery(this).parent().addClass('selected');
		
			if(this.parentNode.children.length==1)
			{
				//self.show(this.getAttribute('rel')); 
				jQuery('.second_menu.show .menu_more').addClass('hide')
			}
			else{
				jQuery(jQuery(this).next()[0].children[o[2]]).addClass('selected');
			}
			
			setTimeout(function(){
			jQuery('.second_menu.show .menu_more').bind('mouseout',function(e){
			if(checkHover(e,this)){
				//setTimeout(function(){
					jQuery('.second_menu.show').find('.menu_more').addClass('hide');
				//},200)
			}
			})
			},200);
		
		
		})
	}
	
	
	this.auto_second_menu();
	this.bind_li_more();
	this.auto_second_menu_event();
	
	(function(n){
		UI.EA(window,'resize',function(){
			window[n].autoHeight();
			window[n].autoTools();
			window[n].auto_second_menu();
		});
	})(this.name);

};

//top_left框架menu
UI.Menu_topleft = function(o) {
	this.name = o.name;
	this.id = o.id;
	this.sub_id = o.sub_id;
	this.contant = UI.G(o.contant_id);
	this.main = UI.G(o.id);
	this.body = UI.G(o.sub_id);
	this.target = o.target; //Target Iframe
	this.cache = (o.cache == undefined ? true : o.cache); //Cache Menu Status
	this.data = o.data;
	this._cookie = "topleft_menustatue_"+UI.GT(document,'title')[0].innerHTML.replace(/\W/g,"");
	var selfthis = this;
	
	//Show Location Information
	this.location = {
		data : [],
		rel : null,
		rebuild : function(o) {
			var o = eval('[' + o + ']');
			this.rel = o;
			this.data = [];
			for (var i=0;i<o.length;i++) {
				if (!i) this.data.push({name:this.tmp[o[i]].name,url:this.tmp[o[i]].url});
				try{
					if (i==1) this.data.push({name:this.tmp[o[i-1]].data[o[i]].name,url:(this.tmp[o[i-1]].data[o[i]].url ? this.tmp[o[i-1]].data[o[i]].url : this.tmp[o[i-1]].data[o[i]].data[0][1])});
					if (i==2) this.data.push({name:this.tmp[o[i-2]].data[o[i-1]].data[o[i]][0],url:this.tmp[o[i-2]].data[o[i-1]].data[o[i]][1]});
				}catch(e){}
			}
		},
		edit : function(n,u) {
			this.data.pop();
			this.data.push({name:n,url:u,fake:true});
		},
		add : function(n,u) {
			if (n != this.data[this.data.length - 1].name) {
				this.data.push({name:n,url:u,fake:true});
			}
		},
		remove : function() {
			this.data.pop();
		},
		show : function(n) {
			this.data.splice(n +1,50);
		}
	}
	this.location.cache = this.cache;
	this.location.tmp = o.data;

	this.show = function(o,load,data) { //Show Menu
	try{
		var url;
		load = load!=undefined?load:true;
		if (UI.isArray(o)) o = this.index(o); //Search By Menu Name
		else var o = o.split(',');
		if (o.length <= 2) {
			o.push(0);
			if (o.length == 2 && this.location.tmp[o[0]].data.length) o.push(0);
		}
		//fiter the o
		var tmp = this.location.tmp;
		for(var i=0;i<o.length;i++){
			if(o[i]<tmp.length){
				tmp=tmp[o[i]].data;
			}else{
				o[i]=0;
				i!=2?tmp=tmp[o[i]].data:null;
			}
		}
		
		var _delay,self=this.body,contant=this.contant,main=this.main;
		if(this.location.tmp[o[0]].data.length==1 && this.location.tmp[o[0]].data[0].data.length==0 )
		{	
			clearTimeout(_delay);
			_delay = setTimeout(function() {
				UI.addClass(self,'hide');
				UI.addClass(contant,'main_auto');
				UI.addClass(UI.GC(main,'.bar')[0],'hide');
			},250);
		}
		else{
			clearTimeout(_delay);
			_delay = setTimeout(function() {
				UI.removeClass(self,'hide');
				UI.removeClass(contant,'main_auto');
				UI.removeClass(UI.GC(main,'.bar')[0],'hide');
				UI.addClass(UI.GC(main,'.bar')[0],'on');
				UI.removeClass(UI.GC(main,'.bar')[0],'hover');
			},250);
		}
		if (load) {
			url = this.data[o[0]].data[o[1]].url || this.data[o[0]].url;
			try {
				url = this.data[o[0]].data[o[1]].data[o[2]][1];
			}catch(e){};
		}
        //cookie menu statue
		UI.cookie(this._cookie,"",10);
		UI.cookie(this._cookie,o,1);
		//List & Main Menu
		this.menu_list[this.cur_list].className = 'left_menu hide';
		this.menu_list[o[0]].className = 'left_menu show';
		UI.removeClass(this.main_menu[this.cur_list],'selected');
		UI.addClass(this.main_menu[o[0]],'selected');
		if (this.cache) {var new_rel =o[0]+','+o[1]+','+o[2]; this.main_menu[o[0]].setAttribute('rel',new_rel);UI.children(this.main_menu[o[0]])[0].setAttribute('href',url);}
		this.cur_list = o[0];

		try{
			UI.removeClass(UI.GC(this.menu_list[o[0]],'.selected')[0],'selected');
		}catch(e){};
		this.bindEvent=function(){
			for (var i=1;i<o.length;i++) {
				if (i==1 && selfthis.location.tmp[o[0]].data.length) {
					UI.each(UI.GC(UI.GC(selfthis.body,'.left_menu.show')[0],'li.selected'),function(on){
						UI.removeClass(on,'selected');
					});
					UI.addClass(UI.GC(UI.GC(selfthis.body,'.left_menu.show')[0],'li.second_menu')[o[1]],'selected');
					UI.each(UI.GT(UI.GC(selfthis.body,'.left_menu.show')[0],'ul'),function(on){
						if(typeof(on)!="string")
						{UI.removeClass(on,'show');UI.addClass(on,'hide');}
					});
				}
				if (i==2 && selfthis.location.tmp[o[0]].data[o[1]].data.length) {//设置三级菜单选中
					var menu_title = selfthis.menu_title[o[0]][o[1]][0];
					UI.removeClass(menu_title,'hide');
					if (selfthis.location.tmp[o[0]].data[o[1]].data.length) {
						UI.addClass(menu_title.getElementsByTagName('li')[o[2]],'selected');
					}
				}
			}
		}
		this.bindEvent();
		this.location.rebuild(o);
		if (url) {
			var par='';
			if(data){
				par = typeof data === 'object'? UI.param(data) : data;
				
				if(url.indexOf('?')>=0){
					url=url+'&'+par;
				}
				else{
					url=url+'?'+par;
				}
			}
			
			window[this.target].location.href = url;
		}
		}catch(e){}
	}
	this.go = function(o,data) {
		var op=o,flag=false;
		if(UI.isString(o)){
			op=o.split(',');
			if(op.length>0 && isNaN(op[0])){
				op=this.indexUrl(o);
				if(op.length<=0){
					return false;
				}
				else{
					flag=true;
					o=op.join(',');
				}
			}
		}
		if(!flag && !this.validatePath(op)){
			return false;
		}
		
		this.show(o,true,data);
		
		return true;
	}
	this.refreshMenu = function(o){
		UI.cookie(this._cookie,"",10);
		this.data = o.data;
		if(typeof jQuery!='undefined' && jQuery.jsoncookie){
			jQuery.jsoncookie('menudata',this.data);
		}
		
		this._init();
		this.reload_menu_statue();
	}
	this.addSubmenu = function(o){
		if(!o.url||!o.data){
			return;
		}
		var menuid = selfthis.indexUrl(o.url);

		var s_data = o.data;
		if(!menuid.length || UI.Objequals(this.data[menuid[0]].data,s_data)){
			return ;
		}
		var id = menuid[0];
		
		this.data[id].data = jQuery.extend(true,[],s_data);
		//add submenu to html
		var old_l = jQuery('.left_menu')[id].children.length;
		var newhtml=[];
		for (var j = 0; j < s_data.length; j++) {
			newhtml.push('<li class="'  + (!s_data[j].data.length ? ' nochild': '') +' second_menu" ><a id="two'+id + j +'" href="javascript:void(0)"  onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));" rel="' + id + ',' + j  + (s_data[0].data.length ? ',0': '') + '">' + s_data[j].name +'</a>');
			if(s_data[j].data.length >0){
			newhtml.push('<ul  class="' + (!j ? ' show left_submenu': 'hide left_submenu') + '">');
			for (var m = 0; m < s_data[j].data.length; m++) {
				newhtml.push('<li  class="' + (m == 0 ? 'selected': '') + ' third_menu"><a id="three'+id + j + m +'" href="javascript:void(0)"  onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));"' + ' rel="' + id + ',' + j + ',' + m + '" title="' + s_data[j].data[m][0] + '">' + s_data[j].data[m][0] + '</a></li>');
			}
			newhtml.push('</ul>');
			}
			newhtml.push('</li>');
		}

		jQuery(jQuery('.left_menu')[id]).empty().append(jQuery(newhtml.join('')));
		
		selfthis.menuupdate();
		selfthis.bindEvent();
	};
	
	this.index = function(o) { //Get Menu Index Number With Menu Name
		var tmp = [];
		for (var i=0;i<o.length;i++) {
			if (i == 0) {
				for (var j=0;j<this.data.length;j++) {
					if (o[i] == this.data[j].name) {
						tmp.push(j);
						break;
					}
				}
			}
			if (i == 1&&tmp[0] != undefined) {
				for (var j=0;j<this.data[tmp[0]].data.length;j++) {
					if (o[i] == this.data[tmp[0]].data[j].name) {
						tmp.push(j);
						break;
					}
				}
			}
			if (i == 2&&tmp[1] != undefined) {
				for (var j=0;j<this.data[tmp[0]].data[tmp[1]].data.length;j++) {
					if (o[i] == this.data[tmp[0]].data[tmp[1]].data[j][0]) {
						tmp.push(j);
						break;
					}
				}
			}
		}
		return tmp;
	}
	
	
	this.indexUrl = function(o) { //Get Menu Index Number With Menu Name
		var tmp = [];
		var url='',index=-1;
		for (var i=0;i<this.data.length;i++) {
			for(var j=0;j<this.data[i].data.length;j++){
				if(o===this.data[i].data[j].url){
					url=this.data[i].data[j].url;
					tmp.push(i);
					tmp.push(j);
					break;
				}
				
				for(var k=0;k<this.data[i].data[j].data.length;k++){
					if(this.data[i].data[j].data[k].length>0 && o===this.data[i].data[j].data[k][1]){
						url=this.data[i].data[j].data[k][1];
						tmp.push(i);
						tmp.push(j);
						tmp.push(k);
						break;
					}
				}
				
				if(url.length>0){
					break;
				}
			}
			
			if(url.length>0){
				break;
			}
			
			if(o===this.data[i].url){
				index = i;
			}
		}
		
		if(url.length<=0 && index>=0){
			tmp.push(index);
			tmp.push(0);
		}

		return tmp;
	}
	
	this.validatePath = function(o) { //Get Menu Index Number With Menu Name
		if(UI.isString(o)){
			o = o.split(',');
		}
		var tmp = [];
		var flag = true;
		for (var i=0;i<o.length;i++) {
			if (i == 0) {
				if(!isNaN(o[i]) && this.data.length > parseInt(o[i])){
					tmp.push(o[i]);
				}
				else{
					for (var j=0;j<this.data.length;j++) {
						if (o[i] == this.data[j].name) {
							tmp.push(j);
							break;
						}
					}
				}
				
				if(this.data.length>0 && tmp.length<=0){
					flag = false;
					break;
				}
			}
			if (i == 1&&tmp[0] != undefined) {
				if(!isNaN(o[i]) && this.data[tmp[0]].data.length > parseInt(o[i])){
					tmp.push(o[i]);
				}
				else{
					for (var j=0;j<this.data[tmp[0]].data.length;j++) {
						if (o[i] == this.data[tmp[0]].data[j].name) {
							tmp.push(j);
							break;
						}
					}
				}
				
				if(this.data[tmp[0]].data.length>0 && tmp.length<=1){
					flag = false;
					break;
				}
			}
			if (i == 2&&tmp[1] != undefined) {
				if(!isNaN(o[i]) && this.data[tmp[0]].data[tmp[1]].data.length > parseInt(o[i])){
					tmp.push(o[i]);
				}
				else{
					for (var j=0;j<this.data[tmp[0]].data[tmp[1]].data.length;j++) {
						if (o[i] == this.data[tmp[0]].data[tmp[1]].data[j][0]) {
							tmp.push(j);
							break;
						}
					}
				}
				
				if(this.data[tmp[0]].data[tmp[1]].data.length>0 && tmp.length<=2){
					flag = false;
					break;
				}
			}
		}
		return flag;
	}

	this._init = function(){
		/* Sub Menu & Main Menu */
		var html = [],html_main = [];
		var menuData = this.data;
		//从缓存中提取菜单数据
		if(typeof jQuery!='undefined' && jQuery.jsoncookie && jQuery.jsoncookie('menudata')){
			menuData = jQuery.jsoncookie('menudata');
		}
		
		if(o.top_menu_hide==true && menuData.length==1){
			UI.addClass(this.contant,'main_top');
			UI.addClass(this.main,'hide');
			UI.addClass(this.body,'hide');
			if(menuData[0].data.length==0){
				UI.addClass(this.contant,'main_auto');
			}
		}
		
		this.data = menuData;
		this.location.tmp = menuData;

		html_main.push('<ul class="top_menu">');
		for (var i = 0; i < menuData.length; i++) {
			if (!i) {
				html_main.push('<a class="' + (!menuData[i].data.length ? ' bar': 'bar on') + '" href="javascript:void(0)"/></a>');
			}
			if(menuData[i].data.length == 0){
				menuData[i].data.push({name:menuData[i].name,url:menuData[i].url,data:[]});
			}
			if(menuData[i].data.length >0){
				html.push('<ul class="left_menu' + (!i ? ' show': ' hide') + '">');
				for (var j = 0; j < menuData[i].data.length; j++) {
					html.push('<li class="' + (!j ? ' selected': '') + (!menuData[i].data[j].data.length ? ' nochild': '') +' second_menu" ><a id="two'+i + j +'" href="javascript:void(0)"  onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));" rel="' + i + ',' + j  + (menuData[i].data[0].data.length ? ',0': '') + '">' + menuData[i].data[j].name +'</a>');
					if(menuData[i].data[j].data.length >0){
					html.push('<ul  class="' + (!j ? ' show left_submenu': 'hide left_submenu') + '">');
					for (var m = 0; m < menuData[i].data[j].data.length; m++) {
						html.push('<li  class="' + (m == 0 ? 'selected': '') + ' third_menu"><a id="three'+i + j + m +'" href="javascript:void(0)"  onfocus="this.blur()" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));"' + ' rel="' + i + ',' + j + ',' + m + '" title="' + menuData[i].data[j].data[m][0] + '">' + menuData[i].data[j].data[m][0] + '</a></li>');
					}
					html.push('</ul>');
					}
					html.push('</li>');
				}
				html.push('</ul>');
			}
			var title = menuData[i].title?menuData[i].title:menuData[i].name;//title属性
			html_main.push('<li  class="' + (i == 0 ? 'selected': '') + '" onclick="' + this.name + '.show(this.getAttribute(\'rel\'));' + (menuData[i].call ? menuData[i].call: '') + '" rel="' + i + ',0' + (menuData[i].data[0].data.length ? ',0': '') + '"><a id="one'+i +'" href="javascript:void(0)" target="' + this.target + '" onfocus="this.blur()"  title="' + title + '">' + menuData[i].name + '</a></li>');
		}
		html_main.push('</ul>');

		this.body.innerHTML = html.join('');
		this.main.innerHTML = html_main.join('');

	    //Menu list
		this.main_menu = UI.GT(this.main,'li');
		this.menu_list = UI.GC(this.body,'ul.left_menu');
		this.menu_title = [];
		this.menuupdate=function(){
			selfthis.main_menu = UI.GT(this.main,'li');
			selfthis.menu_list = UI.GC(this.body,'ul.left_menu');
			selfthis.menu_title = [];
			for (var i=0;i<selfthis.menu_list.length;i++) {
				menu_title_left = [];
				UI.each(UI.GC(selfthis.menu_list[i],'.second_menu'),function(on){
					menu_title_left.push(UI.GT(on,'ul'));
				});
				selfthis.menu_title.push(menu_title_left);
			}
		}
		this.menuupdate();
		this.cur_list = 0;
	}

	this._init();

	//Hide Bar
	this.bar = UI.GC(this.main,'.bar')[0];
	var _contant = this.contant,_body = this.body,_delay,_bar = UI.GC(this.main,'.bar')[0];
	this.bar.onclick = function() {
		if(!UI.hasClass(this,'on')){
			UI.removeClass(this,'hover');
			UI.removeClass(_body,'hide');
			UI.removeClass(_contant,'main_auto');
			//_contant.style.width = UI.windowWidth() - 190+'px';
			UI.addClass(this,'on');
		}
	    else {
			UI.addClass(this,'hover');
			UI.addClass(_body,'hide');
			UI.addClass(_contant,'main_auto');
			//_contant.style.width = UI.windowWidth()+'px';
			UI.removeClass(this,'on');
			}
	}
	this.bar.onmouseover = this.body.onmouseover = function() {
		clearTimeout(_delay);
		_delay = setTimeout(function() {
			if (UI.hasClass(_bar,'hover')) {
				UI.removeClass(_body,'hide');
		        UI.removeClass(_contant,'main_auto');
			}
		},250);
	}
	this.body.onmouseout = this.bar.onmouseout = function() {
		clearTimeout(_delay);
		_delay = setTimeout(function() {
			if (UI.hasClass(_bar,'hover')) {
				UI.addClass(_body,'hide');
		        UI.addClass(_contant,'main_auto');
			}
		},250);
	}
	//cookie设置菜单选中项
	this.reload_menu_statue = function(){
		//var c = getCookie("left_menustatue");
		var c = UI.cookie(this._cookie);
		if(c==null||c==''){this.show('0,0,0')}
		else{
			
				var S = c.split(',');
				if(S.length==3)
				{
				  if(this.data.length>parseInt(S[0]) && this.data[parseInt(S[0])].data.length>parseInt(S[1]) && this.data[parseInt(S[0])].data[parseInt(S[1])].data.length>=parseInt(S[2]))
				  {
				   	this.show(S[0]+','+S[1]+','+S[2]);	
				  }
				  else this.show('0,0,0');
				}
			
		}
	}
	
	this.reload_menu_statue();

	//List Auto Height
	document.documentElement.style.overflow = 'hidden';
	var _menu_height = UI.GC('.header')[0].scrollHeight + UI.GC('.top_menu_div')[0].scrollHeight;
	this.autoHeight = function() {
		this.body.style.height = UI.windowHeight() - _menu_height+'px';
	};
	this.autoHeight();
	(function(n){
		UI.EA(window,'resize',function(){
			window[n].autoHeight();
		});
	})(this.name);
	
};
//top-frame menu use
UI.Menu2 = function(o) {
	this.name = o.name;
	this.id = o.id;
	this.main = UI.G(o.id);
	this.target = o.target;
	this.data = o.data;
	this.topmenu_index=0;
	
	var imagePath=UI.GetRoot(['ui.min.js','ui.js']);
	if(imagePath){
		var path = imagePath.substr(0, imagePath.lastIndexOf("/"));
		imagePath=path.substr(0, path.lastIndexOf("/"))+"/stylesheet/images/";
	}
	
	this.menu_click = function(obj) {
		var path = [];
		if(UI.hasClass(obj,'h-menu-one-a')){
		    path[0] = obj.text?obj.text:obj.innerText;
			for(var i = 0;i < UI.GC('.h-menu-one-a').length;i++)
			{
				if(UI.GC('.h-menu-one-a')[i] == obj)
				{
					this.topmenu_index = i;
					break;
				}
			}
			if(UI.GC('.h-menu-two')[this.topmenu_index]){
			    path[1] = UI.children(UI.children(UI.GC('.h-menu-two')[this.topmenu_index])[0])[0].text?UI.children(UI.children(UI.GC('.h-menu-two')[this.topmenu_index])[0])[0].text:UI.children(UI.children(UI.GC('.h-menu-two')[this.topmenu_index])[0])[0].innerText;
				if(UI.next(UI.children(UI.children(UI.GC('.h-menu-two')[this.topmenu_index])[0])[0])){
					 path[2] = UI.children(UI.children(UI.next(UI.children(UI.children(UI.GC('.h-menu-two')[this.topmenu_index])[0])[0]))[0])[0].text?UI.children(UI.children(UI.next(UI.children(UI.children(UI.GC('.h-menu-two')[this.topmenu_index])[0])[0]))[0])[0].text:UI.children(UI.children(UI.next(UI.children(UI.children(UI.GC('.h-menu-two')[this.topmenu_index])[0])[0]))[0])[0].innerText;
				}
			}
		}
		if(UI.hasClass(obj,'h-menu-two-a')){
			path[0] = UI.GC('.h-menu-one-a')[this.topmenu_index].text?UI.GC('.h-menu-one-a')[this.topmenu_index].text:UI.GC('.h-menu-one-a')[this.topmenu_index].innerText;
			path[1] = obj.text?obj.text:obj.innerText;
			if(UI.next(obj)){
			    path[2] = UI.children(UI.children(UI.next(obj))[0])[0].text?UI.children(UI.children(UI.next(obj))[0])[0].text:UI.children(UI.children(UI.next(obj))[0])[0].innerText;
			}
		}
		if(UI.hasClass(obj,'h-menu-three-a')){
			path[0] = UI.GC('.h-menu-one-a')[this.topmenu_index].text?UI.GC('.h-menu-one-a')[this.topmenu_index].text:UI.GC('.h-menu-one-a')[this.topmenu_index].innerText;
			path[1] = UI.prev(UI.parent(UI.parent(obj))).text?UI.prev(UI.parent(UI.parent(obj))).text:UI.prev(UI.parent(UI.parent(obj))).innerText;
			path[2] = obj.text?obj.text:obj.innerText;
		}
		this.select_by_path(path);					
	}
	/**
	* 根据路径直接设置选中项
	* @param path 路径 例如['My Portal','报表','攻击事件报表']
	* @returns
	*/
	this.select_by_path = function(path)
	{
		var self = this;
		var one_index=0;
		var h_menu_one;
		var h_menu_two;
		var h_menu_three;
		var h_menu_a;
		var twos;
		
		//设置一级菜单的选中
		if(path[0]){
			var ones = UI.GC('.h-menu-one-a');
			UI.each(ones,function(on){//循环所有一级菜单
				one_index++;
				if(UI.trim(on.text?on.text:on.innerText) == UI.trim(path[0])){//找到目标菜单
					h_menu_one = on;
					self._remove_select('all');
					self._add_select(on);//设置选中
					//得到对应的二级菜单
					one_index = one_index > 0 ? one_index - 1:one_index;
					self.topmenu_index = one_index;
					twos = UI.GC('.h-menu-two')[one_index];
					UI.removeClass(UI.parent(twos),'hide');UI.addClass(UI.parent(twos),'show');
					if(on.href!="javascipt:void()"){h_menu_a = on.href;}

				}
			});
		}
		
		//设置二级菜单的选中
		if(path[1]){
			UI.each(UI.children(twos),function(on){//循环二级菜单
				if(UI.trim(UI.children(on)[0].text?UI.children(on)[0].text:UI.children(on)[0].innerText) == UI.trim(path[1])){//找到目标菜单
				    h_menu_two = UI.children(on)[0];
					self._add_select(h_menu_two);
					if(UI.children(on)[0].href!="javascipt:void()"){h_menu_a = UI.children(on)[0].href;}
				}
			});
		}
		//设置三级菜单的选中
		if(path[2]){
			var threes = UI.next(h_menu_two);
			UI.each(UI.children(threes),function(on){//循环二级菜单
				if(UI.trim(UI.children(on)[0].text?UI.children(on)[0].text:UI.children(on)[0].innerText) == UI.trim(path[2])){//找到目标菜单
					h_menu_three = UI.children(on)[0];
				    self._add_select(h_menu_three);
					if(UI.children(on)[0].href!="javascript:void(0)"){h_menu_a = UI.children(on)[0].href;}
				}
			})

		}
		if (h_menu_a){
			window[self.target].document.location.href = h_menu_a;
		}

	}
	/**
	* 内部调用方法
	* 移除菜单的选中样式
	* @param index 可以取 1 2 3 'all' 
	* 1代表移除一级菜单,以此类推
	* 'all'代表移除所有
	* @returns
	*/
	this._remove_select = function(index){
		  switch (index) {
			  case 1:
			      UI.each(UI.GC('.h-menu-one-a'),function(on){
				  UI.removeClass(on,'h-menu-one-a-selected');})
				  break;
			  case 2:
				  UI.each(UI.GC('.h-menu-two-a'),function(on){
				  UI.removeClass(on,'h-menu-two-selected');})
				  break;
			  case 3:
				  UI.each(UI.GC('.h-menu-three-a'),function(on){
				  UI.removeClass(on,'h-menu-three-a-selected');})
				  break;
			  case 4:
				  UI.each(UI.GC('.h-menu-two'),function(on){
				  UI.removeClass(UI.parent(on),'show');UI.addClass(UI.parent(on),'hide');})
				  UI.each(UI.GC('.h-menu-three'),function(on){
				  UI.C(on,'display','none');})
				  break;
			  case 'all':
				  UI.each(UI.GC('.h-menu-one-a'),function(on){
				  UI.removeClass(on,'h-menu-one-a-selected');})
				  UI.each(UI.GC('.h-menu-two-a'),function(on){
				  UI.removeClass(on,'h-menu-two-selected');})
				  UI.each(UI.GC('.h-menu-three-a'),function(on){
				  UI.removeClass(on,'h-menu-three-a-selected');})
				  UI.each(UI.GC('.h-menu-two'),function(on){
				  UI.removeClass(UI.parent(on),'show');UI.addClass(UI.parent(on),'hide');})
				  UI.each(UI.GC('.h-menu-three'),function(on){
				  UI.C(on,'display','none');})
				  break;
			  default:
				  break;
		  }
	  }
	/**
	 * 内部调用方法
	 * 为object对象添加 对应的选中样式
	 * @param obj
	 * @returns
	 */
	this._add_select = function(obj){
		if(UI.hasClass(obj,'h-menu-one-a')){
			UI.addClass(obj,'h-menu-one-a-selected');
			return;
		}
		if(UI.hasClass(obj,'h-menu-two-a')){
			UI.addClass(obj,'h-menu-two-selected');
			return;
		}
		if(UI.hasClass(obj,'h-menu-three-a')){
			UI.addClass(obj,'h-menu-three-a-selected');
			return;
		}
		if(UI.hasClass(obj,'h-menu-two')){
			UI.C(obj,'display','block');
			return;
		}
		
	}
	
	var html = [],
	html_main = [];
	var len = o.data.length;
	html_main.push('<div class="h-menu-one">');
	for (var i = 0; i < o.data.length; i++) {
		if (len < o.data[i].data.length) {
			len = o.data[i].data.length;
		}
		var firstlevel_url = "javascript:void(0)";
		if (o.data[i].url) {
			firstlevel_url = o.data[i].url;
		}
		html_main.push('<a id="' + o.data[i].id + '" href="'
		 + firstlevel_url
		 + '" target="'
		 + this.target
		 + '" class="'
		 + (i == 0 ? 'h-menu-one-a h-menu-one-a-selected': 'h-menu-one-a') + '" title="' + o.data[i].name
		 + '" onfocus="this.blur()" onclick="' + this.name
		 + '.menu_click(this);'
		 + (o.data[i].call ? o.data[i].call: '') 
		 + '"  title="' + o.data[i].name + '">' + o.data[i].name
		 + '</a>');
		html.push('<div class=" ' + (!i ? 'show': 'hide') + '">');
		html.push('<ul class="h-menu-two" >');
		for (var j = 0; j < o.data[i].data.length; j++) {
			var title_url = '#';
			title_url = (o.data[i].data[j].url && o.data[i].data[j].url != '')? o.data[i].data[j].url: o.data[i].data[j].data[0].url;
			var secondMenu = o.data[i].data[j];
			secondMenu.url = title_url;
			html.push('<li class="h-menu-two-li">');
			if (secondMenu.data.length == 0) {
				html.push('<a id="' + secondMenu.id + '" onclick="if('
				 + this.name
				 + ')' + this.name + '.menu_click(this); " href="'
				 + title_url
				 + '" class="h-menu-two-a" target="'
				 + this.target
				 + '" onfocus="this.blur()" >'
				 + secondMenu.name + '</a>');
			} else {
				html.push('<a id="' + secondMenu.id + '" onclick="'
				 + this.name
				 + '.menu_click(this); " href="'
				 + title_url
				 + '" class=" '
				 + (!j ? 'h-menu-two-a h-menu-two-selected': 'h-menu-two-a')
				 + '" target="'
				 + this.target
				 + '" onfocus="this.blur()" >'
				 + secondMenu.name
				 + '<img class="h-menu-two-v2" src="stylesheet/images/top/icon/V2.png"/></a>');
				html.push('<ul class="h-menu-three" style="display:none">');
				for (var m = 0; m < secondMenu.data.length; m++) {
					html.push('<li class="h-menu-three-li">');
					html.push('<a id="' + secondMenu.data[m].id + '" class="h-menu-three-a" onclick="'
				     + this.name
				     + '.menu_click(this); " target="'
					 + this.target + '" href="' + secondMenu.data[m].url
					 + '" >');
					html.push('<img src="stylesheet/images/top/icon/V3.png"/>');
					html.push(secondMenu.data[m].name);
					html.push('</a>');
					html.push('</li>');
				}
				html.push('</ul>');
			}
			html.push('</li>');
		}
		html.push('</ul> ');
		html.push('</div>')
	}
	html_main.push('</div>');
	var menu_content = html_main.join('') + html.join('');
	this.main.innerHTML = menu_content;
	this.main.style.width = UI.pageWidth();
	if (o.data.length == 1) {
		UI.addClass(this.main, 'hide');
	} else
	 UI.addClass(document.body, 'HasMainMenu');
	document.documentElement.style.overflow = 'hidden';
	var _menu_height = UI.GC('div.header')[0].scrollHeight;
	var _footer = UI.GC('div.footer');
	if (_footer)
	 _menu_height += _footer[0].scrollHeight;
	this.autoHeight = function() {
		UI.GC('div.main')[0].style.height = (UI.Browser.ie ? document.documentElement.scrollHeight
		 - _menu_height - 4: window.innerHeight - _menu_height)
		 + 'px';
	};
	this.autoHeight(); (function(n) {
		UI.EA(window, 'resize',
		function() {
			window[n].autoHeight();
		});
	})(this.name);
	if (o.data.length > 0) {
		this.menu_click(UI.GC('.h-menu-two-a')[0]);
	}
};

//输入框交互事件
UI.input_hover = function()
{
	var inputs = UI.GC('.text');
	UI.each(inputs,function(on){
		if(UI.parent(on).className!='selectMulti')
		{
			UI.EA(on,'mouseover',function(){UI.addClass(on,'input_hover');})
			UI.EA(on,'mouseout',function(){UI.removeClass(on,'input_hover');})
			UI.EA(on,'focus',function(){UI.addClass(on,'input_click_down');})
			UI.EA(on,'blur',function(){UI.removeClass(on,'input_click_down');})
		}
		})
};
//按钮交互事件
UI.button_hover = function()
{
	var buttons = UI.GC('.cmn_btn');
	UI.each(buttons,function(on){
		UI.EA(on,'mouseover',function(){UI.addClass(on,'hover');})
		UI.EA(on,'mouseout',function(){UI.removeClass(on,'hover');})
		UI.EA(on,'mousedown',function(){UI.addClass(on,'click_down');})
		UI.EA(on,'mouseup',function(){UI.removeClass(on,'click_down');})
		})
	var buttons1 = UI.GC('.cmn_btn_focus');
	UI.each(buttons1,function(on){
		UI.EA(on,'mouseover',function(){UI.addClass(on,'focus_hover');})
		UI.EA(on,'mouseout',function(){UI.removeClass(on,'focus_hover');})
		UI.EA(on,'mousedown',function(){UI.addClass(on,'focus_click_down');})
		UI.EA(on,'mouseup',function(){UI.removeClass(on,'focus_click_down');})
		})	
};

//表格添加滑过事件
(function($){
	UI.table_hover = function() {
		if(typeof $ =='undefined'){
			UI.each(UI.GC('table.cmn_table'),function(on){
				UI.each(UI.GT(on,'tr'),function(e){
					if(UI.isElement(e) && 
						!UI.hasClass(e,'first_title')&&
						!UI.hasClass(e,'second_title')&&
						!UI.hasClass(e,'more'))	{
						e.onmouseover = function() {
							UI.addClass(this,'hover');
						}
						e.onmouseout = function() {
							if(UI.hasClass(this,'hover')){
							  UI.removeClass(this,'hover');
							}
						}	
					}
				});

			});

			return;
		}
		$(document).delegate('table.cmn_table tr','mouseover',function(){

			if(!$(this).parents('table').hasClass('noborder')&&!$(this).parents('table').hasClass('noborder_table')){
				if(!$(this).hasClass('first_title')&&!$(this).hasClass('second_title')&&!$(this).hasClass('more')){
					$(this).addClass('hover');
				}
			}		
		});
		$(document).delegate('table.cmn_table tr','mouseout',function(){
				if(!$(this).hasClass('first_title')&&!$(this).hasClass('second_title')&&!$(this).hasClass('second_title')){
					$(this).removeClass('hover');
				}
			
		});
		
	};
})(window.jQuery);
//ie6图片透明，只支持img标签，不支持背景图
UI.correctPNG = function(selector) // correctly handle PNG transparency in Win IE 5.5 or higher.
{
	if(!UI.Browser.ie6){ return;}
	
	selector = UI.isArray(selector)?selector:[selector];
	
	UI.each(selector,function(item){
		var imgs = UI.GC('img'+item);
		UI.each(imgs,function(img){
			var imgName = img.src.toUpperCase();
			if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
			{
				var imgID = (img.id) ? "id='" + img.id + "' " : "";
				var imgClass = (img.className) ? "class='" + img.className + "' " : "";
				var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
				var imgStyle = "display:inline-block;" + img.style.cssText;
				var imgWidth = img.width || UI.getStyle(img,'width');
				var imgHeight = img.height || UI.getStyle(img,'height');
				if (img.align == "left") imgStyle = "float:left;" + imgStyle;
				if (img.align == "right") imgStyle = "float:right;" + imgStyle;
				if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle;
				var strNewHTML = "<span " + imgID + imgClass + imgTitle
				+ " style=\"" + "width:" + imgWidth + "px; height:" + imgHeight + "px;" + imgStyle + ";"
				+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
				+ "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>";
				img.outerHTML = strNewHTML;
			}
		})
	});
};

;(function(UI){
	if(typeof UI == 'undefined') return;
	UI.EA(window,'load',function(){
		UI.table_hover();
		UI.input_hover();
		UI.button_hover();
		UI.correctPNG(['.nsfocus','.logo']);
	})
}(window.UI));
/*表格分行显示
* author:chenping@intra.nsfocus.com
* date: 20130530
*/

UI.pageTable=function(count,table){
	this.options={
		count:10,
		table:null,
		select:[]
	};
		
	if(typeof count == 'object'){
		this.options = jQuery.extend(this.options,count);
	}
	else{
		this.options.count=count;
		this.options.table=jQuery("#"+table);
		if(arguments[2]){
			this.options.select.push("#"+arguments[2]);
		}
		if(arguments[3]){
			this.options.select.push("#"+arguments[3]);
		}
	}
	
	this.rowCount = 0;
	this.newCount = this.options.count;
	this.table=this.options.table;

	this.__init__(); 
};

UI.pageTable.prototype.__init__=function(){
	var _self=this;

	jQuery.each(this.options.select,function(i,item){
		jQuery(item).change(function(){
			var newCount=_self.newCount=jQuery(this).find('option:selected').text();

			jQuery.each(_self.options.select,function(i,t){
				if(item!=t){
					_self.__setSelectedText__(t,newCount);
				}
			});
			
			_self.__updateTableRows__();
			
		});
		
		_self.__setSelectedText__(item,_self.newCount);
	})
	
	_self.__updateTableRows__();
};

UI.pageTable.prototype.__updateTableRows__=function(){
	if(this.newCount=="全部"){
		jQuery(this.table).find('tr').show();
	}
	else{
		jQuery(this.table).find('tr:gt('+this.newCount+')').hide();
		jQuery(this.table).find('tr:lt('+this.newCount+')').show();
	}

};

UI.pageTable.prototype.__setSelectedText__=function(item,text){
	var slt = jQuery(item).get(0);
	var count = slt.options.length;
	for(var i=0;i<count;i++){
		if(slt.options[i].text == text){
			slt.options[i].setAttribute("selected","true");
			break;
		}
	} 
};


/*
 * 
 * Usage: $.Placeholder.init({ color : "rgb(0,0,0)" });
 */
;(function($,win){
	if(typeof $ =='undefined'){
		return;
	}

	$.fn.placeholder = function(options){
		options = options || {};
		var defaults = {
			color:"#c8c8c8",
			killdefault:false,//去除默认placeholder
			className:"placeholder",
			text:''
		};

		var settings = $.extend(defaults,options);
		$(this).each(function(){
			var _obj=this;
			var isPlaceholder = function isPlaceholder(){ 
									var input = document.createElement('input');  
									return 'placeholder' in input;  
								}();
			if(isPlaceholder&&settings.killdefault){
				createPlaceholder(_obj,settings);
				$(_obj).attr('placeholder','');
			} 
			else if(!isPlaceholder){
				createPlaceholder(_obj,settings);
			}
		});
	};
	//构造placeholder
	function createPlaceholder(obj,settings){
		var $obj = $(obj);
		var plchdtxt = $.trim($obj.attr('placeholder')||$obj.attr('water') || settings.text);
		
		var placeholder = $("<label>" + plchdtxt + "</label>");
		if($obj.parent() && $obj.parent().hasClass('j-placeholder-wrap')){
			placeholder = $obj.parent().find('label');
		}
		else{
			var  wrapWidth = $obj.css('width');
			var wrapHeight = $obj.innerHeight();
			var wrapCss = {'position':'relative'};
			if(($obj.css('display')&&$obj.css('display')=='block')||($obj.css('position')=='absolute')) {
				$obj.wrap($('<div style="" class="j-placeholder-wrap">'))
			}
			else{
				$obj.wrap($('<div style="display:inline" class="j-placeholder-wrap">'))
			}
			$obj.parent().css(wrapCss)
			
			var leftcss = parseInt($obj.css('paddingLeft'))+3;
			leftcss = leftcss||'3px';
			
			var fontObj = $obj.css('fontSize')||'12px';
			var topcss =(parseInt(wrapHeight)/2-parseInt(fontObj)/2 -1 )||'2px';
			var fontcss = fontObj;
			placeholder.css({'position':'absolute','left':leftcss,'top':topcss,'cursor':'text','overflow':'hidden','font-size':fontcss,'display':'block'});
		
			placeholder.css('max-height',$obj.height());

		    placeholder.css('color',settings.color);
			placeholder.addClass(settings.className);
			$obj.after(placeholder);
			if(settings.style){
				placeholder.attr('style',placeholder.attr('style')+';'+settings.style);
			}
			placeholder.attr('style',placeholder.attr('style')+';'+'_margin-top:3px;');
		}
		
		setTimeout(function(){
			if($obj.val()){
				placeholder.hide();	
			}
		},10);

		placeholder[0].onclick= function(){
			$obj.trigger('focus')
		};
	
		$obj[0].onfocus = function(){
			placeholder.hide();	
		};

		$obj[0].onblur = function(){
			if(!$obj.val()){
				placeholder.show();
			}
		};
	}
	
	/*$(function(){
		$('[placeholder]').placeholder(); 
	})	*/

	//UI.passwordAuto("text框","password框")
	UI.gangedPlaceholder = function(obj1,obj2){
		if(typeof obj1 == "string"){
			obj1 = $("#"+obj1)	
		}
		if(typeof obj2 == "string"){
			obj2 = $("#"+obj2)	
		}
		function changeValue(){
			window.setTimeout(function(){
				var nowValue = jQuery(obj2).val();
				if(nowValue){
					jQuery(obj2).next("label").hide();		
				}else if(!jQuery(obj2).is(":focus")){
					jQuery(obj2).next("label").show();	
				}	
			},10);
		}
		//按下回车键
		$(obj1).keyup(function(e){
			var keynum;
			var e = e || window.event;
			if(e.keyCode) { //IE
				keynum = e.keyCode
			} else if(e.which) {// Netscape/Firefox/Opera 
				keynum = e.which
			}
			if (keynum == 13) {
				changeValue();
				if (e.stopPropagation){
					e.stopPropagation();
				}else{
					e.cancelBubble = true;
				}
			}	
		});
		//失去焦点
		$(obj1).blur(changeValue);
		//鼠标点选
		if(typeof $(obj1)[0].oninput !="undefined"){
			$(obj1)[0].oninput = function(){
				changeValue();	
			}	
		}else if(typeof $(obj1)[0].onpropertychange !="undefined"){
			$(obj1)[0].onpropertychange = function(){
				changeValue();	
			}		
		}	
	}


}(window.jQuery,window));
	
		

UI.PopupMenu=function(el,leftClick){
	this.initialize(el,leftClick);
};
UI.PopupMenu.wrap=null;
UI.PopupMenu.prototype ={
	body : null,
	target : null,
	actions : null,
	actionsCache : null,
	actionsSet : null,
	popupTmp : false,
	leftClick : false,
	display : false,
	initialize : function(el,leftClick) {
		var Self = this,wrap=UI.PopupMenu.wrap;
		if (!wrap) {
			wrap = document.createElement('div');
			wrap.style.display = 'none';
			wrap.className = 'popup_menu';
			UI.PopupMenu.wrap = wrap;
			
			document.body.appendChild(wrap);
			if(UI.Browser.ie)
			UI.EA(window,"load",function(){
				if(!UI.GC(document,'.popup_menu')){
					document.body.appendChild(wrap);
				}
			})
		
		}
		this.body = document.createElement('div');
		UI.EA(this.body,'click',function(e) {
			UI.E(e).stop();
		});
		this.body.oncontextmenu = function(e){
			UI.E(e).stop();
		}
		this.target = el;
		if (!leftClick) {
			this.target.oncontextmenu = clickEvent;
			document.documentElement.oncontextmenu = closeEvent;
		}
		else {
			this.leftClick = true;
			UI.EA(this.target,'click',clickEvent);
		}
		UI.EA(document.documentElement,'click',closeEvent);

		//Function
		function closeEvent(e) {
			Self.hide();
		}
		function clickEvent(e) {
			Self.show(e);
			return false;
		}
	},
	setActions : function(actions){
		this.actionsSet = actions;
	},
	popup : function(actions){
		var delay = arguments.length > 1;
		if (delay) { //Show Menu After Event Stop
			var event = arguments[0];
			var actions = arguments[1];
		}
		if (!actions) actions = this.actionsSet; //For No Arguments
		if (!actions || !actions.length) return false;
		if (!this.actionsCache) this.actionsCache = this.actions;
		this.actions = actions;
		this.build();
		this.popupTmp = true;
		if (delay) this.show(event);
	},
	build : function(actions) {
		var Self = this;
		var html = [],call = [],iframe = [],iframeStr = '<iframe class="cover" src="javascript:false;"></iframe>';
		var args=[];
		args.push(Self.target);
		for(var i=1;i<arguments.length;i++){
			args.push(arguments[i]);
		}
		
		if (actions) this.actions = actions;
		parseMenu(this.actions);
		this.body.innerHTML = html.join('');
		if (UI.Browser.ie6) {
			UI.each(iframe,function(e){
				Self.body.appendChild(e);
			});
		}
		this.cover = iframe[0];
		//Menu List
		var list = this.body.getElementsByTagName('li'),w_son;
		UI.each(list,function(e){
			if (e.getAttribute('rel') == 'son') {
				e.son = true;
				UI.addClass(e,"son");
			}
			if (UI.hasClass(e,'Input')) e.input = true;
			UI.EA(e,'click',function(even) { //Menu Click
				if (e.getAttribute('call') != null) { //Call Function
					call[e.getAttribute('call')].apply(e,args);
					//Firefox has "this" bug
				}
				if (e.input) {
					UI.E(even).stop();
					var input = e.lastChild.previousSibling;
					var son = e.firstChild;
					input.setAttribute('rel',input.getAttribute('rel') ? '' : 'checked');
					var rel = input.getAttribute('rel');
					if (input.className.match(/radio/g)) { //Radio
						UI.each(e.parentNode.getElementsByTagName('b'),function(e){
							if (e.getAttribute('name') == input.getAttribute('name')) {
								e.className = 'radio';
								e.setAttribute('rel','');
							}
						});
						input.setAttribute('rel','checked');
						input.className = input.className.split('_')[0] + '_checked';
						if (e.son) {
							son.style.display = 'block'; //Show Son Menu
							if (UI.Browser.ie6) iframe[son.getAttribute('iframe')].style.display = 'block';
						}
					}
					else { //Checkbox
						input.className = input.className.split('_')[0] + (rel ? '_' + rel : '');
						son.style.display = e.son && (rel == 'checked') ? 'block' : 'none'; //Check Son Menu Display
						if (UI.Browser.ie6) iframe[son.getAttribute('iframe')].style.display = son.style.display;
					}
					return false;
				}
				if (!e.son) Self.hide();
				else UI.E(even).stop();
			});
			e.onmouseover = function(e){ //Menu Hover
				UI.addClass(this,"hover");
				if (this.son) {
					if (this.input && this.lastChild.previousSibling.getAttribute('rel') != 'checked') return false;
					var son = this.firstChild;
					son.style.display = 'block';
					if (UI.E(e).target == this) {
						if (!w_son) w_son = son.offsetWidth;
						son.style.left = '';
						if (w_son + UI.getX(son) > UI.windowWidth() || son.parentNode.parentNode.style.left != '') {
							son.style.left = - w_son + 'px';
						}
					}
					if (UI.Browser.ie6) {
						iframe[son.getAttribute('iframe')].style.cssText = 'display:block;top:' + (UI.getY(son) - UI.getY(Self.body)) + 'px;left:' + (UI.getX(son) - UI.getX(Self.body)) + 'px;width:' + son.offsetWidth + 'px;height:' + son.offsetHeight + 'px;';
					}
				}
			}
			e.onmouseout = function(e){ //Menu Out
				UI.removeClass(this,"hover");
				if (this.son) {
					var son = this.firstChild;
					son.style.display = '';
					if (UI.Browser.ie6) iframe[son.getAttribute('iframe')].style.cssText = '';
				}
			}
		})

		//Function
		function parseMenu(actions) {
			var input,checked,name;
			html.push('<ul iframe="' + iframe.length + '">');
			if (UI.Browser.ie6) iframe.push(UI.html(iframeStr)[0]);
			for (var i = 0,num = actions.length;i < num;i++) {
				var dataCache=actions[i]['dataCache'];
				if(dataCache){
					dataCache=' data-cache="'+dataCache+'"';
				}
				else{
					dataCache='';
				}
				
				input = actions[i].extend && actions[i].extend.match(/\binput\b/gi) ? 'Input' : '';
				if (actions[i].name) {
					html.push('<li' + (actions[i].data ? ' rel="son"' : '') + (actions[i].call ? ' call="' + call.length + '"' : '') + ' class="' + input + '" '+dataCache+'>');
					if (actions[i].call) {
						call.push(actions[i].call);
					}
					if (actions[i].data) {
						var tmp = actions[i].data;
						parseMenu(tmp);
					}
					if (actions[i].extend) {
						html.push(actions[i].extend);
						checked = actions[i].extend.match(/\bchecked\b/gi) ? 'checked' : '';
						name = actions[i].extend.replace(/'/g,'"').match(/\bname="(\w*)"/gi);
						if (input) html.push('<b ' + (name ? name : '') + ' rel="' + checked + '" class="' + (actions[i].extend.match(/\bradio\b/gi) ? 'radio' : 'checkbox') + (checked ? '_' + checked : '') + '"></b>');
					}
					html.push('<span>' + actions[i].name + '</span></li>');
				}
				else html.push('<li class="line" '+dataCache+'></li>');
			}
			html.push('</ul>');
		}
	},
	show : function(e) {
		var wrap=UI.PopupMenu.wrap;
		var E = UI.E(e);
		try {
			E.stop();
		}catch(e){};
		if (!this.popupTmp && (!this.actions || !this.actions.length)) {
			this.hide();
			return false;
		}
		if ((!this.popupTmp || this.body.innerHTML == '') && this.actionsCache) {
			this.build();
		}

		if (wrap.innerHTML != '') wrap.removeChild(wrap.firstChild);
		wrap.appendChild(this.body);
		wrap.style.display = 'block';
		this.display = true;
		var w_window = UI.windowWidth(),w_self = this.body.offsetWidth,h_window = UI.windowHeight(),h_self = this.body.offsetHeight,x_scroll = UI.scrollX(),y_scroll = UI.scrollY(),x = E.x,y = E.y;
		wrap.style.top = (y + y_scroll) + 'px';
		wrap.style.left = (x + x_scroll) + 'px';
		wrap.style.margin = '';
		if (w_self + E.x > w_window) wrap.style.marginLeft = - w_self + 'px';
		if (h_self + E.y > h_window) wrap.style.marginTop = - h_self + 'px';
		var x_wrap = UI.getX(wrap),y_wrap = UI.getY(wrap);
		if (x_wrap < 0) wrap.style.marginLeft = parseInt(wrap.style.marginLeft) - x_wrap + 'px';
		if (y_wrap < 0) wrap.style.marginTop = parseInt(wrap.style.marginTop) - y_wrap + 'px';
		if (this.cover) this.cover.style.cssText = 'display:block;width:' + (w_self + 2) + 'px;height:' + (h_self + 2) + 'px;';
		
		if (this.popupTmp) {
			this.actions = this.actionsCache;
			this.actionsCache = null;
		}
		this.popupTmp = false;
		return false;
	},
	hide : function() {
		var wrap=UI.PopupMenu.wrap;
		wrap.style.display = 'none';
		if (this.cover) this.cover.style.cssText = '';
		this.display = false;
	}
};
//在页面右侧显示的快捷菜单
UI.QuickMenu=function(){
	var _quick = UI.GC('.quick')[0];
	var _quickMenu = UI.GC('.quickMenu')[0];
	if(_quick&&_quickMenu)
	{
		var isOpen=false;
		_quick.onclick=function(){
		if(!isOpen){
			_quickMenu.style.display='block';
			this.style.right=parseInt(_quickMenu.offsetWidth)+'px';
			UI.addClass(this,'on');
			isOpen=true;
		}
		else{
			_quickMenu.style.display='none';
			this.style.right='0';
			UI.removeClass(this,'on');
			isOpen=false;
			}
		}
	}
	else
	{
		return false;
	}
};

UI.Resize = function(o,option) {
	var P = o.parentNode.parentNode;
	var ico = UI.GC(P,'.ico')[0];
	var w,h,x,y,action,padding_y = 0,padding_x = 0;

	if (!option) option = {
		min : {
			x : 20,
			y : 15
		},
		max : {
			x : Infinity,
			y : Infinity
		}
	}
	else {
		if (!option.min) option.min = {
			x : 20,
			y : 15
		}
		if (!option.max) option.max = {
			x : Infinity,
			y : Infinity
		}
	}

	UI.drag(ico,{
		start : function(e) {
			var E = UI.E(e);
			x = E.x;
			y = E.y;
			w = UI.width(o);
			h = UI.height(o);
			if(jQuery){
				h=jQuery(o).height();
			}
			
			action = UI.C(ico,'cursor');
			if (!UI.Browser.ie && document.compatMode == 'BackCompat') {
				var Self = ico.parentNode;
				padding_x = parseInt(UI.C(Self,'paddingLeft')) + parseInt(UI.C(Self,'paddingRight'));
				padding_y = parseInt(UI.C(Self,'paddingBottom')) + parseInt(UI.C(Self,'paddingTop'));
			}
		},
		drag : function(e) {
			var E = UI.E(e),W,H;
			switch (action) {
				case 'ne-resize':
					W = w + E.x - x - padding_x;
					H = h - E.y + y - padding_y;
					break;
				case 'se-resize':
					W = w + E.x - x - padding_x;
					H = h + E.y - y - padding_y;
					break;
				case 'nw-resize':
					W = w - E.x + x - padding_x;
					H = h - E.y + y - padding_y;
					break;
				case 'sw-resize':
					W = w - E.x + x - padding_x;
					H = h + E.y - y - padding_y;
					break;
				case 'e-resize':
					W = w - E.x + x - padding_x;
					break;
				case 's-resize':
					H = h + E.y - y - padding_y;
					break;
			}
			if (W < option.min.x) W = option.min.x;
			if (W > option.max.x) W = option.max.x;
			if (H < option.min.y) H = option.min.y;
			if (H > option.max.y) H = option.max.y;
			try{
				if (UI.hasClass(o,'adaptive'))
				{
					UI.C(o,'height',H + 'px');
				}
				else
				{
				    UI.C(o,'width',W + 'px');
				    UI.C(o,'height',H + 'px');
				}
			}catch(e){};
		}
	},UI.isUndefined(option.capture) ? true : option.capture);
}
UI.Select = function(o) {
	this._body = UI.G(o.id);
	this._input = UI.GT(this._body,'input')[0];
	this._select = UI.GT(this._body,'select')[0];
	this._ul = UI.DC('ul');
	
	this._input.value = this._select.options[this._select.selectedIndex].innerHTML;
	this.cur = this._select.selectedIndex;

	var li = [];
	for (var i=0;i<this._select.options.length;i++) {
		var text = this._select.options[i].innerHTML;
		li[i] = '<li' + ( text==this._input.value ? ' class="on"' : '' ) + ' onmouseover="UI.addClass(this,\'hover\')" onmouseout="UI.removeClass(this,\'hover\')" onclick="' + o.name + '.select(this.innerHTML,' + i + ');UI.addClass(this,\'on\')" title="' + text + '">' + text + '</li>'
	}
	this._ul.innerHTML = li.join('');
	this._body.appendChild(this._ul);
	this._li = UI.GT(this._ul,'li');

	if (UI.Browser.ie6) {
		this._cover = UI.DC('div');
		this._cover.style.cssText = 'position:absolute;';
		this._cover.innerHTML = '<iframe src="javascript:false;" style="position:absolute;z-index:-1;"></iframe>';
		this._body.appendChild(this._cover);
		var iframe = UI.GT(this._cover,'iframe')[0];
	}

	UI.EA(this._body,'click','UI.toggleClass(' + o.name + '._body,"on");' + o.name + '.iframe();');
	UI.EA(document,'click','e=window.event||e;if(UI.ET(e)!=' + o.name + '._input) UI.removeClass(' + o.name + '._body,"on");');

	this.select = function(n,i) {
		this._input.value = n;
		UI.A(this._select.options[this.cur],'selected','');
		UI.removeClass(this._li[this.cur],'on');
		UI.A(this._select.options[i],'selected','selected');
		this._select.value = this._select.options[i].value;
		this.cur = i;
	}
	this.iframe = function() {
		if (UI.Browser.ie6) {
			iframe.style.width = this._ul.offsetWidth+'px';
			iframe.style.height = this._ul.offsetHeight+'px';
			this._cover.style.top = UI.C(this._ul,'top');
			this._cover.style.left = UI.C(this._ul,'left');
		}
	}
}
UI.SelectMulti = function(o,option) {
	if(!o) return;
	//console.log(o);
	//console.log(option);
	option = option || {};
	
	if(!o.name || o.name.indexOf('selectMulti_')<0){
		o.name = 'selectMulti_' + new Date().getTime() + '_' + parseInt(Math.random()*100+1);
		window[o.name] = this;
	}
	var _self = this;
	this.name = o.name;
	this.body = o;
	this.cont = UI.GC(o,'.cont')[0];
	this.input=o.firstChild;
	//消除文本节点的Firefox bug
	while(this.input.nodeType==3){
		this.input = this.input.nextSibling
	}
	this.list=UI.GT(this.cont,'ul')[0];
	var dataHtml = "";
	
	if(option.url){
		//通过url构造
		if(typeof jQuery =='undefined') return;
		(function($){
			$.ajax({
				url: option.url,
				dataType: "json",
				async:false,
				success: function(data) {
					if(!$.isArray(option.data)){
						option.data =[];
					}
					 $.merge(option.data,data);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown)
				}
			});
		})(jQuery);
	}
	if(option.data&&option.data.length>0){
		if(typeof jQuery =='undefined') return;
		var $ =jQuery;
		var dataLen = option.data.length;
		for(var i = 0; i < dataLen;i++){
			var text  = option.data[i].text||"null";
			var checked ="";
			if(option.data[i].checked)
				checked="checked=true";
			var li = document.createElement("li");
			li.innerHTML = '<input type="checkbox"' + checked +'"/><label>' + text +'</label>';
			if(option.tag){
				$(li).addClass(option.tag.tagid);
				if(!option.tag.hasshow) $(li).hide();
			}
			this.list.appendChild(li);
			//判断该条数据有没有子集
			if(option.data[i].sub){
				var selector = $(option.data[i].sub.subSelector)[0];
				var subData = option.data[i].sub.data;
				//为子元素做上父级元素的tag
				var tagid = i+'sub_' + new Date().getTime() + i + '_' + parseInt(Math.random()*100+1);
				$(li).attr("control",tagid);
				var next = new UI.SelectMulti(selector,{data:subData,tag:{tagid:tagid,hasshow:!!checked}});
				next.display = true;
			}	
			
		}
		
	}
	this.allItems=UI.GT(this.cont,'li');
	this.items = function(){
		if(this.allItems.length<=0){
			return [];	
		}
		return UI.grep(this.allItems,function(o,i){
			if(o.style.display != "none"){
				return true;	
			}else{
				return false;	
			}
		});		
	}
	this.allCheckbox = function(){
		var temp = [];
		var _items = this.items();
		for(var x=0,y=_items.length;x<y;x++){
			temp.push(UI.GT(_items[x],"input")[0]);	
		}
		return temp;	
	};
	this.checkbox = function(){
		var _allCheckbox = this.allCheckbox();
		return UI.grep(_allCheckbox,function(o,i){
			if(!o["disabled"] || UI.hasClass(o,"js-set-disabled")){
				return true;
			}else{
				return false;	
			}
		});
	}
	this.tools = UI.GC(o,'div.tools')[0];
	this.value = this.input.value;
	this.readyonly = this.input["readOnly"];;
	this.autochecked = UI.A(this.input,'autochecked');
	this.fillin= UI.A(this.input,'fillin');
	this.maxselect= UI.A(this.input,'maxselect');
	this.display = false;
	this.click = false; //If Click The Menu
	this.key_up = false;
	
	this.options = option;
	//自动选择功能
	var _items = this.items();
	for(var i = 0;i<_items.length;i++){
		_items[i].onclick = function(event){
			var event = event||window.event;
			var target = event.target||event.srcElement;
			if(target.tagName!='INPUT'){
				if(!this.childNodes[0]["disabled"]){
					if(this.childNodes[0].checked)
						this.childNodes[0].checked =false;
					else 
						this.childNodes[0].checked =true;
					//checkbox clicked handler
					checkedHandler(this);
				}
			}
			_self.controlNext(this,this.childNodes[0].checked);
		}
	}
	var keyuped = this.key_up;
	var cont = this.cont,input = this.input,name = this.name,/*allCheckbox = this.allCheckbox,checkbox = this.checkbox,*/list=this.list;
	//var s_li = UI.GT(cont, 'li');
	var all_select, s_text, s_but;
	if(!this.tools){
		var toolDiv = UI.html('<div class="tools"></div>')[0];
		this.cont.appendChild(toolDiv);
		this.tools = UI.GC(o,'div.tools')[0];
	}
	all_select = UI.GC(this.tools, 'input.all_select')[0];
	s_text = UI.GC(this.tools, 'input.s_text')[0];
	s_but = UI.GC(this.tools, 'input.s_but')[0];
		
	new UI.resize(this.cont,{min:{x:100,y:30}});
	
	UI.EA(UI.GC(this.body,'b.ico')[0],'click',function(e){
		UI.E(e).stop();
	});
	jQuery&&jQuery(this.body).find("b.ico").mousedown(function(event){
		var event = event||window.event;
		event&&event.preventDefault();
		event.stopPropagation();
		return false;
	});//edit by gq:禁用选中
	if (UI.Browser.ie6) {
		var iframe = UI.html('<iframe src="javascript:false;" style="display:none;"></iframe>')[0];
		UI.before(iframe,this.cont);
		setInterval(function(){
			iframe.style.cssText = 'position:absolute;filter:alpha(opacity=0);z-index:-1;top:' + cont.offsetTop + 'px;left:' + cont.offsetLeft + 'px;width:' + cont.offsetWidth + 'px;height:' + cont.offsetHeight + 'px;';
		},200);
	}

	if (_self.items().length > 7) {
		this.cont.style.height = '161px';
	}
	else{
		s_text && s_but && (s_text.style.display=s_but.style.display="none");
	}
	if (!this.tools) {
		this.cont.style.padding = '0';
	}
	else {
		var button=UI.GT(this.tools,'input');
		UI.each(button,function(o){
			o.onclick = function(e){
				var T = UI.E(e).target;
				var _checkbox = _self.checkbox();
				if (UI.hasClass(T,'SelectAll')) {
					if(window[name].readyonly){
						UI.each(_checkbox,function(o){
							o.checked = true;
						});	
					}
					else{
						var _items = _self.items();
						UI.each(_items,function(o){
							if (o.style.display!="none" && o.style.visibility !="hidden"){
								if(!UI.GT(o,'input')[0]["disabled"]){
									UI.GT(o,'input')[0].checked = true;
									
										_self.controlNext(o,UI.GT(o,'input')[0].checked);
										
								}
							}
						});
					}
				}
				if (UI.hasClass(T,'SelectReverse')) {
					if(window[name].readyonly){
						UI.each(_checkbox,function(o){
							o.checked = o.checked ? false : true;	
						});
					}
					else{
						var _items = _self.items();
						UI.each(_items,function(o){
							if (o.style.display!="none" && o.style.visibility !="hidden"){
								if(!UI.GT(o,'input')[0]["disabled"]){
									UI.GT(o,'input')[0].checked = UI.GT(o,'input')[0].checked? false:true;
										_self.controlNext(o,UI.GT(o,'input')[0].checked);
								}
							}
						});
					}
				}

				//callback handler
				var callback = UI.A(T,'callback');
				if(typeof window[callback] == 'function'){
					window[callback].call(T,_checkbox);
				}
			};
		});
		all_select && UI.EA(all_select,'click',function(e){
			var T = UI.E(e).target;
			var _checkbox = _self.checkbox(); 
			if(!jQuery){
				if(T.checked){
					UI.each(_checkbox,function(o){
						o.checked = true;	
					});
				}
				else{
					UI.each(_checkbox,function(o){
						o.checked = false;
					});
				}
			}
			else{
				jQuery(_self.items()).trigger("click")
			}
			
			
		});
		s_but && UI.EA(s_but,'click',function(e){
			var str=UI.trim(s_text.value);
			if(!str) return;
			var re=new RegExp('('+str+')', 'gi'),r_text='<span style="color:#FF6600">'+str+'</span>';
			var _items = _self.items();
			UI.each(_items,function(o){
				var lab=UI.GT(o,"label")[0];
				if(lab.title){lab.innerHTML=lab.title}
				else{lab.title=lab.innerHTML};
				lab.innerHTML=lab.innerHTML.replace(re,r_text);
			});
			return false;
		});
	}
	if(_self.items().length==0 && !this.fillin){
		input.disabled="disabled"; 
		UI.addClass(input,"disabled");
	}
	if(option.alwaysShow){
		input.removeAttribute('disabled');
	}
	UI.EA(document,'click',function(e){
		var E = UI.E(e);
		if (E.target != input && window[name]) {
			window[name].hide();
		}
	});
	UI.EA(this.input,'click',function(){
		if (window[name].display) {
			window[name].hide();
		}
		else {
			window[name].show();
		}
	});
	UI.EA(this.input,'keyup',function(e){
		if (!window[name].readyonly&&!window[name].fillin){
			if (window[name].display) 
				window[name].hide_x();

			else window[name].show();
			
			var inValue = UI.trim(input.value);
			var reg = new RegExp(inValue,"i");  //拼音正则表达式
			var cbox_length = _self.checkbox().length;
			var _items = _self.items();
			if(input){
				for(var i=0;i<cbox_length;i++){
					var li_value = _items[i].innerText || _items[i].textContent;
					if (!reg.test(li_value)){
						//_items[i].style.display = "none";
						UI.addClass(_items[i],'hide');
					}else{
						//_items[i].style.display = "block";
						UI.removeClass(_items[i],"hide");
					}
				}
			}
			keyuped = true;
			window[name].show();	
		}
		//输入自动选中
		if(window[name].autochecked){
			var _checkbox = _self.checkbox() ;
			UI.each(_checkbox,function(o){
				if (o.parentNode == null)
					return false;
				var text = o.parentNode.innerText || o.parentNode.textContent;
				var strs = input.value.split(',');
				for (var i=0; i<strs.length; i++) {
					if (UI.trim(text) == UI.trim(strs[i])){
						o.checked = true;
						break;
					}
				}
			});
		}
	});
	UI.EA(this.input,'focus',function(){
		var _items = _self.items();
		if(!window[name].readyonly){
			if(!window[name].fillin){input.value = "";}
			var cbox_length = _items.length;
			for(var i=0;i<cbox_length;i++){
				//_items[i].style.display = "block";
				UI.removeClass(_items[i],"hide");
			}	
		}
	});	
	UI.EA(this.cont,'click',function(e){
		UI.E(e).stop();
		//当没有任何数据时,返回
		if(_self.items().length == 0){
			window[name].click = true;
			return;
		}
		window[name].input_show();
		
		//修复bug:限制勾选数量时，checkbox不可勾选时点击checkbox后会隐藏菜单
		if(UI.A(UI.E(e).target,'type')!=='checkbox' && UI.E(e).target != s_text){
			window[name].click = true;
		}
		//checkbox勾选回调函数
		else{
			checkedHandler(UI.E(e).target);
		}
	});
	//Hide
	var delay;
	UI.EA(this.cont,'mouseout',function(e){
		
		delay = setTimeout(function(){
			if (window[name].click) {
				window[name].hide();
			}
		},500);
	});
	UI.EA(this.cont,'mouseover',function(e){
		clearTimeout(delay);
	});

	this.fillValue = function(){
		var arr_1 = this.input.value ? this.input.value.split(',') : [],arr_2 = [],arr_3 = [];
		var _allCheckbox = _self.allCheckbox();
		for (var i = 0,n = _allCheckbox.length;i < n;i++) {
			var text = _allCheckbox[i].parentNode.innerText || _allCheckbox[i].parentNode.textContent;
			text = UI.trim(text);
			if (_allCheckbox[i].checked) arr_2.push(text);
			else arr_3.push(text);
		}
		this.input.value = UI.apart(UI.merge(UI.merge(arr_1),arr_2),arr_3).join(',');
	}
	this.input_show = function(){
		var num = 0,cur = 0;
		var _allCheckbox = _self.allCheckbox();
		UI.each(_allCheckbox,function(o,i){
			if (o.checked) {
				cur = i
				num++;
			}
			mark=false;
		});
		if(_self.items().length>0)
		{
			var P = _allCheckbox[cur].parentNode;
			if (UI.A(this.input,'show')=="true"||this.fillin) {
				this.fillValue();
			}
			else {
				
				if (num == 0) {
					input.value = '';
				}
				else if (num == 1) {
					input.value = P.innerText || P.textContent;
				}
				else {
					input.value = UI.A(input,'rel') + ' x ' + num;
				}
			}
		}
	}
	
	this.input_show();
	var mark=false;
	this.hide_x = function(){
		UI.removeClass(this.body, 'on');
		this.cont.style.display = 'none';
		this.cont.style.visibility = 'hidden';
		UI.removeClass(this.body, 'top');
		this.display = false;
		this.click = false;
		keyuped = false;
	}
	this.hide = function(){
		UI.removeClass(this.body,'on');
		this.cont.style.display = 'none';
		this.cont.style.visibility= 'hidden';
		UI.removeClass(this.body,'top');
		this.display = false;
		this.click = false;
		keyuped = false;
		
		window[name].input_show();
		//将选中的选项置前
		this.sorted= UI.A(this.input,'sorted');
		if(this.sorted)
		{
			var e_checked;
			var t=(this);
			var te=t.allCheckbox();
			for(var j=0;j<te.length&&mark==false;j++)
			{
				if(te[j].checked)
				{
					e_checked=te[j].parentElement;
					UI.prepend(e_checked,t.list);
				}
			}
			mark=true;
		
		}

	}
	this.show = function(){
		UI.addClass(this.body,'on');
		this.cont.style.display = 'block';
		this.cont.style.visibility = 'visible';
		var h_cont = UI.height(this.cont),h_input = UI.height(this.input),h_window = UI.windowHeight(),h_page = UI.pageHeight(),y_input = UI.getY(this.input),y_scroll = UI.scrollY();
		var h_hack = (this.tools && !UI.Browser.ie && document.compatMode == 'BackCompat') ? UI.height(this.tools) : 0; //CSS Hack

		if (h_cont + h_input + y_input - y_scroll > h_window) {
			if (UI.height(this.cont) <= y_input - y_scroll) {
				UI.addClass(this.body,'top');
				
				//var cont_height = (y_input - y_scroll - 20 - h_hack)>161 && this.checkbox.length > 7?161:(y_input - y_scroll - 20 - h_hack);
				if((y_input - y_scroll - 20 - h_hack)>161 && _self.items().length > 7){
					UI.C(this.cont,'height',161 + 'px');
				}
			}
			else{
				UI.C(this.cont,'height',h_window - h_input - y_input - 75 + 'px');
			}
		}
		 if (UI.height(this.cont)<100&&_self.items().length>=3) {
			UI.C(this.cont,'height',100 + 'px');
		}
		if (UI.height(this.cont)>161&&_self.items().length>=7) {
			UI.C(this.cont,'height',161 + 'px');
		}
		this.display = true;
	}
	
	//是否有最多勾选数量限制
	if(this.maxselect){
		var _self=this;
		var _items = _self.items();
		UI.each(_items,function(o,i){
			if(!UI.GT(o,"input")[0]["disabled"]){
				UI.EA(o,'click',function(e){
					var num = _self.getCheckedboxNum();
					if(num>=_self.maxselect){
						_self.setCheckboxDisabled();
					}
					else{
						_self.setCheckboxCheckable();
					}
				});
			}
		});
	}
	
	this.getCheckedboxNum=function(){
		var num=0;
		var _allCheckbox = this.allCheckbox();
		UI.each(_allCheckbox,function(o,i){
			if (o.checked) {
				num++;
			}
		});
		
		return num;
	}
	this.setCheckboxDisabled=function(){
		var _checkbox = this.checkbox();
		UI.each(_checkbox,function(o,i){
			if (!o.checked) {
				UI.A(o,'disabled','disabled');
				UI.addClass(o,'js-set-disabled');
				UI.addClass(UI.next(o),'disabled');
			}
		});
	}	
	this.setCheckboxCheckable=function(){
		var _checkbox = this.checkbox();
		UI.each(_checkbox,function(o,i){
			if (!o.checked) {
				o.removeAttribute('disabled');
				UI.removeClass(o,'js-set-disabled');
				UI.removeClass(UI.next(o),'disabled');
			}
		});
	}
	
	//动态取消勾选状态
	this.cancelSelect=function(option){
		var valueName,values;
		var _allCheckbox = _self.allCheckbox();
		if(Object.prototype.toString.call(option)!='[object Array]'){
			for(var key in option){
				if(option.hasOwnProperty(key)){
					valueName = key;
					values = option[key];
					break;
				}
			}
		}
		
		if(!valueName){
			values=option;
		}
		UI.each(_allCheckbox,function(o,i){
			var lb = UI.next(o);
			UI.each(values,function(v,j){
				if(valueName){
					var keyValue = UI.A(lb,valueName);
					if(v==keyValue){
						o.checked=false;
					}
				}
				else{
					if(v==UI.trim(lb.innerHTML)){
						o.checked=false;
					}
				}
				
			})
		});
		
		this.input_show();
	}

	function checkedHandler(obj){
		if(!obj) return;
		if(obj.tagName.toLowerCase()=='input'){
			obj = obj.parentNode;
		}
		if(UI.hasClass(obj,'tools')) return;

		if(typeof _self.options.check == 'function'){
			_self.options.check.call(obj,obj.childNodes[0].checked);
		}
		else if(Object.prototype.toString.call(_self.options.check) == '[object Array]'){
			var len = _self.options.check.length;
			for(var i=0;i<len;i++){
				var checkedOption = _self.options.check[i];
				var checkedContainer = UI.GC(checkedOption.container);
				if(checkedOption.container && typeof checkedOption.callback == 'function' 
					&& checkedContainer && checkedContainer.length>0
					&& _self.body == checkedContainer[0]){
					 checkedOption.callback.call(obj,obj.childNodes[0].checked);		
				}
			}
		}
	}

	//显示、隐藏编辑工具栏
	if(_items.length>0){
		UI.each(_items,function(o,i){
			var toolbar=UI.GC(o,'.selectMulti-toolbar')[0];
			if(!toolbar) return;
			
			UI.hide(toolbar);
			UI.EA(o,'mouseout',function(e){
				setTimeout(function(){
					if (toolbar) {
						UI.hide(toolbar);
					}
				},100);
			});
			
			UI.EA(o,'mouseover',function(e){
				setTimeout(function(){
					if (toolbar) {
						UI.show(toolbar);
					}
				},100);
			});
			
			var edit = UI.GC(toolbar,'.selectMulti-edit')[0];
			if(!edit || !option.edit){return;}
			
			UI.EA(edit,'click',function(e){
				if(UI.isFunction(option.edit)){
					option.edit.apply(this,[this]);
				}
			})
		})
	}
	this.controlNext = function(o,show){
		if(jQuery==undefined) return;
		var $ = jQuery;
		var $o =$(o);
		var control = $o.attr("control");
		var controlEl = $("."+control);
		//alert("lenght"+controlEl.length);
		var tag = false;
		controlEl.parent().trigger("click");
		controlEl.each(function(){
			if(show){
				$(this).css("display","list-item");
				tag = true;
			}
			else{
				$(this).css("display","none");
				tag = false;
			}
			var control = $(this).attr("control");
			if(!!control) {
				_self.controlNext(this,$(this).find("input")[0].checked&&tag);
			}			
		})

	}
	
}

UI.ShowBar = function(o) {
	//Element
	this._body = UI.G(o.id);
	if (!this._body) return false;
	this._cont = UI.GC(this._body,'.cont')[0];
	this._li = UI.GT(this._cont,'li');
	this._first = this._li[0];
	this._next = UI.GC(this._body,'.next')[0];
	this._prev = UI.GC(this._body,'.prev')[0];
	
	//Option
	o.speed = o.speed||1500;
	this.cur = 1;
	this.num = this._li.length;
	this.animate = o.animate != undefined ? o.animate : true;
	this.autoplay = o.autoplay != undefined ? o.autoplay : true;
	this.pause = o.pause; //Stop Play When Mouseover Cont
	this.connect = o.connect; //No Disconnected
	this.step = Number(UI.C(this._first,o.action == 'marginTop' ? 'height' : 'width').slice(0,-2));
	this.delay = setInterval('if (' + o.name + '.autoplay) ' + o.name + '.next()',o.speed);
	this.delay2 = null; //Animate
	this.page = o.page;
	this.tmp_cur = 0;
	
	//Connect
	if (this.connect) this._li[0].parentNode.appendChild(this._li[0].cloneNode(true));
	//Page
	if (this.page) {
		if (UI.GC(this._body,'.page').length>0) {
			this._page = UI.GC(this._body,'.page')[0];
			this._li2 = UI.GT(this._page,'li');
			for (var i=0;i<this.num;i++) {
				UI.EA(this._li2[i],this.page,o.name + '.play(' + (i + 1) + ')');
			}
		}
		else {
			this._page = UI.DC('ul'),html=[];
			this._page.className = 'page';
			for (var i=0;i<this.num;i++) {
				html[i] = '<li '+ (!i ? 'class="on" ': '') +'on' + this.page + '="' + o.name + '.play(' + (i + 1) + ')">' + (i + 1) + '</li>';
			}
			this._page.innerHTML = html.join('');
			this._body.appendChild(this._page);
			this._li2 = UI.GT(this._page,'li');
		}
	}
	//Opacity
	if (o.action == 'opacity') {
		for (var i=0;i<this.num;i++) {
			UI.C(this._li[i],'opacity',0);
		}
		UI.C(this._li[0],'opacity',1);
	}
	
	//Event
	this._next.onclick = function(){ eval(o.name + '.next()'); }
	this._prev.onclick = function(){ eval(o.name + '.prev()'); }
	this._first.parentNode.onmouseover = function(){ eval('if(' + o.name + '.pause) clearInterval(' + o.name + '.delay)'); }
	this._first.parentNode.onmouseout = function(){ eval('if(' + o.name + '.pause) ' + o.name + '.delay = setInterval(\'' + o.name + '.next()\',' + o.speed + ')'); }
	this._next.onmouseover = this._prev.onmouseover = this._first.parentNode.onmouseover;
	this._next.onmouseout = this._prev.onmouseout = this._first.parentNode.onmouseout;

	//Function
	this.show = function(){
		if (this.page) {
			if (this.tmp_cur != null) {
				this._li2[this.tmp_cur].className = '';
			}
			var cur_page = this.cur>this.num ? 0 : this.cur-1;
			this._li2[cur_page].className = 'on';
			this.tmp_cur = cur_page;
		}
		if (this.animate) {
			clearInterval(this.delay2);
			if (o.action == 'opacity') {
				this.delay2 = UI.animate(this._li[this.cur-1],o.action,1,'clearInterval(' + o.name + '.delay2);');
				//UI.animate(this._li[this.tmp_cur],o.action,0);
				this.tmp_cur = this.cur>this.num ? 0 : this.cur-1;
			}
			else this.delay2 = UI.animate(this._first,o.action,-this.step * (this.cur-1),'clearInterval(' + o.name + '.delay2);');
		}
		else {
			if (o.action == 'opacity') {
				//this._li[this.cur-1].style.display = 'none';
			}
			else this._first.style[o.action] = -this.step * (this.cur-1) + 'px';
		}
	}
	this.next = function(){
		this.cur++;
		if (this.connect) {
			if (this.cur > this.num+1) {
				this._first.style.marginTop = '';
				this.cur = 2;
			}
		}
		else if (this.cur > this.num) this.cur = 1;
		this.show();
	}
	this.prev = function(){
		this.cur--;
		if (this.cur < 1) this.cur = this.num;
		this.show();
	}
	this.play = function(n){
		this.cur = n;
		this.show();
	}
}
//表格列数可控寻找class为table_control的表格
UI.table_control = function(option)
{
	if(typeof jQuery =='undefined'){
		return;
	}
	var option = jQuery.extend({show:false},option);
	var tabs = jQuery('.table_control');
	UI.each(tabs,function(e){  
	//遍历页面所有可控表格
		new UI.control(e,option,jQuery);            	
	});
};
UI.control = function(e,option,$)
{
	var $table = $(e);//原表格
	this.ctrlwrap=$('<div class="popup_menu">');
	var uc,sd,nl,st;
	uc = $table.attr('uncontrol')?$table.attr('uncontrol').split(','):'';
	sd = $table.attr('setdefault')? $table.attr('setdefault').split(','):'';
	nl = $table.attr('nolist')?$table.attr('nolist').split(','):'';
	st = $table.attr('settype')?$table.attr('settype'):'';
	this.uncontrol =valid(uc);
	this.setdefault = valid(sd);
	this.nolist = valid(nl);
	this.settype = st;

	this.createList = function(){
		//构造控制列表
		var titles = $table.find('.first_title').children();
		var wrap = $('<ul></ul>');
		var html = [];
		uc = this.uncontrol;
		sd = this.setdefault;
		titles.each(function(i){
			if(!UI.has(uc,i)){
				html.push('<li class="Input" call="' + i + '"><b class="' + (UI.has(sd,i) ? 'checkbox': 'checkbox_checked') + '" rel=""></b><span>'+ this.innerHTML +'</span></li>');
			}
			
		});
		html.push('<li class="Input SelectAll"><b class="checkbox_checked" rel=""></b><span>'+UI.control.text.selectAll+'</span></li>');
		wrap.append(html);
		titles.each(function(i){
			if (UI.has(sd,i)){//setdefault有已知列数，则取消全选
				wrap.find('.SelectAll b')[0].className = 'checkbox';
			}
		})
		wrap = $('<div class="popup_menu">').append(wrap);
		
		return wrap;
	}
	this.initShow = function(){
		//第一次初始化时隐藏default列
		for(var i = 0;i<this.setdefault.length;i++){
 			showOrhide($table.find('.j-ctrlColumn'+this.setdefault[i]),true);
 		}
	}
	this.uncontrolShow = function(uncontrol){
	 	$table.find('tr').each(function(){
	 		$(this).children().not('.td_setting_first').not('.td_setting').not('.td_setting_last').hide();
	 	})
	 	for(var i = 0;i<uncontrol.length;i++){
	 		showOrhide($table.find('.j-ctrlColumn'+uncontrol[i]))
	 	}
	 }
 	this.ctrlShow = function(control,row,hide){
    	var i ;
    	var rowLen = row.length;
    	var $ctrl = $(control);
    	for(i=0;i<rowLen;i++){
    		if(row[i]=='-1'){
    			//-1显示整个表格
    			showOrhide($ctrl.find('tr').children(),hide);
    		}
    		else{
    			var rowindex = parseInt(row[i]);//显示row[i](rowindex)列
    			showOrhide($ctrl.find('.j-ctrlColumn'+rowindex),hide);
    		}
    	}
    }
	this.addCtrl = function(control,list){
		var lis = list.find('.Input');
		var ctrlShow = this.ctrlShow;
		var uncontrolShow = this.uncontrolShow;
		lis.each(function(i){
			$(this).bind('mouseover',function(){
				$(this).addClass('hover');
			});
			$(this).bind('mouseout',function(){
				$(this).removeClass('hover');
			});
			$(this).bind('click',function(){
				var ischecked = $(this).find('b').hasClass('checkbox_checked');
				if($(this).hasClass('SelectAll')){
					if(!ischecked){
						//点击全选
						lis.find('b').each(function(){
							this.className='checkbox_checked';
						});
						ctrlShow(control,['-1']);
					}
					else{
						//取消全选，显示默认项
						lis.find('b').each(function(){
							this.className='checkbox';
						});
						uncontrolShow(uc);
					}	
				}
				else{
					//点击不是全选check
					var index = $(this).attr('call');
					if(!ischecked){
						$(this).find('b')[0].className='checkbox_checked';
						ctrlShow(control,[index]);


					}
					else{
						$(this).find('b')[0].className='checkbox';
						ctrlShow(control,[index],true);
						list.find('.SelectAll').find('b')[0].className='checkbox';
					}
					
				}
				//判断是否符合全选逻辑
				var checkedLen =  lis.not('.SelectAll').find('.checkbox_checked').length;
				var checkboxLen = lis.not('.SelectAll').length;
				if(checkedLen == checkboxLen){
					list.find('.SelectAll').find('b')[0].className='checkbox_checked';
				}
				else{
					list.find('.SelectAll').find('b')[0].className='checkbox';
				}
				//setdefault值跟随改变用于记录用户设置
				var setdefault='';
				lis.not('.SelectAll').find('.checkbox').each(function(){
					setdefault += jQuery(this).parent('.Input').attr('call')+',';
				});
				control.attr('setdefault',setdefault);
			})
		})
 		return list;
	}
	this.divColumn = function(control){
		var column =[];
		control.find('.first_title').children().each(function(index){
			var colspan = $(this).attr('colspan')||1;
			if(colspan==1){
				column.push('j-ctrlColumn'+index);
			}
			else{
				for(var i = 0;i<colspan;i++){
					column.push('j-ctrlColumn'+index);
				}
			}	
		});

		var _trs =  control.find('tr');
	   	_trs.each(function(){
	   		var _thisTR = $(this);
	   		var _tdsInTR = $(this).children();
	   		_tdsInTR.each(function(index){
	   			var _thisTD = $(this);
	   			var colspan = $(this).attr('colspan')||1;
	   			if(colspan>1){
	   				for(var i=0;i<colspan-1;i++)
	   					$(this).after('<td class="spancell"></td>');
	   				$(this).attr('tempcolspan',colspan);
	   				$(this).attr('colspan',1);
	   			}
	   			var rowspan = $(this).attr('rowspan')||1;
	   			if(rowspan>1){
	   				//在当前单元格所在行后的tempRowspan-1个兄弟tr结点下
					//的位置为index(_thisTD)的td节点前插入一个stuff_td
					var start = _trs.index(_thisTR) + 1;
					var end = _trs.index(_thisTR) + rowspan;
					var temp1 = _trs.slice(start,end);
					temp1.each(function(){
						$(this).children().eq(_tdsInTR.index(_thisTD)).before('<td class="spancell"></td>');
					});
					$(this).attr('temprowspan',rowspan);
	   				$(this).attr('rowspan',1);
	   			}
	   		})
	   	})
	   	_trs.each(function(){
	   		$(this).children().each(function(index){
	   			for(var i =0;i<column.length;i++){
	   				if(i==index)
	   					$(this).addClass(column[i]);
	   			}
	   			var rowspan= $(this).attr('temprowspan')||1;
	   			if(rowspan>1)
	   				$(this).attr('rowspan',rowspan);
	   			var colspan= $(this).attr('tempcolspan')||1;
	   			if(colspan>1)
	   				$(this).attr('colspan',colspan);
	   		})
	   	});
	   	control.find('.spancell').remove(); 
	}
	this.appendList = function(control,settype,list){
		var nolist = this.nolist;

		if(settype=='left'){
			var st = control.find('.second_title')
			if(st && st.lentgh>0){
				control.find('.first_title').eq(0).prepend('<td rowSpan="2" class="td_setting_first">&nbsp;</td>')
			}
			else{
				control.find('.first_title').eq(0).prepend('<td class="td_setting_first">&nbsp;</td>')
			}
			var tbody = control.find('tbody').not('.multi_title').children();
			var listHeight  = tbody.length;
			if(control.find('tfoot').length>0){
				
			}control.find('tfoot').children().prepend('<td class="td_setting_last" ></td>');
			tbody.eq(0).prepend('<td class="td_setting" rowspan="'+listHeight+'"></td>')
			control.find('.td_setting').append(list);
			control.find('.popup_menu').prepend('<span class="img_set_on " title='+ UI.control.text.buttonTitle +'></span>')
			control.find('.img_set_on').click(function(){
				$(this).toggleClass('set_off');
				$(this).next().toggle();
				if(jQuery(e).attr('open')){
				  	jQuery(e).removeAttr('open');
				  }	
				else{
				  	jQuery(e).attr('open','');
				}
			})
		}
		else{
		/*	var ths = UI.GT(UI.GT(e,'tr')[0],'th');*/
		var ths = control.find('.first_title').children();
			var body = $(list)[0];
			var index = 0;
			$('body').append(list)
			UI.each(ths,function(on){
				if(!UI.has(nolist,index))
				{
					on.innerHTML = "<div class='control_div'>" + on.innerHTML +"<a class='control_bt' href='javascript:void(0)'></a></div>";
				}
				index++;
			});
			index=0;
			UI.each(ths,function(on){
				
				if(UI.has(nolist,index)){index++;}
				else
				{
					index++;
					UI.EA(on,'mouseover',function(){
						UI.addClass(on,"light");
					});
					UI.EA(on,'mouseout',function(){
						UI.removeClass(on,"light");
					});	
					UI.EA(UI.GC(on,'.control_bt')[0],'click',function(e)
					{
						var obj = UI.ET(e);
						UI.each(ths,function(on){UI.removeClass(on,'active')});
						UI.addClass(UI.parent(UI.parent(obj)),'active');
						body.style.display = 'block';
						body.style.top = UI.getY(obj) + UI.height(obj)  +'px';
						if(UI.pageWidth() - UI.getX(obj) < UI.width(body))
						{
							body.style.left = UI.getX(obj) - UI.width(body) + UI.width(obj)+'px';
						}
						else
						{
							body.style.left = UI.getX(obj)  +'px';

						}
						if(UI.Browser.ie6||UI.Browser.ie7)
						{
							body.style.top = UI.getY(obj) + UI.height(obj)  + 8 +'px';
							if(UI.pageWidth() - UI.getX(obj) < UI.width(body))
							{
								body.style.left = UI.getX(obj) - UI.width(body) + UI.width(obj) - 8 +'px';
							}
							else
							{
								body.style.left = UI.getX(obj) - 8 +'px';
							}
							
						}
						
					});
				}
			});
			body.onmouseover = function(e){
				body.style.display = 'block';
			}
			body.onmouseout = function(e){ 
				body.style.display = 'none';
				UI.each(ths,function(on){UI.removeClass(on,'active')});
			}
			/*control.find('.first_title').children().each(function(index){
				if(!UI.has(nolist,index)){
					$(this)[0].innerHTML = "<div class='control_div'>" + $(this)[0].innerHTML +"<a class='control_bt' href='javascript:void(0)'></a></div>";
				}
					
			})
			$('body').append(list);
			control.find('.control_bt').click(function(e){
				var obj = UI.ET(e);
				var _trfirst =  control.find('tr.first_title');
				control.find('.control_bt').click(function(){
					_trfirst.children().removeClass('active');
					$(this).parent().parent().addClass('active');
					list.show();
				})
				UI.each(ths,function(on){UI.removeClass(on,'active')});
				UI.addClass(UI.parent(UI.parent(obj)),'active');
				body.style.display = 'block';
				body.style.top = UI.getY(obj) + UI.height(obj)  +'px';
				if(UI.pageWidth() - UI.getX(obj) < UI.width(body))
				{
					body.style.left = UI.getX(obj) - UI.width(body) + UI.width(obj)+'px';
				}
				else
				{
					body.style.left = UI.getX(obj)  +'px';

				}
				if(UI.Browser.ie6||UI.Browser.ie7)
				{
					body.style.top = UI.getY(obj) + UI.height(obj)  + 8 +'px';
					if(UI.pageWidth() - UI.getX(obj) < UI.width(body))
					{
						body.style.left = UI.getX(obj) - UI.width(body) + UI.width(obj) - 8 +'px';
					}
					else
					{
						body.style.left = UI.getX(obj) - 8 +'px';
					}
					
				}

			})*/
		
		}
	}	
   	this.divColumn($table);
  	this.initShow();
   	var list = this.addCtrl($table,this.createList());
	this.appendList($table,this.settype,list);
	if(!option.show){
		$table.find(".popup_menu ul").hide();
	}
	else{
			$table.attr('open','');
	}
	if(!$table.attr('open')){//如果状态为关闭
			$table.find(".popup_menu ul").hide();
			$table.find(".img_set_on").addClass('set_off');
		}
	else{
			$table.find(".popup_menu ul").show();
			$table.find(".img_set_on").addClass('set_off');
	}
	function valid(s){
		if(s&&s[s.length-1]==''){
			s.pop();
		}
		return s;
	}
	function showOrhide(el,flag){
		if(flag){
			el.hide();
		}
		else{
			el.show();
		}
	}	
}
if(!UI.control.text){
	UI.control.text = {
		buttonTitle : "列数控制",
		selectAll :"全选"
	}
}

UI.Tip = { //Title Tip
	wrap : UI.DC('div'),
	build : function(o) {
		this.wrap.className = 'title_tip';
		this.wrap.innerHTML = '<iframe src="javascript:false;" style="display:none;position:absolute;z-index:-1;"></iframe><div class="cont"></div><b class="cor_1"></b><b class="cor_2"></b><b class="cor_3"></b><b class="cor_4"></b>';
		this.cover = UI.GT(this.wrap,'iframe')[0];
		this.cont = UI.GT(this.wrap,'div')[0];
		this.wrap.appendChild(this.cont);
		document.body.appendChild(this.wrap);
		UI.A(o,'data-width',0);
		var _self=this;
		var et=UI.Browser.ie? "mousemove":"mouseover"
		UI.EA(o,et,function(e) {
			e = window.event || e;
			var css = UI.Tip.wrap.style,html=document.documentElement,body=document.body,W,scrollWidth,H,T,L,cont=UI.Tip.cont;
			W = UI.windowWidth();
			scrollWidth = o.ownerDocument.documentElement.scrollWidth;
			H = UI.windowHeight();
			T = UI.scrollY();
			L = UI.scrollX();
			var tipText = UI.A(o,"tip");
			/*注释掉下面这句后遗留的问题：对于<!的检测会有问题*/
			//tipText = tipText.replace(/\</g,'&lt;').replace(/>/g,'&gt;');
			cont.innerHTML = tipText;
			UI.Tip.wrap.className="title_tip i_"+UI.A(o,"tipico");

			UI.Tip.show();
			e.clientY < H/2 ? css.top = e.clientY + T + 9 + 'px': css.top = e.clientY + T-UI.Tip.wrap.offsetHeight-8+ 9+'px';
			
			var contWidth = UI.A(o,'data-width');
			if(cont.offsetWidth > contWidth){
				contWidth=cont.offsetWidth;
				cont.style.width=cont.offsetWidth + 'px';
			}
			else{
				cont.style.width=offsetWidth + 'px';
			}

			if (UI.Browser.ie&&!cont.style.maxWidth) {
				if(cont.offsetWidth>250)
				{
					cont.style.width = 250+'px'
				};
			};
	
			if(cont.offsetWidth < 100 && cont.offsetHeight > cont.offsetWidth){
				cont.style.width=100+'px';
			}
		
			var leftPos = e.clientX + L + 12;
			if((leftPos + cont.offsetWidth) > W){
				css.left = leftPos - cont.offsetWidth + 'px';
			}
			else{
				css.left = leftPos + 'px';
			}
			
		});
		UI.EA(o,'mouseout',function(e) {
			UI.Tip.hide();
			UI.Tip.cont.innerHTML = '';
			UI.Tip.wrap.className="title_tip";
		});
	},
	show : function(e) {
		this.wrap.style.display = 'block';
	},
	hide : function() {
		this.wrap.style.display = 'none';
		this.cont.style.width="auto";
	}
}
UI.TipBox = function(o) {
	//Dom
	this._body = top.document.createElement('div');//top.UI.DC('div');
	this._body.className = 'tip_box';
	this._body.innerHTML = '<a class="fix" href="javascript:void(0)" title="Hold" onclick="UI.toggleClass(this,\'on\');return false;" onfocus="this.blur()" tabindex="-1"></a><a class="close" href="javascript:void(0)" onclick="return false;" title="Close" tabindex="-1"></a><b class="tip_arrow"></b><b class="shadow"></b><div class="tip_wrap"><div class="tip_title"></div><div class="tip_cont"></div></div>' + (UI.Browser.ie6 ? '<iframe src="javascript:false;" class="cover" height="100%" width="100%"></iframe>' : '');
	this._close = UI.GC(this._body,'a.close')[0];
	this._fix = UI.GC(this._body,'a.fix')[0];
	this._arrow = UI.GC(this._body,'b.tip_arrow')[0];
	this._shadow = UI.GC(this._body,'b.shadow')[0];
	this._wrap = UI.GC(this._body,'div.tip_wrap')[0];
	this._title = UI.GC(this._body,'div.tip_title')[0];
	this._cont = UI.GC(this._body,'div.tip_cont')[0];
	this._cover = UI.GC(this._body,'iframe.cover')[0];

	//Status
	this.__display = false;
	this.__large = o.large;
	this.__fix = false;
	this._body.style.display = 'none';

    if (o.html) {
        this.show(o);
    }
    //Event
    var name = o.name,body = this._body,wrap = this._wrap,title = this._title,shadow = this._shadow,close = this._close,cover = this._cover,_Self = this;
    this.key = function(e) {
        switch(UI.E(e).key) {
            case 27:
                if (_Self.__display) _Self.hide();
                break;
        }
    }
    this.childframe_close = function(o) {
        UI.EA(o.document,'click',function(e){
            if (!_Self.__fix) {
                _Self.hide();
            }
			//hide tipbox when dialog closed
			if(jQuery && _Self._target && _Self._window){
				setTimeout(function(){
					try{
						var frameEle = _Self._window.frameElement;

						var dialogDiv;
						if(frameEle){
							dialogDiv = jQuery(frameEle).parents('.dialog2')[0];
						}
						if(!dialogDiv){
							dialogDiv = jQuery(_Self._target).parents('.dialog2')[0];
						}
						
						if(dialogDiv && jQuery(dialogDiv).is(":hidden")){
							_Self.hide();
						}
					}catch(e){}
				},200); 
			}
        });
        
        for(var i=0;i<o.frames.length;i++) {
            if(typeof o.frames[i] != 'undefined'){
                this.childframe_close(o.frames[i]);
            }
        }
    }
    UI.EA(top.document,'keyup',this.key);
    UI.EA(this._close,'click',function(e){
            _Self.hide();
    });
    UI.EA(this._fix,'click',function(e){
            _Self.__fix = !_Self.__fix;
    });
    UI.EA(body,'click',function(e){
        UI.E(e).stop();
    });
    UI.EA(top,'resize',function(){
        //fix bug: ie7 allways trigger resize event before page loaded
        if(_Self._body.style.display===' '){
            _Self.hide();
        }
    });
    /*if (UI.Browser.ie6) { //Kill IE6 Select Scroll Bug
        setInterval(function(){
            cover.style.zoom = cover.style.zoom == '1' ? '0' : '1';
        },200);
    };*/
    
    (function(){
        var x,y,_x,_y,h_wrap,top,left,move;
        UI.drag(title,{
            start : function(e){
                var E = UI.E(e);
                E.prevent();
                x = E.x;
                y = E.y;
                UI.hide(_Self._arrow);
                top = parseInt(UI.C(body,'top'));
                left = parseInt(UI.C(body,'left'));
            },
            drag : function(e){
                e = e || (this.ownerDocument||this).parentWindow.event;
                var E = UI.E(e);
                E.prevent();
                body.style.left = left + E.x - x + 'px';
                body.style.top =  top + E.y - y + 'px';
            }
        },false,true);
    })();
    //this._body.onmouseover = this._cont.onmousedown = function(e) {
    //  UI.E(e).stop();
    //};
	
    //Method
    this.show = function(o) {
        var selfDoc=o.target.ownerDocument;
        var selfWindow='defaultView' in selfDoc? selfDoc.defaultView:selfDoc.parentWindow; 
		var _self = this;
		
		this._window = selfWindow;
		
        if (!this.__display){
            this.childframe_close(top);
        }   
        
        if (this.__display && this._target != o.target) {
            this.hide();
        }
        if (this.__display) return false;
		
		if(typeof this._arrow === 'unknown'){
			this._arrow = UI.GC(this._body,'b.tip_arrow')[0];
		}
		
		if(typeof this._fix === 'unknown'){
			this._fix = UI.GC(this._body,'a.fix')[0];
		}
		
		if(typeof this._cont === 'unknown'){
			this._cont = this._cont = UI.GC(this._body,'div.tip_cont')[0];
		}
		
        UI.show(this._arrow);
        this.__large = o.large;
        this._target = o.target;
        this.__html = this._cont.innerHTML = o.html;

        this._body.style.display = '';
        top.document.body.appendChild(this._body);
        
        if(o.width==""&&o.height=="")
        {
            if (o.large) {
            body.style.width = '400px';
            wrap.style.height = '200px';
            }
            else {
                wrap.style.cursor = '';
                body.style.width = '250px';
                wrap.style.height = '';
				/*add by zgf*/
				body.style.height = UI.height(body);
            }
        
        }
        else
        {
            wrap.style.cursor = '';
            body.style.width = o.width?parseInt(o.width)+'px':'250px';
            wrap.style.height = o.height?parseInt(o.height)+'px':'';
        }

		//Value
		var h_window = top.UI.windowHeight(),h_wrap = UI.height(this._wrap),h_target = UI.height(this._target),w_window = top.UI.windowWidth(),w_wrap = UI.width(this._wrap),w_target = UI.width(this._target),x_target = UI.getX(this._target),y_target = UI.getY(this._target),x_frame=UI.frameX(selfWindow),y_frame=UI.frameY(selfWindow),w_box=UI.width(this._body);
		var w_arrow = 7,h_arrow = 17;
        var _scrollY =0,_scrollX = 0;
		var now = selfWindow;
		var bodyClass= now.document.body.className;

		var scrollValue = getScrollHeight(o.target,this._body,o.target);
        if(o.target.ownerDocument!=document){//判断是否在iframe中 
          _scrollY += scrollValue.scrolltop; 
        }   
		_scrollX += scrollValue.scrollleft;
		if (parseInt(w_window) < parseInt(w_wrap + w_target + x_target + x_frame + w_arrow)  || w_window < w_wrap) { //Right Arrow
			UI.addClass(this._body,'right');
			this._body.style.top = (y_target + y_frame  - h_target/2  - _scrollY) + 'px';
		    this._body.style.left = (x_target + x_frame - w_arrow - w_wrap  - _scrollX) + 'px';
			this.__right = true;
		}
		else {
			UI.removeClass(this._body,'right');
			this._body.style.top = (y_target + y_frame - h_target/2  - _scrollY)  + 'px';
		    this._body.style.left = (x_target + w_target + x_frame + w_arrow  - _scrollX) + 'px';
			this.__right = false;
		}

        this._shadow.style.height = UI.height(this._wrap) + 'px';
        this.__display = true;
        if(o.resize&&o.large){//未启用，待完善
            var B = UI.html('<b class="ico"></b>')[0];
            this._body.appendChild(B);
            var ex,ey,w_b,h_b,padding_y = 0,padding_x = 0;
            var ow=this._body,oc=this._wrap,os=this._shadow;
            UI.drag(B,{
                start : function(e){
                var E = UI.E(e);
                ex = E.x;
                ey = E.y;
                w_b=UI.width(ow);
                h_b=UI.height(ow);
                if (!UI.Browser.ie && document.compatMode == 'BackCompat') {
                padding_x = parseInt(UI.C(oc,'paddingLeft')) + parseInt(UI.C(oc,'paddingRight'))+1;
                padding_y = parseInt(UI.C(oc,'paddingBottom')) + parseInt(UI.C(oc,'paddingTop'))+1;
                }
                UI.hide(Self._arrow);
                },
                drag : function(e){
                var E = UI.E(e),W,H;
                W=w_b+E.x-ex<120?120:w_b+E.x-ex;
                H=h_b+E.y-ey<100?100:h_b+E.y-ey;
                os.style.width=ow.style.width=W+"px";
                oc.style.width=W-padding_x+"px";
                ow.style.height=H+"px";
                oc.style.height=H-padding_y+1+"px";
                os.style.height=UI.height(oc) + 'px';
                }
            },true,true)
        }
		
		
		if(selfWindow.frameElement){
			UI.EA(selfWindow.frameElement,'load',function(){
				_self.hide();
				if(top.UI["tools"]){
					top.UI["tools"].hide();
				}
			})
		}
		
		bindScroll(o.target,this._body,o.target);

    }
    this.hide = function() {
		this.__fix=false;
		if(typeof this._fix === 'unknown'){
			this._fix = UI.GC(this._body,'a.fix')[0];
		}
		
		UI.removeClass(this._fix,'on');
        
		if(UI.parent(this._body)){
			UI.parent(this._body).removeChild(this._body);
		}
		
		this.__display = false;
	}
	
	function bindScroll(o,tipbody,target){
		var tipContainerParent = UI.parent(o);

		if(tipContainerParent && tipContainerParent.tagName!='HTML' && !UI.hasClass(tipContainerParent,'dialog')){
			var initScrollTop = UI.scrollY(tipContainerParent);
			var initTipBodyTop = tipbody.style.top;
			var initTargetOffsetTop = UI.offset(target).top;
			function scrollTip(){
				var targetOffsetTop = UI.offset(target).top;
				tipbody.style.top = (parseFloat(initTipBodyTop) -initTargetOffsetTop + targetOffsetTop) +'px';
				if(targetOffsetTop<=0 || targetOffsetTop > UI.height(this)){
					tipbody.style.display='none';
				}
				else{
					tipbody.style.display='';
				}
			}
			tipContainerParent.onscroll = scrollTip;
			
			bindScroll(tipContainerParent,tipbody,target)
		}
	}
	
	function getScrollHeight(o){
		var tipContainerParent = UI.parent(o);
		var initScrollTop = UI.scrollYFrame(tipContainerParent);
		var initScrollLeft = UI.scrollX(tipContainerParent);
		return {scrolltop:initScrollTop, scrollleft:initScrollLeft};		
	}
}

UI.TipBox.init=function(name){
	return new top.UI.TipBox({name:'top.UI.'+name});
}
;(function($,UI,window){
	if(typeof $ == 'undefined') return;
	if(typeof UI == 'undefined') UI = window.UI = {};

	UI.Top_tips = function(o) {
		this.text = o.text;
		this.type = o.type;
		this.container = o.container;
		this.position = o.position ? o.position : window;
		this.topTips = this.position.document.createElement('div');
		this.top = o.top || 0;
		//当页面有navbar时
		this.nav = o.nav || ".navbar";
		var $nav = $(this.nav);
		var navHeight = $nav.outerHeight();
		var windowScrollTop = $(window).scrollTop();
		var animateTop = 0;
		var _time = parseInt(o.time) || 2000;

		var _top_tips = this.topTips;
		
		var $toptip = $(_top_tips);
		var _windowWidth = $(this.position).width();
		var _offset = getOffsetTop(this.position, 0);
		
		if (this.type == "error") {
			_top_tips.className = 'top_tips tips_error';
		} else if (this.type == "success") {
			_top_tips.className = 'top_tips tips_success';
		} else {
			_top_tips.className = 'top_tips';
		}

		_top_tips.innerHTML = this.text;
		//we should set init left=0,top=0, or when dialog ~< 500 and text has many word,
		//toptip container div's width will get smaller,make text layout multi line,
		//UI.width(_top_tips) can't get correct value
		_top_tips.style.left = '0px';
		_top_tips.style.top = '0px';

		if (this.container == 'dialog') {
			top.document.body.appendChild(_top_tips);
			$toptip = $(_top_tips, top.document);

			_top_tips.style.left = _offset.left + (_windowWidth - UI.width(_top_tips)) / 2 + 'px';
			_top_tips.style.top = _offset.top - 30 + 'px';
			_top_tips.style.display = 'none';
			$toptip.animate({
				top: _offset.top,
				display: 'show'
			}, "slow");

		} else {
			if (this.position && this.position.document) {
				this.position.document.body.appendChild(_top_tips);
				$toptip = $(_top_tips, this.position.document);
			} else {
				document.body.appendChild(_top_tips);
			}

			_top_tips.style.left = (_windowWidth - UI.width(_top_tips)) / 2 + 'px';

			if (navHeight > 0) { //portal中检测是否在导航下方显示并检测滚动是否出现
				if (windowScrollTop > navHeight) {
					_top_tips.style.top = this.top;
				} else if ((windowScrollTop < navHeight) && (windowScrollTop > 0)) {
					_top_tips.style.top = (navHeight - windowScrollTop) + 'px';
				} else {
					_top_tips.style.top = navHeight + 'px';
				}
			} else {
				_top_tips.style.top = this.top;

			}
			$toptip.hide();
			$toptip.slideDown();
			setTimeout(function() {
				$toptip.slideUp()
			}, _time);
		}

		setTimeout(function() {
			$toptip.remove()
		}, (_time + 2000));

		function getOffsetTop(o, off) {
			if (!off) {
				off = {
					top: 0,
					left: 0
				};
			}
			
			if (!o.frameElement) {
				return off;
			} else {
				off.top = $(o.frameElement).offset().top + off.top;
				off.left = $(o.frameElement).offset().top + off.left;
				return getOffsetTop(o.parent, off);
			}
		}
	};
	UI.Top_tips.close = function(o) {
		var o = o || {};

		var $toptip = $('.top_tips');
		if (o.position && o.position.document) {
			$toptip = $('.top_tips', o.position.document);
		}

		if (o.animate) {
			$toptip.animate({
				top: -30 + 'px'
			}, "slow", function() {
				$(this).remove();
			});
		} else {
			$toptip.remove();
		}
	}
}(window.jQuery,window.UI,window));

UI.Topology = function(o){
	var Self = this;
	if (!o.line) o.line = {};
	this.lineStyle = {
		color : o.line.color || 'black',
		width : o.line.width || 1,
		alpha : o.line.alpha || 1
	};

	//Canvas Object
	this.canvas = UI.G(o.id);
	this.canvas.width = o.width;
	this.canvas.height = o.height;
	this.ctx = this.canvas.getContext('2d');
	this.ctx.strokeStyle = this.lineStyle.color;
	this.ctx.lineWidth = this.lineStyle.width;
	this.ctx.globalAlpha = this.lineStyle.alpha;
	this.ctx.save();

	//Dom
	this.body = UI.html('<div class="' + this.canvas.className + '"><div class="tmpBox"></div><div class="contBox"></div></div>')[0];
	this.body.style.cssText = 'top:' + UI.getY(this.canvas) + 'px;left:' + UI.getX(this.canvas) + 'px;width:' + UI.width(this.canvas) + 'px;height:' + UI.height(this.canvas) + 'px;';
	this.tmpBox = UI.GC(this.body,'.tmpBox')[0];
	this.contBox = UI.GC(this.body,'.contBox')[0];
	this.tmp = null; //Tmp Dom
	this.current = null; //Current Icon
	this.currentId = null; //Current Icon's Index ID
	this.data = o.data;
	this.dataTmp = this.cloneData(o.data);
	this.boolDrag = o.boolDrag||false;
	this.draw();
	UI.before(this.body,this.canvas);
	this.x = UI.getX(this.body);
	this.y = UI.getY(this.body);

	//PopupMenu
	this.popupMenuData = o.popupMenu || {};
	if (o.popupMenu) {
		this.popupMenu = new UI.PopupMenu(this.body);
		this.popupMenu.setActions(this.popupMenuData.main);
		UI.EA(this.body,'mousedown',function(e){
			var E = UI.E(e);
			if (E.button == 2 || (UI.Browser.ie && E.button == 0)) {
				Self.popupMenu.popup(Self.popupMenuData.main);
			}
		});
	}

	//Kill Select Font And Picture
	this.body.onselectstart = function(e){
		return false;
	};
	if(UI.Browser.firefox) {
 		Self.canvas.parentNode.onscroll = function () {
 			Self.drawLine(Self.data)
 		}
	}
}
UI.Topology.prototype = {
	draw : function(){
		this.currentId = null;
		this.parseData(this.data);
		this.drawLine(this.data);
	},
	drawIco : function(ico,wrap){
		var Self = this;
		if (ico.complete) {
			wrap.style.cssText += ';display:block;margin:0 0 0 -99999px;'; //Kill Icon's Sparkle
			setTimeout(function(){
				wrap.style.cssText += ';margin:-' + ico.width/2 + 'px 0 0 -' + ico.height/2 + 'px;';
			},0);
		}
		else setTimeout(function(){ //Kill Load Bug
			Self.drawIco(ico,wrap);
		},100);
	},
	drawLine : function(o,parent){
		if (!parent) {
			this.ctx.clearRect(0,0,this.canvas.width,this.canvas.height);
		}
		var parentObj = this.canvas.parentNode;
		for (var i = 0,num = o.length;i < num;i++) {
			if (parent) {
				if (o[i].line) {
					this.ctx.strokeStyle = o[i].line.color || this.lineStyle.color;
					this.ctx.lineWidth = o[i].line.width || this.lineStyle.width;
					this.ctx.globalAlpha = o[i].line.alpha || this.lineStyle.alpha;
				}
				var parentX = parent.x;
				var oX = o[i].x;
			 	if(UI.Browser.firefox) {
					parentX = parseInt(parentObj.scrollLeft) + parseInt(parentX);
					oX = parseInt(parentObj.scrollLeft) + parseInt(oX);
				}
				this.line(parentX,parent.y,oX,o[i].y);
				this.ctx.restore();
				this.ctx.save();
			}
			if (o[i].son) {
				this.drawLine(o[i].son,{x:o[i].x,y:o[i].y});
			}
		}
	},
	line : function(x1,y1,x2,y2){
		this.ctx.beginPath();
		this.ctx.moveTo(x1,y1);
		this.ctx.lineTo(x2,y2);
		this.ctx.stroke();
	},
	reset : function(){
		this.clear();
		this.data = this.cloneData(this.dataTmp);
		this.draw();
	},
	clear : function(){
		this.ctx.clearRect(0,0,this.canvas.width,this.canvas.height);
		this.contBox.innerHTML = '';
	},
	save : function(){
		this.dataTmp = this.cloneData(this.data);
	},
	add : function(o){
		if (this.currentId) {
			var target = this.findData(this.currentId,this.data),num = parseInt(Math.random() * 120);
			target.son = target.son || [];
			o.x = target.x + 40 + parseInt(Math.random() * 60);
			o.y = target.y + parseInt(Math.random() * 60);
			target.son.push(o);
		}
		else this.data.push(o);
		this.clear();
		this.draw();
	},
	edit : function(o){
		if (this.currentId) {
			var target = this.findData(this.currentId,this.data);
			target.name = o.name;
			target.ico = o.ico;
			this.clear();
			this.draw();
		}
	},
	remove : function(){
		if (this.currentId) {
			try{
				this.findData(this.currentId.slice(0,-1),this.data).son.splice(this.currentId.slice(-1),1);
			}catch(e){
				this.data.splice(this.currentId.slice(-1),1)
			};
		}
		this.clear();
		this.draw();
	},
	findData : function(arr,data){
		var obj = data[arr[0]];
		if (arr.length == 1) return obj;
		else {
			arr.splice(0,1);
			return this.findData(arr,obj.son);
		}
	},
	cloneData : function(o){
		var oTmp = []
		for (var i = 0,num = o.length;i < num;i++) {
			var obj = {};
			for (var j in o[i]) {
				obj[j] = o[i][j];
			}
			if (o[i].son) obj.son = this.cloneData(o[i].son);
			oTmp.push(obj);
		}
		return oTmp;
	},
	parseData : function(o,parent){
		var parentObj = this.canvas.parentNode;
		var Self = this;
		for (var i = 0,num = o.length;i < num;i++) {
			var ico = UI.DC('img'),wrap = UI.DC('span');
			ico.ondragstart = wrap.onmousedown = this.prevent;
			wrap.onmouseover = function(){
				if(!Self.boolDrag) return;
				var ok = false;
				if (Self.tmpBox.innerHTML) {
					//Check Move (Father can't move to son)
					var targetRel = UI.A(this,'rel').toString().split(','),wrapRel = UI.A(Self.tmp,'rel').toString().split(',');
					if (targetRel.length <= wrapRel.length) ok = true;
					else {
						for (var i = 0,n = wrapRel.length;i < n;i++) {
							if (wrapRel[i] != targetRel[i]) {
								ok = true;
								break;
							}
						}
					}
				}
				//document.title = ok + ',' + !UI.hasClass(this,'onSelf');
				if (!UI.hasClass(this,'onSelf') && ok) UI.addClass(this,'on');
			};
			wrap.onmouseout = function(){
				UI.removeClass(this,'on');
			};
			UI.A(wrap,'rel',parent ? parent.rel + ',' + i : i); //Data Index
			wrap.className = 'icon';
			wrap.style.cssText = 'top:' + o[i].y + 'px;left:' + o[i].x + 'px;';

			//Drag Event
			UI.drag(wrap,{start:(function(wrap){
				return function(e){
					var E = UI.E(e);
					if (E.button == 2 || (UI.Browser.ie && E.button == 0)) {
						Self.currentId = UI.A(wrap,'rel').toString().split(',');
						Self.current = Self.findData(Self.currentId.concat([]),Self.data);
						Self.popupMenu.popup(Self.popupMenuData.son);
						Self.rightClick = true;
						E.stop();
						return false;
					}
					else Self.rightClick = false;
					UI.addClass(Self.body,'onMove');
					UI.addClass(wrap,'onSelf');
					UI.removeClass(wrap,'on');
					Self.tmp = wrap.cloneNode(wrap,true);
					var x = E.x - Self.x;
					var y = E.y - Self.y;
				 	if(UI.Browser.ie) {
						x = parseInt(parentObj.scrollLeft) + parseInt(x);
						y =  parseInt(parentObj.scrollTop) + parseInt(y);
					}
				 	if(UI.Browser.firefox) {
				 		y = parseInt(document.documentElement.scrollTop) + parseInt(y)
				 	}
					Self.tmp.style.cssText = 'top:' + y + 'px;left:' + x + 'px;';
					Self.tmpBox.appendChild(Self.tmp);
				}
			})(wrap),drag:function(e){
				var E = UI.E(e);
				if (Self.rightClick) return false;
				var x = E.x - Self.x;
				var y = E.y - Self.y;
			 	if(UI.Browser.ie) {
					x = parseInt(parentObj.scrollLeft) + parseInt(x);
					y =  parseInt(parentObj.scrollTop) + parseInt(y);
				}
			 	if(UI.Browser.firefox) {
			 		y = parseInt(document.documentElement.scrollTop) + parseInt(y)
			 	}
				Self.tmp.style.cssText = 'top:' + y + 'px;left:' + x + 'px;';
			},stop:(function(wrap,o){
				return function(e){
					if (Self.rightClick) return false;
					var on = UI.GC(Self.contBox,'.on');
					if (on) {
						var targetRel = UI.A(on[0],'rel').toString().split(','),wrapRel = UI.A(wrap,'rel').toString().split(',');
						var target = Self.findData(targetRel,Self.data);
						//Add
						if (!target.son) target.son = [o];
						else target.son.push(o);
						//Delete
						try{
							Self.findData(wrapRel.slice(0,-1),Self.data).son.splice(wrapRel.slice(-1),1);
						}catch(e){
							Self.data.splice(wrapRel.slice(-1),1)
						};
						Self.clear();
						Self.draw();
					}
					else {
						var x = parseInt(UI.C(Self.tmp,'left')),y = parseInt(UI.C(Self.tmp,'top'));
						if (x < 0) x = 0;
						else if (x > Self.canvas.width) x = Self.canvas.width;
						if (y < 0) y = 0;
						else if (y > Self.canvas.height) y = Self.canvas.height;
						o.x = x;
						o.y = y;
						wrap.style.cssText += ';left:' + o.x + 'px;top:' + o.y + 'px;';
						Self.drawLine(Self.data);
					}
					UI.removeClass(Self.body,'onMove');
					UI.removeClass(wrap,'onSelf');
					Self.tmpBox.innerHTML = '';
				}
			})(wrap,o[i])},false);

			UI.A(ico,'src',o[i].ico);
			if(o[i].tip) {
				UI.A(ico, 'tip', o[i].tip);
			}
			if (ico.complete) {
				this.drawIco(ico,wrap);
			}
			else {
				ico.onload = (function(ico,wrap){
					return function(){
						Self.drawIco(ico,wrap);
					}
				})(ico,wrap);
			}
			wrap.innerHTML = '<b>' + o[i].name + '</b>';
			wrap.appendChild(ico);
			this.contBox.appendChild(wrap);
			if (o[i].son) {
				this.parseData(o[i].son,{x:o[i].x,y:o[i].y,rel:UI.A(wrap,'rel')});
			}
		}
	},
	prevent : function(e){
		UI.E(e).prevent();
	}
};
UI.Warning=function(o){
	var o=o||{};
	this.name=o.name||"";
	this.icon=o.icon?("i_"+o.icon):"";
	this.html=o.html||"";
	this.H=o.height||"auto";
	this.W=o.width||300;
	this.body=null;
	this.close=null;
	this.fun=o.fun||null;
	this._display=0;
	this.t=0;
	this.ct=0;
	this.init();
};
UI.Warning.prototype.init=function(){
	var h=this.H=="auto"?"auto":(parseInt(this.H)-30)+"px";
	this.body=UI.DC('div');
	this.body.id="body_warnning";
	this.body.className="body_warnning"
	this.body.style.cssText="display:none;right:-"+this.W+"px;width:"+this.W+"px;height:"+(this.H=='auto'?'':(this.H+'px1'));
	this.body.innerHTML="<div class='warn_head'><a href='#' class='warn_close'></a></div><div class='warn_warpper'><div class='warn_content' style='height:"+h+"px'>"+this.html+"</div></div>";
	this.close = UI.GC(this.body,'a.warn_close')[0];
	this._icon=UI.GC(this.body,'div.warn_warpper')[0];
	this._html=UI.GC(this.body,'div.warn_content')[0];
	this._icon.className="warn_warpper";
	UI.addClass(this._icon,this.icon);
	var that=this;
	this.close.onclick=function(){that.hide()}
	document.body.appendChild(that.body);
};
UI.Warning.prototype.show=function(o){
	var o=o||{};
	var body=this.body;
	this.icon=o.icon?("i_"+o.icon):"";
	this.html=o.html||this.html;
	this.H=o.height||this.H;
	this.W=o.width||this.W;
	body.style.width=this.W+"px";
	body.style.height=this.H=="auto"?"":(this.H+'px');
	this._html.style.height=this.H=="auto"?"":(parseInt(this.H)-30)+"px";
	this._icon.className="warn_warpper";
	UI.addClass(this._icon,this.icon);
	body.style.display="block";
	this._html.innerHTML=this.html;
	var that=this;
	if(!this._display)
	{
	clearInterval(this.ct);
	var s=-parseInt(this.W);
	this._display=1;
	var f=function(){
    s=s+parseInt((16-s))*0.4;
	body.style.right=s+"px";
	if(Math.abs(16-s)<3){body.style.right="16px";clearInterval(that.t);}
	}
	this.t=setInterval(f,50);
	}
};
UI.Warning.prototype.hide=function(){
	var body=this.body;
	var f,s=16,w=this.W;
	var that = this;
	var cf=function(){
		s=s-parseInt(parseInt(w)+s)*0.4;
		body.style.right=s+"px";
		if(parseInt(w)+s<3){body.style.right="-"+w+"px";body.style.display="none";clearInterval(that.ct);}
		}
	if(this._display){
		clearInterval(this.t);
		this.ct=setInterval(cf,50);
		this._display=0;
	}
	if(this.fun){this.fun();}
	return false;
};

/*for espc*/
UI.Warning.prototype.setZIndex=function(zindex) {
	this.body.style.zIndex=zindex;
};


+function ($) { "use strict";
	if(typeof $ =='undefined'){
		return;
	}
	
	var Pagination = function(options,ele){
		 var defaults = {
			element: ele,
			selectedPage: 1,
			total: 100,
			pageLength: 7,
			showLocation: 4,
			callback:function(){}
		};
		this.options = $.extend({}, defaults, options || {});
		this._init();
	}

	Pagination.prototype._init = function(options){
		 this.options = $.extend({}, this.options, options || {});
		 $(this.options.element).empty();
		 this._setpage(this.options.selectedPage);
	}
	Pagination.prototype.goto = function(page){
		
		 this._setpage(page);
	}
	
	Pagination.prototype._setpage = function(page){
		var id,totalpage,pagesize,cpage,count,curcount,outstr,countShow; 
		//初始化
		cpage = page; //初始化从哪页开始
		totalpage = this.options.total; //总共的页数
		pagesize = this.options.pageLength-1; //初始化显示几页.
		var pageNum= this.options.showLocation; //当前页数在总体页数中的位置
		var _this = Pagination;
		var outstr = ""; 
			 if(totalpage<=pagesize+1){   
			 outstr = outstr + "<a href='javascript:void(0)' " + " p_value='"+(cpage-1)+"'> < </a>";     
			for (count=1;count<=totalpage;count++) 
			{    if(count!=cpage) 
				{ 
					 outstr = outstr + "<a href='javascript:void(0)' " + " p_value='"+count+"'  >"+count+"</a>"; 
				}else{ 
					outstr = outstr + "<span class='current' >"+count+"</span>"; 
				} 
			} 
			outstr = outstr + "<a href='javascript:void(0)'" + " p_value='"+(cpage+1)+"'> > </a>"; 
		} 
		if(totalpage>pagesize+1){        
			if(cpage<=totalpage-(pagesize-2)) 
			{      
			 outstr = outstr + "<a href='javascript:void(0)' " + " p_value='"+(cpage-1)+"'> < </a>"; 
			 count=1;
			 if(cpage>pageNum){
				 count++;
				 outstr+=" <a href='javascript:void(0)' " + " p_value='"+1+"'>1</a><span>..</span>";
				 }       
				for (;count<=pagesize;count++) 
				{   
				cpage>pageNum?countShow=(cpage-pageNum+count):countShow=count;
				
				 if(countShow!=cpage) 
					{ 
						outstr = outstr + "<a href='javascript:void(0)' " + " p_value='"+countShow+"'>"+countShow+"</a>"; 
					}else{ 
						 outstr = outstr + "<span class='current'>"+countShow+"</span>"; 
					} 
				
				} 
				//显示最后两个页码
				// outstr+=" <li><span>...</span></li><li><a href='javascript:void(0)' onclick='gotopage("+(totalpage-1)+")'>"+(totalpage-1)+"</a></li><li><a href='javascript:void(0)' onclick='gotopage("+totalpage+")'>"+totalpage+"</a></li>";
				 outstr+="<span>..</span><a href='javascript:void(0)' " + " p_value='"+totalpage+"'>"+totalpage+"</a>";		 		  
				 outstr = outstr + "<a href='javascript:void(0)'" + " p_value='"+(cpage+1)+"'> > </a>"; 
			} 
			else{
				 outstr = outstr + "<a href='javascript:void(0)'" + " p_value='"+(cpage-1)+"'  > < </a>"; 
				  outstr+=" <a href='javascript:void(0)' " + " p_value='"+1+"' >1</a><span>..</span>";  
					count=2;
				for (;count<=pagesize+1;count++) 
				{  
				 cpage<(totalpage-(pagesize-pageNum)-1)?countShow=(cpage-pageNum+count):countShow=(totalpage-pagesize+count-1);
				if(countShow==2)
				{
					countShow=3;
					count++;
				}
				//countShow==totalpage-2?count++:null;
				//countShow=totalpage-10+count;
				 if(countShow!=cpage) 
					{ 			
						outstr = outstr + "<a href='javascript:void(0)'" + " p_value='"+countShow+"' >"+countShow+"</a>"; 
					}else{ 
					   outstr = outstr + "<span class='current'>"+countShow+"</span>"; 
					} 		
				} 
				if(cpage<(totalpage-(pagesize-pageNum)-1))
				{
					 outstr+="<span>..</span><a href='javascript:void(0)' " + " p_value='"+totalpage+"' >"+totalpage+"</a>";	
					}
				outstr = outstr + "<a href='javascript:void(0)' " + " p_value='"+(cpage+1)+"'> > </a>"; 
				}   
		}  
			
		this.options.element.innerHTML = "<div>" + outstr + "<\/div>"; 
		outstr = ""; 
		var self = this
		$(this.options.element).find('a').bind('click',function(){
			var val = +$(this).attr('p_value');
			 if(val<1||val>self.options.total)
			  {
				  return false;
			  }
		  	self.options.callback?self.options.callback(val): null;
			self._setpage(val);
		})
		
	}

 	var old = $.fn.Pagination
  
  	 $.fn.pagination = function( options ) {
		var isMethodCall = typeof options === "string",
			args = Array.prototype.slice.call( arguments, 1 ),
			returnValue = this;

		// allow multiple hashes to be passed on init
		options = !isMethodCall && args.length ?
			$.extend.apply( null, [ true, options ].concat(args) ) :
			options;

		// prevent calls to internal methods
		if ( isMethodCall && options.charAt( 0 ) === "_" ) {
			return returnValue;
		}

		if ( isMethodCall ) {
			this.each(function() {
				var instance = $.data( this, name ),
					methodValue = instance && $.isFunction( instance[options] ) ?
						instance[ options ].apply( instance, args ) :
						instance;
				if ( methodValue !== instance && methodValue !== undefined ) {
					returnValue = methodValue;
					return false;
				}
			});
		} else {
			this.each(function() {
				var instance = $.data( this, name );
				if ( instance ) {
					instance._init(options);
				} else {
					$.data( this, name, new Pagination( options, this ) );
				}
			});
		}

		return returnValue;
	};

  $.fn.pagination.Constructor = Pagination


  // TAB NO CONFLICT
  // ===============

  $.fn.pagination.noConflict = function () {
    $.fn.pagination = old
    return this
  }

}(window.jQuery);

/*
	@param options :
		{
			paginal:true, //if set page control
			pageSize:25,
			optionalPageSizes:[25,100],
			sortable:true,
			hasSizeCtrl:true,
			hasGoto:true,
			//isMassive:false,
			images:{'asc':'','desc':'','expand':'','blank':''},
			cookieId:'',
			'getTotalCountUrl':'',
			texts:{'text.sort.confirm':'','text.query.confirm':'','text.loading':'','total.text':'','text.no.data':''}
		}
*/


UI.tables = {}; 
UI.Table = function(tableId, url, options) {
	this.table = jQuery('#' + tableId)[0];
	this.url = url;
	this.sortMethod = 'desc';
	this.options = options;
	this.currentColumn = null;
	this.page = {
			num:1,
			end:false,
			total:-1
			
		};
	this._isinitpage = false;
	!this.options.paginationType && (this.options.paginationType='1')
	
		var table = this;
		if (this.options.paginal) {
		this.header = jQuery('#' + tableId + '-header')[0];
		this.footer = jQuery('#' + tableId + '-footer')[0];

		if(typeof this.options.paginal == "boolean" && this.options.paginal){
			if(!this.header){
				this.header = initHeaderPageCtrl(tableId);
			}
			if(!this.footer){
				this.footer = initFooterPageCtrl(tableId);
			}	
		}else if(typeof this.options.paginal == "object"){
			if(this.options.paginal.header || this.options.paginal.header =="true"){
				if(!this.header){
					this.header = initHeaderPageCtrl(tableId);
				}
			}
			if(this.options.paginal.footer || this.options.paginal.footer =="true"){
				if(!this.footer){
					this.footer = initFooterPageCtrl(tableId);
				}
			}	
		}
		
		if (this.options.appendTool) {
			if(typeof this.options.paginal == "boolean"){
				if(this.options.paginal){
					jQuery('#' + tableId + '-header-appendTool').show();
					jQuery('#' + tableId + '-footer-appendTool').show();
				}
			}else if(typeof this.options.paginal == "object"){
				if(this.options.paginal.header || this.options.paginal.header =="true"){
					jQuery('#' + tableId + '-header-appendTool').show();
				}
				if(this.options.paginal.footer || this.options.paginal.footer =="true"){
					jQuery('#' + tableId + '-footer-appendTool').show();
				}	
			}
		}
		if(!this.options.hasSizeCtrl){
		  jQuery('#' + tableId + '-header-pagesize').hide();
		  jQuery('#' + tableId + '-footer-pagesize').hide();
		  }
		if(this.options.hasGoto)
		{
		   jQuery('#' + tableId + '-header-page').hide();
		   jQuery('#' + tableId + '-footer-page').hide();
		}else{
		   jQuery('#' + tableId + '-header-go').hide();
		   jQuery('#' + tableId + '-footer-go').hide();
		} 
		if(this.options.isMassive) {
			jQuery('#' + tableId + '-header-page').hide();
			jQuery('#' + tableId + '-footer-page').hide();
			jQuery('#' + tableId + '-header-go').hide();
			jQuery('#' + tableId + '-footer-go').hide();
			jQuery('#' + tableId + '-header-first').hide();
			jQuery('#' + tableId + '-header-last').hide();
			jQuery('#' + tableId + '-footer-first').hide();
			jQuery('#' + tableId + '-footer-last').hide();
			jQuery('#' + tableId + '-header-count-text').hide();
			jQuery('#' + tableId + '-footer-count-text').hide();
			if(!this.options.getTotalCountUrl) {
				jQuery('#' + tableId + '-header-total').hide();
				jQuery('#' + tableId + '-footer-total').hide();
			} else {
				jQuery('#' + tableId + '-header-total').show();
				jQuery('#' + tableId + '-footer-total').show();
			}
			jQuery('#' + tableId + '-header-count-text-massive').show();
			jQuery('#' + tableId + '-footer-count-text-massive').show();
		} else {
			jQuery('#' + tableId + '-header-count-text-massive').hide();
			jQuery('#' + tableId + '-footer-count-text-massive').hide();
			jQuery('#' + tableId + '-header-total').hide();
			jQuery('#' + tableId + '-footer-total').hide();
		}
		this.page.size = this.options.pageSize;
		this.page.SIZES = this.options.optionalPageSizes;
		this._getInitParamsByCookie();
		
	}
	if (this.options.sortable) {
		this.orders = {};
	}
	function initHeaderPageCtrl(tableId){
		var headerPageHtml =[];
		headerPageHtml.push('<table id="'+tableId+'-header" class="cmn_page cmn_table_bar"><tbody><tr>');
headerPageHtml.push('<td class="page-size cmn_page" id="'+tableId+'-header-pagesize">');
//headerPageHtml.push('每页显示:');
headerPageHtml.push('<select id="'+tableId+'-header-size"><option value="25">25</option><option value="100">100</option></select>');
headerPageHtml.push('<span id="'+tableId+'-header-count-text">');
headerPageHtml.push('/页，共<span id="'+tableId+'-header-count">0</span>条</span>');
headerPageHtml.push('<span id="'+tableId+'-header-count-text-massive" style="display: none;">第<span id="'+tableId+'-header-pagenum-massive">0</span>页</span>');
headerPageHtml.push('</td>');
headerPageHtml.push('<td></td>');
headerPageHtml.push('<td class="page cmn_page">');
if(table.options.paginationType=='2'){
	headerPageHtml.push('<span class="table_setpage"></span> ');
}
if(table.options.paginationType=='1'){
	headerPageHtml.push('<a id="'+tableId+'-header-first" title="首页" class="first btn disabled" href="javascript:void(0)">首页</a>');
	headerPageHtml.push('<a id="'+tableId+'-header-prev" title="上一页" class="prev btn disabled" href="javascript:void(0)">上一页</a>');
	headerPageHtml.push('<select id="'+tableId+'-header-page" style="display: none;"></select>');
	headerPageHtml.push('<a id="'+tableId+'-header-next" title="下一页" class="next btn disabled" href="javascript:void(0)">下一页</a>');
	headerPageHtml.push('<a id="'+tableId+'-header-last" title="末页" class="last btn disabled" href="javascript:void(0)">末页</a>');
}
	
headerPageHtml.push('</td>');
headerPageHtml.push('<td class="goto cmn_page" id="'+tableId+'-header-go">');
    headerPageHtml.push('<span id="'+tableId+'-header-num">0/0</span>');
	headerPageHtml.push('页，转到');
	headerPageHtml.push('<input id="'+tableId+'-header-goto-text" class="goname text" type="text" size="3">');
	headerPageHtml.push('<a href="javascript:void(0)" id="'+tableId+'-header-goto" class="submit" title="跳转" style="+margin:0;"></a>');
headerPageHtml.push('</td>');
headerPageHtml.push('<td class="total cmn_page" id="'+tableId+'-header-total" style="display: none; ">');
	headerPageHtml.push('<a id="'+tableId+'-header-total-btn" title="总数" class="btn" href="javascript:void(0)">总数</a>');
	headerPageHtml.push('<span id="'+tableId+'-header-total-text"></span>');
headerPageHtml.push('</td>');
headerPageHtml.push('<td id="'+tableId+'-header-appendTool" class="cmn-table-append-tool" style="display:none;">');
headerPageHtml.push('</td>');
headerPageHtml.push('</tr>');
headerPageHtml.push('</tbody></table>');

		jQuery('#'+tableId).before(jQuery(headerPageHtml.join(" ")));
		return jQuery('#' + tableId + '-header')[0];
	}
	
	
	function initFooterPageCtrl(tableId){
		var footerPageHtml =[];
		footerPageHtml.push('<table id="'+tableId+'-footer" class="cmn_page cmn_table_bar"><tbody><tr>');
footerPageHtml.push('<td class="page-size cmn_page" id="'+tableId+'-footer-pagesize">');
//footerPageHtml.push('每页显示:');
footerPageHtml.push('<select id="'+tableId+'-footer-size"><option value="25">25</option><option value="100">100</option></select>');
footerPageHtml.push('<span id="'+tableId+'-footer-count-text">');
footerPageHtml.push('/页，共<span id="'+tableId+'-footer-count">0</span>条</span>');
footerPageHtml.push('<span id="'+tableId+'-footer-count-text-massive" style="display: none;">第<span id="'+tableId+'-footer-pagenum-massive">0</span>页</span>');
footerPageHtml.push('</td>');
footerPageHtml.push('<td></td>');
footerPageHtml.push('<td class="page cmn_page">');

if(table.options.paginationType=='2'){	
	footerPageHtml.push('<span class="table_setpage"></span> ');
}
if(table.options.paginationType=='1'){
	footerPageHtml.push('<a id="'+tableId+'-footer-first" title="首页" class="first btn disabled" href="javascript:void(0)">首页</a>');
	footerPageHtml.push('<a id="'+tableId+'-footer-prev" title="上一页" class="prev btn disabled" href="javascript:void(0)">上一页</a>');
	footerPageHtml.push('<select id="'+tableId+'-footer-page" style="display: none;"></select>');
	footerPageHtml.push('<a id="'+tableId+'-footer-next" title="下一页" class="next btn disabled" href="javascript:void(0)">下一页</a>');
	footerPageHtml.push('<a id="'+tableId+'-footer-last" title="末页" class="last btn disabled" href="javascript:void(0)">末页</a>');
}
	
footerPageHtml.push('</td>');
footerPageHtml.push('<td class="goto cmn_page" id="'+tableId+'-footer-go">');
    footerPageHtml.push('<span id="'+tableId+'-footer-num">0/0</span>');
	footerPageHtml.push('页，转到');
	footerPageHtml.push('<input id="'+tableId+'-footer-goto-text" class="goname text" type="text" size="3">');
	footerPageHtml.push('<a href="javascript:void(0)" id="'+tableId+'-footer-goto" class="submit" title="跳转" style="+margin:0;"></a>');
footerPageHtml.push('</td>');
footerPageHtml.push('<td class="total cmn_page" id="'+tableId+'-footer-total" style="display: none; ">');
	footerPageHtml.push('<a id="'+tableId+'-footer-total-btn" title="总数" class="btn" href="javascript:void(0)">总数</a>');
	footerPageHtml.push('<span id="'+tableId+'-footer-total-text"></span>');
footerPageHtml.push('</td>');
footerPageHtml.push('<td id="'+tableId+'-footer-appendTool" class="cmn-table-append-tool" style="display:none;">');
footerPageHtml.push('</td>');
footerPageHtml.push('</tr>');
footerPageHtml.push('</tbody></table>');

		jQuery('#'+tableId).after(footerPageHtml.join(" "));
		return jQuery('#' + tableId + '-footer')[0];
	}
	

	this.load();
	//this._initPage();
};

UI.Table.prototype = {
	/**
	 * @param options {'paginal':[boolean],'pageSize':[int],'sortable':[boolean],'optionalPageSizes':[optional page size array], 'briefError':[boolean]}
	 */
	_initPage: function(){
		
		if(typeof this.options.paginal == "boolean" && this.options.paginal){
			this._fillBar(this.header);
			this._fillAppendTools(this.header);
			this._fillBar(this.footer);
			this._fillAppendTools(this.footer);	
		}else if(typeof this.options.paginal == "object"){
			if(this.options.paginal.header || this.options.paginal.header =="true"){
				this._fillBar(this.header);
				this._fillAppendTools(this.header);
			}
			if(this.options.paginal.footer || this.options.paginal.footer =="true"){
				this._fillBar(this.footer);
				this._fillAppendTools(this.footer);
			}	
		}
		
	
	},
	_getInitParamsByCookie: function() {
		if(this.options.cookieId) {
			var storeValue = jQuery.cookie(this.options.cookieId);
			if(storeValue) {
				var pageSizeValues = /pageSize:(\d+)/.exec(storeValue);
				if(pageSizeValues) {
					var pageSize = parseInt(pageSizeValues[1], 10);
					if(pageSize && this.page.SIZES.toString().indexOf(pageSize) != -1) {this.page.size = pageSize;}
				}
				var pageValues = /page:(\d+)/.exec(storeValue);
				if(pageValues && this.options.cachePage){
					var page = parseInt(pageValues[1], 10);
					if(page) {
						this.page.num = page;
					}
				}
			}
		}
	},
	_storeParamsByCookie: function() {
		if(this.options.cookieId) {
			var values = [];
			if(this.page.size){values.push("pageSize:" + this.page.size);}
			if(this.options.cachePage && this.page.num){
				values.push("page:"+this.page.num);
			}
			var storeValue = values.join("_");
			if(storeValue){	jQuery.cookie(this.options.cookieId, storeValue, {expires:60,path: "/"});}
		}
	},
	/**
	 * @param params hash of {'column', 'order', 'pageNumber', 'pageSize'}
	 */
	load: function() {
		var params = {};
		if (this.options.sortable && this.currentColumn != null) {
			params.column = this.currentColumn;
			if (this.orders[this.currentColumn] != null) {
				params.order = this.orders[this.currentColumn];
			}
		}
		if (this.options.paginal) {
			params.pageNumber = this.page.num;
			params.pageSize = this.page.size;
		}
		params.isMassive = this.options.isMassive;
		params.text = this.options.texts['text.loading'];
		var pars = '';
		for (var par in params) {
			if (params[par] == null) continue;
			if (pars != '') pars += '&';
			pars += par + '=' + params[par];
		}
		var url = this.url;
		var failure = false;
		var table = this;
		var dataType = (table.options.dataType && table.options.dataType.toLowerCase()=='html')?'html':'xml';
		
		if(!url || jQuery.trim(url).length<=0) return;
		
		jQuery.ajax({
			url:url,
			data:params,
			type:'POST',
			dataType:dataType,
			beforeSend:function(){
				table._cleanTable();

				var t_loadding=table.table.insertRow(-1);
				var td = document.createElement('td');
				td.align="center";
				td.innerHTML='<div class="load_con anywhere" style="position:relative;top:-1px"><span>'+params.text+'</span> </div>';
				if(t_loadding){
					t_loadding.appendChild(td);
				};
			},
			error:function(){
				var txt = 'Resource not found';
				if(!table.options.briefError) {
					txt += ':' + url;
				}
				failure = true;
			},
			complete:function(){
				if (failure) return;
			},
			success:function(data,textStatus, xhr){
				/*针对ie10下iframe横向滚动条*/
				var isIE10 = jQuery.browser.msie&&jQuery.browser.version=='10.0';
				if(isIE10){
					jQuery(table.table).parents("body").css("overflow-y","scroll");					
				};
				var d_total = parseInt(jQuery(data).attr('total'));
				if (table.page != null && d_total != null) {
					table.page.total = d_total;
				}
				//init Pagination
				if(!table._isinitpage){
					table._initPage();
					table._isinitpage = true;
				}

				try {
					if(table.options.dataType && table.options.dataType.toLowerCase()=='html'){
						table._showHTML(data);
					}
					else{
						table._show(data);
					}
					
					UI.table_hover();
					if (table.options.paginal) {
						if(typeof table.options.paginal == "boolean"){
							table._setPageButtons(table.header);
							table._setPageButtons(table.footer);
						}else if(typeof table.options.paginal == "object"){
							if(table.options.paginal.header || table.options.paginal.header =="true"){
								table._setPageButtons(table.header);
							}
							if(table.options.paginal.footer || table.options.paginal.footer =="true"){
								table._setPageButtons(table.footer);
							}	
						}
					}
					
					/*针对ie6下iframe横向滚动条*/
					function ie6IframeHScrollBar(){
						var isIE6 = jQuery.browser.msie&&jQuery.browser.version=='6.0';
						if(isIE6){
							jQuery(table.table).parents("html").css("overflow-y","auto");
							var iframeClientH = jQuery(top.window).height()-85;
							var iframeInnerBodyHeight = jQuery(document.body).height();
							if(iframeClientH < iframeInnerBodyHeight){
								jQuery(table.table).parents("html").css("overflow-y","scroll");	
							}
						};	
					}
					ie6IframeHScrollBar();
					
					/*针对ie10下iframe横向滚动条*/
					if(isIE10){
						window.setTimeout(function(){
							jQuery(table.table).parents("body").css("overflow-y","auto");		
						},100);
					};
					
				}
				catch (e) {
					//alert('show table error:\n' + e + '\n with xml:\n' + xhr.responseText);
				}

				//调用回调函数 by guoqing 20140729
				var callback = table.options.callback;
				if(callback && jQuery.isFunction(callback)){
					callback();
				}
			}
		})
		
	},
	changePageSize: function(size) {
		this.page.size = parseInt(size);
		this.page.num = ((this.page.total>0) ? 1 : 0);
		this.load();
		this._storeParamsByCookie();
		
		var _pageSize=this.page.size;
		jQuery('.page-size',jQuery(this.table).parent()).find('select').each(function(){
			var options = this.options;
			for(var i=0;i<options.length;i++){
				if (options[i].value == _pageSize) {
					options[i].selected = true;
				}
			}
		})
	},
	_fillPageCountInBar: function(bar) {
		if(this.options.isMassive) {
			jQuery('#' + bar.id + '-pagenum-massive')[0].innerHTML = this.page.total>0?this.page.num:0;
		} else {
			var page = parseInt((this.page.total + this.page.size - 1) / this.page.size);
			jQuery('#' + bar.id + '-count')[0].innerHTML = this.page.total;
			jQuery('#' + bar.id + '-num')[0].innerHTML = (this.page.total>0?this.page.num :0)+'/'+ page ;
			if(!this.options.hasGoto && jQuery('#' + bar.id + '-page').length>0) {
				var select = jQuery('#' + bar.id + '-page')[0];
				var options = select.options;
				select.innerHTML="";
				for (var i=1; i<= page; i++ ) {
					var option = new Option(i+'/'+page,i);
					options[options.length] = option; 
					if (i == this.page.num) {
						option.selected = true;
					}
				}
				var table = this;
				select.onchange =  function() {
					table._goto(jQuery(select).find("option:selected").val());
				}
			}
		}
	},
	_fillBar: function(bar) {
		if(this.options.hasSizeCtrl){
			var select = jQuery('#' + bar.id + '-size')[0];
			var options = select.options;
			
			//清空select框
			select.innerHTML="";
			// init
			for (var i=0; i<this.page.SIZES.length; i++ ) {
				var option = new Option(this.page.SIZES[i],this.page.SIZES[i]);
				options[options.length] = option;//options[xx].selected = true;
				if (this.page.SIZES[i] == this.page.size) {
					option.selected = true;
				}
			}
			var table = this;
			select.onchange = function() {
				table._isinitpage = false;
				table.changePageSize(jQuery(select).find("option:selected").val());
			}		
		}
		  
		// fill buttons
		var table = this;
		if(this.options.paginationType=='1'){
			jQuery('#' + bar.id + '-first')[0].onclick = function() {
				table._goFirst(this,bar.id);
			};
			jQuery('#' + bar.id + '-next')[0].onclick = function() {
				table._goNext(this,bar.id);
			};
			jQuery('#' + bar.id + '-last')[0].onclick = function() {
				table._goLast(this,bar.id);
			};
			jQuery('#' + bar.id + '-prev')[0].onclick = function() {
				table._goPrev(this,bar.id);
			};
		}
		
		if(this.options.paginationType=='2'){		
			//beginPage,total,pageSize,pageCurrent,callback
			var opt={
				selectedPage:1,
				total:table._getLastNum(),
				pageLength:7,
				showLocation:4,
				callback:function(p){
					var bar_id = bar.id;
					bar_id=bar_id.indexOf('footer')>-1?bar_id.replace('footer','header'):bar_id.replace('header','footer');
					jQuery('#'+bar_id).find('.table_setpage').pagination('goto',p);
					table._goto(p);
				}
			};
			//jQuery(bar).find('.table_setpage').empty();
			jQuery(bar).find('.table_setpage').pagination(opt);
		}
		
		// fill goto button
		var f = function() {
			var number = jQuery('#' + bar.id + '-goto-text').val();
			if (isNaN(number)) {
				jQuery('#' + bar.id + '-goto-text')[0].select();
				return;
			}
			var page = parseInt(number, 10);
			if (number!="" && table._goto(page) && jQuery(table.header).find('.table_setpage').pagination('goto',page)  && jQuery(table.footer).find('.table_setpage').pagination('goto',page) ) {
				jQuery('#' + bar.id + '-goto-text')[0].select();
				return;
			}
		};

		jQuery('#' + bar.id + '-goto')[0].onclick = f;
		jQuery('#' + bar.id + '-goto-text').onkeydown = function(evt) {
			evt = evt || window.event;
			if (evt.keyCode == 13/* Event.KEY_RETURN*/) {
				f();
			}
		};
		
		table._bindGetTotalCountEvent(jQuery('#' + bar.id + '-total-btn')[0]);
	},
	_fillAppendTools : function(bar){
		if(this.options.appendTool){
			jQuery('#' + bar.id + '-appendTool').append(this.options.appendTool);	
		}	
	},
	_setPageButtons: function(bar) { 
		if (this.page.num > 1) {
			this._enablePageButton(document.getElementById(bar.id + '-first'));
			this._enablePageButton(document.getElementById(bar.id + '-prev'));
		}
		else {
			this._disablePageButton(document.getElementById(bar.id + '-first'));
			this._disablePageButton(document.getElementById(bar.id + '-prev'));
		}
		var lastNum = this._getLastNum();
		if (this.page.num >= lastNum) {
			this._disablePageButton(document.getElementById(bar.id + '-last'));
			this._disablePageButton(document.getElementById(bar.id + '-next'));
		}
		else {
			this._enablePageButton(document.getElementById(bar.id + '-last'));
			this._enablePageButton(document.getElementById(bar.id + '-next'));
		}
		
		if(this.page.total>=1){
			this._enablePageButton(document.getElementById(bar.id + '-total-btn'));
		}
		else{
			this._disablePageButton(document.getElementById(bar.id + '-total-btn'));
		}
	},
	_getLastNum: function() {
		return parseInt((this.page.total + this.page.size - 1) / this.page.size);
	},
	_enablePageButton: function(button) {
		jQuery(button).removeClass('disabled');
	},
	_disablePageButton: function(button) {
		jQuery(button).addClass('disabled');
	},
	_goto: function(page) {
		if (page <= 0 || page > this._getLastNum()) {
			return false;
		}
		this.page.num = page;
		this.load();
		this._storeParamsByCookie();
		return true;
	},
	_goFirst: function(obj,barid) {
		if(jQuery(obj).hasClass('disabled')) return;
		
		this.page.num = 1;
		this.load();
		this._storeParamsByCookie();
	},
	_goNext: function(obj,barid) {
		if(jQuery(obj).hasClass('disabled')) return;
		
	    page = parseInt((this.page.total + this.page.size - 1) / this.page.size);
		if(this.page.num ++ == page) {
			this.page.num --;
			return ;
		}
		this.load();
		this._storeParamsByCookie();
	},
	_goPrev: function(obj,barid) {
		if(jQuery(obj).hasClass('disabled')) return;
		
		if (this.page.num -- == 1 ) {
			this.page.num ++ ;
			return;
		}
		this.load();
		this._storeParamsByCookie();
	},
	_goLast: function(obj,barid) {
		if(jQuery(obj).hasClass('disabled')) return;
		
		this.page.num = parseInt((this.page.total + this.page.size - 1) / this.page.size);
		this.load();
		this._storeParamsByCookie();
	},
	//parse xml data
	_show: function(xmldom) {
		this._cleanTable();
		var total = parseInt(xmldom.documentElement.getAttribute('total'));
		if (this.page != null && total != null) {
			this.page.total = total;
		}

		var thead = this.table.createTHead();
		
		var xmlhead = xmldom.getElementsByTagName('thead')[0];
		var xmlHeadRows = xmlhead.getElementsByTagName('tr');
		for(var h=0;h< xmlHeadRows.length;h++){
			var xmlheadrow = xmlHeadRows[h];
			
			var theadRow = thead.insertRow(-1);
			theadRow.className = 'first_title';
			var expandNodes = xmlheadrow.getElementsByTagName('expand');
			for (var i=0; i<expandNodes.length; i++) {
				var expandNode = expandNodes[i];
				var th = document.createElement('th');
				if (expandNode.getAttribute('width') != null) {
					th.width = expandNode.getAttribute('width');
				}
				theadRow.appendChild(th);
			}
			var cellNodes = xmlheadrow.getElementsByTagName('th');
			for (var i=0; i<cellNodes.length; i++) {
				var xmlcell = cellNodes[i];
				var th = document.createElement('th');
				if (xmlcell.getAttribute('width') != null) {
					th.width = xmlcell.getAttribute('width');
				}
				var col = xmlcell.getAttribute('column');
				if (col != null) th.setAttribute('column',col);
				var colspan = xmlcell.getAttribute('colspan');
				if (colspan != null) {
					if(UI.Browser.ie) {
						th.setAttribute('colSpan',colspan);
					} else {
						th.setAttribute('colspan',colspan);
					}
				}
				var isSortable = xmlcell.getAttribute('isSortable');
				if(!this.currentColumn){
					if(col != null && isSortable == "true"){
						this.currentColumn = col;	
					}	
				}
				th.setAttribute('isSortable',isSortable == null ? false : isSortable);
				th.innerHTML = xmlcell.childNodes[0]?xmlcell.childNodes[0].nodeValue:"";
				theadRow.appendChild(th);

				if (isSortable != 'true') continue;

				if (this.options.sortable) { 
					// init order
					var order = this.orders[col];
					if (order == null) {
						if(this.options.isMassive) {
							if (xmlcell.getAttribute('massiveOrder') != null) {
								order = xmlcell.getAttribute('massiveOrder');
							} else {
								order = 'desc';
							}
						} else {
							if (xmlcell.getAttribute('order') != null) {
								order = xmlcell.getAttribute('order');
							}
							else {
								order = 'desc';
							}
						}
						// save sort status
						this.orders[col] = order;
					}
					if (col == this.currentColumn) {
						// add order flag
						th.innerHTML += '<img class="sort" src="' + this.options.images[order] + '" />';
					}
					// add sort function
					var _self=this;
					jQuery(th).click(function(evt){
						_self._sortColumn(evt,_self);
					})
					//th.onclick = this._sortColumn.bindAsEventListener(this);
					th.onmouseover = function() {
						this.className = 'light';
					}
					th.onmouseout = function() {
						this.className = '';
					}
		
				}
			}
		}
		
		
		var tbody = jQuery(this.table).find('tbody')[0];
		if(!tbody){
			tbody = document.createElement("tbody");
			this.table.appendChild(tbody);
		}
		
		var xmlbody = xmldom.getElementsByTagName('tbody')[0];
		var rowNodes = xmlbody.getElementsByTagName('tr');
		var length = rowNodes.length;
		if(this.options.isMassive) {
			if(rowNodes.length > this.page.size) {
				length = this.page.size;
				this.page.total = (this.page.num + 1) * this.page.size;
			} else {
				this.page.total = (this.page.num - 1) * this.page.size + length;
			}
		}
		if (this.options.paginal) {
			if(typeof this.options.paginal == "boolean"){
				this._fillPageCountInBar(this.header);
				this._fillPageCountInBar(this.footer);
			}else if(typeof this.options.paginal == "object"){
				if(this.options.paginal.header || this.options.paginal.header =="true"){
					this._fillPageCountInBar(this.header);
				}
				if(this.options.paginal.footer || this.options.paginal.footer =="true"){
					this._fillPageCountInBar(this.footer);
				}	
			}
		}
		if(length == 0) {
			var row = tbody.insertRow(-1);
			var cell = row.insertCell(-1);
			if(UI.Browser.ie) {
				cell.setAttribute('colSpan', cellNodes.length + expandNodes.length);
			} else {
				cell.setAttribute('colspan', cellNodes.length + expandNodes.length);
			}
			cell.innerHTML = '<div class="cmn_warning tip"><img class="ico" src="' + this.options.images['blank'] + '"> %s </div>'.replace('%s', this.options.texts['text.no.data'] || '无数据');
		} else {
			for (var i=0; i<length; i++) {
				var xmlrow = rowNodes[i];
				var row = tbody.insertRow(-1);
				if (i % 2 == 0) row.className = 'even';
				else row.className = 'odd';
				
				var lineStyle = xmlrow.getAttribute('lineStyle');
                if(lineStyle){
                    row.className += ' ' + lineStyle;
                }
				
				var expandNodes = xmlrow.getElementsByTagName('expand');
				for (var j=0; j<expandNodes.length; j++) {
					var node = this._createExpandNode(expandNodes[j]);
					var cell = row.insertCell(-1);
					var _self = this;
					(function(n){
						jQuery(n).click(function(evt){
							_self._onExpand.call(_self,evt,n);
						});
					}(node));
					cell.appendChild(node);
				}
				var cellNodes = xmlrow.getElementsByTagName('td');
				for (var j=0; j<cellNodes.length; j++) {
					var cell = row.insertCell(-1);
					if (jQuery(cellNodes[j]).attr('width')) {
						cell.width = jQuery(cellNodes[j]).attr('width');
					}
					var colspan = jQuery(cellNodes[j]).attr("colspan");
					jQuery(cell).attr('colspan', colspan);
					if (!cellNodes[j].hasChildNodes()) {
						continue;
					}
					var textNode = cellNodes[j].childNodes[0];
					if (textNode.nodeName == '#text' || textNode.nodeName == '#cdata-section') {
						var encode_attr = cellNodes[j].getAttribute('encoding');
						var text = textNode.nodeValue;
						if (encode_attr == 'base64') {
							text = UI.Encoder.utf8to16(UI.Base64.decode(text));
						}
						cell.innerHTML = text;
					}
					else {
						alert('invalid element:' + textNode);
						return;
					}
				}
			}
		}
		var script_nodes = xmldom.getElementsByTagName('script');
		if (!script_nodes || script_nodes.length == 0) return;
		
		var script_text = script_nodes[0].childNodes[0].nodeValue;
		eval(script_text);
	},
	//parse html data
	_showHTML: function(xmldom) {
		this._cleanTable();
		var total = parseInt(jQuery(xmldom).attr('total'));
		if (this.page != null && total != null) {
			this.page.total = total;
		}

		var thead = this.table.createTHead();

		var xmlhead = jQuery(xmldom).find('thead').first();
		var xmlHeadRows = xmlhead.find('tr');

		for(var h=0;h< xmlHeadRows.length;h++){
			var xmlheadrow = jQuery(xmlHeadRows[h]);

			var theadRow = thead.insertRow(-1);
			theadRow.className = 'first_title';
			
			var cellNodes = xmlheadrow.find('th');
			for (var i=0; i<cellNodes.length; i++) {
				var xmlcell = jQuery(cellNodes[i]);
				var th = document.createElement('th');
				if (xmlcell.attr('width') != null) {
					th.width = xmlcell.attr('width');
				}
				var col = xmlcell.attr('column');
				if (col != null) th.setAttribute('column',col);
				var colspan = xmlcell.attr('colspan');
				if (colspan != null) {
					if(UI.Browser.ie) {
						th.setAttribute('colSpan',colspan);
					} else {
						th.setAttribute('colspan',colspan);
					}
				}
				var isSortable = xmlcell.attr('isSortable');
				if(!this.currentColumn){
					if(col != null && isSortable == "true"){
						this.currentColumn = col;	
					}	
				}
				th.setAttribute('isSortable',isSortable == null ? false : isSortable);
				th.innerHTML = xmlcell.html().replace(/<\/?[A-Z]+.*?>/g, function (m) { return m.toLowerCase(); });
				theadRow.appendChild(th);

				if (isSortable != 'true') continue;

				if (this.options.sortable) { 
					// init order
					var order = this.orders[col];
					if (order == null) {
						if(this.options.isMassive) {
							if (xmlcell.attr('massiveOrder') != null) {
								order = xmlcell.attr('massiveOrder');
							} else {
								order = 'desc';
							}
						} else {
							if (xmlcell.attr('order') != null) {
								order = xmlcell.attr('order');
							}
							else {
								order = 'desc';
							}
						}
						// save sort status
						this.orders[col] = order;
					}
					if (col == this.currentColumn) {
						// add order flag
						th.innerHTML += '<img class="sort" src="' + this.options.images[order] + '" />';
					}
					// add sort function
					var _self=this;
					jQuery(th).click(function(evt){
						_self._sortColumn(evt,_self);
					})
					//th.onclick = this._sortColumn.bindAsEventListener(this);
					th.onmouseover = function() {
						this.className = 'light';
					}
					th.onmouseout = function() {
						this.className = '';
					}
		
				}
			}
		}

		var tbody = jQuery(this.table).find('tbody')[0];
		if(!tbody){
			tbody = document.createElement("tbody");
			this.table.appendChild(tbody);
		}
		
		var xmlbody = jQuery(xmldom).find('tbody').first();
		var rowNodes = xmlbody.children('tr');
		var length = rowNodes.length;
		if(this.options.isMassive) {
			if(rowNodes.length > this.page.size) {
				length = this.page.size;
				this.page.total = (this.page.num + 1) * this.page.size;
			} else {
				this.page.total = (this.page.num - 1) * this.page.size + length;
			}
		}
		if (this.options.paginal) {
			if(typeof this.options.paginal == "boolean"){
				this._fillPageCountInBar(this.header);
				this._fillPageCountInBar(this.footer);
			}else if(typeof this.options.paginal == "object"){
				if(this.options.paginal.header || this.options.paginal.header =="true"){
					this._fillPageCountInBar(this.header);
				}
				if(this.options.paginal.footer || this.options.paginal.footer =="true"){
					this._fillPageCountInBar(this.footer);
				}	
			}
		}
		if(length == 0) {
			var row = tbody.insertRow(-1);
			var cell = row.insertCell(-1);
			if(UI.Browser.ie) {
				cell.setAttribute('colSpan', cellNodes.length);
			} else {
				cell.setAttribute('colspan', cellNodes.length);
			}
			cell.innerHTML = '<div class="cmn_warning tip"><img class="ico" src="' + this.options.images['blank'] + '"> %s </div>'.replace('%s', this.options.texts['text.no.data'] || '无数据');
		} else {
			for (var i=0; i<length; i++) {
				var xmlrow = jQuery(rowNodes[i]);
				var row = tbody.insertRow(-1);
				if (i % 2 == 0) row.className = 'even';
				else row.className = 'odd';
				var lineStyle = xmlrow[0].getAttribute('lineStyle');
                if(lineStyle){
                    row.className += ' ' + lineStyle;
                };
				
				var expandNodes = xmlrow.children('td[url]');
				for (var j=0; j<expandNodes.length; j++) {
					var node = this._createExpandNode(expandNodes[j]);
					var cell = row.insertCell(-1);
					if (jQuery(expandNodes[j]).attr('width')) {
						cell.width = jQuery(expandNodes[j]).attr('width');
					}
					var colspan = jQuery(expandNodes[j]).attr("colspan");
					jQuery(cell).attr('colspan', colspan);
					var _self = this;
					(function(n){
						jQuery(n).click(function(evt){
							_self._onExpand.call(_self,evt,n);
						});
					}(node));
					cell.appendChild(node);
				}
				var cellNodes = xmlrow.children('td:not([url])');
				for (var j=0; j<cellNodes.length; j++) {
					var cell = row.insertCell(-1);
					var colspan = jQuery(cellNodes[j]).attr("colspan");
					jQuery(cell).attr('colspan', colspan);
					cell.innerHTML = jQuery(cellNodes[j]).html();
				}
			}
		}
		/*var script_nodes = jQuery(xmldom).find('script');
		if (!script_nodes || script_nodes.length == 0) return;
		
		var script_text = script_nodes[0].childNodes[0].nodeValue;
		eval(script_text);*/
		
		//modify by zgf 1.find找不到script 2.childNode有兼容性问题
		var script_nodes = jQuery(xmldom);
		if (!script_nodes || script_nodes.length == 0) return;
		for(var i=0;i<script_nodes.length;i++){
			var script_node = script_nodes[i];
			if(script_node && script_node.tagName && script_node.tagName.toLowerCase() == "script"){
				var script_text = script_node.innerHTML;
				eval(script_text);
		  	} 
		}
	},
	_createExpandNode: function(expand) {
		var url = jQuery(expand).attr('url');
		var img = document.createElement('img');
		img.setAttribute('url',url);
		img.src = this.options.images.expand;
		img.width = 16;
		img.height = 16;
		img.className = 'ico plus';
		return img;
	},
	_onExpand: function(event,img) {
		var url = img.getAttribute('url');
		var expand = jQuery(img).hasClass('plus');
		if (!expand) {
			img.className="ico plus";
			var next_row = img.parentNode.parentNode.nextSibling;
			if (next_row != null && !jQuery(next_row).hasClass('more')) {
				next_row = null;
			}
			if (next_row) {
				next_row.parentNode.removeChild(next_row);
			}
		}
		else {
			img.className="ico minus";
			var row = jQuery(img).parent().parent();
			var trow = jQuery('<tr class="more"></tr>');
			
			if(row.find('td').length > 1){
				trow.append(jQuery('<td></td><td colspan="'+(row.find('td').length -1)+'"></td>'))
			}
			else{
				trow.append(jQuery('<td></td>'))
			}
			var ttd = trow.find('td').last();

			ttd.html('Loading...');
			row.after(trow);
			
			jQuery(ttd).load(url);
		}
	},
	_sortColumn: function(evt) {
		if(this.options.isMassive) {
			var confirmText = this.options.texts['text.sort.confirm'] || '确认排序?';
			if(!confirm(confirmText)) {return;}
		}
		var target = document.all ? evt.srcElement : evt.target;
		if (target.nodeName != 'TH') target = target.parentNode;
		var tableId = target.parentNode.parentNode.parentNode.id;
		//var table = UI.tables[tableId];
		var col = target.getAttribute('column');
		var order = this.orders[col];
		order = (order == 'asc' ? 'desc' : 'asc');
		//table.currentColumn = col;
		this.currentColumn = col;
		this.orders[col] = order;
		//table.load({'column':col,'order':order});
		this.load({'column':col,'order':order});
	},
	_cleanTable: function() {
		jQuery(this.table).empty();
	},
	_bindGetTotalCountEvent: function(btn) {
		var that = this;
		btn.onclick = function() {
			if(jQuery(this).hasClass('disabled')) return;
			that._getTotalCount();
		};
	},
	_getTotalCount: function() {
		var that = this;
		var confirmText = this.options.texts['text.query.confirm'] || '确认查询?';
		if(!confirm(confirmText)) {return;}
		var disableGetTotalCountButton = function () {
			that._disablePageButton(document.getElementById(that.header.id + '-total-btn'));
			that._disablePageButton(document.getElementById(that.footer.id + '-total-btn'));
		};
		var enableGetTotalCountButton = function () {
			that._enablePageButton(document.getElementById(that.header.id + '-total-btn'));
			that._enablePageButton(document.getElementById(that.footer.id + '-total-btn'));
			that._bindGetTotalCountEvent(document.getElementById(that.header.id + '-total-btn'));
			that._bindGetTotalCountEvent(document.getElementById(that.footer.id + '-total-btn'));
		};
		
		jQuery.ajax({
			url:this.options.getTotalCountUrl,
			type:"POST",
			dataType:"json",
			beforeSend:function(){
				UI.loading(top, that.options.texts['text.loading'] || '数据加载中.....');
				disableGetTotalCountButton();
			},
			error:function(){
				enableGetTotalCountButton();
				UI.loading.hide()
			},
			success:function(rs){
				try {
					//var rs = xhr.responseText.evalJSON();
					if(rs && rs['code'] == 200) {
						jQuery('#' + that.header.id + '-total-text')[0].innerHTML = that.options.texts['total.text'].replace('%1', rs['result']);
						jQuery('#' + that.footer.id + '-total-text')[0].innerHTML = that.options.texts['total.text'].replace('%1', rs['result']);
					}
				}catch(e) {
				}
				enableGetTotalCountButton();
				UI.loading.hide();
				
			}
		});
	}
};


//author:zhaoweixiong@intra.nsfocus.com
//user:Pagination
//

(function($){
	if(typeof $ =='undefined'){
		return;
	}
	
	var create_pagination = function(pagination,options){
	
	var name = 'pagination_' + new Date().getTime()+ '_' + parseInt(Math.random()*100+1);
	window[name]={};
	var id,totalpage,pagesize,cpage,count,curcount,outstr,countShow; 
	//初始化
	cpage = options.selectedPage; //初始化从哪页开始
	totalpage = options.total; //总共的页数
	pagesize = options.pageLength-1; //初始化显示几页.
	var pageNum= options.showLocation; //当前页数在总体页数中的位置
	window[name].gotopage=function(page)
	{
		if(page<1||page>totalpage)
		{
			return false;
		}
		options.callback?options.callback(page): null;
		window[name].setpage(page);
	}
	
	window[name].setpage= function (cpage) 
	{
		var window_name = window[name]
		outstr = ""; 
			 if(totalpage<=pagesize+1){   
			 outstr = outstr + "<a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage("+(cpage-1)+")'> < </a>";     
			for (count=1;count<=totalpage;count++) 
			{    if(count!=cpage) 
				{ 
					 outstr = outstr + "<a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage("+count+")'>"+count+"</a>"; 
				}else{ 
					outstr = outstr + "<span class='current' >"+count+"</span>"; 
				} 
			} 
			outstr = outstr + "<a href='javascript:void(0)'" + " onclick='window["+'"'+name+'"'+"].gotopage("+(cpage+1)+")'> > </a>"; 
		} 
		if(totalpage>pagesize+1){        
			if(cpage<=totalpage-(pagesize-2)) 
			{      
			 outstr = outstr + "<a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage("+(cpage-1)+")'> < </a>"; 
			 count=1;
			 if(cpage>pageNum){
				 count++;
				 outstr+=" <a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage(1)'>1</a><span>..</span>";
				 }       
				for (;count<=pagesize;count++) 
				{   
				cpage>pageNum?countShow=(cpage-pageNum+count):countShow=count;
				
				 if(countShow!=cpage) 
					{ 
						outstr = outstr + "<a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage("+countShow+")'>"+countShow+"</a>"; 
					}else{ 
						 outstr = outstr + "<span class='current'>"+countShow+"</span>"; 
					} 
				
				} 
				//显示最后两个页码
				// outstr+=" <li><span>...</span></li><li><a href='javascript:void(0)' onclick='gotopage("+(totalpage-1)+")'>"+(totalpage-1)+"</a></li><li><a href='javascript:void(0)' onclick='gotopage("+totalpage+")'>"+totalpage+"</a></li>";
				 outstr+="<span>..</span><a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage("+totalpage+")'>"+totalpage+"</a>";		 		  
				 outstr = outstr + "<a href='javascript:void(0)'" + " onclick='window["+'"'+name+'"'+"].gotopage("+(cpage+1)+")'> > </a>"; 
			} 
			else{
				 outstr = outstr + "<a href='javascript:void(0)'" + " onclick='window["+'"'+name+'"'+"].gotopage("+(cpage-1)+")'> < </a>"; 
				  outstr+=" <a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage(1)'>1</a><span>..</span>";  
					count=2;
				for (;count<=pagesize+1;count++) 
				{  
				 cpage<(totalpage-(pagesize-pageNum)-1)?countShow=(cpage-pageNum+count):countShow=(totalpage-pagesize+count-1);
				if(countShow==2)
				{
					countShow=3;
					count++;
				}
				//countShow=totalpage-10+count;
				 if(countShow!=cpage) 
					{ 			
						outstr = outstr + "<a href='javascript:void(0)'" + " onclick='window["+'"'+name+'"'+"].gotopage("+countShow+")'>"+countShow+"</a>"; 
					}else{ 
					   outstr = outstr + "<span class='current'>"+countShow+"</span>"; 
					} 		
				} 
				if(cpage<(totalpage-(pagesize-pageNum)-1))
				{
					 outstr+="<span>..</span><a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage("+totalpage+")'>"+totalpage+"</a>";	
					}
				outstr = outstr + "<a href='javascript:void(0)' " + " onclick='window["+'"'+name+'"'+"].gotopage("+(cpage+1)+")'> > </a>"; 
				}   
		}  
			
		pagination.innerHTML = "<div id='"+pagination.id+"'>" + outstr + "<\/div>"; 
		outstr = ""; 
		}
		window[name].setpage(cpage);
		return window[name];
	}
	
	 var defaults = {
		selectedPage: 1,
		total: 100,
		pageLength: 7,
		showLocation: 4,
		callback:function(){}
	};

	$.fn.Pagination=function(options,param){
		if (typeof options == 'string') {
			return $.fn.Pagination.methods[options](this, param);
		}
		if((typeof options == 'object')&&param==undefined)
		{
			$.fn.Pagination.methods['init'](this, options);
		}
		
	};
	$.fn.Pagination.methods = {
		init: function(jq,options){
			//beginPage,total,pageSize,pageCurrent,callback
			options = $.extend({},defaults, options);
			new create_pagination(jq[0],options);  
		}
	};
})(window.jQuery);
// JavaScript Document
//author:zhaoweixiong@intra.nsfocus.com
//date: 2013-06-04
//use: show the progress by the dynamic ring


var progressRing =function(o) { 
	var defaults={
		bgid: 'bg',
		ringclass: 'ring',
		inner: 30,
		outer: 36,
		limit: 0.6,
		timeinterval: 100
		};
	 
	 var o = jQuery.extend({}, defaults, o || {});
	 
     var Self = this;
	 var bgid=o.bgid;
	 var a=jQuery('.'+o.ringclass);
	 var color=a.find('.color');
	 var txt_num=a.find('.txt_num');
	 var r_inner=o.inner;
	 var r_outer=o.outer;
	 var limit=o.limit;
	 var timeinterval=o.timeinterval;
	 
    this.paper=null, 
 	this.timer=null,
	this.currentValue=0;
    this.init=function(value){
        //初始化Raphael画布 
		var value=value?value:0;
		value = value>=1? 1:value;
        this.paper = Raphael(bgid, r_outer*2, r_outer*2); 
 
		this.draw(-1);
		
		a.find('.txt').css('height',r_outer*2+'px');
		a.find('.txt').css('width',r_outer*2+'px');
		a.find('.txt').css('margin-top',-r_outer*2+'px');
		//a.find('.txt').css('line-height',r_outer*2-r_inner+8+'px');
		a.find('.txt').css('line-height',r_outer*2-r_inner-2+'px');
		a.find('.txt_num').css('right',r_outer-10+'px');
		a.find('.txt_num').css('top',r_inner-r_outer+'px');
		a.find('.txt_percent').css('left',r_outer+10+'px');
		a.find('.txt_percent').css('top',r_inner-r_outer+'px')
		
		
		this.currentValue=value;
		this.draw(value);
	};
	this.clean=function(){
		clearInterval(Self.timer);	
		this.draw(-1);	
		this.draw(0);
		this.currentValue=0;
		color.css('color','#9e9fa3');
	};
	this.pause=function(){
		clearInterval(Self.timer);	
	};
	this.goon=function(maxValue){
		clearInterval(Self.timer);	
		this.show(Self.currentValue,maxValue);	
	};
	this.draw=function(percent){
 		var col= percent >= limit ?'#fc5252':'#b9e672';
		if(percent==-1) {
			col='#eeeeee';
			percent=1;
		}
        //进度比例，0到1，在本例中我们画65% 
        //需要注意，下面的算法不支持画100%，要按99.99%来画 
        // var percent = 0.61,
	    color.css('color',col); 
        drawPercent = percent >= 1 ? 0.9999 : percent; 
 
        //开始计算各点的位置
		//起始点为p1，x,y表示与画布左上点的距离
        //r1是内圆半径，r2是外圆半径 
        var r1 = r_inner, r2 = r_outer, PI = Math.PI, 
            p1 = { 
                x:r_outer,  
                y:r_outer*2
            }, 
            p4 = { 
                x:p1.x, 
                y:p1.y - r2 + r1 
            }, 
            p2 = {  
                x:p1.x + r2 * Math.sin(2 * PI * (1 - drawPercent)), 
                y:p1.y - r2 + r2 * Math.cos(2 * PI * (1 - drawPercent)) 
            }, 
            p3 = { 
                x:p4.x + r1 * Math.sin(2 * PI * (1 - drawPercent)), 
                y:p4.y - r1 + r1 * Math.cos(2 * PI * (1 - drawPercent)) 
            }, 
            path = [ 
                'M', p1.x, ' ', p1.y, 
                'A', r2, ' ', r2, ' 0 ', percent > 0.5 ? 1 : 0, ' 1 ', p2.x, ' ', p2.y, 
                'L', p3.x, ' ', p3.y, 
                'A', r1, ' ', r1, ' 0 ', percent > 0.5 ? 1 : 0, ' 0 ', p4.x, ' ', p4.y, 
                'Z' 
            ].join(''); 
			
 
        //用path方法画图形，由两段圆弧和两条直线组成，画弧线的算法见后
        if(!this._path){
        	//背景色圆环
        	this.paper.path(path) 
            .attr({"stroke-width":0.5, "stroke":"#ffffff", "fill":"90-"+col+"-"+col+""}); 
            //进度条圆环
        	this._path = this.paper.path(path) 
            //填充渐变色，从#3f0b3f到#ff66ff 
            .attr({"stroke-width":0.5, "stroke":"#ffffff", "fill":"90-"+col+"-"+col+""}); 
        }
 		else{
 			this._path.attr({path:path}).attr({"stroke-width":0.5, "stroke":"#ffffff", "fill":"90-"+col+"-"+col+""}); 
 		}
        //显示进度文字 
       txt_num.text(Math.round(percent * 100)); 
    };
	
	
	this.show=function(value,maxValue){
		var newValue=value?value:0;
		var maxValue=maxValue?maxValue:1;
		
		if(newValue > maxValue+0.01) {
				
		}
		else{
			color.css('color','#b9e672');
		
			Self.timer=setInterval(function(){
 				newValue=newValue+0.01;
				Self.currentValue=newValue;
					if(newValue>limit) 
					{	
						color.css('color','#fc5252');
					}
					if(newValue > maxValue+0.01) {
							clearInterval(Self.timer);
					}
					else
					{
						Self.draw(newValue);  		
					}
		 	},timeinterval);
		 }
	}
}; 



/**
author:zhaoweixiong@intra.nsfocus.com
datetime:2013-01-25
using:the tabs can be deleted,renamed. Users  can add a new tab,
	  users can turn right or left when tabs more
**/
(function($){
	 /**
	 * get the max tabs scroll width(scope)
	 */
	if(typeof $ =='undefined'){
		return;
	}
	
	function getMaxScrollWidth(container) {
		var header = $(container).children('div.tabs-header');
		var tabsWidth = 0;	// all tabs width
		$('ul.tabs li', header).each(function(){
			if($(this).css('display')!='none'){
				tabsWidth += $(this).outerWidth(true);
			}
		});
		var wrapWidth = header.children('div.tabs-wrap').width();
		var padding = parseInt(header.find('ul.tabs').css('padding-left'));
		
		return tabsWidth - wrapWidth + padding;
	}
	//判断是否出现左右移动块
	var mark=0;
	function setScrollers(container) {
		var opts = $.data(container, 'options');
		var header = $(container).children('div.tabs-header');
		var tabTool = header.children('div.tabs-tool');
		var sLeft = header.children('div.tabs-scroller-left');
		var sRight = header.children('div.tabs-scroller-right');
		var wrap = header.children('div.tabs-wrap');
		var tool=header.children('div.tools');
		
		var tabsWidth = 0;
		$('ul.tabs li', header).each(function(){
			if($(this).css('display')!='none'){
				tabsWidth += $(this).outerWidth(true);
			}
		});
		var cWidth = header.width() -tabTool.outerWidth()-tool.outerWidth();
		//使panel的高度和宽度自适应
		var body_height = window && window.innerHeight 
               ? window.innerHeight
               : document.documentElement.offsetHeight; 
		var container_top=$(container)[0].offsetTop;
		var tabs_panel=$(container).children('div.tabs-panels');
		
		//是否动态设置iframe的高度
		if(!opts.setIframe.setWidth && !opts.setIframe.setHeight){
			//tab控件在对话框中时，减去按钮区域高度
			var _btnHeight = 0;
			if(opts.container && opts.container.find('div.button')[0]){
				//8 is padding width
				_btnHeight = UI.height(opts.container.find('div.button')[0]) + 8;
			}
			if(!UI.Browser.ie6){
				tabs_panel.css({height:body_height-container_top-header.outerHeight(true)- 
					parseInt(tabs_panel.css("padding-top")) -
					parseInt($("body").css("margin-bottom"))-
					parseInt($("body").css("padding-bottom"))-_btnHeight});	
			}else{
				tabs_panel.css({height:body_height-container_top-header.outerHeight(true)- 
					parseInt(tabs_panel.css("padding-top")) -
					parseInt($("body").css("margin-top"))-
					parseInt($("body").css("margin-bottom"))-
					parseInt($("body").css("padding-bottom"))-_btnHeight});	
			}
		}
		
		
		if (tabsWidth > cWidth) {
			sLeft.show();
			sRight.css('right',tool.outerWidth());
			sRight.show();
			tool.css('right', 0);
			if(mark==0)
			{
				tabTool.css('left', 'auto');
				tabTool.css('right',sRight.outerWidth()+tool.outerWidth())
				mark=1;
			}
			wrap.css({
				marginLeft: sLeft.outerWidth(),
				marginRight: sRight.outerWidth() + tabTool.outerWidth(),
				left: 0,
				width: cWidth - sLeft.outerWidth() - sRight.outerWidth()
			});
		} else {
			sLeft.hide();
			sRight.hide();
			var l=0;
			var child=wrap.children('.tabs')[0].lastChild;
			while($(child).css('display')=='none'){
				child=child.previousSibling;
			}
			if(child){
				l=child.offsetLeft+child.offsetWidth;
			}
			tool.css('right',0);
			tabTool.css('left',l);
			tabTool.css('right','auto');
			mark=0;
			wrap.css({
				marginLeft:0,
				marginRight:tabTool.outerWidth(),
				left: 0,
				width: cWidth
			});
			wrap.scrollLeft(0);
		}
	}
	//对移动块点击事件进行绑定
	function setProperties(container){
		//var opts = $.data(container, 'tabs').options;
		var opts={scrollIncrement:100,scrollDuration:100};
		var header = $(container).children('div.tabs-header');
		var panels = $(container).children('div.tabs-panels');
		
		$('.tabs-scroller-left', header).unbind('.tabs').bind('click.tabs', function(){
			var wrap = $('.tabs-wrap', header);
			var pos = wrap.scrollLeft() - opts.scrollIncrement;
			wrap.animate({scrollLeft:pos}, opts.scrollIncrement);
		});
		
		$('.tabs-scroller-right', header).unbind('.tabs').bind('click.tabs', function(){
			var wrap = $('.tabs-wrap', header);
			var pos = Math.min(
					wrap.scrollLeft() + opts.scrollIncrement,
					getMaxScrollWidth(container)
			);
			wrap.animate({scrollLeft:pos}, opts.scrollDuration);
		});
	}
	
	function _rename(container){
		$(container).children('div.tabs-header').find('.tabs li').each(function(){
				if($($(this).find('.tabs-input')).css('display')!='none'){
					$($(this).find('.tabs-input')).hide();
					$($(this).find('.tabs-title')).show();
					//alert($(this).find('.tabs-title')[0].textContent)
					if($(this).find('.tabs-input')[0].value=='') 
					{
						$(this).find('.tabs-input')[0].value=	$(this).find('.tabs-title')[0].textContent||$(this).find('.tabs-title')[0].innerText;
						return;
					}
					for(var i=0;i<tabIframe.length;i++)
					  {
						  if($(this).find('.tabs-title')[0].textContent==tabIframe[i].title||$(this).find('.tabs-title')[0].innerText==tabIframe[i].title)
						  {
							 tabIframe[i].title=$(this).find('.tabs-input')[0].value;
							  }
					  }
					if($(this).find('.tabs-title')[0].textContent)
					{
						$(this).find('.tabs-title')[0].textContent=$(this).find('.tabs-input')[0].value;
						}
					else{
						$(this).find('.tabs-title')[0].innerText=$(this).find('.tabs-input')[0].value;
					}
					setScrollers(container);
				}
			})	
	}
	
	function _onDocumentClicked(container,event){
		var e=event?event:window.event;
		var a=e.target||e.srcElement;
		if(a.tagName=='BODY'||a.parentElement&&a.parentElement.parentElement&&a.parentElement.parentElement.className!='fiter_dropmenu'&&a.parentElement.parentElement.parentElement&&a.parentElement.parentElement.parentElement.className!='fiter_dropmenu'&&a.className!='filter')
		{
			$($(container).children('div.fiter_dropmenu')).hide();
		}
		if(a.className!='tabs_drop')
		{
			$($(container).children('div.item_dropmenu')).hide();
		}
		if(a.className!='tabs-input'&&a.className!='rename'){
			_rename(container);
		}
	}

	function _itemDropMenu(container){
		if($(container).children('div.tabs-header').find('.tabs li').last()[0]==$($(container).children('div.tabs-header').find('.tabs_drop').last()).parent()[0]){
			$($(container).children('div.tabs-header').find('.tabs_drop').last()).bind('click',function(e){
				if($($(container).children('div.item_dropmenu')).css('display')!='none'){
					$($(container).children('div.item_dropmenu')).hide();
					return false;	
				}
	
				var menuleft=getMenuLeft(container,this);

				menuleft=menuleft-$($(container).children('div.item_dropmenu')).width()-$($(container).children('div.tabs-header').find('.tabs-wrap')).scrollLeft();
				menuleft=menuleft<0?10:menuleft;
				$($(container).children('div.item_dropmenu')).css('left',menuleft+'px');
				$($(container).children('div.item_dropmenu')).show();
				return false;
			})
		}
	}

	function getMenuLeft(container,ctrli){
		var menuleft = $(container).children('div.tabs-header')[0].offsetLeft + $(ctrli).parent()[0].offsetWidth+$(ctrli).parent()[0].offsetLeft;
		if($($(container).children('div.tabs-header').find('.tabs-scroller-left')).css('display')!='none'){
			menuleft+=$($(container).children('div.tabs-header').find('.tabs-scroller-left')).width();
		}

		return menuleft;
	}

	//o=top;
	//给iframe页面绑定click事件，关闭下拉菜单
	function childframe_close() {
		var d=$('.tabs-container');
		var doc = d.find('#tab_content')[0].contentDocument;
		$(doc).bind('click',function(e){
			$(d.children('div.fiter_dropmenu')).hide();
			$(d.children('div.item_dropmenu')).hide();
			_rename(d[0]);
		});
	}

	//进行事件的绑定
	function _bindListeners(container){
		setScrollers(container);
		setProperties(container);
		$(window).resize(function(){setScrollers(container);});
		$(document).click(function(event){_onDocumentClicked(container,event)});
		//window.setTimeout(function(){childframe_close(top,container);},800);
		
	}
	
	//在tab初始化时调用，生成tab菜单
	function _createTab(container,options){
		var tabs_container=null,newtab=null;
		var tabs_header="<div class='tabs-scroller-left'></div><div class='tabs-scroller-right'></div>";
		tabs_header+="<div class='tabs-wrap'><ul class='tabs'></ul></div>";
		$(tabs_header).prependTo($(container).children('div.tabs-header'));
		
		var tabs_tools="<div id='tab-tools' class='tabs-tool'>";
		var add=null,filter=null;
		if(options.add && $.trim(options.add)!='false')
		{
			add='<span class="tool-add" ></span>';
			tabs_tools+=add;
		}
		if(options.filter && $.trim(options.filter)!='false')
		{
			filter='<span class="filter"></span>';
			tabs_tools+=filter;
		}
		tabs_tools+='</div>';
		$(tabs_tools).insertAfter($($(container).children('div.tabs-header').find('.tabs-wrap')))
		
		var item_dropmenu="<div class='fiter_dropmenu'></div>";
		$(item_dropmenu).insertAfter($(container).children('div.tabs-header'));
		
		
		/*参数*/
		var setIframeOptions = "",setIframeOptionsOuter = "";
		for(var a in options.setIframe){
			if(setIframeOptions.length>1){
				setIframeOptions+=",";	
				setIframeOptionsOuter+=',';
			}
			
			if(UI.isArray(options.setIframe[a])){
				var sTempOptions = "";
				for(var i=0;i<options.setIframe[a].length;i++){
					if(sTempOptions.length>0){
						sTempOptions+=",";	
					}
					sTempOptions+="'"+options.setIframe[a][i]+"'";	
				}
				sTempOptions = "["+sTempOptions+"]";
				setIframeOptions+=a+":"+sTempOptions;	
				setIframeOptionsOuter+=a+":"+sTempOptions;	
			}else{
				setIframeOptions+=a+":"+options.setIframe[a];
				if(a=='setWidth' || a=='setHeight'){
					setIframeOptionsOuter+=a+":false";
				}
				else{
					setIframeOptionsOuter+=a+":"+options.setIframe[a];
				}
			}
		}

		setIframeOptions= "{"+setIframeOptions+"}";
		setIframeOptions = setIframeOptions.length>0?","+setIframeOptions:"";

		setIframeOptionsOuter= "{"+setIframeOptionsOuter+"}";
		setIframeOptionsOuter = setIframeOptionsOuter.length>0?","+setIframeOptionsOuter:"";
		
		/* 自定义属性 */
		var  customAttr = "";
		if(options.customAttr){
			for(var o in options.customAttr){
				if(customAttr.length>0){
					customAttr+=" ";	
				}
				customAttr+=o+"="+options.customAttr[o];	
			}
		}
		
		var tabs_panel='<div style="height: '+($(container).height()-31)+'px;" class="tabs-panels"><iframe id="tab_content" name="tab_content" notsetheight="true" notsetwidth="true" frameborder="0" width="100%" height="100%" src="about:blank" '+customAttr+' onload="setFrameHeight(this,false,false)"></iframe></div>';
		
		if(options.setIframe.setWidth || options.setIframe.setHeight){
			tabs_panel='<div class="tabs-panels"><iframe id="tab_content" name="tab_content" frameborder="0" width="100%" height="100%" src="about:blank" '+customAttr+' onload="setFrameHeight(this,'+options.setIframe.setHeight+','+options.setIframe.setWidth+setIframeOptions+')"></iframe></div>';
		}
		
		$(tabs_panel).appendTo($(container));
		
		$(container).children('div.tabs-panels').find('#tab_content').bind('load',function(){
			childframe_close();	
		})
		
		if(options.add && $.trim(options.add)!='false'){
		$($(container).children('div.tabs-header').find('.tool-add'))[0].onclick=function(){_addTab(container);}
		}
		if(options.filter  && $.trim(options.filter)!='false'){
		$($(container).children('div.tabs-header').find('.filter'))[0].onclick=function(){_fiterTab(container,options);}
		}
		
		var cookieTabs=[];
		var activeTabTitle = _getSavedActiveTab();
		if (tab_opt.cookieId != null && tab_opt.filter){
			var cookieValue=$.cookie(tab_opt.cookieId);
			if(cookieValue){
				cookieTabs = cookieValue.split(',');
			}
		}
		
		
		for(var i=0;i<options.data.length;i++){
			if(typeof options.data[i].show == 'undefined'){
				options.data[i].show = true;
			}
		 	newtab={title:options.data[i].title,url:options.data[i].url,closable:options.data[i].closable,dropmenu:options.data[i].dropmenu,dataCache:options.data[i].dataCache,selected:options.selected,data:options.data[i]};
			var showFlag=false;
			if (tab_opt.cookieId != null && tab_opt.filter){
				$.each(cookieTabs,function(){
					if(this==newtab.title){
						showFlag=true;
						return false;
					}
				})
				
				if(cookieTabs.length <= 0 && options.data[i].show){
					showFlag=true;
					_saveOnCookie(newtab.title,newtab.url,true);
				}
			
			}
			else if(options.data[i].show){
				showFlag=true;
			}
			
			if(!activeTabTitle && showFlag){
				activeTabTitle=newtab.title;
			}
	
			addTab(container,newtab,showFlag);
		}

		$(container).children('div.tabs-header').find('li').mouseover(function(){
			_addSubMenu(container,$(this));
		});
		
		selectTab(container,{tabname:activeTabTitle});

	}
	//添加tab
	function addTab(container, options , noshow) {
		var display = noshow?'':'style="display:none;"';
		
		var newli="<li " + display + " ><a hidefocus='true' class='tabs-inner' href='"+options.url+"' onclick='return false' data-cache='"+ options.dataCache +"'><input hidefocus='true' class='tabs-input' value='"+options.title+"' /><span class='tabs-title ";
		if(options.dropmenu){
			if(options.closable){
				newli=newli+" tabs-closable'>"+options.title+"</span></a><a href='javascript:void(0)' hidefocus='true' class='tabs_drop'></a><a class='tabs-close' href='javascript:void(0)'></a>";
			}else{
				newli=newli+"'>"+options.title+"</span></a><a hidefocus='true' href='javascript:void(0)' class='tabs_drop'></a>";
			}
		}
		else{
			if(options.closable){
				newli=newli+" tabs-closable'>"+options.title+"</span></a><a class='tabs-close' href='javascript:void(0)'></a>";
			}else{
				newli=newli+"'>"+options.title+"</span></a>";
			}
		}
		
		newli+='</a></li>';
		$(newli).appendTo($(container).children('div.tabs-header').find('.tabs')).data('data',options.data);

		 //添加关闭事件
		if(options.closable){
			_close(container);
		}
		
		//添加选中事件
		_select(container);
		//使得新添加的tab为选中状态
		var selectedLi = $(container).children('div.tabs-header').find('.tabs li').last();
		_setActiveTab(container,selectedLi,options.url);

		tabIframe[tabIframe.length]=options;
		
		setScrollers(container);
		
		_itemDropMenu(container);
		
		if(tab_opt.rename){	
			//双击进行重命名
			_renameTab(container);
		}
		
		//移动新tab，使其出现
		if(!noshow){
			var header = $(container).children('div.tabs-header');
			var wrap = $('.tabs-wrap', header);
				var pos = Math.min(
					wrap.scrollLeft() + 250,
					getMaxScrollWidth(container)
				);
			wrap.animate({scrollLeft:pos}, 300);
		}
	}
		//重命名tab
	function _renameTab(container){
		var mark=true;
		 $(container).children('div.tabs-header').find('.tabs-inner').last().bind('dblclick',function(){
		 	
					$($(this.parentElement).find('.tabs-input')).show();
					$($(this.parentElement).find('.tabs-title')).hide();
					setScrollers(container);
					//$(container).children('div.tabs-header').find('.tabs-inner').bind('click',function(){return false;});
		 });

	}

	function _moveRight(container,o){
		var header = $(container).children('div.tabs-header');
		var wrap = $('.tabs-wrap', header);
		var l=wrap.scrollLeft()-o.outerWidth();
		if(wrap.scrollLeft()>0)
		{
			wrap.animate({scrollLeft:l}, 300);
		}
	}
	function _close(container){
		var opts = $.data(container, 'options');
		$($(container).children('div.tabs-header').find('.tabs-close').last()).bind('click.tabs',function(){

			var flag = true;
			if(typeof opts.onClose == 'function'){
				var data = $(this).parent().data('data');
				flag = opts.onClose(data);
			}

			if(flag){
				_selectOther(container,this.parentElement);
				_moveRight(container,$(this).parent());
				$($(this).parent()).remove();
				setScrollers(container);	
				var closeTab_value=jQuery($(this).parent()).find('.tabs-input').val();
				for(var i=0;i<tabIframe.length;i++){
				  if(closeTab_value==tabIframe[i].title){
					 tabIframe.splice(i,1);
				  }
			  	}	
			}
			
		});	
	}

	function _select(container){
		var opts = $.data(container, 'options');
		$($(container).children('div.tabs-header').find('.tabs li').last()).bind('click.tabs',function(){
			_setActiveTab(container,this);
			
			setScrollers(container);

			//切换不同的iframe页面
			//window.setTimeout(function(){childframe_close(top,container);},800);
		});
	}
	
	function _addSubMenu(container,li){
		var data = $(li).data('data');
		var childMenu = '';
		var activeUrl = $(li).data('active');
		var menuleft = $(container).children('div.tabs-header')[0].offsetLeft + $(li)[0].offsetLeft;
		for(var i=0;data.child && i<data.child.length;i++){
			var active = '';
			if(activeUrl == data.child[i].url){
				active = 'tabs-submenu-active';
			}
			childMenu+='<li class="'+active+'" data-url="'+data.child[i].url+'"><a href="###" >'+data.child[i].title+'</a></li>';
		}
		if(childMenu.length>0){
			childMenu='<div class="tabs-submenu" style="left:'+menuleft+'px"><ul>'+childMenu+'</ul></div>';
		}
		$(container).find('.tabs-submenu').remove();
		$(container).append(childMenu);
		$(container).find('.tabs-submenu').find('li').click(function(){
			_setSubMenuActive(container,li,this);
		});
		$(container).find('.tabs-submenu ul').mouseleave(function(){
			$(container).find('.tabs-submenu').remove();
		})
	}

	function _setSubMenuActive(container,containerli,submenuli){
		$(containerli).parent().find('li').data('active',null);
		$(containerli).data('active',$(submenuli).attr('data-url'));
		$(submenuli).parent().find('li').removeClass('tabs-submenu-active');
		$(submenuli).addClass('tabs-submenu-active');	
		_setActiveTab(container,containerli,$(submenuli).attr('data-url'));
	}

	function _setActiveTab(container,tab,url){
		var data = $(tab).data('data');
		if(data.child && !url) return;

		$(container).find('.tabs-selected').removeClass('tabs-selected');
		$(tab).addClass('tabs-selected');
		
		var title = $(tab).find('span').text();
		if(!url && data && data.url){
			url = data.url;
		}
		
		url=url?url:_getUrl(title);
		$(container).children('div.tabs-panels').find('#tab_content')[0].src=url;
		
		_saveActiveTab(title,url);
		
		//触发选中页的回调函数
		if(tab_opt.selected && typeof tab_opt.selected == 'function'){
			tab_opt.selected.apply($(tab),[$(tab)]);
		}
	}
	//工具栏中的添加按钮的添加事件，默认添加的为new tab inner + index。
	var index=0;
	function _addTab(container){
		addTab(container,{
			title:'New Tab inner '+index++ ,
			closable:true,
			url:tabIframe[tabIframe.length-1].url
		},true);
		
	}
		//根据名字，获取对应url
	function _getUrl(title){
		var url = '';
		for(var i=0;i<tabIframe.length;i++){
			if(title==tabIframe[i].title){
				url = tabIframe[i].url;
			}
			for(var j=0;!url && tabIframe[i].child && j<tabIframe[i].child.length;j++){
				if(title == tabIframe[i].child[j].url){
					url = tabIframe[i].child[j].url;
				}
			}
			if(url) break;
		}

		return url;
	}
	//选中的页签删除，隐藏之后，需要把下一个（或者上一个）页签选中
	function _selectOther(container,currentTab){
		if($(currentTab).hasClass('tabs-selected')){
			var otherTab=null; 
			otherTab=currentTab.nextSibling;
			while(otherTab&&$(otherTab).css('display')=='none'){
				otherTab=otherTab.nextSibling;
			}
			if(!otherTab){	
				otherTab=currentTab.previousSibling;
				while(otherTab&&$(otherTab).css('display')=='none'){
					otherTab=otherTab.previousSibling;
				}
			}
			if(otherTab){
				var title=otherTab.textContent?otherTab.textContent:otherTab.innerText;
				selectTab(container,{tabname:title});
			}
		}
	}
		//按tab的title进行选中
	function selectTab(container,opt){
		if(typeof opt === 'string'){
			opt = {tabname:opt};
		}
		opt=opt||{};
		var tabTitle=opt.tabname;
		var tabUrl=opt.taburl;
		var mark=true;
		var le=0;
		
		if(tabUrl && !tabTitle){
			for(var i=0;i<tabIframe.length;i++){
				if(tabIframe[i].url==tabUrl){
					tabTitle = tabIframe[i].title;
					break;
				}
			}
		}
		
		$(container).children('div.tabs-header').find('.tabs li').each(function(){
			var title=this.textContent?this.textContent:this.innerText;

			if(mark&&title==tabTitle){
				_setActiveTab(container,this);
				mark=false;
				le=this.offsetLeft;
				//window.setTimeout(function(){childframe_close(top,container);},800);
			}
		})
		setScrollers(container);
		var header = $(container).children('div.tabs-header');
		var wrap = $('.tabs-wrap', header);
		if(le<wrap.scrollLeft()){
			wrap.animate({scrollLeft:le}, 300);
		}
	}
	function closeTab(container,opt){
		var mark=true;
		var closeTab_value='';
		if(opt==undefined){
			$(container).children('div.tabs-header').find('.tabs li').each(function(){
				if(mark&&$(this).hasClass('tabs-selected')){
					_selectOther(container,this);
					_moveRight(container,$(this));
					closeTab_value=jQuery(this).find('.tabs-input').val();
					$(this).remove();
					setScrollers(container);
					mark=false;	
				}	
			})
		}
		else{
			opt=opt||{};
			tabname=opt.tabname;
			$(container).children('div.tabs-header').find('.tabs li').each(function(){
				if(mark&&($(this).find('.tabs-inner').find('.tabs-title')[0].textContent==tabname||$(this).find('.tabs-inner').find('.tabs-title')[0].innerText==tabname)){
					_selectOther(container,this);
					_moveRight(container,$(this));
					closeTab_value=jQuery(this).find('.tabs-input').val();
					$(this).remove();
					setScrollers(container);
					mark=false;	
				}	
			})	
		}
		for(var i=0;i<tabIframe.length;i++)
			  {
				  if(closeTab_value==tabIframe[i].title)
				  {
					 tabIframe.splice(i,1);
				  }
			  }
	}
	function renameTab(container,opt){
		
		if(opt==undefined){
			if(tab_opt.rename)
			{
				$(container).children('div.tabs-header').find('.tabs li').each(function(){
				if($(this).hasClass('tabs-selected')){
					$($(this).find('.tabs-input')).show();
					$($(this).find('.tabs-title')).hide();
					}
				})
			}
		}
		else{
			opt=opt||{};
			var sli;
			var tabname=opt.tabname,newName=opt.newName;
			
			if(newName=='') return ;
						
			if(tabname==undefined)
			{
				sli=$(container).children('div.tabs-header').find('.tabs li.tabs-selected');
				tabname=(sli).find('.tabs-inner').find('.tabs-title')[0].textContent||(sli).find('.tabs-inner').find('.tabs-title')[0].innerText;
				((sli).find('.tabs-inner').find('.tabs-title')[0].textContent!=undefined) &&((sli).find('.tabs-inner').find('.tabs-title')[0].textContent=newName);
				((sli).find('.tabs-inner').find('.tabs-title')[0].innerText!=undefined) &&((sli).find('.tabs-inner').find('.tabs-title')[0].innerText=newName);
				(sli).find('.tabs-inner').find('.tabs-input')[0].value=newName;
				for(var i=0;i<tabIframe.length;i++)
				{
					if(tabname==tabIframe[i].title)
					{
						tabIframe[i].title=newName;
					}
				}
			}
			else{
				for(var i=0;i<tabIframe.length;i++)
				{
					if(tabIframe[i].title==tabname)
					{
						tabIframe[i].title=newName;
						sli=$($(container).children('div.tabs-header').find('.tabs li')[i]);

						((sli).find('.tabs-inner').find('.tabs-title')[0] && (sli).find('.tabs-inner').find('.tabs-title')[0].textContent!=undefined) &&((sli).find('.tabs-inner').find('.tabs-title')[0].textContent=newName);
						((sli).find('.tabs-inner').find('.tabs-title')[0] && (sli).find('.tabs-inner').find('.tabs-title')[0].innerText!=undefined) &&((sli).find('.tabs-inner').find('.tabs-title')[0].innerText=newName);
						if((sli).find('.tabs-inner').find('.tabs-input')[0]){
							(sli).find('.tabs-inner').find('.tabs-input')[0].value=newName;
						}
					}
				}
			}

		}
		setScrollers(container);
	}
	//筛选菜单中，选中的显示，未选中的tab进行隐藏
	function _onSelectItem(container,checked,rel){
		if(checked)	$($(container).children('div.tabs-header').find('.tabs li')[rel]).show();
		else $($(container).children('div.tabs-header').find('.tabs li')[rel]).hide();
		_moveRight(container,$($(container).children('div.tabs-header').find('.tabs li')[rel]));
		_selectOther(container,$(container).children('div.tabs-header').find('.tabs li')[rel]);
		setScrollers(container);	
	}

	//工具栏中的筛选功能
	function _fiterTab(container,options){
		var fiter_dropmenu = $(container).children('div.fiter_dropmenu');
		
		if($(fiter_dropmenu).css('display')!='none') {
			$(fiter_dropmenu).hide();
			return;
		}
		
		fiter_dropmenu.empty();
		var dropmenu='';
		var menu_a;
		menu_a='<ul>';
		
		
		$(container).children('div.tabs-header').find('.tabs li').each(function(i){
			var checkedClass='';
			if($(this).css('display')!='none'){
				checkedClass='checkbox_checked';
			}
			else{
				checkedClass='checkbox';
			}
			menu_a+='<li class="Input" rel="'+i+'"><b class="'+checkedClass+'"></b>';
			
			var title=this.textContent?this.textContent:this.innerText;
			menu_a+='<span>'+title+'</span></li>';
			
		});
		menu_a+='</ul>';
		
		$(menu_a).appendTo(fiter_dropmenu);
		
		var checkedCount = $(fiter_dropmenu).find('.checkbox_checked').length;
		if(checkedCount==1 && !options.closeAll){
			$(fiter_dropmenu).find('.checkbox_checked').parent().addClass('disabled');
		}
		
		
		var fiter_dropmenu_left=$(container).children('div .tabs-header').find('.tabs-tool')[0].offsetLeft+$(container).children('div .tabs-header').find('.tabs-tool')[0].offsetWidth - 8;
		if((fiter_dropmenu_left+fiter_dropmenu.outerWidth())>$(container).children('div.tabs-header').width())
		{
			fiter_dropmenu_left=$(container).children('div.tabs-header').width()-fiter_dropmenu.outerWidth();
		}
		$(fiter_dropmenu).css('left',fiter_dropmenu_left+'px');
		$(fiter_dropmenu).show();
		$(container).children('div.fiter_dropmenu').find('li').each(function(){
			this.onmouseover=function(){$(this).addClass('hover');}
			this.onmouseout=function(){$(this).removeClass('hover');}
			this.onclick=function(){
				var checkedCount = $(this).parent().find('.checkbox_checked').length;
				
				var dataOption = options.data[$(this).attr('rel')];
				var checked = true;
				if($(this.children[0]).hasClass('checkbox_checked')){
					if(checkedCount>1 || options.closeAll){
						$(this.children[0]).removeClass('checkbox_checked');
						$(this.children[0]).addClass('checkbox');
						_saveOnCookie($(this).find('span').text(),this.attributes['rel'].value,false);
					}
					
					if(checkedCount==2 && !options.closeAll){
						$(this).parent().find('.checkbox_checked').parent().addClass('disabled');
					}

					checked=false;
				}
				else{
					$(this.children[0]).removeClass('checkbox');
					$(this.children[0]).addClass('checkbox_checked');
					
					$(this).parent().find('.checkbox_checked').parent().removeClass('disabled');
					
					_saveOnCookie($(this).find('span').text(),this.attributes['rel'].value,true);
				}

				_onSelectItem(container,$(this.children[0]).hasClass('checkbox_checked'),this.attributes['rel'].value);

				if(typeof options.checked == 'function'){
					options.checked.call(this,checked,dataOption);
				}
			}
		})
	}
	
	function _saveOnCookie(title,url,add) {
		if (tab_opt.cookieId == null || !tab_opt.filter) return;
		
		var value = $.cookie(tab_opt.cookieId);
		if(add){
			if(!value) value='';
			value += title+',';
		}
		else if(value){
			value = value.replace(title + ',','');
		}
		$.cookie(tab_opt.cookieId,value,{'expires':60});
	}
	
	function _saveActiveTab(title,url) {
		if (!tab_opt.saveSelectedTab) return;
		if(!tab_opt.activeTabCookieId){
			tab_opt.activeTabCookieId = tab_opt.container[0].id+'_activetabcookieid';
		}
		
		$.cookie(tab_opt.activeTabCookieId,title,{'expires':60});
	}
	
	function _getSavedActiveTab() {
		if (!tab_opt.saveSelectedTab) return;
		if(!tab_opt.activeTabCookieId){
			tab_opt.activeTabCookieId = tab_opt.container[0].id+'_activetabcookieid';
		}

		return $.cookie(tab_opt.activeTabCookieId);
	}
	
	//记录title和url
	var tabIframe=[];
	
	//记录总配置
	var tab_opt;
	
	function _get_tabs(){
		var tabs_name=[];
		var t={};
		for(var i=0;i<tabIframe.length;i++)
		{
			t={};
			t.title=tabIframe[i].title;
			t.url=tabIframe[i].url;
			tabs_name.push(t);
		}
		return tabs_name;
	}
	
	$.fn.tabs = function(options, param){
		if (typeof options == 'string') {
			return $.fn.tabs.methods[options](this, param);
		}
		else{
			if(options.data && options.data.length>0){
				return $.fn.tabs.methods['init'](this, options);
			}	
		}
	};
	
	$.fn.tabs.methods = {
		init: function(jq,options){
			if(typeof options.setIframe != "undefined"){
				if(typeof options.setIframe.setHeight == "undefined"){
					if(typeof options.setHeight !="undefined"){
						options.setIframe.setHeight = options.setHeight;	
					}else{
						options.setIframe.setHeight = undefined;	
					}		
				}
				
				if(typeof options.setIframe.setWidth == "undefined"){
					if(typeof options.setWidth !="undefined"){
						options.setIframe.setWidth = options.setWidth;	
					}else{
						options.setIframe.setWidth = false;	
					}		
				}	
			}else{
				options.setIframe = {};
				if(typeof options.setHeight !="undefined"){
					options.setIframe.setHeight = options.setHeight;	
				}else{
					options.setIframe.setHeight = undefined;	
				}
				
				if(typeof options.setWidth !="undefined"){
					options.setIframe.setWidth = options.setWidth;	
				}else{
					options.setIframe.setWidth = false;	
				}
				
			}
			var defaults = {'cookieId':null,'saveSelectedTab':false,'container':jq,closeAll:false,selected:null,onClose:null};
			tab_opt=$.extend(defaults,options);
			
			if(!tab_opt.activeTabCookieId){
				tab_opt.activeTabCookieId = jq[0].id + '_'+UI.hashCode(window.location.toString())+'activetabcookieid';
			}
			
			$(jq).data('options',tab_opt);
			_createTab(jq[0],tab_opt);
			//selectTab(jq[0],{tabname:options.data[0].title});
			_bindListeners(jq[0]);
		},
		add: function(jq, options){
			return jq.each(function(){
				addTab(this, options ,true);
			});
		},
		select:function(jq,options){
			return  selectTab(jq[0],options);
		},
		close:function(jq,options){
			return  closeTab(jq[0],options);
		},
		rename:function(jq,options){
			return  renameTab(jq[0],options);
		},
		gettabs:function(){
			return _get_tabs();
		},
		childframe_close:function(){
			return childframe_close();
		}
	};
	
})(window.jQuery);
/**
* hightlight keyword 
* @author chenping@intra.nsfocus.com
* @time 20130718
*/
(function(jQuery, window) {
	"use strict";
	if(typeof jQuery =='undefined'){
		return;
	}
	jQuery.extend({
		searchhighlight: function (node, re, nodeName, className) {
			if (node.nodeType === 3) {
				var match = node.data.match(re);
				if (match) {
					var highlight = document.createElement(nodeName || 'span');
					highlight.className = className || 'highlight';
					var wordNode = node.splitText(match.index);
					wordNode.splitText(match[0].length);
					var wordClone = wordNode.cloneNode(true);
					highlight.appendChild(wordClone);
					wordNode.parentNode.replaceChild(highlight, wordNode);
					return 1; //skip added node in parent
				}
			} else if ((node.nodeType === 1 && node.childNodes) && // only element nodes that have children
					!/(script|style)/i.test(node.tagName) && // ignore script and style nodes
					!(node.tagName === nodeName.toUpperCase() 
					&& node.className === className)
					&& node.className !== 'first_title') { // skip if already highlighted
				for (var i = 0; i < node.childNodes.length; i++) {
					i += jQuery.searchhighlight(node.childNodes[i], re, nodeName, className);
				}
			}
			return 0;
		}
	});

	jQuery.fn.unsearchhighlight = function (options) {
		var settings = { className: 'highlight', element: 'span' };
		jQuery.extend(settings, options);

		return this.find(settings.element + "." + settings.className).each(function () {
			var parent = this.parentNode;
			parent.replaceChild(this.firstChild, this);
			parent.normalize();
		}).end();
	};

	jQuery.fn.searchhighlight = function (words, options) {
		jQuery(this).unsearchhighlight(options);
		
		var settings = { className: 'highlight', element: 'span', caseSensitive: false, wordsOnly: false };
		jQuery.extend(settings, options);
		
		if (words.constructor === String) {
			words = [words];
		}
		words = jQuery.grep(words, function(word, i){
		  return word != '';
		});
		words = jQuery.map(words, function(word, i) {
		  return word.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
		});
		if (words.length == 0) { return this; };

		var flag = settings.caseSensitive ? "" : "i";
		var pattern = "(" + words.join("|") + ")";
		if (settings.wordsOnly) {
			pattern = "\\b" + pattern + "\\b";
		}
		var re = new RegExp(pattern, flag);
		
		return this.each(function () {
			jQuery.searchhighlight(this, re, settings.element, settings.className);
		});
	};
	
	//scroll to the highlight word  by:zhaoweixiong
	jQuery.fn.scrolltohighlight = function(options){
		var settings = { className: 'highlight', container: 'html' };
		jQuery.extend(settings, options);
		
		var container = jQuery(settings.container),
			scrollTo = jQuery('.'+settings.className);
		
		if(scrollTo[0]){
			container.scrollTop(
				scrollTo.offset().top - container.offset().top + container.scrollTop()
			);
		}
					
	}
}(window.jQuery, window));
/* This notice must be untouched at all times.

wz_tooltip.js	 v. 4.12

The latest version is available at
http://www.walterzorn.com
or http://www.devira.com
or http://www.walterzorn.de

Copyright (c) 2002-2007 Walter Zorn. All rights reserved.
Created 1.12.2002 by Walter Zorn (Web: http://www.walterzorn.com )
Last modified: 13.7.2007

Easy-to-use cross-browser tooltips.
Just include the script at the beginning of the <body> section, and invoke
Tip('Tooltip text') from within the desired HTML onmouseover eventhandlers.
No container DIV, no onmouseouts required.
By default, width of tooltips is automatically adapted to content.
Is even capable of dynamically converting arbitrary HTML elements to tooltips
by calling TagToTip('ID_of_HTML_element_to_be_converted') instead of Tip(),
which means you can put important, search-engine-relevant stuff into tooltips.
Appearance of tooltips can be individually configured
via commands passed to Tip() or TagToTip().

Tab Width: 4
LICENSE: LGPL

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License (LGPL) as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

For more details on the GNU Lesser General Public License,
see http://www.gnu.org/copyleft/lesser.html
*/

var config = new Object();


//===================  GLOBAL TOOPTIP CONFIGURATION  =========================//
var  tt_Debug	= true		// false or true - recommended: false once you release your page to the public
var  tt_Enabled	= true		// Allows to (temporarily) suppress tooltips, e.g. by providing the user with a button that sets this global variable to false
var  TagsToTip	= true		// false or true - if true, the script is capable of converting HTML elements to tooltips

// For each of the following config variables there exists a command, which is
// just the variablename in uppercase, to be passed to Tip() or TagToTip() to
// configure tooltips individually. Individual commands override global
// configuration. Order of commands is arbitrary.
// Example: onmouseover="Tip('Tooltip text', LEFT, true, BGCOLOR, '#FF9900', FADEIN, 400)"

config. init=false
config. Above			= false 	// false or true - tooltip above mousepointer?
config. BgColor 		= '#eff5f7' // Background color
config. BgImg			= ''		// Path to background image, none if empty string ''
config. BorderColor 	= '#d8d8d8'
config. BorderStyle 	= 'solid'	// Any permitted CSS value, but I recommend 'solid', 'dotted' or 'dashed'
config. BorderWidth 	= 1
config. CenterMouse 	= false 	// false or true - center the tip horizontally below (or above) the mousepointer
config. ClickClose		= false 	// false or true - close tooltip if the user clicks somewhere
config. CloseBtn		= false 	// false or true - closebutton in titlebar
config. CloseBtnColors	= ['#990000', '#FFFFFF', '#DD3333', '#FFFFFF']	  // [Background, text, hovered background, hovered text] - use empty strings '' to inherit title colors
config. CloseBtnText	= '&nbsp;X&nbsp;'	// Close button text (may also be an image tag)
config. CopyContent		= true		// When converting a HTML element to a tooltip, copy only the element's content, rather than converting the element by its own
config. Delay			= 400		// Time span in ms until tooltip shows up
config. Duration		= 0 		// Time span in ms after which the tooltip disappears; 0 for infinite duration
config. FadeIn			= 0 		// Fade-in duration in ms, e.g. 400; 0 for no animation
config. FadeOut 		= 0
config. FadeInterval	= 30		// Duration of each fade step in ms (recommended: 30) - shorter is smoother but causes more CPU-load
config. Fix 			= null		// Fixated position - x- an y-oordinates in brackets, e.g. [210, 480], or null for no fixation
config. FollowMouse		= true		// false or true - tooltip follows the mouse
config. FontColor		= '#000044'
config. FontFace		= 'Verdana,Geneva,sans-serif'
config. FontSize		= '8pt' 	// E.g. '9pt' or '12px' - unit is mandatory
config. FontWeight		= 'normal'	// 'normal' or 'bold';
config. Left			= false 	// false or true - tooltip on the left of the mouse
config. OffsetX 		= 14		// Horizontal offset of left-top corner from mousepointer
config. OffsetY 		= 8 		// Vertical offset
config. Opacity 		= 100		// Integer between 0 and 100 - opacity of tooltip in percent
config. Padding 		= 3 		// Spacing between border and content
config. Shadow			= false 	// false or true
config. ShadowColor 	= '#C0C0C0'
config. ShadowWidth 	= 5
config. Sticky			= false 	// Do NOT hide tooltip on mouseout? false or true
config. TextAlign		= 'left'	// 'left', 'right' or 'justify'
config. Title			= ''		// Default title text applied to all tips (no default title: empty string '')
config. TitleAlign		= 'left'	// 'left' or 'right' - text alignment inside the title bar
config. TitleBgColor	= ''		// If empty string '', BorderColor will be used
config. TitleFontColor	= '#ffffff'	// Color of title text - if '', BgColor (of tooltip body) will be used
config. TitleFontFace	= ''		// If '' use FontFace (boldified)
config. TitleFontSize	= ''		// If '' use FontSize
config. Width			= 0 		// Tooltip width; 0 for automatic adaption to tooltip content
//=======  END OF TOOLTIP CONFIG, DO NOT CHANGE ANYTHING BELOW  ==============//




//======================  PUBLIC  ============================================//
function Tip()
{
	if(!config.init){
		tt_Init();
	}
	tt_Tip(arguments, null);
}
function TagToTip()
{
	if(TagsToTip)
	{
		var t2t = tt_GetElt(arguments[0]);
		if(t2t)
			tt_Tip(arguments, t2t);
	}
}

//==================  PUBLIC EXTENSION API	==================================//
// Extension eventhandlers currently supported:
// OnLoadConfig, OnCreateContentString, OnSubDivsCreated, OnShow, OnMoveBefore,
// OnMoveAfter, OnHideInit, OnHide, OnKill

var tt_aElt = new Array(10), // Container DIV, outer title & body DIVs, inner title & body TDs, closebutton SPAN, shadow DIVs, and IFRAME to cover windowed elements in IE
tt_aV = new Array(),	// Caches and enumerates config data for currently active tooltip
tt_sContent,			// Inner tooltip text or HTML
tt_scrlX = 0, tt_scrlY = 0,
tt_musX, tt_musY,
tt_over,
tt_x, tt_y, tt_w, tt_h; // Position, width and height of currently displayed tooltip

function tt_Extension()
{
	tt_ExtCmdEnum();
	tt_aExt[tt_aExt.length] = this;
	return this;
}
function tt_SetTipPos(x, y)
{
	var css = tt_aElt[0].style;

	tt_x = x;
	tt_y = y;
	css.left = x + "px";
	css.top = y + "px";

	if(tt_ie56)
	{
		var ifrm = tt_aElt[tt_aElt.length - 1];
		if(ifrm)
		{
			ifrm.style.left = css.left;
			ifrm.style.top = css.top;
		}
	}
}
function tt_Hide()
{
	if(tt_db && tt_iState)
	{
		if(tt_iState & 0x2)
		{
			tt_aElt[0].style.visibility = "hidden";
			tt_ExtCallFncs(0, "Hide");
		}
		tt_tShow.EndTimer();
		tt_tHide.EndTimer();
		tt_tDurt.EndTimer();
		tt_tFade.EndTimer();
		if(!tt_op && !tt_ie)
		{
			tt_tWaitMov.EndTimer();
			tt_bWait = false;
		}
		if(tt_aV[CLICKCLOSE])
			tt_RemEvtFnc(document, "mouseup", tt_HideInit);
		tt_AddRemOutFnc(false);
		tt_ExtCallFncs(0, "Kill");
		// In case of a TagToTip tooltip, hide converted DOM node and
		// re-insert it into document
		if(tt_t2t && !tt_aV[COPYCONTENT])
		{
			tt_t2t.style.display = "none";
			tt_MovDomNode(tt_t2t, tt_aElt[6], tt_t2tDad);
		}
		tt_iState = 0;
		tt_over = null;
		tt_ResetMainDiv();
		if(tt_aElt[tt_aElt.length - 1])
			tt_aElt[tt_aElt.length - 1].style.display = "none";
	}
}
function tt_GetElt(id)
{
	return(document.getElementById ? document.getElementById(id)
			: document.all ? document.all[id]
			: null);
}
function tt_GetDivW(el)
{
	return(el ? (el.offsetWidth || el.style.pixelWidth || 0) : 0);
}
function tt_GetDivH(el)
{
	return(el ? (el.offsetHeight || el.style.pixelHeight || 0) : 0);
}
function tt_GetScrollX()
{
	return(window.pageXOffset || (tt_db ? (tt_db.scrollLeft || 0) : 0));
}
function tt_GetScrollY()
{
	return(window.pageYOffset || (tt_db ? (tt_db.scrollTop || 0) : 0));
}
function tt_GetClientW()
{
	return(document.body && (typeof(document.body.clientWidth) != tt_u) ? document.body.clientWidth
			: (typeof(window.innerWidth) != tt_u) ? window.innerWidth
			: tt_db ? (tt_db.clientWidth || 0)
			: 0);
}
function tt_GetClientH()
{
	// Exactly this order seems to yield correct values in all major browsers
	return(document.body && (typeof(document.body.clientHeight) != tt_u) ? document.body.clientHeight
			: (typeof(window.innerHeight) != tt_u) ? window.innerHeight
			: tt_db ? (tt_db.clientHeight || 0)
			: 0);
}
function tt_GetEvtX(e)
{
	return (e ? ((typeof(e.pageX) != tt_u) ? e.pageX : (e.clientX + tt_scrlX)) : 0);
}
function tt_GetEvtY(e)
{
	return (e ? ((typeof(e.pageY) != tt_u) ? e.pageY : (e.clientY + tt_scrlY)) : 0);
}
function tt_AddEvtFnc(el, sEvt, PFnc)
{
	if(el)
	{
		if(el.addEventListener)
			el.addEventListener(sEvt, PFnc, false);
		else
			el.attachEvent("on" + sEvt, PFnc);
	}
}
function tt_RemEvtFnc(el, sEvt, PFnc)
{
	if(el)
	{
		if(el.removeEventListener)
			el.removeEventListener(sEvt, PFnc, false);
		else
			el.detachEvent("on" + sEvt, PFnc);
	}
}

//======================  PRIVATE  ===========================================//
var tt_aExt = new Array(),	// Array of extension objects

tt_db, tt_op, tt_ie, tt_ie56, tt_bBoxOld,	// Browser flags
tt_body,
tt_flagOpa, 			// Opacity support: 1=IE, 2=Khtml, 3=KHTML, 4=Moz, 5=W3C
tt_maxPosX, tt_maxPosY,
tt_iState = 0,			// Tooltip active |= 1, shown |= 2, move with mouse |= 4
tt_opa, 				// Currently applied opacity
tt_bJmpVert,			// Tip above mouse (or ABOVE tip below mouse)
tt_t2t, tt_t2tDad,		// Tag converted to tip, and its parent element in the document
tt_elDeHref,			// The tag from which Opera has removed the href attribute
// Timer
tt_tShow = new Number(0), tt_tHide = new Number(0), tt_tDurt = new Number(0),
tt_tFade = new Number(0), tt_tWaitMov = new Number(0),
tt_bWait = false,
tt_u = "undefined";


function tt_Init()
{
	tt_MkCmdEnum();
	// Send old browsers instantly to hell
	if(!tt_Browser() || !tt_MkMainDiv()){
	    return;
	}
	tt_IsW3cBox();
	tt_OpaSupport();
	tt_AddEvtFnc(document, "mousemove", tt_Move);
	// In Debug mode we search for TagToTip() calls in order to notify
	// the user if they've forgotten to set the TagsToTip config flag
	if(TagsToTip || tt_Debug)
		tt_SetOnloadFnc();
	tt_AddEvtFnc(window, "scroll",
		function()
		{
			tt_scrlX = tt_GetScrollX();
			tt_scrlY = tt_GetScrollY();
			if(tt_iState && !(tt_aV[STICKY] && (tt_iState & 2)))
				tt_HideInit();
		} );
	// Ensure the tip be hidden when the page unloads
	tt_AddEvtFnc(window, "unload", tt_Hide);
	tt_Hide();

	//
	config.init=true;
}
// Creates command names by translating config variable names to upper case
function tt_MkCmdEnum()
{
	var n = 0;
	for(var i in config)
		eval("window." + i.toString().toUpperCase() + " = " + n++);
	tt_aV.length = n;
}
function tt_Browser()
{
	var n, nv, n6, w3c;

	n = navigator.userAgent.toLowerCase(),
	nv = navigator.appVersion;
	tt_op = (document.defaultView && typeof(eval("w" + "indow" + "." + "o" + "p" + "er" + "a")) != tt_u);
	tt_ie = n.indexOf("msie") != -1 && document.all && !tt_op;
	if(tt_ie)
	{
		var ieOld = (!document.compatMode || document.compatMode == "BackCompat");
		tt_db = !ieOld ? document.documentElement : (document.body || null);
		if(tt_db)
			tt_ie56 = parseFloat(nv.substring(nv.indexOf("MSIE") + 5)) >= 5.5
					&& typeof document.body.style.maxHeight == tt_u;
	}
	else
	{
		tt_db = document.documentElement || document.body ||
				(document.getElementsByTagName ? document.getElementsByTagName("body")[0]
				: null);
		if(!tt_op)
		{
			n6 = document.defaultView && typeof document.defaultView.getComputedStyle != tt_u;
			w3c = !n6 && document.getElementById;
		}
	}
	tt_body = (document.getElementsByTagName ? document.getElementsByTagName("body")[0]
				: (document.body || null));

	if(tt_ie || n6 || tt_op || w3c)
	{
		if(tt_body && tt_db)
		{
			if(document.attachEvent || document.addEventListener)
				return true;
		}
		else
			tt_Err("wz_tooltip.js must be included INSIDE the body section,"
					+ " immediately after the opening <body> tag.");
	}

	tt_db = null;
	return false;
}
function tt_MkMainDiv()
{
	// Create the tooltip DIV
	if(tt_body.insertAdjacentHTML)
		tt_body.insertAdjacentHTML("afterBegin", tt_MkMainDivHtm());
	else if(typeof tt_body.innerHTML != tt_u && document.createElement && tt_body.appendChild)
		tt_body.appendChild(tt_MkMainDivDom());
	// FireFox Alzheimer bug
	if(window.tt_GetMainDivRefs && tt_GetMainDivRefs()){
		return true;
	}
	tt_db = null;
	return false;
}
function tt_MkMainDivHtm()
{
	return('<div id="WzTtDiV"></div>' +
			(tt_ie56 ? ('<iframe id="WzTtIfRm" src="javascript:false" scrolling="no" frameborder="0" style="filter:Alpha(opacity=0);position:absolute;top:0px;left:0px;display:none;"></iframe>')
			: ''));
}
function tt_MkMainDivDom()
{
	var el = document.createElement("div");
	if(el)
		el.id = "WzTtDiV";
	return el;
}
function tt_GetMainDivRefs()
{
	tt_aElt[0] = tt_GetElt("WzTtDiV");
	if(tt_ie56 && tt_aElt[0])
	{
		tt_aElt[tt_aElt.length - 1] = tt_GetElt("WzTtIfRm");
		if(!tt_aElt[tt_aElt.length - 1])
			tt_aElt[0] = null;
	}
	if(tt_aElt[0])
	{
		var css = tt_aElt[0].style;

		css.visibility = "hidden";
		css.position = "absolute";
		css.overflow = "hidden";
		return true;
	}
	return false;
}
function tt_ResetMainDiv()
{
	var w = (window.screen && screen.width) ? screen.width : 10000;

	tt_SetTipPos(-w, 0);
	tt_aElt[0].innerHTML = "";
	tt_aElt[0].style.width = (w - 1) + "px";
}
function tt_IsW3cBox()
{
	var css = tt_aElt[0].style;

	css.padding = "10px";
	css.width = "40px";
	tt_bBoxOld = (tt_GetDivW(tt_aElt[0]) == 40);
	css.padding = "0px";
	tt_ResetMainDiv();
}
function tt_OpaSupport()
{
	var css = tt_body.style;

	tt_flagOpa = (typeof(css.filter) != tt_u) ? 1
				: (typeof(css.KhtmlOpacity) != tt_u) ? 2
				: (typeof(css.KHTMLOpacity) != tt_u) ? 3
				: (typeof(css.MozOpacity) != tt_u) ? 4
				: (typeof(css.opacity) != tt_u) ? 5
				: 0;
}
// Ported from http://dean.edwards.name/weblog/2006/06/again/
// (Dean Edwards et al.)
function tt_SetOnloadFnc()
{
	tt_AddEvtFnc(document, "DOMContentLoaded", tt_HideSrcTags);
	tt_AddEvtFnc(window, "load", tt_HideSrcTags);
	if(tt_body.attachEvent)
		tt_body.attachEvent("onreadystatechange",
			function() {
				if(tt_body.readyState == "complete")
					tt_HideSrcTags();
			} );
	if(/WebKit|KHTML/i.test(navigator.userAgent))
	{
		var t = setInterval(function() {
					if(/loaded|complete/.test(document.readyState))
					{
						clearInterval(t);
						tt_HideSrcTags();
					}
				}, 10);
	}
}
function tt_HideSrcTags()
{
	if(!window.tt_HideSrcTags || window.tt_HideSrcTags.done)
		return;
	window.tt_HideSrcTags.done = true;
	if(!tt_HideSrcTagsRecurs(tt_body))
		tt_Err("To enable the capability to convert HTML elements to tooltips,"
				+ " you must set TagsToTip in the global tooltip configuration"
				+ " to true.");
}
function tt_HideSrcTagsRecurs(dad)
{
	var a, ovr, asT2t;

	// Walk the DOM tree for tags that have an onmouseover attribute
	// containing a TagToTip('...') call.
	// (.childNodes first since .children is bugous in Safari)
	a = dad.childNodes || dad.children || null;
	for(var i = a ? a.length : 0; i;)
	{--i;
		if(!tt_HideSrcTagsRecurs(a[i]))
			return false;
		ovr = a[i].getAttribute ? a[i].getAttribute("onmouseover")
				: (typeof a[i].onmouseover == "function") ? a[i].onmouseover
				: null;
		if(ovr)
		{
			asT2t = ovr.toString().match(/TagToTip\s*\(\s*'[^'.]+'\s*[\),]/);
			if(asT2t && asT2t.length)
			{
				if(!tt_HideSrcTag(asT2t[0]))
					return false;
			}
		}
	}
	return true;
}
function tt_HideSrcTag(sT2t)
{
	var id, el;

	// The ID passed to the found TagToTip() call identifies an HTML element
	// to be converted to a tooltip, so hide that element
	id = sT2t.replace(/.+'([^'.]+)'.+/, "$1");
	el = tt_GetElt(id);
	if(el)
	{
		if(tt_Debug && !TagsToTip)
			return false;
		else
			el.style.display = "none";
	}
	else
		tt_Err("Invalid ID\n'" + id + "'\npassed to TagToTip()."
				+ " There exists no HTML element with that ID.");
	return true;
}
function tt_Tip(arg, t2t)
{	
	if(!tt_db)
		return;
	if(tt_iState){
		tt_Hide();
	}
	if(!tt_Enabled){
		return;
	}

	tt_t2t = t2t;
	if(!tt_ReadCmds(arg)){
		return;
	}
	tt_iState = 0x1 | 0x4;
	tt_AdaptConfig1();
	tt_MkTipContent(arg);
	tt_MkTipSubDivs();
	tt_FormatTip();
	tt_bJmpVert = false;
	tt_maxPosX = tt_GetClientW() + tt_scrlX - tt_w - 1;
	tt_maxPosY = tt_GetClientH() + tt_scrlY - tt_h - 1;
	tt_AdaptConfig2();
	// We must fake the first mousemove in order to ensure the tip
	// be immediately shown and positioned
	
	var eve=getEvent();
	tt_Move(eve);
	tt_ShowInit();
}
function tt_ReadCmds(a)
{
	var i;

	// First load the global config values, to initialize also values
	// for which no command has been passed
	i = 0;
	for(var j in config)
		tt_aV[i++] = config[j];
	// Then replace each cached config value for which a command has been
	// passed (ensure the # of command args plus value args be even)
	if(a.length & 1)
	{
		for(i = a.length - 1; i > 0; i -= 2)
			tt_aV[a[i - 1]] = a[i];
		return true;
	}
	tt_Err("Incorrect call of Tip() or TagToTip().\n"
			+ "Each command must be followed by a value.");
	return false;
}
function tt_AdaptConfig1()
{
	tt_ExtCallFncs(0, "LoadConfig");
	// Inherit unspecified title formattings from body
	if(!tt_aV[TITLEBGCOLOR].length)
		tt_aV[TITLEBGCOLOR] = tt_aV[BORDERCOLOR];
	if(!tt_aV[TITLEFONTCOLOR].length)
		tt_aV[TITLEFONTCOLOR] = tt_aV[BGCOLOR];
	if(!tt_aV[TITLEFONTFACE].length)
		tt_aV[TITLEFONTFACE] = tt_aV[FONTFACE];
	if(!tt_aV[TITLEFONTSIZE].length)
		tt_aV[TITLEFONTSIZE] = tt_aV[FONTSIZE];
	if(tt_aV[CLOSEBTN])
	{
		// Use title colors for non-specified closebutton colors
		if(!tt_aV[CLOSEBTNCOLORS])
			tt_aV[CLOSEBTNCOLORS] = new Array("", "", "", "");
		for(var i = 4; i;)
		{--i;
			if(!tt_aV[CLOSEBTNCOLORS][i].length)
				tt_aV[CLOSEBTNCOLORS][i] = (i & 1) ? tt_aV[TITLEFONTCOLOR] : tt_aV[TITLEBGCOLOR];
		}
		// Enforce titlebar be shown
		if(!tt_aV[TITLE].length)
			tt_aV[TITLE] = " ";
	}
	// Circumvents broken display of images and fade-in flicker in Geckos < 1.8
	if(tt_aV[OPACITY] == 100 && typeof tt_aElt[0].style.MozOpacity != tt_u && !Array.every)
		tt_aV[OPACITY] = 99;
	// Smartly shorten the delay for fade-in tooltips
	if(tt_aV[FADEIN] && tt_flagOpa && tt_aV[DELAY] > 100)
		tt_aV[DELAY] = Math.max(tt_aV[DELAY] - tt_aV[FADEIN], 100);
}
function tt_AdaptConfig2()
{
	if(tt_aV[CENTERMOUSE])
		tt_aV[OFFSETX] -= ((tt_w - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0)) >> 1);
}
// Expose content globally so extensions can modify it
function tt_MkTipContent(a)
{
	if(tt_t2t)
	{
		if(tt_aV[COPYCONTENT])
			tt_sContent = tt_t2t.innerHTML;
		else
			tt_sContent = "";
	}
	else
		tt_sContent = a[0];
	tt_ExtCallFncs(0, "CreateContentString");
}
function tt_MkTipSubDivs()
{
	var sCss = 'position:relative;margin:0px;padding:0px;border-width:0px;left:0px;top:0px;line-height:normal;width:auto;',
	sTbTrTd = ' cellspacing=0 cellpadding=0 border=0 style="' + sCss + '"><tbody style="' + sCss + '"><tr><td ';

	tt_aElt[0].innerHTML =
		(''
		+ (tt_aV[TITLE].length ?
			('<div id="WzTiTl" style="position:relative;z-index:1;">'
			+ '<table id="WzTiTlTb"' + sTbTrTd + 'id="WzTiTlI" style="' + sCss + '">'
			+ tt_aV[TITLE]
			+ '</td>'
			+ (tt_aV[CLOSEBTN] ?
				('<td align="right" style="' + sCss
				+ 'text-align:right;">'
				+ '<span id="WzClOsE" style="padding-left:2px;padding-right:2px;'
				+ 'cursor:' + (tt_ie ? 'hand' : 'pointer')
				+ ';" onmouseover="tt_OnCloseBtnOver(1)" onmouseout="tt_OnCloseBtnOver(0)" onclick="tt_HideInit()">'
				+ tt_aV[CLOSEBTNTEXT]
				+ '</span></td>')
				: '')
			+ '</tr></tbody></table></div>')
			: '')
		+ '<div id="WzBoDy" style="position:relative;z-index:0;">'
		+ '<table' + sTbTrTd + 'id="WzBoDyI" style="' + sCss + '">'
		+ tt_sContent
		+ '</td></tr></tbody></table></div>'
		+ (tt_aV[SHADOW]
			? ('<div id="WzTtShDwR" style="position:absolute;overflow:hidden;"></div>'
				+ '<div id="WzTtShDwB" style="position:relative;overflow:hidden;"></div>')
			: '')
		);
	tt_GetSubDivRefs();
	// Convert DOM node to tip
	if(tt_t2t && !tt_aV[COPYCONTENT])
	{
		// Store the tag's parent element so we can restore that DOM branch
		// once the tooltip is hidden
		tt_t2tDad = tt_t2t.parentNode || tt_t2t.parentElement || tt_t2t.offsetParent || null;
		if(tt_t2tDad)
		{
			tt_MovDomNode(tt_t2t, tt_t2tDad, tt_aElt[6]);
			tt_t2t.style.display = "block";
		}
	}
	tt_ExtCallFncs(0, "SubDivsCreated");
}
function tt_GetSubDivRefs()
{
	var aId = new Array("WzTiTl", "WzTiTlTb", "WzTiTlI", "WzClOsE", "WzBoDy", "WzBoDyI", "WzTtShDwB", "WzTtShDwR");

	for(var i = aId.length; i; --i)
		tt_aElt[i] = tt_GetElt(aId[i - 1]);
}
function tt_FormatTip()
{
	var css, w, iOffY, iOffSh;

	//--------- Title DIV ----------
	if(tt_aV[TITLE].length)
	{
		css = tt_aElt[1].style;
		css.background = tt_aV[TITLEBGCOLOR];
		css.paddingTop = (tt_aV[CLOSEBTN] ? 2 : 0) + "px";
		css.paddingBottom = "1px";
		css.paddingLeft = css.paddingRight = tt_aV[PADDING] + "px";
		css = tt_aElt[3].style;
		css.color = tt_aV[TITLEFONTCOLOR];
		css.fontFamily = tt_aV[TITLEFONTFACE];
		css.fontSize = tt_aV[TITLEFONTSIZE];
		css.fontWeight = "bold";
		css.textAlign = tt_aV[TITLEALIGN];
		// Close button DIV
		if(tt_aElt[4])
		{
			css.paddingRight = (tt_aV[PADDING] << 1) + "px";
			css = tt_aElt[4].style;
			css.background = tt_aV[CLOSEBTNCOLORS][0];
			css.color = tt_aV[CLOSEBTNCOLORS][1];
			css.fontFamily = tt_aV[TITLEFONTFACE];
			css.fontSize = tt_aV[TITLEFONTSIZE];
			css.fontWeight = "bold";
		}
		if(tt_aV[WIDTH] > 0)
			tt_w = tt_aV[WIDTH] + ((tt_aV[PADDING] + tt_aV[BORDERWIDTH]) << 1);
		else
		{
			tt_w = tt_GetDivW(tt_aElt[3]) + tt_GetDivW(tt_aElt[4]);
			// Some spacing between title DIV and closebutton
			if(tt_aElt[4])
				tt_w += tt_aV[PADDING];
		}
		// Ensure the top border of the body DIV be covered by the title DIV
		iOffY = -tt_aV[BORDERWIDTH];
	}
	else
	{
		tt_w = 0;
		iOffY = 0;
	}

	//-------- Body DIV ------------
	css = tt_aElt[5].style;
	css.top = iOffY + "px";
	if(tt_aV[BORDERWIDTH])
	{
		css.borderColor = tt_aV[BORDERCOLOR];
		css.borderStyle = tt_aV[BORDERSTYLE];
		css.borderWidth = tt_aV[BORDERWIDTH] + "px";
	}
	if(tt_aV[BGCOLOR].length)
		css.background = tt_aV[BGCOLOR];
	if(tt_aV[BGIMG].length)
		css.backgroundImage = "url(" + tt_aV[BGIMG] + ")";
	css.padding = tt_aV[PADDING] + "px";
	css.textAlign = tt_aV[TEXTALIGN];
	// TD inside body DIV
	css = tt_aElt[6].style;
	css.color = tt_aV[FONTCOLOR];
	css.fontFamily = tt_aV[FONTFACE];
	css.fontSize = tt_aV[FONTSIZE];
	css.fontWeight = tt_aV[FONTWEIGHT];
	css.background = "";
	css.textAlign = tt_aV[TEXTALIGN];
	if(tt_aV[WIDTH] > 0)
		w = tt_aV[WIDTH] + ((tt_aV[PADDING] + tt_aV[BORDERWIDTH]) << 1);
	else
		// We measure the width of the body's inner TD, because some browsers
		// expand the width of the container and outer body DIV to 100%
		w = tt_GetDivW(tt_aElt[6]) + ((tt_aV[PADDING] + tt_aV[BORDERWIDTH]) << 1);
	if(w > tt_w)
		tt_w = w;

	//--------- Shadow DIVs ------------
	if(tt_aV[SHADOW])
	{
		tt_w += tt_aV[SHADOWWIDTH];
		iOffSh = Math.floor((tt_aV[SHADOWWIDTH] * 4) / 3);
		// Bottom shadow
		css = tt_aElt[7].style;
		css.top = iOffY + "px";
		css.left = iOffSh + "px";
		css.width = (tt_w - iOffSh - tt_aV[SHADOWWIDTH]) + "px";
		css.height = tt_aV[SHADOWWIDTH] + "px";
		css.background = tt_aV[SHADOWCOLOR];
		// Right shadow
		css = tt_aElt[8].style;
		css.top = iOffSh + "px";
		css.left = (tt_w - tt_aV[SHADOWWIDTH]) + "px";
		css.width = tt_aV[SHADOWWIDTH] + "px";
		css.background = tt_aV[SHADOWCOLOR];
	}
	else
		iOffSh = 0;

	//-------- Container DIV -------
	tt_SetTipOpa(tt_aV[FADEIN] ? 0 : tt_aV[OPACITY]);
	tt_FixSize(iOffY, iOffSh);
}
// Fixate the size so it can't dynamically change while the tooltip is moving.
function tt_FixSize(iOffY, iOffSh)
{
	var wIn, wOut, i;

	tt_aElt[0].style.width = tt_w + "px";
	tt_aElt[0].style.pixelWidth = tt_w;
	wOut = tt_w - ((tt_aV[SHADOW]) ? tt_aV[SHADOWWIDTH] : 0);
	// Body
	wIn = wOut;
	if(!tt_bBoxOld)
		wIn -= ((tt_aV[PADDING] + tt_aV[BORDERWIDTH]) << 1);
	tt_aElt[5].style.width = wIn + "px";
	// Title
	if(tt_aElt[1])
	{
		wIn = wOut - (tt_aV[PADDING] << 1);
		if(!tt_bBoxOld)
			wOut = wIn;
		tt_aElt[1].style.width = wOut + "px";
		tt_aElt[2].style.width = wIn + "px";
	}
	tt_h = tt_GetDivH(tt_aElt[0]) + iOffY;
	// Right shadow
	if(tt_aElt[8])
		tt_aElt[8].style.height = (tt_h - iOffSh) + "px";
	i = tt_aElt.length - 1;
	if(tt_aElt[i])
	{
		tt_aElt[i].style.width = tt_w + "px";
		tt_aElt[i].style.height = tt_h + "px";
	}
}
function tt_DeAlt(el)
{
	var aKid;

	if(el.alt)
		el.alt = "";
	if(el.title)
		el.title = "";
	aKid = el.childNodes || el.children || null;
	if(aKid)
	{
		for(var i = aKid.length; i;)
			tt_DeAlt(aKid[--i]);
	}
}
// This hack removes the annoying native tooltips over links in Opera
function tt_OpDeHref(el)
{
	if(!tt_op)
		return;
	if(tt_elDeHref)
		tt_OpReHref();
	while(el)
	{
		if(el.hasAttribute("href"))
		{
			el.t_href = el.getAttribute("href");
			el.t_stats = window.status;
			el.removeAttribute("href");
			el.style.cursor = "hand";
			tt_AddEvtFnc(el, "mousedown", tt_OpReHref);
			window.status = el.t_href;
			tt_elDeHref = el;
			break;
		}
		el = el.parentElement;
	}
}
function tt_ShowInit()
{
	tt_aElt[0].style.display = "none";
	tt_tShow.Timer("tt_Show()", tt_aV[DELAY], true);
	if(tt_aV[CLICKCLOSE])
		tt_AddEvtFnc(document, "mouseup", tt_HideInit);
}
function tt_OverInit(e)
{
	tt_over = e.target || e.srcElement;
	tt_DeAlt(tt_over);
	tt_OpDeHref(tt_over);
	tt_AddRemOutFnc(true);
}
function tt_Show()
{
	var css = tt_aElt[0].style;

	// Override the z-index of the topmost wz_dragdrop.js D&D item
	css.zIndex = Math.max((window.dd && dd.z) ? (dd.z + 2) : 0, 1010);
	if(tt_aV[STICKY] || !tt_aV[FOLLOWMOUSE])
		tt_iState &= ~0x4;
	if(tt_aV[DURATION] > 0)
		tt_tDurt.Timer("tt_HideInit()", tt_aV[DURATION], true);
	tt_ExtCallFncs(0, "Show")
	css.visibility = "visible";
	css.display = "block";

	tt_iState |= 0x2;
	if(tt_aV[FADEIN])
		tt_Fade(0, 0, tt_aV[OPACITY], Math.round(tt_aV[FADEIN] / tt_aV[FADEINTERVAL]));
	tt_ShowIfrm();
}
function tt_ShowIfrm()
{
	if(tt_ie56)
	{
		var ifrm = tt_aElt[tt_aElt.length - 1];
		if(ifrm)
		{
			var css = ifrm.style;
			css.zIndex = tt_aElt[0].style.zIndex - 1;
			css.display = "block";
		}
	}
}
function tt_Move(e)
{
	e = window.event || e;
	if(e)
	{
		tt_musX = tt_GetEvtX(e);
		tt_musY = tt_GetEvtY(e);
	}

	if(tt_iState)
	{
		if(!tt_over && e)
			tt_OverInit(e);
		if(tt_iState & 0x4)
		{
			// Protect some browsers against jam of mousemove events
			if(!tt_op && !tt_ie)
			{
				if(tt_bWait)
					return;
				tt_bWait = true;
				tt_tWaitMov.Timer("tt_bWait = false;", 1, true);
			}

			if(tt_aV[FIX])
			{
				tt_iState &= ~0x4;
				tt_SetTipPos(tt_aV[FIX][0], tt_aV[FIX][1]);
			}
			else if(!tt_ExtCallFncs(e, "MoveBefore")){
				tt_SetTipPos(tt_PosX(), tt_PosY());
			}
			tt_ExtCallFncs([tt_musX, tt_musY], "MoveAfter")
		}
	}
}
function tt_PosX()
{
	var x;

	x = tt_musX;
	
	if(tt_aV[LEFT]){
		x -= tt_w + tt_aV[OFFSETX] - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0);
	}
	else{
		x += tt_aV[OFFSETX];
	}
	
	// Prevent tip from extending past right/left clientarea boundary
	if(x > tt_maxPosX)
		x = tt_maxPosX;

	return((x < tt_scrlX) ? tt_scrlX : x);
}
function tt_PosY()
{
	var y;

	// Apply some hysteresis after the tip has snapped to the other side of the
	// mouse. In case of insufficient space above and below the mouse, we place
	// the tip below.
	if(tt_aV[ABOVE] && (!tt_bJmpVert || tt_CalcPosYAbove() >= tt_scrlY + 16))
		y = tt_DoPosYAbove();
	else if(!tt_aV[ABOVE] && tt_bJmpVert && tt_CalcPosYBelow() > tt_maxPosY - 16)
		y = tt_DoPosYAbove();
	else
		y = tt_DoPosYBelow();
	// Snap to other side of mouse if tip would extend past window boundary
	if(y > tt_maxPosY)
		y = tt_DoPosYAbove();
	if(y < tt_scrlY)
		y = tt_DoPosYBelow();
	return y;
}
function tt_DoPosYBelow()
{
	tt_bJmpVert = tt_aV[ABOVE];
	return tt_CalcPosYBelow();
}
function tt_DoPosYAbove()
{
	tt_bJmpVert = !tt_aV[ABOVE];
	return tt_CalcPosYAbove();
}
function tt_CalcPosYBelow()
{
	return(tt_musY + tt_aV[OFFSETY]);
}
function tt_CalcPosYAbove()
{
	var dy = tt_aV[OFFSETY] - (tt_aV[SHADOW] ? tt_aV[SHADOWWIDTH] : 0);
	if(tt_aV[OFFSETY] > 0 && dy <= 0)
		dy = 1;
	return(tt_musY - tt_h - dy);
}
function tt_OnOut()
{
	tt_AddRemOutFnc(false);
	if(!(tt_aV[STICKY] && (tt_iState & 0x2)))
		tt_HideInit();
}
function tt_HideInit()
{	
	tt_aElt[0].style.display = "block";
	tt_ExtCallFncs(0, "HideInit");
	tt_iState &= ~0x4;
	if(tt_flagOpa && tt_aV[FADEOUT])
	{
		tt_tFade.EndTimer();
		if(tt_opa)
		{
			var n = Math.round(tt_aV[FADEOUT] / (tt_aV[FADEINTERVAL] * (tt_aV[OPACITY] / tt_opa)));
			tt_Fade(tt_opa, tt_opa, 0, n);
			return;
		}
	}
	tt_tHide.Timer("tt_Hide();", 1, false);
}
function tt_OpReHref()
{
	if(tt_elDeHref)
	{
		tt_elDeHref.setAttribute("href", tt_elDeHref.t_href);
		tt_RemEvtFnc(tt_elDeHref, "mousedown", tt_OpReHref);
		window.status = tt_elDeHref.t_stats;
		tt_elDeHref = null;
	}
}
function tt_Fade(a, now, z, n)
{
	if(n)
	{
		now += Math.round((z - now) / n);
		if((z > a) ? (now >= z) : (now <= z))
			now = z;
		else
			tt_tFade.Timer("tt_Fade("
							+ a + "," + now + "," + z + "," + (n - 1)
							+ ")",
							tt_aV[FADEINTERVAL],
							true);
	}
	now ? tt_SetTipOpa(now) : tt_Hide();
}
// To circumvent the opacity nesting flaws of IE, we set the opacity
// for each sub-DIV separately, rather than for the container DIV.
function tt_SetTipOpa(opa)
{
	tt_SetOpa(tt_aElt[5].style, opa);
	if(tt_aElt[1])
		tt_SetOpa(tt_aElt[1].style, opa);
	if(tt_aV[SHADOW])
	{
		opa = Math.round(opa * 0.8);
		tt_SetOpa(tt_aElt[7].style, opa);
		tt_SetOpa(tt_aElt[8].style, opa);
	}
}
function tt_OnCloseBtnOver(iOver)
{
	var css = tt_aElt[4].style;

	iOver <<= 1;
	css.background = tt_aV[CLOSEBTNCOLORS][iOver];
	css.color = tt_aV[CLOSEBTNCOLORS][iOver + 1];
}
function tt_Int(x)
{
	var y;

	return(isNaN(y = parseInt(x)) ? 0 : y);
}
// Adds or removes the document.mousemove or HoveredElem.mouseout handler
// conveniently. Keeps track of those handlers to prevent them from being
// set or removed redundantly.
function tt_AddRemOutFnc(bAdd)
{
	var PSet = bAdd ? tt_AddEvtFnc : tt_RemEvtFnc;

	if(bAdd != tt_AddRemOutFnc.bOn)
	{
		PSet(tt_over, "mouseout", tt_OnOut);
		tt_AddRemOutFnc.bOn = bAdd;
		if(!bAdd)
			tt_OpReHref();
	}
}
tt_AddRemOutFnc.bOn = false;
Number.prototype.Timer = function(s, iT, bUrge)
{
	if(!this.value || bUrge){
		this.value = window.setTimeout(s, iT);
	}
}
Number.prototype.EndTimer = function()
{
	if(this.value)
	{
		window.clearTimeout(this.value);
		this.value = 0;
	}
}
function tt_SetOpa(css, opa)
{
	tt_opa = opa;
	if(tt_flagOpa == 1)
	{
		// Hack for bugs of IE:
		// A DIV cannot be made visible in a single step if an opacity < 100
		// has been applied while the DIV was hidden.
		// Moreover, in IE6, applying an opacity < 100 has no effect if the
		// concerned element has no layout (position, size, zoom, ...).
		if(opa < 100)
		{
			var bVis = css.visibility != "hidden";
			css.zoom = "100%";
			if(!bVis)
				css.visibility = "visible";
			css.filter = "alpha(opacity=" + opa + ")";
			if(!bVis)
				css.visibility = "hidden";
		}
		else
			css.filter = "";
	}
	else
	{
		opa /= 100.0;
		switch(tt_flagOpa)
		{
		case 2:
			css.KhtmlOpacity = opa; break;
		case 3:
			css.KHTMLOpacity = opa; break;
		case 4:
			css.MozOpacity = opa; break;
		case 5:
			css.opacity = opa; break;
		}
	}
}
function tt_MovDomNode(el, dadFrom, dadTo)
{
	if(dadFrom)
		dadFrom.removeChild(el);
	if(dadTo)
		dadTo.appendChild(el);
}
function tt_Err(sErr)
{
	if(tt_Debug)
		alert("Tooltip Script Error Message:\n\n" + sErr);
}

//===========  DEALING WITH EXTENSIONS	==============//
function tt_ExtCmdEnum()
{
	var s;

	// Add new command(s) to the commands enum
	for(var i in config)
	{
		s = "window." + i.toString().toUpperCase();
		if(eval("typeof(" + s + ") == tt_u"))
		{
			eval(s + " = " + tt_aV.length);
			tt_aV[tt_aV.length] = null;
		}
	}
}
function tt_ExtCallFncs(arg, sFnc)
{
	var b = false;
	for(var i = tt_aExt.length; i;)
	{--i;
		var fnc = tt_aExt[i]["On" + sFnc];
		// Call the method the extension has defined for this event
		if(fnc && fnc(arg))
			b = true;
	}
	return b;
}

function getEvent(){ //同时兼容ie和ff的写法
	if(window.event) return window.event;
	func=getEvent.caller;
	while(func!=null){
		var arg0=func.arguments[0];
		if(arg0){
			if((arg0.constructor==Event || arg0.constructor ==MouseEvent)
			|| (typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation)){
			return arg0;
			}
		}
		func=func.caller;
	}
	return null;
} 


(function($,UI,window,undefined){
	if(typeof $ == 'undefined' || typeof UI == 'undefined') return;
	$.tooltip = function(options){
		var defaults = {
			container:document,
			selector:'[data-tip]'
		}

		var option = $.extend(defaults,options);

		return $(option.selector,$(option.container)).each(function(){
			var tipType = '' , tipMsg ='';
			if($(this).hasClass('tip_error')){
				tipType = 'ns-tooltip-err';
			}
			tipMsg = $(this).attr('data-tip');

			var tipHtml = '<div class="ns-tooltip ' + tipType + '"><div class="ns-tooltip-cont">' + tipMsg + '</div><div class="ns-tooltip-caret"></div></div>';	
			$(this).before(tipHtml);

			$(this).focus(function(){
				var offsetTop = UI.getY(this);
				var scrollTop = UI.scrollY(this);
				var outerHeight = $(this).outerHeight(true) ;
				var tooltip = $(this).parent().find('.ns-tooltip');

				if(offsetTop<60 || (scrollTop - offsetTop)>0 || ((offsetTop - scrollTop)<40 && offsetTop > scrollTop)){
					tooltip.addClass('bottom');
					tooltip.find('.ns-tooltip-cont').css({top:(outerHeight + 9) + 'px'});
					tooltip.find('.ns-tooltip-caret').css({top:(outerHeight - 6)+'px'});
				}
				else{
					tooltip.removeClass('bottom');
					tooltip.find('.ns-tooltip-cont').css({top:'auto'});
					tooltip.find('.ns-tooltip-caret').css({top:'-9px'});
				}
				$(this).parent().find('.ns-tooltip').show();
			}).blur(function(){
				$(this).parent().find('.ns-tooltip').hide();
			})	
		})
		
	}

})(window.jQuery,window.UI,window);


;(function(jQuery,UI,window){
	if(typeof jQuery == 'undefined' || typeof UI == 'undefined') return;
	var dialogConfWizard = top.window['dialogConfWizard'] = new top.UI.Dialog({
		name:'dialogConfWizard'});

	jQuery.fn.ConfWizard = function(o){

		var option = jQuery.extend(jQuery.fn.ConfWizard.defaults,o);
		jQuery(dialogConfWizard._iframe).parent().data('current',0);
		jQuery(dialogConfWizard._iframe).parent().data('option',option);

		var getCurrentWizard = top.getCurrentWizard = function(){
			var op = jQuery(dialogConfWizard._iframe).parent().data('option') ||{};
			op.current = (jQuery(dialogConfWizard._iframe).parent().data('current') | 0)
			op.data = op.data || [];
			return op;
		}

		var setCurrentWizard = top.setCurrentWizard = function(current){
			jQuery(dialogConfWizard._iframe).parent().data('current', current);
		}

		var setBtnStateWizard = top.setBtnStateWizard = function (){
			var option = getCurrentWizard();
			var current = option.current;

			if(current == 0){
				jQuery('.js-prev',jQuery(dialogConfWizard._iframe).parent()).hide();
				jQuery('.js-ok',jQuery(dialogConfWizard._iframe).parent()).hide();
				jQuery('.js-next',jQuery(dialogConfWizard._iframe).parent()).removeAttr('disabled').addClass('cmn_btn_focus').show();
			}
			else if(current == option.data.length-1){
				jQuery('.js-next',jQuery(dialogConfWizard._iframe).parent()).removeAttr('disabled').hide();
				jQuery('.js-ok',jQuery(dialogConfWizard._iframe).parent()).show();
				jQuery('.js-prev',jQuery(dialogConfWizard._iframe).parent()).show();
			}
			else{
				jQuery('.js-next',jQuery(dialogConfWizard._iframe).parent()).removeAttr('disabled').show();
				jQuery('.js-ok',jQuery(dialogConfWizard._iframe).parent()).hide();
				jQuery('.js-prev',jQuery(dialogConfWizard._iframe).parent()).show().removeClass('cmn_btn_focus');
			}

			if(option.data[current] && option.data[current].skip){
				jQuery('.js-skip',jQuery(dialogConfWizard._iframe).parent()).show();
			}
			else{
				jQuery('.js-skip',jQuery(dialogConfWizard._iframe).parent()).hide();
			}
		}

		
		var prevHandlerWizard = top.prevHandlerWizard = function(obj){
			var option = getCurrentWizard();
			var current = option.current;

			if(current>0){
				jQuery(obj).parentsUntil('.data').parent().find(".nm-confwizard-tabheader tr>td").removeClass("tab_selected")
				.addClass("tab_unselected").eq(current-1).removeClass("tab_unselected").addClass("tab_selected");
				setCurrentWizard(--current);
				setBtnStateWizard();
				loadContent(option.data[current],{prev:true});
			}
		}
		

		var nextHandlerWizard = top.nextHandlerWizard =function(obj){
			var option = getCurrentWizard();
			var current = option.current;

			if(current<option.data.length-1){
				var flag = true;

				if(typeof top[option.data[current].nextHandler] == 'function'){
					flag = top[option.data[current].nextHandler].call(obj);
				}

				if(flag){
					jQuery(obj).parentsUntil('.data').parent().find(".nm-confwizard-tabheader tr>td").removeClass("tab_selected")
					.addClass("tab_unselected").eq(current+1).removeClass("tab_unselected").addClass("tab_selected");
					setCurrentWizard(++current);
					setBtnStateWizard();
					loadContent(option.data[current]);
				}
			}
		}

		var okHandlerWizard = top.okHandlerWizard =function(obj){
			var option = getCurrentWizard();
			var current = option.current;

			var flag = true;
			if(typeof top[option.data[current].nextHandler] == 'function'){
				flag = top[option.data[current].nextHandler].call(obj);
			}
			if(flag){
				dialogConfWizard.hide();
			}
			
		}

		var skipHandlerWizard = top.skipHandlerWizard = function(obj){
			var option = getCurrentWizard();
			var current = option.current;

			if(current<option.data.length-1){
				jQuery(obj).parentsUntil('.data').parent().find(".nm-confwizard-tabheader tr>td").removeClass("tab_selected")
				.addClass("tab_unselected").eq(current+1).removeClass("tab_unselected").addClass("tab_selected");
				setCurrentWizard(++current);
				setBtnStateWizard();
				loadContent(option.data[current]);
			}
		}
		

		var loadContent = top.loadContent = function(option,data){
			jQuery.ajax({
				url:option.url,
				context:top,
				data:data||{prev:false},
				dataType:'html',
				success:function(data){
					/**clear cache function
					*if don't do this:if step2 page has initHandler function,press 'prev' btn back to 
					*step1 page initHandler function is still there. but actually step1 page has no initHandler function
					*/
					top[option.initHandler] = null;
					top.jQuery('.content',top.jQuery(dialogConfWizard._iframe).parent()).html(data);

					if(typeof top[option.initHandler] == 'function'){
						top[option.initHandler].call(top,jQuery('.js-prev',jQuery(dialogConfWizard._iframe).parent()),
							jQuery('.js-next',jQuery(dialogConfWizard._iframe).parent()),
							jQuery('.js-skip',jQuery(dialogConfWizard._iframe).parent()),
							jQuery('.js-ok',jQuery(dialogConfWizard._iframe).parent()));
					}
				}
			})
		}

		return this.each(function(){
			var tabsHtml = '';
			var container = jQuery('<div class="nm-confwizard"></div>');

			var dataOption='';
			jQuery.each(option.data,function(i){
				var selectClass = 'tab_unselected';
				if(i==0){
					selectClass = 'tab_selected';
					dataOption = this;
				}

				tabsHtml+='<tr>\
			                  <td class="'+selectClass+'"><a  href="###">'+this.title+'</a></td>\
			                </tr>';
			})

			var verticalTabHtml = '<div class="data_cont nm-confwizard-container">\
									<table cellspacing="0" cellpadding="0" width="100%" height="100%" class="inner vertical">\
							        <tbody><tr>\
							          	<td valign="top" class="tab_cell">\
							          	<div class="cmn_tab nm-confwizard-tabheader">\
							              <table>\
							                <tbody>\
							                '+tabsHtml+'\
							              	</tbody>\
							              </table>\
							            </td>\
							          	<td class="vertical_right" style="width:100%;" valign="top">\
								          	<table class="tab_border" style="width:100%;">\
											<tbody>\
												<tr>\
												<td valign="top">\
													<div class="content nm-confwizard-content"></div>\
												</td>\
												</tr>\
											</tbody></table>\
							            </td>\
							        </tr>\
							      	</tbody></table></div>\
							      	<div class="button nm-confwizard-btn">\
										<input type="button" class="cmn_btn js-prev" onclick="prevHandlerWizard(this);" value="'+option.prevStep+'">\
										<input type="button" class="cmn_btn js-next" onclick="nextHandlerWizard(this);" value="'+option.nextStep+'">\
										<input type="button" class="cmn_btn cmn_btn_focus js-ok" onclick="okHandlerWizard(this);" value="'+option.ok+'">\
										<input type="button" class="cmn_btn js-skip" onclick="skipHandlerWizard(this);" value="'+option.skip+'">\
									</div>';
			container.append(jQuery(verticalTabHtml));

		    dialogConfWizard.show({title:option.title,html:container.html(),width:option.width,height:option.height});
		    top.loadContent(dataOption);
		    top.setBtnStateWizard();
		});						  
	}

	jQuery.fn.ConfWizard.defaults={
		prevStep:'上一步',
		nextStep:'下一步',
		confirm:'完成',
		skip:'跳过',
		cancel:'取消',
		ok:'完成',
		title:' ',
		width:610,height:572,
		data:[]
	};

	jQuery.ConfWizard = function(o){
		jQuery('body').ConfWizard(o);
	}
}(window.jQuery,window.UI,window));
/*
 author:gq
 date:2014-12-03 
 base:bootstrap 2
 useage:add attr data-toggle
 */

!function ($) {
  if(typeof $ == 'undefined') return;
 // jshint ;_;


 /* BUTTON PUBLIC CLASS DEFINITION
  * ============================== */

  var Button = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, $.fn.button.defaults, options)
  }

  Button.prototype.setState = function (state) {
    var d = 'disabled'
      , $el = this.$element
      , data = $el.data()
      , val = $el.is('input') ? 'val' : 'html'

    state = state + 'Text'
    data.resetText || $el.data('resetText', $el[val]())

    $el[val](data[state] || this.options[state])

    // push to event loop to allow forms to submit
    setTimeout(function () {
      state == 'loadingText' ?
        $el.addClass(d).attr(d, d) :
        $el.removeClass(d).removeAttr(d)
    }, 0)
  }

  Button.prototype.toggle = function () {
    var $parent = this.$element.closest('[data-toggle="buttons-radio"]')

    $parent && $parent
      .find('.active')
      .removeClass('active')

    this.$element.toggleClass('active');
  }


 /* BUTTON PLUGIN DEFINITION
  * ======================== */

  var old = $.fn.button

  $.fn.button = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('button')
        , options = typeof option == 'object' && option
      if (!data) $this.data('button', (data = new Button(this, options)))
      if (option == 'toggle') data.toggle()
      else if (option) data.setState(option)
    })
  }

  $.fn.button.defaults = {
    loadingText: 'loading...'
  }

  $.fn.button.Constructor = Button


 /* BUTTON NO CONFLICT
  * ================== */

  $.fn.button.noConflict = function () {
    $.fn.button = old
    return this
  }


 /* BUTTON DATA-API
  * =============== */

  $(document).on('click.button.data-api', '[data-toggle^=button]', function (e) {
    var $btn = $(e.target)
    if (!$btn.hasClass('nm-btn')) $btn = $btn.closest('.nm-btn')
    $btn.button('toggle')
  })

}(window.jQuery);
/*
    json2.js
    2014-02-04

    Public Domain.

    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

    See http://www.JSON.org/js.html


    This code should be minified before deployment.
    See http://javascript.crockford.com/jsmin.html

    USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
    NOT CONTROL.


    This file creates a global JSON object containing two methods: stringify
    and parse.

        JSON.stringify(value, replacer, space)
            value       any JavaScript value, usually an object or array.

            replacer    an optional parameter that determines how object
                        values are stringified for objects. It can be a
                        function or an array of strings.

            space       an optional parameter that specifies the indentation
                        of nested structures. If it is omitted, the text will
                        be packed without extra whitespace. If it is a number,
                        it will specify the number of spaces to indent at each
                        level. If it is a string (such as '\t' or '&nbsp;'),
                        it contains the characters used to indent at each level.

            This method produces a JSON text from a JavaScript value.

            When an object value is found, if the object contains a toJSON
            method, its toJSON method will be called and the result will be
            stringified. A toJSON method does not serialize: it returns the
            value represented by the name/value pair that should be serialized,
            or undefined if nothing should be serialized. The toJSON method
            will be passed the key associated with the value, and this will be
            bound to the value

            For example, this would serialize Dates as ISO strings.

                Date.prototype.toJSON = function (key) {
                    function f(n) {
                        // Format integers to have at least two digits.
                        return n < 10 ? '0' + n : n;
                    }

                    return this.getUTCFullYear()   + '-' +
                         f(this.getUTCMonth() + 1) + '-' +
                         f(this.getUTCDate())      + 'T' +
                         f(this.getUTCHours())     + ':' +
                         f(this.getUTCMinutes())   + ':' +
                         f(this.getUTCSeconds())   + 'Z';
                };

            You can provide an optional replacer method. It will be passed the
            key and value of each member, with this bound to the containing
            object. The value that is returned from your method will be
            serialized. If your method returns undefined, then the member will
            be excluded from the serialization.

            If the replacer parameter is an array of strings, then it will be
            used to select the members to be serialized. It filters the results
            such that only members with keys listed in the replacer array are
            stringified.

            Values that do not have JSON representations, such as undefined or
            functions, will not be serialized. Such values in objects will be
            dropped; in arrays they will be replaced with null. You can use
            a replacer function to replace those with JSON values.
            JSON.stringify(undefined) returns undefined.

            The optional space parameter produces a stringification of the
            value that is filled with line breaks and indentation to make it
            easier to read.

            If the space parameter is a non-empty string, then that string will
            be used for indentation. If the space parameter is a number, then
            the indentation will be that many spaces.

            Example:

            text = JSON.stringify(['e', {pluribus: 'unum'}]);
            // text is '["e",{"pluribus":"unum"}]'


            text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
            // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

            text = JSON.stringify([new Date()], function (key, value) {
                return this[key] instanceof Date ?
                    'Date(' + this[key] + ')' : value;
            });
            // text is '["Date(---current time---)"]'


        JSON.parse(text, reviver)
            This method parses a JSON text to produce an object or array.
            It can throw a SyntaxError exception.

            The optional reviver parameter is a function that can filter and
            transform the results. It receives each of the keys and values,
            and its return value is used instead of the original value.
            If it returns what it received, then the structure is not modified.
            If it returns undefined then the member is deleted.

            Example:

            // Parse the text. Values that look like ISO date strings will
            // be converted to Date objects.

            myData = JSON.parse(text, function (key, value) {
                var a;
                if (typeof value === 'string') {
                    a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                    if (a) {
                        return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                            +a[5], +a[6]));
                    }
                }
                return value;
            });

            myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
                var d;
                if (typeof value === 'string' &&
                        value.slice(0, 5) === 'Date(' &&
                        value.slice(-1) === ')') {
                    d = new Date(value.slice(5, -1));
                    if (d) {
                        return d;
                    }
                }
                return value;
            });


    This is a reference implementation. You are free to copy, modify, or
    redistribute.
*/

/*jslint evil: true, regexp: true */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
    call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
    getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
    lastIndex, length, parse, prototype, push, replace, slice, stringify,
    test, toJSON, toString, valueOf
*/


// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

if (typeof JSON !== 'object') {
    JSON = {};
}

(function () {
    'use strict';

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function () {

            return isFinite(this.valueOf())
                ? this.getUTCFullYear()     + '-' +
                    f(this.getUTCMonth() + 1) + '-' +
                    f(this.getUTCDate())      + 'T' +
                    f(this.getUTCHours())     + ':' +
                    f(this.getUTCMinutes())   + ':' +
                    f(this.getUTCSeconds())   + 'Z'
                : null;
        };

        String.prototype.toJSON      =
            Number.prototype.toJSON  =
            Boolean.prototype.toJSON = function () {
                return this.valueOf();
            };
    }

    var cx,
        escapable,
        gap,
        indent,
        meta,
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string'
                ? c
                : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0
                    ? '[]'
                    : gap
                    ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']'
                    : '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0
                ? '{}'
                : gap
                ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}'
                : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        };
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function'
                    ? walk({'': j}, '')
                    : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());
