<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<title>大大付款-用户登录</title>
<link rel="stylesheet" href="<?php echo Router::getBaseUrl();?>css/bootstrap.min.css" type="text/css">
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <img src="<?php echo Router::getBaseUrl()?>asset/img/logo.jpg"/>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#">返回首页</a></li>
        </ul>
    </div>
</nav>
<div class="container" style="padding-top: 150px;">
    <div class="row" style="padding-top: 30px;">
        <div class="col-lg-offset-1 col-lg-10">
            <form id="loginForm"  class="form-horizontal">
                <div class="form-group">
                    <label for="account" class="col-lg-offset-1 col-lg-2 control-label">帐号</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="account" name="account" placeholder="手机号码"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-lg-offset-1 col-lg-2 control-label">密码</label>
                    <div class="col-lg-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="登录密码"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-4">
                        <input type="button" id="loginBtn" class="btn btn-primary col-lg-4" value="登录"/>
                        <input type="reset"  class="btn btn-primary col-lg-4" value="重置"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script> var baseUrl = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl()?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/prng4.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rng.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/base64.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/jsbn.js"></script>
<script src="<?php echo Router::getBaseUrl();?>js/rsa.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/login.js"></script>
</body>
</html>