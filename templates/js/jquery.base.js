/**
 * jquery.base.js
 * version: 1.0
 * desc: A collection of commonly used functions
 * author: Tang 799345505@qq.com
 */
;(function(){

	window.base = {

		// Default configuration
		config:{},

		// Devices Type
		devType: function(){
			var ua = navigator.userAgent.toLowerCase();
			var sys = /iphone|android|nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|wap|mobile/i;
			if (sys.test(ua)) {
				// Mobile devices
				return true;
			} else {
				// PC devices
				return false;
			}
		},

		// WeiXin open
		wxOpen: function(){
			var ua = navigator.userAgent.toLowerCase();
			if (ua.match(/micromessenger/i) == 'micromessenger') {
				// WeiXin open
				return true;
			} else {
				// Other open
				return false;
			}
		},

		// Chinese judgment
		isCN: function(string){
			if (/^([\u4E00-\uFA29]|[\uE7C7-\uE7F3])*$/.test(string)) {
				// Containing Chinese
				return true;
			} else {
				// Not Containing Chinese
				return false;
			}
		},

		// Check the mailbox format
		emailCheck: function(email){
			var re = /^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
			if (re.test(email)) {
				// Proper
				return true;
			} else {
				// Error
				return false;
			}
		},

		// Check the string is number
		isNumber: function(string){
			if (/^[\d]+$/.test(string)) {
				// Is number
				return true;
			} else {
				// Not is number
				return false;
			}
		},

		// Merge array
		arrayHandle: function(keyArr,valueArr){

			var key    = keyArr instanceof Array;
			var value  = valueArr instanceof Array;
			var tmpArr = new Array();

			if (!key || !value) {
				console.error('Parameter is incorrect');
				return false;
			}

			for (var i = 0; i < keyArr.length; i++) {
				tmpArr[keyArr[i]] = valueArr[i];
			}
			return tmpArr;
		},

		// Click to locate
		scrollClick: function(id,px,speed){
			$('#'+id).click(function(){
				$('html body').animate({scrollTop: px+'px'}, speed);
			});
		},

		// Download process
		urlHandle: function(idArr){
			if (idArr.length > 0) {
				for (i in idArr) {
					var str = $(idArr[i]).attr('href');
					var strArr  = str.split('#');
					var strArr1 = str.split('https://');
					if (strArr.length > 1) {
						$(idArr[i]).attr('href', 'javascript:alert("敬请期待！");')
					}
					if (strArr1.length > 1) {
						var url = 'https://'+strArr1[1];
						$(idArr[i]).attr('href', url);
					}
				}
			} else {
				return '';
			}
		},

		// Pop-up layer display handle
		displayHandle: function(actionID,maskID,hideArr,type,speed){
			if (actionID == '' || type == '') {
				alert('Parameter is incorrect');
				return false;
			}
			if (hideArr.length > 0) {
				for (i in hideArr) {
					$('#'+hideArr[i]).hide();
				}
			}
			if (type == 's') {
				if (maskID != '') {
					$('#'+maskID).show();
				}
				$('#'+actionID).show(speed);
			}
			if (type == 'h') {
				$('#'+actionID).hide(speed);
				if (maskID != '') {
					$('#'+maskID).hide();
				}
			}
		},

	};
})();