/**
 * jquery.handle.js
 * version: 1.0
 * desc: 前端处理JS文件
 * author: Tang 799345505@qq.com
 */
$(function(){
	// 检查是否已经登录
	check_login();
	get_login_game();
	function check_login(){
		$.getJSON(URL+'/index.php?mo=login&me=checkLogin',{},function(data){
			if (data.code == 1000) {
				$('#top_login').hide();
				$('#login_true').show();
				$('#uname').text(data.data['name']);
			}
		});
	}
	// 获取用户最近游戏
	function get_login_game(){
		$.getJSON(URL+'/index.php?mo=login&me=get_login_game',{},function(data){
			if (data.code == 1000) {
				str = "<li><h5>最近游戏</h5></li>";
				for (var i = 0; i < data.data.length; i++) {
					str += '<li>';
					for (var j = 0; j < data.data[i].virtue_array.length; j++) {
						if (data.data[i].virtue_array[j] == 1) {
							str += '<span></span>';
						}
						if (data.data[i].virtue_array[j] == 2) {
							str += '<em></em>';
						}
						
					}
					str += '<a style="color:#c2c2c2;" href="'+data.data[i].gw_url+'" title="'+data.data[i].game_name+'" target="_brank" >'+data.data[i].game_name+'</a></li>';
				};
			} else if (data.code == 0000) {
				str = "<li><h5>最近游戏</h5></li><li>你还没玩过游戏哦！</li>";
			} else if (data.code == 1001) {
				str = "<li><h5>最近游戏</h5></li><li>请先登录！</li>";
			}
			$('.index_top_left').html(str);
		});
	}

	// 退出登录
	$('#logout').click(function(){
		$.getJSON(URL+'/index.php?mo=login&me=logout',{},function(data){
			$('#success_title').text('退出成功');
			$('#success_content').text('恭喜您退出成功！');
			$('#popupbox_wrap').show();
			$('#reg_success').show(300);
			setTimeout("$('#reg_success').hide();history.go(0);",3000);
		});
	});

	// 登录
	$('#user_login').submit(function(){
		var flag = $.trim($('#flag').val());
		var type = $.trim($('#type').val());
		var jump = $.trim($('#jump').val());
		var name = $.trim($('#username').val());
		var pwd  = $.trim($('#password').val());
		var code = $.trim($('#checkcode').val());
		var remMe = $("#rememberMe").is(':checked');

		if (!flag) {
			$('#tips').text('非法操作！请按正常流程登录哦！');
			return false;
		}
		if (!name || name == 'undefined') {
			$('#tips').text('请填写用户名！');
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			$('#tips').text('请输入密码！');
			return false;
		}
		if (!code) {
			$('#tips').text('请输入验证码！');
			return false;
		}
		if (!/^\d{4}$/.test(code)) {
			$('#tips').text('验证码错误！');
			return false;
		}
		$('#tips').text('');
		$.getJSON(URL+'/index.php?mo=login&me=gologin',{flag:flag,type:type,jump:jump,name:name,pwd:pwd,code:code},function(data){
			if (data.code == 1000) {
				$('#success_title').text('登录成功');
				$('#success_content').text('恭喜您登录成功！');
				$('#popupbox_login').hide(300);
				$('#reg_success').show(300);
				setTimeout("$('#reg_success').hide();history.go(0);",3000);
				if (remMe == 'true') {
					// 记录cookie
					rememberMe('username');
				}
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});

	// 页面登录
	$('#login_page').click(function(){
		var flag = $.trim($('#flag').val());
		var type = $.trim($('#type').val());
		var jump = $.trim($('#jump').val());
		var name = $.trim($('#username').val());
		var pwd  = $.trim($('#password').val());
		var remMe = $("#rememberMe").is(':checked');

		if (!flag) {
			$('#tips').text('非法操作！请按正常流程登录哦！');
			return false;
		}
		if (!name || name == 'undefined') {
			$('#tips').text('请填写用户名！');
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			$('#tips').text('请输入密码！');
			return false;
		}
		$('#tips').text('');
		$.getJSON(URL+'/index.php?mo=login&me=gologin',{flag:flag,type:type,jump:jump,name:name,pwd:pwd},function(data){
			if (data.code == 1000) {
				window.location.href = jump;
				if (remMe == 'true') {
					// 记录cookie
					rememberMe('username');
				}
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});

	// 个性注册
	$('#custom_reg').submit(function(){
		var flag  = $('#creg_flag').val();
		var name  = $.trim($('#reg_name').val());
		var pwd   = $.trim($('#reg_pwd').val());
		var repwd = $.trim($('#reg_repwd').val());
		var code  = $.trim($('#creg_code').val());
		var type = $('#creg_type').val();

		if (!flag) {
			$('#creg_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!name || name == 'undefined') {
			$('#creg_tips').text('请填写用户名！');
			return false;
		}
		if (!/^[a-zA-Z]{1}([_a-zA-Z0-9]){5,19}$/.test(name)) {
			$('#creg_tips').text('用户名应由6-20个字符，字母和数字的组合，请以英文字母开头');
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			$('#creg_tips').text('请输入密码！');
			return false;
		}
		if (pwd.length < 6 || pwd.length > 20) {
			$('#creg_tips').text('密码为6-20个字符；只能包含字母大小写、数字以及标点(空格除外)');
			return false;
		}
		if (pwd != repwd) {
			$('#creg_tips').text('确认密码与密码不匹配哦！');
			return false;
		}
		if (!code) {
			$('#creg_tips').text('请输入验证码！');
			return false;
		}
		if (!/^\d{4}$/.test(code)) {
			$('#creg_tips').text('验证码错误！');
			return false;
		}
		$('#creg_tips').text('');
		$.getJSON(URL+'/index.php?mo=register&me=custom_reg',{flag:flag,name:name,pwd:pwd,repwd:repwd,code:code},function(data){
			if (data.code == 1000) {
				if (type == 'page') {
					window.location.href = URL+'/index.php?mo=register&me=index&t=custom&r=success';
				} else {
					$('#success_title').text('注册成功');
					$('#success_content').text('恭喜您注册成功！');
					$('#fast_reg').hide(300);
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
	
	// 手机注册
	$('#tel_reg').submit(function(){
		var flag  = $('#treg_flag').val();
		var step  = $('#treg_step').val();
		var phone = $('#phone').val();
		var code  = $('#treg_code').val();
		var type  = $('#treg_type').val();

		if (!flag) {
			$('#treg_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!phone || phone == 'undefined') {
			$('#treg_tips').text('请输入手机号码！');
			return false;
		}
		if (!/1[34578]{1}\d{9}$/.test(phone) || phone.length < 11 || phone.length > 11) {
			$('#treg_tips').text('手机号码格式不正确，请重新输入！');
			return false;
		}
		if (!code || code == 'undefined') {
			$('#treg_tips').text('请输入短信验证码！');
			return false;
		}
		if (!/^\d{6}$/.test(code)) {
			$('#treg_tips').text('短信验证码错误！');
			return false;
		}
		$('#treg_tips').text('');
		$.getJSON(URL+'/index.php?mo=register&me=mobile_reg',{flag:flag,step:step,phone:phone,code:code},function(data){
			if (data.code == 1105) {
				if (type == 'page') {
					window.location.href = URL+'/index.php?mo=register&me=index&t=mobile&s=2&n='+phone;
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
	// 设置密码
	$('#set_pwd').submit(function(){
		var flag  = $('#setpwd_flag').val();
		var step  = $('#setpwd_step').val();
		var phone = $.trim($('#setpwd_phone').val());
		var pwd   = $.trim($('#user_pass').val());
		var repwd = $.trim($('#user_repass').val());
		var type  = $('#treg_type').val();

		if (!flag) {
			$('#pass_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!phone || phone == 'undefined') {
			alert('系统错误！');
			return false;
		}
		if (!/1[34578]{1}\d{9}$/.test(phone) || phone.length < 11 || phone.length > 11) {
			alert('系统错误，请刷新页面重试！');
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			$('#pass_tips').text('请输入密码！');
			return false;
		}
		if (pwd.length < 6 || pwd.length > 20) {
			$('#pass_tips').text('密码为6-20个字符；只能包含字母大小写、数字以及标点(空格除外)');
			return false;
		}
		if (pwd != repwd) {
			$('#pass_tips').text('确认密码与密码不匹配哦！');
			return false;
		}
		$('#pass_tips').text('');
		$.getJSON(URL+'/index.php?mo=register&me=mobile_reg',{flag:flag,step:step,phone:phone,pwd:pwd,repwd:repwd},function(data){
			if (data.code == 1200) {
				if (type == 'page') {
					window.location.href = URL+'/index.php?mo=register&me=index&t=mobile&r=success';
				} else {
					$('#success_title').text('注册成功');
					$('#success_content').text('恭喜您注册成功！');
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

	// 记住账号密码
	function rememberMe(nameID)
	{
		var name = $('#'+nameID).val();
		if (name != '') {
			$.cookie('UN', name, { expires: 30, path: '/' });
		}
	}

	// 邮箱注册
	$('#mail_reg').submit(function(){
		var flag = $('#mail_flag').val();
		var step = $('#mail_step').val();
		var mail = $('#email').val();
		var code = $('#mail_code').val();

		if (!flag) {
			$('#mail_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!mail || mail == 'undefined') {
			$('#mail_tips').text('请输入邮箱！');
			return false;
		}
		if (!/[a-zA-Z0-9]+@[a-zA-Z0-9]+.[a-z]{2,4}/.test(mail)) {
			$('#mail_tips').text('邮箱格式不正确，请重新输入！');
			return false;
		}
		if (!code || code == 'undefined') {
			$('#mail_tips').text('请输入验证码！');
			return false;
		}
		if (!/^\d{4}$/.test(code)) {
			$('#mail_tips').text('验证码错误！');
			return false;
		}
		$('#mail_tips').text('');
		$.getJSON(URL+'/index.php?mo=register&me=email_reg',{flag:flag,step:step,mail:mail,code:code},function(data){
			if (data.code == 1000) {
				window.location.href = URL+'/index.php?mo=register&me=index&t=email&r=ok';
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});

	$('#mset_pwd').submit(function(){
		var flag = $('#mset_flag').val();
		var step = $('#mset_step').val();
		var mail = $('#mset_name').val();
		var pwd = $('#imset_pwd').val();
		var repwd = $('#mset_repwd').val();

		if (!flag) {
			$('#mset_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!mail || mail == 'undefined') {
			alert('系统错误！请刷新页面重试');
			return false;
		}
		if (!/[a-zA-Z0-9]+@[a-zA-Z0-9]+.[a-z]{2,4}/.test(mail)) {
			alert('系统错误！请刷新页面重试');
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			$('#mset_tips').text('请输入密码！');
			return false;
		}
		if (pwd.length < 6 || pwd.length > 20) {
			$('#mset_tips').text('密码为6-20个字符；只能包含字母大小写、数字以及标点(空格除外)');
			return false;
		}
		if (pwd != repwd) {
			$('#mset_tips').text('确认密码与密码不匹配哦！');
			return false;
		}
		$('#mset_tips').text('');
		$.getJSON(URL+'/index.php?mo=register&me=email_reg',{flag:flag,step:step,mail:mail,pwd:pwd,repwd:repwd},function(data){
			if (data.code == 2000) {
				window.location.href = URL+'/index.php?mo=register&me=index&t=mobile&r=success';
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;

	});

	// 找回密码
	// 查询账号
	$('#find_bind_info').submit(function(){
		var flag = $('#flag').val();
		var name = $('#fpwd_name').val();
		var code = $('#fpwd_code').val();
		if (!flag || flag == 'undefined') {
			$('#step1_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!name || name == 'undefined') {
			$('#step1_tips').text('请输入账号');
			return false;
		}
		if (!code || code == 'undefined') {
			$('#step1_tips').text('请输入验证码！');
			return false;
		}
		if (!/^\d{4}$/.test(code)) {
			$('#step1_tips').text('验证码错误！');
			return false;
		}
		$('#step1_tips').text('');
		$.getJSON(URL+'/index.php?mo=forgetpwd&me=index',{flag:flag,name:name,code:code},function(data){
			if (data.code == 1000) {
				window.location.href = URL+'/index.php?mo=forgetpwd&me=choose_way';
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});

	$('#phone_way_getpwd').submit(function(){
		var flag = $('#phone_flag').val();
		var step = $('#phone_step').val();
		var type = $('#phone_type').val();
		var code = $('#smscode').val();
		if (!flag || flag == 'undefined') {
			$('#step3_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!step || step == 'undefined') {
			$('#step3_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!type || type == 'undefined') {
			$('#step3_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!code || code == 'undefined') {
			$('#step3_tips').text('请输入验证码！');
			return false;
		}
		if (!/^\d{6}$/.test(code)) {
			$('#step3_tips').text('验证码错误！');
			return false;
		}
		$('#step3_tips').text('');
		$.getJSON(URL+'/index.php?mo=forgetpwd&me=phone_getpwd',{flag:flag,code:code,step:step,type:type},function(data){
			if (data.code == 1000) {
				window.location.href = URL+'/index.php?mo=forgetpwd&me=set_newpwd';
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});

	$('#set_newpwd').submit(function(){
		var flag  = $('#setpwd_flag').val();
		var step  = $('#setpwd_step').val();
		var pwd   = $('#set_new_pwd').val();
		var repwd = $('#reset_new_pwd').val();
		if (!flag || flag == 'undefined') {
			$('#step4_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!step || step == 'undefined') {
			$('#step4_tips').text('非法操作！请按正常流程注册哦！');
			return false;
		}
		if (!pwd || pwd == 'undefined') {
			$('#step4_tips').text('请设置新密码！');
			return false;
		}
		if (pwd.length < 6 || pwd.length > 20) {
			$('#step4_tips').text('密码为6-20个字符；只能包含字母大小写、数字以及标点(空格除外)');
			return false;
		}
		if (pwd != repwd) {
			$('#step4_tips').text('确认密码与密码不匹配哦！');
			return false;
		}
		$('#step4_tips').text('');
		$.getJSON(URL+'/index.php?mo=forgetpwd&me=set_newpwd',{flag:flag,pwd:pwd,step:step,repwd:repwd},function(data){
			if (data.code == 1000) {
				window.location.href = URL+'/index.php?mo=forgetpwd&me=setpwd_success';
			} else {
				alert(data.msg);
				return false;
			}
		});
		return false;
	});

});