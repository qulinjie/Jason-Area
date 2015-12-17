<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>大大付款主页</title>
    <link rel="stylesheet" href="<?php echo Router::getBaseUrl();?>css/bootstrap.min.css" type="text/css">
</head>
<body style="background-image: url(<?php echo Router::getBaseUrl();?>asset/img/home.jpg);background-size:cover;">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <img src="<?php echo Router::getBaseUrl();?>asset/img/logo.jpg"/>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="javascript:void(0);">我的大大付款</a></li>
                <li><a href="javascript:void(0);">账户管理</a></li>
            </ul>
        </div>
    </nav>
    <div class="container" style="padding-top: 80px;">
        <div class="row" style="padding-top:300px;">
            <div class="col-lg-6">
                <button type="button" class="btn btn-primary btn-lg center-block text-center" onclick="window.location.href='<?php echo Router::getBaseUrl()?>register/firstStep'">我要注册大大付款</button>
            </div>
            <div class="col-lg-6">
                <button type="button" class="btn btn-primary btn-lg center-block text-center" id="loginModalBtn">已有账号，去登录</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">登录大大付款</h4>
                </div>
                <div class="modal-body">
                    <form id="loginForm"  class="form-horizontal">
                        <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
                        <div class="form-group">
                            <input type="text" class="form-control" id="account" name="account" placeholder="帐号"/>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="登录密码"/>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8" style="padding-left: 0;">
                                <input type="text" class="form-control" id="pinCode" name="pinCode" placeholder="验证码"/>
                            </div>
                            <div class="col-lg-4" style="padding: 0;margin: 0;">
                                <img id="getPinCode" src="<?php echo Router::getBaseUrl()?>user/getPinCode" onclick="this.src='<?php echo Router::getBaseUrl()?>user/getPinCode'" class="form-control" style="padding:0;border:0;"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="<?php echo Router::getBaseUrl()?>user/findPassword" class="pull-right" style="">忘记密码，去找回！</a>
                        </div>
                        <div class="form-group">
                            <button type="button" id="loginBtn" class="btn btn-primary form-control">登录</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" >
                    <div id="errorMsg" class="text-danger pull-left"></div>
                </div>
            </div>
        </div>
    </div>
<script>var BASE_PATH = "<?php echo Router::getBaseUrl();?>" </script>
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