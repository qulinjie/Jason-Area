<?php 
/**
 * 客户信息
 * bcsCustomerInfo.php
 */
?>

<h1 class="page-header">账户信息</h1>

<div class="panel panel-primary">

  <div class="panel-body">
    <div class="form-horizontal">
       <div class="form-group">
	    <label class="col-sm-2 control-label">余额:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['ACCT_BAL'];?>
	       </span>
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
	  
	  <button type="button" class="btn btn-primary" id="for-loadCustInfo-btn">更新客户信息（测试）</button>
	  
	</div>
  </div>
</div>