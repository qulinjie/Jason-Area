
<h1 class="page-header">重置支付密码</h1>
<div class="panel panel-primary">
  <div class="panel-body">	
    <div class="form-horizontal">
       <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken(); ?>"/>
       <div class="form-group"></div> 
       
       <div class="form-group">
	    <label for="add-entity-apply_no" class="col-sm-2 control-label">新支付密码</label>
	    <div class="col-sm-3">
		    <input type="password" class="form-control" id="password" />   
		    <span id="passwordMsg" style="color:red;"></span> 				   
	    </div>	    	
	   </div>
	  <div class="form-group">
	    <label for="add-entity-comp_name" class="col-sm-2 control-label">确认新支付密码</label>
	    <div class="col-sm-3">
		    <input type="password" class="form-control" id="rePassword" />  
		    <span id="rePasswordMsg" style="color:red;"></span>  				   
	    </div>	    
	  </div>	  
	  <div class="form-group">
	    <label for="add-entity-comp_name" class="col-sm-2 control-label">接收短信手机</label>
	    <div class="col-sm-3">
		   	<span><?php if(!empty($mobile)){ echo substr_replace($mobile, '****', 3, 4); }?></span>
	    </div>	    
	  </div>
	  <div class="form-group">
	    <label for="add-entity-apply_no" class="col-sm-2 control-label">短信验证</label>
	    <div class="col-sm-3">
		    <input type="text" class="form-control" id="code" name="code" style="width:120px; display: inline;" placeholder="验证码"/>
            <input type="hidden" id="mobile" name="mobile" value="<?php echo $mobile;?>" />
            <input type="button" id="sendCode" value="获取验证码" class="btn btn-default" style="background-color: rgb(255,0,255);"/>
			<span class="help-block" id="codeMsg" style="color:red;"></span> 		
		</div>	    	
	   </div>  
      <div class="alert alert-danger" id="entity-hint" style="display: none;"></div>  
      <div class="form-group"></div>
	  <div id="button-group" style="padding-bottom: 2px; margin-left: 30%;">
    	<a id="pay-pwd-reset" class="btn btn-primary" href="#" style=" width:120px;">确定</a>
      </div>
      
	</div>
  </div>
</div>