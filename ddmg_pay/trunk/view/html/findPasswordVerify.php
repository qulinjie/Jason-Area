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
    	<div class="img" style="background-position:-8px -91px;"></div>
    </div>
    <div class="bc">
    	<form id="findPasswordVerifyForm">
            <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
            <input type="hidden" id="tel" name="tel" value="<?php echo $tel;?>"/>
    		<p class="service_p"><span>手机号码：</span><span><?php echo $tel;?></span></p>
            <p class="service_x">手机校验码<input name="code" type="text" id="code" value=""><span id="codenum"><a href="javascript:void(0);" id="sendCode">获取短信验证码</a></span></p>
            <b class="ts" id="codeMsg"></b>
            <p class="service_l">办理人姓名<input type="text" name="name" id="name" value=""  /></p>
            <b class="xm" id="nameMsg"></b>
            <p class="service_x" style="padding-left:463px;"><span>授权</span><span class="fj"><a href="javascript:void(0);">选择附件</a></span><input class="select" type="file" id="file" name="file"/></p>
            <b class="gs" id="fileMsg"></b>
            <p class="service_b"><input type="button" id="findPasswordVerifyBtnSave" value="下一步"></p>
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
