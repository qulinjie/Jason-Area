<?php 
/**
 */
?>

<!-- <h1 class="page-header">网银支付行名行号表查询</h1> -->

<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
      <div class="form-group">
	    <label for="entity-search-bankNo">行号</label>
	    <input type="email" class="form-control" id="entity-search-bankNo" placeholder="">
	  </div>
      <div class="form-group">
	    <label for="entity-search-bankName">行名</label>
	    <input type="email" class="form-control" id="entity-search-bankName" placeholder="" style="width: 335px;">
	  </div>
	  <div class="form-group">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn">查询</button>
    	  <button type="button" class="btn btn-default" id="entity-clear-btn">清空查询条件</button>
	  </div>
	  <div class="alert alert-danger search-list-hint" id="search-entity-hint"></div>
	</div>
  </div>
</div>

<div id="add-button-group" style="padding-bottom: 2px;">
<!-- 	<a id="add-entity-new" class="btn btn-primary" href="#">更新</a> -->
</div>
<div  id="entity-list">
<?php echo $entity_list_html; ?>
</div>
