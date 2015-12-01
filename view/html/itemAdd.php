<?php 
/**
 * 添加产品页面
 */
?>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">添加商品</h2>
	</div>
	<div class="col-md-6 text-right">
		  <a href="<?php echo Router::getBaseUrl() ?>item?<?= $queryString ?>" class="btn btn-success text-right">商品列表</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0 " id="cascading">
		<form class="form-horizontal" onsubmit="return checkForm();" action="<?php echo Router::getBaseUrl() ?>item/doAdd" method="POST">

		  <div class="form-group">
			<label for="category_id" class="col-sm-2 control-label">分类</label>
			<div class="col-xs-4">
				<select name="category_id" id="category_id" class="form-control step1">
					<option value>全部</option>
					<!--<option value="2">型材</option>-->
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="product_id" class="col-sm-2 control-label ">品名</label>
			<div class="col-xs-4">
				<select name="product_id" id="product_id" class="form-control step2">
					<option value>全部</option>
					<!--<option value="1">螺纹钢</option>
					<option value="2">螺纹钢</option>-->
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="material_id" class="col-sm-2 control-label">材质</label>
			<div class="col-xs-4">
				<select name="material_id" id="material_id" class="form-control step3">
					<option value>全部</option>
					<!--<option value="1">304</option>
					<option value="2">201</option>-->
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="size_id" class="col-sm-2 control-label">规格</label>
			  <div class="col-xs-2">
				  <input name="size_id" class="form-control"  id="size_id" value="" placeholder="规格" />
			  </div>
		  </div>

		  <div class="form-group">
			<label for="factory_id" class="col-sm-2 control-label">厂家</label>
			<div class="col-xs-4">
				<select name="factory_id" id="factory_id" class="form-control">
					<option value="">全部</option>
					<?php foreach( $factory_list as $factory ) { ?>
					<option value="<?php echo $factory['id'] ?>"><?php echo $factory['name'] ?></option>
					<?php } ?>
					<!--<option value="1">宝钢</option>
					<option value="2">安钢</option>-->
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="city_id" class="col-sm-2 control-label">城市</label>
			<div class="col-xs-4">
				<select name="city_id" id="city_id" class="form-control">
					<option value="">全部</option>
					<?php foreach( $city_list as $city ) { ?>
					<option value="<?php echo $city['id'] ?>"><?php echo $city['name'] ?></option>
					<?php } ?>
					<!--<option value="1">长沙</option>-->
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="price" class="col-sm-2 control-label">单价(元/吨)</label>
			<div class="col-xs-2">
				<input name="price" class="form-control"  id="price" value="" placeholder="每吨多少钱" />
			</div>
		  </div>
		  <div class="form-group">
			<label for="price_type" class="col-sm-2 control-label">价格类型</label>
			<div class="col-xs-4">
				<select name="price_type" id="price_type" class="form-control">
					<option value="">请选择</option>
					<?php foreach( $price_type_list as $k=>$v ) { ?>
					<option value="<?= $k ?>"><?= $v ?></option>
					<?php } ?>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="ton" class="col-sm-2 control-label">单位(吨/件)</label>
			<div class="col-xs-2">
				<input class="form-control" name="ton"  id="ton" value="" placeholder="每件多少吨" />
			</div>
		  </div>
		  <div class="form-group">
			<label for="city_id" class="col-sm-2 control-label">计重类型</label>
			<div class="col-xs-4">
				<select name="count_type" id="count_type" class="form-control">
					<option value="">请选择</option>
					<?php foreach( $count_type_list as $k=>$v ) { ?>
					<option value="<?= $k ?>"><?= $v ?></option>
					<?php } ?>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="ton" class="col-sm-2 control-label">库存</label>
			<div class="col-xs-2">
				<input class="form-control" name="inventory"  id="inventory" value="" placeholder="库存" />
			</div>
		  </div>

		  <div class="form-group">
			<label for="city_id" class="col-sm-2 control-label">交割仓库</label>
			<div class="col-xs-4">
				<select name="delivery_address_id" id="delivery_address_id" class="form-control">
					<option value="">请选择交割仓库</option>
					<?php foreach( $delivery_address_list as $DA ) { ?>
					<option value="<?= $DA['id'] ?>"><?= $DA['name'] ?></option>
					<?php } ?>
				</select>
				
			</div>
		  </div>


		  <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-success">添加商品</button>
			  <button type="reset" class="btn btn-success">重置</button>
			</div>
		  </div>

		</form>
	</div>
</div>


<!-- 提示信息弹层 -->
<div class="modal fade" id="msg-box">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
<!--
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
-->
      <div id="msg-box-content" class="modal-body text-center">
      </div>
