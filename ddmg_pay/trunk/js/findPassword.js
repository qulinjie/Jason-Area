$(function () {
    function checkForm(selector) {
        var msg    = '';
        var span   = '';
        var isErr  = false;
        var config = {
            'account': {'required': true, 'reg': /^[0-9A-Za-z]{1,30}$/},
            'name':    {'required': true, 'reg': /^[\u4e00-\u9fa5A-Za-z]{1,20}$/},
            'tel':     {'required': true, 'reg': /^[0-9]{11}$/},
            'code':    {'required': true, 'reg': /^[0-9]{6}$/},
            'file':    {'required': true, 'reg': /\.(png|jpg|jpeg)$/}
        };
        var conMsg = {
            'account': {'required': '请输入帐号',      'reg': '由数字或字母组成且不能超过30个字符'},
            'name':    {'required': '请输入办卡人姓名', 'reg': '由汉字或字母组成且不能超过20个字符'},
            'tel':     {'required': '请输入手机号码',   'reg': '请输入正确的手机号码'},
            'code':    {'required': '请输入短信验证码', 'reg': '请输入正确的验证码'},
            'file':    {'required': '请选择授权文件',   'reg': '只支持png、jpg、jpeg文件'}
        };
        if (selector == undefined) {
            selector = ':text,:file';
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
                    }
                    if (msg) isErr = true;
                    span = $(this).parent().parent().children(':last-child').find('span');
                    if ((!span.text()&& msg) || (span.text()&& !msg)) {
                        span.text(msg);
                    }
                }
            }
        });
        return isErr;
    }
    $('#findPasswordForm :text,:file').blur(function () {
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
        $.post(BASE_PATH+'user/sendCmsCode',{tel:$('#tel').val(),token:$('#token').val()},function(res){
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
    $('#findPasswordBtnSave').click(function () {
        if (checkForm()) return false;
        $(this).attr('disabled','disabled');
        $.ajax({
            'url':BASE_PATH+'user/doFindPassword',
            'type':'POST',
            data: new FormData($('#findPasswordForm')[0]),
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function (result) {
            if(result.code == 0){
                window.location.href = BASE_PATH+'user/findPasswordMsg';
            }else{
                $(this).removeAttr('disabled');
                alert(result.msg+'('+result.code+')');
            }
        });
    });
});