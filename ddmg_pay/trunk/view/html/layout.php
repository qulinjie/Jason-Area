<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>大大支付-<?php if(doit::$controller == 'register'){?>注册<?php }else{?>找回登录密码<?php }?></title>
    <link rel="stylesheet" href="<?php echo Router::getBaseUrl() ?>css/index.css" type="text/css"/>
</head>

<body style="background:#f8f8f8;">
<div class="entirety">
    <div class="t1">
        <div class="top1">
            <div class="right"><span>欢迎来到大大付款</span><span><a href="javascript:void(0)">大大买钢首页</a></span><span><a
                        href="javascript:void(0)">大大买钢APP</a></span></div>
        </div>
    </div>
    <div class="t2">
        <div class="top2">
            <div class="logo"><img src="<?php echo Router::getBaseUrl() ?>view/images/logo.png"/></div>
            <div class="bt"><b>|</b><span><?php if(doit::$controller == 'Register'){?>注册<?php }else{?>找回登录密码<?php }?></span></div>
        </div>
    </div>
    <?php echo $content;?>
    <div class="bottom">
        <div class="bq">湖南大汉电子商务有限公司 版权所有</div>
    </div>
</div>
<script> var BASE_PATH = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl() ?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl(); ?>js/prng4.js"></script>
<script src="<?php echo Router::getBaseUrl(); ?>js/rng.js"></script>
<script src="<?php echo Router::getBaseUrl(); ?>js/base64.js"></script>
<script src="<?php echo Router::getBaseUrl(); ?>js/jsbn.js"></script>
<script src="<?php echo Router::getBaseUrl(); ?>js/rsa.js"></script>
<script src="<?php echo Router::getBaseUrl() ?>js/register.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/findPassword.js"></script>
</body>
</html>
