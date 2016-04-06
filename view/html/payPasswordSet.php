
<h1 class="page-header">设置支付密码</h1>
<div class="ts">您当前未设置支付密码，请先设置支付密码</div>
<div class="panel panel-primary">
  <div class="panel-body">	
    <div class="form-horizontal">
       <input type="hidden" id="token" name="token" value="<?php echo UserController::getToken(); ?>"/>
       <div class="form-group"></div>   
       <div class="form-group">
	    <label for="add-entity-apply_no" class="col-sm-2 control-label">支付密码</label>
	    <div class="col-sm-3">
		    <input type="password" class="form-control" id="password" />   
		    <span id="passwordMsg" style="color:red;"></span> 				   
	    </div>	    	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-comp_name" class="col-sm-2 control-label">确认支付密码</label>
	    <div class="col-sm-3">
		    <input type="password" class="form-control" id="rePassword" />  
		    <span id="rePasswordMsg" style="color:red;"></span>  				   
	    </div>	    
	  </div>  
      <div class="alert alert-danger" id="entity-hint" style="display: none;"></div>  
      <div class="form-group"></div>
	  <div id="button-group" style="padding-bottom: 2px; margin-left: 30%;">
    	<a id="pay-pwd-set" class="btn btn-primary" href="#" style=" width:120px;">确定</a>
      </div>
      
	</div>
  </div>
</div>