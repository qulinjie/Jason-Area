
$(function () {
	
	function do_encrypt( pwd ) {
        var public_key='00a037b186d9dc36e8d9738df7080016686b9e1791f3b2e7c506c16f44087af150b9b18dc6fb6810f29811f29dffc60169c0bc43c90e005f77a8a39e43a1a7edcca2f4bf430f0eb8fabee9360c02c084ae9b7b7a939915142f160fb032cf70d16c08506d83537929f725315333b306031f2c0bd02b0b75ab2fd81577c8d98b497f';
        var public_length='10001';
        var rsa = new RSAKey();
        rsa.setPublic(public_key, public_length);
        var res = rsa.encrypt(pwd);
        return res ? res: '';
    }
	
	
    function checkForm(selector) {
        var msg    = '';
        var elem   = '';
        var error  = false;
        var config = {         
    		'mobile':     {'required': true, 'reg': /^[0-9]{11}$/},
            'code':    {'required': true, 'reg': /^[0-9]{6}$/},	        		
            'password':{'required': true, 'reg': /^[0-9A-Za-z]{3,20}$/},
            'rePassword':  {'required': true, 'equalTo': '#password'}
        };
        var conMsg = { 	
        	'mobile':     {'required': '请输入手机号码',   'reg': '请输入正确的手机号码'},
            'code':    {'required': '请输入短信验证码', 'reg': '请输入正确的验证码'},
            'password':{'required': '请输入支付密码', 'reg': '必须由3-20个数字或字母组成'},
            'rePassword':{'required': '请再次输入密码', 'equalTo': '两次输入密码不一致'}
        };
        if (selector == undefined) {selector = '#password, #rePassword, #code';}
        $(selector).each(function () {
            if (config[$(this).attr('id')] != undefined) {
                var rules = config[$(this).attr('id')];
                for (var key in rules) {
                    switch (key) {
                        case 'required':
                            msg = rules[key] && !$(this).val()? conMsg[$(this).attr('id')]['required'] : '';
                            break;
                        case 'reg':
                            msg = !rules[key].test($(this).val()) ? conMsg[$(this).attr('id')]['reg'] : '';
                            break;
                        case 'equalTo':
                            msg = $(rules[key]).val()!=$(this).val() ? conMsg[$(this).attr('id')]['equalTo'] : '';
                            break;
                    }
                    if (msg) error = true;
                    elem = $('#'+$(this).attr('id')+'Msg');
                    ((!elem.text()&& msg) || (elem.text()&& !msg)) && elem.text(msg);
                }
            }
        });
        return error;
    }
	
	$('#password, #code').blur(function () {checkForm('#' + $(this).attr('id'));});
	
	/**************begin--设置****************/
    $('#pay-pwd-set').click(function(){    	
        if(checkForm()) return false;
        $(this).attr('disabled','disabled');
        var data   = {};        
        data.pay_pwd   = hex2b64(do_encrypt($('#password').val()));       
        data.token = $('#token').val();
        $.post(BASE_PATH + 'payPassword/set',data,function(result){           	
            if(result.code != 0) {
           	 	$('#pay-pwd-set').removeAttr('disabled');
                $("#entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
                $("#entity-hint").html("设置成功,请重新登录！").fadeIn();
                setTimeout(function(){
                	$('#logoutBtn').click();
            	},1000);                
            }
        },'json');
        
    });
    
    /**************end--设置****************/
    
    /**************begin--重置****************/
    $('#pay-pwd-reset').click(function(){    	
        if(checkForm()) return false;
        $('#pay-pwd-reset').attr('disabled','disabled');
        var data   = {};       
        data.code   = $('#code').val(); 
        data.pay_pwd   = hex2b64(do_encrypt($('#password').val()));        
        data.token = $('#token').val();        
        $.post(BASE_PATH + 'payPassword/reset',data,function(result){           	
            if(result.code != 0) {
           	 	$('#pay-pwd-reset').removeAttr('disabled');
                $("#entity-hint").html(result.msg + '(' + result.code + ')').fadeIn();
            }else {
                $("#entity-hint").html("设置成功,请重新登录！").fadeIn();
                setTimeout(function(){
                	$('#logoutBtn').click();
            	},1000);                
            }
        },'json');
        
    });
    
    $('#sendCode').click(function(){ 
        var obj      = $(this);
        var index    = -1;
        var timer    = null;
        var currTime = 60;
        if(checkForm('#mobile')) return false; 
        obj.attr('disabled','disabled');        
        timer = setInterval(function(){
            index =obj.val().lastIndexOf('(');
            if(index>=0){
                currTime = parseInt(obj.val().substr(index+1));
                currTime--;
                if(currTime == 0){
                    clearInterval(timer);
                    obj.removeAttr('disabled').val('重新获取验证码');
                }else{
                    obj.val(obj.val().substr(0,index)+'('+currTime+')秒后再次发送');
                }
            }
        },1000);
        obj.val('('+currTime+')秒后再次发送');        
        var mobile = $('#mobile').val(); 
		$.post(BASE_PATH + 'sms/sendSmsVerificationCode', {
		        'mobile':mobile,
		        'codetype':12
		    },
		    function(result){	
		    	if(result.code == 5000){
		    		clearInterval(timer);
	                obj.removeAttr('disabled').val('重新获取验证码');
	                $("#entity-hint").html('发送短信失败：' + result.msg + '(' + result.code + ')').fadeIn();
		    	}
		    },
		    'json'
	    );
		
        /*$.post(BASE_PATH+'payPassword/sendSmsCode',{token:$('#token').val()},function(res){
            if (res.code == 0) {
                index = obj.html().lastIndexOf('(');
                if (index >= 0) {
                    currTime = parseInt(obj.text().substr(index + 1));
                    obj.text('成功发送(' + currTime + ')秒');
                }
            } else if(res.code == 106){
                clearInterval(timer);
                obj.removeAttr('disabled').text('获取短信验证码');
                alert(res.msg);
                //$('#tel').parent().parent().children(':last-child').find('span').text(res.msg);
            } else{
                clearInterval(timer);
                obj.removeAttr('disabled').text('获取短信验证码');
                alert(res.msg);
            }
        }, 'json');*/
    });
    
    /**************end--重置****************/
    
});