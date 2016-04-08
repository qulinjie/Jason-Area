<div class="bz">
    <div class="img" style="background-position:-8px -352px;"></div>
</div>
<div class="zc" style="height:415px;">
    <div class="ts">您的账号注册成功，请继续完善资料。</div>
    <form id="personForm" style="margin-top:24px;">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>
        <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken(); ?>"/>

        <p class="service_l">办理人姓名<input type="text" name="name" id="name"/></p>
        <b class="zh" id="nameMsg"></b>

        <p class="service_c" style="margin-top:15px;">办理授权书
            <span class="fj"><a href="">选择附件</a></span>
            <span class="xz"><a href="">下载模板</a></span>
            <input class="select" type="file" id="file" name="file">
        </p>
        <b class="gs" id="fileMsg"></b>

        <p class="service_y">
            <input class="next" type="button" id="personBtnSave" value="下一步">
            <input class="last_step" type="button" value="上一步">
        </p>
    </form>
</div>

