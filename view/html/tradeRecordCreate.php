
<style type="text/css">
.form-group-margin {
	margin-left: 30px !important;
	margin-right: 120px !important;
}
</style>

<div class="modal fade" id="add-entity-modal">
	<div class="modal-dialog" style="width: 1300px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_entity_title"></h4>
			</div>			
			<div class="modal-body">
				<form class="form-horizontal" id="entity-form">
				  <div  id="add-entity-list"></div>
				</form>
			</div>
			<div class="modal-footer" style="display: none;">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-add-entity">确定</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="pay-pwd-modal">
	<div class="modal-dialog" style="width: 500px; margin-top:200px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">验证支付密码</h4>
			</div>			
			<?php if(PayPasswordController::checkExist()){?>
				<div class="modal-body" id="pay-pwd-info"></div>
				<div class="modal-body">
	                <div style="margin-left: 85px;">
	                     <font style="font-weight: bold;">支付密码：</font>
	                     <input type="password" class="form-control" id="pay-pwd" value="" style="width: 150px;display: inline;"/>                     
	                </div>                
				</div>
				<div class="alert alert-danger search-list-hint" id="pay-pwd-hint"></div>			
				<div class="modal-footer">
				    <button type="button" class="btn btn-primary" id="btn-pay-pwd">确定</button>			
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				</div>
			<?php }else {?>
				<div class="modal-body" style="margin-top:60px; margin-bottom:70px;">
					<span><span style="color: #ADABAB; margin-left:60px;">您当前未设置支付密码，请先</span><a href="<?php echo Router::getBaseUrl(). 'payPassword/set'; ?>">设置支付密码</a></span>      
				</div>	      
			<?php }?>			
		</div>
	</div>
</div>

<h1 class="page-header"><?php if(strval($is_advance) == '1'){ echo '增加预付款单';}else{ echo '增加订单付款单';}?></h1>

