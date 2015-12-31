<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>大大支付-找回密码</title>
<link rel="stylesheet" href="<?php echo Router::getBaseUrl()?>css/index.css" type="text/css" />
</head>

<body style="background:#f8f8f8;">
<div class="entirety">
	<div class="t1">
      	<div class="top1">
            <div class="right"><span>欢迎来到大大付款</span><span><a href="">大大买钢首页</a></span><span><a href="">大大买钢APP</a></span></div>
         </div>
      </div>	
	  <div class="t2">
        <div class="top2">
                <div class="logo"><img src="<?php echo Router::getBaseUrl()?>view/images/logo.png" /></div>
                <div class="bt"><b>|</b><span>找回登陆密码</span></div>
         </div>
      </div>
    <div class="bz">
    	<div class="img" style="background-position:-8px -181px;"></div>
    </div>
    <div class="bd" style="height:411px;">
    	<form id="findPasswordResetForm">
            <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
            <input type="hidden" id="tel" name="tel" value="<?php echo $tel;?>"/>
        	<p class="service_p"><span>手机号码：</span><span><?php echo $tel;?></span></p>
            <p class="service_l">新登录密码<input type="password" name="password" id="password" value=""  /></p>
            <b class="mm" id="passwordMsg"></b>
            <p class="service_l">确认登录密码<input type="password" name="rePassword" id="rePassword" value=""  /></p>
            <b class="mms" id="rePasswordMsg"></b>
            <p class="service_b"><input class="" type="button" id="findPasswordResetBtnSave" value="下一步"></p>
        </form>
    </div>
    <div class="bottom">
    	<div class="bq">湖南大汉电子商务有限公司 版权所有</div>
    </div>
</div>
<script> var BASE_PATH = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl()?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/prng4.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rng.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/base64.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/jsbn.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rsa.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/findPassword.js"></script>
</body>
</html>
