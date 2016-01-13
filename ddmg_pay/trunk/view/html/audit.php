<style>
	#content h1,label,input{
		margin: 5px;
		font: 500 12px 微软雅黑;
		color: #000000;
		line-height: 30px;
	}
	#content h1{
		margin: 10px 5px 10px 0;
		font: bold 12px 微软雅黑;
		color: #8b8b8b;
		line-height: 40px;
	}
</style>
<div id="content" class="right" style="margin: 30px 120px;">
	<h1>个人认证</h1>
	<div>
		<label for="realName">真实姓名&nbsp;&nbsp;&nbsp;</label>
		<input type="text" id="realName" value="<?php echo $data['real_name'];?>" disabled/><br/>
		<label>个人证书</label>
		<img src="<?php echo Router::getBaseUrl()?>audit/getCertImg?flag=1" style="width:700px;height: auto;"/>
	</div>
	<h1>企业认证</h1>
	<div>
		<label for="legal_name">法人姓名&nbsp;&nbsp;&nbsp;</label>
		<input type="text" id="legal_name" value="<?php echo $data['legal_name'];?>" disabled/><br/>
		<label for="company_name">公司名称&nbsp;&nbsp;&nbsp;</label>
		<input type="text" id="company_name" value="<?php echo $data['company_name'];?>" disabled/><br/>
		<label for="business_license">营业执照号</label>
		<input type="text" id="business_license" value="<?php echo $data['business_license'];?>" disabled/><br/>
		<label>企业证书</label>
		<img src="<?php echo Router::getBaseUrl()?>audit/getCertImg" style="width:700px;"/>
	</div>
</div>