<div class="reset">
    <form>
        <input type="hidden" name="token" id="token" value="<?php echo UserController::getToken(); ?>"/>
        <?php if ($status) { ?>
            <p class="service_l">当前支付密码<input type="password" name="payOldPwd" id="payOldPwd"></p>
            <b id="payOldPwdMsg" class="paw"></b>
            <p class="service_l">新支付密码<input type="password" name="payNewPwd" id="payNewPwd"></p>
            <b id="payNewPwdMsg" class="pawo"></b>
            <p class="service_l">确认新支付密码<input type="password" name="payNewPwd2" id="payNewPwd2"></p>
            <b id="payNewPwd2Msg" class="pawod"></b>
        <?php } else { ?>
            <p class="service_l">支付密码<input type="password" name="initPayNewPwd" id="initPayNewPwd"></p>
            <b id="initPayNewPwdMsg" class="z_paw"></b>
            <p class="service_l">确认支付密码<input type="password" name="initPayNewPwd2" id="initPayNewPwd2"></p>
            <b id="initPayNewPwd2Msg" class="z_pawd"></b>
        <?php } ?>
        <p class="service_b"><input type="button" id="payPasswordReset" value="确认"></p>
    </form>
</div>