<div class="panel panel-primary">
  <div class="panel-body">
    <div class="form-horizontal">
       <input type="hidden" id="info-entity-id" value=""></input>
       
       <input type="hidden" id="add-entity-is_advance" value="<?php echo $is_advance;?>"></input> <!-- 是否是预付款 -->
       <!--<input type="hidden" id="info-entity-comp_name" value=""></input> 收款单位 -->
       <input type="hidden" id="add-entity-comp_name_code" value=""></input><!-- 收款单位代码 -->
       <input type="hidden" id="add-entity-bank_no" value=""></input><!-- （已验证的）银行行号 -->
       <input type="hidden" id="add-entity-bank_name_checked" value=""></input><!-- （已验证的）银行名称 -->
       <input type="hidden" id="add-entity-full_amount" value=""></input><!-- 金额  -->
       
       <input type="hidden" id="add-entity-fphm" value=""></input><!-- 业务单号-发票号码 -->       
       <input type="hidden" id="add-entity-comp_name_buyer" value=""></input><!-- 下游买家 -->
       <input type="hidden" id="add-entity-comp_name_buyer_code" value=""></input><!-- 下游买家代码 -->
       
       <input type="hidden" id="add-entity-erp_fgsdm" value="<?php echo $data_info['erp_fgsdm'];?>"></input><!-- erp分公司代码 -->
       <input type="hidden" id="add-entity-erp_bmdm" value="<?php echo $data_info['erp_bmdm'];?>"></input><!--erp部门代码 -->
       <input type="hidden" id="add-entity-ACCOUNT_NO" value="<?php echo $data_info['ACCOUNT_NO'];?>"></input><!-- 合伙人虚拟账号  -->
       <input type="hidden" id="add-entity-record_bank_type" value="<?php echo $data_info['record_bank_type'];?>"></input><!-- 合伙人 开户银行 1-长沙 2-浦发  -->
       
       <div class="form-group form-group-margin">
	    <label for="add-entity-apply_no" class="col-sm-2 control-label">申请单号</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-apply_no" placeholder="" value="<?php echo $data_info['id'];?>" />    				   
	    </div>
	    <label for="add-entity-apply_data" class="col-sm-2 control-label">申请日期</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control form_datetime" readonly="readonly" id="add-entity-apply_data" placeholder="" value="<?php echo $data_info['today'];?>"></input>
	    </div>	
	  </div>
	  <div class="form-group form-group-margin">
	    <label for="add-entity-comp_name" class="col-sm-2 control-label">收款单位</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" <?php if(strval($is_advance) != '1'){ echo 'readonly="readonly"';}?> id="add-entity-comp_name" placeholder="" />    				   
	    </div>
	    <label for="add-entity-erp_username" class="col-sm-2 control-label">申请人</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_username" placeholder="" value="<?php echo $data_info['erp_username'];?>" />    				   
	    </div>	   	
	  </div>
	  <div class="form-group form-group-margin">
	   <label for="add-entity-comp_account" class="col-sm-2 control-label">收款账号</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" id="add-entity-comp_account" placeholder="" />
	    </div>
	  </div>
	  <div class="form-group form-group-margin">
	    <label for="add-entity-bank_name" class="col-sm-2 control-label">收款开户行</label>
	    <div class="col-sm-4">
	    	<div class="form-inline">
		    	<input type="text" class="form-control" id="add-entity-bank_name" placeholder="" style="width: 360px;"/>
		    	<input type="button" class="btn btn-primary" id="btn-bank_name" value="查" />
		    </div>
		    <div id="div_data_bank" class="citydatadiv" style="display: none;"><ul></ul></div>
		    <a href="#" id="check-entity-bankName">验证开户行</a>
		    &nbsp;&nbsp;&nbsp;&nbsp;
		    <span id="span_check_success" style="display: none;color: blue;" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"><span style="color:blue">验证通过</span></span>
		    <span id="span_check_failed" style="display: none;color: red;" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"><span style="color:red">验证失败</span></span>
	    </div>	    
	  </div>
	  <div class="form-group form-group-margin">
	  	<label for="add-entity-amount" class="col-sm-2 control-label">总金额</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" <?php if(strval($is_advance) != '1'){echo 'readonly="readonly"';}?> id="add-entity-apply_total_amount" placeholder=""></input>
	    </div>
	    <?php if(strval($is_advance) == '1'){ ?>
	    	<label for="add-entity-buyer" class="col-sm-2 control-label">下游买家</label>
		    <div class="col-sm-4">
		        <input type="text" class="form-control" id="add-entity-buyer" placeholder=""></input>
		    </div>
	    <?php }?>
	  </div>	
	  <div class="form-group form-group-margin">
	    <label for="add-entity-bank_flag" class="col-sm-2 control-label">同行/跨行</label>
	    <div class="col-sm-4">
	        <select class="form-control" id="add-entity-bank_flag">
	        	<option value="-1">-请选择-</option>
            	<option value="0">同行</option>
            	<option value="1">跨行</option>
            </select>
	    </div>
	    <label for="add-entity-amount_type" class="col-sm-2 control-label">款项类别</label>
	    <div class="col-sm-4">	    	    
	        <select class="form-control" id="add-entity-amount_type" <?php if(strval($is_advance) == '1'){ echo "disabled=true";}?>>
	          <option value="">-请选择-</option>
              <option value="1" selected=selected >货款</option>
              <?php if(strval($is_advance) == '1'){ echo '<option value="2" selected=selected >预付款</option>';}?>
            </select>	
	    </div>	    	    
	    <span id="span_local_flag" style="display: none;">	
	    <label for="add-entity-local_flag" class="col-sm-2 control-label">同城/异地</label>
	    <div class="col-sm-4">
	        <select class="form-control" id="add-entity-local_flag">
              <option value="0">同城</option>
              <option value="1">异地</option>
            </select>
	    </div>
	    </span>
	  </div>	  
	  <div class="form-group form-group-margin">
	    <label for="add-entity-use" class="col-sm-2 control-label">用途</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" id="add-entity-use" placeholder="">    				   
	    </div>	
	    <label for="add-entity-pwd" class="col-sm-2 control-label">备注</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" id="add-entity-comment" placeholder=""></input>
	    </div>	    	
	  </div>
	  <div class="form-group form-group-margin">
	    <label for="add-entity-erp_fgsmc" class="col-sm-2 control-label">申请人机构</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_fgsmc" placeholder="" value="<?php echo $data_info['erp_fgsmc'];?>"></input>
	    </div>
	    <label for="add-entity-erp_bmmc" class="col-sm-2 control-label">申请人部门</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_bmmc" placeholder="" value="<?php echo $data_info['erp_bmmc'];?>"></input>
	    </div>
	  </div>
	  <div class="alert alert-danger search-list-hint" id="ref-entity-hint"></div>
	  
	  <?php if(strval($is_advance) != '1'){?>
		  <div id="add-button-group" style="padding-bottom: 2px;">	  	  		
	        <a id="add-entity-quote" class="btn btn-primary" href="#" >引订单</a>      	
	        <!-- <a id="add-entity-ref" class="btn btn-primary" href="#" >插入</a>  -->
	      </div>        
	      <p class="text-center"></p>        
		  <div class="panel-body">
			<table class="table table-hover" id="data-list-table">
				<thead>
					<tr>
						<th>业务单号</th>
						<th>订单采购金额</th>
						<th>申请金额</th>
						<th>下游买家</th>						
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
			</tbody>
			</table>
	      </div> 
	   <?php }?>
	   
	   <div id="div_submit_info" style="display: none;"></div>  
       <div class="alert alert-danger" id="add-entity-hint" style="display: none;"></div>	  
	   <div id="add-button-group" class="text-center" style="padding-bottom: 2px; margin-top:30px; margin-bottom:10px;">
    	 <a id="<?php if(strval($is_advance) == '1'){ echo "add-entity-create-advance";}else {echo "add-entity-create"; } ?>" class="btn btn-primary" href="#">&nbsp;&nbsp;提交申请&nbsp;&nbsp;</a>
    	 <a id="add-entity-cancel" class="btn btn-primary" href="<?php echo Router::getBaseUrl();?>tradeRecord/getIndex">取消</a>
       </div>
	</div>
  </div>
</div>