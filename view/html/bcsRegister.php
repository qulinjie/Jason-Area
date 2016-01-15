<?php 
/**
 * bcsRegister_list_html
 */
?>
<div class="modal fade" id="add-bcsRegister-modal">
	<div class="modal-dialog" style="width: 800px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="info_bcsRegister_title">虚拟卡号</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
				    <input type="hidden" class="form-control" id="info-bcsRegister-id" value=""></input>
				  <div class="form-group">
				    <label for="add-bcsRegister-tel" class="col-sm-2 control-label">手机号</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="add-bcsRegister-tel" placeholder="手机号"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-bcsRegister-name" class="col-sm-2 control-label">姓名</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="add-bcsRegister-name" placeholder="姓名"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-bcsRegister-time" class="col-sm-2 control-label">通话时间</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="add-bcsRegister-time" placeholder="通话时间"></input>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-bcsRegister-status" class="col-sm-2 control-label">状态</label>
				    <div class="col-sm-9">
				      <select class="form-control" id="add-bcsRegister-status">
                	      <option value="1">记录</option>
                	      <option value="2">待处理</option>
                	      <option value="3">处理完成</option>
                      </select>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="add-bcsRegister-remark" class="col-sm-2 control-label">备注</label>
				    <div class="col-sm-9">
				      <input class="form-control" id="add-bcsRegister-remark" placeholder="备注"></input>
				    </div>
				  </div>
				  <div class="alert alert-danger" id="add-bcsRegister-hint"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" id="btn-add-bcsRegister">确定</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<h1 class="page-header">注册信息</h1>

<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
      <div class="form-group">
	    <label for="entity-search-SIT_NO">席位号</label>
	    <input type="email" class="form-control" id="entity-search-SIT_NO" placeholder="席位号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-ACCOUNT_NO">虚拟卡号</label>
	    <input type="email" class="form-control" id="entity-search-ACCOUNT_NO" placeholder="虚拟卡号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-time1">注册时间</label>
	    <input type="email" class="form-control form_datetime" id="entity-search-time1" placeholder="注册时间">
	    -
	    <input type="email" class="form-control form_datetime" id="entity-search-time2" placeholder="注册时间">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-status">注册状态</label>
	    <select class="form-control" id="entity-search-status">
	      <option value="-1">全部</option>
	      <option value="1">成功</option>
	      <option value="2">失败</option>
	      <option value="3">处理中</option>
        </select>
	  </div>
	  <div class="form-group">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn">查询</button>
    	  <button type="button" class="btn btn-default" id="entity-clear-btn">清空查询条件</button>
	  </div>
	  <div class="alert alert-danger search-list-hint" id="search-entity-hint"></div>
	</div>
  </div>
</div>

<div  id="entity-list">
<?php echo $entity_list_html; ?>
</div>
