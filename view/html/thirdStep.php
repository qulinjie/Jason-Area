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
            <img src="<?php echo Router::getBaseUrl()?>asset/img/step3.jpg"/>
        </div>
    </div>
    <div class="row" style="padding-top: 30px;">
        <div class="col-lg-offset-1 col-lg-10">
            <form id="thirdStepForm"  class="form-horizontal">
                <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken();?>"/>
                <input type="hidden" id="id" name="id" value="<?php echo $id;?>"/>
                <div class="form-group">
                    <label for="legalPerson" class="col-lg-offset-1 col-lg-2 control-label">法人姓名</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="legalPerson" name="legalPerson" placeholder="法人姓名"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="companyName" class="col-lg-offset-1 col-lg-2 control-label">公司全称</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="companyName" name="companyName" placeholder="公司全称"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="license" class="col-lg-offset-1 col-lg-2 control-label">营业执照注册号</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="license" name="license" placeholder="营业执照注册号"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <label for="file" class="col-lg-offset-1 col-lg-2 control-label">营业执照附件</label>
                    <div class="col-lg-4">
                        <input type="file" class="form-control" id="file" name="file" placeholder="营业执照附件"/>
                    </div>
                    <div class="col-lg-2"><span class="text-danger"></span></div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-4">
                        <input type="button" id="thirdStepBtnSave" class="btn btn-primary col-lg-4" value="提交"/>
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