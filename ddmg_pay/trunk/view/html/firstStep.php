<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<title>大大付款-用户注册</title>
<link rel="stylesheet" href="<?php echo Router::getBaseUrl();?>css/bootstrap.min.css" type="text/css">
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <img src="<?php echo Router::getBaseUrl()?>asset/img/logo.jpg"/>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo Router::getBaseUrl()?>">返回首页</a></li>
        </ul>
    </div>
</nav>
<div class="container" style="padding-top: 150px;">
    <div class="row">
        <div class="col-lg-offset-1" style="margin-top: 21px;margin-bottom: 21px;">
            <img src="<?php echo Router::getBaseUrl()?>asset/img/step1.jpg"/>
        </div>
    </div>
    <div class="row" style="padding-top: 30px;">
        <div class="col-lg-offset-1 col-lg-10">
            <form id="firstStepForm"  class="form-horizontal">
                <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
                <div class="form-group">
                    <label for="tel" class="col-lg-offset-1 col-lg-2 control-label">手机号码</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="手机号码"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="code" class="col-lg-offset-1 col-lg-2 control-label">验证码</label>
                    <div class="col-lg-2">
                        <input type="text" class="form-control" id="code" name="code" placeholder="验证码"/>
                    </div>
                    <div class="col-lg-2">
                        <input type="button" id="sendCode" value="获取短信验证码" class="btn btn-default" style="background-color: rgb(73,142,201);color: #FFF;"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-lg-offset-1 col-lg-2 control-label">登录密码</label>
                    <div class="col-lg-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="登录密码"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="rePassword" class="col-lg-offset-1 col-lg-2 control-label">确认登录密码</label>
                    <div class="col-lg-4">
                        <input type="password" class="form-control" id="rePassword" name="rePassword" placeholder="确认登录密码"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-4">
                        <input type="checkbox" id="agree" name="agree"/> 我同意《大大支付服务协议》
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-4">
                        <input type="button" id="firstStepBtnSave" class="btn btn-primary col-lg-4" value="下一步" style="background-color: rgb(0,139,254);"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script> var BASE_PATH = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl()?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/prng4.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rng.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/base64.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/jsbn.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rsa.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/register.js"></script>
</body>
</html>