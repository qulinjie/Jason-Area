<?php 
/**
 * 卖家修改商品页面
 */
?>
<div class="row">
	<div class="col-md-6">
		<h2 class="page-header">修改商品</h2>
	</div>
	<div class="col-md-6 text-right">
		  <a href="<?php echo Router::getBaseUrl() ?>item?<?= $queryString ?>" class="btn btn-success text-right">商品列表</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-md-offset-0 " id="cascading">
		<!--<form class="form-horizontal" action="<?php echo Router::getBaseUrl() ?>item/doEdit" method="POST">-->
		<form class="form-horizontal" onsubmit="return false" action="" method="">
			<input name="id" id="id" value="<?=$item['id'] ?>" type="hidden" />

		  <div class="form-group">
			<label for="category_id" class="col-sm-2 control-label">分类</label>
			<div class="col-xs-4">
				<select name="category_id" id="category_id" class="form-control step1" disabled="disabled" >
					<option value="<?=$item['category_id'] ?>"><?=$item['category_name'] ?></option>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="product_id" class="col-sm-2 control-label ">品名</label>
			<div class="col-xs-4">
				<select name="product_id" id="product_id" class="form-control step2" disabled="disabled" >
					<option value="<?=$item['product_id'] ?>"><?=$item['product_name'] ?></option>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="material_id" class="col-sm-2 control-label">材质</label>
			<div class="col-xs-4">
				<select name="material_id" id="material_id" class="form-control step3" disabled="disabled" >
					<option value="<?=$item['material_id'] ?>"><?=$item['material_name'] ?></option>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="size_id" class="col-sm-2 control-label">规格</label>
			<div class="col-xs-4">
				<select name="size_id" id="size_id" class="form-control step4" disabled="disabled" >
					<option value="<?=$item['size_id'] ?>"><?=$item['size_name'] ?></option>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="factory_id" class="col-sm-2 control-label">厂家</label>
			<div class="col-xs-4">
				<select name="factory_id" id="factory_id" class="form-control" disabled="disabled" >
					<option value="<?=$item['factory_id'] ?>"><?=$item['factory_name'] ?></option>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="city_id" class="col-sm-2 control-label">城市</label>
			<div class="col-xs-4">
				<select name="city_id" id="city_id" class="form-control" disabled="disabled" >
					<option value="<?=$item['city_id'] ?>"><?=$item['city_name'] ?></option>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="price" class="col-sm-2 control-label">单价(元/吨)</label>
			<div class="col-xs-2">
				<input name="price" class="form-control"  id="price" value="<?=$item['price'] ?>" placeholder="每件多少钱" />
			</div>
		  </div>

		  <div class="form-group">
			<label for="city_id" class="col-sm-2 control-label">价格类型</label>
			<div class="col-xs-4">
				<select disabled="disabled" name="price_type" id="price_type" class="form-control">
					<?php if(!$price_type_list){}else foreach( $price_type_list as $k=>$v ) { ?>
					<option  value="<?= $k ?>" <?php if( $item['price_type']==$k ) echo 'selected="selected"' ?>><?= $v ?></option>
					<?php } ?>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="ton" class="col-sm-2 control-label">单位(吨/件)</label>
			<div class="col-xs-2">
				<input class="form-control" name="ton"  id="ton" value="<?=$item['ton'] ?>" placeholder="每件多少吨" />
			</div>
		  </div>
		  <div class="form-group">
			<label for="city_id" class="col-sm-2 control-label">计重类型</label>
			<div class="col-xs-4">
				<select  disabled="disabled" name="count_type" id="count_type" class="form-control">
					<?php foreach( $count_type_list as $k=>$v ) { ?>
					<option value="<?= $k ?>" <?php if( $item['count_type']==$k ) echo 'selected="selected"' ?>><?= $v ?></option>
					<?php } ?>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<label for="ton" class="col-sm-2 control-label">库存</label>
			<div class="col-xs-2">
				<input class="form-control" name="inventory"  id="inventory" value="<?= $item['inventory'] ?>" placeholder="库存" />
			</div>
		  </div>

		  <div class="form-group">
			<label for="delivery_point" class="col-sm-2 control-label">交割仓库</label>
			<div class="col-xs-4">
				<select class="form-control" name="delivery_point" id="delivery_point">
					<option value="">请选择交割仓库</option>
					<?php foreach($address_list as  $val){?>
						<option <?php if($val['name']==$item['delivery_point']) echo 'selected';?>><?php echo $val['name'];?></option>
					<?php }?>
				</select>
			</div>
		  </div>

		  <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" id="confirm-change-btn" class="btn btn-success">确认修改</button>
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
		<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
		<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
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


<script>
$('#confirm-change-btn').click( function() {
	var id = $('#id').val();
	if ( id == '' ) {
		$('#msg-box-content').html( '缺失ID');
		$('#msg-box').modal('show');
		return false;
	}
	var price = $('#price').val();
	if ( price == '' ) {
		$('#msg-box-content').html( '请填写价格');
		$('#msg-box').modal('show');
		return false;
	}
	var ton = $('#ton').val();
	if ( ton == '' ) {
		$('#msg-box-content').html( '请填件重');
		$('#msg-box').modal('show');
		return false;
	}
	var inventory = $('#inventory').val();
	if ( inventory == '' ) {
		$('#msg-box-content').html( '请填写库存');
		$('#msg-box').modal('show');
		return false;
	}
	var delivery_point = $('#delivery_point').val();
	if ( delivery_point == '' ) {
		$('#msg-box-content').html( '请填写交割地');
		$('#msg-box').modal('show');
		return false;
	}
	var data		= {};
	data.id			= id;
	data.ton		= ton;
	data.price		= price;
	data.inventory	= inventory;
	data.delivery_point	= delivery_point;

	$.post(BASE_PATH + 'item/doEdit', data,
			function(data) {
				if(data.code != 0) 
				{ // 失败
					$('#msg-box-content').html( '修改失败');
					$('#msg-box').modal('show');
				}
				else
				{ // 成功
					$('#msg-box-content').html( '修改成功');
					$('#msg-box').modal('show');
					setTimeout(function(){  // 1秒后自动关闭弹层
							$('#msg-box').modal('hide');
							window.location = BASE_PATH+'item?'+'<?= $queryString ?>';
					},1000);

				}
			},
			'json'
		);



} );
</script>
