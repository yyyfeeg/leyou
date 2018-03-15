/**
 * jquery.handle.js
 * version: 1.0
 * desc: 鍓嶇澶勭悊JS鏂囦欢
 * author: Tang 799345505@qq.com
 */
$(function(){
	check_login();
	system_wx('.AZ-DURL','.IOS-DURL',URL);

	// 褰撹緭鍏ユ鑾峰緱鐒︾偣鏄敼鍙樿竟妗嗛鑹�
	$("input").focusin(function(){
		$(this).css("border","1px solid #f28f00");
	}).focusout(function(){
		$(this).css("border","1px solid #b2b2b2");
	});

	// 澶撮儴瀵艰埅鏍峰紡澶勭悊
	$('.btn-nav').on('click tap', function () {
		$('.nav-content').toggleClass('showNav hideNav').removeClass('hidden');
		$('#lxt').toggleClass('effect_1 effect_2').removeClass('ggg');
		$(this).toggleClass('animated');
		var str = $('#top_menu').attr('class');
		if (str == 'btn-nav') {
			setTimeout("$('.hideNav').addClass('hidden');",500);
		}
	});

	// 杩斿洖椤堕儴
	$(window).scroll(function(){
		var h = $(this).scrollTop();
		if (h > 100) {
			$('.back_to_top').fadeIn(800);
		} else {
			$('.back_to_top').fadeOut(800);
		}
	});
	$('#gotop').click(function(){
		$('html,body').animate({scrollTop: '0px'}, 300);
	});

	// 鍏抽棴涓嬭浇鎻愮ず
	$('.popupbox_download').click(function(){
		$(this).hide(300);
	});

	// 椤甸潰鐧诲綍
	$('#login_page').click(function(){
		var flag = $.trim($('#flag').val());
		var jump = $.trim($('#jump').val());
		var name = $.trim($('#username').val());
		var pwd  = $.trim($('#password').val());
		if (!name || name == 'undefined') {
			alert('璇疯緭鍏ヨ处鍙�!');
			$('#username').focus();
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			alert('璇疯緭鍏ュ瘑鐮�!');
			$('#password').focus();
			return false;
		}
		$.getJSON(URL+'/index.php?mo=m_login&me=gologin',{flag:flag,jump:jump,name:name,pwd:pwd},function(data){
			if (data.code == 1000) {
				all_tips('鐧诲綍鎴愬姛<br/>1绉掑悗鑷姩璺宠浆....',jump,1000);
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});
	
	// 鎵惧洖瀵嗙爜
	// 鏌ヨ璐﹀彿
	$('#find_bind_info').click(function(){
		var flag = $.trim($('#flag').val());
		var name = $.trim($('#fpwd_name').val());
		var code = $.trim($('#fpwd_code').val());
		if (!flag || flag == 'undefined') {
			$('#step1_tips').text('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬫敞鍐屽摝!');
			return false;
		}
		if (!name || name == 'undefined') {
			$('#step1_tips').text('璇疯緭鍏ヨ处鍙�!');
			return false;
		}
		if (!code || code == 'undefined') {
			$('#step1_tips').text('璇疯緭鍏ラ獙璇佺爜!');
			return false;
		}
		if (!/^\d{4}$/.test(code)) {
			$('#step1_tips').text('楠岃瘉鐮侀敊璇�!');
			return false;
		}
		$('#step1_tips').text('');
		$.getJSON(URL+'/index.php?mo=m_forgetpwd&me=index',{flag:flag,name:name,code:code},function(data){
			if (data.code == 1000) {
				window.location.href = URL+'/index.php?mo=m_forgetpwd&me=phone_getpwd';
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});
	
	$('#phone_way_getpwd').click(function(){
		var flag = $.trim($('#phone_flag').val());
		var step = $.trim($('#phone_step').val());
		var type = $.trim($('#phone_type').val());
		var code = $.trim($('#smscode').val());
		if (!flag || flag == 'undefined') {
			$('#step2_tips').text('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬫敞鍐屽摝!');
			return false;
		}
		if (!step || step == 'undefined') {
			$('#step2_tips').text('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬫敞鍐屽摝!');
			return false;
		}
		if (!type || type == 'undefined') {
			$('#step2_tips').text('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬫敞鍐屽摝!');
			return false;
		}
		if (!code || code == 'undefined') {
			$('#step2_tips').text('璇疯緭鍏ラ獙璇佺爜!');
			return false;
		}
		if (!/^\d{6}$/.test(code)) {
			$('#step2_tips').text('楠岃瘉鐮侀敊璇�!');
			return false;
		}
		$('#step2_tips').text('');
		$.getJSON(URL+'/index.php?mo=m_forgetpwd&me=phone_getpwd',{flag:flag,code:code,step:step,type:type},function(data){
			if (data.code == 1000) {
				window.location.href = URL+'/index.php?mo=m_forgetpwd&me=set_newpwd';
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});

	$('#set_newpwd').click(function(){
		var flag  = $.trim($('#setpwd_flag').val());
		var step  = $.trim($('#setpwd_step').val());
		var pwd   = $.trim($('#set_new_pwd').val());
		var repwd = $.trim($('#reset_new_pwd').val());
		if (!flag || flag == 'undefined') {
			$('#step3_tips').text('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬫敞鍐屽摝!');
			return false;
		}
		if (!step || step == 'undefined') {
			$('#step3_tips').text('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬫敞鍐屽摝!');
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			$('#step3_tips').text('璇疯缃柊瀵嗙爜!');
			return false;
		}
		if (pwd.length < 6 || pwd.length > 20) {
			$('#step3_tips').text('瀵嗙爜涓�6-20涓瓧绗︼紱鍙兘鍖呭惈瀛楁瘝澶у皬鍐欍€佹暟瀛椾互鍙婃爣鐐�(绌烘牸闄ゅ)');
			return false;
		}
		if (pwd != repwd) {
			$('#step4_tips').text('纭瀵嗙爜涓庡瘑鐮佷笉鍖归厤鍝�!');
			return false;
		}
		$('#step3_tips').text('');
		$.getJSON(URL+'/index.php?mo=m_forgetpwd&me=set_newpwd',{flag:flag,pwd:pwd,step:step,repwd:repwd},function(data){
			if (data.code == 1000) {
				window.location.href = URL+'/index.php?mo=m_forgetpwd&me=setpwd_success';
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});
	
	// 鎵嬫満娉ㄥ唽
	$('#tel_reg').click(function(){
		var flag  = $.trim($('#treg_flag').val());
		var step  = $.trim($('#treg_step').val());
		var phone = $.trim($('#phone').val());
		var code  = $.trim($('#treg_code').val());
		var type  = $.trim($('#treg_type').val());

		if (!flag || flag == 'undefined') {
			$('#treg_tips').text('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬫敞鍐屽摝!');
			return false;
		}
		if (!phone || phone == 'undefined') {
			$('#treg_tips').text('璇疯緭鍏ユ墜鏈哄彿鐮�!');
			return false;
		}
		if (!/1[34578]{1}\d{9}$/.test(phone) || phone.length != 11) {
			$('#treg_tips').text('鎵嬫満鍙风爜鏍煎紡涓嶆纭紝璇烽噸鏂拌緭鍏�!');
			return false;
		}
		if (!code || code == 'undefined') {
			$('#treg_tips').text('璇疯緭鍏ョ煭淇￠獙璇佺爜!');
			return false;
		}
		if (!/^\d{6}$/.test(code)) {
			$('#treg_tips').text('鐭俊楠岃瘉鐮侀敊璇�!');
			return false;
		}
		$('#treg_tips').text('');
		$.getJSON(URL+'/index.php?mo=m_register&me=mobile_reg',{flag:flag,step:step,phone:phone,code:code},function(data){
			if (data.code == 1105) {
				if (type == 'page') {
					window.location.href = URL+'/index.php?mo=m_register&me=index&s=2&n='+phone;
				} else {
					$('#phone_reg').hide(300);
					$('#tel_reg_pass').show(300);
					$('#setpwd_phone').val(phone);
					$('#phone_ac').text(phone);
				}
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});
	
	// 璁剧疆瀵嗙爜
	$('#set_pwd').click(function(){
		var flag  = $.trim($('#setpwd_flag').val());
		var step  = $.trim($('#setpwd_step').val());
		var phone = $.trim($('#setpwd_phone').val());
		var pwd   = $.trim($('#user_pass').val());
		var repwd = $.trim($('#user_repass').val());
		var type  = $.trim($('#treg_type').val());

		if (!flag || flag == 'undefined') {
			$('#pass_tips').text('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬫敞鍐屽摝!');
			return false;
		}
		if (!phone || phone == 'undefined') {
			alert('绯荤粺閿欒锛岃閲嶈瘯!');
			return false;
		}
		if (!/1[34578]{1}\d{9}$/.test(phone) || phone.length != 11) {
			alert('绯荤粺閿欒锛岃閲嶈瘯!');
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			$('#pass_tips').text('璇疯緭鍏ュ瘑鐮�!');
			return false;
		}
		if (pwd.length < 6 || pwd.length > 20) {
			$('#pass_tips').text('瀵嗙爜涓�6-20涓瓧绗︼紱鍙兘鍖呭惈瀛楁瘝澶у皬鍐欍€佹暟瀛椾互鍙婃爣鐐�(绌烘牸闄ゅ)');
			return false;
		}
		if (!repwd || repwd == 'undefined') {
			$('#pass_tips').text('璇疯緭鍏ョ‘璁ゅ瘑鐮�!');
		}
		if (pwd != repwd) {
			$('#pass_tips').text('纭瀵嗙爜涓庡瘑鐮佷笉鍖归厤鍝�!');
			return false;
		}
		$('#pass_tips').text('');
		$.getJSON(URL+'/index.php?mo=m_register&me=mobile_reg',{flag:flag,step:step,phone:phone,pwd:pwd,repwd:repwd},function(data){
			if (data.code == 1200) {
				if (type == 'page') {
					window.location.href = URL+'/index.php?mo=m_users&me=index';
				} else {
					$('#success_title').text('娉ㄥ唽鎴愬姛');
					$('#success_content').text('鎭枩鎮ㄦ敞鍐屾垚鍔�!');
					$('#tel_reg_pass').hide(300);
					$('#reg_success').show(300);
					setTimeout("$('#reg_success').hide();history.go(0);",3000);
				}
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});

	// 鐧诲嚭
	$('#sign_out').click(function(){
		$.getJSON(URL+'/index.php?mo=m_login&me=logout',{},function(data){
			all_tips('閫€鍑烘垚鍔�<br/>1绉掑悗鑷姩璺宠浆....','','');
			setTimeout("$('#reg_success').hide();history.go(0);",1000);
		});
	});

	//绛惧埌澶勭悊
	var nday= $(".nday p:eq(0)").text();
	var eday= $(".nday p:eq(1)").text();
	var date = $(".nday p:eq(2)").text();
	var ndate = $(".nday p:eq(3)").text();
	if(date == ndate){//褰撳勾褰撴湀
		$(".day #signs").slice(nday-1, eday+1).bind("click",function(){
			check_login();
			var url = './index.php?mo=m_signin&me=index';
	        var day = $(this).html();
	       if(nday!=day){
				all_tipss('浜诧紝鍙兘绛惧埌褰撳ぉ鍝�!');
	            return;
	        }
			$.post(url,{type:1,day:day},function(result){
				var flage = result.flage;
				all_tipss(result.msg,flage);
			},'json');
	    })
	}
});
//绀煎寘澶勭悊
function get_gift(url){
	check_login();
	$.post(url,{},function(result){
		var flage = result.flage;
		all_tipss(result.msg,flage);
	},'json');
}

// 妫€鏌ユ槸鍚︾櫥褰�
function check_login(){
	var str = '';
	var str1 = '';
	var nickname = '';
	var inteArr = [0,300,1000,2000,5000,10000,20000,50000,100000];

	getJson({},function(data){
		if (data.code == 1000) {
			var next = parseInt(data.data.vip) + 1;
			var inte = inteArr[next];
			var percent = parseInt((parseInt(data.data.grow) / parseInt(inte))*100);
			if (data.data.ulogo != '') {
				$('#no_ulogo img').attr('src',data.data.ulogo);
			}
			if (data.data.nickname != '') {
				nickname = data.data.nickname;
			} else {
				nickname = data.data.account;
			}
			str += '<p>'+nickname+'</p>'
					+'<div class="vip">'
						+'<em>VIP'+data.data.vip+'</em>'
						+'<div class="vip_content">'
							+'<div class="already" style="width:'+percent+'%"></div>'
							+'<div class="besides"></div>'
						+'</div>'
						+'<em style="color:#424243">VIP'+next+'</em>'
					+'</div>';
			str1 += '<p>'+nickname+'<i>VIP'+data.data.vip+'</i></p>'
					+'<span><a id="sign_out" href="javascript:;" title="閫€鍑�">閫€鍑�</a></span>';
			$('#no_login').hide();
			$('#true_login').html(str).show();
			$('.user_exits').html(str1).show();
			if (data.data.sign_flag) {
				$('#sign-in').html('<em><i></i>宸茬鍒�</em>');
			}
		} else {
			$('#sign-in').attr('href','javascript:;');
			$('#sign-in').click(function(){
				all_tips('璇峰厛鐧诲綍<br/>2绉掑悗鑷姩璺宠浆....',URL+'/index.php?mo=m_login&me=index',2000);
			});
		}
	},URL+'/index.php?mo=m_login&me=checkLogin','POST');
}

// 绀煎寘澶勭悊
function give_gifts(id,gid,url)
{
	if (id == 1) {
		
		
	} else if (id == 2) {
		// 棰嗗彇cdkey
		getJson('',function(result){
			if (result != '') {
				if (result.code == 2000) {
					cdkey_tips(result.data.cdkey);
					return false;
				} else if (result.code == '-1') {
					all_tips('璇峰厛鐧诲綍<br/>2绉掑悗鑷姩璺宠浆....',result.data.gotoUrl,2000);
					return false;
				} else {
					all_tips(result.msg,'','');
					return false;
				}
			} else {
				alert('缃戠粶绻佸繖锛�');
				return false;
			}
		},
		url,'POST');
	} else {
		alert('闈炴硶鎿嶄綔锛佽鎸夋甯告祦绋嬮鍙栫ぜ鍖呭摝!');
		return false;
	}
}

// 鐑棬绀煎寘
function get_hot_gifts()
{	
	var str = '';
	getJson({},function(data){
		if (data.code == 1000) {
			for (var i in data.data) {
				str += '<div class="popular popular_'+data.data[i].classid+'">'
						+'<span><a href="'+data.data[i].c_url+'"><img src="'+data.data[i].icon+'" width="93" alt="'+data.data[i].gname+'"/></a></span>'
						+'<div class="popular_right">'
							+'<p><a href="'+data.data[i].c_url+'">'+data.data[i].name+'</a></p>'
							+'<i><a href="'+data.data[i].c_url+'">'+data.data[i].gname+'</a></i>';
							if (data.data[i].draw) {
								str += '<em><a href="javascript:;" onclick="give_gifts('+data.data[i].id+','+data.data[i].gid+',\''+data.data[i].lq_url+'\');">棰嗗彇</a></em>';
							} else {
								str += '<em style="background: #787878;"><a href="javascript:;">宸查</a></em>';
							}
						str += '</div>'
					str += '</div>';
			}
		} else {
			str += '<p>璇峰埛鏂伴〉闈�</p>';
		}
		str += '<div class="popular_more">'
					+'<a href="'+URL+'/index.php?mo=m_gifts&me=html_gifts" title="鏇村绀煎寘">+鏇村绀煎寘</a>'
				+'</div>';
		$('.popular_content').html(str);
	},URL+'/index.php?mo=m_index&me=show_hot_gifts','GET');
}

// 绯荤粺绫诲瀷鍙婂井淇＄幇鍦ㄥ湴鍧€杞崲
function system_wx(azid,iosid,pcurl)
{
	var ua = navigator.userAgent.toLowerCase();
	var type = /iphone|android|nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|wap|mobile/i;
	if (type.test(ua)) { // 绉诲姩绔�
		if (ua.match(/micromessenger/i) == 'micromessenger') { // 寰俊
			$(azid).attr('href','javascript:;');
			$(iosid).attr('href','javascript:;');
			$(azid).click(function(){download_tips();});
			$(iosid).click(function(){download_tips();});
		} else if (ua.indexOf('android') > -1 || ua.indexOf('linux') > -1) {
			$(azid).show();
			$(iosid).hide();
		} else if (ua.indexOf('iphone') > -1) {
			$(iosid).show();
			$(azid).hide();
		} else {
			$(azid).attr('href','javascript:;').show();
			$(azid).attr('onclick',"alert('鏁鏈熷緟!');");
			$(iosid).hide();
		}
	} else { // PC绔�
		// window.location.href = pcurl;
	}
}

// 鑾峰彇json鏁版嵁
function getJson(data,callback,url,type)
{
	$.ajax({
		'url': url?url:'index.php',
		'type': type?type:'GET',
		'data': data,
		'dataType': 'json',
		'async': false,
		'cache': false,
		success: function(info) {
			callback(info);
			return false;
		}
	});
}

// 鍏ㄥ眬鎻愮ず绐楀彛
function download_tips()
{
	$('.popupbox_download').show();
}
function all_tips(msg,gotoUrl,time)
{
	var Url = gotoUrl? gotoUrl:'';
	var Time = time? time:3000;
	$('#all_tips .pop_up_writing p').html(msg);
	$('.pop_up').show();
	$('#all_tips').show(300);
	if (Url != '') {
		setTimeout('window.location.href="'+Url+'"; $("#all_tips").hide();$(".pop_up").hide();',Time);
	}
}
function cdkey_tips(cdkey)
{
	$('#cdkey_tips .pop_up_writing span a').html(cdkey);
	$('.pop_up').show();
	$('#cdkey_tips').show(300);
}

//鎻愮ず鍒锋柊绐楀彛
function all_tipss(msg,gotoUrl)
{
	var Url = gotoUrl? gotoUrl:'';
	$('#all_tips .pop_up_writing p').html(msg);
	$('.pop_up').show();
	$('#all_tips').show();
	$('.pop_up_confirm p').click(function(){
	if(Url ==1){
		location.reload();
	}else if (Url != '') {
		return;
	}
	});
}

/**
 * 鍒ゆ柇鎵嬫満绯荤粺绫诲瀷
 * @return {[type]} 1 瀹夊崜绯荤粺  2 IOS 3 鍏朵粬绯荤粺
 */
function dev_type()
{
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf('android') > -1 || ua.indexOf('linux') > -1) {
        return 1;
    } else if (ua.indexOf('iphone') > -1) {
        return 2;
    } else {
        return 3;
    }
}

/**
 * 鍒ゆ柇鏄惁寰俊鎵撳紑
 * @return {Boolean} true:寰俊鎵撳紑  false:鍚�  
 */
function is_weixin()
{
	var browserInfo = navigator.userAgent.toLowerCase();
	if (browserInfo.match(/micromessenger/i) == "micromessenger") {
		return true;
	} else {
		return false;
	}
}

/**
 * 鍒ゆ柇鏄惁涓轰腑鏂囧瓧绗�
 * @param  {[type]}  str 闇€妫€鏌ョ殑瀛楃涓�
 * @return {Boolean}     true锛氫负涓枃瀛楃 false锛氬惁
 */
function is_cn(str)
{
	if (/^([\u4E00-\uFA29]|[\uE7C7-\uE7F3])*$/.test(str)) {
		return true;
	} else {
		return false;
	}
}

/**
 * 鍒ゆ柇鏄惁涓烘暟瀛�
 * @param  {[type]}  str 闇€妫€鏌ョ殑瀛楃涓�
 * @return {Boolean}     true锛氫负鏁板瓧 false锛氬惁
 */
function is_num(str)
{
	var re = /^[\d]+$/;
	return re.test(str);
}

/**
 * 澶勭悊涓嬭浇閾炬帴杩樻病涓嬭浇鍦板潃(鍦ㄥ悗鍙板～鈥�#鈥欏彿)鍙奾ttps鐨勬儏鍐�
 * @param  {[type]} idArr 瀵硅薄ID鎴朇lass锛屾槸涓€涓暟缁勶紝渚嬶細['.class1','#id1']
 * @return {[type]}       鏁扮粍涓虹┖锛岃繑鍥炵┖
 */
function dldUrl_handle(idArr)
{
	if (idArr.length > 0) {
		for (i in idArr) {
			var str = $(idArr[i]).attr('href');
			var strArr  = str.split('#');
			var strArr1 = str.split('https://');
			if (strArr.length > 1) {
				$(idArr[i]).attr('href', 'javascript:alert("鏁鏈熷緟锛�");')
			}
			if (strArr1.length > 1) {
				var url = 'https://'+strArr1[1];
				$(idArr[i]).attr('href', url);
			}
		}
	} else {
		return '';
	}
}

/**
 * 鍙戣捣JSON璇锋眰
 * @param  {[type]}   data     浜や簰鏁版嵁
 * @param  {Function} callback 浜や簰閫昏緫
 * @param  {[type]}   url      璇锋眰鍦板潃
 * @param  {[type]}   type     璇锋眰绫诲瀷
 * @param  {[type]}   async    鏄惁寮傛
 * @return {[type]}            
 */
function getJson(data,callback,url,type,async)
{
	$.ajax({
		'url': url?url:'index.php',
		'type': type?type:'GET',
		'data': data,
		'dataType': 'json',
		'async': async?async:false,
		'cache': false,
		success: function(info) {
			callback(info);
			return false;
		}
	});
}

/**
 * PC鐗堝拰绉诲姩鐗堣闂垽鏂�
 * @return {[type]} true:PC璁块棶  false:绉诲姩璁惧璁块棶
 */
function jump_handle()
{
    if (/iphone|android|nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|wap|mobile/i.test(navigator.userAgent.toLowerCase())){
           return false;
        } else {
            return true;
        }
}