<?php 
/**
 * bcsCustomerInfo.php
 */
?>
<div class="modal fade" id="add-entity-modal">
	<div class="modal-dialog" style="width: 1200px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_entity_title"></h4>
			</div>
			
			<div class="modal-body">
				<form class="form-horizontal" id="entity-form">
				  <div  id="entity-list"></div>
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
			<div class="modal-body" id="pay-pwd-info"></div>
			<div class="modal-body">
                <div style="margin-left: 85px;">
                     <font style="font-weight: bold;">支付密码：</font>
                     <input type="password" class="form-control" id="pay-pwd" value="" style="width: 150px;display: inline;"/>
                     <!-- 
                     <span id="tips_set_pay_pwd"><span style="color: #ADABAB;">您当前未设置支付密码，请先</span><a href="#">设置支付密码</a></span>
                     -->
                </div>                
			</div>
			<div class="alert alert-danger search-list-hint" id="pay-pwd-hint"></div>			
			<div class="modal-footer">
			    <button type="button" class="btn btn-primary" id="btn-pay-pwd">确定</button>			
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>

<h1 class="page-header">增加申请付款单</h1>

<div class="panel panel-primary">

  <div class="panel-body">
    <div class="form-horizontal">
       <input type="hidden" id="info-entity-id" value=""></input>
       
       <input type="hidden" id="info-entity-comp_name" value=""></input><!-- 收款单位 -->
       <input type="hidden" id="add-entity-comp_name_code" value=""></input><!-- 收款单位代码 -->
       <input type="hidden" id="add-entity-bank_no" value=""></input><!-- （已验证的）银行行号 -->
       <input type="hidden" id="add-entity-bank_name_checked" value=""></input><!-- （已验证的）银行名称 -->
       <input type="hidden" id="add-entity-fphm" value=""></input><!-- 业务单号-发票号码 -->
       <input type="hidden" id="add-entity-full_amount" value=""></input><!-- 采购单金娥  -->
       <input type="hidden" id="add-entity-comp_name_buyer" value=""></input><!-- 下游买家 -->
       <input type="hidden" id="add-entity-comp_name_buyer_code" value=""></input><!-- 下游买家代码 -->
       <input type="hidden" id="add-entity-erp_fgsdm" value="<?php echo $data_info['erp_fgsdm'];?>"></input>
       <input type="hidden" id="add-entity-erp_bmdm" value="<?php echo $data_info['erp_bmdm'];?>"></input>
       
       <input type="hidden" id="add-entity-ACCOUNT_NO" value="<?php echo $data_info['ACCOUNT_NO'];?>"></input><!-- 合伙人虚拟账号  -->
       <input type="hidden" id="add-entity-record_bank_type" value="<?php echo $data_info['record_bank_type'];?>"></input><!-- 合伙人 开户银行 1-长沙 2-浦发  -->
       
       <div class="form-group">
	    <label for="add-entity-apply_no" class="col-sm-2 control-label">申请单号</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-apply_no" placeholder="" value="<?php echo $data_info['id'];?>" />    				   
	    </div>
	    <label for="add-entity-apply_data" class="col-sm-2 control-label">申请日期</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control form_datetime" readonly="readonly" id="add-entity-apply_data" placeholder="" value="<?php echo $data_info['today'];?>"></input>
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-comp_name" class="col-sm-2 control-label">收款单位</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-comp_name" placeholder="" />    				   
	    </div>
	    <label for="add-entity-comp_account" class="col-sm-2 control-label">收款账号</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" id="add-entity-comp_account" placeholder="" />
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-bank_name" class="col-sm-2 control-label">开户行</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" id="add-entity-bank_name" placeholder="" />
		    <div id="div_data_bank" class="citydatadiv" style="display: none;"><ul></ul></div>
		    <a href="#" id="check-entity-bankName">验证开户行</a>
		    &nbsp;&nbsp;&nbsp;&nbsp;
		    <span id="span_check_success" style="display: none;color: blue;" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"><span style="color:blue">验证通过</span></span>
		    <span id="span_check_failed" style="display: none;color: red;" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"><span style="color:red">验证失败</span></span>
	    </div>
	    <label for="add-entity-amount" class="col-sm-2 control-label">金额</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" id="add-entity-amount" placeholder=""></input>
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-bank_flag" class="col-sm-2 control-label">同行/跨行</label>
	    <div class="col-sm-4">
	        <select class="form-control" id="add-entity-bank_flag">
              <option value="0">同行</option>
              <option value="1">跨行</option>
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
	  <div class="form-group">
	    <label for="add-entity-amount_type" class="col-sm-2 control-label">款项类别</label>
	    <div class="col-sm-4">
	        <select class="form-control" id="add-entity-amount_type">
              <option value="货款">货款</option>
            </select>
	    </div>
	    <label for="add-entity-use" class="col-sm-2 control-label">用途</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" id="add-entity-use" placeholder="">    				   
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-pwd" class="col-sm-2 control-label">备注</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" id="add-entity-comment" placeholder=""></input>
	    </div>
	    <label for="add-entity-erp_username" class="col-sm-2 control-label">申请人</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_username" placeholder="" value="<?php echo $data_info['erp_username'];?>" />    				   
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-erp_fgsmc" class="col-sm-2 control-label">机构</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_fgsmc" placeholder="" value="<?php echo $data_info['erp_fgsmc'];?>"></input>
	    </div>
	    <label for="add-entity-erp_bmmc" class="col-sm-2 control-label">部门</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_bmmc" placeholder="" value="<?php echo $data_info['erp_bmmc'];?>"></input>
	    </div>
	  </div>
	  <div class="alert alert-danger search-list-hint" id="ref-entity-hint"></div>
	  
	  <div id="add-button-group" style="padding-bottom: 2px;">
        	<a id="add-entity-new" class="btn btn-primary" href="#" >引用采购单</a>
        	<a id="add-entity-ref" class="btn btn-primary" href="#" >插入</a>
        </div>
        
      <p class="text-center"></p>
        
	  <div class="panel-body">
		<table class="table table-hover" id="data-list-table">
			<thead>
				<tr>
					<th>业务单号</th>
					<th>申请金额</th>
					<th>下游买家</th>
					<th>备注</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
		</tbody>
		</table>
  </div>
  
      <div id="div_submit_info" style="display: none;"></div>
  
      <div class="alert alert-danger" id="add-entity-hint" style="display: none;"></div>
  
	  <div id="add-button-group" style="padding-bottom: 2px;">
    	<a id="add-entity-create" class="btn btn-primary" href="#">提交申请</a>
    	<a id="add-entity-cancel" class="btn btn-primary" href="<?php echo Router::getBaseUrl();?>tradeRecord/getIndex">取消</a>
      </div>

	</div>
  </div>
</div>