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
            <div class="right"><span>欢迎来到大大付款</span><span><a href="">大大买钢首页</a></span><span><a href="">大大买钢APP</a></span></div>
         </div>
      </div>	
	  <div class="t2">
        <div class="top2">
                <div class="logo"><img src="<?php echo Router::getBaseUrl()?>view/images/logo.png" /></div>
                <div class="bt"><b>|</b><span>注册</span></div>
         </div>
      </div>
    <div class="bz">
    	<div class="img" style="background-position:-8px -352px;"></div>
    </div>
    <div class="zc" style="height:415px;">
    	<div class="ts">您的账号注册成功，请继续完善资料。</div>
    	<form id="personForm" style="margin-top:24px;">
            <input type="hidden" id="id" name="id" value="<?php echo $id;?>"/>
            <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
        	<p class="service_l">办理人姓名<input type="text" name="name"  id="name"/></p>
            <b class="zh" id="nameMsg"></b>
            <p class="service_c" style="margin-top:15px;">办理授权书<span class="fj"><a href="">选择附件</a></span><span class="xz"><a href="">下载模板</a></span><input class="select" type="file" id="file" name="file"></p>
            <b class="gs2" id="fileMsg"></b>
            <p class="service_y"><input class="next" type="button" id="personBtnSave" value="下一步"><input class="last_step" type="button" value="上一步"></p>
        </form>
    </div>
    <div class="bottom">
    	<div class="bq">湖南大汉电子商务有限公司 版权所有</div>
    </div>
</div>
<script> var BASE_PATH = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl()?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/register.js"></script>
</body>
</html>
