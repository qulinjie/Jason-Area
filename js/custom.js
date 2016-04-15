
function do_encrypt(pwd) {
    var public_key = "00a037b186d9dc36e8d9738df7080016686b9e1791f3b2e7c506c16f44087af150b9b18dc6fb6810f29811f29dffc60169c0bc43c90e005f77a8a39e43a1a7edcca2f4bf430f0eb8fabee9360c02c084ae9b7b7a939915142f160fb032cf70d16c08506d83537929f725315333b306031f2c0bd02b0b75ab2fd81577c8d98b497f";
    var public_length = "10001";
    var rsa = new RSAKey();
    rsa.setPublic(public_key, public_length);
    var res = rsa.encrypt(pwd);
    if (res) {
        return res;
    } else {
        return '';
    }
}

$(function () {
	$(document).on('click', 'a[href=#]', function(e){
		e.preventDefault();
	});
    /*************************** global start ******************************/
    function checkForm(selector) {
        var msg    = '';
        var elem   = '';
        var error  = false;
        var config = {
            'loginOldPwd'   :{'required': true},
            'loginNewPwd'   :{'required': true},
            'loginNewPwd2'  :{'required': true, 'equalTo': '#loginNewPwd'},
            'initPayNewPwd' :{'required': true},
            'initPayNewPwd2':{'required': true, 'equalTo': '#initPayNewPwd'},
            'payOldPwd'     :{'required': true},
            'payNewPwd'     :{'required': true},
            'payNewPwd2'    :{'required': true, 'equalTo': '#payNewPwd'}
        };
        var conMsg = {
            'loginOldPwd'   :{'required': '请输入当前密码'},
            'loginNewPwd'   :{'required': '请输入新登录密码'},
            'loginNewPwd2'  :{'required': '请再次输入新登录密码', 'equalTo': '两次输入密码不一致'},
            'initPayNewPwd' :{'required': '请输入支付密码'},
            'initPayNewPwd2':{'required': '请再次输入支付密码', 'equalTo': '两次输入密码不一致'},
            'payOldPwd'     :{'required': '请输入当前支付密码'},
            'payNewPwd'     :{'required': '请输入新支付密码'},
            'payNewPwd2'    :{'required': '请再次输入新支付密码', 'equalTo': '两次输入密码不一致'}
        };
        if (selector == undefined) {selector = ':password,:text,:file';}
        $('form  ' + selector).each(function () {
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
    $('form :password').blur(function(){
        checkForm('#'+$(this).attr('id'));
    });
    $('#logoutBtn').click(function () {
        $.post(BASE_PATH + 'user/logout', {'token':$('#token').val()}, function (result) {
            if (result.code == 0) {
                window.location.href = BASE_PATH;
            }else{
                alert(result.msg+'('+result.code+')');
            }
        }, 'json');
    });
    /**********************************global end*********************************************/
    
    if ($('#isLogin').length) {
        /**********************************index start*********************************************/
       $(".form_datetime").datetimepicker({
            language: 'zh-CN',
            format: 'yyyy-mm-dd hh:ii',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 0
        });
        if ($("#isLogin").val()) {
            if ($("#menuList") && 0 < $("#menuList").length && !$("#menuList").find('.active').length) {
                window.location.href = $('#menuList li:eq(0)').find('a').attr('href');
            }
        } else {
            window.location.href = BASE_PATH + 'user/login';
        }
        $('#loginPasswordReset').click(function(){
            if(checkForm()){ return false;}
            var data = {};
            data.token  = $('#token').val();
            data.oldPwd = hex2b64(do_encrypt($('#loginOldPwd').val()));
            data.newPwd = hex2b64(do_encrypt($('#loginNewPwd').val()));
            $.post(BASE_PATH+'user/passwordReset',data,function(res){
                var str = '';
                if(res.code == 0){
                    str = '<div class="hint"><div class="hint_tb"><img src="'+BASE_PATH+'view/images/bt_02.jpg"/><span>恭喜，您已成功重置登录密码！</span></div><div class="clear"></div> <div class="hint_lj"><a href="">返回账户安全</a><a href="'+BASE_PATH+'tradeRecord/getIndex">返回我的大大付款</a></div></div>';
                    $('.reset').html(str);
                }else{
                    switch (res.code){
                        case 111:
                            $('#loginOldPwdMsg').text(res.msg);
                            break;
                        default:
                            alert(res.msg+'('+res.code+')');
                    }
                    $('#loginPasswordReset').removeAttr('disabled');
                }
            },'json');
        });
        $('#payPasswordReset').click(function(){
            if(checkForm()){return false;}
            var data = {token:$('#token').val()};
            if($('#initPayNewPwd').length){
                data.newPwd = hex2b64(do_encrypt($('#initPayNewPwd').val()));
            }else{
                data.newPwd = hex2b64(do_encrypt($('#payNewPwd').val()));
                data.oldPwd = hex2b64(do_encrypt($('#payOldPwd').val()));
            }
            $(this).attr('disabled','disabled');
            $.post(BASE_PATH+'payPassword/reset',data,function(res){
                var str = '';
                if(res.code == 0){
                    str = '<div class="hint"><div class="hint_tb"><img src="'+BASE_PATH+'view/images/bt_02.jpg"/><span>恭喜，您已成功重置支付密码！</span></div><div class="clear"></div> <div class="hint_lj"><a href="">返回账户安全</a><a href="'+BASE_PATH+'tradeRecord/getIndex">返回我的大大付款</a></div></div>';
                    $('.reset').html(str);                    
                }else{
                    switch (res.code){
                        case 111:
                            $('#payOldPwdMsg').text(res.msg);
                            break;
                        default:
                            alert(res.msg+'('+res.code+')');
                    }
                    $('#payPasswordReset').removeAttr('disabled');
                }
            });
        });
        prettyPrint();
        /**********************************index end*********************************************/
    } else {
        /**********************************login start*********************************************/
    	$('#getPinCode').click(function(){
    		this.src = BASE_PATH + 'user/getPinCode?r=' + Math.random();
    	});
        $('#password,#account,#pinCode').on('keydown', function (event) {
            if (event.which == 13) {
                $('#loginBtn').click();
            }
        }).change(function () {
            $('#errorMsg').html('');
        });
        $('#loginBtn').click(function () {
            var msg     = '';
            var account = $('#account').val();
            var pwd     = $('#password').val();
            var pinCode = $('#pinCode').val();

            $(this).attr('disabled', 'disabled');

            if (account == '') {
                msg += '请输入账号<br/>';
            } /*else if (!/^[0-9]{11}$/.test(account)) {
                msg += '请输入正确的账号<br/>';
            }
            */
            
            if (pwd == '') {
                msg += '请输入密码<br/>';
            }

            if (pinCode == '') {
                msg += '请输入验证码<br/>';
            } else if (!/^[A-Za-z0-9]{4}$/.test(pinCode)) {
                msg += '请输入正确的验证码<br/>';
            }

            if (msg) {
                $(this).removeAttr('disabled');
                $('#errorMsg').html(msg);
                return false;
            }

            $('#errorMsg').text('登录中...');
            $.post(BASE_PATH + 'user/login', {
                    'account': account,
                    'password': hex2b64(do_encrypt(pwd)),
                    'token':$('#token').val(),
                    'pinCode':pinCode
                },
                function (result) {
                    if (result.code != 0) {
                        $('#errorMsg').html(result.msg + '(' + result.code + ')');
                        $("#loginBtn").removeAttr('disabled').html('登录');
                        $('#getPinCode').click();
                    } else {
                        $("#errorMsg").html('登录成功');
                        setTimeout(function () {
                            window.location.href = BASE_PATH;
                        }, 500);
                    }
                },
                'json'
            );
        });
        /**********************************login end*********************************************/
    }

});

$(document).ready(function(){
	
//	$(document).on('click', '#test_showLeftMenu', function(event){
//		showLeftMenu('div-menu-list');
//	});
	
	function showLeftMenu(id){
		var menu = $('#'+id);
		if( "block" == menu.css("display") ){
			menu.css("display","none");
		} else {
			menu.css("display","block");
		}
	}
	
	 $('#div_switch_menu').click(function () {
		 var img_obj = $('#div_switch_menu').find('img');
		 //alert(img_obj.attr('src'));
		 var src = img_obj.attr('src');
		 if( -1 == src.indexOf('left') ){
			 src = img_obj.attr('src').replace('right','left');
			 img_obj.attr('src',src);
			 $('#div-menu-list').css('width','280px');
			 $('#li-menu-list').css('display','block');
			 $('#div_content_html').css('width','85%').css('margin-left','280px');
		 } else {
			 src = img_obj.attr('src').replace('left','right');
			 img_obj.attr('src',src);
			 $('#div-menu-list').css('width','20px');
			 $('#li-menu-list').css('display','none');
			 $('#div_content_html').css('width','99%').css('margin-left','20px');
		 }
	 });
	 
});

$(document).ready(function(){
//	$(":range").rangeinput({progress: true});
	$("ul.expmenu li > div.header").click(function(){
		var arrow = $(this).find("span.arrow");
		if(arrow.hasClass("up")){
			arrow.removeClass("up");
			arrow.addClass("down");
		}
		else if(arrow.hasClass("down")){
			arrow.removeClass("down");
			arrow.addClass("up");
		}

		$(this).parent().find("ul.menu").slideToggle();
	});
});

