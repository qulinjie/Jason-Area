
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
	
	function adminLoginForm() {
		$('#admin-login-modal').modal({
			  keyboard: false,
			  backdrop: 'static'
			});
		$('#admin-login-modal').modal('show');
		$("#admin-login-hint").html('').hide();
		
		$('#btn-admin-login').unbind("click");
		$('#btn-admin-login').on('click',function(event){
			admin_login();
		});
		
		$("#admin-login-name").keypress(function (e){
			var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode; 
			if (13 == keyCode) {
				if('' != $("#admin-login-password").val()){
					$("#btn-admin-login").click();
				} else {
					$("#admin-login-password").focus();
				}
			}
		});
		
		$("#admin-login-password").keypress(function (e){
			var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode; 
			if (13 == keyCode) {
				$("#btn-admin-login").click();
			}
		});
		
		setTimeout(function(){
			$("#admin-login-name").focus();
	    }, 1000);
		
	}
	
	if(!$('#isAdminLogin') || 1 != $('#isAdminLogin').val() ){
		adminLoginForm(); // 弹出登录框
	}
	
    /**********************************login start*********************************************/
	function admin_login(admin){
		$("#btn-admin-login").attr('disabled', 'disabled');
	    $("#admin-login-hint").html('').fadeOut();
	    
	    var account = $('#admin-login-account').val();
	    var password = $('#admin-login-password').val();
	    var pincode = $('#admin-login-pincode').val();
	    
	    var login_csrf = $('#login-csrf').val();
	    var other_csrf = $('#other-csrf').val();
	    
	    var hint_html = '';
	    if(''==account){
	    	hint_html += (hint_html == '' ? '' : '<BR>');
	        hint_html +=  '请输入用户名！';
	    }
	    if(''==password){
	    	hint_html += (hint_html == '' ? '' : '<BR>');
	        hint_html +=  '请输入密码！';
	    }
	    
	    if(hint_html != ''){
	        $("#admin-login-hint").html(hint_html).fadeIn();
	        $("#btn-admin-login").removeAttr('disabled');
	        $("#btn-admin-login").html("登录");
	        return false;
	    }
	    
	    var url  = 'admin/login';
	    password = hex2b64(do_encrypt(password));
	    
		$("#btn-admin-login").html("登录中...");
	    $.post(BASE_PATH + url, {'login_csrf':login_csrf,'other_csrf':other_csrf,
		    	'account':account,
		    	'password':password, 
		        'pincode':pincode
	        },
	        function(result){
	            if(result.code != 0) {
	            	if(admin) {
		                $("#admin-login-hint").html(result.msg + '(' + result.code + ')').fadeIn();
		                $("#btn-admin-login").removeAttr('disabled');
		                $("#btn-admin-login").html("登录");
	            	} else {
	            		admin_login(true);
	            	}
	            }else {
	                $("#admin-login-hint").html(result.msg).fadeIn();
	                setTimeout(function(){
	                    $("#admin-login-modal").modal('hide');
	                    location.href = BASE_PATH + 'admin/mgr';
	                }, 500);
	            }
	        },
	        'json'
	    );
	}
    /**********************************login end*********************************************/

	/**************strat--注销****************/
	$(document).on('click', '#amdin-loginOut-btn', function(event){
		var txt = $(this).text();
		
		$("#confirm-admin-hint").html('').hide();
		$('#confirm-admin-modal').modal('show');
		$('#confirm-admin-body').html('是否'+txt+'!');
		
		$('#confirm-admin-btn').unbind("click");
		$('#confirm-admin-btn').on('click', {},function(event){
			$.post(BASE_PATH + 'admin/loginOut', {},
			        function(result){
			            if(result.code != 0) {
			                $("#confirm-admin-hint").html(result.msg + '(' + result.code + ')').fadeIn();
			            }else {
			            	$("#confirm-admin-hint").html(result.msg + ', 关闭...').fadeIn();
			            	setTimeout(function(){
			            		$('#confirm-admin-modal').modal('hide');
			            	},500);
			            	setTimeout(function(){
			            		location.href = BASE_PATH + 'admin/mgr';
			            	},800);
			            }
			        },
			        'json'
			    );
		});
	});
	/**************end--注销****************/
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
