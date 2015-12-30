<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>大大支付-注册</title>
<link rel="stylesheet" href="<?php echo Router::getBaseUrl()?>css/index.css" type="text/css" />
</head>

<body style="background:#f8f8f8;">
<div class="entirety">
	<div class="t1">
      	<div class="top1">
            <div class="right"><span>欢迎来到大大付款</span><span><a href="javascript:void(0)">大大买钢首页</a></span><span><a href="javascript:void(0)">大大买钢APP</a></span></div>
         </div>
      </div>	
	  <div class="t2">
        <div class="top2">
                <div class="logo"><img src="<?php echo Router::getBaseUrl()?>view/images/logo.png" /></div>
                <div class="bt"><b>|</b><span>注册</span></div>
         </div>
      </div>
    <div class="bz">
    	<div class="img" style="background-position:-8px -266px;"></div>
    </div>
    <div class="zc">
    	<form id="accountForm">
            <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
        	<p class="service_l">账号<input type="text" id="tel" name="tel" /></p>
            <b class="zh" id="telMsg"></b>
            <p class="service_x">验证码<input type="text" id="code" name="code" style="width: 109px;"/>
                <input type="button" id="sendCode" name="sendCode" value="获取短信验证码" style="background-color: rgb(216,216,216);color: #FFF;width: 112px;margin-left:0;padding-left: 6px;"/>
            </p>
            <b class="tb" id="codeMsg"></b>
            <p class="service_l">登录密码<input type="password" id="password" name="password"/></p>
            <b class="mm" id="passwordMsg"></b>
            <p class="service_l">确认登录密码<input type="password" id="rePassword" name="rePassword" /></p>
            <b class="qr" id="rePasswordMsg"></b>
            <p class="service_tex"><input type="checkbox" name="agree" id="agree"/><span>我同意《大大支付服务协议》</span></p>
            <p class="service_b"><input type="button" name="accountBtnSave" id="accountBtnSave" value="下一步"/></p>
        </form>
    </div>
    <div class="bottom">
    	<div class="bq">湖南大汉电子商务有限公司 版权所有</div>
    </div>
</div>
<script> var BASE_PATH = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl()?>js/jquery.min.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/prng4.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rng.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/base64.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/jsbn.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rsa.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/register.js"></script>
</body>
</html>
