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
    	<div class="img" style="background-position:-8px -14px;"></div>
    </div>
    <div class="bd">
    	<form id="findPasswordAccountForm">
            <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
        	<p class="service_l">账号<input type="text" name="tel" id="tel" value=""  /></p>
            <b class="zh" id="telMsg"></b>
            <p class="service_x">验证码<input type="text" name="pinCode" id="pinCode" value=""><a href="javascript:void(0);"><img id="getPinCode" src="<?php echo Router::getBaseUrl()?>findPassword/getPinCode" onclick="this.src='<?php echo Router::getBaseUrl()?>findPassword/getPinCode'" /></a></p>
            <b class="tt" id="pinCodeMsg"></b>
            <p class="service_b"><input type="button" id="findPasswordAccountBtnSave" value="下一步"></p>
        </form>
    </div>
    <div class="bottom">
    	<div class="bq">湖南大汉电子商务有限公司 版权所有</div>
    </div>
</div>
<script> var BASE_PATH = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl()?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/findPassword.js"></script>
</body>
</html>
