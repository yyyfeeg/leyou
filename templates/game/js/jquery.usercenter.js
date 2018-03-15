$(function(){
	// 提交绑定手机
	$('#sub_bindphone').click(function(){
		var flag   = $.trim($('#bp_flag').val());
		var name   = $.trim($('#bp_name').val());
		var act    = $.trim($('#bp_act').val());
		var phone  = $.trim($('#tel').val());
		var code   = $.trim($('#smscode').val());
		var token  = $.trim($('#token').val());
		var length = phone.length;
		var regex  = /^1[3|4|5|7|8][0-9]\d{4,8}$/;

		if (!flag || flag == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!name || name == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!act || act == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!phone || phone == 'undefined') {
			$('#bp_tips').text('请填写手机号!').show();
			return false;
		}
		if (length != 11 || !regex.test(phone)) {
			$('#bp_tips').text('手机号不正确!').show();
			return false;
		}
		if (!code || code == 'undefined') {
			$('#bp_tips').text('请输入验证码!').show();
			return false;
		}
		if (!/^[0-9]{6}$/.test(code)) {
			$('#bp_tips').text('验证码不正确!').show();
			return false;
		}
		$('#bp_tips').html('').hide();
		$.getJSON(URL+'/index.php',{flag:flag,name:name,act:act,mo:'g_users',me:'bing',tel:phone,code:code,v:token},function(data){
			if (data.code == 1000) {
				window.location.href = URL+'/index.php?mo=g_users&me=show_act&r=success&t=bp&v=' + token;
			} else {
				alert(data.msg);
				return false;
			}
		});
	});

	// 验证真实姓名
	$('#truename').focusout(function(){
		var flag  = $.trim($('#bp_flag').val());
		var name  = $.trim($('#bp_name').val());
		var tname = $.trim($(this).val());
		var token  = $.trim($('#token').val());
		if (!flag || flag == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!name || name == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!tname || tname == 'undefined') {
			$('#bp_tips').text('请输入真实姓名！').show();
			return false;
		}
		if (!is_cn(tname)) {
			$('#bp_tips').text('请输入正确的姓名！').show();
			return false;
		}
		$('#bp_tips').html('').hide();
		getJson({flag:flag,name:name,act:3,mo:'g_users',me:'bing',tname:tname,v:token},function(res){
			if (res.code == 3000) {
				$('#tn_flag').attr('value','2');
				$('#bp_tips').html('').hide();
			} else {
				$('#bp_tips').text(res.msg).show();
				return false;
			}
		},URL+'/index.php','GET',true);

	});

	// 验证身份证号
	$('#idnum').focusout(function(){
		var flag  = $.trim($('#bp_flag').val());
		var name  = $.trim($('#bp_name').val());
		var idnum = $.trim($('#idnum').val());
		var token  = $.trim($('#token').val());
		if (!flag || flag == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!name || name == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!idnum || idnum == 'undefined') {
			$('#bp_tips').text('请输入身份证号！').show();
			return false;
		}
		if (!is_num(idnum)) {
			$('#bp_tips').text('请输入正确的身份证号！').show();
			return false;
		}
		if (!isIdCardNo(idnum)) {
			$('#bp_tips').text('请输入正确的身份证号！').show();
			return false;
		}
		$('#bp_tips').html('').hide();
		getJson({flag:flag,name:name,act:4,mo:'g_users',me:'bing',idnum:idnum,v:token},function(res){
			if (res.code == 4000) {
				$('#id_flag').attr('value','2');
				$('#bp_tips').html('').hide();
			} else {
				$('#bp_tips').text(res.msg).show();
				return false;
			}
		},URL+'/index.php','GET',true);
	});

	// 提交实名认证
	$('#legalize').click(function(){
		var flag  = $.trim($('#bp_flag').val());
		var name  = $.trim($('#bp_name').val());
		var tname = $.trim($('#truename').val());
		var idnum = $.trim($('#idnum').val());
		var act   = $.trim($('#bp_act').val());
		var tn_flag = $.trim($('#tn_flag').val());
		var id_flag = $.trim($('#id_flag').val());
		var token  = $.trim($('#token').val());
		if (!flag || flag == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!name || name == 'undefined') {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		if (!tname || tname == 'undefined') {
			$('#bp_tips').text('请输入真实姓名！').show();
			return false;
		}
		if (!is_cn(tname)) {
			$('#bp_tips').text('请输入正确的姓名！').show();
			return false;
		}
		if (!idnum || idnum == 'undefined') {
			$('#bp_tips').text('请输入身份证号！').show();
			return false;
		}
		if (!is_num(idnum)) {
			$('#bp_tips').text('请输入正确的身份证号！').show();
			return false;
		}
		if (!isIdCardNo(idnum)) {
			$('#bp_tips').text('请输入正确的身份证号！').show();
			return false;
		}
		if (tn_flag != 2 || id_flag !=2 ) {
			$('#bp_tips').text('系统错误，请刷新页面重试!').show();
			return false;
		}
		getJson({flag:flag,name:name,act:act,mo:'g_users',me:'bing',tname:tname,idnum:idnum,v:token},function(res){
			if (res.code == 5000) {
				window.location.href = URL+'/index.php?mo=g_users&me=show_act&r=success&t=rz&v=' + token;
			} else {
				alert(res.msg);
				return false;
			}
		},URL+'/index.php','GET',true);

	});
});

function isIdCardNo(myidNumber) 
{
    var factorArr = new Array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1);
	var aCity={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"};
    var error;
    var varArray = new Array();
    var intValue;
    var lngProduct = 0;
    var intCheckDigit;
    var idNumber = myidNumber;
	 var intStrLen = idNumber.length;
    if ((intStrLen != 15) && (intStrLen != 18)) {
//        error = "输入身份证号码长度不对！";
//        alert(error);
        return false;
    }
	if(aCity[parseInt(idNumber.substring(0,2))]==null){
//            error = "地区错误！.";
//            alert(error);
		return false;	
	}
    // check and set value
    for(i=0;i<intStrLen;i++) {
        varArray[i] = idNumber.charAt(i);
        if ((varArray[i] < '0' || varArray[i] > '9') && (i != 17)) {
//            error = "错误的身份证号码！.";
//            alert(error);
            return false;
        } else if (i < 17) {
            varArray[i] = varArray[i]*factorArr[i];
        }
    }
    if (intStrLen == 18) {
        //check date
        var date8 = idNumber.substring(6,14);
        if (checkDate(date8,18) == false) {
//            error = "身份证中日期信息不正确！.";
//            alert(error);
            return false;
        }        
        // calculate the sum of the products
        for(i=0;i<17;i++) {
            lngProduct = lngProduct + varArray[i];
        }        
        // calculate the check digit
        intCheckDigit = 12 - lngProduct % 11;
        switch (intCheckDigit) {
            case 10:
                intCheckDigit = 'X';
                break;
            case 11:
                intCheckDigit = 0;
                break;
            case 12:
                intCheckDigit = 1;
                break;
        }        
        // check last digit
        if (varArray[17].toUpperCase() != intCheckDigit) {
//            error = "身份证效验位错误!...正确为： " + intCheckDigit + ".";
//            alert(error);
            return false;
        }
    } 
    else{        //length is 15
        //check date
        var date6 = idNumber.substring(6,12);
        if (checkDate(date6,15) == false) {
           // alert("身份证日期信息有误！.");
            return false;
        }
    }
    //alert ("Correct.");
    return true;
}

function checkDate(date,len){
	var re;
	if(len==15){		
		re = new RegExp(/^(\d{2})(\d{2})(\d{2})$/); 
	}else{
		re = new RegExp(/^(\d{4})(\d{2})(\d{2})$/);
		
	}
	var a = date.match(re);
	if (a != null){
	      if (len==15){
		  var D = new Date("19"+a[1]+"/"+a[2]+"/"+a[3]);
		  var B = D.getYear()==a[1]&&(D.getMonth()+1)==a[2]&&D.getDate()==a[3];
		  }else{
			var D = new Date(a[1]+"/"+a[2]+"/"+a[3]);
			var B = D.getFullYear()==a[1]&&(D.getMonth()+1)==a[2]&&D.getDate()==a[3];
		  }
		  if (!B){
			return false;
		  }else{
			return true;  
		  }
	 }else{
		return false;	 
	 }
}

/**
 * 判断是否为中文字符
 * @param  {[type]}  str 需检查的字符串
 * @return {Boolean}     true：为中文字符 false：否
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
 * 判断是否为数字
 * @param  {[type]}  str 需检查的字符串
 * @return {Boolean}     true：为数字 false：否
 */
function is_num(str)
{
	var re = /^[\d]+$/;
	return re.test(str);
}