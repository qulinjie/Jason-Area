<div class="bz">
    <div class="img" style="background-position:-8px -14px;"></div>
</div>
<div class="bd">
    <form id="findPasswordAccountForm">
        <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken(); ?>"/>
        <p class="service_l">账号<input type="text" name="tel" id="tel" value=""/></p>
        <b class="zh" id="telMsg"></b>
        <p class="service_x">验证码
            <input type="text" name="pinCode" id="pinCode" value="">
            <a href="javascript:void(0);">
                <img id="getPinCode" src="<?php echo Router::getBaseUrl() ?>findPassword/getPinCode" onclick="this.src='<?php echo Router::getBaseUrl() ?>findPassword/getPinCode'"/>
            </a>
        </p>
        <b class="tt" id="pinCodeMsg"></b>
        <p class="service_b"><input type="button" id="findPasswordAccountBtnSave" value="下一步"></p>
    </form>
</div>

