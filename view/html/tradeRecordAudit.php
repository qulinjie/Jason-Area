
<style type="text/css">
.form-group-margin {
	margin-left: 20px !important;
	margin-right: 130px !important;
}
</style>

<div class="modal fade" id="sms-entity-modal">
	<div class="modal-dialog" style="width: 500px; margin-top:200px;">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close1" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button> -->
				<h4 class="modal-title">短信验证</h4>
			</div>
			<div class="modal-body" id="info-sms-trade"></div>
			<div class="modal-body">
				<div style="margin-left:35px;margin-bottom: 10px;">
                     <font style="font-weight: bold; margin-left:15px;">手机号码：</font>
                     <span><?php if(!empty($mobile)){ echo substr_replace($mobile, '****', 3, 4); }?></span> 
                </div> 
                <div style="margin-left: 35px;">
                     <font style="font-weight: bold;">短信验证码：</font>
                     <input type="text" class="form-control" id="vcode" name="vcode" style="width:120px; display: inline;" placeholder="验证码"/>
                     <input type="hidden" id="mobile" name="mobile" value="<?php echo $mobile;?>" />
                     <input type="button" id="sendCode" value="获取验证码" class="btn btn-default" style="background-color: rgb(255,0,255);"/>
                     <!-- 
                     <span id="tips_set_pay_pwd"><span style="color: #ADABAB;">您当前未设置手机号码，请先</span><a href="#">设置手机号码</a></span>
                     -->
                </div>                
			</div>
			<div class="alert alert-danger search-list-hint" id="info-sms-hint"></div>
			<div class="modal-footer">
			    <button type="button" class="btn btn-primary" id="btn-sms-pay">确定</button>			
				<button type="button" class="btn btn-default" id="sms-entity-close">关闭</button>
			</div>
		</div>
	</div>
