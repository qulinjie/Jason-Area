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
            <li><a href="<?php echo Router::getBaseUrl()?>user/login">返回首页</a></li>
        </ul>
    </div>
</nav>
<div class="container" style="padding-top: 150px;">
    <div class="row">
        <div class="col-lg-offset-1" style="margin-top: 21px;margin-bottom: 21px;">
            <img src="<?php echo Router::getBaseUrl()?>asset/img/step2.jpg"/>
        </div>
    </div>
    <div class="row" style="padding-top: 30px;">
        <div class="col-lg-offset-1 col-lg-10">
            <form id="secondStepForm"  class="form-horizontal">
                <input type="hidden" id="id" name="id" value="<?php echo $id;?>"/>
                <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
                <div class="form-group">
                    <label for="name" class="col-lg-offset-1 col-lg-2 control-label">办理人姓名</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="name" name="name" placeholder="办理人姓名"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="file" class="col-lg-offset-1 col-lg-2 control-label">办理授权书</label>
                    <div class="col-lg-4">
                        <input type="file" class="form-control" id="file" name="file" placeholder="办理授权书"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-4">
                        <input type="button" id="secondStepBtnSave" class="btn btn-primary col-lg-4" value="下一步"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script> var BASE_PATH = "<?php echo Router::getBaseUrl();?>";</script>
<script src="<?php echo Router::getBaseUrl()?>js/jquery.js"></script>
<script src="<?php echo Router::getBaseUrl()?>js/register.js"></script>
</body>
</html>