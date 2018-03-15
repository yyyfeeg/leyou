/**
 * @name 点赞组件
 * @version 0.1
 * @author zefei
 * @description 先去后台生成id然后在页面中初始化，后台地址：http://nie-like.webapp.163.com/admin/index
 * 
	var ilike=new iLike({
		id:2,//id,必填
		parent:selector,//appendTo父级选择器，留空则默认为body
		style:1 //三个样式 1/2/3
	});
 *
 */
var iLike = (function ($){
	function jsonp(url,cb1,cb2){
		$.ajax({
			type: "get",
			url: url,
			dataType: "jsonp",
			success: function(json){
				cb1(json);
			},
			error: function(json){
				cb2(json);
			}
		});
	}
	function setNum(num){
		var n=parseInt(num);//>9999?'1万+':num
		return n>9999?Math.floor(n/10000)+'万+':n
	}
	var style=$('<style>\	.NIE-ilike{position:relative;display:inline-block;*display:inline;*zoom:1;cursor:pointer;color:#333;font-family:Verdana,helvetica,arial,"Microsoft Yahei";font-size:12px;-moz-user-select:none;-webkit-user-select:none;-ms-user-select:none;}\
	.NIE-ilike-style1{height:20px;line-height:20px;padding:0 5px 0 25px;border:1px solid #ff6666;border-radius:2px;background:#fff url(https://nie.res.netease.com/comm/js/ilike/1.png) 5px -1px no-repeat;}\
	.NIE-ilike:hover{text-decoration:none;}\
	.NIE-ilike-style1:hover{background-position:5px -23px;color:#fff;background-color:#ff6666}\
	.NIE-ilike-style2{height:30px;line-height:30px;padding-left:35px;background:url(https://nie.res.netease.com/comm/js/ilike/2.png) 0 0 no-repeat;}\
	.NIE-ilike-style2:hover{background-position:0 -30px;}\
	.NIE-ilike-style3{min-width:82px;_width:82px;height:30px;line-height:30px;padding-top:82px;background:url(https://nie.res.netease.com/comm/js/ilike/3.png) 50% 0 no-repeat;text-align:center;background-image:url(https://nie.res.netease.com/comm/js/ilike/3.ie6.png);}.NIE-ilike-style3:hover{background-position:50% -112px;}\
	.NIE-ilike-num{font-style:normal;}\
	.NIE-ilike-add,.NIE-ilike-limited{position:absolute;color:#ff3333;font-size:12px;opacity:0;filter:alpha(opacity=0);}\
	.NIE-ilike-style1 .NIE-ilike-add{left:3px;bottom:0;font-size:12px;margin-bottom:4px;}\
	.NIE-ilike-style2 .NIE-ilike-add{left:5px;bottom:50%;font-size:14px;}\
	.NIE-ilike-style3 .NIE-ilike-add{left:50%;margin-left:-15px;bottom:50%;font-size:20px;color:#fff;}\
	.NIE-ilike-style1 .NIE-ilike-limited{left:0;width:150px;bottom:0;margin-bottom:5px;}\
	.NIE-ilike-style2 .NIE-ilike-limited{left:0;width:150px;bottom:50%;}\
	.NIE-ilike-style3 .NIE-ilike-limited{left:50%;width:132px;margin-bottom:26px;margin-left:-66px;bottom:50%;font-size:12px;color:#ff3333;}</style>');
    var iLike = function (options) {
        this.options = $.extend({
			parent:'body',
			style:1,
			countUrl:'https://nie-like.webapp.163.com/count/',
			likeUrl:'https://nie-like.webapp.163.com/like/'
		},options);
		this.init();
    }
	iLike.prototype.init=function(){
		var self=this;
			
		jsonp(self.options.countUrl+self.options.id,function(json){
			if(json['status']=='ok'){
				style.appendTo('head');
				var styleClass=(self.options.style!=1)?'NIE-ilike-style'+self.options.style:'NIE-ilike-style1';
				var o=$('<a id="NIE-ilike-'+self.options.id+'" class="NIE-ilike '+styleClass+'">人赞过</a>').appendTo(self.options.parent),
					num=$('<em class="NIE-ilike-num"></em>').prependTo(o);
				num.text(setNum(json['count'])).attr('title',json['count']);
				o.bind('click',function(){
					jsonp(self.options.likeUrl+self.options.id,function(json){
						if(json['status']=='ok'){
							var count=json['count'];
							$('<span class="NIE-ilike-add">+1</span>').appendTo(o).animate({'bottom':'70%','opacity':1}).delay(400).fadeOut(function(){
								$(this).remove();
							});
							num.text(setNum(json['count'])).attr('title',json['count']);
							o.addClass('NIE-ilike-liked');
						}
						if(json['status']=='ip_limited'){
							//alert('1小时内只能投'+json['limit_times']+'票哦！');
							$('<span class="NIE-ilike-limited">1小时只能赞'+json['limit_times']+'次哦！</span>').appendTo(o).animate({'bottom':'70%','opacity':1}).delay(1000).fadeOut(function(){
								$(this).remove();
							});
						}
					},function(){
						alert('网络问题，请稍后再试！');
					});
				});
			}
		},function(e){
			window.console && console.log(e);
		});
	}
    return iLike;
})(jQuery);