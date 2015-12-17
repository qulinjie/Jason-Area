<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<title>大大付款-找回密码</title>
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
        <div class="col-lg-offset-3" style="margin-top: 21px;margin-bottom: 21px;">
            <img src="<?php echo Router::getBaseUrl()?>asset/img/findPasswdProcess.jpg"/>
        </div>
    </div>
    <div class="row" style="padding-top: 30px;">
        <div class="col-lg-offset-2 col-lg-10">
            <form id="findPasswordForm"  class="form-horizontal">
                <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
                <div class="form-group">
                    <label for="account" class="col-lg-offset-1 col-lg-2 control-label">帐号</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="account" name="account" placeholder="帐号"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-lg-offset-1 col-lg-2 control-label">办卡人姓名</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="name" name="name" placeholder="办卡人姓名"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="tel" class="col-lg-offset-1 col-lg-2 control-label">手机号码</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="手机号码"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="code" class="col-lg-offset-1 col-lg-2 control-label">短信验证码</label>
                    <div class="col-lg-2">
                        <input type="text" class="form-control" id="code" name="code" placeholder="验证码"/>
                    </div>
                    <div class="col-lg-2">
                        <input type="button" id="sendCode" value="获取短信验证码" class="btn btn-default" style="background-color: rgb(73,142,201);color: #FFF;"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="file" class="col-lg-offset-1 col-lg-2 control-label">授权</label>
                    <div class="col-lg-4">
                        <input type="file" class="form-control" id="file" name="file" placeholder="授权"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-4">
                        <input type="button" id="findPasswordBtnSave" class="btn btn-primary form-control" value="提交申请" style="background-color: rgb(0,139,254);"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script> var BASE_PATH = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl()?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/findPassword.js"></script>
</body>
</html>