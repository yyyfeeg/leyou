//提交问题保存
function save_send_func()
{	
	var tid = $("#tid").val();//团队ID
	var gid = $("#gid").val();//游戏ID
	var sid = $("#sid").val();//游戏服ID
	var uid = $("#uid").val();//CRM用户ID
	var uname = $("#uname").val();//游戏角色ID/昵称
	var type = $("#type").val();//问题类型
	var title = $("#title").val();
	var content = $("#content").val();
	var tel = $("#tel").val();
	
	if(type==''){
		alert('请选择问题类型！');
		return false;
	}
	if(title==''){
		alert('请输入问题标题！');
		return false;
	}
	if(sid==''){
		alert('请选择游戏区服！');
		return false;
	}
	if(uname==''){
		alert('请输入游戏角色ID！');
		return false;
	}
	if(tel==''){
		alert('请输入联系电话！');
		return false;
	}
	if(content==''){
		alert('请详细描述问题！');
		return false;
	}
	$.getJSON(URL+"/api/service/service.api.php?act=5&tid="+tid+"&gid="+gid+"&sid="+sid+"&uid="+uid+"&type="+type+"&title="+title+"&content="+content+"&tel="+tel+"&uname="+uname,function(res){
		if( res.s == 1 )
		{
			alert(res.m);
			window.location.href=URL+"/index.php?mo=service&me=mylading";
		}else if( res.s == 1001 )
		{
			alert("请先登录后再提交问题！");
			$(".popupbox_wrap").show();
			$(".popupbox_login").show();
		}else{
			alert(res.m);
		}
	});	
}

$(document).ready(function(e) {
	// 登录
	$('#user_login').submit(function(){
		var flag = $.trim($('#flag').val());
		var type = $.trim($('#type').val());
		var jump = $.trim($('#jump').val());
		var name = $.trim($('#username').val());
		var pwd  = $.trim($('#password').val());
		var code = $.trim($('#checkcode').val());
		var reme = $("#rememberMe").is(':checked');

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
	
	//显示玩家问题详细
	var onli=-1;
	$(".wtlb_txt_li_top").click(function(){
		thisli=$(this).parent().index();
		if(thisli != onli && onli != -1){
	
			$("#wtlb_txt li").eq(onli).addClass("two_li");
			$("#wtlb_txt li").eq(onli).children(".wtlb_txt_li_bottom").hide();
		}
		$(this).parent().toggleClass("two_li");
		$(this).siblings(".wtlb_txt_li_bottom").toggle();
		onli=thisli;
	})
	
	//弹出层关闭
	$(".popupbox_close").click(function(){
		$(".popupbox_wrap").hide();
		$(".popupbox_login").hide();
	})
	
	//点击我的提单时
	$("#mylading").click(function(){
		loginstatus(1,"",$(this));
	})
	
	//点击我的提单分页时
	$("#ladinglist li a").click(function(){
		loginstatus(1,"",$(this));
	})
	
	//点击我的提单中：处理中的问题/已处理的问题
	$(".td_type a").click(function(){
		loginstatus(1,"",$(this));
	})
	
	//点击已处理的问题中的时间选项
	$(".td_time a").click(function(){
		loginstatus(1,"",$(this));
	})
	
	//选择问题分类（大类）时
	$(".wt_type_choice").click(function(){
		loginstatus(1,"",$(this));
	})
	
	//点击官方QQ验证
//	$("#nav_qqverify").click(function(){
//		loginstatus(1,"",$(this));
//	})

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
});

//登录超时检测：tips：1为无提示，2为提示；msg提示内容，为空时提示reg返回值
so_href="";
function loginstatus(tips,msg,object){
	$.ajax({
		type: 'POST',
		async:false,
		url:URL+"/index.php?mo=service&me=checklogin",
		data:{},
		dataType: "json",
		success: function(res){
			if( res.s == 1001 )
			{
				so_href=object.attr("href");
				object.attr("href","javascript:void(0);");
				if(tips==2){
					msg==""?alert(res.m):alert(msg);
				}
				$(".popupbox_wrap").show();
				$(".popupbox_login").show();
				return false;
			}else{
				if(so_href != ""){
					object.attr("href",so_href);
				}
				so_href="";
				return true;
			}
		}
	});	
}

//玩家问题评价
function award_send_func(tid,gid,uid,id)
{
	var award=$('input:radio[name="Fruit'+id+'"]:checked').val();
	$.getJSON(URL+"/api/service/service.api.php?act=7&tid="+tid+"&gid="+gid+"&uid="+uid+"&award="+award+"&id="+id,function(res){
		if( res.s == 1 )
		{
			alert(res.m);
			switch(award){
				case "2":
					$("#award_"+id).html("满意");
				break;
				case "4":
					$("#award_"+id).html("一般");
				break;
				case "5":
					$("#award_"+id).html("不满意");
				break;
			}
		}else if( res.s == 1001 )
		{
			alert("登录超时，请重新登录后再操作！");
			$(".popupbox_wrap").show();
			$(".popupbox_login").show();
		}else{
			alert(res.m);
		}		
	});		
}

//QQ验证
function qq_send_func()
{
	var qq = $("#qq").val();
	if(qq==""){
		$("#verifyresult").html('<span style=" color:#F00;">请输入你想验证是否为官方客服的QQ</span>');
		return false;
	}
	$.getJSON(URL+"/api/service/service.api.php?act=8&qq="+qq,function(res){
		if(res.s==1){
			$("#verifyresult").html('<span style=" color:#0C0;">'+res.m+'</span>');	
		}else{
			$("#verifyresult").html('<span style=" color:#F00;">'+res.m+'</span>');	
		}
		
	});		
}
