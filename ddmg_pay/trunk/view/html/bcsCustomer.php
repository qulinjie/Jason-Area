<?php 
/**
 * bcsRegister_list_html
 */
?>
<div class="modal fade" id="add-entity-modal">
	<div class="modal-dialog" style="width: 800px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_customer_title">开户</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
				    <input type="hidden" class="form-control" id="info-customer-id" value=""></input>
				  <div class="form-group">
				    <label for="add-entity-account" class="col-sm-2 control-label">用户登录账号</label>
				    <div class="col-sm-9">
                      <input type="hidden" class="form-control" id="add-entity-account" placeholder=""/>
                      <input type="text" class="form-control" id="add-entity-account_str" placeholder="数据加载中..."/>
                      <div id="div_data_user" class="citydatadiv" style="display: none;width: 560px;max-height: 220px;margin-top: 5px;border-top-style:solid overflow-y: scroll;overflow-x: hidden;"><ul></ul></div>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-bankType" class="col-sm-2 control-label">银行</label>
				    <div class="col-sm-9">
				      <select class="form-control" id="add-entity-bankType">
				            <option value="2">浦发银行</option>
                      </select>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-entity-ACCOUNT_NO" class="col-sm-2 control-label">专属账户</label>
				    <div class="col-sm-9">
				      <select class="form-control" id="add-entity-ACCOUNT_NO">
                      </select>
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

<!-- <h1 class="page-header">开户管理</h1> -->

<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
      <div class="form-group">
	    <label for="entity-search-SIT_NO">专属帐户名</label>
	    <input type="email" class="form-control" id="entity-search-SIT_NO" placeholder="">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-ACCOUNT_NO">专属帐号</label>
	    <input type="email" class="form-control" id="entity-search-ACCOUNT_NO" placeholder="">
	  </div>
	  <div class="form-group" style="display: none;">
	    <label for="entity-search-time1">注册时间</label>
	    <input type="email" class="form-control form_datetime" id="entity-search-time1" placeholder="注册时间">
	    -
	    <input type="email" class="form-control form_datetime" id="entity-search-time2" placeholder="注册时间">
	  </div>
	  <div class="form-group" style="display: none;">
	    <label for="entity-search-status">客户状态</label>
	    <select class="form-control" id="entity-search-status">
	      <option value="-1">全部</option>
	      <option value="1">已注册</option>
	      <option value="2">已签约</option>
	      <option value="3">已注销</option>
        </select>
	  </div>
	  <div class="form-group">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn">查询</button>
    	  <button type="button" class="btn btn-default" id="entity-clear-btn">清空查询条件</button>
	  </div>
	  <div class="alert alert-danger search-list-hint" id="search-entity-hint"></div>
	  <div class="alert alert-danger search-list-hint" id="operation-entity-hint"></div>
	</div>
  </div>
</div>

<div id="add-button-group" style="padding-bottom: 2px;">
	<a id="add-entity-new" class="btn btn-primary" href="#" data-toggle="modal" data-keyboard="false" data-backdrop="static">新增</a>
	<a id="entity-loadList-btn" class="btn btn-primary" href="#">更新浦发账户</a>
</div>

<div  id="entity-list">
<?php echo $entity_list_html; ?>
</div>