<!--
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="<?php echo Router::getBaseUrl();?>js/jquery.cascadingdropdown.min.js"></script>
<script>
$( function(){
	$('#cascading').cascadingDropdown({
		selectBoxes: [
			{
				selector: '.step1',
				//selected: '11',  // 默认
				source: function(request, response) {
					$.getJSON('<?php echo Router::getBaseUrl();?>item/category', {}, function(data) {
						if ( data==[] ) return false;
						var selectOnlyOption = data.length <= 1;
						response($.map(data, function(item, index) {
							return {
								label: item.name, 
								value: item.id,
								selected: selectOnlyOption // Select if only option
							};
						}));
					});
				}, 
			},
			{
				selector: '.step2',
				requires: ['.step1'],
				requireAll: true,
				source: function(request, response) {
					var category_id = $('#category_id').val();
					if ( category_id=='' ) return false;
					$.getJSON('<?php echo Router::getBaseUrl() ?>item/product', {'category_id':category_id}, function(data) {
						if ( data==[] ) return false;
						var selectOnlyOption = data.length <= 1;
						response($.map(data, function(item, index) {
							return {
								label: item.name, 
								value: item.id,
								selected: selectOnlyOption // Select if only option
							};
						}));
					});
				}, 
				onChange: function(event, value, requiredValues){
				//	console.log( event );
				//	console.log( value );
				//	console.log( requiredValues );
				}
			},
			{
				selector: '.step3',
				requires: ['.step1', '.step2'],
				requireAll: true,
				source: function(request, response) {
					var product_id = $('#product_id').val();
					if ( product_id=='' ) return false;
					$.getJSON('<?php echo Router::getBaseUrl() ?>item/material', {'product_id':product_id}, function(data) {
						if ( data==[] ) return false;
						response($.map(data, function(item, index) {
							return {
								label: item.name,
								value: item.id,
								selected: index == 0 // Select first available option
							};
						}));
					});
				},
				onChange: function(event, value, requiredValues){
					// do stuff
				}
			}
		]
	});
});


function checkForm()
{
	var category_id = $('#category_id').val();
	if ( category_id == '' ) {
		$( '#msg-box-content' ).html('请选择分类');
		$('#msg-box').modal('toggle');
		return false;
	}
	var product_id = $('#product_id').val();
	if ( product_id == '' ) {
		$( '#msg-box-content' ).html('请选择品名');
		$('#msg-box').modal('toggle');
		return false;
	}
	var material_id = $('#material_id').val();
	if ( material_id == '' ) {
		$( '#msg-box-content' ).html('请选择材质');
		$('#msg-box').modal('toggle');
		return false;
	}
	var size_id = $('#size_id').val();
	if ( size_id == '' ) {
		$( '#msg-box-content' ).html('请填写规格');
		$('#msg-box').modal('toggle');
		return false;
	}else if(size_id.length>20){
		$( '#msg-box-content' ).html('规格内容太长');
		$('#msg-box').modal('toggle');
		return false;
	}else if(!/^[0-9A-Za-z.*]+$/.test(size_id)){//做个简单过滤
		$( '#msg-box-content' ).html('只能包含数字字母.*');
		$('#msg-box').modal('toggle');
		return false;
	}
	var factory_id = $('#factory_id').val();
	if ( factory_id == '' ) {
		$( '#msg-box-content' ).html('请选择厂家');
		$('#msg-box').modal('toggle');
		return false;
	}
	var city_id = $('#city_id').val();
	if ( city_id == '' ) {
		$( '#msg-box-content' ).html('请选择城市');
		$('#msg-box').modal('toggle');
		return false;
	}
	var price = $('#price').val();
	if ( price == '' ) {
		$( '#msg-box-content' ).html('请填写价格');
		$('#msg-box').modal('toggle');
		return false;
	}
	var price_type = $('#price_type').val();
	if ( price_type == '' ) {
		$( '#msg-box-content' ).html('请选择价格类型');
		$('#msg-box').modal('toggle');
		return false;
	}
	var ton = $('#ton').val();
	if ( ton == '' ) {
		$( '#msg-box-content' ).html('请填写单位重量');
		$('#msg-box').modal('toggle');
		return false;
	}
	var count_type = $('#count_type').val();
	if ( count_type == '' ) {
		$( '#msg-box-content' ).html('请选择计重类型');
		$('#msg-box').modal('toggle');
		return false;
	}
	var inventory = $('#inventory').val();
	if ( inventory == '' ) {
		$( '#msg-box-content' ).html('请填写库存');
		$('#msg-box').modal('toggle');
		return false;
	}
	var delivery_address_id = $('#delivery_address_id').val();
	if ( delivery_address_id == '' ) {
		$( '#msg-box-content' ).html('请选择交货地');
		$('#msg-box').modal('toggle');
		return false;
	}

	return true;
}


</script>

