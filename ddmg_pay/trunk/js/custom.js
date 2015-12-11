$(function () {
    /*************************** global start ******************************/
    function do_encrypt(pwd) {
        var public_key = "00c10bebb447db4fa916e4bb3d3e3a05322a4023b78538c6a5676205bdd71f9d912938a27ac0bcd835c42bf50268f797e59ebdc95ffe70a49be3ac35fddbdaf7c52ed55996dbd5ca19b4076491a20c8dbc30383e55c5c6a7c15be938c9a50775918bcaa4ec687763034609ea910e80e0e9c8c33bd927e25e0cbf987d90941314f7";
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
        $.post(BASE_PATH + 'user/logout', {}, function (result) {
            if (result.code == 0) {
                window.location.href = BASE_PATH + 'user/login';
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
            if (!$("#menuList").find('.active').length) {
                window.location.href = $('#menuList li:eq(0)').find('a').attr('href');
            }
        } else {
            window.location.href = BASE_PATH + 'user/login';
        }
        prettyPrint();
        /**********************************index end*********************************************/
    } else {
        /**********************************login start*********************************************/
        $('#password,#account').on('keydown', function (event) {
            if (event.which == 13) {
                $('#loginBtn').click();
            }
        }).change(function () {
            $(this).parent().parent().find('span').text('');
        });
        $('#loginBtn').click(function () {
            var accountMcg = '';
            var pwdMcg = '';
            var account = $('#account').val();
            var pwd = $('#password').val();

            $(this).attr('disabled', 'disabled');

            if (account == '') {
                accountMcg = '请输入账号';
            } else if (!/^[0-9]{11}$/.test(account)) {
                accountMcg = '请输入正确的账号';
            }

            if (pwd == '') {
                pwdMcg = '请输入密码';
            }

            if (accountMcg != '' || pwdMcg != '') {
                $(this).removeAttr('disabled');
                $('#account').parent().parent().find('span').text(accountMcg);
                $('#password').parent().parent().find('span').text(pwdMcg);
                return false;
            }

            $(this).text('登录中...');
            $.post(BASE_PATH + 'user/doLogin', {
                    'account': account,
                    'password': hex2b64(do_encrypt(pwd))
                },
                function (result) {
                    if (result.code != 0) {
                        alert(result.msg + '(' + result.code + ')');
                        $("#loginBtn").removeAttr('disabled').html('登录');
                    } else {
                        $("#loginBtn").html(result.msg).fadeIn();
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

