<?php 
/**
 * tradeRecord.php
 */
?>

<div class="modal fade" id="info-check-modal">
	<div class="modal-dialog" style="width: 1100px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">登记实发</h4>
			</div>
			<div class="modal-body" id="info-check-trade"></div>
			<div class="alert alert-danger" id="info-check-hint"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" class="btn btn-primary" id="btn-add-check">确定</button>
			</div>
		</div>
	</div>
</div>

<!--
<div class="panel panel-primary">
  <div class="panel-body">
    <div class="form-inline">
	  <div class="form-group">
	    <label for="entity-search-order_no">订单号</label>
	    <input type="text" class="form-control" id="entity-search-order_no" placeholder="订单号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-time">添加时间</label>
	    <input type="text" class="form-control form_datetime" id="entity-search-time1" placeholder="添加时间">
	    -
	    <input type="text" class="form-control form_datetime" id="entity-search-time2" placeholder="添加时间">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-order_time">时间</label>
	    <input type="text" class="form-control form_datetime" id="entity-search-order_time1" placeholder="时间">
	    -
	    <input type="text" class="form-control form_datetime" id="entity-search-order_time2" placeholder="时间">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-seller_name">卖家</label>
	    <input type="text" class="form-control" id="entity-search-seller_name" placeholder="卖家">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-seller_conn_name">业务员</label>
	    <input type="text" class="form-control" id="entity-search-seller_conn_name" placeholder="业务员">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-order_sum_amount">金额</label>
	    <input type="text" class="form-control" id="entity-search-order_sum_amount1" placeholder="金额">
	    -
	    <input type="text" class="form-control" id="entity-search-order_sum_amount2" placeholder="金额">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-order_status">状态</label>
	    <select class="form-control" id="entity-search-order_status">
	      <option value="-1">全部</option>
          <option value="1">待付</option>
          <option value="2">已付</option>
          <option value="3">拒付</option>
        </select>
	  </div>
	  <div class="form-group">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn">查询</button>
    	  <button type="button" class="btn btn-default search-clear-btn" id="entity-clear-btn">清空查询条件</button>
	  </div>
	  <div class="form-group">
    	  <button type="button" class="btn btn-primary" id="entity-export-page-btn">导出当前页</button>
    	  <button type="button" class="btn btn-primary" id="entity-export-all-btn">导出全部</button>
	  </div>
	  <div class="alert alert-danger  search-list-hint" id="search-list-hint"></div>
	</div>
  </div>
</div>
-->

<select id="entity-search-order_status" style="display: none;">
  <option value="-1">全部</option>
  <option value="1">待付</option>
  <option value="2">已付</option>
  <option value="3">拒付</option>
</select>

<div class="seek"> 
    <p class="p1">
        <span class="dh">单号<input type="text" id="entity-search-order_no"/></span>
        <span class="sj">时间<input style=" margin-left:6px;" type="text" class="form_datetime" id="entity-search-order_time1"/><b>—</b><input type="text" class="form_datetime" id="entity-search-order_time2"/></span>
        <span class="mj">供应商<input type="text" id="entity-search-seller_name"/></span>
    </p>
    <p class="p2"> 
        <span class="yw">业务员<input type="text" id="entity-search-seller_conn_name"/></span>
        <span class="je">金额<input style=" margin-left:6px;" type="text" id="entity-search-order_sum_amount1"/><b>—</b><input type="text" id="entity-search-order_sum_amount2"/></span>
        <input class="query"  type="button" value="查询" id="entity-search-btn"/>
    </p>
    <div class="alert alert-danger  search-list-hint" id="search-list-hint"></div>    
</div>


<div  id="entity-list">
<?php echo $entity_list_html; ?>
</div>