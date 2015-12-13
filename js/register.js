;$(function () {
    $('form :text,:password').blur(function () {
        checkForm('#' + $(this).attr('id'));
    });
    $('#sendCode').click(function () {
        if (checkForm('#tel')) return false;
        $.post(BASE_PATH + 'register/sendCmsCode', {"tel": $('#tel').val(),"token":$("#token").val()}, function (res) {
            if (res.code == 0) {
                $('#sendCode').val('已发送');
            } else {
                $('#sendCode').val('点击获取验证码');
                alert(res.msg+'('+res.code+')');
            }
        }, 'json');
    });
    $('#firstStepBtnSave').click(function () {
        var postData = {};
        if (checkForm() || $('#agree').is(':checked') == false) return false;
        postData.tel = $('#tel').val();
        postData.code = $('#code').val();
        postData.token = $('#token').val();
        postData.pwd = hex2b64(do_encrypt($('#password').val()));

        $(this).attr('disabled', 'disabled');
        $.post(BASE_PATH + 'register/doFirstStep', postData, function (res) {
            if (res.code == 0) {
                window.location.href = BASE_PATH + 'register/secondStep';
            } else {
                $('#firstStepBtnSave').removeAttr('disabled');
                $('#sendCode').val('点击获取验证码');
                alert(res.msg + '(' + res.code + ')');
            }
        }, 'json');
    });
    //检查选择器selector,如"#tel,#code"
    function checkForm(selector) {
        var msg = '';
        var span = '';
        var hasError = false;
        var config = {
            "tel": {"required": true, "reg": /^[0-9]{11}$/},
            "code": {"required": true, "reg": /^[0-9]{6}$/},
            "name": {"required": true, "reg": /^[\u4e00-\u9fa5A-Za-z]{1,20}$/},
            "password": {"required": true},
            "legalPerson": {"required": true, "reg": /^[\u4e00-\u9fa5A-Za-z]{1,20}$/},
            "companyName": {"required": true, "reg": /^[\u4e00-\u9fa5A-Za-z]{1,30}$/},
            "license": {"required": true, "reg": /^[0-9A-Za-z]{1,30}$/},
            "rePassword": {"required": true, "equalTo": '#password'}
        };
        if (selector == undefined) {
            selector = ':text,:password';
        }
        $('form  ' + selector).each(function () {
            if (config[$(this).attr('id')] != undefined) {
                var rules = config[$(this).attr('id')];
                for (var key in rules) {
                    switch (key) {
                        case 'required':
                            msg = rules[key] == true && $(this).val() == '' ? '不能为空' : '';
                            break;
                        case 'reg':
                            msg = !rules[key].test($(this).val()) ? '输入有误' : '';
                            break;
                        case 'equalTo':
                            msg = $(rules[key]).val() != $(this).val() ? '两次输入密码不一致' : '';
                            break;
                    }
                    if (msg != '') hasError = true;
                    span = $(this).parent().parent().children(":last-child").find('span');
                    if ((span.text() == '' && msg != '') || (span.text() != '' && msg == '')) {
                        span.text(msg);
                    }
                }
            }
        });
        return hasError;
    }
    function checkFile(elemId){
        var filename = $(elemId).val();
        if(filename == ''){
            $(elemId).parent().parent().children(':last-child').find('span').text('选择办理授权书');
            return true;
        }

        var ext = filename.substring(filename.lastIndexOf('.'), filename.length).toUpperCase();
        if (ext != ".PNG" && ext != ".JPG" && ext != ".JPEG") {
            $(elemId).parent().parent().children(':last-child').find('span').text('图片限于png,jpeg,jpg格式');
            return true;
        }
        return false;
    }
    function do_encrypt( pwd ) {
        var public_key="00a037b186d9dc36e8d9738df7080016686b9e1791f3b2e7c506c16f44087af150b9b18dc6fb6810f29811f29dffc60169c0bc43c90e005f77a8a39e43a1a7edcca2f4bf430f0eb8fabee9360c02c084ae9b7b7a939915142f160fb032cf70d16c08506d83537929f725315333b306031f2c0bd02b0b75ab2fd81577c8d98b497f";
        var public_length="10001";
        var rsa = new RSAKey();
        rsa.setPublic(public_key, public_length);
        var res = rsa.encrypt(pwd);
        if(res) {
            return res;
        }else {
            return '';
        }
    }

    $('#secondStepBtnSave').click(function () {
        if (checkForm('#name') || checkFile('#file')) return false;
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
        if (checkForm() || checkFile('#file')) return false;
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