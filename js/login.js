;$(function() {
	$('#password,#account').on('keydown', function (event) {
		if (event.which == 13) {
			$('#loginBtn').click();
		}
	}).change(function(){
		$(this).parent().parent().find('span').text('');
	});

	$('#loginBtn').click(function () {
		var accountMcg = '';
		var pwdMcg     = '';
		var account    = $('#account').val();
		var pwd        = $('#password').val();

		$(this).attr('disabled','disabled');

		if (account == '') {
			accountMcg = '请输入账号';
		}else if(!/^[0-9]{11}$/.test(account)){
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
						alert(result.msg+'('+result.code+')');
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
	$('#logoutBtn').click(function () {
		$.post(BASE_PATH + 'user/logout',{},function(result){
			if(result.code == 0){
				window.location.href = BASE_PATH+'user/login';
			}
		},'json');
	});
});