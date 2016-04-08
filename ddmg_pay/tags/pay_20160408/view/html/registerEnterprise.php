<div class="bz">
    <div class="img" style="background-position:-8px -433px;"></div>
</div>
<div class="zc" style="height:350px;">
    <form id="enterpriseForm" style="margin-top:30px;">
        <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken(); ?>"/>
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>

        <p class="service_l">法人姓名<input type="text" name="legalPerson" id="legalPerson"/></p>
        <b class="zh" id="legalPersonMsg"></b>

        <p class="service_l">公司全称<input type="text" name="companyName" id="companyName"/></p>
        <b class="tb" id="companyNameMsg"></b>

        <p class="service_l">营业执照注册号<input type="text" name="license" id="license"/></p>
        <b class="mm" id="licenseMsg"></b>

        <p class="service_c" style="margin-top:15px; padding-left:429px;">营业执照附件
            <span class="fj"><a href="javascript:void(0);">选择附件</a></span>
            <span class="xz"><a href="javascript:void(0);">下载模板</a></span>
            <input class="select" type="file" id="file" name="file">
        </p>
        <b class="qr" id="fileMsg"></b>

        <p class="service_y" style="padding-top:48px;">
            <input class="next" type="button" id="enterpriseBtnSave" value="提交">
            <input class="last_step" type="button" value="上一步">
        </p>
    </form>
</div>
