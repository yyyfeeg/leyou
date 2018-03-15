/**
 * jquery.usercenter.js
 * version: 1.0
 * desc: A collection of commonly used functions
 * author: Tang 799345505@qq.com
 * copyright: Teamtopgame technical department
 */
(function(){
	usercenter = {

		// Default configuration
		config:{},

		// check user login
		init:function(){},

		// certified user infomation
		certified:function(id,style,id2,tips){
			$('#'+id).addClass(style);
			$('#'+id+' span').text(tips);
			if (id2 != '') {
				$('#'+id+' a').click(function(){
					$('#pop-ups').show();
					$('#'+id2).show(300);
				});
			}
		},

		// binding user phone
		bindPhone:function(flagID,nameID,actID,phoneID,codeID,tipsID,url,successID,maskID,hideArrID){
			var flag = $('#'+flagID).val();
			var name = $('#'+nameID).val();
			var act  = $('#'+actID).val();
			var phone = $('#'+phoneID).val();
			var code = $('#'+codeID).val();
			var length = phone.length;
			var regex = /^1[3|4|5|7|8][0-9]\d{4,8}$/;

			if (!flag || flag == 'undefined') {
				$('#'+tipsID).text('系统错误，请刷新页面重试!').show();
            	return false;
			}
			if (!name || name == 'undefined') {
				$('#'+tipsID).text('系统错误，请刷新页面重试!').show();
            	return false;
			}
			if (!act || act == 'undefined') {
				$('#'+tipsID).text('系统错误，请刷新页面重试!').show();
            	return false;
			}
			if (!phone || phone == 'undefined') {
				$('#'+tipsID).text('请填写手机号!').show();
            	return false;
			}
			if (length != 11 || !regex.test(phone)) {
				$('#'+tipsID).text('手机号不正确!').show();
            	return false;
			}
			if (!code || code == 'undefined') {
				$('#'+tipsID).text('请输入验证码!').show();
            	return false;
			}
			if (!/^[0-9]{6}$/.test(code)) {
				$('#'+tipsID).text('验证码不正确!').show();
            	return false;
			}
			$('#'+tipsID).html('').hide();
			$.getJSON(url+'/index.php',{flag:flag,name:name,act:act,mo:'user_center',me:'bing',tel:phone,code:code},function(data){
				if (data.code == 1000) {
					$('#bind-phone').hide();
					$('#'+successID).show(300);
					setTimeout("$('#"+successID+"').hide(100);history.go(0);",2000);
				} else {
					alert(data.msg);
					return false;
				}
			});
		},

		// binding user email
		bindEmail:function(flagID,nameID,actID,mailID,tipsID,url,successID,maskID,hideArrID){
			var flag = $('#'+flagID).val();
			var name = $('#'+nameID).val();
			var act  = $('#'+actID).val();
			var mail = $('#'+mailID).val();

			if (!flag || flag == 'undefined') {
				$('#'+tipsID).text('系统错误，请刷新页面重试!').show();
            	return false;
			}
			if (!name || name == 'undefined') {
				$('#'+tipsID).text('系统错误，请刷新页面重试!').show();
            	return false;
			}
			if (!act || act == 'undefined') {
				$('#'+tipsID).text('系统错误，请刷新页面重试!').show();
            	return false;
			}
			if (!mail || mail == 'undefined') {
				$('#'+tipsID).text('请填写邮箱地址!').show();
            	return false;
			}
			if (!base.emailCheck(mail)) {
				$('#'+tipsID).text('邮箱地址格式不正确!').show();
            	return false;
			}
			$('#'+tipsID).html('').hide();
			$.getJSON(url+'/index.php',{flag:flag,name:name,act:act,mail:mail,mo:'user_center',me:'bing'},function(data){
				if (data.code == 1000) {
					$('#bind-email').hide();
					$('#'+successID).show(300);
					setTimeout("$('#"+successID+"').hide(100);$('#"+maskID+"').hide();",2000);
				} else {
					alert(data.msg);
					return false;
				}
			});
		},
		
		// change user password
		changePassword:function(flagID,nameID,oldpassID,newpassID,repassID,tipsID,url,successID,maskID,hideArrID){
			var flag    = $('#'+flagID).val();
			var name    = $('#'+nameID).val();
			var oldpass = $('#'+oldpassID).val();
			var newpass = $('#'+newpassID).val();
			var repass  = $('#'+repassID).val();
			if (flag == '' || flag == 'undefined') {
				$('#'+tipsID).text('系统错误，请刷新页面重试!').show();
            	return false;
			}
			if (name == '' || name == 'undefined') {
				$('#'+tipsID).text('系统错误，请刷新页面重试!').show();
            	return false;
			}
			if (oldpass == '' || oldpass == 'undefined') {
				$('#'+tipsID).text('请输入旧密码!').show();
            	return false;
			}
			if (newpass == '' || newpass == 'undefined') {
				$('#'+tipsID).text('请设置新密码!').show();
            	return false;
			}
			if (repass == '' || repass == 'undefined') {
				$('#'+tipsID).text('请确认新密码!').show();
            	return false;
			}
			if (newpass != repass) {
				$('#'+tipsID).text('新密码与确认新密码不一致!').show();
            	return false;
			}
			if (newpass == oldpass) {
				$('#'+tipsID).text('新密码不可以与旧密码相同哦!').show();
            	return false;
			}
			$('#'+tipsID).html('').hide();
			$.getJSON(url+'/index.php',{flag:flag,name:name,oldpwd:oldpass,newpwd:newpass,renewpwd:repass,mo:'user_center',me:'changePwd'},function(result){
				if (result.code == 1000) {
					$('#success_title').text('修改成功');
					$('#success_content').text('恭喜您修改密码成功！');
					base.displayHandle(successID,maskID,hideArrID,'h',300);
					$('#popupbox_wrap').show();
					$('#reg_success').show(300);
					setTimeout("$('#reg_success').hide();history.go(0);",3000);
				} else {
					alert(result.msg);
					return false;
				}
			});
		},

	};
})();