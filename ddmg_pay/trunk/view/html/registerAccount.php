<div class="bz">
    <div class="img" style="background-position:-8px -266px;"></div>
</div>
<div class="zc">
    <form id="accountForm">
        <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken(); ?>"/>

        <p class="service_l">账号<input type="text" id="tel" name="tel"/></p>
        <b class="zh" id="telMsg"></b>

        <p class="service_x">验证码<input type="text" id="code" name="code" style="width: 109px;"/>
            <input type="button" id="registerSendCode" name="sendCode" value="获取短信验证码"
                   style="background-color: rgb(216,216,216);color: #FFF;width: 112px;margin-left:0;padding-left: 6px;"/>
        </p>
        <b class="tb" id="codeMsg"></b>

        <p class="service_l">登录密码<input type="password" id="password" name="password"/></p>
        <b class="mm" id="passwordMsg"></b>

        <p class="service_l">确认登录密码<input type="password" id="rePassword" name="rePassword"/></p>
        <b class="qr" id="rePasswordMsg"></b>

        <p class="service_tex"><input type="checkbox" name="agree" id="agree"/><span>我同意《大大支付服务协议》</span></p>

        <p class="service_b"><input type="button" name="accountBtnSave" id="accountBtnSave" value="下一步"/></p>
    </form>
</div>

