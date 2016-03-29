<?php 
/**
 * bcsTrade.php
 */
?>

<div class="modal fade" id="info-pay-modal">
	<div class="modal-dialog" style="width: 1000px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">付款</h4>
			</div>
			<div class="modal-body" id="info-pay-trade"></div>
			<div class="modal-body">
                <div style="margin-left: 15px;">
                     <font style="font-weight: bold;">支付密码：</font>
                     <input type="password" class="form-control" id="add-pay-pwd" value="" style="width: 150px;display: inline;"/>
                     <span id="tips_set_pay_pwd"><span style="color: #ADABAB;">您当前未设置支付密码，请先</span><a href="#">设置支付密码</a></span>
                </div>
                <div style="margin: 15px;">
                    <button type="button" class="btn btn-primary" id="btn-add-pay" style="width: 150px;height: 38px;">确定支付</button>
                </div>
			</div>
			<div class="alert alert-danger" id="info-pay-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add-entity-modal">
	<div class="modal-dialog" style="width: 700px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_entity_title">付款</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="entity-form">
				    <input type="hidden" class="form-control" id="info-entity-id" value=""></input>
				  <div class="form-group">
				    <label for="add-entity-code" class="col-sm-2 control-label">授权码</label>
				    <div class="col-sm-6">
				        <span id="info-entity-code" style="vertical-align:middle;line-height:2.5;">DDMG0099</span>
				        <input type="hidden" class="form-control" id="add-entity-code" value=""></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-type" class="col-sm-2 control-label">使用方式</label>
				    <div class="col-sm-1" style="padding-right: 0px;padding-left: 0px;"><input type="radio" class="form-control" id="add-entity-type-count" name="add-entity-type-rdo" value="1" checked="checked"/></div>
				    <div class="col-sm-2" style="vertical-align:middle;line-height:2.8;padding-left: 0px;">按次数</div>
				    <div class="col-sm-1" style="padding-right: 0px;padding-left: 0px;"><input type="radio" class="form-control" id="add-entity-type-time" name="add-entity-type-rdo" value="2"/></div>
				    <div class="col-sm-2" style="vertical-align:middle;line-height:2.8;padding-left: 0px;">按时间</div>
				  </div>
				  <div class="form-group" id="div-active-count">
				    <label for="add-entity-active_count" class="col-sm-2 control-label">可用次数</label>
				    <div class="col-sm-6">
				      <input class="form-control" id="add-entity-active_count" placeholder="可用次数"
				            onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"></input>
				    </div>
				  </div>
				  <div class="form-group" id="div-time-start" style="display: none;">
				    <label for="add-entity-time_start" class="col-sm-2 control-label">有效开始时间</label>
				    <div class="col-sm-6">
				      <input class="form-control form_datetime" id="add-entity-time_start" placeholder="有效开始时间"></input>
				    </div>
				  </div>
				  <div class="form-group" id="div-time-end" style="display: none;">
				    <label for="add-entity-time_end" class="col-sm-2 control-label">有效结束时间</label>
				    <div class="col-sm-6">
				      <input class="form-control form_datetime" id="add-entity-time_end" placeholder="有效结束时间"></input>
				    </div>
				 </div>
				  <div class="form-group">
				    <label for="add-entity-comment" class="col-sm-2 control-label">备注</label>
				    <div class="col-sm-6">
				      <input class="form-control" id="add-entity-comment" placeholder="备注"></input>
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
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<h1 class="page-header">交易流水</h1>

<div class="panel panel-primary" style="width:100%;">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
        <input type="hidden" class="form-control" id="entity-search-inout" value="<?php echo $inout; ?>">
      <div class="form-group">
	    <label for="entity-search-FMS_TRANS_NO">交易流水号</label>
	    <input type="text" class="form-control" id="entity-search-FMS_TRANS_NO" placeholder="交易流水号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-seller_name">对方名称</label>
	    <input type="text" class="form-control" id="entity-search-seller_name" placeholder="对方名称">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-s_account">对方帐号</label>
	    <input type="text" class="form-control" id="entity-search-s_account" placeholder="对方帐号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-time">收付款时间</label>
	    <input type="text" class="form-control form_datetime" id="entity-search-time1" placeholder="时间">
	    -
	    <input type="text" class="form-control form_datetime" id="entity-search-time2" placeholder="时间">
	  </div>
	  <div class="form-group" style="display:none;">
	    <label for="entity-search-status">付款状态</label>
	    <select class="form-control" id="entity-search-status">
	      <option value="-1">全部</option>
          <option value="1">成功</option>
          <option value="2">失败</option>
          <option value="3">未知</option>
        </select>
	  </div>
	  <div class="form-group">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn">查询</button>
    	  <button type="button" class="btn btn-default search-clear-btn" id="entity-clear-btn">清空查询条件</button>
	  </div>
	  <div class="form-group" style="display: none;">
    	  <button type="button" class="btn btn-primary" id="entity-export-page-btn">导出当前页</button>
    	  <button type="button" class="btn btn-primary" id="entity-export-all-btn">导出全部</button>
	  </div>
	  <div class="alert alert-danger  search-list-hint" id="search-list-hint"></div>
	  <div class="alert alert-danger search-list-hint" id="operation-entity-hint"></div>
	</div>
  </div>
</div>

<div id="add-button-group" style="padding-bottom: 2px;">
    <?php if(AdminController::isLogin()){ ?>
	<a id="entity-loadList-btn" class="btn btn-primary" href="#">更新流水</a>
	<?php } ?>
</div>

<div  id="entity-list">
<?php echo $entity_list_html; ?>
</div>
