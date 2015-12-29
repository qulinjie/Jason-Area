$(function () {
    /*************************** global start ******************************/
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
        $('#pwdBtn').click(function () {
            $('#oldPwd').val('');
            $('#newPwd').val('');
            $('#newPwd2').val('');
            $('#updatePasswordModal').modal();
            $('#updatePasswordErrorMsg').hide();
        });
        $('#updatePasswordBtnSave').click(function(){
            var oldPwd  = $('#oldPwd').val();
            var newPwd  = $('#newPwd').val();
            var newPwd2 = $('#newPwd2').val();
            var data    ={};
            var msg     = '';

            if(!oldPwd){
                msg += '请输入旧密码<br/>';
            }
            if(!newPwd){
                msg += '请输入新密码<br/>';
            }

            if(newPwd != newPwd2){
                msg += '两次输入密码不一样<br/>';
            }

            if(msg){
                $('#updatePasswordErrorMsg').html(msg).show();
                return false;
            }

            data.token  = $('#token').val();
            data.oldPwd = hex2b64(do_encrypt(oldPwd));
            data.newPwd = hex2b64(do_encrypt(newPwd));
            $.post(BASE_PATH+'user/setPassword',data,function(res){
                if(res.code == 0){
                    alert('密码修改成功，需要重新登录');
                    $('#logoutBtn').click();
                }else{
                    $('#updatePasswordErrorMsg').text(res.msg+'('+res.code+')').show();
                }
            },'json');
        });
        $('#password,#rePassword').change(function(){
            $('#setPayPasswordMsg').hide().text('');
        });
        $('#setPayPassword').click(function(){
            var password   = $('#password').val();
            var rePassword = $('#rePassword').val();
            var data = {};
            if(!password){
                $('#setPayPasswordMsg').text('请输入支付密码').show();
                return false;
            }
            if(!rePassword){
                $('#setPayPasswordMsg').text('请输入确认支付密码').show();
                return false;
            }

            if(rePassword != password){
                $('#setPayPasswordMsg').text('两次输入密码不一致').show();
                return false;
            }
            data.password   = hex2b64(do_encrypt(rePassword));
            data.token = $('#token').val();

            $(this).attr('disabled','disabled');
            $('#setPayPasswordMsg').text('保存中...');
            $.post(BASE_PATH+'user/doSetPayPassword',data,function(res){
                var msg = res.code == 0 ? '保存成功' : res.msg+'('+res.code+')';
                $('#password').val('');
                $('#rePassword').val('');
                $('#setPayPassword').removeAttr('disabled','disabled');
                $('#setPayPasswordMsg').text(msg).show().hide(3000);
            });
        });
        prettyPrint();
        /**********************************index end*********************************************/
    } else {
        /**********************************login start*********************************************/
        $('#loginModalBtn').click(function(){
            $('#loginModal').modal();
            $('#getPinCode').click();
        });

        function centerModals() {
            $('#loginModal').each(function(i) {
                var $clone = $(this).clone().css('display', 'block').appendTo('body');
                var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 3);
                top = top > 0 ? top : 0;
                $clone.remove();
                $(this).find('.modal-content').css("margin-top", top);
            });
        }
        $('#loginModal').on('show.bs.modal', centerModals);
        $(window).on('resize', centerModals);

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
            } else if (!/^[0-9]{11}$/.test(account)) {
                msg += '请输入正确的账号<br/>';
            }

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
            $.post(BASE_PATH + 'user/doLogin', {
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

