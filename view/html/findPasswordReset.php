<div class="bz">
    <div class="img" style="background-position:-8px -181px;"></div>
</div>
<div class="bd" style="height:411px;">
    <form id="findPasswordResetForm">
        <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken(); ?>"/>
        <input type="hidden" id="tel" name="tel" value="<?php echo $tel; ?>"/>

        <p class="service_p"><span>手机号码：</span><span><?php echo $tel; ?></span></p>

        <p class="service_l">新登录密码<input type="password" name="password" id="password" value=""/></p>
        <b class="mm" id="passwordMsg"></b>

        <p class="service_l">确认登录密码<input type="password" name="rePassword" id="rePassword" value=""/></p>
        <b class="mms" id="rePasswordMsg"></b>

        <p class="service_b"><input class="" type="button" id="findPasswordResetBtnSave" value="下一步"></p>
    </form>
</div>
