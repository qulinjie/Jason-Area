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
            'account': {'required': true, 'reg': /^[0-9A-Za-z]{1,30}$/},
            'name':    {'required': true, 'reg': /^[\u4e00-\u9fa5A-Za-z]{1,20}$/},
            'tel':     {'required': true, 'reg': /^[0-9]{11}$/},
            'code':    {'required': true, 'reg': /^[0-9]{6}$/},
            'file':    {'required': true, 'reg': /\.(png|jpg|jpeg)$/},
            'pinCode': {'required': true, 'reg': /^[A-Za-z0-9]{4}$/},
            'password':{'required': true},
            'rePassword':  {'required': true, 'equalTo': '#password'}
        };
        var conMsg = {
            'account': {'required': '请输入帐号',      'reg': '由数字或字母组成且不能超过30个字符'},
            'name':    {'required': '请输入办理人姓名', 'reg': '由汉字或字母组成且不能超过20个字符'},
            'tel':     {'required': '请输入手机号码',   'reg': '请输入正确的手机号码'},
            'code':    {'required': '请输入短信验证码', 'reg': '请输入正确的验证码'},
            'file':    {'required': '请选择授权文件',   'reg': '只支持png、jpg、扫描件格式'},
            'pinCode': {'required': '请输入验证码',     'reg': '请输入正确的验证码'},
            'password':{'required': '请输入密码'},
            'rePassword':{'required':'请再次输入密码', 'equalTo': '两次输入密码不一致'}
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
    $('form :password,:text,:file').blur(function () {checkForm('#' + $(this).attr('id'));});
    $('#findPasswordAccountBtnSave').click(function(){
        if(checkForm()) return false;
        $(this).attr('disabled','disabled');
        $.post(BASE_PATH+'findPassword/account',$('#findPasswordAccountForm').serialize(),function(res){
            if(res.code == 0){
                window.location.href = BASE_PATH+'findPassword/verify';
            }else{
                $('#findPasswordAccountBtnSave').removeAttr('disabled');
                $('#getPinCode').click();
                switch (res.code){
                    case 110:
                    case 220:
                        $('#telMsg').text(res.msg);
                        break;
                    case 304:
                        $('#pinCodeMsg').text(res.msg);
                        break;
                    default:
                        alert(res.msg+'('+res.code+')');
                }
            }
        },'json');
    });
    $('#sendCode').click(function(){
        var obj      = $(this);
        var index    = -1;
        var timer    = null;
        var currTime = 60;
        if(checkForm('#tel')) return false;
        obj.attr('disabled','disabled');
        timer = setInterval(function(){
            index =obj.text().lastIndexOf('(');
            if(index>=0){
                currTime = parseInt(obj.text().substr(index+1));
                currTime--;
                if(currTime == 0){
                    clearInterval(timer);
                    obj.removeAttr('disabled').text('获取短信验证码');
                }else{
                    obj.text(obj.text().substr(0,index)+'('+currTime+')秒');
                }
            }
        },1000);
        obj.text('正在发送('+currTime+')秒');
        $.post(BASE_PATH+'findPassword/sendCmsCode',{tel:$('#tel').val(),token:$('#token').val()},function(res){
            if (res.code == 0) {
                index = obj.text().lastIndexOf('(');
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
        }, 'json');
    });
    $('#findPasswordVerifyBtnSave').click(function () {
        if (checkForm()) return false;
        $(this).attr('disabled','disabled');
        $.ajax({
            'url':BASE_PATH+'findPassword/verify',
            'type':'POST',
            data: new FormData($('#findPasswordVerifyForm')[0]),
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function (res) {
            switch (res.code){
                case 0:
                    window.location.href = BASE_PATH+'findPassword/reset';
                    break;
                default:
                    $('#findPasswordVerifyBtnSave').removeAttr('disabled');
                    alert(res.msg);
                    break;
            }
        });
    });
    $('#findPasswordResetBtnSave').click(function(){
        if(checkForm()) return false;
        var data   = {};
        data.tel   = $('#tel').val();
        data.pwd   = hex2b64(do_encrypt($('#password').val()));
        data.token = $('#token').val();
        $.post(BASE_PATH + 'findPassword/reset',data,function(res){
            switch (res.code){
                case 0:
                case 115:
                    window.location.href = BASE_PATH+'findPassword/finish';
                    break;
                default:
                    $('#findPasswordVerifyBtnSave').removeAttr('disabled');
                    alert(res.msg);
                    break;
            }
        },'json');
    });
});