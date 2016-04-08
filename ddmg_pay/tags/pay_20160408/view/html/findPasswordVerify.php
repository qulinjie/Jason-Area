<div class="bz">
    <div class="img" style="background-position:-8px -91px;"></div>
</div>
<div class="bc">
    <form id="findPasswordVerifyForm">
        <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken(); ?>"/>
        <input type="hidden" id="tel" name="tel" value="<?php echo $tel; ?>"/>

        <p class="service_p"><span>手机号码：</span><span><?php echo $tel; ?></span></p>

        <p class="service_x">手机校验码<input name="code" type="text" id="code" value=""><span id="codenum"><a
                    href="javascript:void(0);" id="sendCode">获取短信验证码</a></span></p>
        <b class="ts" id="codeMsg"></b>

        <p class="service_l">办理人姓名<input type="text" name="name" id="name" value=""/></p>
        <b class="xm" id="nameMsg"></b>

        <p class="service_x" style="padding-left:463px;"><span>授权</span><span class="fj"><a href="javascript:void(0);">选择附件</a></span><input
                class="select" type="file" id="file" name="file"/></p>
        <b class="gs" id="fileMsg"></b>

        <p class="service_b"><input type="button" id="findPasswordVerifyBtnSave" value="下一步"></p>
    </form>
</div>

