<?php 
/**
 * bcsInflow_list_html
 */
?>
<h1 class="page-header">银行出入金</h1>

<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
      <div class="form-group">
	    <label for="entity-search-SIT_NO">席位号</label>
	    <input type="email" class="form-control" id="entity-search-SIT_NO" placeholder="席位号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-MCH_TRANS_NO">交易流水号</label>
	    <input type="email" class="form-control" id="entity-search-MCH_TRANS_NO" placeholder="交易流水号">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-START_DATE">起止时间</label>
	    <input type="email" class="form-control form_datetime" id="entity-search-START_DATE" placeholder="开始时间">
	    -
	    <input type="email" class="form-control form_datetime" id="entity-search-END_DATE" placeholder="结束时间">
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

<div  id="entity-list">
<?php echo $bcsInflow_list_html; ?>
</div>
