<?php 
/**
 * 客户信息
 * bcsCustomerInfo.php
 */
?>
<div class="modal fade" id="add-entity-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_entity_title"></h4>
			</div>
			
			<div class="modal-body">
				<form class="form-horizontal" id="entity-form">
				  <input type="hidden" class="form-control" id="info-entity-id" value=""></input>
				  <div class="form-group">
				    <label for="add-entity-amount" class="col-sm-2 control-label">金额</label>
				    <div class="col-sm-6">
				      <input class="form-control" id="add-entity-amount" placeholder="金额"></input>
				    </div>
				 </div>
				 <div class="form-group">
				    <label for="add-entity-pwd" class="col-sm-2 control-label">支付密码</label>
				    <div class="col-sm-6">
				      <input type="password" class="form-control" id="add-entity-pwd" placeholder="支付密码"></input>
				    </div>
				 </div>
				  <div class="alert alert-danger" id="add-entity-hint"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-add-entity">确定</button>
			</div>
		</div>
	</div>
</div>

<h1 class="page-header">账户信息</h1>

<div class="panel panel-primary" style="width:1100px;">

  <div class="panel-body">
    <div class="form-horizontal">
       <div class="form-group">
	    <label class="col-sm-2 control-label">余额:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['ACCT_BAL'];?>
	       </span>
	       <?php if( 1 == $item['record_bank_type'] ) { ?>
	       &nbsp;&nbsp;
	       <a id="add-transferIn-new" class="btn btn-primary" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">转入</a>
	       <a id="add-transferOut-new" class="btn btn-primary" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">转出</a>
	       <?php } ?>
	    </div>
	    <label class="col-sm-2 control-label">可用余额:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['AVL_BAL'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">虚拟账号:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['ACCOUNT_NO'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">会员证件类型:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_CERT_TYPE'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">会员证件号码:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_CERT_NO'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">会员名称:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_NAME'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">会员指定账号（结算账号）:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_SPE_ACCT_NO'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">会员指定户名:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_SPE_ACCT_NAME'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">行名:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_BANK_NAME'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">行号:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_BANK_NO'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">电话:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_TELENO'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">手机号:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_PHONE'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">开户日期:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['SIGNED_DATE'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">签约时间:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['ACT_TIME'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">会员联系地址:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MBR_ADDR'];?>
	       </span>
	    </div>
	  </div>
	  <!-- 
	  <button type="button" class="btn btn-primary" id="for-loadCustInfo-btn">更新客户信息（测试）</button>
	   -->
	</div>
  </div>
</div>