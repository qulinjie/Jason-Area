;$(function () {
    function do_encrypt( pwd ) {
        var public_key='00a037b186d9dc36e8d9738df7080016686b9e1791f3b2e7c506c16f44087af150b9b18dc6fb6810f29811f29dffc60169c0bc43c90e005f77a8a39e43a1a7edcca2f4bf430f0eb8fabee9360c02c084ae9b7b7a939915142f160fb032cf70d16c08506d83537929f725315333b306031f2c0bd02b0b75ab2fd81577c8d98b497f';
        var public_length='10001';
        var rsa = new RSAKey();
        rsa.setPublic(public_key, public_length);
        var res = rsa.encrypt(pwd);
        if(res) {
            return res;
        }else {
            return '';
        }
    }
    function checkForm(selector) {
        var msg    = '';
        var span   = '';
        var isErr  = false;
        var config = {
            'tel':         {'required': true, 'reg': /^[0-9]{11}$/},
            'code':        {'required': true, 'reg': /^[0-9]{6}$/},
            'password':    {'required': true},
            'rePassword':  {'required': true, 'equalTo': '#password'},
            'file':        {'required': true, 'reg': /\.(png|jpg|jpeg)$/},
            'agree':       {'isChecked':true},
            'name':        {'required': true, 'reg': /^[\u4e00-\u9fa5A-Za-z]{1,20}$/},
            'legalPerson': {'required': true, 'reg': /^[\u4e00-\u9fa5A-Za-z]{1,20}$/},
            'companyName': {'required': true, 'reg': /^[\u4e00-\u9fa5A-Za-z]{1,30}$/},
            'license':     {'required': true, 'reg': /^[0-9A-Za-z]{1,30}$/}

        };
        var conMsg = {
            'tel':         {'required': '请输入手机号码',   'reg': '请输入正确的手机号码'},
            'code':        {'required': '请输入短信验证码', 'reg': '请输入正确的验证码'},
            'password':    {'required': '请输入密码'},
            'rePassword':  {'required': '请再次输入密码',  'equalTo': '两次输入密码不一致'},
            'file':        {'required': '请选择上传文件',  'reg': '只支持png、jpg、jpeg文件'},
            'agree':       {'isChecked':'是否同意《大大支付服务协议》'},
            'name':        {'required': '请输入办理人姓名', 'reg': '由汉字或字母组成且不能超过20个字符'},
            'legalPerson': {'required': '请输入法人姓名', 'reg': '由汉字或字母组成且不能超过20个字符'},
            'companyName': {'required': '请输入公司全称', 'reg':'由汉字或字母组成且不能超过30个字符'},
            'license':     {'required': '请输入营业执照注册号', 'reg': '由数字或字母组成且不能超过30个字符'}


        };
        if (selector == undefined) {
            selector = ':text,:file,:password,:checkbox';
        }
        $('form  ' + selector).each(function () {
            if (config[$(this).attr('id')] != undefined) {
                var rules = config[$(this).attr('id')];
                for (var key in rules) {
                    switch (key) {
                        case 'required':
                            msg = rules[key] == true && !$(this).val()? conMsg[$(this).attr('id')]['required'] : '';
                            break;
                        case 'reg':
                            msg = !rules[key].test($(this).val()) ? conMsg[$(this).attr('id')]['reg'] : '';
                            break;
                        case 'equalTo':
                            msg = $(rules[key]).val() != $(this).val() ? conMsg[$(this).attr('id')]['equalTo'] : '';
                            break;
                        case 'isChecked':
                            console.log($(this).is(':checked'));
                            msg = rules[key] && !$(this).is(':checked')? conMsg[$(this).attr('id')]['isChecked'] : '';
                    }
                    span = $(this).parent().parent().children(':last-child').find('span');
                    if (msg){
                        isErr = true;
                        $(this).focus();
                    }
                    if ((!span.text()&& msg) || (span.text()&& !msg)) {
                        span.text(msg);
                    }
                }
            }
        });
        return isErr;
    }
    $('form :text,:password,:file,:checkbox').blur(function () {
        checkForm('#' + $(this).attr('id'));
    });
    $('#sendCode').click(function(){
        var obj      = $(this);
        var index    = -1;
        var timer    = null;
        var currTime = 60;
        if(checkForm('#tel')) return false;
        obj.attr('disabled','disabled');
        timer = setInterval(function(){
            index =obj.val().lastIndexOf('(');
            if(index>=0){
                currTime = parseInt(obj.val().substr(index+1));
                currTime--;
                if(currTime == 0){
                    clearInterval(timer);
                    obj.removeAttr('disabled').val('获取短信验证码');
                }else{
                    obj.val(obj.val().substr(0,index)+'('+currTime+')秒');
                }
            }
        },1000);
        obj.val('正在发送('+currTime+')秒');
        $.post(BASE_PATH+'register/sendCmsCode',{tel:$('#tel').val(),token:$('#token').val()},function(res){
            if (res.code == 0) {
                index = obj.val().lastIndexOf('(');
                if (index >= 0) {
                    currTime = parseInt(obj.val().substr(index + 1));
                    obj.val('成功发送(' + currTime + ')秒');
                }
            } else if(res.code == 106){
                clearInterval(timer);
                obj.removeAttr('disabled').val('获取短信验证码');
                $('#tel').parent().parent().children(':last-child').find('span').text(res.msg);
            } else{
                clearInterval(timer);
                obj.removeAttr('disabled').val('发送异常，请稍后再试');
            }
        }, 'json');
    });
    $('#firstStepBtnSave').click(function () {
        var postData = {};
        if (checkForm()) return false;
        postData.tel   = $('#tel').val();
        postData.code  = $('#code').val();
        postData.token = $('#token').val();
        postData.pwd   = hex2b64(do_encrypt($('#password').val()));
        $(this).attr('disabled', 'disabled');
        $.post(BASE_PATH + 'register/doFirstStep', postData, function (res) {
            if (res.code == 0) {
                window.location.href = BASE_PATH + 'register/secondStep';
            } else {
                $('#firstStepBtnSave').removeAttr('disabled');
                alert(res.msg + '(' + res.code + ')');
            }
        }, 'json');
    });
    $('#secondStepBtnSave').click(function () {
        if (checkForm()) return false;
        $.ajax({
            url: BASE_PATH + 'register/doSecondStep',
            type: 'POST',
            data: new FormData($('#secondStepForm')[0]),
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function (result) {
            if(result.code == 0){
                window.location.href = BASE_PATH+'register/thirdStep';
            }else{
                alert(data.msg+'('+data.code+')');
            }
        });
    });
    $('#thirdStepBtnSave').click(function () {
        if (checkForm()) return false;
        $.ajax({
            url: BASE_PATH + 'register/doThirdStep',
            type: 'POST',
            data: new FormData($('#thirdStepForm')[0]),
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function (result) {
            if(result.code == 0){
                window.location.href = BASE_PATH+'register/fourthStep';
            }else{
                alert(data.msg+'('+data.code+')');
            }
        });
    });
});