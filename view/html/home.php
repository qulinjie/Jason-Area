<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>大大付款主页</title>
<link rel="stylesheet" href="<?php echo Router::getBaseUrl(); ?>css/index.css" type="text/css" />
</head>

<body>
<div class="all">
	  <div class="t1">
      	<div class="top1">
            <div class="left"><span><a class="theme-login" href="javascript:;">登陆</a></span><!--  <span><a href="<?php echo Router::getBaseUrl()?>register/account">注册</a></span>--></div>
            <div class="right"><span>欢迎来到大大付款</span><span><a href="javascript:void(0);">大大买钢首页</a></span><span><a href="javascript:void(0);">大大买钢APP</a></span></div>
         </div>
      </div>	
	  <div class="t2">
        <div class="top2">
                <div class="logo"><a href="javascript:void(0);"><img src="<?php echo Router::getBaseUrl(); ?>view/images/logo.png" /></a></div>
                <div class="nav"><a href="javascript:void(0);">首页</a><a href="javascript:void(0);">我的大大付款</a><a href="javascript:void(0);">账户管理</a></div>
         </div>
      </div>
      <div class="tbs">
             <!--  <div class="register"><a href="<?php echo Router::getBaseUrl()?>register/account">我要注册大大付款</a></div>-->
             <div class="login"><a class="theme-login" href="javascript:;">已有账号，去登录</a></div>
      </div>
      <div class="bottom">
      		<p>Copyright&copy;大大买钢 2015, All Rights Reserved &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;湘B2-20150061</p>
      </div>
        <div class="theme-popover">
             <div class="theme-poptit">
                  <a href="javascript:;" title="关闭" class="close">×</a>
             </div>
             <div class="detail">
                    <div class="title"><span>登录大大付款</span></div>
                    <form id="loginForm">
                        <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
                    	<input type="text" class="zh"  id="account" name="account" placeholder="账号"  />
                        <input type="password" class="mm" id="password" name="password" placeholder="登录密码" />
                        <input type="text" class="yz" id="pinCode" name="pinCode" placeholder="验证码" />
                        <div class="img">
                            <a href="javascript:void(0);"><img id="getPinCode" src="<?php echo Router::getBaseUrl()?>user/getPinCode"/></a>                      </div>
                        <div class="wj"><!--  <a href="<?php echo Router::getBaseUrl()?>findPassword/account">忘记密码，去找回！</a>--></div>
                        <input type="button" id="loginBtn" class="dl" name="loginBtn" value="登录" />
                    </form>
                </div>
                <div id="errorMsg" style="font-size: 12px; 微软雅黑;color: #999999;padding: 5px 30px;"></div>
        </div>
        <div class="theme-popover-mask"></div>
</div>
<script>var BASE_PATH = "<?php echo Router::getBaseUrl();?>" </script>
<script src="<?php echo Router::getBaseUrl(); ?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl(); ?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl(); ?>js/bootstrap.min.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/prng4.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rng.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/base64.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/jsbn.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rsa.js"></script>
<script src="<?php echo Router::getBaseUrl(); ?>js/custom.js"></script>
</body>
</html>
<script>
jQuery(document).ready(function($) {
	$('.theme-login').click(function(){
		$('.theme-popover-mask').fadeIn(100);
		$('.theme-popover').slideDown(200);
	});
	$('.theme-poptit .close').click(function(){
		$('.theme-popover-mask').fadeOut(100);
		$('.theme-popover').slideUp(200);
	});

});
$(document).ready(function(){  
    //初始化高度  
    $(".all").height($(window).height());  
    //当文档窗口发生改变时 触发  
    $(window).resize(function(){  
        $(".all").height($(window).height());  
    });
}) ;
</script>