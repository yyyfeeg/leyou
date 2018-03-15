/**
 * jquery.handle.js
 * version: 1.0
 * desc: 前端处理JS文件
 * author: Tang 799345505@qq.com
 */

// 礼包处理
function give_gifts(id,gid,url)
{
	if (id == 1) {
		
		
	} else if (id == 2) {
		// 领取cdkey
		getJson('',function(result){
			if (result != '') {
				if (result.code == 2000) {
					cdkey_tips(result.data.cdkey);
					return false;
				} else if (result.code == '-1') {
					all_tips('请先登录<br/>2秒后自动跳转....',result.data.gotoUrl,2000);
					return false;
				} else {
					all_tips(result.msg,'','');
					return false;
				}
			} else {
				alert('网络繁忙！');
				return false;
			}
		},
		url,'POST');
	} else {
		alert('非法操作！请按正常流程领取礼包哦！');
		return false;
	}
}

// 获取json数据
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

// 全局提示窗口
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