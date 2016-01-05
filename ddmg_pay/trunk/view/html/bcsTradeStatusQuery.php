<?php if(empty($data_list)){ ?>
<h1 class="page-header">交易状态查询</h1>

<div class="panel panel-primary" style="width: 1000px;">
  <div class="panel-heading">查询</div>
  <div class="panel-body">
    <div class="form-inline">
      <div class="form-group">
	    <label for="entity-search-FMS_TRANS_NO">交易流水号</label>
	    <input type="text" class="form-control" id="entity-search-FMS_TRANS_NO" placeholder="交易流水号" style="width: 330px;"/>
	    <label for="entity-search-FUNC_CODE">0-出入金交易，1-冻结解冻交易，2-现货交易</label>
	    <input type="text" class="form-control" id="entity-search-FUNC_CODE" placeholder="功能号" style="width: 60px;" value="0"/>
	  </div>
	  <div class="form-group">
    	  <button type="button" class="btn btn-primary" id="entity-search-btn">查询</button>
	  </div>
	  <div class="alert alert-danger  search-list-hint" id="search-list-hint"></div>
	</div>
  </div>
</div>

<div  id="entity-list"></div>
<?php } else { ?>
<p/>
资金监管系统交易流水号:<?php echo $data_list['FMS_TRANS_NO'] ?>
<p/>
交易状态:<?php echo $data_list['TRANS_STS'] ?> &nbsp; (备注：1:交易成功；2：交易失败；3：状态未知；4：未找到交易记录)
<p/>
返回码:<?php echo $data_list['OLD_RECODE'] ?>
<p/>
返回信息:<?php echo $data_list['OLD_REMSG'] ?>
<p/>
<?php } ?>