</div>


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
				  <div  id="audit-entity-list"></div>
				</form>
			</div>
			<div class="modal-footer" style="display: none;">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-add-entity">确定</button>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-primary">

  <div class="panel-body">
    <div class="form-horizontal">
       <input type="hidden" id="info-entity-id" value="<?php echo $data_info['id'];?>"></input>
       
       <div class="form-group form-group-margin">
	    <label for="add-entity-apply_no" class="col-sm-2 control-label">申请单号</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-apply_no" placeholder="" value="<?php echo $data_info['apply_no'];?>" />    				   
	    </div>
	    <label for="add-entity-apply_data" class="col-sm-2 control-label">申请日期</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control form_datetime" readonly="readonly" id="add-entity-order_timestamp" placeholder="" value="<?php echo date("Y-m-d",strtotime($data_info['order_timestamp']));?>"></input>
	    </div>	
	  </div>
	  <div class="form-group form-group-margin">
	    <label for="add-entity-comp_name" class="col-sm-2 control-label">收款单位</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-seller_name" placeholder="" value="<?php echo $data_info['seller_name'];?>">    				   
	    </div>
	    <label for="add-entity-account" class="col-sm-2 control-label">申请人</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_username" placeholder="" value="<?php echo $data_info['erp_username'];?>" />    				   
	    </div>	    
	  </div>
	  <div class="form-group form-group-margin">
	  	<label for="add-entity-comp_account" class="col-sm-2 control-label">收款账号</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-comp_account" placeholder="" value="<?php echo $data_info['comp_account'];?>"></input>
	    </div>	
	  </div>
	  <div class="form-group form-group-margin">
	    <label for="add-entity-bank_name" class="col-sm-2 control-label">收款开户行</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-bank_name" placeholder="" value="<?php echo $data_info['bank_name'];?>">    				   
	    </div>
	    <?php 
	    	if(intval($data_info['order_apply_type'] == 1)){				
	    		if(isset($data_info['data_list']) && isset($data_info['data_list'][0]['item_comp_name_buyer'])){
	    ?>	      	
    	<label for="add-entity-buyer" class="col-sm-2 control-label">下游买家</label>
	    <div class="col-sm-4">
	      <input type="text" class="form-control" id="advance-comp_name_buyer" readonly="readonly" placeholder="" value="<?php echo $data_info['data_list'][0]['item_comp_name_buyer'];?>"></input>		        
	    </div>   
	    <?php } }?>	    
	  </div>	
	  <div class="form-group form-group-margin">
	  	<label for="add-entity-amount" class="col-sm-2 control-label">金额</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-order_bid_amount" placeholder="" value="<?php echo $data_info['order_bid_amount'];?>"></input>
	    </div>
	    <label for="add-entity-amount_type" class="col-sm-2 control-label">款项类别</label>
	    <div class="col-sm-4">	        
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-amount_type" placeholder="" value="<?php echo $data_info['amount_type'];?>"></input>
	    </div>	
	  </div>
	  <div class="form-group form-group-margin">
	    <label for="add-entity-bank_flag" class="col-sm-2 control-label">同行/跨行</label>
	    <div class="col-sm-4">	    	      
        	<input type="text" class="form-control" readonly="readonly" id="add-entity-bank_flag" placeholder="" 
        		value="<?php echo ($data_info['bank_flag'] ==0) ? '同行' : '跨行';?>"></input>
	    </div>
	    <span id="span_local_flag" <?php echo ($data_info['bank_flag'] ==0) ? 'style="display: none;"' : '' ?>>
	    <label for="add-entity-local_flag" class="col-sm-2 control-label">同城/异地</label>
	    <div class="col-sm-4">	        
            <input type="text" class="form-control" readonly="readonly" id="add-entity-local_flag" placeholder="" 
        		value="<?php echo ($data_info['local_flag'] ==0) ? '同城' : '异地';?>"></input>
	    </div>	    
	    </span>	    
	  </div>	
	  <div class="form-group form-group-margin">	    
	    <label for="add-entity-use" class="col-sm-2 control-label">用途</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-useTodo" placeholder="" value="<?php echo $data_info['useTodo'];?>">    				   
	    </div>	  
	    <label for="add-entity-pwd" class="col-sm-2 control-label">备注</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-comment" placeholder="" value="<?php echo $data_info['comment'];?>"></input>
	    </div>	    	
	  </div>
	  <div class="form-group form-group-margin">
	    <label for="add-entity-organization" class="col-sm-2 control-label">机构</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_fgsmc" placeholder="" value="<?php echo $data_info['erp_fgsmc'];?>"></input>
	    </div>
	    <label for="add-entity-dept" class="col-sm-2 control-label">部门</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_bmmc" placeholder="" value="<?php echo $data_info['erp_bmmc'];?>"></input>
	    </div>
	  </div>
	  <?php if($data_info['apply_status'] == 2 && $is_admin && '2' == strval($audit_level)) { ?>
		  <div class="form-group form-group-margin">			  
			  <label for="add-entity-dept" class="col-sm-2 control-label">余额</label>
			  <div class="col-sm-4">
				  <input type="text" class="form-control" readonly="readonly" id="add-entity-acct_bal" placeholder="" value="<?php echo $data_info['acct_bal']; ?>">
				  <!--<input type="text" class="form-control" readonly="readonly" id="add-entity-avl_bal" placeholder="" value="<?php echo $data_info['avl_bal']; ?>">-->
			  </div>
		  </div>
	  <?php }?>
	  <div class="alert alert-danger search-list-hint" id="ref-entity-hint"></div>
	    
      <p class="text-center"></p>
      <?php if(intval($data_info['order_apply_type']) == 0){ ?> 
	  <div class="panel-body">
		<table class="table table-hover" id="data-list-table">
			<thead>
				<tr>
					<th>业务单号</th>
					<th>申请金额</th>
					<th>下游买家</th>
					<th>备注</th>					
				</tr>
			</thead>
			<tbody>
			<?php if(isset($data_info['data_list'])){ foreach ($data_info['data_list'] as $item){ ?>
			<tr>
	            <td style="display:none"><?php echo $item['id'];?></td>
	            <td><?php echo $item['itme_no'];?></td>	            
	            <td><?php echo number_format($item['bid_amount'],2);?></td>
	            <td><?php echo $item['item_comp_name_buyer']; ?></td>
	            <td><?php echo $item['comment'];?></td>	            
	       </tr>
	       <?php } }?>			
		   </tbody>
		</table>
  	  </div>
  	  <?php }?>
  
      <div id="div_submit_info" style="display: none;"></div>  
      <div class="alert alert-danger" id="add-entity-hint" style="display: none;"></div>  	  
  	  
	  <div id="add-button-group" class="modal-footer">	 
	  	<?php 
		  	//audit_user_id_first audit_user_id_second
		  	//apply_status 申请状态 1一级待审核 2一级审核通过 3一级审核驳回 4二级待审核 5二级审核通过 6二级审核驳回
		  	//order_status 订单交易状态 1-待付款 2-已付款
		  	
		  	$html1 = '<a id="add-entity-audit1" class="btn btn-primary" href="#">审批通过</a>
    				  <a id="add-entity-audit2" class="btn btn-primary" href="#">审批驳回</a>';
		  	$html2 = '<a id="add-entity-audit21" class="btn btn-primary" href="#">审批通过</a>
    				  <a id="add-entity-audit22" class="btn btn-primary" href="#">审批驳回</a>';
		  	
		  	$html3 = '<button id="add-entity-pay-pass" type="button" class="btn btn-default disabled" data-dismiss="modal">已付款</button>';
		  	$html4 = '<a id="add-entity-pay" class="btn btn-primary" href="#">付款</a>';
		  	
		  	$html12 = '<button id="add-entity-audit-yes-pass1" type="button" class="btn btn-default disabled" data-dismiss="modal">一级审批已通过</button>';
		  	$html25 = '<button id="add-entity-audit-yes-pass2" type="button" class="btn btn-default disabled" data-dismiss="modal">二级审批已通过</button>';
		  	
		  	$html13 = '<button id="add-entity-audit-no-pass1" type="button" class="btn btn-default disabled" data-dismiss="modal">一级审批已驳回</button>';
		  	$html26 = '<button id="add-entity-audit-no-pass2" type="button" class="btn btn-default disabled" data-dismiss="modal">二级审批已驳回</button>';
		  	
		  	if(!$is_admin && '1' == strval($audit_level)){
		  		//一级审批
		  		if(1==$data_info['apply_status']){		  					  			
					echo $html1; //审批通过/审批驳回 
		  		}
		  		if(2==$data_info['apply_status']){
		  			echo $html12; //一级审批已通过
		  		}
		  	}elseif($is_admin && '2' == strval($audit_level)){
		  		//二级审批
		  		if(2==$data_info['apply_status'] || 4==$data_info['apply_status']){
		  			echo $html2; //审批通过/审批驳回 
		  		}
		  		if(5==$data_info['apply_status']){
		  			echo $html25; //二级审批已通过
		  		}		  		
		  		/* if(5==$data_info['apply_status'] && 1==$data_info['order_status']){
		  			echo $html4; //付款
		  		} */		  		
		  	}		  	
		  	if(3==$data_info['apply_status']){
		  		echo $html13; //一级审批已驳回
		  	}		  	
		  	if(6==$data_info['apply_status']){
		  		echo $html26; //一级审批已驳回
		  	}
		  	if(5==$data_info['apply_status'] && 2==$data_info['order_status']){
		  		echo $html3; //已付款
		  	}	  	
	  	?> 	
	  	<a id="add-entity-pay" class="btn btn-primary <?php echo (5==$data_info['apply_status'] && 2!=$data_info['order_status']) ? '' : ' hidden'?>" href="#">付款</a>
	  	
	    <!--
	    <?php if(1==$data_info['apply_status']){ ?>	  	
			<a id="add-entity-audit1" class="btn btn-primary" href="#">审批通过</a>
    		<a id="add-entity-audit2" class="btn btn-primary" href="#">审批驳回</a>    		
    	<?php }else{ ?>
    		<button id="add-entity-audit-pass" type="button" class="btn btn-default disabled" data-dismiss="modal">已审批</button>
       	<?php }?>
       	<?php if(2==$data_info['apply_status'] && 2==$data_info['order_status']){ ?>
       		<button id="add-entity-pay-pass" type="button" class="btn btn-default disabled" data-dismiss="modal">已付款</button>
       	<?php }?>
       	<a id="add-entity-pay" class="btn btn-primary <?php echo (2==$data_info['apply_status'] && 2!=$data_info['order_status']) ? '' : ' hidden'?>" href="#">付款</a>
		  <a id="add-entity-cancel" class="btn btn-primary" href="#">关闭</a> 
		-->
        <button id="add-entity-cancel" type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>

	</div>
  </div>
</div>
<script>
	$(function() {
		<?php
		if($data_info['apply_status'] == 2) {
		?>
		var bid_amount = $('#add-entity-order_bid_amount').val();
		var acct_bal = $('#add-entity-acct_bal').val();
		if (parseFloat(bid_amount) > parseFloat(acct_bal)) {
			$('#add-entity-audit21').addClass('disabled');
			$('#add-entity-hint').css('display', 'block').html('当申请金额高于余额,不能进行审批通过操作！');
		}
		<?php
		}
		?>
	});
</script>