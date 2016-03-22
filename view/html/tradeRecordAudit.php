
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

<div class="panel panel-primary">

  <div class="panel-body">
    <div class="form-horizontal">
       <input type="hidden" id="info-entity-id" value=""></input>
       
       <div class="form-group">
	    <label for="add-entity-apply_no" class="col-sm-2 control-label">申请单号</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-apply_no" placeholder="" value="<?php echo $data_info['id'];?>" />    				   
	    </div>
	    <label for="add-entity-apply_data" class="col-sm-2 control-label">申请日期</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control form_datetime" readonly="readonly" id="add-entity-order_timestamp" placeholder="" value="<?php echo $data_info['order_timestamp'];?>"></input>
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-comp_name" class="col-sm-2 control-label">收款单位</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-seller_name" placeholder="" value="<?php echo $data_info['seller_name'];?>">    				   
	    </div>
	    <label for="add-entity-comp_account" class="col-sm-2 control-label">收款账号</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-comp_account" placeholder="" value="<?php echo $data_info['comp_account'];?>"></input>
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-bank_name" class="col-sm-2 control-label">开户行</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-bank_name" placeholder="" value="<?php echo $data_info['bank_name'];?>">    				   
	    </div>
	    <label for="add-entity-amount" class="col-sm-2 control-label">金额</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-order_bid_amount" placeholder="" value="<?php echo $data_info['order_bid_amount'];?>"></input>
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-amount_type" class="col-sm-2 control-label">款项类别</label>
	    <div class="col-sm-4">
	        <!-- <select class="form-control" id="add-entity-amount_type">
              <option value="货款">货款</option>
            </select> -->
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-amount_type" placeholder="" value="<?php echo $data_info['amount_type'];?>"></input>
	    </div>
	    <label for="add-entity-use" class="col-sm-2 control-label">用途</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-useTodo" placeholder="" value="<?php echo $data_info['useTodo'];?>">    				   
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-pwd" class="col-sm-2 control-label">备注</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-comment" placeholder="" value="<?php echo $data_info['comment'];?>"></input>
	    </div>
	    <label for="add-entity-account" class="col-sm-2 control-label">申请人</label>
	    <div class="col-sm-4">
		    <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_username" placeholder="" value="<?php echo $data_info['erp_username'];?>" />    				   
	    </div>	
	  </div>
	  <div class="form-group">
	    <label for="add-entity-organization" class="col-sm-2 control-label">机构</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_fgsmc" placeholder="" value="<?php echo $data_info['erp_fgsmc'];?>"></input>
	    </div>
	    <label for="add-entity-dept" class="col-sm-2 control-label">部门</label>
	    <div class="col-sm-4">
	        <input type="text" class="form-control" readonly="readonly" id="add-entity-erp_bmmc" placeholder="" value="<?php echo $data_info['erp_bmmc'];?>"></input>
	    </div>
	  </div>
	  <div class="alert alert-danger search-list-hint" id="ref-entity-hint"></div>
	    
      <p class="text-center"></p>
        
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
	            <td><?php echo number_format($item['item_price'],2);?></td>
	            <td><?php echo $item['itme_comp_name_buyer']; ?></td>
	            <td><?php echo $item['comment'];?></td>	            
	       </tr>
	       <?php } }?>
			
			</tbody>
		</table>
  </div>
  
      <div id="div_submit_info" style="display: none;"></div>
  
      <div class="alert alert-danger" id="add-entity-hint" style="display: none;"></div>
  
	  <div id="add-button-group" class="modal-footer">
    	<a id="add-entity-audit1" class="btn btn-primary" href="#">审批通过</a>
    	<a id="add-entity-audit2" class="btn btn-primary" href="#">审批不通过</a>
    	<!-- <a id="add-entity-cancel" class="btn btn-primary" href="<?php echo Router::getBaseUrl();?>tradeRecord/getIndex">取消</a>-->
      	<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
      </div>

	</div>
  </div>
</div>