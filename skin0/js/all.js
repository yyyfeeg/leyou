var PopDialog=function(){function a(a){setThemeConfig=!0,setThemeConfig&&(setTheme=a.theme)}function e(a){if(lockFlag){lockFlag=!1,options.quickCallback="",options.cancleCallback="",options.confirmCallback="",options=$.extend(options,a),mouldClass=a.theme?"alert_box_"+a.theme:setThemeConfig?"alert_box_"+setTheme:"";var e=Templ[a.type];e=e.replace(/\$theme\$/gi,mouldClass).replace(/\$placeholder\$/gi,options.placeholder).replace(/\$msg\$/gi,options.msg).replace(/\$cancle\$/gi,options.cancleTxt).replace(/\$confirm\$/gi,options.confirmTxt),$("body").append(e),$("#alertBox").show(),setTimeout(function(){$("#alertBox").addClass("show"),$.browser.msie&&9>=parseInt($.browser.version)&&n($("#alertBox"))},100);var i=$("#alertBox .alert_wrap"),c=i.height();i.css({marginTop:-(c/2)+"px"}),t(options.confirmCallback),l(options.cancleCallback),o()}}function n(a){a.find("input").each(function(a,e){var n=$(e);n.attr("placeholder")&&n.val(n.attr("placeholder")).addClass("labelPlaceholder"),n.on("blur",function(){""===n.val()&&n.val(n.attr("placeholder")).addClass("labelPlaceholder")}),n.on("focus",function(){n.attr("placeholder")===n.val()&&n.val("").removeClass("labelPlaceholder")})})}function t(a){$("#alertBox .btnsConfirm").click(function(){"confirm"==options.type?quickFlag=!0:"prompt"==options.type&&(quickFlag=$("#alertBox input").val()),i(a)})}function l(a){$("#alertBox .btnsCancle").click(function(){"confirm"==options.type?quickFlag=!1:"prompt"==options.type&&(quickFlag=null),i(a)})}function o(){$("#alertBox .alertClose").click(function(){i()})}function i(a){$("#alertBox").removeClass("show"),void 0==s?($("#alertBox").remove(),lockFlag=!0):setTimeout(function(){$("#alertBox").remove(),lockFlag=!0},200),"function"==typeof a&&a(quickFlag),"function"==typeof options.quickCallback&&options.quickCallback(quickFlag)}function c(a,e,n){var t={};return"string"==typeof a?(t.msg=a,"string"==typeof e?t.placeholder=e:"function"==typeof e&&(t.quickCallback=e),"function"==typeof n&&(t.quickCallback=n)):t=a,t}var s=document.createElement("div").style.opacity;return setThemeConfig=!1,setTheme="",lockFlag=!0,mouldClass="",quickFlag="",options={confirmTxt:"\u786e  \u5b9a",cancleTxt:"\u53d6  \u6d88",placeholder:""},Templ={alert:'<div class="alert_box $theme$" id="alertBox">\n	<div class="shadow"></div>\n	<div class="alert_wrap">\n		<a href="javascript:;" class="alertClose"><i></i><em></em></a>\n		<div class="alert_main">\n			<div class="alert_content">$msg$</div>\n			<div class="btns_wrap">\n				<a href="javascript:;" class="btns btnsConfirm">$confirm$</a>\n			</div>\n		</div>\n	</div>\n</div>',confirm:'<div class="alert_box $theme$" id="alertBox">\n	<div class="shadow"></div>\n	<div class="alert_wrap">\n		<a href="javascript:;" class="alertClose"><i></i><em></em></a>\n		<div class="alert_main">\n			<div class="alert_content">$msg$</div>\n			<div class="btns_wrap">\n				<a href="javascript:;" class="btns btnsCancle">$cancle$</a>\n				<a href="javascript:;" class="btns btnsConfirm">$confirm$</a>\n			</div>\n		</div>\n	</div>\n</div>',prompt:'<div class="alert_box $theme$" id="alertBox">\n	<div class="shadow"></div>\n	<div class="alert_wrap">\n		<a href="javascript:;" class="alertClose"><i></i><em></em></a>\n		<div class="alert_main">\n			<div class="alert_content">\n				<p class="tit">$msg$</p>\n				<input type="text" class="text" placeholder="$placeholder$">\n			</div>\n			<div class="btns_wrap">\n				<a href="javascript:;" class="btns btnsCancle">$cancle$</a>\n				<a href="javascript:;" class="btns btnsConfirm">$confirm$</a>\n			</div>\n		</div>\n	</div>\n</div>'},{config:function(e){a(e)},Close:function(){i()},Alert:function(a){var n=c(a);n.type="alert",e(n)},Confirm:function(a,n){var t=c(a,n);t.type="confirm",e(t)},Prompt:function(a,n,t){var l=c(a,n,t);l.type="prompt",e(l)}}}();
var NieDownload=function(n){var t=/iphone|ios|ipod/i.test(navigator.userAgent.toLowerCase()),e=/ipad/i.test(navigator.userAgent.toLowerCase()),o=/android/i.test(navigator.userAgent.toLowerCase()),i=function(t,e,o){t.find(e).length>0&&(n.addEventListener?t.find(e)[0].addEventListener("click",function(){"function"==typeof o?o():alert("\u656c\u8bf7\u671f\u5f85\uff01")},!1):t.find(e)[0].attachEvent("onclick",function(){"function"==typeof o?o():alert("\u656c\u8bf7\u671f\u5f85\uff01")}))},d=function(n,d,a){t&&!e?a.enableIos?$(n).find(".NIE-download").attr("href",d):i($(n),".NIE-download",a.disableClick):o?a.enableAndroid?$(n).find(".btn_download").attr("href",d):i($(n),".btn_download",a.disableClick):(a.enableAndroid?$(n).find(".NIE-button-android").attr("href",d+"?type=android"):i($(n),".NIE-button-android",a.disableClick),a.enableIos?$(n).find(".NIE-button-ios").attr("href",d+"?type=ios"):i($(n),".NIE-button-ios",a.disableClick))},a=function(n){var t,e=n.wrapper,o=e.attr("data-download"),i="https://adl.netease.com/d/g/"+o.split("|")[0]+"/c/"+o.split("|")[1];t=n.qrcode?n.qrcode:i+"/qr",e.each(function(e,o){$(o).find(".NIE-qrcode").append("<img src='"+t+"' />"),d(o,i,n)})};return{create:a}}(window,document,void 0);
nie.util.share_traceCome=nie.util.share_traceCome||!1,nie.util.share_css=nie.util.share_css||!1,function(e){nie.util.share=nie.util.share||function(){var t={data:{},args:{panelID:"NIE-share-panel",fat:"#NIE-share",product:nie.config.site,imgs:null,txt:null,type:1,traceType:null,imgSize:[100,100],defShow:[23,22,2,1],defShow2:[23,22,2,1],moreShow:[23,22,2,1,24],searchTips:"������վ����ƴ����д",sideBar_top:100,title:null,url:null,img:null,content:null,urlReg:new RegExp("([&?]nieShare=)(\\d+),([^,]+),(\\d+)")}},a=function(){var a={};return a._href="javascript:;",a.chkDefault=function(e,t){return"undefined"!=typeof e?e:t},a.loadImg=function(t){e(new Image).bind("readystatechange",function(){"complete"==this.readyState}).bind("abort",function(){}).attr("src",t)},a.chkDatas=function(e,a,n,i){var o=null!=t.args[e]?t.args[e]:n;return""==o?o:("url"==e&&24!=a&&(o=this.addUrlMark(o,a)),encodeURIComponent(o+("undefined"!=typeof i?i:"")))},a.combind=function(e){var t=[];for(var a in e)t.push(a+"="+e[a]);return t},a.track=function(e){var a=["product="+t.args.product,"id="+e,"type="+t.args.traceType];this.loadImg("http://click.ku.163.com/share.v2.gif?"+a.join("&"))},a.aObj=function(e){var t=document.createElement("div");return t.innerHTML='<a href="'+e.replace(/"/g,"%22")+'"/>',t.firstChild},a.addUrlMark=function(e,a){var n=e.match(t.args.urlReg);if(n)return e.replace(n[0],n[1]+[a,t.args.product,t.args.traceType].join(","));var i=this.aObj(e),o=i.search+(""==i.search?"?":"&")+"nieShare="+[a,t.args.product,t.args.traceType].join(","),r=/^\//.test(i.pathname)?i.pathname:"/"+i.pathname,s=80==i.port?i.hostname:i.host;return i.protocol+"//"+s+r+o+i.hash},a.jump=function(e){if(t.data[e]){var a=t.data[e],n=this.chkDatas("url",e,location.href),i=this.chkDatas("title",e,document.title),o=this.chkDatas("img",e,""),r=this.chkDatas("content",e,document.title),s=function(){var t=[650,500];return 22==e?t=[500,480]:23==e?t=[573,600]:24==e&&(t=[750,550]),t}(),c=this.combind({width:s[0],height:s[1],top:(screen.height-s[1])/4,left:(screen.width-s[0])/2,toolbar:"no",menubar:"no",scrollbars:"no",resizable:"yes",location:"no",status:"no"});for(var h in a.paramName){var u="";switch(parseInt(h)){case 1:u=n;break;case 2:u=5==e?i+n:i;break;case 3:u=o;break;case 4:u=r;break;case 5:u=encodeURIComponent(nie.util.siteName())}a.params[a.paramName[h]]=u}var l="http://"+a.file+"?"+this.combind(a.params).join("&");window.open(l,"_blank",c.join(","))}},a.fontVal={1:"&#xe602;",2:"&#xe604;",3:"&#xe605;",8:"&#xe603;",11:"&#xe6f0;",14:"&#xe6ad;",22:"&#xe607;",23:"&#xe606;",24:"&#xe900;",more:"&#xe601;",qcode:"&#xe600;"},a}(),n={addBtn:function(n,i,o,r,s,c){var h="",u="",n=parseInt(n),l="������",d="",f="",p=0,m=20,g={1:!0,2:!0,22:!0,23:!0,24:!0};if(g[n]){switch(a.chkDefault(c,t.args.type)){case 1:p=-(i-20+5*(n-1)+20*(n-1)),22==n?p=-325:23==n&&(p=-350);break;case 2:p=40*-(n-1)-80,22==n?p=-40:23==n&&(p=0),m=36;break;case 3:p=-(i-16+6*(n+1)+16*(n+1))}var v="background-position:"+o+"px "+p+"px";1==s?(h=t.data[n].name,u=l+h,f=v):0==s?(u=l+t.data[n].name,d=""):(h=s,f=v);var w={index:n,"class":"iconfont",style:"",href:a._href,title:u,target:"_self"};""==d?w.text=h:w.html=d;var T=e("<a>",w).addClass("NIE-share-btn"+n).appendTo(e("<li></li>").appendTo(r));return a.chkDefault(c,t.args.type)<=2&&0==s&&T.html(a.fontVal[n]),T.click(function(){a.jump(n)}),T}},addPanel:function(){var a=e("<div>",{id:t.args.panelID,html:"<h3>������...</h3><input type=text value='"+t.args.searchTips+"'/><div></div>"}).hide().appendTo(e(document.body));e("<button>").click(function(){a.hide()}).hover(function(){e(this).addClass("hover")},function(){e(this).removeClass("hover")}).appendTo(a);var n=a.find("div");for(var i in t.data)this.addBtn(i,12,5,n,!0,1);a.find("input").click(function(){var a=e(this),n=a.val();n==t.args.searchTips&&a.val("")}).keyup(function(){var a=e(this).val().toLowerCase();n.find("a").each(function(){var n=e(this),i=n.attr("index");-1!=t.data[i].name.toLowerCase().indexOf(a)||-1!=t.data[i].searchTxt.toLowerCase().indexOf(a)||-1!=t.data[i].searchTxt.replace(/[a-z]/g,"").toLowerCase().indexOf(a)?n.show():n.hide()})})},showPanel:function(){var a="#"+t.args.panelID,n=e(a),i=e(window);0==n.length&&this.addPanel(),n=e(a);var o=i.scrollTop()+(i.height()-n.height())/2;n.css({top:0>o?0:o,left:(i.width()-n.width())/2}).show().find("a").show()}},i=[[23,"΢�Ŷ�ά��","YiXinQrCode","res.nie.netease.com/comm/js/nie/util/share/api/index.html",{1:"url",2:"title"}],[22,"����","YiXin","open.yixin.im/share",{1:"url",2:"desc",3:"pic",4:"title"},{appkey:"yx3ae08a776bf04178a583cb745fb6aa0c",type:"webpage"}],[1,"QQ�ռ�","QQKongJian","sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey",{1:"url",2:"title",3:"pics",4:"desc"}],[2,"����΢��","XinLangWeiBo","v.t.sina.com.cn/share/share.php",{1:"url",2:"title",3:"pic"},{c:"nie",content:"gb2312",source:"nie"}],[24,"QQ����","TXQQ","connect.qq.com/widget/shareqq/index.html",{1:"url",2:"title",3:"pics",4:"summary"}]],o=function(){function i(i){var o=e(t.args.fat);if(!o.length||o.length<=0)return!1;var r=e("<div>",{"class":"NIE-share NIE-share"+i,html:"<span class='NIE-share-txt'>������:</span>"}).appendTo(o),s=e("<ul>",{"class":"NIE-share-iconBtn"}).appendTo(r);e("<div>",{"class":"NIE-share-clearfix"}).appendTo(r),e("<div>",{"class":"NIE-share-clearfix"}).appendTo(o),e.each(t.args.defShow,function(){n.addBtn(this,20,0,s,!1)});var c,h=e("<li>").appendTo(s),u=e("<a>",{"class":"iconfont morebtn",href:"javascript:void(0)",html:a.fontVal.more}).appendTo(h),l=!0,d=e("<ul>").appendTo(e("<div>",{"class":"NIE-share-more"}).appendTo(r)),f=[];e.each(t.args.moreShow,function(e,a){for(var n=0;n<t.args.defShow.length&&a!=t.args.defShow[n];n++);n>=t.args.defShow.length&&f.push(a)}),e.each(f,function(){n.addBtn(this,20,5,d,!0,1)}),f.length<1&&u.hide(),1==f.length&&d.css({width:"99px"}),u.mouseenter(function(){clearTimeout(c),l=!0;var t=e(window),a=d.outerHeight(),n=d.outerWidth(),i=r.offset(),o=e(this).width()>30?2:1;d.css({top:t.scrollTop()+t.height()<i.top+a+20?-a-12:-10*o,left:t.scrollLeft()+t.width()<i.left+r.width()+n?-n+42:-10*o}).fadeIn("fast")}).mouseleave(function(){clearTimeout(c),l=!1,c=setTimeout(function(){l||d.fadeOut("fast")},500)}),d.mouseenter(function(){clearTimeout(c),l=!0}).mouseleave(function(){clearTimeout(c),l=!1,c=setTimeout(function(){l||d.fadeOut("fast")},500)})}function o(){var a,i="NIE-share-sideBar",o=!1,r=!1,s=500,c=e("<div>",{id:i,html:"<div class='sideBarShareBox'><b></b><div><button></button><h3>������...</h3><ul></ul></div></div>",mouseleave:function(){o&&(clearTimeout(a),r=!1,a=setTimeout(function(){r||(c.animate({width:30},s),u.animate({right:-120},s,function(){o=!1}))},2*s))},mouseenter:function(){o&&(clearTimeout(a),r=!0)}}).appendTo(e(document.body)),h=c.find("ul"),u=c.find(".sideBarShareBox"),l=function(){c.css("top",e(window).scrollTop()+t.args.sideBar_top)};e.each(t.args.moreShow,function(){n.addBtn(this,20,5,h,!0,1)}),c.find("b").hover(function(){o||(o=!0,c.animate({width:150},s),u.animate({right:0},s))}),c.find("button").click(function(){u.animate({right:-150},s,function(){c.remove()})}).hover(function(){e(this).addClass("hover")},function(){e(this).removeClass("hover")}),e.browser.msie&&e.browser.msie<=6?(e(window).scroll(l),l()):c.css({position:"fixed",top:t.args.sideBar_top})}function r(){var n,i="NIE-share-img",o=e("#"+i),r=o.length>0,s=500,c=e(t.args.imgs),u=!1,l=function(e){return t.args.imgSize[0]<e.outerWidth()&&t.args.imgSize[1]<e.outerHeight()};c.hover(function(){var i=e(this),r=i.outerHeight(),s=i.offset(),c=10,h=e(window),d=o.outerHeight(),f=Math.max(s.top,this.offsetTop);l(i)&&(clearTimeout(n),u=!0,t.args.img=a.aObj(i.attr("src")).href,o.find(".NIE-share-more>div").hide(),o.css({top:f+r>h.scrollTop()+h.height()?f+c:f+r-c-d,left:s.left+c}).show())},function(){var t=e(this);l(t)&&(u=!1,clearTimeout(n),n=setTimeout(function(){u||o.hide()},s))}),t.args.defShow=t.args.defShow2,r||(o=e("<div>",{id:i,mouseenter:function(){clearTimeout(n),u=!0},mouseleave:function(){u=!1,clearTimeout(n),n=setTimeout(function(){u||o.hide()},s)}}).appendTo(e(document.body)),t.args.type=1,t.args.fat=o,h())}function s(){var a="NIE-share-txt",n=e("#"+a),i=n.length>0,o=!1,r=e(t.args.txt),s=function(){var t="";return window.getSelection?t=window.getSelection():document.getSelection?t=document.getSelection():document.selection&&(t=document.selection.createRange().text),e.trim(t)};t.args.defShow=t.args.defShow2,i||(n=e("<div>",{id:a}).appendTo(e(document.body)),r.mouseup(function(a){setTimeout(function(){var i=e.trim(s());o&&""!=i?(t.args.content=t.args.title=i,n.css({top:a.pageY+15,left:a.pageX-50}).show()):n.hide()},100)}).mouseleave(function(){o=!1}).mouseenter(function(){o=!0}),e(document.body).mouseup(function(){o||""!=e.trim(s())||n.hide()}),t.args.type=1,t.args.fat=n,h())}function c(){var i=e(t.args.fat);if(!i.length||i.length<=0)return!1;var o=e("<div>",{"class":"NIE-share NIE-share6",html:"<span class='NIE-share-txt'>������:</span>"}).appendTo(i),r=e("<ul>",{"class":"NIE-share-iconBtn"}).appendTo(o);e("<div>",{"class":"NIE-share-clearfix"}).appendTo(o),e("<div>",{"class":"NIE-share-clearfix"}).appendTo(i),e.each(t.args.defShow,function(){var e=n.addBtn(this,20,0,r,!1,1);if(e){var a=e.text();e.html('<span class="iconfont">'+a+"</span><em>"+t.data[this].name.replace("��ά��","")+"</em>")}});var s,c=e("<li>").appendTo(r),h=e("<a>",{"class":"iconfont morebtn",href:"javascript:void(0)",html:'<span class="iconfont">'+a.fontVal.more+"</span><em>����</em>"}).appendTo(c),u=!0,l=e("<ul>").appendTo(e("<div>",{"class":"NIE-share-more"}).appendTo(o)),d=[];e.each(t.args.moreShow,function(e,a){for(var n=0;n<t.args.defShow.length&&a!=t.args.defShow[n];n++);n>=t.args.defShow.length&&d.push(a)}),e.each(d,function(){n.addBtn(this,20,5,l,!0,1)}),d.length<1&&h.hide(),1==d.length&&l.css({width:"99px"}),h.mouseenter(function(){clearTimeout(s),u=!0;var t=e(window),a=l.outerHeight(),n=l.outerWidth(),i=o.offset(),r=e(this).width()>30?2:1;l.css({top:t.scrollTop()+t.height()<i.top+a+20?-a-12:-10*r,left:t.scrollLeft()+t.width()<i.left+o.width()+n?-n+42:-10*r}).fadeIn("fast")}).mouseleave(function(){clearTimeout(s),u=!1,s=setTimeout(function(){u||l.fadeOut("fast")},500)}),l.mouseenter(function(){clearTimeout(s),u=!0}).mouseleave(function(){clearTimeout(s),u=!1,s=setTimeout(function(){u||l.fadeOut("fast")},500)})}function h(){var e=t.args.type;null==t.args.traceType&&(t.args.traceType=e),1==e||2==e?i(e):3==e?o():4==e&&t.args.imgs?r():5==e&&t.args.txt?s():6==e&&c()}return h}(),r=function(){if(""!=location.search&&!nie.util.share_traceCome){var n=location.search.match(t.args.urlReg);n&&(nie.util.share_traceCome=!0)}var r=arguments;if(r.length>0&&r[0].length>0){var s=r[0][0];for(var c in s)s[c]&&(t.args[c]=s[c])}for(var c=0,h=i.length;h>c;c++){var u=i[c];t.data[u[0]]={name:u[1],searchTxt:u[2],file:u[3],paramName:u[4],params:a.chkDefault(u[5],{})}}nie.util.share_css||(e(t.args.fat).hide(),e.include("https://nie.res.netease.com/comm/js/nie/util/share/css/share.v5.css",function(){e(t.args.fat).show()}),nie.util.share_css=!0),o()};r(arguments);var s={modify:function(e){for(var a in e)t.args[a]=e[a]}};return s}}(jQuery);
!function(e,t,a){function s(e,t){var a=(e[0]||0)-(t[0]||0);return a>0||!a&&e.length>0&&s(e.slice(1),t.slice(1))}function i(e){if(typeof e!=o)return e;var t=[],a="";for(var s in e)a=typeof e[s]==o?i(e[s]):[s,l?encodeURI(e[s]):e[s]].join("="),t.push(a);return t.join("&")}function n(t){var a="";for(var s in t)if(t[s])switch(s){case"type":a+=e.browser.msie?" classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'":s+"="+t[s];break;case"data":e.browser.msie||(a+=" "+s+'="'+t[s]+'"');break;default:a+=" "+s+'="'+t[s]+'"'}return a}function r(e){var t=[];for(var a in e)t.push(['<param name="',a,'" value="',i(e[a]),'" />'].join(""));return t.join("")}var o="object",l=!0,c='<div><h4>\u9875\u9762\u9700\u8981\u65b0\u7248Adobe Flash Player.</h4><p><a href="http://www.adobe.com/go/getflashplayer" target="_blank"><img width="112" height="33" alt="\u83b7\u53d6\u65b0\u7248Flash" src="https://nie.res.netease.com/comm/js/util/swfobject/get_flash_player.gif"></a></p></div>';try{var h=a.description||function(){return new a("ShockwaveFlash.ShockwaveFlash").GetVariable("$version")}()}catch(f){h="Unavailable"}var d=h.match(/\d+/g)||[0];e[t]={available:d[0]>0,activeX:a&&!a.name,version:{original:h,array:d,string:d.join("."),major:parseInt(d[0],10)||0,minor:parseInt(d[1],10)||0,release:parseInt(d[2],10)||0},hasVersion:function(e){var t=/string|number/.test(typeof e)?e.toString().split("."):/object/.test(typeof e)?[e.major,e.minor]:e||[0,0];return s(d,t)},encodeParams:!0,expressInstall:"https://nie.res.netease.com/comm/js/util/expressInstall.swf",expressInstallIsActive:!1,create:function(e){var t,a=this;if("undefined"!=typeof e.noFlashTips?(t=e.noFlashTips,delete e.noFlashTips):t=c,!e.swf||a.expressInstallIsActive)return t;if(!a.hasVersion(e.hasVersion||1)){if(a.expressInstallIsActive=!0,"function"==typeof e.hasVersionFail&&!e.hasVersionFail.apply(e))return t;e={swf:e.expressInstall||a.expressInstall,height:137,width:214,quality:"high",flashvars:{MMredirectURL:location.href,MMplayerType:a.activeX?"ActiveX":"PlugIn",MMdoctitle:document.title.slice(0,47)+" - Flash Player Installation"}}}return attrs={data:e.swf,type:"application/x-shockwave-flash",id:e.id||"flash_"+Math.floor(999999999*Math.random()),width:e.width||320,height:e.height||180,style:e.style||""},l="undefined"!=typeof e.useEncode?e.useEncode:a.encodeParams,e.movie=e.swf,e.wmode=e.wmode||"opaque",delete e.fallback,delete e.hasVersion,delete e.hasVersionFail,delete e.height,delete e.id,delete e.swf,delete e.useEncode,delete e.width,"<object "+n(attrs)+">"+r(e)+t+"</object>"}},e.fn[t]=function(a){var s=this.find(o).andSelf().filter(o);return/string|object/.test(typeof a)&&this.each(function(){var s,i=e(this);a=typeof a==o?a:{swf:a},a.fallback=this,s=e[t].create(a),i.empty().html(s)}),"function"==typeof a&&s.each(function(){var s=this,i="jsInteractionTimeoutMs";s[i]=s[i]||0,s[i]<660&&(s.clientWidth||s.clientHeight?a.call(s):setTimeout(function(){e(s)[t](a)},s[i]+66))}),s}}(jQuery,"flash",navigator.plugins["Shockwave Flash"]||window.ActiveXObject);
;!function(e){return nie.util.video?!1:(window.console=window.console||{log:function(){}},void(nie.util.video=function(){function t(){var e=navigator.userAgent;l=/i(Phone|Pad)/i.test(e),a=/Android/i.test(e),r=l||a}function i(){if(document.createElement("video").canPlayType){var e=document.createElement("video");return oggtest=e.canPlayType("video/ogg; codecs=theora, vorbis"),oggtest?"probably"==oggtest?!0:!1:(h264test=e.canPlayType("video/mp4; codecs=avc1.42e01e, mp4a.40.2"),h264test&&"probably"==h264test?!0:!1)}return!1}function o(){t(),s=i()}var n=e.flash.available,s=!1,l=!1,a=!1,r=!1,h=0,d='<div><h4>\u9875\u9762\u9700\u8981\u65b0\u7248Adobe Flash Player.</h4><p><a href="http://www.adobe.com/go/getflashplayer" target="_blank"><img width="112" height="33" alt="\u83b7\u53d6\u65b0\u7248Flash" src="http://res.nie.netease.com/comm/js/util/swfobject/get_flash_player.gif"></a></p></div>',c="https://nie.res.netease.com/comm/images/check_net.jpg",p=102,u=function(t){var i={width:"100%",height:"100%",wmode:"direct",bgcolor:"#000000",host:location.host,movieUrl:null,HDmovieUrl:null,SHDmovieUrl:null,vtype:null,hideCtrlBar:null,videoWidth:null,videoHeight:null,volume:.8,autoPlay:!1,startImg:null,loopTimes:0,maskImg:null,bufferTime:5,videoIndex:h,playBtnArea:null,allowFullScreen:!0};this.fat=e(t.fat),t.fat="",e.extend(i,t),this.video=null,this.speed=0,this.init(i)};return u.prototype={init:function(t){var i=this;if(!r&&!n&&!s)return this.fat.html(d),!1;if(!r){if(this.speed<=0&&!t.vtype)return this.checkNetWork(function(){i.init(t)}),!1;t.vtype||(t.vtype=this.speed>=800?"shd":this.speed>=300?"hd":""),"shd"==t.vtype||"hd"==t.vtype?(t.HDmovieUrl||"hd"!=t.vtype||(t.vtype=""),t.SHDmovieUrl||"shd"!=t.vtype||(t.vtype="")):t.vtype=""}if(r||s&&!n){var o={shd:t.SHDmovieUrl,hd:t.HDmovieUrl},l=e("<video>",{controls:!0,poster:t.startImg,src:o[t.vtype]||t.movieUrl,loop:0!=t.loopTimes}).attr({width:r?"100%":t.width,height:r?"100%":t.height}).css("background-color","black");t.autoPlay&&l.attr("autoplay","autoplay"),a&&l.click(function(){this.play()}),e("<source>",{src:o[t.vtype]||t.movieUrl,type:"video/mp4"}).appendTo(l);var h="";h=r?"max-height:100%":"height:"+t.height+"px";var c=e("<div>",{style:"position:relative;left:0;top:0;display:inline-block;width:"+(r?"100%":t.width+"px")+";"+h});c.append(l),this.fat.empty().append(c),this.video=l[0],r||this.videoProcess(t)}else this.fat.flash({width:t.width,height:t.height,swf:"https://nie.res.netease.com/comm/js/nie/util/video/img/player.swf?v=2017032302",allowFullScreen:t.allowFullScreen,allowscriptaccess:"always",wmode:t.wmode,bgcolor:t.bgcolor,hasVersion:10.2,flashvars:t})},videoProcess:function(t){for(var i=this,o=e("<a>",{href:"javascript:void(0)",style:"position:absolute;width:30px;height:30px;border:1px solid white;border-radius:15px;font-size:16px;color:white;line-height:30px;text-align:center;top:50%;right:10px;text-decoration:none;"}),n=e("<ul>",{style:"list-style:none;position:absolute;width:126px;height:32px;top:50%;right:10px;margin:0;display:none;"}),s=["\u6807","\u9ad8","\u8d85"],l={"\u6807":t.movieUrl,"\u9ad8":t.HDmovieUrl,"\u8d85":t.SHDmovieUrl},a=0;3>a;a++){var r=e("<li>",{style:"float:left;width:30px;height:30px;border:1px solid white;border-radius:15px;font-size:16px;color:white;line-height:30px;text-align:center;margin-left:10px;cursor:pointer;",html:s[a]});l[s[a]]||r.css("border-color","#aaa").css("color","#aaa"),n.append(r)}o.html("shd"==t.vtype?"\u8d85":"hd"==t.vtype?"\u9ad8":"\u6807"),o.click(function(){e(this).hide(),e(this).siblings().show()}),n.click(function(t){var o=t.target;return l[o.innerHTML]?(e(this).hide(),e(this).siblings().html(o.innerHTML).show(),void i.change(l[o.innerHTML])):!1}),e(this.video.parentNode).mouseenter(function(){o.show(100)}).mouseleave(function(){n.hide(100),o.hide(100)}),this.fat.find("div").append(o[0]),this.fat.find("div").append(n[0])},checkNetWork:function(t){var i=e.cookie("nie_video_speed");if(i)return this.speed=parseInt(i,10),t&&t(),!1;var o=this,n=new Date,s=null,l=new Image;l.onload=function(){var i=new Date-n;o.speed=(p/(i/1e3)).toFixed(1),e.cookie("nie_video_speed",o.speed,{expires:1,path:"/",domain:"163.com"}),clearTimeout(s),s=0,l.onload=null,l.onerror=null,t&&t()},l.onerror=function(){o.speed=0,clearTimeout(s),s=0,l.onload=null,l.onerror=null,t&&t()},s=setTimeout(function(){return s?(l.onload=null,l.onerror=null,o.speed=50,void(t&&t())):!1},1e3),l.src=c+"?"+Math.random()},destroy:function(){this.stop(),this.fat.empty()},change:function(e){this.video?this.video.src=e:this.fat.flash(function(){try{this.change(e)}catch(t){console.log(t.message)}})},stop:function(){this.video?(this.video.pause(),e.browser.mozilla?this.video.mozSrcObject=null:this.video.src=e.browser.opera?null:e.browser.webkit?"":""):this.fat.flash(function(){try{this.stopVideo()}catch(e){console.log(e.message)}})},pause:function(){this.video?this.video.pause():this.fat.flash(function(){try{this.pauseVideo()}catch(e){console.log(e.message)}})},play:function(){this.video?this.video.play():this.fat.flash(function(){try{this.playVideo()}catch(e){console.log(e.message)}})}},o(),function(e){return new u(e)}}()))}(window.jQuery||window.Zepto);
(function(g,h,k){function m(a,d){var b=(a[0]||0)-(d[0]||0);return 0<b||!b&&0<a.length&&m(a.slice(1),d.slice(1))}function n(a){if("object"!=typeof a)return a;var d=[],b,c;for(c in a)b="object"==typeof a[c]?n(a[c]):[c,p?encodeURI(a[c]):a[c]].join("="),d.push(b);return d.join("&")}var p=!0;try{var l=k.description||(new k("ShockwaveFlash.ShockwaveFlash")).GetVariable("$version")}catch(a){l="Unavailable"}var e=l.match(/\d+/g)||[0];g[h]={available:0<e[0],activeX:k&&!k.name,version:{original:l,array:e,string:e.join("."),
major:parseInt(e[0],10)||0,minor:parseInt(e[1],10)||0,release:parseInt(e[2],10)||0},hasVersion:function(a){a=/string|number/.test(typeof a)?a.toString().split("."):/object/.test(typeof a)?[a.major,a.minor]:a||[0,0];return m(e,a)},encodeParams:!0,expressInstall:"https://nie.res.netease.com/comm/js/util/expressInstall.swf",expressInstallIsActive:!1,create:function(a){var d;"undefined"!=typeof a.noFlashTips?(d=a.noFlashTips,delete a.noFlashTips):d='<div><h4>\u9875\u9762\u9700\u8981\u65b0\u7248Adobe Flash Player.</h4><p><a href="http://www.adobe.com/go/getflashplayer" target="_blank"><img width="112" height="33" alt="\u83b7\u53d6\u65b0\u7248Flash" src="https://nie.res.netease.com/comm/js/util/swfobject/get_flash_player.gif"></a></p></div>';
if(!a.swf||this.expressInstallIsActive)return d;if(!this.hasVersion(a.hasVersion||1)){this.expressInstallIsActive=!0;if("function"==typeof a.hasVersionFail&&!a.hasVersionFail.apply(a))return d;a={swf:a.expressInstall||this.expressInstall,height:137,width:214,quality:"high",flashvars:{MMredirectURL:location.href,MMplayerType:this.activeX?"ActiveX":"PlugIn",MMdoctitle:document.title.slice(0,47)+" - Flash Player Installation"}}}var b={data:a.swf,type:"application/x-shockwave-flash",id:a.id||"flash_"+
Math.floor(999999999*Math.random()),width:a.width||320,height:a.height||180,style:a.style||""};p="undefined"!==typeof a.useEncode?a.useEncode:this.encodeParams;a.movie=a.swf;a.wmode=a.wmode||"opaque";delete a.fallback;delete a.hasVersion;delete a.hasVersionFail;delete a.height;delete a.id;delete a.swf;delete a.useEncode;delete a.width;var c="",f;for(f in b)if(b[f])switch(f){case "type":c+=g.browser.msie?" classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'":f+"="+b[f];break;case "data":g.browser.msie||
(c+=" "+f+'="'+b[f]+'"');break;default:c+=" "+f+'="'+b[f]+'"'}var b="<object "+c+">",e,c=[];for(e in a)c.push(['<param name="',e,'" value="',n(a[e]),'" />'].join(""));e=c.join("");return b+e+d+"</object>"}};g.fn[h]=function(a){var d=this.find("object").andSelf().filter("object");/string|object/.test(typeof a)&&this.each(function(){var b=g(this),c;a="object"==typeof a?a:{swf:a};a.fallback=this;c=g[h].create(a);b.empty().html(c)});"function"==typeof a&&d.each(function(){var b=this;b.jsInteractionTimeoutMs=
b.jsInteractionTimeoutMs||0;660>b.jsInteractionTimeoutMs&&(b.clientWidth||b.clientHeight?a.call(b):setTimeout(function(){g(b)[h](a)},b.jsInteractionTimeoutMs+66))});return d}})(jQuery,"flash",navigator.plugins["Shockwave Flash"]||window.ActiveXObject);