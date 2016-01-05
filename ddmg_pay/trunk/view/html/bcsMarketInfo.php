<?php 
/**
 * 市场信息
 * bcsMarketInfo.php
 */
?>

<h1 class="page-header">市场信息</h1>

<div class="panel panel-primary" style="width:1100px;">

  <div class="panel-body">
    <div class="form-horizontal">
       <div class="form-group">
	    <label class="col-sm-2 control-label">商户编号:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MCH_NO'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">商户名称:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MCH_NAME'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">商户联系电话:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MCH_PHONE'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">商户地址:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MCH_ADDRES'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">商户创建日期:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MCH_CREATEDATE'];?>
	       </span>
	    </div>
	    <label class="col-sm-2 control-label">商户资总账号:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MCH_ACCOUNT_NO'];?>
	       </span>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">商户资总账户名称:</label>
	    <div class="col-sm-3">
	       <span style="vertical-align:middle;line-height:2.5;">
	           <?php echo $item['MCH_ACCOUNT_NAME'];?>
	       </span>
	    </div>
	  </div>
	  <button type="button" class="btn btn-primary" id="for-loadMarketInfo-btn">更新市场信息（测试）</button>
	  <button type="button" class="btn btn-primary" id="for-loadMarketChildAccount-btn">更新市场子账号信息（测试）</button>
	</div>
  </div>
</div>