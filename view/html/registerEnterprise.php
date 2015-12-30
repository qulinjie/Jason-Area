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
    	<div class="img" style="background-position:-8px -433px;"></div>
    </div>
    <div class="zc" style="height:350px;">
    	<form id="enterpriseForm" style="margin-top:30px;">
            <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
            <input type="hidden" id="id" name="id" value="<?php echo $id;?>"/>
        	<p class="service_l">法人姓名<input type="text" name="legalPerson"  id="legalPerson" /></p>
            <b class="zh" id="legalPersonMsg"></b>
            <p class="service_l">公司全称<input type="text" name="companyName"  id="companyName"/></p>
            <b class="tb" id="companyNameMsg"></b>
            <p class="service_l">营业执照注册号<input type="text" name="license" id="license"/></p>
            <b class="mm" id="licenseMsg"></b>
            <p class="service_c" style="margin-top:15px; padding-left:429px;">营业执照附件<span class="fj"><a href="javascript:void(0);">选择附件</a></span><span class="xz"><a href="javascript:void(0);">下载模板</a></span><span>支持jpg、png、扫描件格式</span><input class="select" type="file" id="file" name="file"></p>
            <b class="qr" id="fileMsg"></b>
            <p class="service_y" style="padding-top:48px;"><input class="next" type="button" id="enterpriseBtnSave" value="提交"><input class="last_step" type="button" value="上一步"></p>
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
