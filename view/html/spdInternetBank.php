<?php 
/**
 */
?>

<!-- <h1 class="page-header">网银支付行名行号表查询</h1> -->

<?php if(strval($isApplyIndex) == '1'){?>
<script src="<?php echo Router::getBaseUrl();?>js/spdInternetBank.js"></script>
<?php }?>
<div class="panel panel-primary">
  <div class="panel-heading">查找</div>
  <div class="panel-body">
    <div class="form-inline">
      <div class="form-group">
	    <label for="entity-search-bankNo">行号</label>
	    <input type="email" class="form-control" id="entity-search-bankNo" placeholder="">
	  </div>
      <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
	    <label for="entity-search-bankName">行名</label>
	    <input type="email" class="form-control" id="entity-search-bankName" placeholder="" style="width: 220px;">
	  </div>
	  <div class="form-group">
	    <label for="entity-search-super_bank_id">行别</label>
	    <select class="form-control" id="entity-search-super_bank_id">
	      <option value="-1">-请选择-</option>
	      <?php 
	      		$super_bank_list = SpdInternetSuperBankController::getSuperBankList(false);
	      		if(is_array($super_bank_list) && !empty($super_bank_list)){
					$option = '';
		      		foreach ($super_bank_list as $super_bank){
		      			$option .= "<option value=". $super_bank['id'] .">". $super_bank['super_bank_name'] ."</option>";
		      		}
		      		echo $option;
	      		}
	      ?> 
        </select>        
	  </div>
	  <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
	    <label for="entity-search-provice_id">省份</label>
	    <select class="form-control" id="entity-search-provice_id">
	      <option value="-1">-请选择-</option>
	      <?php 
	      		$province_list = ProvinceController::getProviceList(false);
	      		if(is_array($province_list) && !empty($province_list)){
					$option = '';
	      			foreach ($province_list as $province){
	      				$option .= "<option value=". $province['id'] .">". $province['province_name'] ."</option>";
	      			}
	      			echo $option;
	      		}	      		
	      ?> 
        </select>
        <label for="entity-search-city_id">城市</label>
	    <select class="form-control" id="entity-search-city_id">
	      <option value="-1">-请选择-</option> 
        </select>
	  </div>
	  <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
	  	  <input type="hidden" id="entity-search-is_apply_index" value="<?php echo $isApplyIndex;?>"/>
    	  <button type="button" class="btn btn-primary" id="entity-search-btn-bank">查询</button>
    	  <button type="button" class="btn btn-default" id="entity-clear-btn-bank">清空查询条件</button>
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
